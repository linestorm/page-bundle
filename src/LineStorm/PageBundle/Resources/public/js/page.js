
var contentCounts = contentCounts || {};

define(['jquery', 'jqueryui', 'dropzone', 'cms_api'], function ($, $ui, Dropzone, api) {

    // setup dropzone
    Dropzone.autoDiscover = false;

    // add a new form to the page from a prototype
    window.addForm = function($collectionHolder, prototype, indexer, name) {
        var newForm, newContainer, $elementHtml;

        if(indexer === undefined){
            console.log('addForm: indexer must be defined');
            return;
        }

        if(name === undefined)
            name = '__name__';

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have#
        var rgx = new RegExp(name, 'g');
        if(indexer){
            newForm = prototype.replace(rgx, indexer.count);
            indexer.count++;
        } else {
            newForm = prototype.replace(rgx, '');
        }


        newContainer = $collectionHolder.data('prototype').replace(/__widget__/, newForm);

        $elementHtml = $(newContainer);
        $collectionHolder.append($elementHtml);

        ++window.contentCounts.components;

        return $elementHtml;
    };


    $(document).ready(function(){
        $('form.api-save').on('submit', function(e){
            e.preventDefault();
            e.stopPropagation();
            $('#FormErrors').slideUp(function(){ $(this).html(''); });
            window.lineStorm.api.saveForm($(this), function(on, status, xhr){
                if(xhr.status === 200){
                } else if(xhr.status === 201) {
                    window.location = on.location;
                } else {
                }
            }, function(e, status){
                if(e.status === 400){
                    if(e.responseJSON){
                        var errors = window.lineStorm.api.parseError(e.responseJSON.errors);
                        var str = '';
                        for(var i in errors){
                            if(errors[i].length)
                                str += "<p class=''><strong style='text-transform:capitalize;'>"+i+":</strong> "+errors[i].join(', ')+"</p>";
                        }
                        $('#FormErrors').html(str).slideDown();
                    } else {
                        alert(status);
                    }
                }
            });

            return false;
        });

        $('.page-form-delete').on('click', function(){
            if(confirm("Are you sure you want to permanently delete this page?")){
                window.lineStorm.api.call($(this).data('url'), {
                    method: 'DELETE',
                    success: function(o){
                        alert(o.message);
                        window.location = o.location;
                    }
                });
            }
        });

        var $pageBodyHolder;

        $pageBodyHolder = $('.page-components');

        $('a.page-component-new').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Get the data-prototype explained earlier
            var id = $(this).data('id');
            var prototype = $(this).data('prototype');

            // add a new tag form (see next code block)
            var $el = addForm($pageBodyHolder, prototype, contentCounts[id]);

            $el.find('.page-component-item').addClass('item-'+id).trigger('widget-init');

            return false;
        });

        // set up the cover image dropzone

        var $coverImageDropZone = $('.dropzone-coverImage');
        var coverImageformId = $coverImageDropZone.data('form-target');
        var $coverImageform = $('#'+coverImageformId);
        var $coverImageformPreview = $('.'+coverImageformId+'_preview');

        new Dropzone($coverImageDropZone[0], {
            url: window.lineStormTags.mediaBank.upload,
            acceptedFiles: 'image/*',
            init: function(){
                this.on("success", function(file, response) {
                    if(file.xhr.status == 200){
                        alert('An identical file already exists and has been returned.');
                    }
                    $coverImageform.val(response.id);
                    $coverImageformPreview.attr('src', response.src);
                    this.removeFile(file);
                });
                this.on("error", function(file, response) {
                    this.removeFile(file);
                    alert("Cannot add file:\n\n"+response.error);
                });
                this.on("removedfile", function(file){
                });
            },
            previewTemplate: $coverImageDropZone.data('preview')
        });

        // set up the sortable content
        $pageBodyHolder.sortable({
            handle: '.item-reorder',
            axis: 'y',
            create: function( event, ui ) {
                var $ul = $(this);
                $ul.children('li').sort(function(a,b) {
                    return a.dataset.order > b.dataset.order;
                }).appendTo($ul);
            },
            start: function(e, ui){

                $(e.target).children().addClass('fade-overlay');
                $(this).sortable('refreshPositions');

                // save the ckeditor state and destroy it else is breaks on sorting stop
                var tarea = ui.item.find('textarea.ckeditor-textarea');
                if(tarea.length){
                    tarea.data('value', tarea.val()).val('Moving...');
                    var ck = tarea.ckeditorGet();
                    ck.destroy();
                }
            },
            stop:function(e,ui){

                $(e.target).children().removeClass('fade-overlay');
                $(this).sortable('refreshPositions');

                // rebuild ckeditor
                var tarea = ui.item.find('textarea.ckeditor-textarea');
                if(tarea.length){
                    tarea.val(tarea.data('value'));
                    tarea.ckeditor();
                }

                // update the order
                $pageBodyHolder.children('li').each(function(i, li){
                    var $li = $(li);
                    var $order = $li.find('input[name*="[order]"]');
                    $order.val(i);
                });
            }
        });

        // configure remove button
        $pageBodyHolder.on('click', 'button.item-remove', function(){
            if(confirm('Are you sure you want to remove this item?\n\nNOTE: IT CANNOT BE UNDONE ONCE SAVED')){
                var i = $(this).data('count');
                $(this).closest('.page-component-item').parent().remove();
            }
        });

        $(document).on('click', '.options-toggle', function(){
            $(this).next('.'+$(this).data('toggle')).slideToggle();
            return false;
        });

        var $categorySelect = $('.category-select');
        $('.add-category').on('click', function(e){

            e.preventDefault();
            e.stopPropagation();


            window.lineStorm.api.call(this.href, {
                'success': function(o){
                    if(o.form){
                        var $modal = $(window.lineStorm.modalContainer.replace(/__title__/gim, 'New Category').replace(/__widget__/gim, o.form));
                        var $form = $modal.find('form');

                        $modal.find('button.modal-save').on('click', function(){
                           $form.submit();
                        });
                        $form.on('submit', function(){
                            window.lineStorm.api.saveForm($form, function(o){
                                $categorySelect.append('<option value="'+o.id+'">'+o.name+'</option>').val(o.id);
                                $modal.modal('hide');
                            },function(xhr, state){
                            });
                            return false;
                        });

                        $modal.modal({}).appendTo(document.body);
                    }
                }
            });

            return false;
        });

        // auto fill in the slug until it is changed
        var hasSlugChanged = false,
            hasRouteChanged = false,
            $slugInput  = $('input.page-form-slug'),
            $routeInput  = $('input.page-form-route'),
            $titleInput = $('input.page-form-title');

        $titleInput.on('keyup', function(){
            if(!hasSlugChanged){
                $slugInput.val('/'+this.value.replace(/[^\w\d\s-]/g, '').replace(/\s+/g, '-').toLowerCase());
            }
            if(!hasRouteChanged){
                $routeInput.val(this.value.replace(/[^\w\d\s-]/g, '').replace(/\s+/g, '_').toLowerCase());
            }
        });
        $slugInput.on('keyup', function(){
            hasSlugChanged = true;
        });
        $routeInput.on('keyup', function(){
            hasRouteChanged = true;
        });

    });

});
