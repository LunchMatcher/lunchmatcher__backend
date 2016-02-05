//this is the application.js file from the example code//
$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload();
    
    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            './cors/result.html?%s'
            )
        );
            
           $('#download-files > .template-download > .add').each(function(e){
                             

                                alert(e);

                                

                                });
            
           

            
       

    if (window.location.hostname === 'perrot-julien.fr') {
       
        // Upload server status check for browsers with CORS support:
        if ($.ajaxSettings.xhr().withCredentials !== undefined) {
            $.ajax({
                url: 'admin/get_files',
                dataType: 'json', 
                
                success : function(data) {  
                    var fu = $('#fileupload').data('fileupload'), 
                    template;
                    fu._adjustMaxNumberOfFiles(-data.length);
                    template = fu._renderDownload(data)
                    .appendTo($('#fileupload .files'));
                    
                    // Force reflow:
                    fu._reflow = fu._transition && template.length &&
                    template[0].offsetWidth;
                    template.addClass('in');
                    $('#loading').remove();
                }  
         
                
            }).fail(function () {
                $('<span class="alert alert-error"/>')
                .text('Upload server currently unavailable - ' +
                    new Date())
                .appendTo('#fileupload');
            });
        }
    } else {
        // Load existing files:
        $('#fileupload').each(function () {
            var that = this;
            $.getJSON(this.action, function (result) {
                if (result && result.length) {
                    $(that).fileupload('option', 'done')
                    .call(that, null, {
                        result: result
                    });
                }
            });
        });
    }


    // Open download dialogs via iframes,
    // to prevent aborting current uploads:
    $('#fileupload .files a:not([target^=_blank])').live('click', function (e) {
        e.preventDefault();
        $('<iframe style="display:none;"></iframe>')
        .prop('src', this.href)
        .appendTo('body');
    });
var fu = $('#fileupload').data("fileupload");
var eventData = { fileupload: fu };
var clickEvent = 'click.' + fu.options.namespace;

fu._files
    .off(clickEvent, '.delete button')
    .on(clickEvent, '.delete button', eventData, function(e) {
        var fileName = $(this).parent().siblings(".name").children().prop("title");
        if (confirm("Are you sure you want to delete " + fileName +"?")) {
            fu._deleteHandler.call($(this), e);
        } else {
            return false;
        }
    });

fu.element.find('.fileupload-buttonbar .delete')
    .off(clickEvent)
    .on(clickEvent, eventData, function(e) {
        var toDelete = fu._files.find('.delete input:checked')
            .siblings('button');

        if (toDelete.size() > 0 && confirm("Are you sure you want to delete the selected files?")) {
            toDelete.each(function() {
                fu._deleteHandler.call($(this), e);
            });
        } else {
            return false;
        }
    });
});
