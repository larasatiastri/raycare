mb.app.add = mb.app.add || {};


(function(o){
    
     var 
        baseAppUrl        = '',
        $form             = $('#form_add_permintaan_kasbon'),
        $tableTambahBiaya = $('#table_tambah_biaya',$form);


    var initForm = function(){
        handleValidation();
        handleDatePickers();
        handleConfirmSave();
        handleChangeHarga();
    };

    var handleChangeHarga = function(){
        $('input[name$="[harga]"]', $tableTambahBiaya).on('change', function(){

            var idx      = $(this).data('idx'),
                harga    = $(this).val(),
                jml      = parseFloat($('input#biaya_jumlah_'+idx).val()),
                subtotal = 0;

            if(harga == ''){
                harga = 0;
            }

            subtotal = jml * parseFloat(harga);


            $('label#biaya_label_sub_total_'+idx).text(mb.formatRp(subtotal));
            $('input#biaya_sub_total_'+idx).val(subtotal);
            $('input#biaya_sub_total_'+idx).attr("value", subtotal);

            countTotalBiaya();
        });

        $('input[name$="[jumlah]"]', $tableTambahBiaya).on('change', function(){

            var idx      = $(this).data('idx'),
                jml      = parseFloat($(this).val()),
                harga    = $('input#biaya_harga_'+idx).val(),
                subtotal = 0;

            if(harga == ''){
                harga = 0;
            }

            subtotal = jml * parseFloat(harga);


            $('label#biaya_label_sub_total_'+idx).text(mb.formatRp(subtotal));
            $('input#biaya_sub_total_'+idx).val(subtotal);
            $('input#biaya_sub_total_'+idx).attr("value", subtotal);

            countTotalBiaya();
        });
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
        var time = new Date($('#tanggal').val());
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                orientation: "left",
                autoclose: true,
                update : time

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

    var handleTerbilang = function(nominal){
        $.ajax
        ({
            type: 'POST',
            url: baseAppUrl +  "get_terbilang",  
            data:  {nominal:nominal},  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            { 
                
              $('label#terbilang').text(data.terbilang);
            
            },
            complete : function(){
              Metronic.unblockUI();
            }
        });
    }


    var countTotalBiaya = function(){
        var $inputNominal = $('input.nominal_biaya', $form);
        var total = 0;
        $.each($inputNominal, function(idx, inputNominal){
            var nominal = $(this).val();

            if(nominal == ""){
                nominal = 0;
            }else{
                nominal = parseInt(nominal);
            }

            total = total + nominal;
        }); 

        $('input[name="nominal_show"]', $form).val(mb.formatRp(total));
        $('input[name="nominal"]', $form).val(total);
        handleTerbilang(total);
    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/permintaan_kasbon/';
        initForm();

    };

}(mb.app.add));

$(function(){    
    mb.app.add.init();
});