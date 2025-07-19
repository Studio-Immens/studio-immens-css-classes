console.log( 'Studio Immens css block is loaded' );

( function( blocks, element, blockEditor, i18n ) {
    const { registerBlockType } = blocks;
    const { createElement: el } = element;
    const { PlainText } = blockEditor;
    const { __ } = i18n;

    registerBlockType( 'studioimmens/css-editor', {
        title: siCssDataBlock.labels.title || 'Custom CSS',
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
                { className: props.className + ' studioimmens-css-editor-block' },
                el( 'p', {}, siCssDataBlock.labels.desc || 'Enter CSS to apply to this post:' ),
                el( PlainText, {
                    value: props.attributes.cssCode,
                    onChange: function( value ) {
                        props.setAttributes( { cssCode: value } );
                    },
                    placeholder: __( 'Es: body { background: red; }', 'studio-immens-css-classes' )
                } )
            );
        },
        save: function( props ) {
            // Il CSS viene iniettato nel frontend all'interno di <style>
            return el( 'style', {}, props.attributes.cssCode );
        }
    } );
} )(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.i18n
);
