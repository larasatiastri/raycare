mb.app.proses_klaim = mb.app.proses_klaim || {};
mb.app.proses_klaim.buat_kwitansi = mb.app.proses_klaim.buat_kwitansi || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_buat_kwitansi');

    var initForm = function(){

       
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

    var handleChangeTipeBayar = function(){
        $('select#tipe_bayar', $form).on('change', function(){
            var tipe = $(this).val();

            if(tipe == 1)
            {
                $('div#group_nomor').addClass('hidden');
                $('input#no_check_transfer').removeAttr('required');
            }
            if(tipe == 2)
            {
                $('div#group_nomor').removeClass('hidden');
                $('input#no_check_transfer').attr('required','required');
                $('label#label_nomor').innerHtml('No. Check : <span style="color:red;" class="required">*</span>');
            }
            if(tipe == 3)
            {
                $('div#group_nomor').removeClass('hidden');
                $('input#no_check_transfer').removeAttr('required');
                $('label#label_nomor').innerHtml('No. Transfer : ');
            }
        });
    };
    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klaim/proses_klaim/';
        handleValidation();
        handleConfirmSave();
        handleChangeTipeBayar();
        initForm();
    };
 }(mb.app.proses_klaim.buat_kwitansi));


// initialize  mb.app.home.table
$(function(){
    mb.app.proses_klaim.buat_kwitansi.init();
});