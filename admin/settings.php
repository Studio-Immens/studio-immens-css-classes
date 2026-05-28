<div class="wrap si-css-admin">
  <div class="si-admin-header">
    <div class="si-header-left">
      <img src="<?php echo esc_url(SI_CSS_CLASS_URL . 'assets/classyblocks-icon.png'); ?>" class="si-header-icon" alt="">
      <div>
        <h1><?php esc_html_e('ClassyBlocks - Settings', 'studio-immens-css-classes'); ?></h1>
      </div>
    </div>
  </div>

  <form method="post" action="options.php">
    <?php settings_fields('sicc_css_settings_group'); ?>
    <?php do_settings_sections('studio-immens-css-settings'); ?>

    <div class="si-settings-grid">
      <div>
        <div class="si-form-card" style="margin-bottom:20px;">
          <h2><?php esc_html_e('CSS Frameworks', 'studio-immens-css-classes'); ?></h2>
          <p style="font-size:0.9em;color:var(--cb-text-light);margin:0 0 16px 0;">
            <?php esc_html_e('Enable CSS frameworks to make their classes available in the Gutenberg editor.', 'studio-immens-css-classes'); ?>
          </p>

          <?php $frameworks = [
            'bootstrap'   => ['label' => 'Bootstrap',   'v' => SI_CSS_BOOTSTRAP_VERSION],
            'materialize' => ['label' => 'Materialize', 'v' => SI_CSS_MATERIALIZE_VERSION],
            'pure'        => ['label' => 'Pure CSS',    'v' => SI_CSS_PURE_VERSION],
            'bulma'       => ['label' => 'Bulma',       'v' => SI_CSS_BULMA_VERSION],
            'uikit'       => ['label' => 'UIkit',       'v' => SI_CSS_UIKIT_VERSION],
            'spectre'     => ['label' => 'Spectre',     'v' => SI_CSS_SPECTRE_VERSION],
            'semantic'    => ['label' => 'Semantic UI', 'v' => SI_CSS_SEMANTIC_VERSION],
            'foundation'  => ['label' => 'Foundation',  'v' => SI_CSS_FOUNDATION_VERSION],
            'tailwind'    => ['label' => 'Tailwind CSS', 'v' => SI_CSS_TAILWIND_VERSION],
          ];
          $cssSettings = get_option('sicc_css_settings', []);
          foreach ($frameworks as $key => $fw):
          ?>
          <div class="si-framework-group">
            <h3>
              <?php echo esc_html($fw['label']); ?>
              <span class="cb-version">v<?php echo esc_html($fw['v']); ?></span>
            </h3>
            <label>
              <input type="checkbox" name="sicc_css_settings[enable_<?php echo esc_attr($key); ?>]" value="1" <?php checked(1, $cssSettings['enable_' . $key] ?? 0); ?>>
              <?php esc_html_e('Enable', 'studio-immens-css-classes'); ?>
            </label>
            <label>
              <input type="checkbox" name="sicc_css_settings[disp_edit_<?php echo esc_attr($key); ?>]" value="1" <?php checked(1, $cssSettings['disp_edit_' . $key] ?? 0); ?>>
              <?php esc_html_e('Display in editor', 'studio-immens-css-classes'); ?>
            </label>
            <label>
              <input type="checkbox" name="sicc_css_settings[disp_admin_<?php echo esc_attr($key); ?>]" value="1" <?php checked(1, $cssSettings['disp_admin_' . $key] ?? 0); ?>>
              <?php esc_html_e('Display in admin', 'studio-immens-css-classes'); ?>
            </label>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div>
        <div class="si-preview-card" style="margin-bottom:20px;">
          <h2><?php esc_html_e('Tailwind Configuration', 'studio-immens-css-classes'); ?></h2>
          <div style="padding:16px;">
            <div class="si-form-group">
              <textarea name="sicc_css_tailwind_config" rows="8" style="width:100%;font-family:monospace;background:#f6f7f7;padding:10px;border:1px solid #c3c4c7;border-radius:6px;font-size:0.85em;"><?php echo esc_textarea(get_option('sicc_css_tailwind_config', '')); ?></textarea>
              <small style="display:block;color:var(--cb-text-muted);margin-top:4px;"><?php esc_html_e('tailwind.config object in JSON format.', 'studio-immens-css-classes'); ?></small>
            </div>
          </div>
        </div>

        <div class="si-preview-card" style="margin-bottom:20px;">
          <h2><?php esc_html_e('Backup & Tools', 'studio-immens-css-classes'); ?></h2>
          <div style="padding:16px;">
            <div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;">
              <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=studioimmens-css-settings&action=sicc_export'), 'sicc_export_nonce')); ?>" class="cb-btn cb-btn-primary cb-btn-sm">
                <span class="dashicons dashicons-download" style="font-size:14px;width:14px;height:14px;"></span>
                <?php esc_html_e('Export Data (JSON)', 'studio-immens-css-classes'); ?>
              </a>
            </div>

            <hr style="border:none;border-top:1px solid var(--cb-border);margin:16px 0;">

            <h3 style="font-size:0.95em;font-weight:600;margin:0 0 10px 0;"><?php esc_html_e('Import Data', 'studio-immens-css-classes'); ?></h3>
            <form method="post" enctype="multipart/form-data" action="">
              <?php wp_nonce_field('sicc_import_nonce'); ?>
              <div class="si-form-group">
                <input type="file" name="sicc_import_file" accept=".json">
                <small style="display:block;color:var(--cb-text-muted);margin-top:4px;"><?php esc_html_e('Upload a JSON file exported from another site.', 'studio-immens-css-classes'); ?></small>
              </div>
              <input type="submit" name="sicc_import_submit" class="cb-btn cb-btn-secondary cb-btn-sm" value="<?php esc_attr_e('Upload & Import JSON', 'studio-immens-css-classes'); ?>">
            </form>

            <hr style="border:none;border-top:1px solid var(--cb-border);margin:16px 0;">

            <h3 style="font-size:0.95em;font-weight:600;margin:0 0 10px 0;"><?php esc_html_e('Reset Settings', 'studio-immens-css-classes'); ?></h3>
            <form method="post" action="" onsubmit="return confirm('<?php esc_attr_e('Are you sure you want to reset all settings? This action cannot be undone.', 'studio-immens-css-classes'); ?>');">
              <?php wp_nonce_field('sicc_reset_nonce'); ?>
              <p style="font-size:0.85em;color:var(--cb-text-muted);margin-bottom:10px;"><?php esc_html_e('Delete all custom classes, settings, and Tailwind configuration.', 'studio-immens-css-classes'); ?></p>
              <input type="submit" name="sicc_reset_submit" class="cb-btn cb-btn-danger cb-btn-sm" value="<?php esc_attr_e('Reset All Settings', 'studio-immens-css-classes'); ?>">
            </form>
          </div>
        </div>

        <div class="si-preview-card" style="margin-top:20px;">
          <div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
              <strong style="font-size:0.9em;">⚡ <?php esc_html_e('ClassyBlocks Pro', 'studio-immens-css-classes'); ?></strong>
              <span style="font-size:0.85em;color:var(--cb-text-muted);margin-left:6px;"><?php esc_html_e('Scroll-driven animations, Pack Manager & more.', 'studio-immens-css-classes'); ?></span>
            </div>
            <a href="https://studioimmens.com/classyblocks-pro/" target="_blank" class="cb-btn cb-btn-pro cb-btn-sm" style="text-decoration:none;white-space:nowrap;">
              <?php esc_html_e('Learn More', 'studio-immens-css-classes'); ?> →
            </a>
          </div>
        </div>
      </div>
    </div>

    <div style="margin-top:20px;">
      <?php submit_button(__('Save Settings', 'studio-immens-css-classes')); ?>
    </div>
  </form>
</div>
