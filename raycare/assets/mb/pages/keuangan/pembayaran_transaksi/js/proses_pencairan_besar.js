mb.app.proses = mb.app.proses || {};


(function(o){
    
     var 
        baseAppUrl            = '',
        $form                 = $('#form_proses');

    var initForm = function(){
        handleValidation();
        handleConfirmSave();
        handleTerbilang();
        handleChangeTipeBayar();
        handleBankSelect();
        handleDatePickers();
        $('select[name$="[biaya_tambah_id]"]', $form).select2();
    };

    
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
    };

    var handleTerbilang = function(){
        $('input#nominal_setujui').on('change', function(){
            var nominal = $(this).val();
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
                    
                  $('label#terbilang_setujui').text(data.terbilang);
                
                },
                complete : function(){
                  Metronic.unblockUI();
                }
            });

        });
    }

    var handleChangeTipeBayar = function(){

        var $tipe = $('input[name$="[tipe]"]', $('#table_bayar'));

        $.each($tipe, function(idx, tipe){
            index = '';
            tipe = '';
            if ($(this).prop("checked") === true){
                index = $(this).data('index');
                tipe = $(this).data('text');

                $('th#th_jenis_bayar_'+index).text(tipe);
            }


        });

        $('input[name$="[tipe]"]', $('#table_bayar')).on('change', function(){

            var id = $(this).val();
            var $tableInvoice = $('#table_bayar'),
                rowId         = $(this).closest('tr').prop('id'),
                index         = $(this).data('index'),
                tipe          = $(this).data('text'),
                $row          = $('#'+rowId, $tableInvoice); 
             

            if(id == 1){
                $('div#pilih_trf',$row).addClass('hidden');
                $('div#pilih_giro',$row).addClass('hidden');
                $('div#pilih_cek',$row).removeClass('hidden');
                $('th#th_jenis_bayar_'+index).text(tipe);
            }
            if(id == 2){
                $('div#pilih_trf',$row).addClass('hidden');
                $('div#pilih_giro',$row).removeClass('hidden');
                $('div#pilih_cek',$row).addClass('hidden');
                $('th#th_jenis_bayar_'+index).text(tipe);
            }
            if(id == 3){
                $('div#pilih_trf',$row).removeClass('hidden');
                $('div#pilih_giro',$row).addClass('hidden');
                $('div#pilih_cek',$row).addClass('hidden');
                $('th#th_jenis_bayar_'+index).text(tipe);
            }
        });



        $nominal = $('input[name$="[nominal]"]', $('#table_bayar'));
        $total_invoice = $('input[name$="[total_invoice]"]', $('#table_bayar'));

        $nominal.on('change', function(){
            var $tableInvoice = $('#table_bayar'),
                rowId         = $(this).closest('tr').prop('id'),
                $row          = $('#'+rowId, $tableInvoice); 


            total_biaya = 0;
            $.each($nominal, function(idx, nominal){
                biaya = $(this).val();
                if(biaya == ''){
                    biaya = 0;
                }
                total_biaya = total_biaya + parseInt(biaya);
            });

            grand_total = 0;
            $.each($total_invoice, function(idx, total_invoice){
                invoice = $(this).val();

                if(invoice == ''){
                    invoice = 0;
                }
                grand_total = grand_total + parseInt(invoice);
            });


            $('th#biaya_tambahan', $('#table_bayar')).text(mb.formatRp(total_biaya));
            $('th#th_total_bayar', $('#table_bayar')).text(mb.formatRp(total_biaya + grand_total));
            $('input#total_bayar', $('#table_bayar')).val(total_biaya + grand_total);
            $('input#total_bayar', $('#table_bayar')).attr('value', total_biaya + grand_total);

            var nominal = total_biaya + grand_total;
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
                    
                  $('th#th_terbilang').text('Terbilang: '+data.terbilang);
                
                },
                complete : function(){
                  Metronic.unblockUI();
                }
            });

        });

        $.each($total_invoice, function(idx, total_invoice){
            invoice = parseInt($(this).val());

            if(invoice == ''){
                invoice = 0;
            }
            
            $('th#th_total_cek_bayar_'+idx).text(mb.formatRp(invoice));
        });

        $('input[name$="[bank_no_cek]"]',  $('#table_bayar')).on('change', function(){
         var $tableInvoice = $('#table_bayar'),
             rowId         = $(this).closest('tr').prop('id'),
             $row          = $('#'+rowId, $tableInvoice),
             index         = $(this).data('index');

             no_cek = $('input[name$="[bank_no_cek]"]',  $row).val();

             $('th#th_no_cek_bayar_'+index).text(no_cek);
        });

        $('input[name$="[bank_no_giro]"]',  $('#table_bayar')).on('change', function(){
         var $tableInvoice = $('#table_bayar'),
             rowId         = $(this).closest('tr').prop('id'),
             $row          = $('#'+rowId, $tableInvoice),
             index         = $(this).data('index');

             no_giro = $('input[name$="[bank_no_giro]"]',  $row).val();

             $('th#th_no_cek_bayar_'+index).text(no_giro);
        });

        $('input[name$="[bank_supp_nomor]"]',  $('#table_bayar')).on('change', function(){
         var $tableInvoice = $('#table_bayar'),
             rowId         = $(this).closest('tr').prop('id'),
             $row          = $('#'+rowId, $tableInvoice),
             index         = $(this).data('index');

             no_transfer = $('input[name$="[bank_supp_nomor]"]',  $row).val();

             $('th#th_no_cek_bayar_'+index).text(no_transfer);
        });
    }

    var handleBankSelect = function(){
        $selectBank = $('select[name$="[bank_id]"]', $('#table_bayar'));

        $.each($selectBank, function(idx, selectBank){
            var index         = $(this).data('index');
            var bank          = $(this).find(":selected").text();

            $('th#th_bank_bayar_'+index).text(bank);

        });

        $selectBank.on('change', function(){
            var $tableInvoice = $('#table_bayar'),
                rowId         = $(this).closest('tr').prop('id'),
                $row          = $('#'+rowId, $tableInvoice),
                index         = $(this).data('index');

            var bank = $('select[name$="[bank_id]"] option:selected', $row).text();
                $('th#th_bank_bayar_'+index).text(bank);
        });
    }

    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true,
                format : 'dd M yyyy'
            });

            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true,
                format : 'dd M yyyy'
            });
        }
    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pembayaran_transaksi/';
        handleValidation();
        initForm();
    };

}(mb.app.proses));

$(function(){    
    mb.app.proses.init();
});