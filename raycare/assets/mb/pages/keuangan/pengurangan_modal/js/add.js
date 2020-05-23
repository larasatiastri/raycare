mb.app.pengurangan_modal = mb.app.pengurangan_modal || {};
mb.app.pengurangan_modal.add = mb.app.pengurangan_modal.add || {};

(function(o){

    var 
        baseAppUrl             = '',
        $form                  = $('#form_add_pengurangan_modal');


    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;
            var i = 0;
            var msg = $(this).data('confirm');
            var proses = $(this).data('proses');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                  Metronic.blockUI({boxed: true});
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form).attr('disabled','disabled');
                    if(i === 1)
                    {
                       $('button#save').click();
                    }
                }
            });
        });
    };

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                orientation: "left",
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var handleNominalChange = function(){
        $('input#nominal').on('change', function(){

            var nominal = $(this).val();

            if(nominal == ''){
                nominal = 0;
            }

            handleTerbilang(nominal);
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

// mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pengurangan_modal/';
        handleConfirmSave();
        handleDatePickers();
        handleNominalChange();
    };

            
}(mb.app.pengurangan_modal.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.pengurangan_modal.add.init();
});