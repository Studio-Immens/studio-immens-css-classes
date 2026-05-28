<div class="wrap si-css-admin">
  <div class="si-admin-header">
    <div class="si-header-left">
      <img src="<?php echo esc_url(SI_CSS_CLASS_URL . 'assets/classyblocks-icon.png'); ?>" class="si-header-icon" alt="">
      <div>
        <h1><?php esc_html_e('ClassyBlocks', 'studio-immens-css-classes'); ?></h1>
        <p class="si-subtitle"><?php esc_html_e('Create and manage your custom CSS classes for Gutenberg.', 'studio-immens-css-classes'); ?></p>
      </div>
    </div>
    <a href="https://studioimmens.com/classyblocks-pro/" target="_blank" class="cb-btn cb-btn-pro">
      <span class="dashicons dashicons-unlock" style="font-size:16px;width:16px;height:16px;"></span>
      <?php esc_html_e('Discover ClassyBlocks Pro', 'studio-immens-css-classes'); ?>
    </a>
  </div>

  <div class="cb-pro-stats">
    <span class="cb-pro-stat cb-pro-stat-active" data-category="">
      <strong id="si-stat-classes">0</strong>
      <span><?php esc_html_e('All', 'studio-immens-css-classes'); ?></span>
    </span>
    <span class="cb-pro-stat" data-category="hover">
      <strong id="si-stat-hover">0</strong>
      <span><?php esc_html_e('With :hover', 'studio-immens-css-classes'); ?></span>
    </span>
    <span class="cb-pro-stat" data-category="focus">
      <strong id="si-stat-focus">0</strong>
      <span><?php esc_html_e('With :focus', 'studio-immens-css-classes'); ?></span>
    </span>
    <span class="cb-pro-stat" data-category="active">
      <strong id="si-stat-active">0</strong>
      <span><?php esc_html_e('With :active', 'studio-immens-css-classes'); ?></span>
    </span>
    <span class="cb-pro-stat" data-category="visited">
      <strong id="si-stat-visited">0</strong>
      <span><?php esc_html_e('With :visited', 'studio-immens-css-classes'); ?></span>
    </span>
    <span class="cb-pro-stat" data-category="base">
      <strong id="si-stat-base">0</strong>
      <span><?php esc_html_e('Base only', 'studio-immens-css-classes'); ?></span>
    </span>
  </div>

  <div class="cb-pro-toolbar">
    <button type="button" class="button button-primary" id="si-add-class">
      <span class="dashicons dashicons-plus"></span> <?php esc_html_e('New CSS Class', 'studio-immens-css-classes'); ?>
    </button>
    <input type="text" id="si-class-search" placeholder="<?php esc_attr_e('Search class...', 'studio-immens-css-classes'); ?>" class="cb-pro-search">
    <select id="sicc-pack-filter" class="cb-pro-pack-filter">
      <option value=""><?php esc_html_e('All packs', 'studio-immens-css-classes'); ?></option>
      <?php
      $packs = get_option('sicc_css_packs', []);
      foreach ($packs as $slug => $pack) {
        echo '<option value="' . esc_attr($slug) . '">' . esc_html($pack['name']) . '</option>';
      }
      ?>
      <option value="__none"><?php esc_html_e('No pack', 'studio-immens-css-classes'); ?></option>
    </select>
  </div>

  <div class="cb-playmode-bar">
    <span class="cb-playmode-label"><?php esc_html_e('Preview:', 'studio-immens-css-classes'); ?></span>
    <label class="cb-playmode-option cb-playmode-active" data-mode="base">
      <input type="radio" name="si-preview-mode" value="base" checked>
      <span><?php esc_html_e('Base', 'studio-immens-css-classes'); ?></span>
    </label>
    <label class="cb-playmode-option" data-mode="hover">
      <input type="radio" name="si-preview-mode" value="hover">
      <span><?php esc_html_e(':hover', 'studio-immens-css-classes'); ?></span>
    </label>
    <label class="cb-playmode-option" data-mode="active">
      <input type="radio" name="si-preview-mode" value="active">
      <span><?php esc_html_e(':active', 'studio-immens-css-classes'); ?></span>
    </label>
    <label class="cb-playmode-option" data-mode="focus">
      <input type="radio" name="si-preview-mode" value="focus">
      <span><?php esc_html_e(':focus', 'studio-immens-css-classes'); ?></span>
    </label>
    <label class="cb-playmode-option" data-mode="visited">
      <input type="radio" name="si-preview-mode" value="visited">
      <span><?php esc_html_e(':visited', 'studio-immens-css-classes'); ?></span>
    </label>
    <button type="button" class="cb-btn cb-btn-sm" id="cb-auto-preview" style="margin-left:auto;">
      ▶ <?php esc_html_e('Auto', 'studio-immens-css-classes'); ?>
    </button>
    <button type="button" class="cb-btn cb-btn-sm" id="cb-interactive-preview">
      🖱 <?php esc_html_e('Interactive', 'studio-immens-css-classes'); ?>
    </button>
  </div>

  <div id="si-classes-container" class="cb-animations-grid">
    <div class="cb-loading">
      <div class="cb-spinner"></div>
      <p><?php esc_html_e('Loading classes...', 'studio-immens-css-classes'); ?></p>
    </div>
  </div>

  <div id="si-class-modal" class="cb-modal" style="display:none;">
    <div class="cb-modal-backdrop"></div>
    <div class="cb-modal-content">
      <div class="cb-modal-header">
        <h2 id="si-modal-title"><?php esc_html_e('New CSS Class', 'studio-immens-css-classes'); ?></h2>
        <button type="button" class="cb-modal-close dashicons dashicons-no-alt"></button>
      </div>
      <div class="cb-modal-body">
        <form id="si-class-form">
          <input type="hidden" id="si-class-id" value="">

          <div class="cb-form-group">
            <label for="si-class-name"><?php esc_html_e('Class Name', 'studio-immens-css-classes'); ?></label>
            <input type="text" id="si-class-name" class="regular-text" placeholder="<?php esc_attr_e('e.g. my-custom-class', 'studio-immens-css-classes'); ?>" required>
            <small><?php esc_html_e('Without the dot (e.g. "my-class")', 'studio-immens-css-classes'); ?></small>
          </div>

          <div class="cb-form-group">
            <label for="si-class-desc"><?php esc_html_e('Description', 'studio-immens-css-classes'); ?></label>
            <input type="text" id="si-class-desc" class="regular-text" placeholder="<?php esc_attr_e('Brief description of the class (e.g. "Centers content")', 'studio-immens-css-classes'); ?>" maxlength="80">
            <small><?php esc_html_e('Max 80 characters. Appears below the class name.', 'studio-immens-css-classes'); ?></small>
          </div>

          <div class="cb-form-group">
            <label for="si-class-css"><?php esc_html_e('CSS Code', 'studio-immens-css-classes'); ?></label>
            <textarea id="si-class-css" rows="5" class="cb-code-editor" placeholder="<?php esc_attr_e('color: red; font-size: 18px;', 'studio-immens-css-classes'); ?>" required></textarea>
          </div>

          <div class="cb-form-group">
            <label for="si-hover-css"><?php esc_html_e(':hover', 'studio-immens-css-classes'); ?></label>
            <textarea id="si-hover-css" rows="4" class="cb-code-editor"></textarea>
            <small><?php esc_html_e('Optional. CSS applied on mouse hover.', 'studio-immens-css-classes'); ?></small>
          </div>

          <div class="cb-form-group">
            <label for="si-active-css"><?php esc_html_e(':active', 'studio-immens-css-classes'); ?></label>
            <textarea id="si-active-css" rows="4" class="cb-code-editor"></textarea>
            <small><?php esc_html_e('Optional. CSS applied when element is clicked/active.', 'studio-immens-css-classes'); ?></small>
          </div>

          <div class="cb-form-group">
            <label for="si-focus-css"><?php esc_html_e(':focus', 'studio-immens-css-classes'); ?></label>
            <textarea id="si-focus-css" rows="4" class="cb-code-editor"></textarea>
            <small><?php esc_html_e('Optional. CSS applied when the element receives focus.', 'studio-immens-css-classes'); ?></small>
          </div>

          <div class="cb-form-group">
            <label for="si-visited-css"><?php esc_html_e(':visited', 'studio-immens-css-classes'); ?></label>
            <textarea id="si-visited-css" rows="4" class="cb-code-editor"></textarea>
            <small><?php esc_html_e('Optional. CSS applied to visited links.', 'studio-immens-css-classes'); ?></small>
          </div>

          <div class="cb-form-group">
            <label><?php esc_html_e('Preview', 'studio-immens-css-classes'); ?></label>
            <div id="si-preview-box" class="cb-anim-preview-box" style="min-height:90px;">
              <div id="si-preview-content" class="cb-preview-content">
                <div class="sicc-preview-flex" style="display:flex;gap:8px;align-items:center;justify-content:center;width:100%;">
                  <div class="sicc-preview-demo" style="width:50px;height:50px;background:linear-gradient(135deg,#2271b1,#7c3aed);border-radius:8px;"></div>
                  <div class="sicc-preview-demo" style="width:40px;height:40px;background:linear-gradient(135deg,#7c3aed,#10b981);border-radius:8px;"></div>
                  <div class="sicc-preview-demo" style="width:30px;height:30px;background:linear-gradient(135deg,#10b981,#f59e0b);border-radius:8px;"></div>
                </div>
              </div>
            </div>
            <button type="button" id="si-test-class" class="button"><?php esc_html_e('Test Class', 'studio-immens-css-classes'); ?></button>
          </div>

          <div class="cb-form-actions">
            <button type="submit" class="button button-primary">
              <span class="dashicons dashicons-saved" style="font-size:14px;width:14px;height:14px;"></span>
              <?php esc_html_e('Save Class', 'studio-immens-css-classes'); ?>
            </button>
            <button type="button" class="button cb-modal-close"><?php esc_html_e('Cancel', 'studio-immens-css-classes'); ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div style="margin-top:24px;padding:12px 0;border-top:1px solid var(--cb-border);text-align:center;font-size:0.85em;color:var(--cb-text-muted);">
    <?php esc_html_e('Made by', 'studio-immens-css-classes'); ?>
    <a href="https://studioimmens.com" target="_blank" style="color:var(--cb-primary);text-decoration:none;">Studio Immens</a>
    — <a href="https://studioimmens.com/classyblocks-pro/" target="_blank" style="color:var(--cb-primary);text-decoration:none;"><?php esc_html_e('Discover ClassyBlocks Pro', 'studio-immens-css-classes'); ?></a>
  </div>
</div>
