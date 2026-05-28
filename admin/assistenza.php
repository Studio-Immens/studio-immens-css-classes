<div class="wrap si-css-admin">
  <div class="si-admin-header">
    <div class="si-header-left">
      <img src="<?php echo esc_url(SI_CSS_CLASS_URL . 'assets/classyblocks-icon.png'); ?>" class="si-header-icon" alt="">
      <div>
        <h1><?php esc_html_e('Discover Studio Immens plugins', 'studio-immens-css-classes'); ?></h1>
        <p class="si-subtitle"><?php esc_html_e('Modular tools to empower your WordPress.', 'studio-immens-css-classes'); ?></p>
      </div>
    </div>
  </div>

  <div class="si-plugins-grid">
    <div class="si-plugin-card" style="border:2px solid #2271b1;">
      <div class="si-plugin-card-header">
        <h3>ClassyBlocks</h3>
        <span class="si-plugin-version">v<?php echo esc_html(SI_CSS_CLASS_VERSION); ?></span>
      </div>
      <div class="si-plugin-card-body">
        <p><?php esc_html_e('Add custom CSS classes to Gutenberg blocks with live preview. Supports Bootstrap, Tailwind, Bulma and other CSS frameworks.', 'studio-immens-css-classes'); ?></p>
        <a href="https://studioimmens.com/classyblocks-pro/" target="_blank" class="button"><?php esc_html_e('Official site', 'studio-immens-css-classes'); ?> →</a>
      </div>
    </div>

    <div class="si-plugin-card">
      <div class="si-plugin-card-header">
        <h3>Primary Source</h3>
        <span class="si-plugin-version">v<?php echo esc_html(defined('PRIMARY_SOURCE_VERSION') ? PRIMARY_SOURCE_VERSION : '2.3.3'); ?></span>
      </div>
      <div class="si-plugin-card-body">
        <p><?php esc_html_e('AI-ready universal tool for professionals. Optimize your site for Large Language Models (LLM) and become the authoritative source for ChatGPT, Claude, Gemini and Perplexity.', 'studio-immens-css-classes'); ?></p>
        <a href="https://studioimmens.com/primary-source" target="_blank" class="button"><?php esc_html_e('Official site', 'studio-immens-css-classes'); ?> →</a>
      </div>
    </div>

    <div class="si-plugin-card">
      <div class="si-plugin-card-header">
        <h3>Immens Keyword Explorer</h3>
        <span class="si-plugin-version">v<?php echo esc_html(defined('IMMENS_KEYEX_VERSION') ? IMMENS_KEYEX_VERSION : '1.3.1'); ?></span>
      </div>
      <div class="si-plugin-card-body">
        <p><?php esc_html_e('Advanced AIO SEO tool for keyword research on search engines and AI, with editorial planner and real-time competitive analysis.', 'studio-immens-css-classes'); ?></p>
        <a href="https://studioimmens.com/immens-keyword-explorer" target="_blank" class="button"><?php esc_html_e('Official site', 'studio-immens-css-classes'); ?> →</a>
      </div>
    </div>
  </div>

  <div class="si-footer-note">
    <p><?php esc_html_e('All Studio Immens plugins are developed with professional standards and regularly updated.', 'studio-immens-css-classes'); ?></p>
    <p><?php esc_html_e('Made with passion by Studio Immens.', 'studio-immens-css-classes'); ?></p>
  </div>
</div>

<style>
.si-plugins-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  margin: 20px 0;
}
.si-plugin-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  overflow: hidden;
  transition: all 0.2s;
}
.si-plugin-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.si-plugin-card-header {
  padding: 16px 20px 12px;
  border-bottom: 1px solid #f0f0f1;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.si-plugin-card-header h3 {
  margin: 0;
  font-size: 1.05em;
  font-weight: 600;
}
.si-plugin-version {
  font-size: 0.8em;
  color: #8c8f94;
  background: #f0f0f1;
  padding: 2px 8px;
  border-radius: 4px;
}
.si-plugin-card-body {
  padding: 16px 20px 20px;
}
.si-plugin-card-body p {
  margin: 0 0 12px 0;
  font-size: 0.9em;
  color: #3c434a;
  line-height: 1.5;
}
.si-footer-note {
  text-align: center;
  margin-top: 40px;
  padding: 20px;
  color: #8c8f94;
  font-size: 0.85em;
  border-top: 1px solid #e2e8f0;
}
.si-footer-note p {
  margin: 4px 0;
}
</style>
