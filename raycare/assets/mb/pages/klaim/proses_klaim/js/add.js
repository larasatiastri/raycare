mb.app.proses_klaim = mb.app.proses_klaim || {};
mb.app.proses_klaim.add = mb.app.proses_klaim.add || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_add_proses_klaim');

    var initForm = function(){

        var bulan = $('input#periode_tindakan').val();
        
        handleGetJumlahTindakan(bulan);
    };

    var handleGetJumlahTindakan = function(bulan) {
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_jumlah_tindakan',
            data     : {bulan: bulan},
            dataType : 'json',
            beforeSend : function() {
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
            },
            success  : function( results ) {
                if(results.success == true)
                {
                    $('input#jumlah_tindakan').val(results.count);
                    $('input#jumlah_tarif_riil').val(results.tarif_rs);
                    $('input#jumlah_tarif_ina').val(results.tarif_ina);
                }
                else
                {
                    mb.showMessage('error','Tindakan di bulan terpilih tidak tersedia','Informasi');
                }
            },
            complete : function() {
                 Metronic.unblockUI();
            }
        });
    };

    var handleGetJumlahTindakanManual = function(jumlah) {
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_jumlah_tindakan_manual',
            data     : {jumlah: jumlah},
            dataType : 'json',
            beforeSend : function() {
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
            },
            success  : function( results ) {
                if(results.success == true)
                {
                    $('input#jumlah_tindakan').val(results.count);
                    $('input#jumlah_tarif_riil').val(results.tarif_rs);
                    $('input#jumlah_tarif_ina').val(results.tarif_ina);
                }
            },
            complete : function() {
                 Metronic.unblockUI();
            }
        });
    };

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('#date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                autoclose: true
            });
            $('#month', $form).datepicker({
                rtl: Metronic.isRTL(),
                format: "MM yyyy",
                minViewMode: "months",
                autoclose: true
            }).on('changeDate', function(ev) {
                var bulan = $('input#periode_tindakan').val();
        
                handleGetJumlahTindakan(bulan);
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
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

	var handleConfirmSave = function(){
		$('a#confirm_save', $form).click(function() {
			if (! $form.valid()) return;

			var msg = $(this).data('confirm');
		    bootbox.confirm(msg, function(result) {
		        if (result==true) {
		            $('#save', $form).click();
		        }
		    });
		});
	};

    var handleChangeJmlTindakan = function(){
        $('input#jumlah_tindakan', $form).on('change keyup', function(){
            var jumlah = $(this).val();
            handleGetJumlahTindakanManual(jumlah);
        });
    };
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klaim/proses_klaim/';
        handleValidation();
        handleConfirmSave();
        handleDatePickers();
        handleChangeJmlTindakan();
        initForm();
    };
 }(mb.app.proses_klaim.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.proses_klaim.add.init();
});