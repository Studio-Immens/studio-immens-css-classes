# INTERNAL_DOCUMENTATION.md - ClassyBlocks

## 🆔 Identità del Plugin
**Nome:** ClassyBlocks  
**Tagline:** Aggiungi classi CSS personalizzate ai blocchi Gutenberg con anteprima live.  
**Versione:** 2.1.0 (come definito in `studio-immens-css-classes.php`)  
**Slug WordPress.org:** `studio-immens-css-classes`  
**Text Domain:** `studio-immens-css-classes`  
**Autore:** Studio Immens

---

## 💎 Proposta di Valore

### 📌 Problema Risolto
Gli utenti Gutenberg hanno bisogno di applicare classi CSS personalizzate ai blocchi senza scrivere codice ogni volta. Invece di creare CSS nel tema o aggiungere classi manualmente tramite filtri PHP, ClassyBlocks permette di creare un arsenale di classi riutilizzabili e applicarle con un clic direttamente dall'editor, con anteprima live.

### 🚀 Benefici Chiave
- **Zero attrito in editor:** Seleziona classi con checkbox, non scrivere CSS ogni volta.
- **Anteprima live:** Vedi l'effetto della classe sul blocco prima di salvarlo.
- **Framework CSS inclusi:** Bootstrap, Tailwind, Bulma, UIkit e 5 altri framework già pronti all'uso.

### 🌟 Unique Selling Points (USPs)
- **Archivio centralizzato:** Tutte le classi in un unico posto, non disperse nel tema.
- **Classi con stati:** Crea classi base, `:hover` e `:focus` in un unico pannello.
- **Framework switcher:** Attiva/disattiva framework CSS dall'admin senza modificare il tema.
- **Esportazione completa:** Esporta tutte le classi, impostazioni e config in un file JSON.

---

## 🎯 Marketing & Sales Copy Hooks

### **Target Audience**
- Sviluppatori WordPress che usano Gutenberg.
- Agency con clienti che necessitano di design modulari.
- Freelancer che vogliono velocizzare il workflow di sviluppo.
- Utenti Blocksy, GeneratePress, Astra e temi moderni.

### **Elevator Pitch**
"Smetti di scrivere CSS personalizzato per ogni blocco. ClassyBlocks ti permette di creare la tua libreria di classi CSS e applicarle ai blocchi Gutenberg con un clic — come avere un micro-framework CSS personale sempre pronto."

---

## 📖 Guida all'Utilizzo

### **Installazione e Configurazione**
1. Installa e attiva il plugin da WordPress.org.
2. Vai su **ClassyBlocks** nel menu admin.
3. Crea le tue prime classi CSS (nome + proprietà).
4. Apri un blocco Gutenberg → tab **ClassyBlocks** → seleziona le classi.

### **Funzionalità Core**
- **Crea classi:** Nome, CSS base, `:hover`, `:focus` con editor CodeMirror.
- **Framework CSS:** Attiva Bootstrap, Tailwind, Bulma, Materialize, Pure, UIkit, Spectre, Semantic UI, Foundation.
- **Anteprima live:** Preview in tempo reale mentre scrivi il CSS.
- **Gestione classi:** Ricerca, modifica, duplica, elimina in blocco.
- **Import/Export:** Esporta tutto in JSON, importa da un altro sito.

### **PRO Teaser**
Il free mostra una card "Animazioni PRO" in fondo alla dashboard con demo animate. Nell'editor è presente un tab "Animazioni" con una card promozionale per ClassyBlocks Pro.

---

## 🛠 Riferimento Tecnico

### **Architettura**
```
studio-immens-css-classes/
├── studio-immens-css-classes.php    # Plugin bootstrap + classi core
├── admin/
│   ├── admin-ui.php                  # Dashboard classi
│   ├── settings.php                  # Pagina impostazioni framework
│   └── assistenza.php                # Pagina risorse/plugin
├── assets/
│   ├── admin.css                     # Stili admin
│   ├── admin.js                      # JS admin (CRUD, toast, batch)
│   ├── editor.js                     # Gutenberg panel (two-tab)
│   ├── editor.css                    # Stili editor
│   ├── classyblocks-icon.png         # Icona 128x128
│   ├── classyblocks-icon-20.png      # Icona 20x20 per menu
│   ├── bs-editor.js, bulma-editor.js # Framework-specific editor scripts
│   └── ... (framework editor files)
├── includes/
│   ├── bootstrap.min.css             # CSS framework
│   ├── bulma.min.css, uikit.min.css  # Minified framework CSS
│   └── ... (other framework files)
└── languages/
    ├── studio-immens-css-classes.pot
    ├── studio-immens-css-classes-it_IT.po
    └── studio-immens-css-classes-it_IT.mo
```

### **Specifiche Tecniche**
- **PHP:** Classe `StudioImmens_CSS_Classes` con metodi per AJAX, admin, editor e frontend.
- **CSS:** File CSS generato dinamicamente in `wp-content/uploads/studioimmens_cc/`.
- **JS:** CodeMirror per editing CSS, React/WP Element per pannello Gutenberg.
- **Opzioni DB:** `sicc_css_classes`, `sicc_css_settings`, `sicc_css_tailwind_config`, `sicc_css_save_count`.
- **AJAX Actions:** `sicc_save_css_class`, `sicc_edit_css_class`, `sicc_delete_css_class`, `sicc_get_css_classes`.

### **Hooks & Filters**
- `wp_consent_api_registered_{basename}`: Supporto WP Consent API.
- `sicc_css_settings_group`: Settings group per le opzioni.
- `cb_pro_sslverify`: Filter per bypassare SSL verify in localhost.
- Filtri automatici: `admin_enqueue_scripts`, `enqueue_block_editor_assets`, `wp_head`, `admin_head`.

### **Menu Admin**
```
ClassyBlocks           → toplevel_page_studioimmens-css
├── Animazioni PRO     → classyblocks_page_classyblocks-animations (Pro)
├── Pack PRO           → classyblocks_page_classyblocks-packs (Pro)
├── Risorse            → classyblocks_page_studioimmens-assistenza
├── Settings           → classyblocks_page_studioimmens-css-settings
└── Licenza PRO        → classyblocks_page_classyblocks-license (Pro)
```

### **Licenza**
GPL v2 o successiva. Il plugin è ospitato su WordPress.org.

---

## 🗺 Roadmap
- [ ] Template classi predefiniti (utilities per spaziatura, colori, tipografia).
- [ ] Condivisione classi via URL (cloud pack).
- [ ] Condivisione framework personalizzati dall'utente.

---

## 🔗 Collegamenti
- **WordPress.org:** https://wordpress.org/plugins/studio-immens-css-classes/
- **Plugin URI:** https://studioimmens.com/classyblocks-pro/
- **GitHub:** https://github.com/Studio-Immens/classyblocks
- **Supporto:** https://studioimmens.com/classyblocks-pro/
