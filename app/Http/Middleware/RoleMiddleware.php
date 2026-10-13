<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
  public function handle(Request $request, Closure $next, $role)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    if (strcasecmp(Auth::user()->role, $role) !== 0) {
        \RealRashid\SweetAlert\Facades\Alert::error('Akses Ditolak', 'Anda tidak memiliki akses ke halaman tersebut.');
        return redirect('/welcomeTKB')->with('error', 'Anda tidak punya akses ke halaman tersebut.');
    }

    return $next($request);
}
}
