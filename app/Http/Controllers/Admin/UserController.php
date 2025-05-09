<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{   private $view_path = "backend.users";

    function index(){
        $roles = Role::all();
        return view($this->view_path.'/index', ["roles" => $roles]);
    }

    function getUsers(){

        $users = User::select(['id', 'name', 'email', 'created_at']);

        return DataTables::of($users)
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" class="btn btn-warning btn-circle" data-toggle="modal" data-target="#editUserModal" data-u-email-id="' . $row->email . '" data-user-id="' . $row->id . '"
                            data-user-name="' . $row->name . '">
                        <i class="fa fa-paint-brush"></i>
                    </button>
                    <a href="users/delete/' . $row->id . '" id="delete" class="btn btn-danger btn-circle" onclick="return confirm(\'Are you sure you want to delete this user?\');">
                        <i class="fa fa-trash"></i>
                    </a>
                ';
            })
            ->addColumn('role', function ($row) {
                return $row->getRoleNames()->first() ? $row->getRoleNames()->first() : '';
            })
            ->editColumn('created_at', function ($user) {
                return Carbon::parse($user->created_at)->format('d M Y, h:i A');
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    function store(Request $request){
        //return $request;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','email', 'max:255', 'unique:'.User::class],
            'password' => ['required'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('role')) {
            $roleId = $request->role;
            $user->roles()->attach($roleId);
        }

        return redirect()->route('admin.users')->with('status', [
            'icon' => 'success',
            'message' => 'Data saved successfully!'
        ]);
    }

    function update(Request $request){

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            //'email' => ['required','email', 'max:255', 'unique:'.User::class],
            'email' => ['required','email'],
        ]);

        $user = User::find($request->user_id);
        $user->update([
            "name" => $request->name,
            "email" => $request->email
        ]);
        return redirect()->route('admin.users')->with('status', [
            'icon' => 'success',
            'message' => 'Record updated successfully!',
        ]);
    }

    function delete(User $id){
        $id->delete();
        return redirect()->back()->with('status', [
            'icon' => 'warning',
            'message' => 'Data deleted successfully!'
        ]);

    }

    // ROLE METHODS
    function getRolesIndex(){
        return view($this->view_path.'/roles');
    }

    function getRolesList(){
        $roles = Role::all();

        return DataTables::of($roles)
            ->addColumn('action', function ($row) {
                if ($row->name === 'Admin' ) {
                    return '';
                } else {
                    return '
                        <button type="button" class="btn btn-warning btn-circle" data-toggle="modal" data-target="#editRoleModal" data-role-id="' . $row->id . '" data-role-name="' . $row->name . '"><i class="fa fa-paint-brush"></i></button>
                        <a href="roles/delete/'.$row->id.'" id="delete" class="btn btn-circle btn-danger" onclick="return confirm(\'Are you sure you want to delete this user?\');"><i class="fa fa-trash"></i></a>
                    ';
                }
            })
            ->editColumn('created_at', function ($roles) {
                return Carbon::parse($roles->created_at)->format('d M Y, h:i A');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function addRole(Request $request){
        $request->validate([
            'name' => ['required'],
        ]);
        $role = Role::firstOrCreate(['name' => $request->name]);
        return redirect()->back()->with('status', [
            'icon' => 'success',
            'message' => 'Role added successfully!'
        ]);
    }

    function updateRole(Request $request){

        $request->validate([
            'name' => ['required'],
        ]);
        $role = Role::findOrFail($request->role_id);
        $record = $role->update([
            'name' => $request->name
        ]);
        if($record){
            return redirect()->back()->with('status', [
                'icon' => 'success',
                'message' => 'Record updated successfully!'
            ]);
        }else{
            return redirect()->back()->with('status', [
                'icon' => 'warning',
                'message' => 'Failed to update record!'
            ]);
        }


    }
    function deleteRole(Role $role){
        $role->delete();
        return redirect()->back()->with('status', [
            'icon' => 'warning',
            'message' => 'Data deleted successfully!'
        ]);

    }
}
