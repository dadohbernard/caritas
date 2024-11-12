<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = "Manage support";

        return view("manage-support.index",$data);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function getSupportListAjax(Request $request){

        $member = Support::join('members','members.id','supports.member_id')->join('categories','categories.id','members.cat_id')
        ->join('users','users.id','members.user_id')
        ->select('users.role','users.community_id','users.centrale_id','supports.id as support_id','supports.reasons','supports.status as statuses','supports.amount','users.first_name as user_first_name','users.last_name as user_last_name','categories.category_name','categories.description as cat_description','members.*')->orderBy('members.created_at', 'desc')
        ->orderBy(function($member){
            $member->selectRaw('CASE
            WHEN members.status = 1 THEN 0
            WHEN members.status = 0 THEN 1
            WHEN members.status = 2 THEN 2
            ELSE 3
        END');
        })
        ->where(function ($member){
            return (auth()->user()->role!=1 && auth()->user()->role !=5  ?
                $member->where('users.community_id', auth()->user()->community_id) : auth()->user()->role==4)?$member->where('users.centrale_id', auth()->user()->centrale_id):"";
        })->get();
        return datatables()->of($member)
        ->addColumn('action', function($member){
             $editableColor =(auth()->user()-> role == 1?"": auth()->user()-> role == 4 && $member->statuses == 0|| auth()->user()-> role == 5 && $member->statuses == 0|| auth()->user()-> role == 3 && $member->statuses == 0)?'btn-success':'btn-warning';
            $action = "";
           $disableEdit = (auth()->user()-> role == 1?"": auth()->user()-> role == 4 && $member->statuses == 0|| auth()->user()-> role == 5 && $member->statuses == 0|| auth()->user()-> role == 3 && $member->statuses == 0)? 'modal':'disable-modal';
              $action .= '&nbsp;<span title="Edit support" style="cursor:pointer" class="'.$editableColor.' add-support" data-target="#exampleModal2" data-id="'.$member->id.'" data-reason="'.$member->reasons.'" data-amount="'.$member->amount.'" data-toggle="'.$disableEdit.'" data-support-id="'.$member->support_id.'" data-name="'.$member->first_name.' '.$member->last_name.'";><i class="fa fa-edit" ></i></span></div>';

            return $action;
        })
        ->editColumn('created_by',function($member){
        return $member->user_first_name.' '.$member->user_last_name;
        })
        ->editColumn('status', function($member){
            $status = ($member->statuses == 1) ? 'checked' : '';
            $disabled = ( $member->statuses == 1)? 'disabled' :(($member->statuses ==2)?'disabled' : ((auth()->user()->role==2 || auth()->user()->role==1  && $member->statuses ==0)? 'disabled' : ''));
            return '<input class="toggle-class" type="checkbox" data-id="'.$member->support_id.'" '.$status.'  data-toggle="toggle" data-on="Paid" data-off="'.($member->statuses == 0 ?'Wait':'Rejected').'" data-onstyle="success" data-offstyle="'.($member->statuses == 0 ? 'default' : 'danger').'" data-url="'.route('manage-support-status') .'"' . $disabled . '>';
        })
        ->editColumn('description',function($member){
            return '<button type="button" class="btn btn-primary view-category" data-toggle="modal" data-cat="'.$member->first_name.' '.$member->last_name.'" data-id-description="'.$member->reasons.'" data-target="#exampleModal">
            Description
          </button>';
            })
        ->rawColumns(['action', 'status','description'])
        ->make(true);

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'reason' => 'required',
            'amount' =>'required',
        ]);
        $add = Support::create([
            'reasons' =>$request->reason,
            'member_id' => $request->member_id,
            'user_id' => \Auth::user()->id,
            'amount' => $request->amount,
        ]);
        $request->session()->flash('success', "support is successfully created");
       return response()->json(['status' => 201,'message' => "new support provided"]);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Support $support)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Support $support)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Support $support)
    {
        $add = Support::where('id',$request->support_id)->update([

            'reasons' =>$request->reason,
            // 'member_id' => $request->member_id,
            'user_id' => \Auth::user()->id,
            'amount' => $request->amount,
        ]);
        $request->session()->flash('success', "support is successfully updated");
       return response()->json(['status' => 201,'message' => "new support provided"]);
        //
    }
public function status(Request $request)
   {
       $id = $request->id;
       $status = $request->status;
    //    return "id:".$id." status:".$status;
       if($id)
           return (new Support)->updateStatuses($id,$status);
       else
           return false;
   }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Support $support)
    {
        //
    }
}
