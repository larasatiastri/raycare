mb.app.gudang = mb.app.gudang || {};
(function(o){

    var 
        baseAppUrl                 = '',
        $form                      = $('#form_barang_datang_farmasi')
        $tableJumlahPesan          = $('#table_jumlah_pesan'),
        $tablePembelianDetail      = $('#table_pembelian_detail'),
        $popoverJumlahPesanContent = $('#popover_jumlah_pesan'), 
        tplPembelian               = $.validator.format( $('#tpl_pembelian_row').text()),
        pembelianCounter           = 1,
        $lastPopoverJumlahPesan    = null;

    var initform = function()
    {
            // addPembelianRow();
        
    }

    // var addPembelianRow = function(){
        
    //     var numRow = $('tbody tr', $tablePembelianDetail).length;

    //     console.log('numrow' + numRow);

    //     // if (numRow > 0 && ! isValidLastRow()) return;

    //     var 
    //         $rowContainer      = $('tbody', $tablePembelianDetail),
    //         $newItemRow        = $(tplPembelian(pembelianCounter++)).appendTo( $rowContainer ),
    //         // $newGetItemRow  = $(tplGetItemRow(itemCounter++)).appendTo( $rowContainer ),
    //         $btnSearchItem     = $('.search-item', $newItemRow);

    //     // handle delete btn
    //     // handleBtnDelete( $('.del-this', $newItemRow));
        
    //     // handle button search item
    //     // handleBtnSearchItem($btnSearchItem);
    // };

    var handleDatePicker = function()
    {
         if (jQuery().datepicker) {
            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd MM yyyy',
                autoclose: true
            })
            // $('body').removeClass("modal-open");
        }
    }


    

    var handleBtnJumlahPesan = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // alert($btn.data('id'));

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverJumlahPesan != null) $lastPopoverJumlahPesan.popover('hide');

            $lastPopoverJumlahPesan = $btn;

            $popoverJumlahPesanContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverJumlahPesanContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverJumlahPesanContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverJumlahPesanContent.hide();
            $popoverJumlahPesanContent.appendTo($('.page-content'));

            $lastPopoverJumlahPesan = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleJumlahMasuk = function(){
        $('input[name$="[jumlah_masuk]"]').on('change keyup', function(){
            // alert($(this).data('id'));
            var id = $(this).data('id');
            $('input#jumlah_masuk_' + id).val($(this).val());
            $('input#jumlah_masuk_' + id).attr('value',$(this).val());

            $('input#jumlah_masuk_awal_' + id).val($(this).val());
            $('input#jumlah_masuk_awal_' + id).attr('value',$(this).val());

            row_id = 'item_row_'+id;
            $row        = $('tr#'+row_id, $('#table_pembelian_detail'));

            $('a.pemisahan_item', $row).attr('href', baseAppUrl + 'modal_pemisahan_item/' + $('input#item_id_'+id).val() + '/' + $('input#item_satuan_id_'+id).val() + '/' + $(this).val() + '/item_row_' +id);

        });
    }

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;
            var i = 0;
            var msg = $(this).data('confirm');
            var proses = $(this).data('proses');
            bootbox.confirm(msg, function(result) {
                Metronic.blockUI({boxed: true, message: proses});
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
        baseAppUrl = mb.baseUrl() + 'gudang/barang_datang_farmasi/';
        // handleDataTableInfoItem();
        // handleDataTableHistoryPecah();
        initform();
        handleJumlahMasuk();
        handleDatePicker();
        handleConfirmSave();
        handleFancybox();

    };
 }(mb.app.gudang));


// initialize  mb.app.home.table
$(function(){
    mb.app.gudang.init();
});