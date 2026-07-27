<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRuolo
{
    /**
     * Consente l'accesso solo se l'area selezionata in sessione
     * corrisponde al ruolo richiesto (uso: middleware('ruolo:HR')).
     */
    public function handle(Request $request, Closure $next, string $ruolo): Response
    {
        abort_unless(
            session('area_accesso') === $ruolo,
            403,
            "Sezione riservata all'area {$ruolo}."
        );

        return $next($request);
    }
}