
mb.app.proses_klaim = mb.app.proses_klaim || {};
mb.app.proses_klaim.proses = mb.app.proses_klaim.proses || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_add_proses_klaim');

    var initForm = function(){
        var admin = $('input#biaya_admin', $form).val();
        if($(this).val() == '')
        {
            admin = 0;
        }else{
            admin = $(this).val();
        }

        var jml_inacbg = $('input#jumlah_terima').val();
        var total_terima = parseInt(jml_inacbg - admin);

        $('label#label_jml_terima').text(mb.formatRp(total_terima));

        handleTerbilang(total_terima);
        
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
                $(element).closest('[class^="col"]').prosesClass('has-error');
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

    var handleChangeJmlAdmin = function(){
        $('input#biaya_admin', $form).on('keyup', function(){
            var admin = 0;
            if($(this).val() == '')
            {
                admin = 0;
            }else{
                admin = $(this).val();
            }

            var jml_inacbg = $('input#jumlah_terima').val();
            var total_terima = parseInt(jml_inacbg - admin);

            $('label#label_jml_terima').text(mb.formatRp(total_terima));

            handleTerbilang(total_terima);

        });
    };

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
                
              $('label#label_terbilang_jml_terima').text(data.terbilang);
            
            },
            complete : function(){
              Metronic.unblockUI();
            }
        });
    }

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
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    };
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/proses_pencairan_klaim/';
        handleValidation();
        handleConfirmSave();
        handleChangeJmlAdmin();
        handleDatePickers();
        initForm();
    };
 }(mb.app.proses_klaim.proses));


// initialize  mb.app.home.table
$(function(){
    mb.app.proses_klaim.proses.init();
});