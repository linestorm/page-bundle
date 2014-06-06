
var contentCounts = contentCounts || {};

define(['jquery', 'cms_api'], function ($, api) {


    $(document).ready(function(){
        $('form.api-save').on('submit', function(e){
            e.preventDefault();
            e.stopPropagation();
            $('#FormErrors').slideUp(function(){ $(this).html(''); });
            api.saveForm($(this), function(on, status, xhr){
                if(xhr.status === 200){
                } else if(xhr.status === 201) {
                    window.location = on.location;
                } else {
                }
            }, function(e, status){
                if(e.status === 400){
                    if(e.responseJSON){
                        var errors = api.parseError(e.responseJSON.errors);
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
                api.call($(this).data('url'), {
                    method: 'DELETE',
                    success: function(o){
                        alert(o.message);
                        window.location = o.location;
                    }
                });
            }
        });

    });

});
