mb.app.kelompok_penjamin = mb.app.kelompok_penjamin || {};
mb.app.kelompok_penjamin.add = mb.app.kelompok_penjamin.add || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form = $('#form_add_kelompok_penjamin');

        $errorTop               = $('.alert-danger', $form),
        $successTop             = $('.alert-success', $form),
        theadFilterTemplate     = $('#thead-filter-template').text(),
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter             = 9
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

    var initForm = function(){
      
    };

     


    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/kelompok_penjamin/';
       
     //   alert('hi');
        handleValidation();
          initForm(); 
      //    handleDTItems();
        handleConfirmSave();
    };
 }(mb.app.kelompok_penjamin.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.kelompok_penjamin.add.init();
});