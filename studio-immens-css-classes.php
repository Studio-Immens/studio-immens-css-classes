<?php
/*
Plugin Name: Studio Immens CSS Classes
Description: Add custom CSS classes to Gutenberg blocks with live preview.
Version: 1.1.1
Requires at least:  5.8  
Tested up to:       6.8  
Requires PHP:       7.4
Author: Studio Immens
Text Domain: studio-immens-css-classes
Domain Path: /languages
License: GPL v2 or later
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// === FILE PRINCIPALE: studio-immens-css-classes.php ===
define('SI_CSS_CLASS_PATH', plugin_dir_path(__FILE__));
define('SI_CSS_CLASS_URL', plugin_dir_url(__FILE__));
define('SI_CSS_CLASS_VERSION', '1.1.1');


class StudioImmens_CSS_Classes {
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
        add_action('enqueue_block_editor_assets', [$this, 'sicc_studioimmens_register_css_block'] );
        add_action('enqueue_block_editor_assets', [$this, 'sicc_editor_assets']);

        add_action('wp_ajax_nopriv_sicc_get_css_classes', function() {
            wp_send_json_error('Not logged in', 401);
        });

        // Aggiungi handler AJAX
        add_action('wp_ajax_sicc_save_css_class', [$this, 'sicc_ajax_save_class']);
        add_action('wp_ajax_sicc_get_css_classes', [$this, 'sicc_ajax_get_classes']);
        add_action('wp_ajax_sicc_edit_css_class', [$this, 'sicc_ajax_edit_class']);
        add_action('wp_ajax_sicc_delete_css_class', [$this, 'sicc_ajax_delete_class']);

        // Logga errori AJAX
        add_filter('wp_php_error_message', function($message) {
            return $message;
        }, 10, 2);

        add_action('wp_footer', [$this, 'sicc_css_constructor']);
        add_action('admin_footer', [$this, 'sicc_css_constructor']);
        add_action('enqueue_block_editor_assets', [$this, 'sicc_css_constructor']);

        add_action('admin_init', [$this, 'sicc_css_register_settings']);

        $this::sicc_css_framework_evaluator();
    }

    public function sicc_css_framework_evaluator() {
        $cssSettings = $this::sicc_css_all_settings();

        if (isset($cssSettings['enable_bootstrap']) && $cssSettings['enable_bootstrap'] == '1') {
            if ( version_compare( get_option('sicc_css_bootstrap_classes_vers'), '5.3.3', '!=') ) {
                $this::sicc_css_framework_constructor( SI_CSS_CLASS_URL . 'includes/bootstrap.min.css', 'sicc_css_bootstrap_classes');
                update_option('sicc_css_bootstrap_classes_vers', '5.3.3');
            }
        }
        if (isset($cssSettings['enable_materialize']) && $cssSettings['enable_materialize'] == '1') {
            if ( version_compare( get_option('sicc_css_materialize_classes_vers'), '1.0.0', '!=') ) {
                $this::sicc_css_framework_constructor( SI_CSS_CLASS_URL . 'includes/materialize.min.css', 'sicc_css_materialize_classes');
                update_option('sicc_css_materialize_classes_vers', '1.0.0');
            }
        }
        if (isset($cssSettings['enable_pure']) && $cssSettings['enable_pure'] == '1') {
            if ( version_compare( get_option('sicc_css_pure_classes_vers'), '3.0.0', '!=') ) {
                $this::sicc_css_framework_constructor( SI_CSS_CLASS_URL . 'includes/pure-min.css', 'sicc_css_pure_classes');
                update_option('sicc_css_pure_classes_vers', '3.0.0');
            }
        }
        if (isset($cssSettings['enable_bulma']) && $cssSettings['enable_bulma'] == '1') {
            if ( version_compare( get_option('sicc_css_bulma_classes_vers'), '1.0.0', '!=') ) {
                $this::sicc_css_framework_constructor( SI_CSS_CLASS_URL . 'includes/bulma.min.css', 'sicc_css_bulma_classes');
                update_option('sicc_css_bulma_classes_vers', '1.0.0');
            }
        }
        if (isset($cssSettings['enable_uikit']) && $cssSettings['enable_uikit'] == '1') {
            if ( version_compare( get_option('sicc_css_uikit_classes_vers'), '3.20.4', '!=') ) {
                $this::sicc_css_framework_constructor( SI_CSS_CLASS_URL . 'includes/uikit.min.css', 'sicc_css_uikit_classes');
                update_option('sicc_css_uikit_classes_vers', '3.20.4');
            }
        }
        if (isset($cssSettings['enable_spectre']) && $cssSettings['enable_spectre'] == '1') {
            if ( version_compare( get_option('sicc_css_spectre_classes_vers'), '0.5.9', '!=') ) {
                $this::sicc_css_framework_constructor( SI_CSS_CLASS_URL . 'includes/spectre.min.css', 'sicc_css_spectre_classes');
                update_option('sicc_css_spectre_classes_vers', '0.5.9');
            }
        }
        if (isset($cssSettings['enable_semantic']) && $cssSettings['enable_semantic'] == '1') {
            if ( version_compare( get_option('sicc_css_semantic_classes_vers'), '2.5.0', '!=') ) {
                $this::sicc_css_framework_constructor( SI_CSS_CLASS_URL . 'includes/semantic.min.css', 'sicc_css_semantic_classes');
                update_option('sicc_css_semantic_classes_vers', '2.5.0');
            }
        }
    }

    public function sicc_css_register_settings() {
        register_setting('sicc_css_settings_group', 'sicc_css_settings');
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
        $output_file = SI_CSS_CLASS_UPLOAD_PATH . '/si-css.css';
        file_put_contents($output_file, $css_output);
    }

    public function sicc_css_framework_constructor($url, $tag) {
        $css = file_get_contents( $url );

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
            esc_html__('Studio Immens', 'studio-immens-css-classes'),
            esc_html__('Studio Immens', 'studio-immens-css-classes'),
            'manage_options',
            'studioimmens-page',
            [$this, 'sicc_studioimmens_page']
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

    public function sicc_studioimmens_page() {
        include SI_CSS_CLASS_PATH . 'admin/studioimmens.php';
    }

    public function sicc_studioimmens_settings() {
        include SI_CSS_CLASS_PATH . 'admin/settings.php';
    }

    // Script admin
    public function sicc_admin_scripts($hook) {
        // var_dump($hook);
        if ( $hook === 'toplevel_page_studioimmens-css'|| $hook === 'classi-css_page_studioimmens-page') {
            $cssSettings = $this::sicc_css_all_settings();

            wp_enqueue_style('sicc-css-admin', SI_CSS_CLASS_URL . 'assets/admin.css', array(), SI_CSS_CLASS_VERSION );
            wp_enqueue_script('sicc-css-admin', SI_CSS_CLASS_URL . 'assets/admin.js', ['jquery'], SI_CSS_CLASS_VERSION, true);

            wp_localize_script('sicc-css-admin', 'siCssAdmin', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('sicc_css_nonce'),
                'delete' => esc_html__('Delete', 'studio-immens-css-classes'),
                'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
                'edit' => esc_html__('Edit', 'studio-immens-css-classes'),
            ]);

            wp_enqueue_style('sicc-css-admin-edit', SI_CSS_CLASS_UPLOAD_URL . 'si-css.css', array(), SI_CSS_CLASS_VERSION );

            if (isset($cssSettings['enable_bootstrap']) && $cssSettings['enable_bootstrap'] == '1') {
                    wp_enqueue_style(
                    'sicc-bootstrap-css',
                    SI_CSS_CLASS_URL . 'includes/bootstrap.min.css',
                    array(),
                    '5.3.3'
                );
            }
            if (isset($cssSettings['enable_materialize']) && $cssSettings['enable_materialize'] == '1') {
                    wp_enqueue_style(
                    'sicc-materialize-css',
                    SI_CSS_CLASS_URL . 'includes/materialize.min.css',
                    array(),
                    '1.0.0'
                );
            }
            if (isset($cssSettings['enable_pure']) && $cssSettings['enable_pure'] == '1') {
                    wp_enqueue_style(
                    'sicc-pure-css',
                    SI_CSS_CLASS_URL . 'includes/pure-min.css',
                    array(),
                    '3.0.0'
                );
            }
            if (isset($cssSettings['enable_bulma']) && $cssSettings['enable_bulma'] == '1') {
                    wp_enqueue_style(
                    'sicc-bulma-css',
                    SI_CSS_CLASS_URL . 'includes/bulma.min.css',
                    array(),
                    '1.0.0'
                );
            }
            if (isset($cssSettings['enable_uikit']) && $cssSettings['enable_uikit'] == '1') {
                    wp_enqueue_style(
                    'sicc-uikit-css',
                    SI_CSS_CLASS_URL . 'includes/uikit.min.css',
                    array(),
                    '3.20.4'
                );
            }
            if (isset($cssSettings['enable_spectre']) && $cssSettings['enable_spectre'] == '1') {
                    wp_enqueue_style(
                    'sicc-spectre-css',
                    SI_CSS_CLASS_URL . 'includes/spectre.min.css',
                    array(),
                    '0.5.9'
                );
            }
            if (isset($cssSettings['enable_semantic']) && $cssSettings['enable_semantic'] == '1') {
                    wp_enqueue_style(
                    'sicc-semantic-css',
                    SI_CSS_CLASS_URL . 'includes/semantic.min.css',
                    array(),
                    '2.5.0'
                );
            }
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
            'labels' => [
                'title' => esc_html__('CSS Classes', 'studio-immens-css-classes'),
                'select' => esc_html__('Select Class', 'studio-immens-css-classes'),
                'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
                'search' => esc_html__('Search Classes', 'studio-immens-css-classes'),
                'ttos' => esc_html__('Type to search...', 'studio-immens-css-classes'),
                'livep' => esc_html__('Live Preview', 'studio-immens-css-classes'),
                'nofound' => esc_html__('No classes found', 'studio-immens-css-classes'),
            ]
        ]);
//Bootstrap
        if (isset($cssSettings['enable_bootstrap']) && $cssSettings['enable_bootstrap'] == '1') {
            wp_enqueue_script('sicc-css-bs-editor', SI_CSS_CLASS_URL . 'assets/bs-editor.js', ['wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks'], SI_CSS_CLASS_VERSION, true );
            wp_localize_script('sicc-css-bs-editor', 'siCssDataBs', [
                'classes' => get_option('sicc_css_bootstrap_classes', []),
                'labels' => [
                    'title' => esc_html__('Bootstrap Classes', 'studio-immens-css-classes'),
                    'select' => esc_html__('Select Class', 'studio-immens-css-classes'),
                    'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
                    'search' => esc_html__('Search Classes', 'studio-immens-css-classes'),
                    'ttos' => esc_html__('Type to search...', 'studio-immens-css-classes'),
                    'livep' => esc_html__('Live Preview', 'studio-immens-css-classes'),
                    'nofound' => esc_html__('No classes found', 'studio-immens-css-classes'),
                ]
            ]);
            wp_enqueue_style(
                'sicc-bootstrap-css',
                SI_CSS_CLASS_URL . 'includes/bootstrap.min.css',
                array(),
                '5.3.3'
            );
        }
//Materialize
        if (isset($cssSettings['enable_materialize']) && $cssSettings['enable_materialize'] == '1') {
            wp_enqueue_script('sicc-css-material-editor', SI_CSS_CLASS_URL . 'assets/material-editor.js', ['wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks'], SI_CSS_CLASS_VERSION, true );
            wp_localize_script('sicc-css-material-editor', 'siCssDataMt', [
                'classes' => get_option('sicc_css_materialize_classes', []),
                'labels' => [
                    'title' => esc_html__('Materialize Classes', 'studio-immens-css-classes'),
                    'select' => esc_html__('Select Class', 'studio-immens-css-classes'),
                    'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
                    'search' => esc_html__('Search Classes', 'studio-immens-css-classes'),
                    'ttos' => esc_html__('Type to search...', 'studio-immens-css-classes'),
                    'livep' => esc_html__('Live Preview', 'studio-immens-css-classes'),
                    'nofound' => esc_html__('No classes found', 'studio-immens-css-classes'),
                ]
            ]);
            wp_enqueue_style(
                'sicc-materialize-css',
                SI_CSS_CLASS_URL . 'includes/materialize.min.css',
                array(),
                '1.0.0'
            );
        }
//Pure
        if (isset($cssSettings['enable_pure']) && $cssSettings['enable_pure'] == '1') {
            wp_enqueue_script('sicc-css-pure-editor', SI_CSS_CLASS_URL . 'assets/pure-editor.js', ['wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks'], SI_CSS_CLASS_VERSION, true );
            wp_localize_script('sicc-css-pure-editor', 'siCssDataPr', [
                'classes' => get_option('sicc_css_pure_classes', []),
                'labels' => [
                    'title' => esc_html__('Pure Classes', 'studio-immens-css-classes'),
                    'select' => esc_html__('Select Class', 'studio-immens-css-classes'),
                    'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
                    'search' => esc_html__('Search Classes', 'studio-immens-css-classes'),
                    'ttos' => esc_html__('Type to search...', 'studio-immens-css-classes'),
                    'livep' => esc_html__('Live Preview', 'studio-immens-css-classes'),
                    'nofound' => esc_html__('No classes found', 'studio-immens-css-classes'),
                ]
            ]);
            wp_enqueue_style(
                'sicc-pure-css',
                SI_CSS_CLASS_URL . 'includes/pure-min.css',
                array(),
                '3.0.0'
            );
        }
//Bulma
        if (isset($cssSettings['enable_bulma']) && $cssSettings['enable_bulma'] == '1') {
            wp_enqueue_script('sicc-css-bulma-editor', SI_CSS_CLASS_URL . 'assets/bulma-editor.js', ['wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks'], SI_CSS_CLASS_VERSION, true );
            wp_localize_script('sicc-css-bulma-editor', 'siCssDataBl', [
                'classes' => get_option('sicc_css_bulma_classes', []),
                'labels' => [
                    'title' => esc_html__('Bulma Classes', 'studio-immens-css-classes'),
                    'select' => esc_html__('Select Class', 'studio-immens-css-classes'),
                    'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
                    'search' => esc_html__('Search Classes', 'studio-immens-css-classes'),
                    'ttos' => esc_html__('Type to search...', 'studio-immens-css-classes'),
                    'livep' => esc_html__('Live Preview', 'studio-immens-css-classes'),
                    'nofound' => esc_html__('No classes found', 'studio-immens-css-classes'),
                ]
            ]);
            wp_enqueue_style(
                'sicc-bulma-css',
                SI_CSS_CLASS_URL . 'includes/bulma.min.css',
                array(),
                '1.0.0'
            );
        }
//Uikit
        if (isset($cssSettings['enable_uikit']) && $cssSettings['enable_uikit'] == '1') {
            wp_enqueue_script('sicc-css-uikit-editor', SI_CSS_CLASS_URL . 'assets/uikit-editor.js', ['wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks'], SI_CSS_CLASS_VERSION, true );
            wp_localize_script('sicc-css-uikit-editor', 'siCssDataUi', [
                'classes' => get_option('sicc_css_uikit_classes', []),
                'labels' => [
                    'title' => esc_html__('Uikit Classes', 'studio-immens-css-classes'),
                    'select' => esc_html__('Select Class', 'studio-immens-css-classes'),
                    'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
                    'search' => esc_html__('Search Classes', 'studio-immens-css-classes'),
                    'ttos' => esc_html__('Type to search...', 'studio-immens-css-classes'),
                    'livep' => esc_html__('Live Preview', 'studio-immens-css-classes'),
                    'nofound' => esc_html__('No classes found', 'studio-immens-css-classes'),
                ]
            ]);
            wp_enqueue_style(
                'sicc-uikit-css',
                SI_CSS_CLASS_URL . 'includes/uikit.min.css',
                array(),
                '3.20.4'
            );
        }
//Spectre
        if (isset($cssSettings['enable_spectre']) && $cssSettings['enable_spectre'] == '1') {
            wp_enqueue_script('sicc-css-spectre-editor', SI_CSS_CLASS_URL . 'assets/spectre-editor.js', ['wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks'], SI_CSS_CLASS_VERSION, true );
            wp_localize_script('sicc-css-spectre-editor', 'siCssDataSp', [
                'classes' => get_option('sicc_css_spectre_classes', []),
                'labels' => [
                    'title' => esc_html__('Spectre Classes', 'studio-immens-css-classes'),
                    'select' => esc_html__('Select Class', 'studio-immens-css-classes'),
                    'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
                    'search' => esc_html__('Search Classes', 'studio-immens-css-classes'),
                    'ttos' => esc_html__('Type to search...', 'studio-immens-css-classes'),
                    'livep' => esc_html__('Live Preview', 'studio-immens-css-classes'),
                    'nofound' => esc_html__('No classes found', 'studio-immens-css-classes'),
                ]
            ]);
            wp_enqueue_style(
                'sicc-spectre-css',
                SI_CSS_CLASS_URL . 'includes/spectre.min.css',
                array(),
                '0.5.9'
            );
        }
//Semantic
        if (isset($cssSettings['enable_semantic']) && $cssSettings['enable_semantic'] == '1') {
            wp_enqueue_script('sicc-css-semantic-editor', SI_CSS_CLASS_URL . 'assets/semantic-editor.js', ['wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks'], SI_CSS_CLASS_VERSION, true );
            wp_localize_script('sicc-css-semantic-editor', 'siCssDataSe', [
                'classes' => get_option('sicc_css_semantic_classes', []),
                'labels' => [
                    'title' => esc_html__('Semantic Classes', 'studio-immens-css-classes'),
                    'select' => esc_html__('Select Class', 'studio-immens-css-classes'),
                    'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
                    'search' => esc_html__('Search Classes', 'studio-immens-css-classes'),
                    'ttos' => esc_html__('Type to search...', 'studio-immens-css-classes'),
                    'livep' => esc_html__('Live Preview', 'studio-immens-css-classes'),
                    'nofound' => esc_html__('No classes found', 'studio-immens-css-classes'),
                ]
            ]);
            wp_enqueue_style(
                'sicc-semantic-css',
                SI_CSS_CLASS_URL . 'includes/semantic.min.css',
                array(),
                '2.5.0'
            );
        }

    }

    public function sicc_studioimmens_register_css_block() {
        wp_enqueue_script( 'studioimmens-css-block', SI_CSS_CLASS_URL . 'assets/studioimmens-css-block.js', array( 'wp-blocks','wp-element','wp-block-editor','wp-components','wp-i18n','wp-hooks' ), SI_CSS_CLASS_VERSION, true );
        wp_localize_script('studioimmens-css-block', 'siCssDataBlock', [
            'labels' => [
                'title' => esc_html__('Custom CSS', 'studio-immens-css-classes'),
                'desc' => esc_html__('Enter CSS to apply to this post:', 'studio-immens-css-classes'),
            ]
        ]);
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
            'name' => (!empty( sanitize_text_field(wp_unslash($_POST['name'])) ))?sanitize_text_field(wp_unslash($_POST['name'])):'',
            'css' =>  (!empty( sanitize_text_field(wp_unslash($_POST['css'])) ))?sanitize_text_field(wp_unslash($_POST['css'])):'',
            'hover' =>  (!empty( sanitize_text_field(wp_unslash($_POST['hover'])) ))?sanitize_text_field(wp_unslash($_POST['hover'])):'',
            'focus' =>  (!empty( sanitize_text_field(wp_unslash($_POST['focus'])) ))?sanitize_text_field(wp_unslash($_POST['focus'])):'',
        );
        
        $classes[] = $new_class;
        update_option('sicc_css_classes', $classes);
        wp_send_json_success($new_class);
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
        $id = (!empty( sanitize_text_field(wp_unslash($_POST['id'])) ))?sanitize_text_field(wp_unslash($_POST['id'])):'';

        foreach ($classes as $key => $value) {
            if (isset($value['id']) && $value['id'] == $id) {
                $classes[$key] = array(
                    'id' => $id,
                    'name' => (!empty( sanitize_text_field(wp_unslash($_POST['name'])) ))?sanitize_text_field(wp_unslash($_POST['name'])):'',
                    'css' =>  (!empty( sanitize_text_field(wp_unslash($_POST['css'])) ))?sanitize_text_field(wp_unslash($_POST['css'])):'',
                    'hover' =>  (!empty( sanitize_text_field(wp_unslash($_POST['hover'])) ))?sanitize_text_field(wp_unslash($_POST['hover'])):'',
                    'focus' =>  (!empty( sanitize_text_field(wp_unslash($_POST['focus'])) ))?sanitize_text_field(wp_unslash($_POST['focus'])):'',
                );
            }
        }
        
        update_option('sicc_css_classes', $classes);
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
        wp_send_json_success();
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