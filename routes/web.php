<?php

use App\Http\Controllers\AccessoController;
use App\Http\Controllers\AnagraficaController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\DotazioneController;

Route::get('/login', [AccessoController::class, 'show'])->name('login');
Route::post('/login', [AccessoController::class, 'login'])->name('login.submit');
Route::post('/logout', [AccessoController::class, 'logout'])->name('logout');

Route::middleware('accesso')->group(function () {
    Route::get('/', function () {
        return redirect()->route(AccessoController::rottaHomepage(session('area_accesso')));
    });

    Route::get('/home/it', [HomeController::class, 'it'])->name('home.it');
    Route::get('/home/admin', [HomeController::class, 'admin'])->name('home.admin');
    Route::get('/home/altro', [HomeController::class, 'altro'])->name('home.altro');

    Route::middleware('ruolo:HR')->group(function () {
        Route::resource('anagrafiche', AnagraficaController::class)
            ->parameters(['anagrafiche' => 'anagrafica'])
            ->only(['create', 'store']);

        Route::patch(
            'anagrafiche/{anagrafica}/disabilita',
            [AnagraficaController::class, 'disabilita']
        )->name('anagrafiche.disabilita');

        Route::patch(
            'anagrafiche/{anagrafica}/riattiva',
            [AnagraficaController::class, 'riattiva']
        )->name('anagrafiche.riattiva');

        Route::middleware('anagrafica.attiva')->group(function () {
            Route::resource('anagrafiche', AnagraficaController::class)
                ->parameters(['anagrafiche' => 'anagrafica'])
                ->only(['edit', 'update']);

            Route::patch(
                'anagrafiche/{anagrafica}/stato',
                [AnagraficaController::class, 'updateStato']
            )->name('anagrafiche.stato');

            Route::resource('anagrafiche.documenti', DocumentoController::class)
                ->parameters(['anagrafiche' => 'anagrafica', 'documenti' => 'documento'])
                ->except(['index', 'show']);

            Route::resource('anagrafiche.dotazioni', DotazioneController::class)
                ->parameters(['anagrafiche' => 'anagrafica', 'dotazioni' => 'dotazione'])
                ->except(['index', 'show']);
        });
    });

    Route::get(
        '/anagrafiche/{anagrafica}/documenti/{tipo}',
        [AnagraficaController::class, 'download']
    )->name('anagrafiche.documenti.download');

    Route::get(
        '/anagrafiche/{anagrafica}/documenti/{tipo}/visualizza',
        [AnagraficaController::class, 'visualizza']
    )->name('anagrafiche.documenti.visualizza');

    // Elenco documenti/dotazioni di un dipendente: consultabile da tutte le aree.
    Route::get('anagrafiche/{anagrafica}/documenti', [DocumentoController::class, 'index'])
        ->name('anagrafiche.documenti.index');

    Route::get('documenti/{documento}', [DocumentoController::class, 'show'])
        ->name('documenti.show');

    Route::get('dotazioni/{dotazione}', [DotazioneController::class, 'show'])
        ->name('dotazioni.show');

    Route::get('anagrafiche/{anagrafica}/dotazioni', [DotazioneController::class, 'index'])
        ->name('anagrafiche.dotazioni.index');


    Route::middleware('anagrafica.attiva')->group(function () {
        Route::patch(
            'anagrafiche/{anagrafica}/documenti/{documento}/risolvi',
            [DocumentoController::class, 'risolvi']
        )->name('documenti.risolvi');

        Route::patch(
            'anagrafiche/{anagrafica}/dotazioni/{dotazione}/risolvi',
            [DotazioneController::class, 'risolvi']
        )->name('dotazioni.risolvi');
    });

    Route::get('documenti/{documento}/download', [DocumentoController::class, 'download'])
        ->name('documenti.download');

    Route::get('documenti/{documento}/visualizza', [DocumentoController::class, 'visualizza'])
        ->name('documenti.visualizza');

    // Consultazione elenco e scheda dipendente
    Route::resource('anagrafiche', AnagraficaController::class)
        ->parameters(['anagrafiche' => 'anagrafica'])
        ->only(['index', 'show']);
});