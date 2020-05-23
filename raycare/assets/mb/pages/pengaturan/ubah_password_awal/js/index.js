mb.app.ubah_password_awal = mb.app.ubah_password_awal || {};

// mb.app.home.ubah_password_awal namespace
(function(o){

    var $form = $('#form_ubah_password_awal');
    var baseAppUrl = '';

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
			var success = handleCheckOldPassword();
			
			//console.log(success);
			
		});
	};



	

	var handleCheckOldPassword = function(){
		$.ajax({
		  url: baseAppUrl + "validate_password",
		  type : 'POST',
		  data : {old_password : $('#old_password', $form).val() },
		  dataType : 'json'
		  // context: document.body
		}).done(function( data ) {
			// console.log(data);
			
			if(data.success === true) {

				var msg = $('a#confirm_save', $form).data('confirm');
		   		bootbox.confirm(msg, function(result) {
			        if (result==true) {
			            $('#save', $form).click();
			        }
		    	});
		    	return true;
			}else if(data.success === false){
				bootbox.alert("old password is wrong");
				$('.help-block', $form).parent().addClass( "has-error" );
				return false;				
			}
			

		});
		
	}

	var handleMatchPassword = function(){
		if($('#password').val() != $('#rpassword').val())
		{
			
			return false;
		}
		return true;
	}

    o.init = function(){
    	baseAppUrl = mb.baseUrl() + "pengaturan/ubah_password_awal/";
    	handleValidation();
    	handleConfirmSave();

    };

}(mb.app.ubah_password_awal));


// initialize  mb.app.ubah_password_awal
$(function(){
	mb.app.ubah_password_awal.init();
});