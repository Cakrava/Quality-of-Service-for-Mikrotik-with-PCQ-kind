<?php

namespace App\Http\Controllers;

use App\Models\RouterosApi;
use Illuminate\Http\Request;

class AuthLoginController extends Controller
{
   public function index()
{
    // Memeriksa apakah pengguna sudah login
    if (session()->has('login_status') && session('login_status') == true) {
        return redirect()->route('dashboard.dashboard');
    }

    // Cek User-Agent untuk membatasi akses hanya dari PC (sebagai contoh)
    $userAgent = request()->header('User-Agent');

    // Kondisi untuk memeriksa apakah perangkat adalah PC
    if (preg_match('/windows/i', $userAgent)) {
        return view('auth.login');
    } else {
        return abort(403, 'Akses hanya diperbolehkan dari perangkat PC.');
    }
}

public function proses_login(Request $request)
{
    $request->validate([
        'login' => 'required',
        'password' => 'required',
    ], [
        'login.required' => 'Nomor atau email diperlukan.',
        'password.required' => 'Kata sandi diperlukan.',
    ]);

    $ip = 'id-12.hostddns.us:12295';
    $user = $request->login;
    $pass = $request->password;

    $API = new RouterosApi();
    $API->debug(false);

    if ($API->connect($ip, $user, $pass)) {
        // Jika koneksi berhasil, arahkan ke dashboard
        session()->put('ip', $ip);
        session()->put('user', $user);
        session()->put('password', $pass);
        session()->put('login_status', true);

        // Simpan waktu login
        session()->put('login_time', now()); 

        return redirect()->route('dashboard.dashboard');
    } else {
        // Jika koneksi gagal, kembalikan error
        return back()->withErrors(['login' => 'Koneksi gagal. Pastikan Username dan Password benar.']);
    }
}


    public function logout()
    {
        session()->forget('user');
        session()->forget('login_status');
        return redirect()->route('auth.login');
    }

}
