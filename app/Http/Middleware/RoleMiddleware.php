<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = Auth::user()->role;
        $hasRole = false;
        foreach ($roles as $r) {
            if (strcasecmp($userRole, trim($r)) === 0) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            \RealRashid\SweetAlert\Facades\Alert::error('Akses Ditolak', 'Anda tidak memiliki akses ke halaman tersebut.');
            return redirect('/welcomeTKB')->with('error', 'Anda tidak punya akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
