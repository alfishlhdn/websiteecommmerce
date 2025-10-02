<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if (!$user) {
                // Kalau user belum ada, buat user baru
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(40)), // simpan password random biar kolom tidak kosong
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'role' => 'user', // default role
                ]);
            } else {
                // Update token & refresh token
                $user->update([
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                ]);
            }

            Auth::login($user);

            return redirect('/')->with('success',
                'Berhasil login dengan Google! Selamat berbelanja di AGA IT COMPUTER!
                Catatan: Anda login menggunakan akun Google.
                Jika ingin login dengan email & password, silakan gunakan fitur "Lupa Password" untuk membuat password baru.'
            );
        } catch (\Exception $e) {
            return redirect('/Masuk')->with('error', 'Gagal login dengan Google. ' . $e->getMessage());
        }
    }
}
