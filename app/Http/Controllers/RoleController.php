<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Exception;
class RoleController extends Controller
{
   /**
	 * This controller method is used to get roles blade
	 *
	 * @return view
	 * @author caritas:Kwizera
	 */
	public function index() {

		$title = "Roles List";
		return view('role.list', compact('title'));
	}

	/**
	 * This controller method is used to get non admin roles
	 *
	 * @param Request $request
	 * @return json
	 * @author caritas:Kwizera
	 */
	public function getDatatable(Request $request) {

		if ($request->ajax()) {
			$user=\Auth::user();
			return datatables()->of(Role::select('id', 'name','status','created_at','updated_at'))
				->addColumn('action', function ($role) use($user) {

					$updateBtn='<a href="' . route('role-edit', ['id' => $role->id]) . '" class="text-dark" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;';

					return ($user->role==1 && $user->delete_feature==1)?$updateBtn.'<span title="Delete" class="delete-role text-danger pointer" data-role-id="' . $role->id . '"><i class="fa fa-trash" aria-hidden="true"></i></span>':$updateBtn;
				})
				->editColumn('status', function($role) use ($user){
					$status = ($role->status == 1) ? 'checked' : '';
					return '<input class="toggle-class" type="checkbox" data-id="'.$role->id.'" '.$status.'  data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger" data-url="'.route('role-status') .'">';
				})
				->addColumn('permissions', function ($role) {
					$row = str_replace('","', ',<br/>', $role->getPermissionNames());
					$row = str_replace('"', ' ', $row);
					return $row;
				})
				->rawColumns(['action', 'permissions','status'])
				->make(true);
		}

	}

	/**
	 * This controller method is used to get edit role blade
	 *
	 * @param Request $request
	 * @return view
	 * @author caritas:Kwizera
	 */
	public function edit(Request $request) {

		$title = 'Edit Role';
		$info = Role::find($request->id);
		if (!$info) {
			abort(404);
			exit;
		}
		$permissions = Permission::get();
		return view('role.edit', compact('title', 'info', 'permissions'));
	}

	/**
	 * This controller method is used to update role data
	 *
	 * @param UpdateRoleRequest $request
	 * @return redirect
	 * @author caritas:Kwizera
	 */
	public function update(Request $request) {

		try {
			$role = Role::find($request->role_id);
			if (!$role) {
				abort(404);
				exit;
			}
			$newRole = Role::where('name', $request->name)->where('id', '!=', $role->id)->get();
			if (count($newRole) > 0) {
				throw new \Exception($request->name . " already exist");
			}

			$role->name = $request->name;
			$role->save();
			if ($request->Permissions) {
				$permissions = Permission::whereIn('id', $request->Permissions)->get();
				$role->syncPermissions($permissions);
			} else {
				foreach ($role->permissions as $key => $value) {
					$role->revokePermissionTo($value);
				}
			}
		} catch (\Exception $e) {
			$error['name'] = [$e->getMessage()];
			throw \Illuminate\Validation\ValidationException::withMessages($error);
		}
		$request->session()->flash('success', 'Role Updated Successfully');
		return redirect(route('role-list'));
	}

	/**
     * This function is used to delete role
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:Kwizera
     */
	public function delete(Request $request) {

		$role = Role::find($request->role_id);
		$users = User::role($role->name)->get();

		if (count($users) == 0) {
			foreach ($role->permissions as $key => $value) {
				$role->revokePermissionTo($value);
			}
			$role->delete();
			$data['status'] = 'success';
			$data['message'] = 'Role Deleted';
		} else {
			$data['status'] = 'error';
			$data['message'] = 'Role assigned to users';
		}
		return $data;
	}
/**
     * This function is used to status record
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:Kwizera
     */
    public function status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $role = Role::find($id);
        $role->status = $status;
        $role->save();
        return response()
                    ->json(['status' => 200, 'message' => "Status changed"]);
    }

	/**
     * This function is used to add role
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author caritas:Kwizera
     */
	public function add(Request $request) {

		$title = 'Add Role';
		$permissions = Permission::get();
		return view('role.add', compact('title', 'permissions'));
	}

	/**
	 * This controller method is used to save role
	 *
	 * @param Request $request
	 * @return redirect
	 * @author caritas:Kwizera
	 */
	public function save(Request $request) {

		try {
			$role = Role::create(['name' => $request->name]);
			if ($request->Permissions) {
				$permissions = Permission::whereIn('id', $request->Permissions)->get();
				$role->syncPermissions($permissions);
			}
		} catch (\Exception $e) {
			$error['name'] = [$e->getMessage()];
			throw \Illuminate\Validation\ValidationException::withMessages($error);
		}
		$request->session()->flash('success', 'Role Added Successfully');
		return redirect(route('role-list'));
	}
}
