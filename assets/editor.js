(function() {
    if (
        typeof wp === 'undefined' || 
        typeof wp.blocks === 'undefined' || 
        typeof wp.element === 'undefined' ||
        typeof wp.blockEditor === 'undefined' ||
        typeof wp.components === 'undefined'
    ) {
        return;
    }

    const { __ } = wp.i18n;
    const { createElement, Fragment, useState, useEffect } = wp.element;
    const { InspectorControls, useBlockEditContext } = wp.blockEditor;
    const { PanelBody, ToggleControl, TextControl, Button, Card, CardBody, CardHeader, CheckboxControl } = wp.components;
    const { addFilter } = wp.hooks;

    function AddCSSClassControl(settings, name) {
        if (!settings.supports || !settings.supports.className) {
            // return settings;
        }

        const originalEdit = settings.edit;
        
        settings.edit = function(props) {
            const blockEditContext = useBlockEditContext();
            const [searchTerm, setSearchTerm] = useState('');
            const [showPreview, setShowPreview] = useState(false);
            const [previewClass, setPreviewClass] = useState('');
            
            // Estrae le classi correnti
            const currentClasses = props.attributes.className ? 
                props.attributes.className.split(' ') : 
                [];
                
            // Filtra le classi in base alla ricerca
            const filteredClasses = siCssData.classes.filter(cls => 
                cls.name.toLowerCase().includes(searchTerm.toLowerCase())
            );
            
            // Gestione toggle classi
            const toggleClass = (className, isChecked) => {
                let newClasses;
                
                if (isChecked) {
                    newClasses = [...currentClasses, className];
                } else {
                    newClasses = currentClasses.filter(c => c !== className);
                }
                
                props.setAttributes({ className: newClasses.join(' ') });
            };
            
            // Anteprima al passaggio del mouse
            const handleMouseEnter = (css) => {
                if (showPreview) setPreviewClass(css);
            };
            
            return createElement(
                Fragment,
                null,
                createElement(originalEdit, props),
                blockEditContext.isSelected && createElement(
                    InspectorControls,
                    null,
                    createElement(
                        PanelBody, 
                        { 
                            title: siCssData.labels.title, 
                            initialOpen: false,
                            className: 'studioimmens-css-control'
                        },
                        createElement(TextControl, {
                            label: __('Search Classes', 'studioimmens-css'),
                            value: searchTerm,
                            onChange: setSearchTerm,
                            placeholder: __('Type to search...', 'studioimmens-css')
                        }),
                        
                        createElement(ToggleControl, {
                            label: __('Live Preview', 'studioimmens-css'),
                            checked: showPreview,
                            onChange: () => {
                                setShowPreview(!showPreview);
                                if (!showPreview) setPreviewClass('');
                            }
                        }),
                        
                        createElement('div', { className: 'si-classes-container' },
                            filteredClasses.length > 0 ? 
                                filteredClasses.map(cls => {
                                    const isActive = currentClasses.includes(cls.name);
                                    return createElement(
                                        Card,
                                        {
                                            key: cls.id,
                                            className: `si-class-card ${isActive ? 'is-active' : ''}`,
                                            onMouseEnter: () => handleMouseEnter(cls.css),
                                            onMouseLeave: () => setPreviewClass('')
                                        },
                                        createElement(CardHeader, null,
                                            createElement(CheckboxControl, {
                                                label: `.${cls.name}`,
                                                checked: isActive,
                                                onChange: (checked) => toggleClass(cls.name, checked)
                                            })
                                        ),
                                        createElement(CardBody, null,
                                            createElement('pre', { className: 'si-class-code' }, cls.css)
                                        )
                                    );
                                })
                            : createElement('p', null, __('No classes found', 'studioimmens-css'))
                        )
                    )
                )
            );
        };

        return settings;
    }

    addFilter(
        'blocks.registerBlockType',
        'studioimmens/css-classes',
        AddCSSClassControl
    );
})();