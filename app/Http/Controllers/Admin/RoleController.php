<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Role berhasil dibuat']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dibuat');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $request->name]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Role berhasil diperbarui']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diperbarui');
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        // Cek jika user ini adalah Super Admin terakhir dan role barunya bukan Super Admin
        if ($user->hasRole('Super Admin')) {
            $superAdminCount = User::role('Super Admin')->count();
            if ($superAdminCount === 1 && $request->role !== 'Super Admin') {
                return response()->json(['message' => 'Tidak dapat mengubah role satu-satunya Super Admin.'], 422);
            }
        }

        // Hapus semua role yang ada
        $user->roles()->detach();

        // Tambahkan role baru
        $user->assignRole($request->role);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Role user berhasil diperbarui']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role user berhasil diperbarui');
    }

    public function destroy(Role $role)
    {
        if($role->name === 'Super Admin') {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Role Super Admin tidak dapat dihapus'], 400);
            }
            return back()->with('error', 'Role Super Admin tidak dapat dihapus');
        }

        $role->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Role berhasil dihapus']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus');
    }

    // API Methods
    public function getUsersWithRoles()
    {
        $users = User::with('roles')->get();
        return response()->json($users);
    }

    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function getPermissions()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function getRolesPermissions()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return response()->json([
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }
}
