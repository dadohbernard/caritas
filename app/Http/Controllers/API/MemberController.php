<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Validator;
use Auth;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $member = Member::join('categories','categories.id','members.cat_id')
        ->join('users','users.id','members.user_id')
        ->join('communities','communities.id','users.community_id')
        ->select('communities.community_name','members.id as memberId','users.role','users.first_name as user_first_name','users.last_name as user_last_name','categories.category_name','categories.description as cat_description','members.*')->orderBy('members.updated_at','desc')
        ->where(function ($member){
            return (Auth::user()->role!=1 && Auth::user()->role !=5  ?
            $member->where('users.community_id', Auth::user()->community_id) : Auth::user()->role==4)?$member->where('users.centrale_id', auth()->user()->centrale_id):"";
        })->orderBy(function($member){
            $member->selectRaw('CASE
            WHEN members.status = 0 THEN 1
            WHEN members.status = 1 THEN 1
            WHEN members.status = 1 THEN 2
            ELSE 3
        END');
        })->get();
        return response()->json(["msg"=>'success',"data"=>$member,"status"=>200],200);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'last_name' => 'required',
            'cat_id' =>'required',
            'phone' => 'required|unique:members,phone',
            'address' => 'required',
            'dob' => 'required',
            'description' =>'required',
        ]);
        if ($validator->fails()) {
            $data ['status'] = 401;
            $data ['data'] = 'Validation Error.';
            $data['success']= false;
            $data ['message'] = $validator->errors()->all();
            return response()->json($data);

        }
        $member = new Member();
        $member->first_name = $request->first_name;
        $member->last_name = $request->last_name;
        $member->cat_id = $request->cat_id;
        $member->phone = $request->phone;
        $member->address = $request->address;
        $member->bod = $request->dob;
        $member->description = $request->description;
        $member->user_id = Auth::user()->id;
        ($request->cat_id ==1 ? $member->hospital=$request->hospital:$request->cat_id ==2 )? $member->school_name=$request->school_name && $member->sdms_code=$request->sdms_code : $member->other_support=$request->other_support;
        $member->save();
        return response()->json(["msg"=>'New member created',"status"=>200],200);
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
    public function update(Request $request, string $id)
    {
        //
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
       $member = Member::where('id',$id)->update(['status'=>$status]);
       if($status == 1){
       return response()->json(["msg"=>'Accepted',"status"=>200],200);
       }else{
       return response()->json(["msg"=>'Declined',"status"=>200],200);
    }
   }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
