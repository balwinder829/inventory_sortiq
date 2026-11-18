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
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
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

