<?php
/*
Plugin Name: Studio Immens CSS Classes
Description: Aggiungi classi CSS personalizzate ai blocchi Gutenberg con anteprima live.
Version: 1.0.0
Author: Studio Immens
Text Domain: studioimmens-css
Domain Path: /languages
*/

// === FILE PRINCIPALE: studioimmens-css-classes.php ===
define('SI_CSS_PATH', plugin_dir_path(__FILE__));
define('SI_CSS_URL', plugin_dir_url(__FILE__));

class StudioImmens_CSS_Classes {
    public function __construct() {
        // Verifica compatibilità minima con WordPress
        if (version_compare(get_bloginfo('version'), '5.0', '<')) {
            add_action('admin_notices', [$this, 'compatibility_notice']);
            return;
        }
        // Admin
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
        
        // Editor
        add_action('enqueue_block_editor_assets', [$this, 'editor_assets']);
        
        // Traduzioni
        load_plugin_textdomain('studioimmens-css', false, basename(dirname(__FILE__)) . '/languages');

        add_action('wp_ajax_nopriv_si_get_css_classes', function() {
            wp_send_json_error('Not logged in', 401);
        });

        // Aggiungi handler AJAX
        add_action('wp_ajax_si_save_css_class', [$this, 'ajax_save_class']);
        add_action('wp_ajax_si_get_css_classes', [$this, 'ajax_get_classes']);
        add_action('wp_ajax_si_edit_css_class', [$this, 'ajax_edit_class']);
        add_action('wp_ajax_si_delete_css_class', [$this, 'ajax_delete_class']);

        // Logga errori AJAX
        add_filter('wp_php_error_message', function($message) {
            error_log('[CSS Classes] AJAX Error: ' . $message);
            return $message;
        }, 10, 2);

        add_action('wp_footer', [$this, 'css_constructor']);
        add_action('admin_footer', [$this, 'css_constructor']);
    }

    public function css_constructor() {
        $classes = get_option('si_css_classes', []);
        echo '<style id="studioImmensCss">';
        foreach ($classes as $key => $value) {
                if (isset($value['name']) && isset($value['css'])) {
                    echo ".".$value['name']." { ";
                        echo $value['css'];
                    echo "} ";
                }
                if (isset($value['name']) && isset($value['hover'])) {
                    echo ".".$value['name'].":hover { ";
                         echo $value['hover'];
                    echo "} ";
                }
                if (isset($value['name']) && isset($value['focus'])) {
                    echo ".".$value['name'].":focus { ";
                        echo $value['focus'];
                    echo "} ";
                }
        }
        echo "</style>";
    }

    public function compatibility_notice() {
        echo '<div class="error"><p>';
        printf(
            __('Studio Immens CSS Classes requires WordPress 5.0 or higher. Your version is %s.', 'studioimmens-css'),
            get_bloginfo('version')
        );
        echo '</p></div>';
    }

    // Menu admin
    public function admin_menu() {
        add_menu_page(
            __('CSS Classes', 'studioimmens-css'),
            __('CSS Classes', 'studioimmens-css'),
            'manage_options',
            'studioimmens-css',
            [$this, 'admin_page'],
            'dashicons-art',
            80
        );
    }

    // Pagina admin
    public function admin_page() {
        include SI_CSS_PATH . 'admin/admin-ui.php';
    }

    // Script admin
    public function admin_scripts($hook) {
        if ($hook === 'toplevel_page_studioimmens-css') {
            wp_enqueue_style('si-css-admin', SI_CSS_URL . 'assets/admin.css');
            wp_enqueue_script('si-css-admin', SI_CSS_URL . 'assets/admin.js', ['jquery'], null, true);

             wp_localize_script('si-css-admin', 'siCssAdmin', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('si_css_nonce'),
                'delete' => __('Delete', 'studioimmens-css'),
                'preview' => __('Preview', 'studioimmens-css'),
                'edit' => __('Edit', 'studioimmens-css')
            ]);
        }
    }

    // Script editor
    public function editor_assets() {
        // Verifica che siamo nell'editor
        if (!function_exists('get_current_screen') || !get_current_screen()->is_block_editor()) {
            return;
        }

        wp_enqueue_script(
            'si-css-editor',
            SI_CSS_URL . 'assets/editor.js',
            ['wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-block-editor', 'wp-hooks'],
            '1.0.0',
            true
        );
        
        wp_localize_script('si-css-editor', 'siCssData', [
            'classes' => get_option('si_css_classes', []),
            'labels' => [
                'title' => __('CSS Classes', 'studioimmens-css'),
                'select' => __('Select Class', 'studioimmens-css'),
                'preview' => __('Preview', 'studioimmens-css')
            ]
        ]);
        
        wp_enqueue_style('si-css-editor', SI_CSS_URL . 'assets/editor.css');
    }

        // Handler AJAX per salvataggio
    public function ajax_save_class() {
        check_ajax_referer('si_css_nonce', 'security');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized', 403);
        }
        
        // Valida i dati
        if (empty($_POST['name']) || empty($_POST['css'])) {
            wp_send_json_error('Invalid data', 400);
        }
        
        $classes = get_option('si_css_classes', []);
        if ($classes=='') {
            $classes = array();
        }
        
        $new_class = array(
            'id' => uniqid(),
            'name' => sanitize_text_field($_POST['name']),
            'css' => sanitize_textarea_field($_POST['css']),
            'hover' => sanitize_textarea_field($_POST['hover']),
            'focus' => sanitize_textarea_field($_POST['focus'])
        );
        
        $classes[] = $new_class;
        update_option('si_css_classes', $classes);
        wp_send_json_success($new_class);
    }

            // Handler AJAX per update
    public function ajax_edit_class() {
        check_ajax_referer('si_css_nonce', 'security');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized', 403);
        }

        // Valida i dati
        if (empty($_POST['name']) || empty($_POST['css'])) {
            wp_send_json_error('Invalid data', 400);
        }
        
        $classes = get_option('si_css_classes', []);
        $id = sanitize_text_field($_POST['id']);

        foreach ($classes as $key => $value) {
            if (isset($value['id']) && $value['id'] == $id) {
                $classes[$key] = array(
                    'id' => $id,
                    'name' => sanitize_text_field($_POST['name']),
                    'css' => sanitize_textarea_field($_POST['css']),
                    'hover' => sanitize_textarea_field($_POST['hover']),
                    'focus' => sanitize_textarea_field($_POST['focus'])
                );
            }
        }
        
        update_option('si_css_classes', $classes);
        wp_send_json_success($classes);
    }
    
    // Handler AJAX per ottenere le classi
    public function ajax_get_classes() {
        check_ajax_referer('si_css_nonce', 'security');
        wp_send_json_success(get_option('si_css_classes', []));
    }
    
    // Handler AJAX per eliminare
    public function ajax_delete_class() {
        check_ajax_referer('si_css_nonce', 'security');
        
        if (!current_user_can('manage_options') || empty($_POST['id'])) {
            wp_send_json_error('Invalid request', 400);
        }
        
        $classes = get_option('si_css_classes', []);
        $id = sanitize_text_field($_POST['id']);
        
        $updated = array_filter($classes, function($cls) use ($id) {
            return $cls['id'] !== $id;
        });
        
        update_option('si_css_classes', array_values($updated));
        wp_send_json_success();
    }
}

new StudioImmens_CSS_Classes();

// Registra opzioni
register_activation_hook(__FILE__, function() {
    add_option('si_css_classes', []);
});