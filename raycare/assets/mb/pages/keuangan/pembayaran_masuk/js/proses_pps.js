mb.app.pembayaran_masuk = mb.app.pembayaran_masuk || {};
mb.app.pembayaran_masuk.proses_pps = mb.app.pembayaran_masuk.proses_pps || {};
(function(o){

    var 
        baseAppUrl                 = '',
        $form                      = $('#form_proses_pps'),
        $lastPopoverItemBayar      = null;

    var initform = function()
    {
        handleDatePicker();      
    }

    var handleDatePicker = function()
    {
         if (jQuery().datepicker) {
            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true
            });
        }
    }

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            
            if (! $form.valid()) return;
            
            var i = 0;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                Metronic.blockUI({boxed: true, message: "Sedang diproses..."});
                if (result==true) {
                    i = parseInt(i) + 1;
                    $('a#confirm_save', $form).attr('disabled','disabled');
                    if(i === 1)
                    {
                      $('#save', $form).click();
                    }
                }else{
                    Metronic.unblockUI();
                }
            });
            
        });
    };

    var handleFancybox = function() {
        if (!jQuery.fancybox) {
            return;
        }

        if ($(".fancybox-button").size() > 0) {
            $(".fancybox-button").fancybox({
                groupAttr: 'data-rel',
                prevEffect: 'none',
                nextEffect: 'none',
                closeBtn: true,
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            });
        }
    };

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pembayaran_masuk/';
        initform();
        handleConfirmSave();
        handleFancybox();
    };
 }(mb.app.pembayaran_masuk.proses_pps));


// initialize  mb.app.home.table
$(function(){
    mb.app.pembayaran_masuk.proses_pps.init();
});