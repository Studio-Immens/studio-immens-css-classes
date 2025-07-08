<?php
/*
Plugin Name: Studio Immens CSS Classes
Description: Add custom CSS classes to Gutenberg blocks with live preview.
Version: 1.0.0
Author: Studio Immens
Text Domain: studio-immens-css-classes
Domain Path: /languages
License: GPL v2 or later
*/


// === FILE PRINCIPALE: studio-immens-css-classes.php ===
define('SI_CSS_PATH', plugin_dir_path(__FILE__));
define('SI_CSS_URL', plugin_dir_url(__FILE__));
define('SI_CSS_VERSION', '1.0.0');

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
        load_plugin_textdomain('studio-immens-css-classes', false, basename(dirname(__FILE__)) . '/languages');

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
            // error_log('[CSS Classes] AJAX Error: ' . $message);
            return $message;
        }, 10, 2);

        add_action('wp_footer', [$this, 'css_constructor']);
        add_action('admin_footer', [$this, 'css_constructor']);
        add_action('enqueue_block_editor_assets', [$this, 'css_constructor']);
    }

    public function css_constructor() {
        $css_output = '';
        $classes = get_option('si_css_classes', []);
        echo '<style id="studioImmensCss">';
        foreach ($classes as $key => $value) {
                if (isset($value['name']) && isset($value['css'])) {
                    echo ".".esc_attr($value['name'])." { ";
                        echo esc_attr($value['css']);
                    echo "} ";
                  $css_output .= '.'.$value['name'].'{'.$value['css'].'} ';
                }
                if (isset($value['name']) && isset($value['hover'])) {
                    echo ".".esc_attr($value['name']).":hover { ";
                         echo esc_attr($value['hover']);
                    echo "} ";
                  $css_output .= '.'.$value['name'].':hover {'.$value['css'].'} ';
                }
                if (isset($value['name']) && isset($value['focus'])) {
                    echo ".".esc_attr($value['name']).":focus { ";
                        echo esc_attr($value['focus']);
                    echo "} ";
                  $css_output .= '.'.$value['name'].':focus {'.$value['css'].'} ';
                }
        }
        echo "</style>";
        $output_file = SI_CSS_PATH . 'si-css.css';

        file_put_contents($output_file, $css_output);
    }

    public function compatibility_notice() {
        echo '<div class="error"><p>';
        printf(/* translators: CSS Classes requires WordPress 5.0 or higher */
            esc_html__('Studio Immens CSS Classes requires WordPress 5.0 or higher. Your version is %s.', 'studio-immens-css-classes'),
            esc_attr(get_bloginfo('version'))
        );
        echo '</p></div>';
    }

    // Menu admin
    public function admin_menu() {
        add_menu_page(
            esc_html__('CSS Classes', 'studio-immens-css-classes'),
            esc_html__('CSS Classes', 'studio-immens-css-classes'),
            'manage_options',
            'studioimmens-css',
            [$this, 'admin_page'],
            'dashicons-art',
            80
        );
        add_submenu_page(
            'studioimmens-css',
            esc_html__('Studio Immens', 'studio-immens-css-classes'),
            esc_html__('Studio Immens', 'studio-immens-css-classes'),
            'manage_options',
            'studioimmens-page',
            [$this, 'studioimmens_page']
        );
    }

    // Pagina admin
    public function admin_page() {
        include SI_CSS_PATH . 'admin/admin-ui.php';
    }

    public function studioimmens_page() {
        include SI_CSS_PATH . 'admin/studioimmens.php';
    }

    // Script admin
    public function admin_scripts($hook) {
        if ($hook === 'toplevel_page_studioimmens-css') {
            wp_enqueue_style('si-css-admin', SI_CSS_URL . 'assets/admin.css', array(), SI_CSS_VERSION );
            wp_enqueue_script('si-css-admin', SI_CSS_URL . 'assets/admin.js', ['jquery'], SI_CSS_VERSION, true);

             wp_localize_script('si-css-admin', 'siCssAdmin', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('si_css_nonce'),
                'delete' => esc_html__('Delete', 'studio-immens-css-classes'),
                'preview' => esc_html__('Preview', 'studio-immens-css-classes'),
                'edit' => esc_html__('Edit', 'studio-immens-css-classes'),
            ]);
        }
    }

    // Script editor
    public function editor_assets() {
        // Verifica che siamo nell'editor
        if (!function_exists('get_current_screen') || !get_current_screen()->is_block_editor()) {
            return;
        }

        wp_enqueue_style(
            'studio-immens-css-classes-editor',
            SI_CSS_URL . 'si-css.css',
            array('wp-edit-blocks'),
            SI_CSS_VERSION
        );

        wp_enqueue_script(
            'si-css-editor',
            SI_CSS_URL . 'assets/editor.js',
            ['wp-blocks', 'wp-edit-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-block-editor', 'wp-hooks'],
            SI_CSS_VERSION,
            true
        );
        
        wp_localize_script('si-css-editor', 'siCssData', [
            'classes' => get_option('si_css_classes', []),
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
        
        wp_enqueue_style('si-css-editor', SI_CSS_URL . 'assets/editor.css', array(), SI_CSS_VERSION );
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
            'name' => (!empty( sanitize_text_field(wp_unslash($_POST['name'])) ))?sanitize_text_field(wp_unslash($_POST['name'])):'',
            'css' =>  (!empty( sanitize_text_field(wp_unslash($_POST['css'])) ))?sanitize_text_field(wp_unslash($_POST['css'])):'',
            'hover' =>  (!empty( sanitize_text_field(wp_unslash($_POST['hover'])) ))?sanitize_text_field(wp_unslash($_POST['hover'])):'',
            'focus' =>  (!empty( sanitize_text_field(wp_unslash($_POST['focus'])) ))?sanitize_text_field(wp_unslash($_POST['focus'])):'',
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
        $id = sanitize_text_field(wp_unslash($_POST['id']));
        
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


function SI_CC_check( $var ){
    if ( isset($var) && !empty($var) ) {
        return true;
    }else{ 
        return false;
    }
}

