<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RolesPermissionsController extends Controller
{
    public function index()
    {
        return view('admin.roles-permissions.index');
    }

    public function getRoles()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    public function getPermissions()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        $role->permissions()->sync($request->permissions);

        return response()->json($role->load('permissions'), 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name
        ]);

        $role->permissions()->sync($request->permissions);

        return response()->json($role->load('permissions'));
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->detach();
        $role->delete();

        return response()->json(null, 204);
    }
}