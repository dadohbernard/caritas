<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;

class ActivityController extends Controller
{
   
 /**
     * Display a listing of the resource.
     */
    public function viewActivity(Request $request)
    {
        $data['title'] = "Manage Activity";
        $data['addText'] = "Add";
        $data['id'] = $request->id;
      
        return view('manage-activity.activity',$data);
        //
    }
    

     /**
     * Show the form for creating a new resource.
     */
    public function getActivityAjaxView(Request $request){
        $id =  $request->id;
     
        $data = Activity::select('users.first_name','users.last_name','activity_log.*')
        ->rightJoin('users','users.id','activity_log.causer_id')
        ->distinct("users.id")->orderBy("activity_log.created_at",'DESC')
        ->where("activity_log.causer_id",$id)->get();
        return datatables()->of($data)
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
