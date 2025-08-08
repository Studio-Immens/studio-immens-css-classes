console.log(
  'Studio Immens css pure-editor is loaded',
  typeof siCssDataPr !== 'undefined' ? siCssDataPr : 'siCssDataPr NON definito'
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

  if (typeof siCssDataPr === 'undefined') {
    console.warn('siCssDataPr non definito, impossibile inizializzare il CSS Class Editor');
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
    if (typeof document === 'undefined' || typeof siCssDataPr === 'undefined') {
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
    styleEl.textContent = siCssDataPr.classes
      .map(c => `${c}`)
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
      const filtered = siCssDataPr.classes.filter((cls) => 
        cls.toLowerCase().includes(searchTerm.toLowerCase())
      );

      // if ( filtered == siCssDataPr.classes.toLowerCase() ) {
      //   filtered = [];
      // }

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
                title: siCssDataPr.labels.title || 'Pure Classes',
                initialOpen: false,
                className: 'studioimmens-css-control',
              },
              // Campo di ricerca
              createElement(TextControl, {
                label: siCssDataPr.labels.search || 'Search Classes',
                value: searchTerm,
                onChange: setSearchTerm,
                placeholder: siCssDataPr.labels.ttos || 'Type to search...',
              }),
              // Lista delle classi filtrate solo se searchTerm è diverso da stringa vuota
              searchTerm.trim() !== ''
                ? createElement(
                    'div',
                    { className: 'si-classes-container' },
                    filtered.length > 0
                      ? filtered.map((cls) => {
                          const isActive = currentClasses.includes(cls);
                          return createElement(
                            Card,
                            {
                              key: cls.id || cls,
                              className: 'si-class-card' + (isActive ? ' is-active' : ''),
                              onMouseEnter: () => showPreview && setPreviewClass(cls),
                              onMouseLeave: () => setPreviewClass(''),
                            },
                            createElement(
                              CardHeader,
                              {},
                              createElement(CheckboxControl, {
                                label: cls,
                                checked: isActive,
                                onChange: (checked) => toggleClass(cls, checked),
                              })
                            )
                          );
                        })
                      : createElement(
                          'p',
                          {},
                          siCssDataPr.labels.nofound || 'No classes found'
                        )
                  )
                : null
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
