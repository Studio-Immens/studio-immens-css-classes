console.log( 'Studio Immens css block is loaded' );
( function( blocks, element, blockEditor ) {
    const { registerBlockType } = blocks;
    const { createElement: el } = element;
    const { PlainText } = blockEditor;

    registerBlockType( 'studioimmens/css-editor', {
        title: 'CSS Personalizzato',
        icon: 'editor-code',
        category: 'design',
        attributes: {
            cssCode: {
                type: 'string',
                default: ''
            }
        },
        edit: function( props ) {
            return el(
                'div',
                { className: props.className },
                el( 'p', {}, 'Inserisci CSS da applicare a questo post:' ),
                el( PlainText, {
                    value: props.attributes.cssCode,
                    onChange: function( value ) {
                        props.setAttributes( { cssCode: value } );
                    },
                    placeholder: 'Es: body { background: red; }'
                } )
            );
        },
        save: function( props ) {
            return el( 'style', {}, props.attributes.cssCode );
        }
    } );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );
