<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Support;
use Illuminate\Support\Facades\Validator;
class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $member = Support::join('members','members.id','supports.member_id')->join('categories','categories.id','members.cat_id')
        ->join('users','users.id','members.user_id')
        ->select('users.role','users.community_id','users.centrale_id','supports.id as support_id','supports.reasons','supports.status as statuses','supports.amount','users.first_name as user_first_name','users.last_name as user_last_name','categories.category_name','categories.description as cat_description','members.*')->orderBy('members.created_at', 'desc')
        ->orderBy(function($member){
            $member->selectRaw('CASE
            WHEN members.status = 0 THEN 1
            WHEN members.status = 1 THEN 1
            WHEN members.status = 1 THEN 2
            ELSE 3
        END');
        })->where(function ($member){
            return (auth()->user()->role!=1 && auth()->user()->role !=5  ?
                $member->where('users.community_id', auth()->user()->community_id) : auth()->user()->role==4)?$member->where('users.centrale_id', auth()->user()->centrale_id):"";
        })->get();
        return response()->json(["msg"=>"success","data"=>$member,"status"=>200]);
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
        $validator = Validator::make($request->all(),[
            'reason' => 'required',
            'amount' =>'required',
        ]);
         if ($validator->fails()) {
            $data ['status'] = 401;
            $data ['data'] = 'Validation Error.';
            $data['success']= false;
            $data ['message'] = $validator->errors()->all();
            return response()->json($data);

        }
        $add = Support::create([
            'reasons' =>$request->reason,
            'member_id' => $request->member_id,
            'user_id' => \Auth::user()->id,
            'amount' => $request->amount,
        ]);
       return response()->json(['status' => 201,'message' => "new support provided"]);
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
