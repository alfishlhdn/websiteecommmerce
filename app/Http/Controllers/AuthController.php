<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        return view('view-auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin') {
                return redirect()->intended('/dashboard')->with('success', 'Berhasil masuk. Selamat datang kembali!');
            } elseif (Auth::user()->role === 'client') {
                return redirect()->intended('/price-list')->with('success', 'Berhasil masuk. Selamat berbelanja di AGA IT COMPUTER!');
            } else {
                return redirect()->intended('/')->with('success', 'Berhasil masuk.Selamat berbelanja di AGA IT COMPUTER!');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }


    // Tampilkan form register
    public function showRegisterForm()
    {
        return view('view-auth.daftar');
    }

    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'phone' => 'required|regex:/^[0-9]+$/|min:10|max:15'
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect('/Masuk')->with('success', 'Daftar berhasil! Silakan Masuk.');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success','Berhasil Keluar Terimakasih Sudah Berbelanja di AGA IT COMPUTER ;)');
    }
}
