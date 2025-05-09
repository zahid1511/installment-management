<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    function index(){
        //return 'index';
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('backend.users.permissions', compact('roles', 'permissions'));
    }
    function store(Request $request){

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
        ]);
        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions')->with('status', [
            'icon' => 'success',
            'message' => 'Permission added successfully!'
        ]);
    }
    function update(Request $request, $roleId){

        $role = Role::findOrFail($roleId);
        // Detach all current permissions
        $role->permissions()->detach();

        // Attach selected permissions
        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }
        return redirect()->back()->with('status', [
            'icon' => 'success',
            'message' => 'Permission updated successfully!'
        ]);
    }
}
