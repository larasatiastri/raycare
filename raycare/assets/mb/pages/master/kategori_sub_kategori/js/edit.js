mb.app.kategori_sub_kategori = mb.app.kategori_sub_kategori || {};
mb.app.kategori_sub_kategori.add = mb.app.kategori_sub_kategori.add || {};
(function(o){

    var 
        baseAppUrl      = '',
        $form           = $('#form_edit')
    ;

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

            
        });       
    }


    var handleConfirmSave = function()
    {
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'check_modified_kategori',
                        data     : {id:$('input[name="id"]').val(), modified_date : $('input[name="modified_date"]').val()},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                           if(results.success == true)
                           {
                              $('#save', $form).click();
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
            });
        });
    };

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/kategori_sub_kategori/';
        handleValidation();
        handleConfirmSave();
    };
    
 }(mb.app.kategori_sub_kategori.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.kategori_sub_kategori.add.init();
});