<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AdminUserController extends Controller
{
    public function create()
    {
        return view('admin.register');
    }

    public function superadmin_create()
    {
        return view('admin.superadmin_register');
    }

    public function list()
    {
        $users = User::all();
        //return view('admin.store_create', compact('users'));
        return view('admin.user_list',  compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users',
            'password'        => 'required|min:6|confirmed',
            'is_superadmin'   => 'nullable|boolean',
        ]);

        User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'is_superadmin' => $request->has('is_superadmin') ? $validated['is_superadmin'] : false,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User created.');
    }
}
