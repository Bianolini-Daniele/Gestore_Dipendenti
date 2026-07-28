<?php

namespace App\Http\Middleware;

use App\Models\Anagrafica;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAnagraficaAttiva
{
    /**
     * Blocca qualsiasi azione di modifica/eliminazione se il dipendente
     * legato alla rotta risulta disabilitato.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $anagrafica = $request->route('anagrafica');

        if ($anagrafica instanceof Anagrafica && $anagrafica->isDisabilitato()) {
            abort(403, 'Il dipendente è disabilitato: non è possibile modificarlo.');
        }

        return $next($request);
    }
}