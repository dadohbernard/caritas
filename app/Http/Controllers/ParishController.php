<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parish;
class ParishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $data['title'] = "Manage Parish";
        $data['addText'] = "Add Parish";

        return view('manage-parish.index', $data);
        //
    }
public function getParishListAjax(Request $request)
    {


         $data = Parish::join('users','users.id','parish.user_id')
         ->select("users.first_name","users.last_name","parish.*")->orderBy('parish.updated_at','DESC')
        -> where(function ($data){
            return auth()->user()->role==3  ?
            $data->where('parish.user_id', auth()->user()->id) : '';
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
        $data['title'] = "Manage parish - Add";
        $data['brVal'] = "Manage parish";
        return view('manage-parish.add', $data);
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
