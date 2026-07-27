# TODO - Search Behavior Fix

## ✅ Step 1: HomeController.php — Fix Non-HR Search (IT/Admin/Altri) [DONE]
- In `it()`, `admin()`, and `altro()` methods: added `CONCAT_WS` conditions so full name search ("Mario Rossi") also works, alongside existing individual nome/cognome LIKE search.

## ✅ Step 2: AnagraficaController.php — Fix HR Search (Strict Full Name) [DONE]
- Replaced current `nome LIKE '%term%' OR cognome LIKE '%term%'` with logic that requires **all space-separated words** in the search to match either nome or cognome.
- This ensures a specific employee appears only when nome AND cognome are typed.

## ✅ Step 3: Verify [DONE]
- No views, migrations, or routes were changed — only controller logic.
- HR search now correctly requires full nome + cognome for auto-redirect.
- Non-HR search now matches any part of the name, including full name.

