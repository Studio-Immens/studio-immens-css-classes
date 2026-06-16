# ClassyBlocks — Custom CSS Classes for Gutenberg Blocks

[![WordPress.org](https://img.shields.io/badge/WordPress.org-studio--immens--css--classes-blue)](https://wordpress.org/plugins/studio-immens-css-classes)
[![Landing Page](https://img.shields.io/badge/Pro_Version-studioimmens.com-blue)](https://studioimmens.com/classyblocks-pro)
[![Version](https://img.shields.io/badge/version-2.3.0-blue)]()
[![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-brightgreen)]()
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple)]()
[![License](https://img.shields.io/badge/license-GPL_v2-green)](https://www.gnu.org/licenses/gpl-2.0.html)

> Create your own set of custom CSS classes, ready to use with one click, to enhance the design of your Gutenberg blocks.

---

## 🔗 Links

- 📦 **WordPress.org:** [wordpress.org/plugins/studio-immens-css-classes](https://wordpress.org/plugins/studio-immens-css-classes)
- ⭐ **Pro Version (ClassyBlocks Pro):** [studioimmens.com/classyblocks-pro](https://studioimmens.com/classyblocks-pro)
- 💻 **GitHub:** [github.com/Immens95/studio-immens-css-classes](https://github.com/Immens95/studio-immens-css-classes)

---

## Overview

ClassyBlocks lets you build your own CSS arsenal inside WordPress and apply classes to Gutenberg blocks with a single click — no more writing custom CSS for every block. With 9 built-in CSS frameworks and live preview, it's like having a personal micro-framework always ready.

### Problem Solved

Gutenberg users need to apply custom CSS classes to blocks without writing code each time. Instead of creating CSS in the theme or adding classes manually via PHP filters, ClassyBlocks provides a centralized library of reusable classes with one-click application and live preview.

---

## Features

### 🎨 Custom CSS Class Creator
- Create classes with **base**, **:hover**, and **:focus** states
- CodeMirror-powered CSS editor with syntax highlighting
- Real-time live preview of your CSS
- Search, edit, duplicate, and bulk delete classes

### 🧩 9 Built-in CSS Frameworks
Toggle frameworks on/off from the admin without modifying your theme:

| Framework | Version |
|-----------|---------|
| Bootstrap | 5.3 |
| Tailwind CSS | 3.x |
| Bulma | 0.9 |
| UIkit | 3.x |
| Materialize | 1.x |
| Pure CSS | 3.x |
| Spectre | 0.5 |
| Semantic UI | 2.x |
| Foundation | 6.x |

### 🖊️ Gutenberg Integration
- Dedicated **ClassyBlocks** panel in the block sidebar
- Checkbox-based class selection for any block
- Live preview on hover — see the effect before saving
- Works with any theme: Blocksy, GeneratePress, Astra, Kadence, and more

### 📦 Import/Export
- Export all classes, settings, and configuration as a single JSON file
- Import from another site to replicate your CSS library
- Perfect for agencies managing multiple client sites

### ⚡ Performance
- Minimal CSS output — classes are only generated for what you create
- Dynamic CSS file in `wp-content/uploads/studioimmens_cc/`
- No complex JavaScript dependencies
- Zero impact on frontend performance when classes aren't used

---

## PRO Version

Upgrade to [ClassyBlocks Pro](https://studioimmens.com/classyblocks-pro) for:

- **20 Scroll-Driven Animations** — fade, slide, zoom, rotate, flip, bounce, blur, pulse, shake (zero JavaScript)
- **Pack Manager** — create, export, and import animation packs as JSON files
- **Surgical Animation Application** — apply animations to individual Gutenberg blocks, not entire sections
- **Live Animation Preview** — see animations in the editor before publishing
- **Auto Updates** — receive new animations and fixes via the built-in updater

---

## Installation

### Via WordPress.org
1. Go to **Plugins → Add New** in your WordPress admin
2. Search for "ClassyBlocks"
3. Click **Install Now** and then **Activate**

### Manual Installation
```bash
git clone https://github.com/Immens95/studio-immens-css-classes.git
```

1. Upload the `studio-immens-css-classes` folder to `/wp-content/plugins/`
2. Activate the plugin
3. Go to **ClassyBlocks** in the admin menu to start creating classes

---

## Usage

### Create a CSS Class
1. Go to **ClassyBlocks** in the WordPress admin menu
2. Click **Add New Class**
3. Enter a **name** (this becomes the CSS class name)
4. Write your CSS properties in the editor:
   ```css
   border-radius: 8px;
   box-shadow: 0 4px 12px rgba(0,0,0,0.1);
   ```
5. Optionally add `:hover` and `:focus` styles
6. Click **Save**

### Apply a Class to a Block
1. Open any page in the Gutenberg editor
2. Select a block
3. Open the **ClassyBlocks** panel in the right sidebar
4. Check the box next to your class name
5. The style applies immediately — hover over the class name to preview

### Enable a CSS Framework
1. Go to **ClassyBlocks → Settings**
2. Toggle the frameworks you want to use
3. Framework CSS loads automatically on the frontend

### Export Your Library
1. Go to **ClassyBlocks → Settings → Import/Export**
2. Click **Export All** to download a JSON file
3. On another site, click **Import** and upload the file

---

## Requirements

| Requirement | Minimum |
|-------------|---------|
| WordPress | 5.8+ |
| PHP | 7.4+ |

---

## Architecture

```
studio-immens-css-classes/
├── studio-immens-css-classes.php   # Bootstrap + core class
├── admin/
│   ├── admin-ui.php                 # Class management dashboard
│   ├── settings.php                 # Framework settings page
│   └── assistenza.php               # Resources/support page
├── assets/
│   ├── admin.css / admin.js         # Admin CRUD, toast, batch ops
│   ├── editor.js / editor.css       # Gutenberg panel
│   └── framework-editor-*.js        # Framework-specific previews
├── includes/
│   └── *.min.css                    # Minified framework CSS files
├── si-css.css                       # Core plugin CSS
├── languages/                       # Translation-ready (IT included)
└── .wordpress-org/                  # WP.org assets
```

### Database Options
| Option | Purpose |
|--------|---------|
| `sicc_css_classes` | Custom CSS class definitions |
| `sicc_css_settings` | Plugin configuration |
| `sicc_css_tailwind_config` | Tailwind CSS custom config |
| `sicc_css_save_count` | Class save counter |

### AJAX Actions
| Action | Description |
|--------|-------------|
| `sicc_save_css_class` | Create/update a class |
| `sicc_edit_css_class` | Edit an existing class |
| `sicc_delete_css_class` | Delete a class |
| `sicc_get_css_classes` | Retrieve all classes |

---

## FAQ

**Is it compatible with all themes?**
Yes, but it has been optimized for Blocksy, GeneratePress, Astra, Kadence, and other modern block themes.

**Can I add my own CSS classes?**
Yes. Go to the **ClassyBlocks** section and create your own classes with the properties you want.

**Will it slow down my site?**
No. It's a lightweight plugin with no complex JavaScript or PHP dependencies. CSS is only loaded for the classes you create.

**Does it work with the Site Editor (FSE)?**
Yes, the ClassyBlocks panel is available in both the post editor and the Site Editor.

---

## License

GPL v2 or later — see [LICENSE](LICENSE).

---

Built by [Studio Immens](https://studioimmens.com) — WordPress & AI integration specialists.
