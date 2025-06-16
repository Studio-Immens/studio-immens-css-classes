jQuery(document).ready(function($) {
    const $form = $('#si-css-form');
    const $clear = $('#clear');
    const $preview = $('#si-dummy-content');
    const $container = $('#si-classes-container');
    const $search = $('#si-class-search');
    const $count = $('#si-classes-count');
    const $livePreview = $('#si-live-preview');
    const $remClass = $('#remove-class');

    const $classCss = $('#class-css');
    
    // Stato preview
    let previewEnabled = true;
    $livePreview.on('change', function() {
        previewEnabled = this.checked;
        if (!previewEnabled) $preview.removeAttr('style');
    });
    
    // Carica classi
    function loadClasses(search = '') {
        const searchTerm = $search.val().toString().toLowerCase(); // Converti sempre a stringa
        
        $.post(ajaxurl, {
            action: 'si_get_css_classes',
            security: siCssAdmin.nonce
        }, function(response) {
            if (response.success) {
                $container.empty();
                
                // Filtra i risultati
                const filtered = searchTerm ? 
                    response.data.filter(cls => 
                        cls.name.toString().toLowerCase().includes(searchTerm)
                    ) : 
                    response.data;
                
                $count.text(filtered.length);
                
                if (filtered.length === 0) {
                    $container.html(`
                        <div class="si-no-results">
                            ${siCssAdmin.noClasses}
                        </div>
                    `);
                    return;
                }
                
                filtered.forEach(cls => {
                    $container.append(`
                        <div class="si-class-card" data-id="${cls.id}">
                            <div class="si-class-header">
                                <span class="si-class-name">.${cls.name}</span>
                                <div class="si-class-actions">
                                    <a href="#" class="si-edit" data-name="${cls.name}" data-id="${cls.id}" title="${siCssAdmin.edit}">
                                        <span class="dashicons dashicons-edit"></span>
                                    </a>
                                    <a href="#" class="si-preview" data-name="${cls.name}" title="${siCssAdmin.preview}">
                                        <span class="dashicons dashicons-visibility"></span>
                                    </a>
                                    <a href="#" class="si-delete" title="${siCssAdmin.delete}">
                                        <span class="dashicons dashicons-trash"></span>
                                    </a>
                                </div>
                            </div>
                            <pre class="si-class-code">${cls.css}</pre>
                             :hover
                            <pre class="si-hover-code">${cls.hover}</pre>
                             :focus
                            <pre class="si-focus-code">${cls.focus}</pre>
                        </div>
                    `);
                });
            }
        });
    }
    
    // Anteprima
    $container.on('mouseenter', '.si-class-card', function() {
        if (!previewEnabled) return;
        
        const css = $(this).find('.si-class-code').text();
        $preview.css('cssText', css);
    }).on('mouseleave', '.si-class-card', function() {
        if (previewEnabled) $preview.removeAttr('style');
    });
    
    // Anteprima manuale
    $container.on('click', '.si-preview', function(e) {
        e.preventDefault();
        const css = $(this).attr('data-name');

        $preview.addClass(css);
    });

    // Anteprima manuale
    $container.on('click', '.si-edit', function(e) {
        e.preventDefault();
        const id = $(this).attr('data-id');
        const name = $(this).attr('data-name');
        const css = $(this).closest('.si-class-card').find('.si-class-code').text();
        const hover = $(this).closest('.si-class-card').find('.si-hover-code').text();
        const focus = $(this).closest('.si-class-card').find('.si-focus-code').text();

        $('#class-id').val(id);
        $('#class-name').val(name);
        $('#class-css').val(css);
        $('#hover-css').val(hover);
        $('#focus-css').val(focus);

        $('#edit-or-not').val('yes');

        $preview.css('cssText', css);
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
    });
    
    // Ricerca
    $search.on('input', function() {
        loadClasses($(this).val());
    });

    
    // Salva classe
    $form.on('submit', function(e) {
        e.preventDefault();

        const id = $('#class-id').val();
        const name = $('#class-name').val();
        const css = $('#class-css').val();
        const hover = $('#hover-css').val();
        const focus = $('#focus-css').val();
        
        if (!name || !css) {
            alert(siCssAdmin.emptyFields);
            return;
        }

        const eon = $('#edit-or-not').val();
        if ( eon == 'no' ) {
            $.post(ajaxurl, {
                action: 'si_save_css_class',
                name: name,
                css: css,
                hover: hover,
                focus: focus,
                security: siCssAdmin.nonce
            }, function(response) {
                if (response.success) {
                    $form.trigger('reset');
                    $('#edit-or-not').val('no');
                    loadClasses();
                } else {
                    console.error('Errore nel salvataggio:', response.data);
                    alert(siCssAdmin.saveError);
                }
            }).fail(function(xhr) {
                console.error('AJAX Error:', xhr.responseText);
                alert(siCssAdmin.saveError);
            });
        } else {
            $.post(ajaxurl, {
                action: 'si_edit_css_class',
                id: id,
                name: name,
                css: css,
                hover: hover,
                focus: focus,
                security: siCssAdmin.nonce
            }, function(response) {
                if (response.success) {
                    $form.trigger('reset');
                    $('#edit-or-not').val('no');
                    loadClasses();
                } else {
                    console.error('Errore nel salvataggio:', response.data);
                    alert(siCssAdmin.saveError);
                }
            }).fail(function(xhr) {
                console.error('AJAX Error:', xhr.responseText);
                alert(siCssAdmin.saveError);
            });
        }


    });

    // Eliminazione con nonce
    $container.on('click', '.si-delete', function(e) {
        e.preventDefault();
        const id = $(this).closest('.si-class-card').attr('data-id');
        
        $.post(ajaxurl, {
            action: 'si_delete_css_class',
            id: id,
            security: siCssAdmin.nonce  // Aggiungi questo
        }, loadClasses);

        loadClasses();
    });


    $clear.on('click', function(e) {
        $('#class-id').val('');
        $('#class-name').val('');
        $('#class-css').val('');
        $('#hover-css').val('');
        $('#focus-css').val('');

        $('#edit-or-not').val('no');
    });

    $remClass.on('click', function(e) {
        $preview.removeAttr('style');

        $('#si-dummy-content').removeAttr('class');
        $("#si-dummy-content").attr('class', 'si-dummy-content');
    });

    $classCss.on('change', function(e) {
        $preview.attr( 'style', $classCss.val());
    });
    $classCss.on('keyup', function(e) {
        $preview.attr( 'style', $classCss.val());
    });
    
    // Inizializza
    loadClasses();
});