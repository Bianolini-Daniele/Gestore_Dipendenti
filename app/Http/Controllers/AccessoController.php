<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccessoController extends Controller
{
    /**
     * Aree tra cui l'utente può scegliere in fase di accesso.
     */
    public const AREE = ['HR', 'IT', 'Amministrazione', 'Altro'];

    /**
     * Nome della rotta della homepage associata a ciascuna area.
     */
    public const HOMEPAGE_PER_AREA = [
        'HR' => 'anagrafiche.index',
        'IT' => 'home.it',
        'Amministrazione' => 'home.admin',
        'Altro' => 'home.altro',
    ];

    public static function rottaHomepage(?string $area): string
    {
        return self::HOMEPAGE_PER_AREA[$area] ?? 'login';
    }

    public function show(): View|RedirectResponse
    {
        if (session('area_accesso')) {
            return redirect()->route(self::rottaHomepage(session('area_accesso')));
        }

        return view('auth.login', ['aree' => self::AREE]);
    }

    public function login(Request $request): RedirectResponse
    {
        $dati = $request->validate([
            'area' => ['required', 'string', 'in:' . implode(',', self::AREE)],
        ]);

        $request->session()->put('area_accesso', $dati['area']);
        $request->session()->regenerate();

        return redirect()->route(self::rottaHomepage($dati['area']));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('area_accesso');
        $request->session()->regenerate();

        return redirect()->route('login');
    }
}