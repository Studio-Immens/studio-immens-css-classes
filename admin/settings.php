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

                        <div class="si-form-group si-box">
                            <h3>INFO: enable</h3>
                            <P><?php esc_html_e('When enabled, the Framework classes will be listed and selectable in the editor and inserted into the site’s front-end.', 'studio-immens-css-classes'); ?></P>

                            <h3>INFO: display</h3>
                            <P><?php esc_html_e('If enabled, the Framework will be added to the main scripts in the editor or admin side, but this may cause problems or strange effects because frameworks often have the same class name or ID, etc.', 'studio-immens-css-classes'); ?></P>
                        </div>

                        <div class="si-box">
        <!-- Bootstrap --><div class="si-form-group">
                            <label for="si_enable_bootstrap">
                                <input type="checkbox" name="sicc_css_settings[enable_bootstrap]" id="si_enable_bootstrap" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_bootstrap'] ?? 0); ?>>
                                <?php esc_html_e('enable Bootstrap CSS', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_editor_bootstrap">
                                <input type="checkbox" name="sicc_css_settings[disp_edit_bootstrap]" id="si_display_in_editor_bootstrap" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_edit_bootstrap'] ?? 0); ?>>
                                <?php esc_html_e('display Bootstrap CSS in editor', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_admin_bootstrap">
                                <input type="checkbox" name="sicc_css_settings[disp_admin_bootstrap]" id="si_display_in_admin_bootstrap" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_admin_bootstrap'] ?? 0); ?>>
                                <?php esc_html_e('display Bootstrap CSS in admin', 'studio-immens-css-classes'); ?>
                            </label>
        <!-- Bootstrap --></div> 
        <!-- Materialize --><div class="si-form-group">
                            <label for="si_enable_materialize">
                                <input type="checkbox" name="sicc_css_settings[enable_materialize]" id="si_enable_materialize" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_materialize'] ?? 0); ?>>
                                <?php esc_html_e('enable Materialize CSS', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_editor_materialize">
                                <input type="checkbox" name="sicc_css_settings[disp_edit_materialize]" id="si_display_in_editor_materialize" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_edit_materialize'] ?? 0); ?>>
                                <?php esc_html_e('display Materialize CSS in editor', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_admin_materialize">
                                <input type="checkbox" name="sicc_css_settings[disp_admin_materialize]" id="si_display_in_admin_materialize" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_admin_materialize'] ?? 0); ?>>
                                <?php esc_html_e('display Materialize CSS in admin', 'studio-immens-css-classes'); ?>
                            </label>

        <!-- Materialize --></div>
        <!-- Pure --><div class="si-form-group">
                            <label for="si_enable_pure">
                                <input type="checkbox" name="sicc_css_settings[enable_pure]" id="si_enable_pure" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_pure'] ?? 0); ?>>
                                <?php esc_html_e('enable Pure CSS', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_editor_pure">
                                <input type="checkbox" name="sicc_css_settings[disp_edit_pure]" id="si_display_in_editor_pure" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_edit_pure'] ?? 0); ?>>
                                <?php esc_html_e('display Pure CSS in editor', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_admin_pure">
                                <input type="checkbox" name="sicc_css_settings[disp_admin_pure]" id="si_display_in_admin_pure" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_admin_pure'] ?? 0); ?>>
                                <?php esc_html_e('display Pure CSS in admin', 'studio-immens-css-classes'); ?>
                            </label>

        <!-- Pure --></div>
        <!-- Bulma --><div class="si-form-group">
                            <label for="si_enable_bulma">
                                <input type="checkbox" name="sicc_css_settings[enable_bulma]" id="si_enable_bulma" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_bulma'] ?? 0); ?>>
                                <?php esc_html_e('enable Bulma CSS', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_editor_bulma">
                                <input type="checkbox" name="sicc_css_settings[disp_edit_bulma]" id="si_display_in_editor_bulma" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_edit_bulma'] ?? 0); ?>>
                                <?php esc_html_e('display Bulma CSS in editor', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_admin_bulma">
                                <input type="checkbox" name="sicc_css_settings[disp_admin_bulma]" id="si_display_in_admin_bulma" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_admin_bulma'] ?? 0); ?>>
                                <?php esc_html_e('display Bulma CSS in admin', 'studio-immens-css-classes'); ?>
                            </label>
        <!-- Bulma --></div>
        <!-- Uikit --><div class="si-form-group">
                            <label for="si_enable_uikit">
                                <input type="checkbox" name="sicc_css_settings[enable_uikit]" id="si_enable_uikit" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_uikit'] ?? 0); ?>>
                                <?php esc_html_e('enable Uikit CSS', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_editor_uikit">
                                <input type="checkbox" name="sicc_css_settings[disp_edit_uikit]" id="si_display_in_editor_uikit" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_edit_uikit'] ?? 0); ?>>
                                <?php esc_html_e('display Uikit CSS in editor', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_admin_uikit">
                                <input type="checkbox" name="sicc_css_settings[disp_admin_uikit]" id="si_display_in_admin_uikit" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_admin_uikit'] ?? 0); ?>>
                                <?php esc_html_e('display Uikit CSS in admin', 'studio-immens-css-classes'); ?>
                            </label>
        <!-- Uikit --></div>
        <!-- Spectre --><div class="si-form-group">
                            <label for="si_enable_spectre">
                                <input type="checkbox" name="sicc_css_settings[enable_spectre]" id="si_enable_spectre" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_spectre'] ?? 0); ?>>
                                <?php esc_html_e('enable Spectre CSS', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_editor_spectre">
                                <input type="checkbox" name="sicc_css_settings[disp_edit_spectre]" id="si_display_in_editor_spectre" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_edit_spectre'] ?? 0); ?>>
                                <?php esc_html_e('display Spectre CSS in editor', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_admin_spectre">
                                <input type="checkbox" name="sicc_css_settings[disp_admin_spectre]" id="si_display_in_admin_spectre" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_admin_spectre'] ?? 0); ?>>
                                <?php esc_html_e('display Spectre CSS in admin', 'studio-immens-css-classes'); ?>
                            </label>
        <!-- Spectre --></div>
        <!-- Semantic --><div class="si-form-group">
                            <label for="si_enable_semantic">
                                <input type="checkbox" name="sicc_css_settings[enable_semantic]" id="si_enable_semantic" value="1" <?php checked(1, get_option('sicc_css_settings')['enable_semantic'] ?? 0); ?>>
                                <?php esc_html_e('enable Semantic CSS', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_editor_semantic">
                                <input type="checkbox" name="sicc_css_settings[disp_edit_semantic]" id="si_display_in_editor_semantic" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_edit_semantic'] ?? 0); ?>>
                                <?php esc_html_e('display Semantic CSS in editor', 'studio-immens-css-classes'); ?>
                            </label>

                            <label for="si_display_in_admin_semantic">
                                <input type="checkbox" name="sicc_css_settings[disp_admin_semantic]" id="si_display_in_admin_semantic" value="1" <?php checked(1, get_option('sicc_css_settings')['disp_admin_semantic'] ?? 0); ?>>
                                <?php esc_html_e('display Semantic CSS in admin', 'studio-immens-css-classes'); ?>
                            </label>
        <!-- Semantic --></div>
                    </div>
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
        <script type="text/javascript">

            allToggleInputFramework();

            jQuery('input').on('change', function() {
                allToggleInputFramework();
            });

            function allToggleInputFramework() {
                toggleInputFramework('#si_enable_bootstrap', '#si_display_in_editor_bootstrap', '#si_display_in_admin_bootstrap');
                toggleInputFramework('#si_enable_materialize', '#si_display_in_editor_materialize', '#si_display_in_admin_materialize');
                toggleInputFramework('#si_enable_pure', '#si_display_in_editor_pure', '#si_display_in_admin_pure');
                toggleInputFramework('#si_enable_bulma', '#si_display_in_editor_bulma', '#si_display_in_admin_bulma');
                toggleInputFramework('#si_enable_uikit', '#si_display_in_editor_uikit', '#si_display_in_admin_uikit');
                toggleInputFramework('#si_enable_spectre', '#si_display_in_editor_spectre', '#si_display_in_admin_spectre');
                toggleInputFramework('#si_enable_semantic', '#si_display_in_editor_semantic', '#si_display_in_admin_semantic');
            }

            function toggleInputFramework(is_enable, is_disp, is_disp_adm = '') {
                if (jQuery(is_enable).is(':checked')) {
                    jQuery(is_disp).parent().show();
                    jQuery(is_disp_adm).parent().show();
                } else {
                    jQuery(is_disp).parent().hide();
                    jQuery(is_disp_adm).parent().hide();
                }
            }
            
        </script>

        <?php submit_button(__('Save Settings', 'studio-immens-css-classes')); ?>
    </form>
</div>
