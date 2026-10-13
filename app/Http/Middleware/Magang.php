<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Magang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Tambahkan role lain agar saat route web.php tertimpa (override), 
        $allowedRoles = ['admin', 'pemilik'];

        if (Auth::check() && in_array(strtolower(Auth::user()->role), $allowedRoles)) {
            return $next($request);
        }

        // Jika tidak punya akses, kembalikan ke halaman login
        return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}