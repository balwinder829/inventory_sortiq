<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;


class AdminUserController extends Controller
{   
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:users.view')->only(['list']);
       $this->middleware('permission:users.create')->only(['create','store']);
       // $this->middleware('permission:all_store.view|my_store.view')->only(['show']);
       
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function superadmin_create()
    {
        return view('admin.superadmin_register');
    }

    public function list()
    {
        $users = User::all();
        //return view('admin.store_create', compact('users'));
        return view('admin.users.index',  compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users',
            'password'        => 'required|min:4|confirmed',
            'is_superadmin'   => 'nullable|boolean',
            'is_active'       => 'required|boolean',
        ]);

       $user = User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'is_superadmin' => $request->has('is_superadmin') ? $validated['is_superadmin'] : false,
            'is_active'     => $validated['is_active'],
        ]);

        if (!$user->is_superadmin) {
            $user->assignRole('manager');
        }
        return redirect()->route('user.list')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'password'      => 'nullable|min:4|confirmed',
            'is_superadmin' => 'nullable|boolean',
            'is_active'     => 'required|boolean',
        ]);

        // Prevent currently logged-in user from deactivating themselves
        if ($user->id === auth()->id() && !$validated['is_active']) {
            return back()->withErrors([
                'is_active' => 'You cannot deactivate your own account.',
            ])->withInput();
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->is_superadmin = $request->has('is_superadmin') ? $validated['is_superadmin'] : false;
        $user->is_active = $validated['is_active'];

        $user->save();

        return redirect()->route('user.list')->with('success', 'User updated successfully.');
    }
}
