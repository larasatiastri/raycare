mb.app.set_user_level = mb.app.set_user_level || {};

// mb.app.users.add namespace
(function(o){

    var $form = $('#form_set_user_level');
    //untuk dropdown pemilihan bahasa agar muncul bendera setiap negara
	

	var handleMultiSelect = function () {
        $('#user_level_id', $form).multiSelect();   
    };

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;
            
            var i = 0;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                Metronic.blockUI({boxed: true });
                if (result==true) {
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form).attr('disabled','disabled');
                    if(i === 1)
                    {
                      $('#save', $form).click();
                    }
                }
            });
        });
    };


    o.init = function(){
        baseAppUrl = mb.baseUrl()+'pengaturan/fitur_user_level/';
        handleMultiSelect();
        handleConfirmSave();
    };

}(mb.app.set_user_level));


// initialize  mb.app.users.add
$(function(){
	mb.app.set_user_level.init();
});