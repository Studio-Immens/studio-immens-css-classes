console.log(
  'Studio Immens css editor is loaded',
  typeof siCssData !== 'undefined' ? siCssData : 'siCssData NON definito'
);

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
    console.warn('siCssData non definito, impossibile inizializzare il CSS Class Editor');
    return;
  }

  const { __ } = wp.i18n;
  const { createElement, Fragment, useState } = wp.element;
  const { InspectorControls } = wp.blockEditor;
  const {
    PanelBody,
    ToggleControl,
    TextControl,
    Card,
    CardBody,
    CardHeader,
    CheckboxControl,
  } = wp.components;
  const { addFilter } = wp.hooks;

  (function injectDynamicCSS() {
    if (typeof document === 'undefined' || typeof siCssData === 'undefined') {
      return;
    }
    const head = document.head || document.getElementsByTagName('head')[0];
    let styleEl = head.querySelector('#si-css-dynamic');
    if (!styleEl) {
      styleEl = document.createElement('style');
      styleEl.id = 'si-css-dynamic';
      head.appendChild(styleEl);
    }
    // Inietta tutte le regole attuali in un solo colpo
    styleEl.textContent = siCssData.classes
      .map(c => `.${c.name} { ${c.css} }`)
      .join('\n');
  })();


  function AddCSSClassControl(settings) {
    if (!settings.supports) {
      return settings;
    }

    const originalEdit = settings.edit;

    settings.edit = function (props) {
      // Stato locale
      const [searchTerm, setSearchTerm] = useState('');
      const [showPreview, setShowPreview] = useState(false);
      const [previewClass, setPreviewClass] = useState('');

      // Classi correnti
      const currentClasses = props.attributes.className
        ? props.attributes.className.split(' ').filter(Boolean)
        : [];

      // Filtra le classi
      const filtered = siCssData.classes.filter((cls) =>
        cls.name.toLowerCase().includes(searchTerm.toLowerCase())
      );

      // Aggiungi/rimuovi classe
      const toggleClass = (className, checked) => {
        const setClasses = new Set(currentClasses);
        if (checked) setClasses.add(className);
        else setClasses.delete(className);
        props.setAttributes({ className: Array.from(setClasses).join(' ') });
      };

      // Render
      return createElement(
        Fragment,
        {},
        // Render originale
        createElement(originalEdit, props),
        // Solo se selezionato
        props.isSelected &&
          createElement(
            InspectorControls,
            {},
            createElement(
              PanelBody,
              {
                title: siCssData.labels.title || __('CSS Classes', 'studio-immens'),
                initialOpen: false,
                className: 'studioimmens-css-control',
              },
              // Campo di ricerca
              createElement(TextControl, {
                label: siCssData.labels.search || __('Search Classes', 'studio-immens'),
                value: searchTerm,
                onChange: setSearchTerm,
                placeholder: siCssData.labels.ttos || __('Type to search...', 'studio-immens'),
              }),
              // Toggle anteprima
              createElement(ToggleControl, {
                label: siCssData.labels.livep || __('Live Preview', 'studio-immens'),
                checked: showPreview,
                onChange: () => {
                  setShowPreview(!showPreview);
                  if (!showPreview) setPreviewClass('');
                },
              }),
              // Lista delle classi filtrate
              createElement(
                'div',
                { className: 'si-classes-container' },
                filtered.length > 0
                  ? filtered.map((cls) => {
                      const isActive = currentClasses.includes(cls.name);
                      return createElement(
                        Card,
                        {
                          key: cls.id || cls.name,
                          className: 'si-class-card' + (isActive ? ' is-active' : ''),
                          onMouseEnter: () => showPreview && setPreviewClass(cls.css),
                          onMouseLeave: () => setPreviewClass(''),
                        },
                        createElement(
                          CardHeader,
                          {},
                          createElement(CheckboxControl, {
                            label: '.' + cls.name,
                            checked: isActive,
                            onChange: (checked) => toggleClass(cls.name, checked),
                          })
                        ),
                        createElement(
                          CardBody,
                          {},
                          createElement('pre', { className: 'si-class-code' }, cls.css)
                        )
                      );
                    })
                  : createElement(
                      'p',
                      {},
                      siCssData.labels.nofound || __('No classes found', 'studio-immens')
                    )
              )
            )
          )
      );
    };

    return settings;
  }

  // Aggiunge il pannello a tutti i blocchi registrati
  addFilter(
    'blocks.registerBlockType',
    'studioimmens/css-classes',
    AddCSSClassControl
  );
})();
