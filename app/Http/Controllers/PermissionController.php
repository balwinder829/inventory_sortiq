<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        // 🔐 Admin check
        if (!auth()->user() || !auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized');
        }

        // Get all managers
        $users = User::role('manager')->get();
        // dd($users);
        // Group permissions by menu_group
        $permissions = Permission::orderBy('menu_group_order')
            ->orderBy('menu_item_order')
            ->get()
            ->groupBy('menu_group');

        $selectedUser = null;
        // dd($permissions);
        if ($request->user_id) {
            $selectedUser = User::find($request->user_id);
        }

        return view('admin.permissions.index', compact('users', 'permissions', 'selectedUser'));
    }

    public function store(Request $request)
    {
        // 🔐 Admin check
        if (!auth()->user() || !auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'user_id' => 'required',
            'permissions' => 'array'
        ]);

        $user = User::findOrFail($request->user_id);

        // Assign permissions directly to user
        $user->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Permissions updated');
    }
}