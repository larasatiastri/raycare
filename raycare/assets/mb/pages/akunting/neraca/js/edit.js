mb.app.neraca = mb.app.neraca || {};
mb.app.neraca.edit = mb.app.neraca.edit || {};


(function(o){
    
     var 
        baseAppUrl          = '',
        $form               = $('#form_edit_neraca');

    var initForm = function(){ 
        handleValidation();
        handleDatePickers();
        handleConfirmSave();

        $('input[name$="[nominal]"]').on('change keyup', function(){

            var parent = $(this).data('parent_id'),
                nominal = $(this).val();

                // alert(parent);

            handleCountTotalParent(parent);

        });
    };

    var handleCountTotalParent = function($parent) {
        
        var $child = $('input.'+$parent);

        var total = 0;
        $.each($child, function(idx, child){
            var nominal = $(this).val();

            if(nominal == ""){
                nominal = 0;
            }else{
                nominal = parseFloat(nominal);
            }

            total = total + nominal;
        }); 

        $('input#akun_'+$parent).val(total);
        $('input#akun_'+$parent).attr('value',total);

        var $parentCurrent = $('input.parent_current_asset'),
            $parentFixed = $('input.parent_fixed_asset'),
            $parentLiability = $('input.parent_liability'),
            $parentEquity = $('input.parent_equity');

        var total_current = 0;
        $.each($parentCurrent, function(idx, current){
            var nominal_current = $(this).val();

            if(nominal_current == ""){
                nominal_current = 0;
            }else{
                nominal_current = parseFloat(nominal_current);
            }

            total_current = total_current + nominal_current;
        });

        $('th#total_current_asset').text(mb.formatRp(total_current));
        $('input#total_current_asset').val(total_current);
        $('input#total_current_asset').attr('value',total_current);
        
        var total_fixed = 0;
        $.each($parentFixed, function(idx, fixed){
            var nominal_fixed = $(this).val();

            if(nominal_fixed == ""){
                nominal_fixed = 0;
            }else{
                nominal_fixed = parseFloat(nominal_fixed);
            }

            total_fixed = total_fixed + nominal_fixed;
        });

        $('th#total_fixed_asset').text(mb.formatRp(total_fixed));
        $('input#total_fixed_asset').val(total_fixed);
        $('input#total_fixed_asset').attr('value',total_fixed);

        var total_aktiva = parseFloat(total_current + total_fixed);

        $('div#total_aktiva').html('<b>' + mb.formatRp(total_aktiva) + '</b>');
        $('input#total_aktiva').val(total_aktiva);
        $('input#total_aktiva').attr('value', total_aktiva);


        var total_liability = 0;
        $.each($parentLiability, function(idx, liability){
            var nominal_liability = $(this).val();

            if(nominal_liability == ""){
                nominal_liability = 0;
            }else{
                nominal_liability = parseFloat(nominal_liability);
            }

            total_liability = total_liability + nominal_liability;
        });

        $('th#total_liability').text(mb.formatRp(total_liability));
        $('input#total_liability').val(total_liability);
        $('input#total_liability').attr('value',total_liability);

        var total_equity = 0;
        $.each($parentEquity, function(idx, equity){
            var nominal_equity = $(this).val();

            if(nominal_equity == ""){
                nominal_equity = 0;
            }else{
                nominal_equity = parseFloat(nominal_equity);
            }

            total_equity = total_equity + nominal_equity;
        });

        $('th#total_equity').text(mb.formatRp(total_equity));
        $('input#total_equity').val(total_equity);
        $('input#total_equity').attr('value',total_equity);

        var total_pasiva = parseFloat(total_liability + total_equity);

        $('div#total_pasiva').html('<b>' + mb.formatRp(total_pasiva) + '</b>');
        $('input#total_pasiva').val(total_pasiva);
        $('input#total_pasiva').attr('value', total_pasiva);

    }

   
   

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            // alert('klik');
            if (! $form.valid()) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    $('#save', $form).click();
                }
            });
        });
    };

 
    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

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


    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'akunting/neraca/';
        initForm();
    
        // handleDropdownTypeChange();
 
    };

}(mb.app.neraca.edit));

$(function(){    
    mb.app.neraca.edit.init();
});