# TODO: Aggiungere stato "restituzione" per dotazioni e documenti

## 1. Controller - DocumentoController.php ✅
- [x] `store()`: cambiare `$dati['stato'] === 'richiesta'` → `in_array($dati['stato'], Documento::STATI_RICHIESTA_TIPO)`
- [x] `update()`: idem
- [x] `risolvi()`: idem
- [x] `required_if` per responsabilita include 'restituzione'

## 2. Controller - DotazioneController.php ✅
- [x] `validaDati()`: `required_if:stato,richiesta,restituzione`
- [x] `store()`: condizioni per STATI_RICHIESTA_TIPO
- [x] `update()`: idem
- [x] `risolvi()`: idem

## 3. Forms - Documenti ✅
- [x] `_form.blade.php`: JS include 'restituzione'
- [x] `_riga_documento.blade.php` (già gestito dalla logica dello stato)

## 4. Forms - Dotazioni ✅
- [x] `_form.blade.php`: JS include 'restituzione'
- [x] `_riga_dotazione.blade.php` (già gestito dalla logica dello stato)

## 5. Views elenco dipendente ✅
- [x] `documenti/index.blade.php`: badge/bottoni per STATI_RICHIESTA_TIPO
- [x] `dotazioni/index.blade.php`: badge/bottoni per STATI_RICHIESTA_TIPO
- [x] `anagrafiche/show.blade.php`: badge/bottoni per STATI_RICHIESTA_TIPO

## 6. HomeController ✅
- [x] `it()`: `whereIn(['richiesta','restituzione'])`, rimosso stato_dipendente, aggiunto filtro tipo_richiesta
- [x] `admin()`: idem
- [x] `altro()`: idem

## 7. Home views ✅
- [x] `home/it.blade.php`: rimosso colonna stato_dipendente, aggiunta colonna Tipo con badge e filtro
- [x] `home/admin.blade.php`: idem
- [x] `home/altro.blade.php`: idem

