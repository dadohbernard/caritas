<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
         $authUser = Auth::user();
        $userRole = $authUser->role;
        $data['incomes'] = Income::join('users','users.id','incomes.user_id')
        ->join('incomes_source','incomes_source.id','incomes.income_source')
       ->leftJoin('centers', 'centers.id', '=', 'users.centrale_id')
       ->leftJoin('communities', 'communities.id', '=', 'users.community_id')
        ->select('users.role','users.community_id','users.centrale_id','incomes.id as income_id','centers.id as center_id', 'centers.center_name', 'communities.id as community_id','communities.community_name','incomes_source.id as source_id','incomes_source.name as income_source','incomes.status as statuses','incomes.amount as amount_per_each','users.first_name as user_first_name','users.last_name as user_last_name','incomes.updated_at')->orderBy('incomes.created_at', 'desc')

        ->where(function ($incomes){
            return (auth()->user()->role==2  ?
                $incomes->where('users.community_id', auth()->user()->community_id) : auth()->user()->role==4 || auth()->user()->role ==3  )?$incomes->where('users.centrale_id', auth()->user()->centrale_id):"";
        })
        ->get();
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
            ->where(function ($incomes) use ($authUser, $userRole) {
                // Apply role-based conditions
                if ($userRole == 2) {
                    $incomes->where('users.community_id', $authUser->community_id);
                } elseif ($userRole == 4) {
                    $incomes->where('users.centrale_id', $authUser->centrale_id);
                }
            })
            ->first();

        // Assign the total amount to the data array
        $data['amount'] = $sum->total_amount;
        return response()->json(["msg"=>'success',"data"=>$data,"status"=>200],200);
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
