<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleAssignmentController extends Controller
{
    function index(){
        $roles = Role::all();
        return view('backend.users.role-assignment',['roles' => $roles]);
    }
    function getUserRoles(){

        $users = User::select(['id', 'name', 'email']);
        return DataTables::of($users)
            ->addColumn('assign_role', function ($row) {
                // if ($row->getRoleNames()->first() === 'Admin' ) {
                //     return '';
                // }else{

                //     return '
                //         <button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#assignRole" data-user-id="' . $row->id . '" data-role-name="' . $row->name . '"><i class="fa fa-paint-brush"></i></button>
                //     ';
                // }
                return '
                        <button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#assignRole" data-user-id="' . $row->id . '" data-role-name="' . $row->name . '"><i class="fa fa-paint-brush"></i></button>
                    ';
            })
            ->addColumn('role', function ($row) {
                return $row->getRoleNames()->first() ? $row->getRoleNames()->first() : '';
            })
            ->rawColumns(['assign_role'])
            ->make(true);
    }

    function assignOrUpdateRole(Request $request){

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);
        $user = User::findOrFail($request->user_id);

        $user->syncRoles([$request->role]);

        $role = Role::findByName($request->role);
        $permissions = $role->permissions;
        $user->syncPermissions($permissions);

        return redirect()->back()->with('status', [
            'icon' => 'success',
            'message' => 'Role assigned successfully!'
        ]);
    }
}
