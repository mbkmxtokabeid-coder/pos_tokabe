<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use RealRashid\SweetAlert\Facades\Alert;

class AdminTKB
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (auth()->user()->role === 'Pemilik' || auth()->user()->role === 'Admin') {
            return $next($request);
        }
        Alert::error('Kamu tidak memiliki akses Admin');
        return redirect('/welcomeTKB');
    }
}
