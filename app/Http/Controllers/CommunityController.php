<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Center;
use Illuminate\Http\Request;
use App\Rules\UniqueCommunity;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = "Manage Community";
        $data['addText'] = "Add Community";

        return view('basic-ecclessial-community.index', $data);
        //
    }
    public function getCommunityListAjax(Request $request)
    {


         $data = Community::join('users','users.id','communities.user_id')->join('centers','centers.id','communities.center_id')->select("users.first_name","users.last_name","centers.center_name","communities.*")->where('communities.is_deleted',0)->orderBy('communities.updated_at','DESC')
        -> where(function ($data){
            return auth()->user()->role!=1 && auth()->user()->role !=5  ?
            $data->where('communities.center_id', auth()->user()->centrale_id) : '';
        })->get();


        return datatables()->of($data)

                        ->addColumn('action', function($data){
                            $action = '<div class="action-btn"><a class="btn-success" title="Edit" href="'.route('manage-community-edit', $data->id) .'"><i class="fa fa-edit"></i></a>';
                            $data->role == "Admin" ? " ": $action .='&nbsp;<span title="Delete" style="cursor:pointer" class=" delete-user btn-dark" data-id="'.$data->id.'" data-url="'.route('manage-community-delete', $data->id) .'"><i class="fa fa-trash"></i></span></div>';

                            return $action;
                        })
                        ->editColumn('created_by',function($data){
                        return $data->first_name.' '.$data->last_name;
                        })
                        ->editColumn('status', function($data){
                            $status = ($data->status == 1) ? 'checked' : '';
                            return '<input class="toggle-class" type="checkbox" data-id="'.$data->id.'" '.$status.'  data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger" data-url="'.route('manage-community-status') .'">';
                        })

                        ->rawColumns(['action', 'status'])
                        ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
 /**
     * add the form for creating a new resource.
     */
    public function add()
    {
        $data['title'] = "Manage community - Add";
        $data['brVal'] = "Manage community";
        $center = Center::where(function ($center)  {
                return auth()->user()->centrale_id ?
                    $center->where('centers.id', auth()->user()->centrale_id) : '';
            })->get();
            $data['centrales']=$center;
        return view('basic-ecclessial-community.add', $data);
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'community_name' => ['required', new UniqueCommunity()],
            'center_id'=>'required',
        ]);

        // Check if a record with the same center_name exists with is_deleted = 1
        $community = Community::where('community_name', $request->community_name)->where('is_deleted', 1)->first();

        if ($community) {
            // Update the is_deleted column to 0 if the record is found
            $community->is_deleted = 0;
            $community->save();
            $request->session()->flash('success', 'Community added successfully');
            return redirect()->route('manage-community')->withInput();
        } else {
            // Insert a new record if no matching record is found
            Community::create([
                'community_name' => $request->community_name,
                'user_id' => \Auth::user()->id,
                'center_id' => $request->center_id,
            ]);
            $request->session()->flash('success', 'Community added successfully');
            return redirect()->route('manage-community')->withInput();
        }
        //
    }
public function viewCommunity(Request $request){
    $data = Community::where('center_id',$request->center_ids)->get();
    return response()->json(['status' => 200, "message"=>"selected",'data'=>$data]);
}
    /**
     * Display the specified resource.
     */
    public function show(Community $community)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Community $community,Request $request)
    {
        $data['info'] = $community::find($request->id);
        $data['title'] = "Manage community - Edit";
        $data['brVal'] = "Manage community";
        $data['centrales'] = Center::all();
        return view('basic-ecclessial-community.edit', $data);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Community $community)
    {
        $request->validate([
            'community_name' => 'required|unique:centers,center_name,'.$request->id,
        ]);
        $center =  Community::find($request->id);
        $center->community_name = $request->community_name;
        $center->center_id = $request->center_id;
        $center->user_id = \Auth::user()->id;
        $center->save();
        if($center){
         $request->session()->flash('success', 'Centrale updated successfully');
        return redirect()->route('manage-community')->withInput();
        }else{
            $request->session()->flash('error', 'Something went wrong');
            return redirect()->route('manage-community')->withInput();
        }
        //
    }
/**
     * This function is used to delete manage CTR Tech
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritas:kwizera
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        if($id)
            return (new Community)->deleteModel($id);
        else
            return false;
    }
     /**
     * This function is used to Active Status update
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritas:kwizera
     */
    public function status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        if($id)
            return (new Community)->updateStatus($id,$status);
        else
            return false;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Community $community)
    {
        //
    }
}
