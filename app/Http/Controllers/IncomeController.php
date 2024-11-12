<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use DB;
use Illuminate\Support\Facades\Auth;
class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     $data['title'] = "Manage Grant";

$sum = $userRole = auth()->user()->role;



        $inc = \DB::table('incomes_source')->select('id','name')
 ->where(function ($inc){
            return auth()->user()->role!=2  ?
                $inc->where('incomes_source.id', 3) : "";
        })
        ->get();
        $data['sources'] = $inc;
        return view("manage-income.index",$data);
    }

    public function getIncomeListAjax(Request $request){

        $incomes = Income::join('users','users.id','incomes.user_id')
        ->join('incomes_source','incomes_source.id','incomes.income_source')
       ->leftJoin('centers', 'centers.id', '=', 'users.centrale_id')
       ->leftJoin('communities', 'communities.id', '=', 'users.community_id')
        ->select('users.role','users.community_id','users.centrale_id','incomes.id as income_id','centers.id as center_id', 'centers.center_name', 'communities.id as community_id','communities.community_name','incomes_source.id as source_id','incomes_source.name as income_source','incomes.status as statuses','incomes.amount','users.first_name as user_first_name','users.last_name as user_last_name','incomes.updated_at')->orderBy('incomes.created_at', 'desc')

        ->where(function ($incomes){
            return (auth()->user()->role==2  ?
                $incomes->where('users.community_id', auth()->user()->community_id) : auth()->user()->role==4 || auth()->user()->role ==3  )?$incomes->where('users.centrale_id', auth()->user()->centrale_id):"";
        })
        ->get();

        return datatables()->of($incomes)
        ->addColumn('action', function($incomes){
             $editableColor =(auth()->user()-> role == 1?"": auth()->user()-> role == 4 && $incomes->statuses == 0|| auth()->user()-> role == 5 && $incomes->statuses == 0|| auth()->user()-> role == 3 && $incomes->statuses == 0)?'btn-success':'btn-warning';
            $action = "";
           $disableEdit = (auth()->user()-> role == 1?"": auth()->user()-> role == 4 && $incomes->statuses == 0|| auth()->user()-> role == 5 && $incomes->statuses == 0|| auth()->user()-> role == 3 && $incomes->statuses == 0)? 'modal':'disable-modal';
              $action .= '&nbsp;<span title="Edit support" style="cursor:pointer" class="'.$editableColor.' add-support" data-target="#exampleModal2" data-id="'.$incomes->id.'" data-reason="'.$incomes->reasons.'" data-amount="'.$incomes->amount.'" data-toggle="'.$disableEdit.'" data-support-id="'.$incomes->support_id.'" data-name="'.$incomes->first_name.' '.$incomes->last_name.'";><i class="fa fa-edit" ></i></span></div>';

            return $action;
        })
        ->editColumn('created_by',function($incomes){
        return $incomes->user_first_name.' '.$incomes->user_last_name;
        })
        ->editColumn('status', function($incomes){
            $status = ($incomes->statuses == 1) ? 'checked' : '';
            $disabled = ( $incomes->statuses == 1)? 'disabled' :(($incomes->statuses ==2)?'disabled' : ((auth()->user()->role==2 || auth()->user()->role==1  && $incomes->statuses ==0)? 'disabled' : ''));
            return '<input class="toggle-class" type="checkbox" data-id="'.$incomes->support_id.'" '.$status.'  data-toggle="toggle" data-on="Paid" data-off="'.($incomes->statuses == 0 ?'Wait':'Rejected').'" data-onstyle="success" data-offstyle="'.($incomes->statuses == 0 ? 'default' : 'danger').'" data-url="'.route('manage-support-status') .'"' . $disabled . '>';
        })
         ->editColumn('community_name', function($incomes){

            return  auth()->user()->role == 2 ? $incomes->community_name:'<a href="'.route('manage-income-single',$incomes->community_id).'">'.$incomes->community_name.'</a>';
        })
        ->editColumn('center_name', function($incomes){

            return auth()->user()->role == 2?$incomes->center_name:'<a href="'.route('manage-income-single',$incomes->center_id).'">'.$incomes->center_name.'</a>';
        })
        ->editColumn('share', function($incomes){

    if($incomes->source_id ==1){
     return auth()->user()->role == 2 && $incomes->source_id == 1
    ? $incomes->amount - $incomes->amount
    : (
        auth()->user()->role == 4 && $incomes->source_id == 1
        ? $incomes->amount-$incomes->amount
        : (
            auth()->user()->role == 5 && $incomes->source_id == 1
            ? $incomes->amount * 3 / 4
            : (
                auth()->user()->role == 3 && $incomes->source_id == 1
                ? $incomes->amount - $incomes->amount * 3 / 4
                : ""
            )
        )
    );

              }elseif($incomes->source_id ==2){
  return auth()->user()->role == 2 && $incomes->source_id == 2
    ? $incomes->amount/2
    : (
        auth()->user()->role == 4 && $incomes->source_id == 2
        ? $incomes->amount-$incomes->amount
        : (
            auth()->user()->role == 5 && $incomes->source_id == 2
            ? $incomes->amount /2
            : (
                auth()->user()->role == 3 && $incomes->source_id == 2
                ? $incomes->amount-$incomes->amount
                : ""
            )
        )
    );
              }elseif($incomes->source_id ==3){
                return $incomes->amount;
              }

        })
        ->rawColumns(['action', 'status','share','center_name','community_name'])
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userAuth = \Auth::user()->role;
        $income= Income::create([
            'user_id' => \Auth::user()->id,
            'income_source' => $request->income_source,
            'amount' => $request->amount,
        ]);

         $request->session()->flash('success', "Income is successfully created");
       return response()->json(['status' => 201,'message' => "new support provided"]);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
         $id = $request->id;
        //  print_r($id);
        //  exit;
         $authUser = Auth::user();
         $userRole = $authUser->role;

    $sum = Income::join('users', 'users.id', '=', 'incomes.user_id')
            ->join('incomes_source', 'incomes_source.id', '=', 'incomes.income_source')
            ->leftJoin('centers', 'centers.id', '=', 'users.centrale_id')
            ->leftJoin('communities', 'communities.id', '=', 'users.community_id')
            ->select(
                DB::raw("
                    FLOOR(SUM(
                        CASE
                            WHEN incomes_source.id = 1 THEN
                                CASE
                                    WHEN $userRole = 2 THEN incomes.amount - incomes.amount
                                    WHEN $userRole = 4 THEN incomes.amount - incomes.amount
                                    WHEN $userRole = 5 THEN incomes.amount * 3 / 4
                                    WHEN $userRole = 3 THEN incomes.amount - (incomes.amount * 3 / 4)
                                    ELSE 0
                                END
                            WHEN incomes_source.id = 2 THEN
                                CASE
                                    WHEN $userRole = 2 THEN incomes.amount / 2
                                    WHEN $userRole = 4 THEN incomes.amount - incomes.amount
                                    WHEN $userRole = 5 THEN incomes.amount / 2
                                    WHEN $userRole = 3 THEN incomes.amount - incomes.amount
                                    ELSE 0
                                END
                            WHEN incomes_source.id = 3 THEN incomes.amount
                            ELSE 0
                        END
                    )) AS total_amount
                ")
            )
            ->where(function ($incomes) use ($authUser, $userRole, $id) {
                // Apply role-based conditions
                if ($userRole != 1 && $userRole != 5) {
                    $incomes->where('communities.id', $id);
                } elseif ($userRole == 4 || $userRole == 5|| $userRole == 3) {
                    $incomes->where('communities.center_id', $id);
                }
            })
            ->first();

        // Assign the total amount to the data array
        $data['title'] = "Amount of money remain";
        $data['amount'] = $sum->total_amount;
        return view("manage-income.single-income",$data);
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
