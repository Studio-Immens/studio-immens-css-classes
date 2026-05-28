<div class="wrap si-css-admin">
  <div class="si-admin-header">
    <div class="si-header-left">
      <img src="<?php echo esc_url(SI_CSS_CLASS_URL . 'assets/classyblocks-icon.png'); ?>" class="si-header-icon" alt="">
      <div>
        <h1><?php esc_html_e( 'ClassyBlocks - Packs', 'studio-immens-css-classes' ); ?></h1>
        <p class="si-subtitle"><?php esc_html_e( 'Import, create, export and delete CSS class packs.', 'studio-immens-css-classes' ); ?></p>
      </div>
    </div>
  </div>

  <div class="cb-pro-toolbar" style="margin-bottom:20px;">
    <button type="button" class="cb-btn cb-btn-primary" id="sicc-import-pack-btn">
      <span class="dashicons dashicons-upload" style="font-size:14px;width:14px;height:14px;"></span>
      <?php esc_html_e( 'Import Pack', 'studio-immens-css-classes' ); ?>
    </button>
    <input type="file" id="sicc-pack-file-input" accept=".json" style="display:none;">
    <button type="button" class="cb-btn cb-btn-secondary" id="sicc-create-pack-btn">
      <span class="dashicons dashicons-plus" style="font-size:14px;width:14px;height:14px;"></span>
      <?php esc_html_e( 'Create Pack', 'studio-immens-css-classes' ); ?>
    </button>
    <button type="button" class="cb-btn cb-btn-secondary" id="sicc-export-all-btn">
      <span class="dashicons dashicons-download" style="font-size:14px;width:14px;height:14px;"></span>
      <?php esc_html_e( 'Export All', 'studio-immens-css-classes' ); ?>
    </button>
  </div>

  <?php
  $packs = get_option( 'sicc_css_packs', [] );
  if ( empty( $packs ) ) :
  ?>
  <div class="cb-empty" style="padding:40px 20px;text-align:center;border:2px dashed var(--cb-border,#e2e8f0);border-radius:10px;background:#fafafa;">
    <p style="font-size:1.1em;color:var(--cb-text-muted,#8c8f94);margin:0;">
      <?php esc_html_e( 'No packs imported. Preset packs are loaded automatically.', 'studio-immens-css-classes' ); ?>
    </p>
  </div>
  <?php else : ?>
  <table class="wp-list-table widefat fixed striped" style="margin-top:0;">
    <thead>
      <tr>
        <th scope="col" style="width:30%;"><?php esc_html_e( 'Name', 'studio-immens-css-classes' ); ?></th>
        <th scope="col" style="width:15%;"><?php esc_html_e( 'Author', 'studio-immens-css-classes' ); ?></th>
        <th scope="col" style="width:10%;"><?php esc_html_e( 'Version', 'studio-immens-css-classes' ); ?></th>
        <th scope="col" style="width:10%;"><?php esc_html_e( 'Classes', 'studio-immens-css-classes' ); ?></th>
        <th scope="col" style="width:15%;"><?php esc_html_e( 'Imported on', 'studio-immens-css-classes' ); ?></th>
        <th scope="col" style="width:20%;"><?php esc_html_e( 'Actions', 'studio-immens-css-classes' ); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ( $packs as $slug => $pack ) : ?>
      <tr>
        <td>
          <strong><?php echo esc_html( $pack['name'] ); ?></strong>
          <?php if ( ! empty( $pack['description'] ) ) : ?>
            <br><small style="color:var(--cb-text-muted,#8c8f94);"><?php echo esc_html( $pack['description'] ); ?></small>
          <?php endif; ?>
        </td>
        <td><?php echo esc_html( $pack['author'] ?? '—' ); ?></td>
        <td><?php echo esc_html( $pack['version'] ?? '1.0' ); ?></td>
        <td><?php echo intval( $pack['class_count'] ?? 0 ); ?></td>
        <td><?php echo esc_html( $pack['imported_at'] ? date_i18n( get_option( 'date_format' ), $pack['imported_at'] ) : '—' ); ?></td>
        <td>
          <button type="button" class="button button-small sicc-export-pack" data-slug="<?php echo esc_attr( $slug ); ?>">
            <?php esc_html_e( 'Export', 'studio-immens-css-classes' ); ?>
          </button>
          <button type="button" class="button button-small sicc-delete-pack" data-slug="<?php echo esc_attr( $slug ); ?>" style="color:#d63638;">
            <?php esc_html_e( 'Delete', 'studio-immens-css-classes' ); ?>
          </button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>

  <div style="margin-top:24px;padding:16px;background:#f0f6fc;border-radius:8px;border:1px solid #e2e8f0;">
    <p style="margin:0;font-size:0.9em;color:#2271b1;">
      <strong>💡 <?php esc_html_e( 'What are Packs?', 'studio-immens-css-classes' ); ?></strong><br>
      <?php esc_html_e( 'Packs are collections of CSS classes exportable as JSON files. Import packs created by others, export your classes to share between sites, or create new packs from existing classes.', 'studio-immens-css-classes' ); ?>
    </p>
  </div>

  <div id="sicc-create-pack-modal" class="cb-modal" style="display:none;">
    <div class="cb-modal-backdrop"></div>
    <div class="cb-modal-content" style="max-width:600px;">
      <div class="cb-modal-header">
        <h2><?php esc_html_e( 'Create New Pack', 'studio-immens-css-classes' ); ?></h2>
        <button type="button" class="cb-modal-close dashicons dashicons-no-alt"></button>
      </div>
      <div class="cb-modal-body">
        <form id="sicc-create-pack-form">
          <div class="cb-form-group">
            <label for="sicc-pack-name"><?php esc_html_e( 'Pack Name', 'studio-immens-css-classes' ); ?></label>
            <input type="text" id="sicc-pack-name" class="regular-text" placeholder="<?php esc_attr_e( 'e.g. My Custom Pack', 'studio-immens-css-classes' ); ?>" required>
          </div>
          <div class="cb-form-group">
            <label for="sicc-pack-slug"><?php esc_html_e( 'Slug', 'studio-immens-css-classes' ); ?></label>
            <input type="text" id="sicc-pack-slug" class="regular-text" placeholder="<?php esc_attr_e( 'my-custom-pack', 'studio-immens-css-classes' ); ?>" required>
            <small><?php esc_html_e( 'Unique identifier (letters, numbers and hyphens only).', 'studio-immens-css-classes' ); ?></small>
          </div>
          <div class="cb-form-group">
            <label><?php esc_html_e( 'Select classes to include', 'studio-immens-css-classes' ); ?></label>
            <div id="sicc-pack-classes-list" style="max-height:250px;overflow-y:auto;border:1px solid var(--cb-border,#e2e8f0);border-radius:6px;padding:8px;">
              <div class="cb-loading" style="padding:20px;">
                <p><?php esc_html_e( 'Loading classes...', 'studio-immens-css-classes' ); ?></p>
              </div>
            </div>
          </div>
          <div class="cb-form-actions">
            <button type="submit" class="cb-btn cb-btn-primary">
              <?php esc_html_e( 'Create Pack', 'studio-immens-css-classes' ); ?>
            </button>
            <button type="button" class="cb-btn cb-btn-secondary cb-modal-close">
              <?php esc_html_e( 'Cancel', 'studio-immens-css-classes' ); ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
