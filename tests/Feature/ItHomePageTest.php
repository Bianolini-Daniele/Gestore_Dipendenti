<?php

namespace Tests\Feature;

use App\Http\Controllers\AnagraficaController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\DotazioneController;
use App\Http\Controllers\HomeController;
use App\Models\Anagrafica;
use App\Models\Documento;
use App\Models\Dotazione;
use Illuminate\Http\Request;
use Tests\TestCase;

class ItHomePageTest extends TestCase
{
    public function test_it_homepage_filters_requests_by_type_and_employee_state(): void
    {
        $anagrafica = Anagrafica::create([
            'nome' => 'Mario',
            'cognome' => 'Rossi',
            'codice_fiscale' => 'RSSMRA80A01H501U',
            'stato_dipendente' => 'on_boarding',
        ]);

        Documento::create([
            'anagrafica_id' => $anagrafica->id,
            'nome' => 'Contratto',
            'tipo_documento' => 'Contratto',
            'stato' => 'richiesta',
            'urgenza' => 'alta',
            'risolto' => false,
            'stato_richiesta' => 'non_risolta',
            'responsabilita' => 'IT',
            'file_path' => 'test.pdf',
        ]);

        Dotazione::create([
            'anagrafica_id' => $anagrafica->id,
            'tipo_dotazione' => 'Laptop',
            'stato' => 'richiesta',
            'urgenza' => 'media',
            'risolto' => false,
            'stato_richiesta' => 'non_risolta',
            'responsabilita' => 'IT',
        ]);

        $request = new Request([
            'tipo' => 'documenti',
            'stato_dipendente' => 'on_boarding',
        ]);

        $view = app(HomeController::class)->it($request);
        $richieste = $view->getData()['richieste'];

        $this->assertCount(1, $richieste);
        $this->assertSame('documento', $richieste->first()['tipo_richiesta']);
        $this->assertSame('Contratto', $richieste->first()['richiesta']);
    }

    public function test_it_homepage_filters_requests_by_urgency_and_employee_name(): void
    {
        $mario = Anagrafica::create([
            'nome' => 'Mario',
            'cognome' => 'Rossi',
            'codice_fiscale' => 'RSSMRA80A01H501Z',
            'stato_dipendente' => 'on_boarding',
        ]);

        $luigi = Anagrafica::create([
            'nome' => 'Luigi',
            'cognome' => 'Verdi',
            'codice_fiscale' => 'VRDLCU80A01H501A',
            'stato_dipendente' => 'attivo',
        ]);

        Documento::create([
            'anagrafica_id' => $mario->id,
            'nome' => 'Contratto urgente',
            'tipo_documento' => 'Contratto',
            'stato' => 'richiesta',
            'urgenza' => 'alta',
            'risolto' => false,
            'stato_richiesta' => 'non_risolta',
            'responsabilita' => 'IT',
            'file_path' => 'test.pdf',
        ]);

        Documento::create([
            'anagrafica_id' => $luigi->id,
            'nome' => 'Contratto basso',
            'tipo_documento' => 'Contratto',
            'stato' => 'richiesta',
            'urgenza' => 'bassa',
            'risolto' => false,
            'stato_richiesta' => 'non_risolta',
            'responsabilita' => 'IT',
            'file_path' => 'test.pdf',
        ]);

        $request = new Request([
            'urgenza' => 'alta',
            'search' => 'mario',
        ]);

        $view = app(HomeController::class)->it($request);
        $richieste = $view->getData()['richieste'];

        $this->assertCount(1, $richieste);
        $this->assertSame('Contratto urgente', $richieste->first()['richiesta']);
    }

    public function test_hr_search_redirects_to_employee_detail_when_one_match_is_found(): void
    {
        $anagrafica = Anagrafica::create([
            'nome' => 'Marco',
            'cognome' => 'Neri',
            'codice_fiscale' => 'NRIMRC80A01H501B',
            'stato_dipendente' => 'dipendente',
        ]);

        $response = app(AnagraficaController::class)->index(new Request(['search' => 'Marco']));

        $this->assertTrue($response->isRedirect());
        $this->assertSame(route('anagrafiche.show', $anagrafica), $response->getTargetUrl());
    }

    public function test_status_update_redirects_to_the_current_area_homepage(): void
    {
        $anagrafica = Anagrafica::create([
            'nome' => 'Paolo',
            'cognome' => 'Bianchi',
            'codice_fiscale' => 'BNCPLO80A01H501X',
            'stato_dipendente' => 'attivo',
        ]);

        $documento = Documento::create([
            'anagrafica_id' => $anagrafica->id,
            'nome' => 'Richiesta da aggiornare',
            'tipo_documento' => 'Richiesta da aggiornare',
            'stato' => 'richiesta',
            'urgenza' => 'media',
            'risolto' => false,
            'stato_richiesta' => 'non_risolta',
            'responsabilita' => 'Admin',
            'file_path' => 'test.pdf',
        ]);

        $this->withSession(['area_accesso' => 'Admin']);

        $response = app(DocumentoController::class)->risolvi(new Request(['stato_richiesta' => 'risolta']), $anagrafica, $documento);

        $this->assertTrue($response->isRedirect());
        $this->assertSame(route('home.admin'), $response->getTargetUrl());
    }

    public function test_document_detail_page_is_available_for_non_hr_users(): void
    {
        $anagrafica = Anagrafica::create([
            'nome' => 'Paolo',
            'cognome' => 'Bianchi',
            'codice_fiscale' => 'BNCPLO80A01H501X',
            'stato_dipendente' => 'attivo',
        ]);

        $documento = Documento::create([
            'anagrafica_id' => $anagrafica->id,
            'nome' => 'Scheda dettaglio',
            'tipo_documento' => 'Scheda dettaglio',
            'stato' => 'richiesta',
            'urgenza' => 'media',
            'risolto' => false,
            'stato_richiesta' => 'non_risolta',
            'responsabilita' => 'IT',
            'file_path' => 'test.pdf',
        ]);

        $view = app(DocumentoController::class)->show($documento);

        $this->assertSame('documenti.show', $view->getName());
        $this->assertSame($documento->id, $view->getData()['documento']->id);
    }

    public function test_dotazione_detail_page_is_available_for_non_hr_users(): void
    {
        $anagrafica = Anagrafica::create([
            'nome' => 'Giulia',
            'cognome' => 'Rossi',
            'codice_fiscale' => 'RSSGLA80A01H501Y',
            'stato_dipendente' => 'attivo',
        ]);

        $dotazione = Dotazione::create([
            'anagrafica_id' => $anagrafica->id,
            'tipo_dotazione' => 'Mouse',
            'stato' => 'richiesta',
            'urgenza' => 'media',
            'risolto' => false,
            'stato_richiesta' => 'non_risolta',
            'responsabilita' => 'Admin',
        ]);

        $view = app(DotazioneController::class)->show($dotazione);

        $this->assertSame('dotazioni.show', $view->getName());
        $this->assertSame($dotazione->id, $view->getData()['dotazione']->id);
    }

    public function test_admin_homepage_shows_only_admin_responsibility_requests(): void
    {
        $anagrafica = Anagrafica::create([
            'nome' => 'Anna',
            'cognome' => 'Bianchi',
            'codice_fiscale' => 'BNCNNA80A01H501V',
            'stato_dipendente' => 'attivo',
        ]);

        Documento::create([
            'anagrafica_id' => $anagrafica->id,
            'nome' => 'Licenza',
            'tipo_documento' => 'Licenza',
            'stato' => 'richiesta',
            'urgenza' => 'bassa',
            'risolto' => false,
            'stato_richiesta' => 'non_risolta',
            'responsabilita' => 'Admin',
            'file_path' => 'test.pdf',
        ]);

        Documento::create([
            'anagrafica_id' => $anagrafica->id,
            'nome' => 'Altro documento',
            'tipo_documento' => 'Altro documento',
            'stato' => 'richiesta',
            'urgenza' => 'bassa',
            'risolto' => false,
            'stato_richiesta' => 'non_risolta',
            'responsabilita' => 'IT',
            'file_path' => 'test.pdf',
        ]);

        $view = app(HomeController::class)->admin(new Request());
        $richieste = $view->getData()['richieste'];

        $this->assertCount(1, $richieste);
        $this->assertSame('Licenza', $richieste->first()['richiesta']);
    }

    public function test_altro_homepage_shows_only_altri_responsibility_requests(): void
    {
        $anagrafica = Anagrafica::create([
            'nome' => 'Luca',
            'cognome' => 'Verdi',
            'codice_fiscale' => 'VRDLCU80A01H501W',
            'stato_dipendente' => 'attivo',
        ]);

        Documento::create([
            'anagrafica_id' => $anagrafica->id,
            'nome' => 'Richiesta Altri',
            'tipo_documento' => 'Richiesta Altri',
            'stato' => 'richiesta',
            'urgenza' => 'media',
            'risolto' => false,
            'stato_richiesta' => 'non_risolta',
            'responsabilita' => 'Altri',
            'file_path' => 'test.pdf',
        ]);

        Documento::create([
            'anagrafica_id' => $anagrafica->id,
            'nome' => 'Richiesta IT',
            'tipo_documento' => 'Richiesta IT',
            'stato' => 'richiesta',
            'urgenza' => 'media',
            'risolto' => false,
            'stato_richiesta' => 'non_risolta',
            'responsabilita' => 'IT',
            'file_path' => 'test.pdf',
        ]);

        $view = app(HomeController::class)->altro(new Request());
        $richieste = $view->getData()['richieste'];

        $this->assertCount(1, $richieste);
        $this->assertSame('Richiesta Altri', $richieste->first()['richiesta']);
    }
}
