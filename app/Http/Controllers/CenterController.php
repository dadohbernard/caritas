<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;
use App\Rules\UniqueCenterName;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = "Manage Centrale";
        $data['addText'] = "Add Centrale";

        return view('manage-center.index', $data);
        //
    }
    public function getCentraleListAjax(Request $request)
    {


         $data = Center::join('parish','parish.id','centers.parish_id')
         ->join('users','users.id','parish.user_id')
         ->select("users.first_name","users.last_name","centers.*")->where('centers.is_deleted',0)->orderBy('centers.updated_at','DESC')
         -> where(function ($data){
            return auth()->user()->role!=1 && auth()->user()->role !=5 ?
            $data->where('parish_id', auth()->user()->parish_id) : '';
        })->get();


        return datatables()->of($data)

                        ->addColumn('action', function($data){
                            $action = '<div class="action-btn"><a class="btn-success" title="Edit" href="'.route('manage-centrales-edit', $data->id) .'"><i class="fa fa-edit"></i></a>';
                            $data->role == "Admin" ? " ": $action .='&nbsp;<span title="Delete" style="cursor:pointer" class=" delete-user btn-dark" data-id="'.$data->id.'" data-url="'.route('manage-centrales-delete', $data->id) .'"><i class="fa fa-trash"></i></span></div>';

                            return $action;
                        })
                        ->editColumn('created_by',function($data){
                        return $data->first_name.' '.$data->last_name;
                        })
                        ->editColumn('status', function($data){
                            $status = ($data->status == 1) ? 'checked' : '';
                            return '<input class="toggle-class" type="checkbox" data-id="'.$data->id.'" '.$status.'  data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger" data-url="'.route('manage-centrales-status') .'">';
                        })

                        ->rawColumns(['action', 'status'])
                        ->make(true);
    }
    /**
     * add the form for creating a new resource.
     */
    public function add()
    {
        $data['title'] = "Manage centrales - Add";
        $data['brVal'] = "Manage Centrales";
        return view('manage-center.add', $data);
        //
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'center_name' => ['required',new UniqueCenterName()]
        // ]);
        // $center = new Center();
        // $center->center_name = $request->center_name;
        // $center->user_id = \Auth::user()->id;
        // $center->save();
        // Validate the request using the custom rule
    $request->validate([
        'center_name' => ['required', new UniqueCenterName()],
    ]);

    // Check if a record with the same center_name exists with is_deleted = 1
    $center = Center::where('center_name', $request->center_name)->where('is_deleted', 1)->first();

    if ($center) {
        // Update the is_deleted column to 0 if the record is found
        $center->is_deleted = 0;
        $center->save();
        $request->session()->flash('success', 'Centrale added1 successfully');
        return redirect()->route('manage-centrales')->withInput();
    } else {
        // Insert a new record if no matching record is found
        Center::create([
            'center_name' => $request->center_name,
            'user_id' => \Auth::user()->id,
        ]);
        $request->session()->flash('success', 'Centrale added successfully');
        return redirect()->route('manage-centrales')->withInput();
    }


        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Center $center)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Center $center, Request $request)
    {

        $data['info'] = $center::find($request->id);
        $data['title'] = "Manage center - Edit";
        $data['brVal'] = "Manage center";
        return view('manage-center.edit', $data);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Center $center)
    {
        $request->validate([
            'center_name' => 'required|unique:centers,center_name,'.$request->id,
        ]);
        $center =  Center::find($request->id);
        $center->center_name = $request->center_name;
        // $center->user_id = \Auth::user()->id;
        $center->save();
        if($center){
         $request->session()->flash('success', 'Centrale updated successfully');
        return redirect()->route('manage-centrales')->withInput();
        }else{
            $request->session()->flash('error', 'Something went wrong');
            return redirect()->route('manage-centrales')->withInput();
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
            return (new Center)->deleteModel($id);
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
            return (new Center)->updateStatus($id,$status);
        else
            return false;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Center $center)
    {
        //
    }
}
