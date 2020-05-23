mb.app.bayar_bpjs = mb.app.bayar_bpjs || {};
(function(o){

    var 
        baseAppUrl             = '',
        $form                  = $('#form_bayar_bpjs');

    

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

    var handleMonthPeriode = function()
    {
        $(".date").datepicker( {
            format: "M yyyy",
            viewMode: "months", 
            minViewMode: "months",
            autoclose : true,
        });

    }  

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function(){

            if (! $form.valid()) return;

            var msg = $(this).data('confirm'),
                i = 0;

            bootbox.confirm(msg, function(result){
                if(result == true)
                {
                    Metronic.blockUI({boxed: true});
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form).attr('disabled','disabled');
                    if(i === 1)
                    {
                      $('#save', $form).click();
                    }
                }
            });

        });

        $('a#cek_tagihan').click(function() {
             $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'inquiry',
                data     : $form.serialize(),
                dataType : 'json',
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
                success  : function( results ) {
                    if(results.success == true){
                        $('input#order_id').val(results.order_id);
                        $('input#order_id').attr('value', results.order_id);
                        $('input#inquiry_id').val(results.inquiry_id);
                        $('input#inquiry_id').attr('value', results.inquiry_id);
                        $('input#trx_id').val(results.trx_id);
                        $('input#trx_id').attr('value', results.trx_id);
                        $('input#jpa_ref').attr('value', results.refnum);
                        $('input#jpa_ref').val(results.refnum);
                        $('input#periode').val(results.periode_bayar);
                        $('input#periode').attr('value', results.periode_bayar);
                        $('input#total_bayar_trx').val(results.total_bayar+'.00');
                        $('input#total_bayar_trx').attr('value', results.total_bayar+'.00');
                        $('input#nama_pelanggan').val(results.nama_pelanggan);
                        $('input#nama_pelanggan').attr('value',results.nama_pelanggan);
                        $('input#no_va_keluarga').val(results.no_va_keluarga);
                        $('input#no_va_keluarga').attr('value',results.no_va_keluarga);
                        $('input#no_va_kepala_keluarga').val(results.no_va_kepala_keluarga);
                        $('input#no_va_kepala_keluarga').attr('value',results.no_va_kepala_keluarga);
                        $('input#jumlah_peserta').val(results.jumlah_peserta);
                        $('input#jumlah_peserta').attr('value',results.jumlah_peserta);
                        $('input#jumlah_tagihan').val(results.jumlah_tagihan);
                        $('input#jumlah_tagihan').attr('value',results.jumlah_tagihan);
                        $('input#biaya_admin').val(results.biaya_admin);
                        $('input#biaya_admin').attr('value',results.biaya_admin);
                        $('label#nama_pelanggan').text(results.nama_pelanggan);
                        $('label#jumlah_peserta').text(results.jumlah_peserta);
                        $('label#periode_pembayaran').text(results.periode_bayar);
                        $('label#jumlah_tagihan').text(mb.formatRp(results.jumlah_tagihan));
                        $('label#biaya_admin').text(mb.formatRp(results.biaya_admin));
                        $('label#total_bayar').text(mb.formatRp(results.total_bayar));

                        $('input[name$="[nominal]"]').val(results.total_bayar);

                        $('a#cek_tagihan').addClass('hidden');
                        $('a#bayar_tagihan').removeClass('hidden');
                    }if(results.success == false){
                        $('input#order_id').val('');
                        $('input#inquiry_id').val('');
                        $('input#trx_id').val('');
                        $('input#jpa_ref').val('');
                        $('input#periode').val('');
                        $('input#total_bayar_trx').val('');
                        $('input#nama_pelanggan').val('');
                        $('input#no_va_keluarga').val('');
                        $('input#no_va_kepala_keluarga').val('');
                        $('input#jumlah_peserta').val('');
                        $('input#jumlah_tagihan').val('');
                        $('input#biaya_admin').val('');
                        $('label#nama_pelanggan').text('');
                        $('label#jumlah_peserta').text('');
                        $('label#periode_pembayaran').text('');
                        $('label#jumlah_tagihan').text('');
                        $('label#biaya_admin').text('');
                        $('label#total_bayar').text('');

                        mb.showToast('error', results.msg, 'Informasi');
                        $('a#cek_tagihan').removeClass('hidden');
                        $('a#bayar_tagihan').addClass('hidden');
                    }
                },
                complete : function() {
                     Metronic.unblockUI();
                }
            });
        });

        $('a#bayar_tagihan').click(function() {
             $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'payment',
                data     : $form.serialize(),
                dataType : 'json',
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
                success  : function( results ) {
                    if(results.success == true){
                        $('btn#cek_tagihan').removeClass('hidden');
                        $('btn#bayar_tagihan').addClass('hidden');
                        
                        window.open( baseAppUrl + 'print_invoice_dot/'+results.id_bayar, '_blank');                

                        
                    }else if(results.success == false){
                        mb.showToast('error', results.msg, 'Informasi');

                        $('a#cek_tagihan').removeClass('hidden');
                        $('a#bayar_tagihan').addClass('hidden');
                    }
                },
                complete : function() {
                     Metronic.unblockUI();
                }
            });
        });
    }

    var handleAdvice = function(){
        $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'advice',
                data     : $form.serialize(),
                dataType : 'json',
                success  : function( results ) {
                    if(results.success == true){
                        

                        
                    }
                }
            });
    }

    var textLength = function(value){
       var maxLength = 16;
       if(value.length != maxLength) return false;
       return true;
    }

    var handleSelectSection = function(value)
    {
        if(value == 1)
        {
            $('div#section_1').show();
            $('div#section_2').hide();
            $('div#section_3').hide();
        }
        if(value == 2)
        {
            $('div#section_1').show();
            $('div#section_2').show();
            $('div#section_3').hide();
        }
        if(value == 3)
        {
            $('div#section_3').show();
            $('div#section_2').hide();
            $('div#section_1').show();
        }
    }

    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klaim/bayar_bpjs/';
        handleValidation();
        handleConfirmSave();
        $('select#periode').select2();

        $('select#periode').on('change', function(){
            $('a#cek_tagihan').removeClass('hidden');
            $('a#bayar_tagihan').addClass('hidden');
        }); 

        $('input#no_va').on('change', function(){

            if(!textLength(this.value)){
                mb.showToast('error', 'No. VA HARUS 16 DIGIT', 'Informasi');
            } 

            $('a#cek_tagihan').removeClass('hidden');
            $('a#bayar_tagihan').addClass('hidden');
        });

        handleSelectSection(1);

        $('select[name$="[payment_type]"]').on('change', function(){
            handleSelectSection(this.value);
        });

        $('input[name$="[jumlah_bayar]"]').on('change keyup', function(){

            var jumlah_bayar = parseInt($(this).val()),
                total_invoice = parseInt($('input[name$="[nominal]"]').val()),
                kembali = jumlah_bayar - total_invoice;


            $('input[name$="[kembali]"]').val(kembali);
            $('input[name$="[kembali]"]').attr('value',kembali);

        });
    };
 }(mb.app.bayar_bpjs));


// initialize  mb.app.home.table
$(function(){
    mb.app.bayar_bpjs.init();
});