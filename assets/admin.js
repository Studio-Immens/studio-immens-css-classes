jQuery(document).ready(function($) {
  var classesData = [];
  var previewMode = 'base';
  var activeCategory = '';
  var hasActive = false;
  var hasVisited = false;
  var currentEditId = '';
  var autoPreviewActive = false;
  var autoPreviewTimer = null;
  var interactiveActive = false;
  var stateCycle = ['base', 'hover', 'active', 'focus', 'visited'];
  var stateIndex = 0;
  var editors = {};
  var modalEditorsInited = false;

  // ─── TOAST SYSTEM ───
  var $toastContainer = $('.cb-toast-container');
  if (!$toastContainer.length) {
    $toastContainer = $('<div class="cb-toast-container"></div>').appendTo('body');
  }
  function showToast(message, type) {
    type = type || 'success';
    var icons = { success: '✓', error: '✗', info: 'ℹ' };
    var $t = $('<div class="cb-toast cb-toast-' + type + '"><span>' + (icons[type] || '') + '</span> ' + message + '</div>');
    $toastContainer.append($t);
    setTimeout(function() {
      $t.addClass('cb-toast-out');
      setTimeout(function() { $t.remove(); }, 400);
    }, 3000);
  }

  // ─── CODEMIRROR ───
  function initModalEditors() {
    if (modalEditorsInited) return;
    if (typeof wp === 'undefined' || !wp.codeEditor || !siCodeMirror) return;
    var ids = ['si-class-css', 'si-hover-css', 'si-active-css', 'si-focus-css', 'si-visited-css'];
    ids.forEach(function(id) {
      var el = document.getElementById(id);
      if (el && !editors[id]) {
        var editor = wp.codeEditor.initialize(el, siCodeMirror.settings);
        editors[id] = editor.codemirror;
      }
    });
    // Live preview on CodeMirror change
    if (editors['si-class-css']) {
      editors['si-class-css'].on('change', function() { updateModalPreview(); });
    }
    modalEditorsInited = true;
  }

  function resetModalEditors() {
    if (editors['si-class-css']) editors['si-class-css'].setValue('');
    if (editors['si-hover-css']) editors['si-hover-css'].setValue('');
    if (editors['si-active-css']) editors['si-active-css'].setValue('');
    if (editors['si-focus-css']) editors['si-focus-css'].setValue('');
    if (editors['si-visited-css']) editors['si-visited-css'].setValue('');
  }

  function getModalCss(id) {
    if (editors[id]) return editors[id].getValue();
    return $('#' + id).val();
  }

  function setModalCss(id, val) {
    if (editors[id]) editors[id].setValue(val);
    $('#' + id).val(val);
  }

  // ─── MODAL ───
  function openModal(title, data) {
    $('#si-modal-title').text(title);
    if (data) {
      currentEditId = data.id;
      $('#si-class-id').val(data.id);
      $('#si-class-name').val(data.name);
      $('#si-class-desc').val(data.description || '');
      setModalCss('si-class-css', data.css || '');
      setModalCss('si-hover-css', data.hover || '');
      setModalCss('si-active-css', data.active || '');
      setModalCss('si-focus-css', data.focus || '');
      setModalCss('si-visited-css', data.visited || '');
    } else {
      currentEditId = '';
      $('#si-class-id').val('');
      $('#si-class-name').val('');
      $('#si-class-desc').val('');
      $('#si-active-css').val('');
      $('#si-visited-css').val('');
      resetModalEditors();
    }
    $('#si-class-modal').show();
    setTimeout(function() {
      initModalEditors();
      updateModalPreview();
    }, 50);
  }

  function closeModal() {
    $('#si-class-modal').hide();
  }

  function updateModalPreview() {
    var css = getModalCss('si-class-css');
    var $el = $('#si-preview-content');
    var previewHtml = getPreviewHtml('modal', css);
    $el.html(previewHtml);
  }

  // ─── LOAD & RENDER CLASSES ───
  function loadClasses() {
    var search = $('#si-class-search').val().toString().toLowerCase();
    var category = activeCategory;
    var packFilter = $('#sicc-pack-filter').val();

    $('#si-classes-container').html(
      '<div class="cb-loading"><div class="cb-spinner"></div><p>' + (siCssAdmin.loading || 'Loading classes...') + '</p></div>'
    );

    $.post(ajaxurl, {
      action: 'sicc_get_css_classes',
      security: siCssAdmin.nonce
    }, function(response) {
      if (!response.success) return;
      classesData = response.data || [];

      // Apply filters
      var filtered = classesData;
      if (search) {
        filtered = filtered.filter(function(cls) {
          return cls.name.toLowerCase().indexOf(search) !== -1;
        });
      }
      if (category === 'hover') {
        filtered = filtered.filter(function(cls) { return cls.hover && cls.hover.trim(); });
      } else if (category === 'focus') {
        filtered = filtered.filter(function(cls) { return cls.focus && cls.focus.trim(); });
      } else if (category === 'active') {
        filtered = filtered.filter(function(cls) { return cls.active && cls.active.trim(); });
      } else if (category === 'visited') {
        filtered = filtered.filter(function(cls) { return cls.visited && cls.visited.trim(); });
      } else if (category === 'base') {
        filtered = filtered.filter(function(cls) { return !(cls.hover && cls.hover.trim()) && !(cls.active && cls.active.trim()) && !(cls.focus && cls.focus.trim()) && !(cls.visited && cls.visited.trim()); });
      }
      if (packFilter) {
        if (packFilter === '__none') {
          filtered = filtered.filter(function(cls) { return !cls.pack_slug; });
        } else {
          filtered = filtered.filter(function(cls) { return cls.pack_slug === packFilter; });
        }
      }

      renderCards(filtered);
      updateStats();
    });
  }

  function getPreviewHtml(context, css, hover, focus, mode, active, visited) {
    mode = mode || 'base';
    var previewCss = css || '';
    if (mode === 'hover' && hover) previewCss = hover;
    else if (mode === 'active' && active) previewCss = active;
    else if (mode === 'focus' && focus) previewCss = focus;
    else if (mode === 'visited' && visited) previewCss = visited;

    var combined = (css + ' ' + hover + ' ' + focus).toLowerCase();
    var styleAttr = previewCss ? 'style="' + escapeHtml(previewCss) + '"' : '';

    // Flex
    if (combined.indexOf('display: flex') !== -1 || combined.indexOf('display: inline-flex') !== -1 || combined.indexOf('flex-direction') !== -1) {
      var flexStyle = previewCss || 'display: flex; gap: 8px;';
      var align = '';
      if (combined.indexOf('flex-center') !== -1) align = ' align-items:center; justify-content:center;';
      else if (combined.indexOf('flex-between') !== -1) align = ' align-items:center; justify-content:space-between;';
      else if (combined.indexOf('flex-col') !== -1) align = ' flex-direction:column;';
      else if (combined.indexOf('flex-row') !== -1) align = ' flex-direction:row;';
      return '<div style="display:flex;gap:6px;' + align + ';width:100%;height:100%;align-items:center;justify-content:center;">' +
        '<div style="width:32px;height:32px;background:#2271b1;border-radius:4px;transition:all 0.2s;"></div>' +
        '<div style="width:44px;height:32px;background:#7c3aed;border-radius:4px;transition:all 0.2s;"></div>' +
        '<div style="width:26px;height:32px;background:#10b981;border-radius:4px;transition:all 0.2s;"></div>' +
        '</div>';
    }

    // Grid
    if (combined.indexOf('display: grid') !== -1 || combined.indexOf('grid-template') !== -1) {
      var cols = '2';
      if (combined.indexOf('grid-cols-3') !== -1) cols = '3';
      return '<div style="display:grid;grid-template-columns:repeat(' + cols + ',1fr);gap:4px;width:100%;height:100%;padding:8px;">' +
        '<div style="height:24px;background:#2271b1;border-radius:3px;"></div>' +
        '<div style="height:24px;background:#7c3aed;border-radius:3px;"></div>' +
        (cols === '3' ? '<div style="height:24px;background:#10b981;border-radius:3px;"></div>' : '') +
        '</div>';
    }

    // Text align
    if (combined.indexOf('text-align') !== -1) {
      return '<div style="width:100%;padding:8px;font-size:13px;color:#fff;' + escapeHtml(previewCss) + '">Testo di esempio</div>';
    }

    // Font weight / size
    if (combined.indexOf('font-weight') !== -1 || combined.indexOf('font-size') !== -1) {
      return '<div style="width:100%;padding:12px;color:#fff;' + escapeHtml(previewCss) + '">Aa</div>';
    }

    // Line height / whitespace / truncate
    if (combined.indexOf('line-height') !== -1 || combined.indexOf('white-space') !== -1 || combined.indexOf('text-overflow') !== -1) {
      return '<div style="width:100%;padding:8px;color:#fff;font-size:12px;' + escapeHtml(previewCss) + '">Testo di esempio con contenuto pi\u00f9 lungo per mostrare l\'effetto</div>';
    }

    // Text transform
    if (combined.indexOf('text-transform') !== -1) {
      return '<div style="width:100%;padding:8px;color:#fff;font-size:13px;' + escapeHtml(previewCss) + '">testo esempio</div>';
    }

    // Text decoration
    if (combined.indexOf('text-decoration') !== -1) {
      return '<div style="width:100%;padding:8px;color:#fff;font-size:13px;' + escapeHtml(previewCss) + '">Testo di esempio</div>';
    }

    // Margin / padding
    if (combined.indexOf('margin') !== -1 || combined.indexOf('padding') !== -1) {
      var isMargin = combined.indexOf('margin') !== -1;
      var innerPad = isMargin ? '4px' : '0px';
      return '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;' + escapeHtml(previewCss) + '">' +
        '<div style="background:rgba(34,113,177,0.3);border:1px dashed rgba(34,113,177,0.6);border-radius:4px;padding:' + innerPad + ';width:60px;text-align:center;">' +
        '<div style="background:#2271b1;height:20px;border-radius:2px;"></div>' +
        '</div></div>';
    }

    // Width / height
    if (combined.indexOf('width') !== -1 || combined.indexOf('height') !== -1 || combined.indexOf('max-width') !== -1) {
      var isH = combined.indexOf('height') !== -1 || combined.indexOf('h-') !== -1 || combined.indexOf('h-screen') !== -1;
      if (isH) {
        return '<div style="width:100%;height:100%;display:flex;align-items:flex-end;' + escapeHtml(previewCss) + '">' +
          '<div style="width:100%;background:linear-gradient(180deg,#2271b1,#7c3aed);border-radius:4px 4px 0 0;"></div></div>';
      }
      return '<div style="width:100%;height:100%;display:flex;align-items:center;' + escapeHtml(previewCss) + '">' +
        '<div style="height:16px;background:linear-gradient(90deg,#2271b1,#7c3aed);border-radius:4px;"></div></div>';
    }

    // Box shadow
    if (combined.indexOf('box-shadow') !== -1) {
      return '<div style="width:50px;height:50px;margin:auto;background:linear-gradient(135deg,#2271b1,#7c3aed);border-radius:8px;' + escapeHtml(previewCss) + '"></div>';
    }

    // Border radius
    if (combined.indexOf('border-radius') !== -1) {
      return '<div style="width:60px;height:60px;margin:auto;background:#2271b1;' + escapeHtml(previewCss) + '"></div>';
    }

    // Border
    if (combined.indexOf('border:') !== -1 || combined.indexOf('border-') !== -1) {
      return '<div style="width:60px;height:40px;margin:auto;' + escapeHtml(previewCss) + '"></div>';
    }

    // Opacity
    if (combined.indexOf('opacity') !== -1) {
      var baseOpacity = previewCss || 'opacity: 1;';
      return '<div style="display:flex;gap:8px;align-items:center;justify-content:center;width:100%;height:100%;">' +
        '<div style="width:40px;height:40px;background:#2271b1;border-radius:6px;opacity:1;"></div>' +
        '<div style="width:40px;height:40px;background:#2271b1;border-radius:6px;' + escapeHtml(baseOpacity) + '"></div></div>';
    }

    // Cursor
    if (combined.indexOf('cursor') !== -1) {
      return '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:24px;' + escapeHtml(previewCss) + '">' +
        (previewCss.indexOf('pointer') !== -1 ? '\u2610' : '\u2611') + '</div>';
    }

    // Transition / transform
    if (combined.indexOf('transform') !== -1 || combined.indexOf('transition') !== -1 || combined.indexOf('scale') !== -1 || combined.indexOf('rotate') !== -1) {
      return '<div style="width:48px;height:48px;margin:auto;background:linear-gradient(135deg,#2271b1,#7c3aed);border-radius:8px;' + escapeHtml(previewCss) + '"></div>';
    }

    // Position (relative / absolute)
    if (combined.indexOf('position') !== -1) {
      return '<div style="width:100%;height:100%;background:rgba(34,113,177,0.1);border-radius:6px;' + escapeHtml(previewCss) + '">' +
        '<div style="width:24px;height:24px;background:#2271b1;border-radius:4px;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);"></div></div>';
    }

    // Hover-only or focus-only (empty base CSS)
    if (previewCss === '' && (hover || focus)) {
      var icon = mode === 'focus' ? '\u2316' : '\u25CB';
      var hint = mode === 'focus' ? 'Focus' : 'Hover';
      return '<div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;font-size:12px;color:rgba(255,255,255,0.6);">' +
        '<span style="font-size:24px;margin-bottom:4px;">' + icon + '</span>' + hint + '</div>';
    }

    // Default
    return '<div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;' + escapeHtml(previewCss) + '">' +
      '<div style="font-size:16px;font-weight:700;">Aa</div>' +
      '<div style="font-size:10px;opacity:0.7;">Preview</div></div>';
  }

  function renderCards(classes) {
    var $container = $('#si-classes-container');
    $container.empty();

    if (classes.length === 0) {
    $container.html(
          '<div class="cb-empty"><p>' + (siCssAdmin.noClasses || 'No classes found.') + '</p></div>'
        );
      return;
    }

    var html = '';
    classes.forEach(function(cls) {
      var hasHover = cls.hover && cls.hover.trim();
      var hasActive = cls.active && cls.active.trim();
      var hasFocus = cls.focus && cls.focus.trim();
      var hasVisited = cls.visited && cls.visited.trim();

      // Preview HTML
      var previewHtml = getPreviewHtml('card', cls.css, cls.hover, cls.focus, previewMode, cls.active, cls.visited);

      // Build badges
      var badges = '';
      badges += '<span class="cb-anim-badge cb-badge-base">' + (siCssAdmin.badgeBase || 'base') + '</span>';
      if (hasHover) badges += '<span class="cb-anim-badge cb-badge-hover">' + (siCssAdmin.badgeHo || 'ho') + '</span>';
      if (hasActive) badges += '<span class="cb-anim-badge cb-badge-active">' + (siCssAdmin.badgeAc || 'ac') + '</span>';
      if (hasFocus) badges += '<span class="cb-anim-badge cb-badge-focus">' + (siCssAdmin.badgeFo || 'fo') + '</span>';
      if (hasVisited) badges += '<span class="cb-anim-badge cb-badge-visited">' + (siCssAdmin.badgeVi || 'vi') + '</span>';

      // Description from field, fallback to CSS
      var desc = cls.description || (cls.css ? cls.css.substring(0, 60) : '');
      if (!cls.description && cls.css && cls.css.length > 60) desc += '...';

      html += '<div class="cb-anim-card" data-id="' + cls.id + '">';
      html += '  <div class="cb-anim-preview-wrap">';
      html += '    <div class="cb-preview-content">' + previewHtml + '</div>';
      html += '  </div>';
      html += '  <div class="cb-anim-card-header">';
      html += '    <span class="cb-anim-name">.' + escapeHtml(cls.name) + '</span>';
      html += '    <div class="cb-anim-header-right">' + badges + '</div>';
      html += '  </div>';
      html += '  <div class="cb-anim-card-body">';
      html += '    <p class="cb-anim-label">' + escapeHtml(desc || cls.name) + '</p>';
      html += '    <p class="cb-anim-desc">' + escapeHtml(cls.css || '') + '</p>';
      html += '  </div>';
      html += '  <div class="cb-anim-card-actions">';
      html += '    <button class="button si-edit-class" data-id="' + cls.id + '">' + (siCssAdmin.edit || 'Edit') + '</button>';
      html += '    <button class="button si-duplicate-class" data-id="' + cls.id + '">' + (siCssAdmin.duplicate || 'Duplicate') + '</button>';
      html += '    <button class="button si-delete-class" data-id="' + cls.id + '">' + (siCssAdmin.delete || 'Delete') + '</button>';
      html += '  </div>';
      html += '</div>';
    });

    $container.html(html);

    // Staggered appearance
    var cards = $container.find('.cb-anim-card');
    cards.each(function(i) {
      setTimeout(function() { cards[i].classList.add('cb-visible'); }, i * 80);
    });
  }

  function updateStats() {
    var total = classesData.length;
    var hoverCount = classesData.filter(function(c) { return c.hover && c.hover.trim(); }).length;
    var activeCount = classesData.filter(function(c) { return c.active && c.active.trim(); }).length;
    var focusCount = classesData.filter(function(c) { return c.focus && c.focus.trim(); }).length;
    var visitedCount = classesData.filter(function(c) { return c.visited && c.visited.trim(); }).length;
    var baseCount = classesData.filter(function(c) { return !(c.hover && c.hover.trim()) && !(c.active && c.active.trim()) && !(c.focus && c.focus.trim()) && !(c.visited && c.visited.trim()); }).length;
    $('#si-stat-classes').text(total);
    $('#si-stat-hover').text(hoverCount);
    $('#si-stat-active').text(activeCount);
    $('#si-stat-focus').text(focusCount);
    $('#si-stat-visited').text(visitedCount);
    $('#si-stat-base').text(baseCount);
  }

  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function applyPreviewMode() {
    $('.cb-preview-content').each(function() {
      var $el = $(this);
      var $card = $el.closest('.cb-anim-card');
      var id = $card.data('id');
      var clsData = null;
      for (var i = 0; i < classesData.length; i++) {
        if (classesData[i].id === id) { clsData = classesData[i]; break; }
      }
      if (!clsData) return;
      var html = getPreviewHtml('card', clsData.css, clsData.hover, clsData.focus, previewMode, clsData.active, clsData.visited);
      $el.html(html);
    });
  }

  // ─── AUTO PREVIEW ───
  function startAutoPreview() {
    if (autoPreviewActive) return;
    disableInteractivePreview();
    autoPreviewActive = true;
    stateIndex = 0;
    $('#cb-auto-preview').text('\u25A0 Stop');
    autoPreviewTimer = setInterval(function() {
      stateIndex = (stateIndex + 1) % stateCycle.length;
      previewMode = stateCycle[stateIndex];
      $('.cb-playmode-option').removeClass('cb-playmode-active');
      $('.cb-playmode-option[data-mode="' + previewMode + '"]').addClass('cb-playmode-active');
      applyPreviewMode();
    }, 1500);
  }

  function stopAutoPreview() {
    if (!autoPreviewActive) return;
    autoPreviewActive = false;
    clearInterval(autoPreviewTimer);
    autoPreviewTimer = null;
    $('#cb-auto-preview').text('\u25B6 Auto');
  }

  $('#cb-auto-preview').on('click', function() {
    if (autoPreviewActive) {
      stopAutoPreview();
      previewMode = 'base';
      $('.cb-playmode-option').removeClass('cb-playmode-active');
      $('.cb-playmode-option[data-mode="base"]').addClass('cb-playmode-active');
      applyPreviewMode();
    } else {
      startAutoPreview();
    }
  });

  // ─── INTERACTIVE PREVIEW ───
  function enableInteractivePreview() {
    if (interactiveActive) return;
    stopAutoPreview();
    interactiveActive = true;
    $('#cb-interactive-preview').text('\u25A0 Stop Interactive');

    $(document).on('mouseenter.interactive', '.cb-anim-card .cb-preview-content', function() {
      var $card = $(this).closest('.cb-anim-card');
      if ($card.attr('data-preview-override')) return;
      showCardState($card, 'hover');
    });

    $(document).on('mousedown.interactive', '.cb-anim-card .cb-preview-content', function() {
      var $card = $(this).closest('.cb-anim-card');
      if ($card.attr('data-preview-override')) return;
      showCardState($card, 'active');
    });

    $(document).on('mouseup.interactive', '.cb-anim-card .cb-preview-content', function() {
      var $card = $(this).closest('.cb-anim-card');
      if ($card.attr('data-preview-override')) return;
      showCardState($card, 'hover');
    });

    $(document).on('mouseleave.interactive', '.cb-anim-card .cb-preview-content', function() {
      var $card = $(this).closest('.cb-anim-card');
      if ($card.attr('data-preview-override')) return;
      resetCardPreview($card);
    });

    $(document).on('focusin.interactive', '.cb-anim-card .cb-preview-content', function() {
      var $card = $(this).closest('.cb-anim-card');
      if ($card.attr('data-preview-override')) return;
      showCardState($card, 'focus');
    });

    $(document).on('focusout.interactive', '.cb-anim-card .cb-preview-content', function() {
      var $card = $(this).closest('.cb-anim-card');
      if ($card.attr('data-preview-override')) return;
      resetCardPreview($card);
    });
  }

  function disableInteractivePreview() {
    if (!interactiveActive) return;
    interactiveActive = false;
    $('#cb-interactive-preview').text('\uD83D\uDDB2 Interactive');
    $(document).off('.interactive');
    $('.cb-anim-card').each(function() {
      var $card = $(this);
      if (!$card.attr('data-preview-override')) resetCardPreview($card);
    });
  }

  function showCardState($card, mode) {
    var id = $card.data('id');
    var clsData = null;
    for (var i = 0; i < classesData.length; i++) {
      if (classesData[i].id === id) { clsData = classesData[i]; break; }
    }
    if (!clsData) return;
    var $el = $card.find('.cb-preview-content');
    var html = getPreviewHtml('card', clsData.css, clsData.hover, clsData.focus, mode, clsData.active, clsData.visited);
    $el.html(html);
  }

  function resetCardPreview($card) {
    var id = $card.data('id');
    var clsData = null;
    for (var i = 0; i < classesData.length; i++) {
      if (classesData[i].id === id) { clsData = classesData[i]; break; }
    }
    if (!clsData) return;
    var $el = $card.find('.cb-preview-content');
    var html = getPreviewHtml('card', clsData.css, clsData.hover, clsData.focus, 'base');
    $el.html(html);
  }

  $('#cb-interactive-preview').on('click', function() {
    if (interactiveActive) {
      disableInteractivePreview();
    } else {
      enableInteractivePreview();
    }
  });

  // ─── STATS BAR FILTER ───
  $('.cb-pro-stat').on('click', function() {
    var cat = $(this).data('category');
    if (cat === activeCategory) {
      activeCategory = '';
      $('.cb-pro-stat').removeClass('cb-pro-stat-active');
      $('.cb-pro-stat[data-category=""]').addClass('cb-pro-stat-active');
    } else {
      activeCategory = cat;
      $('.cb-pro-stat').removeClass('cb-pro-stat-active');
      $(this).addClass('cb-pro-stat-active');
    }
    loadClasses();
  });

  // ─── PER-CARD UPDATE ───
  function updateSingleCardPreview($card) {
    var id = $card.data('id');
    var clsData = null;
    for (var i = 0; i < classesData.length; i++) {
      if (classesData[i].id === id) { clsData = classesData[i]; break; }
    }
    if (!clsData) return;
    var mode = $card.attr('data-preview-override') || previewMode;
    var $el = $card.find('.cb-preview-content');
    var html = getPreviewHtml('card', clsData.css, clsData.hover, clsData.focus, mode, clsData.active, clsData.visited);
    $el.html(html);
  }

  // ─── BADGE CLICK (per-card override) ───
  $(document).on('click', '.cb-anim-badge', function(e) {
    e.stopPropagation();
    var $badge = $(this);
    var $card = $badge.closest('.cb-anim-card');
    var mode = 'base';
    if ($badge.hasClass('cb-badge-hover')) mode = 'hover';
    else if ($badge.hasClass('cb-badge-active')) mode = 'active';
    else if ($badge.hasClass('cb-badge-focus')) mode = 'focus';
    else if ($badge.hasClass('cb-badge-visited')) mode = 'visited';

    var current = $card.attr('data-preview-override') || '';
    if (mode === 'base' || current === mode) {
      $card.removeAttr('data-preview-override');
    } else {
      $card.attr('data-preview-override', mode);
    }
    updateSingleCardPreview($card);
  });

  // ─── PREVIEW MODE (radio buttons) ───
  $('.cb-playmode-option').on('click', function() {
    var mode = $(this).data('mode');
    if (mode === previewMode) return;
    stopAutoPreview();
    disableInteractivePreview();
    previewMode = mode;
    $('.cb-playmode-option').removeClass('cb-playmode-active');
    $(this).addClass('cb-playmode-active');
    $(this).find('input').prop('checked', true);
    $('.cb-anim-card').removeAttr('data-preview-override');
    if (classesData.length > 0) {
      applyPreviewMode();
    } else {
      loadClasses();
    }
  });

  // ─── TOOLBAR ───
  $('#si-class-search').on('input', function() { loadClasses(); });

  // ─── PACK FILTER ───
  $('#sicc-pack-filter').on('change', function() { loadClasses(); });

  // ─── ADD CLASS ───
  $('#si-add-class').on('click', function() {
    openModal(siCssAdmin.newClass || 'New CSS Class', null);
  });

  // ─── MODAL CLOSE ───
  $('.cb-modal-close, .cb-modal-backdrop').on('click', function() {
    closeModal();
  });

  // ─── TEST CLASS IN MODAL ───
  $('#si-test-class').on('click', function() {
    updateModalPreview();
  });
  $('#si-class-css, #si-hover-css, #si-active-css, #si-focus-css, #si-visited-css').on('change keyup', function() {
    updateModalPreview();
  });

  // ─── EDIT ───
  $(document).on('click', '.si-edit-class', function() {
    var id = $(this).data('id');
    var cls = null;
    for (var i = 0; i < classesData.length; i++) {
      if (classesData[i].id === id) { cls = classesData[i]; break; }
    }
    if (!cls) return;
    openModal(siCssAdmin.editClass || 'Edit CSS Class', cls);
  });

  // ─── DUPLICATE ───
  $(document).on('click', '.si-duplicate-class', function() {
    var id = $(this).data('id');
    var cls = null;
    for (var i = 0; i < classesData.length; i++) {
      if (classesData[i].id === id) { cls = classesData[i]; break; }
    }
    if (!cls) return;
    $.post(ajaxurl, {
      action: 'sicc_save_css_class',
      name: cls.name + '-copy',
      css: cls.css || '',
      hover: cls.hover || '',
      active: cls.active || '',
      focus: cls.focus || '',
      visited: cls.visited || '',
      security: siCssAdmin.nonce
    }, function(response) {
      if (response.success) {
        showToast((siCssAdmin.duplicatedAs || 'Class duplicated as') + ' .' + cls.name + '-copy', 'success');
        loadClasses();
      } else {
        showToast(siCssAdmin.saveError || 'Errore', 'error');
      }
    });
  });

  // ─── DELETE ───
  $(document).on('click', '.si-delete-class', function() {
    if (!confirm(siCssAdmin.deleteConfirm || 'Delete this class?')) return;
    var id = $(this).data('id');
    $.post(ajaxurl, {
      action: 'sicc_delete_css_class',
      id: id,
      security: siCssAdmin.nonce
    }, function() {
      loadClasses();
      showToast(siCssAdmin.classDeleted || 'Class deleted', 'success');
    });
  });

  // ─── SAVE ───
  $('#si-class-form').on('submit', function(e) {
    e.preventDefault();
    var id = $('#si-class-id').val();
    var name = $('#si-class-name').val();
    var desc = $('#si-class-desc').val();
    var css = getModalCss('si-class-css');
    var hover = getModalCss('si-hover-css');
    var active = getModalCss('si-active-css');
    var focus = getModalCss('si-focus-css');
    var visited = getModalCss('si-visited-css');

    if (!name || !css) {
      showToast(siCssAdmin.emptyFields || 'Compila tutti i campi obbligatori.', 'error');
      return;
    }

    var isEdit = !!currentEditId;
    var action = isEdit ? 'sicc_edit_css_class' : 'sicc_save_css_class';
    var data = {
      action: action,
      name: name,
      css: css,
      hover: hover,
      active: active,
      focus: focus,
      visited: visited,
      description: desc,
      security: siCssAdmin.nonce
    };
    if (isEdit) data.id = currentEditId;

    $.post(ajaxurl, data, function(response) {
      if (response.success) {
        closeModal();
        loadClasses();
        showToast(isEdit ? (siCssAdmin.classUpdated || 'Class updated!') : (siCssAdmin.classCreated || 'Class created!'), 'success');

        if (siCssAdmin.saveCount === 3) {
          setTimeout(function() {
            showToast('\uD83D\uDCA1 ' + (siCssAdmin.proNudge || 'PRO animations bring your classes to life. Discover them!'), 'info');
          }, 1500);
        }
      } else {
        showToast(siCssAdmin.saveError || 'Error saving', 'error');
      }
    }).fail(function() {
      showToast(siCssAdmin.connectionError || 'Connection error', 'error');
    });
  });

  // ─── KEYBOARD SHORTCUT ───
  $(document).on('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
  });

  // ─── INIT ───
  loadClasses();
});