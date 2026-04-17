<?php
/*
Plugin Name: Studio Immens CSS Classes
Description: Add custom CSS classes to Gutenberg blocks with live preview.
Version: 2.0.0
Requires at least:  5.8  
Tested up to:       6.8  
Requires PHP:       7.4
Author: Studio Immens
Text Domain: studio-immens-css-classes
Domain Path: /languages
License: GPL v2 or later
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register with WP Consent API to declare support and follow guidelines.
 */
add_filter( 'wp_consent_api_registered_' . plugin_basename( __FILE__ ), '__return_true' );

// === FILE PRINCIPALE: studio-immens-css-classes.php ===
define('SI_CSS_CLASS_PATH', plugin_dir_path(__FILE__));
define('SI_CSS_CLASS_URL', plugin_dir_url(__FILE__));
define('SI_CSS_CLASS_VERSION', '2.0.0');


// Frameworks CSS versions
define('SI_CSS_BOOTSTRAP_VERSION', '5.3.3');
define('SI_CSS_MATERIALIZE_VERSION', '1.0.0');
define('SI_CSS_PURE_VERSION', '3.0.0');
define('SI_CSS_BULMA_VERSION', '1.0.0');
define('SI_CSS_UIKIT_VERSION', '3.20.4');
define('SI_CSS_SPECTRE_VERSION', '0.5.9');
define('SI_CSS_SEMANTIC_VERSION', '2.5.0');
define('SI_CSS_FOUNDATION_VERSION', '6.8.1');
define('SI_CSS_TAILWIND_VERSION', '3.4.1');

class StudioImmens_CSS_Classes {
    private static $block_css = '';

    public function __construct() {
        // Verifica compatibilità minima con WordPress
        if (version_compare(get_bloginfo('version'), '5.0', '<')) {
            add_action('admin_notices', [$this, 'sicc_compatibility_notice']);
            return;
        }

        $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'studioimmens_cc';
        $uploads_dir_url = trailingslashit( wp_upload_dir()['baseurl'] ) . 'studioimmens_cc/';

        wp_mkdir_p( $uploads_dir );

        define('SI_CSS_CLASS_UPLOAD_PATH', $uploads_dir );
        define('SI_CSS_CLASS_UPLOAD_URL', $uploads_dir_url );

        // Admin
        add_action('admin_menu', [$this, 'sicc_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'sicc_admin_scripts']);
        
        // Editor
        add_action('init', [$this, 'sicc_register_blocks_server_side']);
        add_action('enqueue_block_editor_assets', [$this, 'sicc_editor_assets']);

        add_action('wp_head', [$this, 'sicc_output_block_css'], 100);
        add_action('admin_head', [$this, 'sicc_output_block_css'], 100);

        add_action('wp_ajax_nopriv_sicc_get_css_classes', function() {
            wp_send_json_error('Not logged in', 401);
        });

        // Aggiungi handler AJAX
        add_action('wp_ajax_sicc_save_css_class', [$this, 'sicc_ajax_save_class']);
        add_action('wp_ajax_sicc_get_css_classes', [$this, 'sicc_ajax_get_classes']);
        add_action('wp_ajax_sicc_edit_css_class', [$this, 'sicc_ajax_edit_class']);
        add_action('wp_ajax_sicc_delete_css_class', [$this, 'sicc_ajax_delete_class']);

        // Gestione Export/Import/Reset
        add_action('admin_init', [$this, 'sicc_handle_tools_actions']);

        // Logga errori AJAX
        add_filter('wp_php_error_message', function($message) {
            return $message;
        }, 10, 2);

        add_action('admin_init', [$this, 'sicc_css_register_settings']);

        add_action('wp_footer', [$this, 'sicc_css_frontend']);
        add_action('admin_footer', [$this, 'sicc_css_frontend']);

        // Esegui la generazione e l'evaluator solo se necessario (es. attivazione o admin)
        if ( is_admin() ) {
            $this::sicc_css_framework_evaluator();
        }
    }

    public function sicc_css_framework_evaluator() {
        $cssSettings = $this::sicc_css_all_settings();
        $frameworks = [
            'bootstrap'   => ['v' => SI_CSS_BOOTSTRAP_VERSION,   'file' => 'bootstrap.min.css', 'opt' => 'sicc_css_bootstrap_classes'],
            'materialize' => ['v' => SI_CSS_MATERIALIZE_VERSION, 'file' => 'materialize.min.css', 'opt' => 'sicc_css_materialize_classes'],
            'pure'        => ['v' => SI_CSS_PURE_VERSION,        'file' => 'pure-min.css',       'opt' => 'sicc_css_pure_classes'],
            'bulma'       => ['v' => SI_CSS_BULMA_VERSION,       'file' => 'bulma.min.css',      'opt' => 'sicc_css_bulma_classes'],
            'uikit'       => ['v' => SI_CSS_UIKIT_VERSION,       'file' => 'uikit.min.css',      'opt' => 'sicc_css_uikit_classes'],
            'spectre'     => ['v' => SI_CSS_SPECTRE_VERSION,     'file' => 'spectre.min.css',    'opt' => 'sicc_css_spectre_classes'],
            'semantic'    => ['v' => SI_CSS_SEMANTIC_VERSION,    'file' => 'semantic.min.css',   'opt' => 'sicc_css_semantic_classes'],
            'foundation'  => ['v' => SI_CSS_FOUNDATION_VERSION,  'file' => 'foundation.min.css', 'opt' => 'sicc_css_foundation_classes'],
        ];

        foreach ($frameworks as $key => $data) {
            if (isset($cssSettings['enable_' . $key]) && $cssSettings['enable_' . $key] == '1') {
                $saved_v = get_option('sicc_css_' . $key . '_classes_vers');
                $classes = get_option($data['opt']);

                if ($saved_v !== $data['v'] || empty($classes)) {
                    $this::sicc_css_framework_constructor(SI_CSS_CLASS_PATH . 'includes/' . $data['file'], $data['opt']);
                    update_option('sicc_css_' . $key . '_classes_vers', $data['v']);
                }
            }
        }
    }

    public function sicc_css_frontend() {
        $cssSettings = $this::sicc_css_all_settings();
        
        $css_file = SI_CSS_CLASS_UPLOAD_PATH . '/si-css.css';
        if (file_exists($css_file)) {
            wp_enqueue_style('sicc-css-studio-immens', SI_CSS_CLASS_UPLOAD_URL . 'si-css.css', array(), SI_CSS_CLASS_VERSION );
        }

        $frameworks = [
            'bootstrap'   => ['v' => SI_CSS_BOOTSTRAP_VERSION,   'file' => 'bootstrap.min.css', 'opt' => 'sicc_css_bootstrap_classes'],
            'materialize' => ['v' => SI_CSS_MATERIALIZE_VERSION, 'file' => 'materialize.min.css', 'opt' => 'sicc_css_materialize_classes'],
            'pure'        => ['v' => SI_CSS_PURE_VERSION,        'file' => 'pure-min.css',       'opt' => 'sicc_css_pure_classes'],
            'bulma'       => ['v' => SI_CSS_BULMA_VERSION,       'file' => 'bulma.min.css',      'opt' => 'sicc_css_bulma_classes'],
            'uikit'       => ['v' => SI_CSS_UIKIT_VERSION,       'file' => 'uikit.min.css',      'opt' => 'sicc_css_uikit_classes'],
            'spectre'     => ['v' => SI_CSS_SPECTRE_VERSION,     'file' => 'spectre.min.css',    'opt' => 'sicc_css_spectre_classes'],
            'semantic'    => ['v' => SI_CSS_SEMANTIC_VERSION,    'file' => 'semantic.min.css',   'opt' => 'sicc_css_semantic_classes'],
            'foundation'  => ['v' => SI_CSS_FOUNDATION_VERSION,  'file' => 'foundation.min.css', 'opt' => 'sicc_css_foundation_classes'],
        ];

        $content = '';
        if (!is_admin()) {
            global $post;
            $content = $post ? $post->post_content : '';
        }

        foreach ($frameworks as $key => $data) {
            $enabled = isset($cssSettings['enable_' . $key]) && $cssSettings['enable_' . $key] == '1';
            if (!$enabled) continue;

            $display_admin = isset($cssSettings['disp_admin_' . $key]) && $cssSettings['disp_admin_' . $key] == '1';
            
            $should_load = false;
            if (is_admin()) {
                $should_load = $display_admin;
            } else {
                // Check usage in content
                $framework_classes = get_option($data['opt'], []);
                if (!empty($framework_classes)) {
                    $pattern = '/\b(' . implode('|', array_map('preg_quote', $framework_classes)) . ')\b/';
                    if (preg_match($pattern, $content)) {
                        $should_load = true;
                    }
                }
            }

            if ($should_load) {
                wp_enqueue_style(
                    'sicc-' . $key . '-css',
                    SI_CSS_CLASS_URL . 'includes/' . $data['file'],
                    array(),
                    $data['v']
                );
            }
        }

        // Tailwind CDN
        if (isset($cssSettings['enable_tailwind']) && $cssSettings['enable_tailwind'] == '1') {
            if (is_admin() || (!is_admin() && isset($cssSettings['disp_frontend_tailwind']) && $cssSettings['disp_frontend_tailwind'] == '1')) {
                wp_enqueue_script(
                    'sicc-tailwind-cdn',
                    'https://cdn.tailwindcss.com',
                    array(),
                    SI_CSS_TAILWIND_VERSION,
                    false
                );
                // Tailwind Config opzionale
                $tw_config = get_option('sicc_css_tailwind_config', '');
                if ($tw_config) {
                    wp_add_inline_script('sicc-tailwind-cdn', 'tailwind.config = ' . $tw_config, 'before');
                }
            }
        }
    }

    public function sicc_css_register_settings() {
        register_setting('sicc_css_settings_group', 'sicc_css_settings', array('sanitize_callback' => array( $this,'sicc_sanitize_settings')) );
        register_setting('sicc_css_settings_group', 'sicc_css_tailwind_config', array('sanitize_callback' => array($this, 'sicc_sanitize_tailwind_config')) );
    }

    public function sicc_sanitize_tailwind_config($config) {
        // Rimuove tag HTML/PHP ma mantiene la struttura JSON/JS
        $config = wp_strip_all_tags($config);
        
        // Ulteriore pulizia per prevenire injection di script comuni in JS objects
        $config = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $config);
        $config = preg_replace('/javascript:/i', "", $config);
        $config = preg_replace('/onclick|onerror|onload|onmouseover/i', "", $config);

        // Validazione base JSON se non è vuoto
        if (!empty($config)) {
            $test = json_decode($config);
            // Se non è JSON valido, potrebbe essere un oggetto JS letterale accettato da Tailwind Play CDN
        }
        return trim($config);
    }

    public function sicc_register_blocks_server_side() {
        register_block_type('studioimmens/css-editor', [
            'editor_script' => 'studioimmens-css-block',
            'render_callback' => [$this, 'sicc_render_custom_css_block'],
            'attributes' => [
                'cssCode' => [
                    'type' => 'string',
                    'default' => '',
                ],
            ],
        ]);
    }

    public function sicc_render_custom_css_block($attributes) {
        if (!empty($attributes['cssCode'])) {
            self::$block_css .= $this->sicc_sanitize_css($attributes['cssCode']) . "\n";
        }
        return ''; // Non renderizzare nulla nel contenuto
    }

    public function sicc_output_block_css() {
        if (!empty(self::$block_css)) {
            echo '<style id="sicc-custom-blocks-css">' . self::$block_css . '</style>';
        }
    }

    public function sicc_sanitize_settings( $input ) {
        $old_settings = get_option('sicc_css_settings', []);
        
        $keys = array_keys($input);
        $keys = array_map('sanitize_key', $keys);
        $values = array_values($input);
        $values = array_map('sanitize_text_field', $values);
        $new_input = array_combine($keys, $values);

        // Se le impostazioni dei framework sono cambiate, forza la rigenerazione selettori
        // (Verrà gestito dal sicc_css_framework_evaluator al prossimo caricamento admin)
        
        // Se ci sono classi personalizzate salvate via settings (non AJAX), rigenera CSS
        $this->sicc_css_constructor();

        return $new_input;
    }

    public function sicc_sanitize_text_field( $input ){
        $keys = array_keys($input);
        $keys = array_map('sanitize_key', $keys);

        $values = array_values($input);
        $values = array_map('sanitize_text_field', $values);

        $input = array_combine($keys, $values);

        return $input;
    }

    public function sicc_css_all_settings() {
        return get_option('sicc_css_settings', []);
    }

    public function sicc_css_constructor() {
        $css_output = '';
        $classes = get_option('sicc_css_classes', []);
        foreach ($classes as $key => $value) {
            if (isset($value['name']) && isset($value['css'])) {
              $css_output .= '.'.$value['name'].'{'.$value['css'].'} ';
            }
            if (isset($value['name']) && isset($value['hover'])) {
              $css_output .= '.'.$value['name'].':hover {'.$value['hover'].'} ';
            }
            if (isset($value['name']) && isset($value['focus'])) {
              $css_output .= '.'.$value['name'].':focus {'.$value['focus'].'} ';
            }
        }
        
        // Minificazione base
        $css_output = preg_replace('/\s+/', ' ', $css_output);
        $css_output = str_replace(['; ', ': ', ' {', '{ '], [';', ':', '{', '{'], $css_output);

        $output_file = SI_CSS_CLASS_UPLOAD_PATH . '/si-css.css';
        
        if (empty($css_output)) {
            if (file_exists($output_file)) {
                unlink($output_file);
            }
            return;
        }

        file_put_contents($output_file, $css_output);
    }

    public function sicc_css_framework_constructor($path, $tag) {
        if ( ! file_exists( $path ) ) return;
        $css = file_get_contents( $path );

        update_option( $tag, SICC_extract_css_selectors($css) );
    }

    public function sicc_compatibility_notice() {
        echo '<div class="error"><p>';
        printf(/* translators: CSS Classes requires WordPress 5.0 or higher */
            esc_html__('Studio Immens CSS Classes requires WordPress 5.0 or higher. Your version is %s.', 'studio-immens-css-classes'),
            esc_attr(get_bloginfo('version'))
        );
        echo '</p></div>';
    }

    // Menu admin
    public function sicc_admin_menu() {
        add_menu_page(
            esc_html__('CSS Classes', 'studio-immens-css-classes'),
            esc_html__('CSS Classes', 'studio-immens-css-classes'),
            'manage_options',
            'studioimmens-css',
            [$this, 'sicc_admin_page'],
            'dashicons-art',
            80
        );
        add_submenu_page(
            'studioimmens-css',
            esc_html__('Assistenza', 'studio-immens-css-classes'),
            esc_html__('Assistenza', 'studio-immens-css-classes'),
            'manage_options',
            'studioimmens-assistenza',
            [$this, 'sicc_assistenza_page']
        );
         add_submenu_page(
            'studioimmens-css',
            esc_html__('Settings', 'studio-immens-css-classes'),
            esc_html__('Settings', 'studio-immens-css-classes'),
            'manage_options',
            'studioimmens-css-settings',
            [$this, 'sicc_studioimmens_settings']
        );

    }

    // Pagina admin
    public function sicc_admin_page() {
        include SI_CSS_CLASS_PATH . 'admin/admin-ui.php';
    }

    public function sicc_assistenza_page() {
        include SI_CSS_CLASS_PATH . 'admin/assistenza.php';
    }

    public function sicc_studioimmens_settings() {
        include SI_CSS_CLASS_PATH . 'admin/settings.php';
    }

    // Script admin
    public function sicc_admin_scripts($hook) {
        if ( $hook === 'toplevel_page_studioimmens-css'|| $hook === 'css-classes_page_studioimmens-css-settings'|| $hook === 'css-classes_page_studioimmens-assistenza') {

            $cssSettings = $this::sicc_css_all_settings();

            wp_enqueue_style('sicc-css-admin', SI_CSS_CLASS_URL . 'assets/admin.css', array(), SI_CSS_CLASS_VERSION );
            wp_enqueue_script('sicc-css-admin', SI_CSS_CLASS_URL . 'assets/admin.js', ['jquery'], SI_CSS_CLASS_VERSION, true);

            // CodeMirror per CSS
            $cm_settings = wp_enqueue_code_editor(array('type' => 'text/css'));
            wp_localize_script('sicc-css-admin', 'siCodeMirror', array(
                'settings' => $cm_settings,
            ));

            wp_localize_script('sicc-css-admin', 'siCssAdmin', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('sicc_css_nonce'),
                'delete' => esc_html__('Delete', 'studio-immens-css-classes'),
                'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
                'edit' => esc_html__('Edit', 'studio-immens-css-classes'),
                'emptyFields' => esc_html__('Please fill in all required fields.', 'studio-immens-css-classes'),
                'saveError' => esc_html__('Error saving class.', 'studio-immens-css-classes'),
                'noClasses' => esc_html__('No classes found.', 'studio-immens-css-classes'),
            ]);

            wp_enqueue_style('sicc-css-admin-edit', SI_CSS_CLASS_UPLOAD_URL . 'si-css.css', array(), SI_CSS_CLASS_VERSION );

            $this::sicc_css_frontend();
        }
    }

    // Script editor
    public function sicc_editor_assets() {
        // Verifica che siamo nell'editor
        if (!function_exists('get_current_screen') || !get_current_screen()->is_block_editor()) {
            return;
        }
        $cssSettings = $this::sicc_css_all_settings();

        wp_enqueue_style('sicc-css-editor-edit', SI_CSS_CLASS_UPLOAD_URL . 'si-css.css', ['wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks'], SI_CSS_CLASS_VERSION );
        wp_enqueue_style('sicc-css-editor', SI_CSS_CLASS_URL . 'assets/editor.css', array(), SI_CSS_CLASS_VERSION );

        wp_enqueue_script('sicc-css-editor', SI_CSS_CLASS_URL . 'assets/editor.js', ['wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks'], SI_CSS_CLASS_VERSION, true );
        wp_localize_script('sicc-css-editor', 'siCssData', [
            'classes' => get_option('sicc_css_classes', []),
            'labels' => $this->sicc_get_editor_labels('CSS Classes')
        ]);

        // Custom CSS Block
        wp_enqueue_script( 'studioimmens-css-block', SI_CSS_CLASS_URL . 'assets/studioimmens-css-block.js', array( 'wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks' ), SI_CSS_CLASS_VERSION, true );
        wp_localize_script('studioimmens-css-block', 'siCssDataBlock', [
            'labels' => [
                'title' => esc_html__('Custom CSS', 'studio-immens-css-classes'),
                'desc' => esc_html__('Enter CSS to apply to this post:', 'studio-immens-css-classes'),
            ]
        ]);

        $frameworks = [
            'bootstrap'   => ['js' => 'siCssDataBs', 'asset' => 'bs-editor.js',       'opt' => 'sicc_css_bootstrap_classes',   'label' => 'Bootstrap Classes',   'v' => SI_CSS_BOOTSTRAP_VERSION,   'file' => 'bootstrap.min.css'],
            'materialize' => ['js' => 'siCssDataMt', 'asset' => 'material-editor.js', 'opt' => 'sicc_css_materialize_classes', 'label' => 'Materialize Classes', 'v' => SI_CSS_MATERIALIZE_VERSION, 'file' => 'materialize.min.css'],
            'pure'        => ['js' => 'siCssDataPr', 'asset' => 'pure-editor.js',     'opt' => 'sicc_css_pure_classes',        'label' => 'Pure Classes',        'v' => SI_CSS_PURE_VERSION,        'file' => 'pure-min.css'],
            'bulma'       => ['js' => 'siCssDataBl', 'asset' => 'bulma-editor.js',    'opt' => 'sicc_css_bulma_classes',       'label' => 'Bulma Classes',       'v' => SI_CSS_BULMA_VERSION,       'file' => 'bulma.min.css'],
            'uikit'       => ['js' => 'siCssDataUi', 'asset' => 'uikit-editor.js',    'opt' => 'sicc_css_uikit_classes',       'label' => 'Uikit Classes',       'v' => SI_CSS_UIKIT_VERSION,       'file' => 'uikit.min.css'],
            'spectre'     => ['js' => 'siCssDataSp', 'asset' => 'spectre-editor.js',  'opt' => 'sicc_css_spectre_classes',     'label' => 'Spectre Classes',     'v' => SI_CSS_SPECTRE_VERSION,     'file' => 'spectre.min.css'],
            'semantic'    => ['js' => 'siCssDataSe', 'asset' => 'semantic-editor.js', 'opt' => 'sicc_css_semantic_classes',    'label' => 'Semantic Classes',    'v' => SI_CSS_SEMANTIC_VERSION,    'file' => 'semantic.min.css'],
            'foundation'  => ['js' => 'siCssDataFd', 'asset' => 'foundation-editor.js','opt' => 'sicc_css_foundation_classes',  'label' => 'Foundation Classes',  'v' => SI_CSS_FOUNDATION_VERSION,  'file' => 'foundation.min.css'],
        ];

        foreach ($frameworks as $key => $data) {
            if (isset($cssSettings['enable_' . $key]) && $cssSettings['enable_' . $key] == '1') {
                wp_enqueue_script('sicc-css-' . $key . '-editor', SI_CSS_CLASS_URL . 'assets/' . $data['asset'], ['wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks'], SI_CSS_CLASS_VERSION, true );
                wp_localize_script('sicc-css-' . $key . '-editor', $data['js'], [
                    'classes' => get_option($data['opt'], []),
                    'labels' => $this->sicc_get_editor_labels($data['label'])
                ]);

                if (isset($cssSettings['disp_edit_' . $key]) && $cssSettings['disp_edit_' . $key] == '1') {
                    wp_enqueue_style('sicc-' . $key . '-css', SI_CSS_CLASS_URL . 'includes/' . $data['file'], array(), $data['v']);
                }
            }
        }
    }

    private function sicc_get_editor_labels($title) {
        return [
            'title' => esc_html__($title, 'studio-immens-css-classes'),
            'select' => esc_html__('Select Class', 'studio-immens-css-classes'),
            'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
            'search' => esc_html__('Search Classes', 'studio-immens-css-classes'),
            'ttos' => esc_html__('Type to search...', 'studio-immens-css-classes'),
            'livep' => esc_html__('Live Preview', 'studio-immens-css-classes'),
            'nofound' => esc_html__('No classes found', 'studio-immens-css-classes'),
        ];
    }



    public function sicc_ajax_save_class() {
        check_ajax_referer('sicc_css_nonce', 'security');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized', 403);
        }
        
        // Valida i dati
        if (empty($_POST['name']) || empty($_POST['css'])) {
            wp_send_json_error('Invalid data', 400);
        }
        
        $classes = get_option('sicc_css_classes', []);
        if ($classes=='') {
            $classes = array();
        }
        
        $new_class = array(
            'id' => uniqid(),
            'name' => (!empty( $_POST['name'] )) ? sanitize_html_class(wp_unslash($_POST['name'])) : '',
            'css' =>  (!empty( $_POST['css'] )) ? $this->sicc_sanitize_css(wp_unslash($_POST['css'])) : '',
            'hover' =>  (!empty( $_POST['hover'] )) ? $this->sicc_sanitize_css(wp_unslash($_POST['hover'])) : '',
            'focus' =>  (!empty( $_POST['focus'] )) ? $this->sicc_sanitize_css(wp_unslash($_POST['focus'])) : '',
        );
        
        $classes[] = $new_class;
        update_option('sicc_css_classes', $classes);
        $this->sicc_css_constructor(); // Rigenera il file CSS
        wp_send_json_success($new_class);
    }

    // Aggiunge le classi personalizzate al theme.json se supportato
    public function sicc_update_theme_json_support() {
        // I temi moderni basati su blocchi caricano automaticamente gli stili registrati con wp_enqueue_style.
        // Non è necessaria un'iniezione manuale in theme.json al momento,
        // ma manteniamo il metodo per future implementazioni di preset.
    }

    // Sanitizzazione CSS avanzata
    private function sicc_sanitize_css($css) {
        // Rimuove tag HTML e PHP
        $css = wp_strip_all_tags($css);
        // Rimuove blocchi script
        $css = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $css);
        // Rimuove espressioni pericolose (expression(), behavior:, ecc.)
        $css = preg_replace('/expression\s*\(|behavior\s*:|url\s*\(\s*["\']?\s*javascript:/i', '', $css);
        return trim($css);
    }

    // Handler AJAX per update
    public function sicc_ajax_edit_class() {
        check_ajax_referer('sicc_css_nonce', 'security');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized', 403);
        }

        // Valida i dati
        if (empty($_POST['name']) || empty($_POST['css'])) {
            wp_send_json_error('Invalid data', 400);
        }
        
        $classes = get_option('sicc_css_classes', []);
        $id = (!empty( $_POST['id'] )) ? sanitize_text_field(wp_unslash($_POST['id'])) : '';

        foreach ($classes as $key => $value) {
            if (isset($value['id']) && $value['id'] == $id) {
                $classes[$key] = array(
                    'id' => $id,
                    'name' => (!empty( $_POST['name'] )) ? sanitize_html_class(wp_unslash($_POST['name'])) : '',
                    'css' =>  (!empty( $_POST['css'] )) ? $this->sicc_sanitize_css(wp_unslash($_POST['css'])) : '',
                    'hover' =>  (!empty( $_POST['hover'] )) ? $this->sicc_sanitize_css(wp_unslash($_POST['hover'])) : '',
                    'focus' =>  (!empty( $_POST['focus'] )) ? $this->sicc_sanitize_css(wp_unslash($_POST['focus'])) : '',
                );
            }
        }
        
        update_option('sicc_css_classes', $classes);
        $this->sicc_css_constructor(); // Rigenera il file CSS
        wp_send_json_success($classes);
    }
    
    // Handler AJAX per ottenere le classi
    public function sicc_ajax_get_classes() {
        check_ajax_referer('sicc_css_nonce', 'security');
        wp_send_json_success(get_option('sicc_css_classes', []));
    }
    
    // Handler AJAX per eliminare
    public function sicc_ajax_delete_class() {
        check_ajax_referer('sicc_css_nonce', 'security');
        
        if (!current_user_can('manage_options') || empty($_POST['id'])) {
            wp_send_json_error('Invalid request', 400);
        }
        
        $classes = get_option('sicc_css_classes', []);
        $id = sanitize_text_field(wp_unslash($_POST['id']));
        
        $updated = array_filter($classes, function($cls) use ($id) {
            return $cls['id'] !== $id;
        });
        
        update_option('sicc_css_classes', array_values($updated));
        $this->sicc_css_constructor(); // Rigenera il file CSS
        wp_send_json_success();
    }

    // Gestione delle azioni dei tools (Export, Import, Reset)
    public function sicc_handle_tools_actions() {
        if (!current_user_can('manage_options')) return;

        // Export
        if (isset($_GET['action']) && $_GET['action'] === 'sicc_export') {
            check_admin_referer('sicc_export_nonce');

            $classes = get_option('sicc_css_classes', []);
            $settings = get_option('sicc_css_settings', []);
            $tailwind = get_option('sicc_css_tailwind_config', '');

            $data = [
                'classes' => $classes,
                'settings' => $settings,
                'tailwind_config' => $tailwind,
                'exported_at' => time(),
                'version' => SI_CSS_CLASS_VERSION
            ];

            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="sicc-export-' . date('Y-m-d') . '.json"');
            echo json_encode($data);
            exit;
        }

        // Import
        if (isset($_POST['sicc_import_submit'])) {
            check_admin_referer('sicc_import_nonce');

            if (!empty($_FILES['sicc_import_file']['tmp_name'])) {
                $file_name = $_FILES['sicc_import_file']['name'];
                $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                if ($file_ext !== 'json') {
                    add_action('admin_notices', function() {
                        echo '<div class="error"><p>' . esc_html__('Error: Only JSON files are allowed.', 'studio-immens-css-classes') . '</p></div>';
                    });
                    return;
                }

                $file = $_FILES['sicc_import_file']['tmp_name'];
                $content = file_get_contents($file);
                $data = json_decode($content, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    add_action('admin_notices', function() {
                        echo '<div class="error"><p>' . esc_html__('Error: Invalid JSON file.', 'studio-immens-css-classes') . '</p></div>';
                    });
                    return;
                }

                if ($data && isset($data['classes']) && is_array($data['classes'])) {
                    update_option('sicc_css_classes', $data['classes']);
                    if (isset($data['settings']) && is_array($data['settings'])) update_option('sicc_css_settings', $data['settings']);
                    if (isset($data['tailwind_config'])) update_option('sicc_css_tailwind_config', sanitize_textarea_field($data['tailwind_config']));
                    
                    $this->sicc_css_constructor();
                    add_action('admin_notices', function() {
                        echo '<div class="updated"><p>' . esc_html__('Import completed successfully!', 'studio-immens-css-classes') . '</p></div>';
                    });
                } else {
                    add_action('admin_notices', function() {
                        echo '<div class="error"><p>' . esc_html__('Error: Data structure is invalid.', 'studio-immens-css-classes') . '</p></div>';
                    });
                }
            }
        }

        // Reset Settings
        if (isset($_POST['sicc_reset_submit'])) {
            check_admin_referer('sicc_reset_nonce');

            delete_option('sicc_css_classes');
            delete_option('sicc_css_settings');
            delete_option('sicc_css_tailwind_config');
            
            // Re-inizializza il file CSS vuoto
            $this->sicc_css_constructor();

            add_action('admin_notices', function() {
                echo '<div class="updated"><p>' . esc_html__('All settings have been reset to default.', 'studio-immens-css-classes') . '</p></div>';
            });
        }
    }
}

new StudioImmens_CSS_Classes();

// Registra opzioni
register_activation_hook(__FILE__, function() {
    add_option('sicc_css_classes', []);
});


function SICC_check( $var ){
    if ( isset($var) && !empty($var) ) {
        return true;
    }else{ 
        return false;
    }
}

// Funzione per estrarre classi e ID dal CSS
function SICC_extract_css_selectors($css) {
    $selectors = array();
    
    // Rimuovi commenti
    $css = preg_replace('/\/\*.*?\*\//s', '', $css);

    // 2. Rimuove tutti i blocchi @... { ... } inclusi @keyframes, @media, ecc.
    $css = preg_replace('/@[\w\-]+[^{]*\{(?:[^{}]++|(?R))*\}/s', '', $css);
    
    // Trova classi
    preg_match_all('/\.([a-zA-Z0-9_-]+)(?=[^{}]*\{)/', $css, $class_matches);
    if (!empty($class_matches[1])) {
        foreach ($class_matches[1] as $class) {
            $selectors[] = $class;
        }
    }
    
    // Trova ID
    // preg_match_all('/#([a-zA-Z0-9_-]+)(?=[^{}]*\{)/', $css, $id_matches);
    // if (!empty($id_matches[1])) {
    //     foreach ($id_matches[1] as $id) {
    //         $selectors[] = $id;
    //     }
    // }
    
    // Rimuovi duplicati e valori vuoti
    $selectors = array_unique($selectors);
    $selectors = array_filter($selectors);
    
    // Ordina alfabeticamente
    sort($selectors);
    
    return $selectors;
}