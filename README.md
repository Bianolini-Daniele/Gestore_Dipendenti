# Gestione Dipendenti

Webapp di gestione dipendenti progettata per l'azienda **Trescal Italia**.

Realizzata in Laravel (backend PHP + Blade), pensata per essere eseguita in
ambiente XAMPP, con database gestito tramite PHPMyAdmin (MySQL).

## Descrizione

Questa webapp permette di gestire l'anagrafica dei dipendenti aziendali, i loro
documenti (es. carta d'identità, CUD, corsi sicurezza, visite mediche) e le
dotazioni assegnate, tenendo traccia di
scadenze, richieste e stato di ciascun dipendente lungo tutto il suo ciclo di
vita in azienda.

L'accesso è organizzato per aree aziendali: HR, IT, Amministrazione e Altro. Ogni
area ha una propria homepage; l'area HR detiene permessi più ampi
(gestione completa delle anagrafiche, documenti e dotazioni), mentre le altre
aree consultano unicamente le richieste di loro competenza.

Inoltre, il codice è strutturato in modo da rendere facilmente creabili nuovi profili, in caso di futura necessità.

## Funzionalità principali

- **Login per area**: schermata iniziale con selezione dell'area di accesso
  (HR, IT, Amministrazione, Altro), che determina homepage e permessi.
- **Anagrafiche dipendenti** (area HR): creazione, modifica, consultazione ed
  eliminazione di schede dipendente, con dati anagrafici, di residenza,
  contrattuali, patenti e relativi allegati/scadenze.
- **Documenti e dotazioni** collegati a ciascun dipendente (relazione
  one-to-many), con stato (in uso, richiesta, restituito/restituita,
  dismesso/dismessa), livello di urgenza, responsabilità (IT, Amministrazione, Altri) e
  possibilità di allegare/consultare/scaricare file.
- **Homepage per area** (IT, Amministrazione, Altro): elenco filtrabile e ricercabile
  delle richieste di documenti/dotazioni di propria competenza, con filtri
  per tipo, stato richiesta, stato dipendente e urgenza.
- **Gestione stato dipendente**: aggiornamento rapido dello stato (On
  Boarding, Dipendente, Off Boarding) da parte dell'HR.

## Stack tecnico

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Blade, Tailwind CSS 4, Vite
- **Database**: MySQL (via PHPMyAdmin)
- **Ambiente locale**: XAMPP (Apache + MySQL) + Composer + Node.js/npm

## Requisiti

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL/MariaDB + PHP 8.2+)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) e npm

## Struttura del progetto

- app/Models — Anagrafica, Documento, Dotazione, User
- app/Http/Controllers — vari controller per l'accesso,
  per l'homepage (per area), per le anagrafiche, per i documenti e per le dotazioni
- app/Http/Middleware — EnsureAccessoSelezionato (richiede la selezione
  dell'area prima di accedere) ed EnsureRuolo (limita l'accesso alle
  sezioni riservate in base all'area selezionata)
- database/migrations — contiene le migrazioni che definiscono lo schema delle tabelle nel DataBase
- resources/views — viste Blade organizzate per modulo (anagrafiche, documenti, dotazioni, home, auth)

## Note

- L'accesso alle aree non richiede al momento un account
  utente: la scelta dell'area viene salvata in sessione e determina i
  permessi.
- Progetto in sviluppo attivo: struttura e funzionalità sono soggette a
  modifiche ed estensioni.

## Tutorial

In caso di necessità, ecco una spiegazione dei passaggi necessari da svolgere per il corretto funzionamento della webapp:

1) Scarica XAMPP e fai partire Apache e MySQL
2) Apri http://localhost/phpmyadmin/
3) Crea una nuova tabella con lo stesso nome che inserirai all’interno del .env (ricordare sempre di fare il check e controllare bene di avere gli stessi nomi in env e database
5) apri il terminale 
6) php artisan migrate
7) apri il terminale del progetto
8) npx vite oppure npm run dev
9) Facendo click sul link che dovrebbe uscire come risultato, si andrà sulla pagina della webapp. 
