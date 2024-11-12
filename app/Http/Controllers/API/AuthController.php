<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use Exception;
use Password;
use App\Events\ResetPasswordEvent;
use Illuminate\Auth\Events\PasswordReset;
use App\Rules\MatchOldPassword;
use Spatie\Activitylog\Facades\Activity;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {



      $user = User::join('roles','roles.id','users.role')->select('users.*','roles.name')->where('users.id',auth()->user()->id)->first();
      $domain = config('app.url');
      $response = [
        'user' => [
            "id"=> $user->id,
            "first_name" => $user->first_name." ".$user->last_name,
            "email" =>$user->email,
            "profile_picture" => $user->profile_picture,
            "role" => $user->role,
            "role_name" => $user->name,
            "status" => $user->status,
            "is_deleted" => $user->is_delete,
            ],
'base_url' => $domain,
        'success' => true,
    ];
    activity()
    ->performedOn($user)
    ->withProperties(['by'=>auth()->user()])
    ->causedBy(auth()->user())
    ->log('Profile viewed');
      return response()->json([$response],200);

        //
    }
/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $Authorized = User::where('email', $request->email)->where('status',1)->first();
        $user = User::join('roles','roles.id','users.role')->select('users.*','roles.name')->where('email', $request->email)->first();
        if(!$user){
            activity()
            ->event("Login ")
            ->log('Some one who doesn\'t exist want to log in');
$response = ["message" =>'User does not exist','status'=>404,'success' => false];
return response([$response, 404]);
        }else{
    if ($Authorized) {
        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;

            $response = [
                'user' => [
                    "id"=> $user->id,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "email" =>$user->email,
                    "profile_picture" => $user->profile_picture,
                    "role" => $user->role,
                    "role_name" => $user->name,
                    "status" => $user->status,
                    "is_deleted" => $user->is_delete,
                    ],
                'token' => $token,
                'success' => true,
                'status' => 200,
            ];
            activity()
                      ->performedOn(User::find($user->id))
                      ->withProperties(['by'=>User::find($user->id)])
                      ->causedBy(User::find($user->id))
                      ->event("Logged in")
                      ->log('User logged in');
            return response()->json([$response]);
        } else {
            activity()

                      ->event("Login")
                      ->log('some one add wrong password');
            $response = ["message" => "Password mismatch",'success' => false,"status"=>401];
            return response([$response]);
        }
    } else {

        activity()

        ->event("Login ")
        ->log('someone who doesn \'t allowed to access trying to access account');
        $response = ["message" =>'Your not allowed to access','success' => false,"status"=>401];
        return response([$response, 401]);
    }}
        //
    }
    public function changePassword(Request $request)
    {
        $data = [];
        try
        {
                $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                'new_password' => 'required',
                ]);
            if ($validator->fails()) {
                $data ['status'] = 401;
                $data ['data'] = 'Validation Error.';
                $data ['message'] = $validator->errors()->first();
                return response()->json($data);

            }
            if (!(Hash::check($request->get('password'),auth()->user()->password))) {
                    $data ['status'] = 401;
                    $data ['data'] = "";
                    $data ['message'] = "wrong current password sent.";
                    return response()->json($data);
            }

            if (strcmp($request->get('new_password'), $request->get('password')) == 0) {
                //Current password and new password are same
                $data ['status'] = 401;
                $data ['data'] = "";
                $data ['message'] = "Current password and new password are same";
                return response()->json($data);
            }
                $new_password = Hash::make($request->new_password);

                $updated = User::where('email', $request->email)->update(['password' => $new_password]);
                if($updated) {
                        $data ['status'] = 200;
                        $data ['data'] = "";
                        $data ['message'] = "Updated successfully.";
                } else {
                        $data ['status'] = 500;
                        $data ['data'] = "";
                        $data ['message'] = "Something Went Wrong...";
                }
        }
        catch(Exception $ex)
        {
                $data ['status'] = 500;
                $data ['data'] = 'Something Went Wrong...';
               $data ['message'] = $ex->getMessage();
        }
        return response()->json($data);
    }
    /**forgot password API */
   public function forgetPassword(Request $request)
   {
       $data = [];
       try
       {
           $validator = Validator::make($request->all(), [
               'email' => 'required|email'
           ]);
           if ($validator->fails()) {
                   $data ['status'] = 201;
                   $data ['data'] = 'Validation Error.';
                   $data ['message'] = $validator->errors()->first();
                   return response()->json($data);
           }
        //    $response = Password::sendResetLink($request->only('email'));
        $email = $request->email;

        $user = User::where('email', $email)->first();
        if($user == null)
        {
            $data ['status'] = 401;
            $data ['data'] = "";
            $data ['message'] = "Email is not exist.";
        }else{
        $response = event(new ResetPasswordEvent($email));
           if($response) {
                           $data ['status'] = 200;
                           $data ['data'] = "";
                           $data ['message'] = "Reset password email sent successfully.";
           } else {
                           $data ['status'] = 200;
                           $data ['data'] = "";
                           $data ['message'] = "Unable to send reset password email.";
           }
       }}
       catch(Exception $ex)
       {
               $data ['status'] = 500;
               $data ['data'] = 'Something Went Wrong...';
               $data ['message'] = $ex->getMessage();
       }
       return response()->json($data);
   }
   public function create(Request $request)
   {
       // return $request->token;
       $data['email'] = $request->email;
       $data['token'] = $request->token;


       return view('auth.reset-pass', $data);
   }
 //******************************THIS FUNCTION  FOR RESETTING PASSWORD************************************************/
 public function reset()
 {
     try
     {
         $credentials = request()->validate([
             'email' => 'required|email',
             'token' => 'required|string',
             'password' => 'required|string|confirmed',
          ]);

         $reset_password_status = Password::reset($credentials, function ($user, $password)
         {
             $user->password = bcrypt($password);
             $user->save();
         });

         if ($reset_password_status == Password::PASSWORD_RESET)
         {
             $data['status'] = 200;
             $data['data'] = '';
             $data['message'] = 'Password has been successfully changed';
         }
         else if ($reset_password_status == Password::INVALID_TOKEN)
         {
             $data['status'] = 400;
             $data['data'] = '';
             $data['message'] = 'Invalid token provided';
         }

     }
     catch(Exception $ex)
     {
         $data['status'] = 500;
         $data['data'] = 'Something Went Wrong...';
         $data['message'] = $ex->getMessage();
     }
     return response()->json($data);

 }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request){
    $data = [];
    try
    {
    $validator = Validator::make($request->all(), [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|string|email|max:255',
        'phone_number' => 'required',
    ]);
    if ($validator->fails())
    {
        return response()->json(['errors'=>$validator->errors()->all()], 403);
    }
        $update = User::where('id',auth()->user()->id)->update(
              [
                    "first_name" => $request->first_name,
                    "last_name" => $request->last_name,
                    "email" =>$request->email,
                    "phone_number" => $request->phone_number,
              ]
            );
                  $data ['status'] = 200;
                    $data ['data'] = "";
                    $data ['message'] = "profile updated.";
                    activity()
                      ->performedOn(User::find(auth()->user()->id))
                      ->withProperties(['by'=>auth()->user()])
                      ->causedBy(auth()->user())
                      ->event("update profile")
                      ->log('Profile updated');
                    return response()->json($data);
   }

catch(Exception $ex)
{
        $data ['status'] = 500;
        $data ['data'] = 'Something Went Wrong...';

       $data ['message'] = \Log::debug($$ex->getMessage());
}

return response()->json($data);
   }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
