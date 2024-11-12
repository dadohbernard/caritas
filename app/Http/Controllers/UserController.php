<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Center;
use App\Models\Community;
use App\Events\NewUserCreatedEvent;
use App\Events\UserRegisteredEvent;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['role'] = $request->r;
        $data['title'] = "Manage Users";
        $data['addText'] = "Add User";
        $data['centrales'] = Center::all();
        $data['commonuties'] = Community::all();
        $data['roles'] = Role::all();
        return view('manage-users.index', $data);
    }

    /**
     * This function is used to get user list ajax
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:kwizera
     */
    public function getUserListAjax(Request $request)
    {

        $role = $request->role;

         $user = User::leftJoin('centers', 'centers.id', '=', 'users.centrale_id')
    ->leftJoin('communities', 'communities.id', '=', 'users.community_id')
    ->select('users.*', 'centers.center_name', 'communities.community_name')
    ->where(function ($query) use ($request) {
        if ($request->role != "") {
            $query->where('users.role', $request->role);
        }
    })
    ->where(function ($query) {
        $authRole = \Auth::user()->role;
        $authCommunityId = \Auth::user()->community_id;
        $authCentraleId = \Auth::user()->centrale_id;

        if ($authRole == 4) {
            // Role 4 can view users with roles 2 and 3
            $query->whereIn('users.role', [2, 3,4])
             ->where('users.centrale_id', $authCentraleId);
        } elseif ($authRole == 5) {
            // Role 5 can view users with roles 4, 3, and 2
            $query->whereIn('users.role', [2, 3, 4,5])
             ->where('users.centrale_id', $authCentraleId);
        } elseif ($authRole == 6 && $authRole == 1) {
            // Roles 6 and 1 can view users with roles 5, 4, 3, and 2
            $query->whereIn('users.role', [1,2, 3, 4, 5,6])
             ->where('users.centrale_id', $authCentraleId);
        }elseif($authRole == 3){
$query->whereIn('users.role', [2, 3])
             ->where('users.centrale_id', $authCentraleId);
        }
    })
    ->where('users.is_delete', 0)
    ->get();



        return datatables()->of($user)

                        ->addColumn('action', function($user){
                            $action = '<div class="action-btn"><a class="btn-success" title="Edit" href="'.route('manage-user-edit', $user->id) .'"><i class="fa fa-edit"></i></a>';
                            $user->role == auth()->user()->role || $user->role == 1 || $user->role == 5 ? " ": $action .='&nbsp;<span title="Delete" style="cursor:pointer" class=" delete-user btn-dark" data-id="'.$user->id.'" data-url="'.route('manage-user-delete', $user->id) .'"><i class="fa fa-trash"></i></span></div>';

                            return $action;
                        })
                        ->addColumn('activity', function($user){
                            return $action = '<a href ="'.route('activity-view',$user->id).'"><button class="btn btn-success">View Activity</button></a>';
                        })
                        ->editColumn('roles', function ($user) {
                            $row = str_replace('","', ',', $user->getRoleNames());
                            $row = str_replace('"', ' ', $row);
                            $row = str_replace('[', ' ', $row);
                            $row = str_replace(']', ' ', $row);
                            return $row;
                        })
                        ->editColumn('status', function($user){
                            $status = ($user->status == 1) ? 'checked' : '';
                             $disabled = $user->role == auth()->user()->role? 'disabled':'';
                             $dataOnStyle = $user->role == auth()->user()->role ? 'default' : 'success';
                            return '<input class="toggle-class" type="checkbox" data-id="'.$user->id.'" '.$status.'  data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="'. $dataOnStyle.'" data-offstyle="danger" data-url="'.route('manage-user-status') .'"' . $disabled . '>';
                        })
                        ->editColumn('first_name', function($user){
                            return $user->first_name;
                        })
                        ->editColumn('last_name', function($user){
                            return $user->last_name;
                        })
                        ->editColumn('phone_number', function($user){
                            return $user->phone_number;
                        })
                        ->editColumn('email', function($user){
                            return $user->email;
                        })
                        ->rawColumns(['activity','roles','action', 'status'])
                        ->make(true);
    }
    /**
     * This function is used to get add manage user page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:Kwizera
     */
    public function add(Request $request)
    {
        $auth =auth()->user()->role;

         $roles= Role::where(function($query)use ($auth) {
            if ($auth == 4) {
                $query->where('id', 2);
            }elseif($auth == 3){
                $query->whereIn('id',[2,4]);
            }elseif($auth == 5){
                $query->whereIn('id',[2,4,3]);
            }
            elseif($auth == 1){
                $query->whereIn('id',[2,4,3,5]);
            }

        })->get();
        $data['roles']= $roles;
        $centrale = auth()->user()->centrale_id;
       $center = Center::where('id',$centrale) ->get();
         $data['centrales'] = $center;
        $data['commonuties'] = Community::all();
        $data['title'] = "Manage Users - Add";
        $data['brVal'] = "Manage Users";
        return view('manage-users.add', $data);
    }

    /**
     * This function is used to save manage user
     *
     * @param UserCreateRequest $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:Kwizera
     */
    public function save(UserCreateRequest $request)
    {
         $password = Str::random(8);

         $encryptpassword = Hash::make($password);
         $info = $request->all();


         $info['encryptpassword'] = $encryptpassword;
         $info['password'] = $password;
         $info['first_name'] = $request->first_name;
         $info['last_name'] = $request->last_name;
         $info['community_id'] = $request->community_id;
         $info['center_id'] = $request->centrale_id;
         $info['phone_number'] = $request->phone_number;
         $info['email'] = $request->email;
         $info['created_by'] = \Auth::user()->id;
         $info['role'] = $request->role;
         $info['profile_pic'] = null;

         $checkUser= User::where('email',$info['email'])->first();

         if(isset($checkUser))
         {

            $info['id'] = $checkUser->id;
            $info['first_name'] = $request->first_name;
            $info['last_name'] = $request->last_name;
            $info['phone_number'] = $request->phone_number;
            $info['community_id'] = $request->community_id;
            $info['centrale_id'] = $request->centrale_id;
            $info['email'] = $request->email;
            $info['is_delete'] = 0;
            $info['password'] = $password;
            $info['role'] = $request->role;
            $role = Role::findById($request->role);
            if (isset($role->id)&& !empty($checkUser)) {

                $checkUser->assignRole($role->id);
            }


            if((new User)->updateExistUser($info)) {
                $request->session()->flash('success', "User created Successfully.");
                return redirect(route('manage-user'));
            } else {
                $request->session()->flash('error', "Nothing to update (or) unable to update.");
                return redirect(route('manage-user'))->withInput();
            }
         }

        if($request->profile_pic) {
            $directory = public_path().'/users_pic';
            if (!is_dir($directory)) {
                mkdir($directory);
                chmod($directory, 0777);
            }
            $imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('profile_pic')->getClientOriginalName());
            $request->file('profile_pic')->move($directory, $imageName);
            $info['profile_pic'] = 'users_pic/'.$imageName;
        }

        $userId = (new User)->createUser($info);
        $user = user::find($userId);
        // print_r($user->id);
        // exit();
        if($info['status']) {
            event(new UserRegisteredEvent($info));
         }
        if($userId && $user) {


            $role = Role::findById($user->role);
            if (isset($role->id)) {
                $user->assignRole($role->id);
            }

            $request->session()->flash('success', "New User Created Successfully.");
            return redirect(route('manage-user'));
        } else {
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-user'))->withInput();
        }
    }
    /**
     * This function is used to get edit manage user page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:kwizera
     */
    public function edit(Request $request)
    {

        $id = $request->id;
        if(!$id){
            $request->session()->flash('error', "Something Went Wrong!.");
            return redirect(route('manage-user'))->withInput();
        }
        $data['info'] = $info = User::find($id);
        if(!$info) {
            $request->session()->flash('error', "Unable to find user.");
            return redirect(route('manage-user'))->withInput();
        }
        $data['roles'] = Role::all();
        $data['centrales'] = Center::all();
        $data['communities'] = Community::all();
        $data['title'] = "Manage Users - Edit";
        $data['brVal'] = "Manage Users";
        return view('manage-users.edit', $data);
    }


    /**
     * This function is used to update manage user
     *
     * @param UserUpdateRequest $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:kwizera
     */
    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|unique:users,email,'.$request->id,

        ]);
        if ($validator->fails()) {
                    return redirect(route('manage-user-edit', $request->id))
                    ->withErrors($validator)
                    ->withInput();
        }
        $info['first_name'] = $request->first_name;
        $info['last_name'] = $request->last_name;
        $info['name'] = "-";
        $info['email'] = $request->email;
        $info['status'] = $request->status;
        $info['is_delete'] = 0;
        $info['role'] = $request->role;
        $info['id'] = $request->id;
        $info['community_id'] =$request-> community_id;
        $info['centrale_id'] = $request->centrale_id;
        $image_name = $request->hidden_image;
        $info['profile_pic'] = $image_name;
        if($request->profile_pic) {
            $directory = public_path().'/users_pic';
            if (!is_dir($directory)) {
                mkdir($directory);
                chmod($directory, 0777);
            }
            $imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('profile_pic')->getClientOriginalName());
            $request->file('profile_pic')->move($directory, $imageName);
            $info['profile_pic'] = 'users_pic/'.$imageName;
        }
        $user = User::find($request->id);
        $role = Role::findById($user->role);
        // return $request->role;
        if (isset($request->role)) {
            $user->syncRoles($role->name);
        } else {
            $user->syncRoles([]);
        }
        $update=(new User)->updateUser($info);

        if($update) {
            $request->session()->flash('success', "User Updated Successfully.");
            return redirect(route('manage-user'));
        } else {
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-user'))->withInput();
        }
    }
/**
     * This function is used to profile update
     *
     * @param SportsUpdateRequest $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:Kwizera
     */
	public function updateProfile(Request $request) {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|unique:users,email,'.$request->id,

        ]);
        if ($validator->fails()) {
                    return redirect(route('manage-user-edit', $request->id))
                    ->withErrors($validator)
                    ->withInput();
        }
        $info['first_name'] = $request->first_name;
        $info['last_name'] = $request->last_name;
        $info['name'] = "-";
        $info['email'] = $request->email;
        $info['status'] = $request->status;
        $info['phone_number'] =$request->phone_number;
        $info['is_delete'] = 0;
        $info['status']=1;
        $info['role'] = $request->role;
        $info['id'] = $request->id;
        $image_name = $request->hidden_image;
        $info['profile_pic'] = $image_name;
        if($request->profile_pic) {
            $directory = public_path().'/users_pic';
            if (!is_dir($directory)) {
                mkdir($directory);
                chmod($directory, 0777);
            }
            $imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('profile_pic')->getClientOriginalName());
            $request->file('profile_pic')->move($directory, $imageName);
            $info['profile_pic'] = 'users_pic/'.$imageName;
        }
        $user = User::find($request->id);
        $role = Role::findById($user->role);
        if (isset($request->role)) {
            $user->syncRoles($role->name);
        } else {
            $user->syncRoles([]);
        }
        $update=(new User)->updateUserProfile($info);

        if($update) {
            $request->session()->flash('success', "Profile Updated Successfully.");
            return redirect(route('manage-edit-profile'));
        } else {
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-edit-profile'))->withInput();
     }}
    /**
     * This function is used to delete manage user
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:kwizera
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        if($id)
            return (new User)->deleteUser($id);
        else
            return false;
    }
     /**
     * This function is used to Active Status update
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:kwizera
     */
    public function status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        if($id)
            return (new User)->updateStatus($id,$status);
        else
            return false;
    }
    /**
     * This function is used to Active Delete Image
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:kwizera
     */
    public function deleteImage(Request $request)
    {
        $id = $request->id;
        if($id)
            return (new User)->deleteUserImages($id);
        else
            return false;
    }
     /**
     * This function is used to get edit profile
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:Kwizera
     */
    public function editProfile(Request $request)
    {

    	$user = \Auth::user();
    	$id   = $user->id;
        $data['info'] = $info = User::find($id);
        if(!$info) {
            $request->session()->flash('error', "Unable to find user.");
            return redirect(route('manage-edit-profile'))->withInput();
        }
        $data['roles'] = Role::all();
        $data['title'] = "Profile - Edit";
        $data['brVal'] = "Edit Profile";
        return view('manage-users.edit_profile', $data);
    }
}
