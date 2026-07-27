<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccessoSelezionato
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('area_accesso')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}