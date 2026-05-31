<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function viewLogin(){
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (auth()->user()->hasRole('admin'))
            {
                return redirect()->intended('/admin-dashboard');
            }
            elseif(auth()->user()->hasRole('guru'))
            {
                return redirect()->intended('/guru-dashboard');
            }
            elseif(auth()->user()->hasRole('siswa'))
            {
                return redirect()->intended('/siswa-dashboard');
            }
        }

        return back()->with('error', 'Email atau password salah.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
