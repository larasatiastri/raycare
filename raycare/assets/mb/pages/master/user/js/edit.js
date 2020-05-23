mb.app.user = mb.app.user || {};
mb.app.user.edit = mb.app.user.edit || {};

// mb.app.users.add namespace
(function(o){

    var $form = $('#form_edit_user'),
        baseAppUrl = '';

    var handleValidation = function() {
        var error1   = $('.alert-danger', $form);
        var success1 = $('.alert-success', $form);

        $form.validate({
            // class has-error disisipkan di form element dengan class col-*
            errorPlacement: function(error, element) {
                error.appendTo(element.closest('[class^="col"]'));
            },
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            // rules: {
            // buat rulenya di input tag
            // },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                Metronic.scrollTo(error1, -200);
            },

            highlight: function (element) { // hightlight error inputs
                $(element).closest('[class^="col"]').addClass('has-error');
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control group
            }

            // // ajax form submit
            // submitHandler: function (form) {
            //     success1.show();
            //     error1.hide();
            //     // ajax form submit
            // }
        });
        
        //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
        $('#bahasa', $form).change(function () {
            $form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
    }
    
    //untuk dropdown pemilihan bahasa agar muncul bendera setiap negara
    function formatLang(state) {
        if (!state.id) return state.text; // optgroup
        return '<img class="flag" src="' + mb.baseUrl() + 'assets/global/img/flags/' + state.id.toLowerCase() + '.png"/>&nbsp;&nbsp;' + state.text;
    }

    var handleDropdownLanguage = function()
    {
        $('#bahasa', $form).select2({
            placeholder: 'Select a Language',
            allowClear: true,
            formatResult: formatLang,
            formatSelection: formatLang,
            escapeMarkup: function (m) {
                return m;
            }
        });
    }

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;

            var msg = $(this).data('confirm');
            var i = 0;

            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form).attr('disabled','disabled');
                    if(i === 1)
                    {
                        $.ajax({
                            type     : 'POST',
                            url      : baseAppUrl + 'check_modified',
                            data     : {id:$('input[name="id"]').val(), modified_date : $('input[name="modified_date"]').val()},
                            dataType : 'json',
                            beforeSend : function(){
                                Metronic.blockUI({boxed: true });
                            },
                            success  : function( results ) {
                               if(results.success == true)
                               {
                                  $('#save', $form).click();
                                  Metronic.blockUI({boxed: true });
                               }    
                               else
                               {
                                    bootbox.confirm(results.msg, function(result) {
                                        if(result == true)
                                        {
                                            window.open($('#open_new_tab', $form).attr("href"));
                                        }
                                    });
                               }
                            },
                            complete : function(){
                                Metronic.unblockUI();
                            }
                        });
                        
                    }
                }
            });
        });
    };

    var handleErrorAfterSubmit = function(){
        
        var hasError = false;
        $('.help-block', $form).each(function() {
            var str = $(this).text();
            if (str.length>0) {
                //jika tidak mangandung kata 'hint:', ini adalah error message.
                if (str.indexOf('hint:') == -1) {
                    $(this).parent().addClass( "has-error" );
                    hasError = true;
                }
            } 
        });
        
        if (hasError == true) $('.alert-danger', $form).show();

    };

    var handleUploadify = function()
    {
        var ul = $('#upload ul#upload');

       
        // Initialize the jQuery File Upload plugin
        $('#upl').fileupload({

            // This element will accept file drag/drop uploading
            dropZone: $('#drop'),
            dataType: 'json',
            // This function is called when a file is added to the queue;
            // either via the browse button, or via drag/drop:
            add: function (e, data) {

                tpl = $('<li class="working"><div class="thumbnail"></div><span></span></li>');

                // Initialize the knob plugin
                tpl.find('input').knob();

                // Listen for clicks on the cancel icon
                tpl.find('span').click(function(){

                    if(tpl.hasClass('working')){
                        jqXHR.abort();
                    }

                    tpl.fadeOut(function(){
                        tpl.remove();
                    });

                });

                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
            },
            done: function(e, data){

                console.log(data);
                var filename = data.result.filename;
                var filename = filename.replace(/ /g,"_");
                
                tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                $('input#url').attr('value',filename);
                // Add the HTML to the UL element
                ul.html(tpl);
                // data.context = tpl.appendTo(ul);
                
                handleFancybox();
                Metronic.unblockUI();

                    // data.context = tpl.appendTo(ul);

            },

            progress: function(e, data){

                // Calculate the completion percentage of the upload
                Metronic.blockUI({boxed: true});
            },


            fail:function(e, data){
                // Something has gone wrong!
                bootbox.alert('File Tidak Dapat Diupload');
                Metronic.unblockUI();
            }
        });


        // Prevent the default action when a file is dropped on the window
        $(document).on('drop dragover', function (e) {
            e.preventDefault();
        });

        // Helper function that formats the file sizes
        function formatFileSize(bytes) {
            if (typeof bytes !== 'number') {
                return '';
            }

            if (bytes >= 1000000000) {
                return (bytes / 1000000000).toFixed(2) + ' GB';
            }

            if (bytes >= 1000000) {
                return (bytes / 1000000).toFixed(2) + ' MB';
            }

            return (bytes / 1000).toFixed(2) + ' KB';
        }
    }

    var handleUploadifySign = function()
    {
        var ul_sign = $('#upload ul#upload_sign');

       
        // Initialize the jQuery File Upload plugin
        $('#upl_sign').fileupload({

            // This element will accept file drag/drop uploading
            dropZone: $('#drop'),
            dataType: 'json',
            // This function is called when a file is added to the queue;
            // either via the browse button, or via drag/drop:
            add: function (e, data) {

                tpl = $('<li class="working"><div class="thumbnail"></div><span></span></li>');

                // Initialize the knob plugin
                tpl.find('input').knob();

                // Listen for clicks on the cancel icon
                tpl.find('span').click(function(){

                    if(tpl.hasClass('working')){
                        jqXHR.abort();
                    }

                    tpl.fadeOut(function(){
                        tpl.remove();
                    });

                });

                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
            },
            done: function(e, data){

                console.log(data);
                var filename = data.result.filename;
                var filename = filename.replace(/ /g,"_");
                
                tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                $('input#url_sign').attr('value',filename);
                // Add the HTML to the UL element
                ul_sign.html(tpl);
                // data.context = tpl.appendTo(ul);
                
                handleFancybox();
                Metronic.unblockUI();

                    // data.context = tpl.appendTo(ul);

            },

            progress: function(e, data){

                // Calculate the completion percentage of the upload
                Metronic.blockUI({boxed: true});
            },


            fail:function(e, data){
                // Something has gone wrong!
                bootbox.alert('File Tidak Dapat Diupload');
                Metronic.unblockUI();
            }
        });


        // Prevent the default action when a file is dropped on the window
        $(document).on('drop dragover', function (e) {
            e.preventDefault();
        });

        // Helper function that formats the file sizes
        function formatFileSize(bytes) {
            if (typeof bytes !== 'number') {
                return '';
            }

            if (bytes >= 1000000000) {
                return (bytes / 1000000000).toFixed(2) + ' GB';
            }

            if (bytes >= 1000000) {
                return (bytes / 1000000).toFixed(2) + ' MB';
            }

            return (bytes / 1000).toFixed(2) + ' KB';
        }
    }

    var handleFancybox = function() {
        if (!jQuery.fancybox) {
            return;
        }

        if ($(".fancybox-button").size() > 0) {
            $(".fancybox-button").fancybox({
                groupAttr: 'data-rel',
                prevEffect: 'none',
                nextEffect: 'none',
                closeBtn: true,
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            });
        }
    };

    o.init = function(){
        baseAppUrl = mb.baseUrl()+'master/user/'
        handleValidation();
        handleDropdownLanguage();
        handleUploadify();
        handleUploadifySign();
        handleConfirmSave();
        handleErrorAfterSubmit();
        handleFancybox();
    };

}(mb.app.user.edit));


// initialize  mb.app.users.add
$(function(){
    mb.app.user.edit.init();
});