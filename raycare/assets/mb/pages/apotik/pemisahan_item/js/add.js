mb.app.cabang = mb.app.cabang || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form = $('#form_pemisahan_item'),
        $tableKonversi = $('#table_konversi'),
        tplKonversiRow  = $.validator.format( $('#tpl_item_row_konversi').text()),
        itemCounter        = 1;

    var initform = function()
    {
        var $btnaddRow = $('a#addRow');
        handleAddRow($btnaddRow);
        addKonversiRow();
         // $('tbody tr#item_row_1', $tableKonversi).hide();

        var $btninfo = ('a.info', $tableKonversi);
        handleDataInfo($btninfo);
    }
    
    var addKonversiRow = function(){

        var numRow = $('tbody tr', $tableKonversi).length;
        console.log('numrow ' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        // if( numRow > 0 && ! isValidLastRow() ) return;

        $('input#itemRow').val(itemCounter);
        var 
            $rowContainer         = $('tbody', $tableKonversi),
            $newItemRow           = $(tplKonversiRow(itemCounter++)).appendTo( $rowContainer ),

            // $btnSearchItem        = $('.search-item', $newItemRow)
            // $inputNumber          = $('input[name$="[qty]"], input[name$="[cost]"]', $newItemRow)
            // handle delete btn
            $btnInfoItem = $('a.info', $newItemRow);

            console.log($rowContainer);
            // alert($rowContainer);
    };

    var handleAddRow = function($btn)
    {
        $btn.on('click', function(){
            addKonversiRow();
        })
    }

    var handleDataInfo = function($btn){
        $btn.on('click', function(){
            var info = $(this).data('info');
            // $('input.jumlah_item').attr();
            // alert($(this).data('info'));
            // $('input#item').val($('input[name$="[item_sebelum_satuan]"]').val());
            // var $tes = $('input[name$="[item_sebelum_satuan]"]').val();
            // alert($('input#items_sebelum_satuan_'+ info).val());
        })
    }

	var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "right",
                autoclose: true,
                format:"dd M yyyy"
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }

        /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
    }


    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            // if (! $form.valid()) return;

            var i = 0;
            var msg = $(this).data('confirm');
            var proses = $(this).data('proses');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    Metronic.blockUI({boxed: true, message: proses});
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

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/pemisahan_item/';
        handleDatePickers();
        initform();
        handleConfirmSave();
    };
 }(mb.app.cabang));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.init();
});