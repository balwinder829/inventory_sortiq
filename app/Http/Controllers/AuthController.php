<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function showRegister()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => \Illuminate\Support\Facades\Hash::make($request->password),
            'is_superadmin' => true,
            'is_active'     => true,
        ]);

        Auth::login($user);

        return redirect()->intended('/admin/dashboard');
    }

    public function login(Request $request)
    {

    //     try {
    //     $credentials = $request->validate([
    //         'email'    => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     dd($credentials);
    // } catch (ValidationException $e) {
    //     dd('Validation failed', $e->errors());
    // }
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // dd(Auth::attempt($credentials));
        // dd($credentials);
        // echo "<pre>";print_r($credentials);echo "</pre>";die;
        if (Auth::attempt(array_merge($credentials, ['is_active' => true]))) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        // Check if account is deactivated
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user && !$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account is deactivated. Please contact support.',
            ])->withInput();
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

