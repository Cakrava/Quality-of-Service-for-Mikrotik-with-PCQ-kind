<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class CheckLoginStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah login_status ada dan bernilai true
        if (!session()->has('login_status') || session('login_status') == false) {
            return redirect()->route('auth.login');
        }

        // Cek apakah session 'login_time' ada dan apakah sudah lebih dari 1 menit
        if (session()->has('login_time')) {
            $loginTime = session('login_time');
            $currentTime = now();

            // Jika sudah lebih dari 1 menit
            if ($currentTime->diffInMinutes($loginTime) >= 20) {
                // Hapus session login dan login_time
                session()->forget(['login_status', 'login_time', 'ip', 'user', 'password']);
                return redirect()->route('auth.login');
            }
        }

        return $next($request);
    }
}
