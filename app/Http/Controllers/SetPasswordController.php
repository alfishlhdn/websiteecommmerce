<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SetPasswordController extends Controller
{
    // tampilkan form set password (hanya untuk user yang sudah login)
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('Masuk')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        return view('view-auth.set_password');
    }

    // simpan password baru
    public function update(Request $request)
    {
        $request->validate([
            'password' => 'confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/')->with('success', 'Password berhasil disimpan. Sekarang Anda bisa masuk menggunakan email & password.');
    }

    // optional: jika user klik Lewati
    public function skip()
    {
        return redirect('/')->with('info', 'Anda melewati pengaturan password. Anda masih dapat masuk menggunakan Google. Untuk masuk tanpa Google, gunakan fitur "Lupa Password" nanti.');
    }
}
