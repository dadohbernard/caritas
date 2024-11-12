<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data['title'] = "Manage Categories";
        $data['addText'] = "Add Category";

        return view('manage-categories.index', $data);
        //
    }
/**
     * This function is used to get user list ajax
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:kwizera
     */
    public function getCategoryListAjax(Request $request)
    {


         $user = Category::join('users','users.id','categories.user_id')->select("users.first_name","users.last_name","categories.*")->where('categories.is_deleted',0)->orderBy('categories.updated_at','DESC')->get();


        return datatables()->of($user)

                        ->addColumn('action', function($user){
                            $action = '<div class="action-btn"><a class="btn-success" title="Edit" href="'.route('manage-category-edit', $user->id) .'"><i class="fa fa-edit"></i></a>';
                            $user->role == "Admin" ? " ": $action .='&nbsp;<span title="Delete" style="cursor:pointer" class=" delete-user btn-dark" data-id="'.$user->id.'" data-url="'.route('manage-category-delete', $user->id) .'"><i class="fa fa-trash"></i></span></div>';

                            return $action;
                        })
                        ->editColumn('created_by',function($user){
                        return $user->first_name.' '.$user->last_name;
                        })
                        ->editColumn('status', function($user){
                            $status = ($user->status == 1) ? 'checked' : '';
                            return '<input class="toggle-class" type="checkbox" data-id="'.$user->id.'" '.$status.'  data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger" data-url="'.route('manage-category-status') .'">';
                        })
                        ->editColumn('description',function($user){
                            return '<button type="button" class="btn btn-primary view-category" data-toggle="modal" data-cat="'.$user->category_name.'" data-id-description="'.$user->description.'" data-target="#exampleModal">
                            Description
                          </button>';
                            })
                        ->rawColumns(['action', 'status','description'])
                        ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function add()
    {
        $data['title'] = "Manage Category - Add";
        $data['brVal'] = "Manage Category";
        return view('manage-categories.add', $data);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'category_name' => 'required|unique:categories,category_name',
            'description' => 'required',
        ]);
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->description = $request->description;
        $category->user_id = auth()->user()->id;
        $category->save();
        if($category){
        $request->session()->flash('success', 'Category added successfully');
        return redirect()->route('manage-category')->withInput();
        }else{
            $request->session()->flash('error', 'Something went wrong');
            return redirect()->route('manage-category')->withInput();
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {

        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,Category $category)
    {
        $data['info'] = $category::find($request->id);
        $data['title'] = "Manage category - Edit";
        $data['brVal'] = "Manage category";
        return view('manage-categories.edit', $data);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validator = $request->validate([
            'category_name' => 'required|unique:categories,category_name,'.$request->id,
            'description' => 'required',
        ]);
        $category = $category::find($request->id);
        $category->category_name = $request->category_name;
        $category->description = $request->description;
        $category->user_id = auth()->user()->id;

        $category->save();
        if($category){
            $request->session()->flash('success', 'Category updated successfully');
            return redirect()->route('manage-category')->withInput();
            }else{
                $request->session()->flash('error', 'Something went wrong');
                return redirect()->route('manage-category')->withInput();
            }

        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
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
            return (new Category)->deleteModel($id);
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
            return (new Category)->updateStatus($id,$status);
        else
            return false;
    }
}
