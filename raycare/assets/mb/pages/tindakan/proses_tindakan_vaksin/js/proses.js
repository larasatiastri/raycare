mb.app.tindakan_vaksin = mb.app.tindakan_vaksin || {};
mb.app.tindakan_vaksin.proses = mb.app.tindakan_vaksin.proses || {};

// mb.app.tindakan_vaksin.proses namespace
(function(o){

    var 
        baseAppUrl              = '',
        $form                   = $('#form_proses_tindakan_vaksin'),
        $errorTop               = $('.alert-danger', $form),
        $succesTop              = $('.alert-success', $form);


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
                $(element).closest('[class^="col"]').prosesClass('has-error');
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control locker
            },

            success: function (label) {
                $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control locker
            } 
        });   
    }

    var initForm = function(){
        handleConfirmSave();
    };

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function(e) {
            
            if (! $form.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                     $('#save',$form).click();  
                }
            });
            e.preventDefault();
        });
    };


    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'tindakan/proses_tindakan_vaksin/';
        initForm(); 
    };

}(mb.app.tindakan_vaksin.proses));


// initialize  mb.app.tindakan_vaksin.proses
$(function(){
    mb.app.tindakan_vaksin.proses.init();
});