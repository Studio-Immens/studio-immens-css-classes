<div class="si-css-admin wrap">
    <h1 class="si-title">
        <?php esc_html_e('Studio Immens - Settings', 'studio-immens-css-classes'); ?>
        <!-- <a href="https://studioimmens.com/support" class="immens-btn"><?php esc_html_e('Support & Docs', 'studio-immens-css-classes'); ?></a> -->
    </h1>

    <form method="post" action="options.php">
        <?php settings_fields('sicc_css_settings_group'); ?>
        <?php do_settings_sections('studio-immens-css-settings'); ?>

        <div class="si-form-container">
            <div class="si-form-column">
                <div class="si-form-box">
                    <h2><?php esc_html_e('Plugin Configuration', 'studio-immens-css-classes'); ?></h2>
    <!-- Bootstrap --><div class="si-form-group">
                        <label for="si_enable_bootstrap">
                            <input type="checkbox" name="sicc_css_settings[enable_bootstrap]" id="si_enable_bootstrap" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_bootstrap'] ?? 0); ?>>
                            <?php esc_html_e('enable Bootstrap CSS', 'studio-immens-css-classes'); ?>
                        </label>
                        <small><?php esc_html_e('When enabled, Bootstrap CSS classes will be listed and selectable in the editor and inserted into the site’s front-end.', 'studio-immens-css-classes'); ?></small>
    <!-- Bootstrap --></div> 
    <!-- Materialize --><div class="si-form-group">
                        <label for="si_enable_materialize">
                            <input type="checkbox" name="sicc_css_settings[enable_materialize]" id="si_enable_materialize" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_materialize'] ?? 0); ?>>
                            <?php esc_html_e('enable Materialize CSS', 'studio-immens-css-classes'); ?>
                        </label>
                        <small><?php esc_html_e('When enabled, Materialize CSS classes will be listed and selectable in the editor and inserted into the site’s front-end.', 'studio-immens-css-classes'); ?></small>
    <!-- Materialize --></div>
    <!-- Pure --><div class="si-form-group">
                        <label for="si_enable_pure">
                            <input type="checkbox" name="sicc_css_settings[enable_pure]" id="si_enable_pure" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_pure'] ?? 0); ?>>
                            <?php esc_html_e('enable Pure CSS', 'studio-immens-css-classes'); ?>
                        </label>
                        <small><?php esc_html_e('When enabled, Pure CSS classes will be listed and selectable in the editor and inserted into the site’s front-end.', 'studio-immens-css-classes'); ?></small>
    <!-- Pure --></div>
    <!-- Bulma --><div class="si-form-group">
                        <label for="si_enable_bulma">
                            <input type="checkbox" name="sicc_css_settings[enable_bulma]" id="si_enable_bulma" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_bulma'] ?? 0); ?>>
                            <?php esc_html_e('enable Bulma CSS', 'studio-immens-css-classes'); ?>
                        </label>
                        <small><?php esc_html_e('When enabled, Bulma CSS classes will be listed and selectable in the editor and inserted into the site’s front-end.', 'studio-immens-css-classes'); ?></small>
    <!-- Bulma --></div>
    <!-- UIKIT --><div class="si-form-group">
                        <label for="si_enable_uikit">
                            <input type="checkbox" name="sicc_css_settings[enable_uikit]" id="si_enable_uikit" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_uikit'] ?? 0); ?>>
                            <?php esc_html_e('enable UIKIT CSS', 'studio-immens-css-classes'); ?>
                        </label>
                        <small><?php esc_html_e('When enabled, UIKIT CSS classes will be listed and selectable in the editor and inserted into the site’s front-end.', 'studio-immens-css-classes'); ?></small>
    <!-- UIKIT --></div>
    <!-- Spectre --><div class="si-form-group">
                        <label for="si_enable_spectre">
                            <input type="checkbox" name="sicc_css_settings[enable_spectre]" id="si_enable_spectre" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_spectre'] ?? 0); ?>>
                            <?php esc_html_e('enable Spectre CSS', 'studio-immens-css-classes'); ?>
                        </label>
                        <small><?php esc_html_e('When enabled, Spectre CSS classes will be listed and selectable in the editor and inserted into the site’s front-end.', 'studio-immens-css-classes'); ?></small>
    <!-- Spectre --></div>
    <!-- Semantic --><div class="si-form-group">
                        <label for="si_enable_semantic">
                            <input type="checkbox" name="sicc_css_settings[enable_semantic]" id="si_enable_semantic" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_semantic'] ?? 0); ?>>
                            <?php esc_html_e('enable Semantic CSS', 'studio-immens-css-classes'); ?>
                        </label>
                        <small><?php esc_html_e('When enabled, Semantic CSS classes will be listed and selectable in the editor and inserted into the site’s front-end.', 'studio-immens-css-classes'); ?></small>
    <!-- Semantic --></div>
                </div>
            </div>

            <div class="si-preview-column">
                <div class="si-preview-box">
                    <h2><?php esc_html_e('Instructions', 'studio-immens-css-classes'); ?></h2>
                    <div class="si-instructions">
                        <p><?php esc_html_e('This plugin allows you to manage and inject custom CSS classes globally and inside the editor. Use this settings page to configure behavior.', 'studio-immens-css-classes'); ?></p>
                        <p><?php esc_html_e('Toggle features on/off using the checkboxes.', 'studio-immens-css-classes'); ?></p>
                    </div>
                </div>
            </div>

        </div>

        <?php submit_button(__('Save Settings', 'studio-immens-css-classes')); ?>
    </form>
</div>
