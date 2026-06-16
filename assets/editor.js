(function () {
  if (
    typeof wp === 'undefined' ||
    typeof wp.blocks === 'undefined' ||
    typeof wp.element === 'undefined' ||
    typeof wp.blockEditor === 'undefined' ||
    typeof wp.components === 'undefined' ||
    typeof wp.hooks === 'undefined'
  ) {
    console.warn('Gutenberg APIs non disponibili');
    return;
  }

  if (typeof siCssData === 'undefined') {
    console.warn('siCssData non definito');
    return;
  }

  var __ = wp.i18n.__;
  var createElement = wp.element.createElement;
  var Fragment = wp.element.Fragment;
  var useState = wp.element.useState;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var useBlockEditContext = wp.blockEditor.useBlockEditContext;
  var PanelBody = wp.components.PanelBody;
  var ToggleControl = wp.components.ToggleControl;
  var TextControl = wp.components.TextControl;
  var CheckboxControl = wp.components.CheckboxControl;
  var Card = wp.components.Card;
  var CardBody = wp.components.CardBody;
  var CardHeader = wp.components.CardHeader;
  var Button = wp.components.Button;
  var addFilter = wp.hooks.addFilter;

  (function injectDynamicCSS() {
    if (typeof document === 'undefined' || typeof siCssData === 'undefined') return;
    var head = document.head || document.getElementsByTagName('head')[0];
    var styleEl = head.querySelector('#si-css-dynamic');
    if (!styleEl) {
      styleEl = document.createElement('style');
      styleEl.id = 'si-css-dynamic';
      head.appendChild(styleEl);
    }
    var css = '';
    siCssData.classes.forEach(function(c) {
      if (c.css) css += '.' + c.name + '{' + c.css + '} ';
      if (c.visited) css += '.' + c.name + ':visited{' + c.visited + '} ';
      if (c.hover) css += '.' + c.name + ':hover{' + c.hover + '} ';
      if (c.active) css += '.' + c.name + ':active{' + c.active + '} ';
      if (c.focus) css += '.' + c.name + ':focus{' + c.focus + '} ';
    });
    styleEl.textContent = css;
  })();

  function AddCSSClassControl(settings) {
    if (!settings.supports) return settings;

    var originalEdit = settings.edit;

    settings.edit = function(props) {
      var blockEditContext = useBlockEditContext();
      var _useState = useState('classes');
      var activeTab = _useState[0];
      var setActiveTab = _useState[1];
      var _useState2 = useState('');
      var searchTerm = _useState2[0];
      var setSearchTerm = _useState2[1];
      var _useState3 = useState(false);
      var showPreview = _useState3[0];
      var setShowPreview = _useState3[1];

      var currentClasses = props.attributes.className
        ? props.attributes.className.split(' ').filter(Boolean)
        : [];

      var filtered = siCssData.classes.filter(function(cls) {
        return cls.name.toLowerCase().includes(searchTerm.toLowerCase());
      });

      var toggleClass = function(className, checked) {
        var setClasses = new Set(currentClasses);
        if (checked) setClasses.add(className);
        else setClasses.delete(className);
        props.setAttributes({ className: Array.from(setClasses).join(' ') });
      };

      var renderTab = function(tabKey, tabLabel, isActive) {
        return createElement(
          'button',
          {
            className: 'cb-panel-tab' + (isActive ? ' active' : ''),
            onClick: function() { setActiveTab(tabKey); },
            key: tabKey,
            style: {
              padding: '8px 16px',
              cursor: 'pointer',
              border: 'none',
              borderBottom: '2px solid ' + (isActive ? '#2271b1' : 'transparent'),
              background: 'none',
              fontSize: '13px',
              fontWeight: isActive ? '600' : '400',
              color: isActive ? '#2271b1' : '#646970',
              flex: '1',
              textAlign: 'center'
            }
          },
          tabLabel
        );
      };

      var proActive = typeof cbProEditor !== 'undefined' && cbProEditor.labels;
      var panelTitle = proActive ? 'ClassyBlocks' : (siCssData.labels.title || 'CSS Classes');

      return createElement(
        Fragment,
        {},
        createElement(originalEdit, props),
        props.isSelected &&
          createElement(
            InspectorControls,
            {},
            createElement(
              PanelBody,
              {
                title: panelTitle,
                initialOpen: false,
                className: 'studioimmens-css-control'
              },
              createElement(
                'div',
                { style: { display: 'flex', borderBottom: '1px solid #e0e0e0', marginBottom: '12px' } },
                renderTab('classes', siCssData.labels.title || wp.i18n.__('Classes', 'studio-immens-css-classes'), activeTab === 'classes'),
                !proActive && renderTab('pro-animations', wp.i18n.__('Animations', 'studio-immens-css-classes'), activeTab === 'pro-animations')
              ),

              activeTab === 'classes' && createElement(
                'div',
                { className: 'cb-panel-content' },
                createElement(TextControl, {
                  label: siCssData.labels.search || wp.i18n.__('Search Classes', 'studio-immens-css-classes'),
                  value: searchTerm,
                  onChange: setSearchTerm,
                  placeholder: siCssData.labels.ttos || 'Type to search...'
                }),
                  createElement(
                    'div',
                    { style: { maxHeight: '300px', overflowY: 'auto' } },
                    filtered.length > 0
                      ? filtered.map(function(cls) {
                          var isActive = currentClasses.indexOf(cls.name) !== -1;
                          return createElement(
                            Card,
                            {
                              key: cls.id || cls.name,
                              className: 'si-class-card' + (isActive ? ' is-active' : ''),
                              style: { marginBottom: '4px' }
                            },
                            createElement(
                              CardHeader,
                              {},
                              createElement(CheckboxControl, {
                                label: '.' + cls.name,
                                checked: isActive,
                                onChange: function(checked) { toggleClass(cls.name, checked); }
                              })
                            ),
                            createElement(
                              CardBody,
                              {},
                              cls.description ? createElement('small', { style: { display: 'block', fontSize: '11px', color: '#646970', marginBottom: '4px', lineHeight: '1.3' } }, cls.description) : null,
                              createElement('div', { style: { fontSize: '11px', margin: 0, background: '#f6f7f7', padding: '6px', borderRadius: '3px', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'pre-wrap', wordBreak: 'break-all', color: '#8c8f94' } }, cls.css)
                            )
                          );
                        })
                      : createElement('p', { style: { color: '#8c8f94', textAlign: 'center', padding: '20px 0' } }, siCssData.labels.nofound || 'No classes found')
                  ),
                filtered.length > 0 && siCssData.classes.length > 0 && createElement(
                  'div',
                  { style: { fontSize: '11px', color: '#8c8f94', textAlign: 'center', paddingTop: '8px', borderTop: '1px solid #f0f0f0', marginTop: '8px' } },
                  siCssData.classes.length + ' ' + wp.i18n.__('total classes', 'studio-immens-css-classes')
                )
              ),

              !proActive && activeTab === 'pro-animations' && createElement(
                'div',
                { className: 'cb-panel-content' },
                createElement(
                  'div',
                  { style: { padding: '12px', fontSize: '0.85em', color: '#646970', lineHeight: '1.5', textAlign: 'center' } },
                  wp.i18n.__('Scroll-driven animations (Fade, Slide, Zoom, Float, Bounce, Flip, Blur, Scale) are available in', 'studio-immens-css-classes'),
                  ' ',
                  createElement(
                    'a',
                    { href: 'https://studioimmens.com/classyblocks-pro/', target: '_blank', style: { fontWeight: 600, color: '#2271b1', textDecoration: 'none' } },
                    'ClassyBlocks Pro'
                  ),
                  '.'
                )
              )
            )
          )
      );
    };

    return settings;
  }

  addFilter('blocks.registerBlockType', 'studioimmens/css-classes', AddCSSClassControl);
})();
