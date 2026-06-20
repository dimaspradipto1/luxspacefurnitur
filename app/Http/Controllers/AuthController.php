<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function login()
    {
        return view('layouts.auth.login');
    }

    public function register()
    {
        return view('layouts.auth.register');
    }

    public function loginProcess(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            Alert::success('Login', 'Anda berhasil login')
                ->toToast()
                ->autoClose(2000);
            return redirect()->route('dashboard');
        }
        Alert::error('Login', 'Login gagal')
            ->toToast()
            ->autoClose(2000);
        return redirect()->back();
    }

    public function logout()
    {
        Auth::logout();
        Alert::success('Logout', 'Anda berhasil logout')
            ->toToast()
            ->autoClose(2000);
        return redirect()->route('login');
    }

    public function registerProcess(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Alert::success('Register', 'Anda berhasil register')
            ->toToast()
            ->autoClose(2000);
        Auth::login($user);
        return redirect()->route('dashboard');
    }
}
