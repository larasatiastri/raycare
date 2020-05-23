mb.app.resep_obat = mb.app.resep_obat || {};
mb.app.resep_obat.proses = mb.app.resep_obat.proses || {};

// mb.app.resep_obat.proses namespace
(function(o){

    var 
        baseAppUrl              = '',
        $form                   = $('#form_proses_resep'),
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

    var handleChangeRb = function() {
        
        var $tableDetail = $('table#table_identitas_detail', $('table#table_item')),
            $rb = $('input[name$="[aksi]"]',$tableDetail);

            $rb.on('click', function(){
                var row = $(this).data('row'),
                    value = $(this).val();

                if(value == 2){
                    $('td#pj_column', $('tr#item_detail_row_'+row)).removeClass('hidden');
                    $('td#field_pj', $(this).parents('.fieldset').eq(0)).removeClass('hidden');
                }else{
                    $('td#pj_column', $('tr#item_detail_row_'+row)).addClass('hidden');
                    $('td#field_pj', $(this).parents('.fieldset').eq(0)).addClass('hidden');
                }
                
            });

    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/resep_obat/';
        initForm(); 
        handleChangeRb();
    };

}(mb.app.resep_obat.proses));


// initialize  mb.app.resep_obat.proses
$(function(){
    mb.app.resep_obat.proses.init();
});