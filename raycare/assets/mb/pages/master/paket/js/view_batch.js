mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl              = '',
        $form                   = $('#form_addtemplate'),
        $tableAddAccount        = $('#table_add_account', $form),
        $tableAddAccountTitipan = $('#table_add_account_titipan', $form),
        $tableAccountSearch     = $('#table_account_search'),
        $tablePaketItem         = $('#table_paket_item'),
        $tablePaketTindakan     = $('#table_paket_tindakan'),
        $tableInformation       = $('#table_information'),
        $popoverItemContent     = $('#popover_item_content'),
        $popoverItemContentTindakan     = $('#popover_item_content_tindakan'),
        $lastPopoverItem        = null,
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        tplItemAccRow           = $.validator.format( $('#tpl_item_acc_row').text() ),
        itemCounter             = 9,
        totalBayar              = 0
        ;

       var initForm = function(){
      

        var 
            $btnSearchAccount        = $('.search-account', $tableAddAccount),
            $btnSearchAccountTitipan  = $('.search-account-titipan', $tableAddAccountTitipan),
            $btnDeletes              = $('.del-this', $tableAddAccount);
            $btnDeletestitipan       = $('.del-this-plus', $tableAddAccountTitipan);

        handleBtnSearchAccount($btnSearchAccount);  

        handleBtnSearchAccountTitipan($btnSearchAccountTitipan);  


        
        // handle delete btn
        $.each($btnDeletes, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        $.each($btnDeletestitipan, function(idx, btn){
            handleBtnDeleteTitipan( $(btn) );
        });

        // tambah 1 row kosong pertama
        addItemAccRow();
        addItemRow();

        // $('.row_plus', $tableAddAccount).hide();
        // $('.row_plus', $tableAddAccountTitipan).hide();

        // $popoverItemContent.hide();

        ////////////////////////////////////

        $('input[name$="[jumlah]"]', $tableAddAccount).on('keyup', function(){
            calculateTotal();
        });

        $('input[name$="[jumlah]"]', $tableAddAccount).on('change', function(){
            calculateTotal();
        });

        calculateTotal();

        //////////////////////////////////////////////////////////////////////////////////

        $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTitipan).on('keyup', function(){
            calculateTotalTitipan();
        });

        $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTitipan).on('change', function(){
            calculateTotalTitipan();
        });

        calculateTotalTitipan();

        //////////////////////////////////////////////////////////////////////////////
        
        $('input[name$="[biaya_tambahan]"]', $form).on('keyup', function(){
            calculateTotalKeseluruhan();
        });

        $('input[name$="[biaya_tambahan]"]', $form).on('change', function(){
            calculateTotalKeseluruhan();
        });

        calculateTotalKeseluruhan();


    };

    var calculateTotalKeseluruhan = function(){

        // var nominal = $('input[name$="[nominal]"]');

            // var totalBayar = 0;
            $('input#biaya_tambahan').on('change', function(){
            // alert('klik');
                
                var cash = parseInt($(this).val());
                var bayar = parseInt($('input#total_bayar_hidden').val());
                var tindakan = parseInt($('input#total_tindakan_hidden').val());

                    // alert(cash);

                totalBayar = bayar + tindakan + cash;
                
                $('input#total_keseluruhan').val(mb.formatRp(totalBayar));
                $('input#total_keseluruhan_hidden').val(totalBayar);

                if (!isNaN(totalBayar)){

                $('input#total_keseluruhan').val(mb.formatRp(totalBayar));
                $('input#total_keseluruhan_hidden').val(totalBayar);
                
                } else {

                $('input#total_keseluruhan').val(0);
                $('input#total_keseluruhan_hidden').val(0);

                }

                // var cash = parseInt($(this).val());
                // alert(cash);
                // alert('oii');

            });
    }


    var calculateTotal = function(){
        // alert('masuk function');
        var 
            $rows     = $('tbody>tr', $tableAddAccount), 
            totalCost = 0,
            total_alat_obat = 0;

        $.each($rows, function(idx, row){
            var 
                $row     = $(row), 
                itemCode = $('label[name$="[kode]"]', $row).text(),
                harga = $('input[name$="[harga]"]', $row).val(),
                jumlah     = $('input[name$="[jumlah]"]', $row).val()*1
                ;
                // alert(itemCode);

            if (itemCode != '' ){
                totalCost = harga*jumlah;
                
                $('label[name$="[sub_total]"]', $row).text(mb.formatRp(totalCost));
                $('input[name$="[sub_total]"]', $row).val(totalCost);

                 total_alat_obat = total_alat_obat + totalCost;
                $('input#total_bayar').val(mb.formatRp(total_alat_obat));
                $('input#total_bayar_hidden').val(total_alat_obat);

            }
            // alert(totalCost);

        });

        // $('#total_before_discount_hidden').val(totalCost);
    };
    
    var calculateTotalTitipan = function(){
        // alert('masuk function');
        var 
            $rows     = $('tbody>tr', $tableAddAccountTitipan), 
            totalCost = 0,
            total_tindakan = 0;

        $.each($rows, function(idx, row){
            var 
                $row     = $(row), 
                itemCode = $('label[name$="[kode_titipan]"]', $row).text(),
                harga    = $('input[name$="[harga_tindakan]"]', $row).val(),
                jumlah   = $('input[name$="[jumlah_tindakan]"]', $row).val()*1
                ;
                // alert(itemCode);

            if (itemCode != 0 ){
                // totalCost += harga*jumlah;
                totalCost = harga*jumlah;

                // alert(totalCost);
                
                $('label[name$="[sub_total_titipan]"]', $row).text(mb.formatRp(totalCost));
                $('input[name$="[sub_total_tindakan]"]', $row).val(totalCost);
            
                total_tindakan = total_tindakan + totalCost;
                $('input#total_tindakan').val(mb.formatRp(total_tindakan));
                $('input#total_tindakan_hidden').val(total_tindakan);


            }
            // alert(totalCost);

        });

        // $('#total_before_discount_hidden').val(totalCost);
    };

    var addItemRow = function(){

        var numRow = $('tbody tr', $tableAddAccount).length;
        var 
            $rowContainer         = $('tbody', $tableAddAccount),
            $newItemRow           = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchAccount  = $('.search-account', $newItemRow)
            ;

        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
      
        // handle button search item
        handleBtnSearchAccount($btnSearchAccount);

        //

        $('input[name$="[jumlah]"]', $tableAddAccount).on('keyup', function(){
            calculateTotal();
            // calculateTotalAdisc();
        });

        $('input[name$="[jumlah]"]', $tableAddAccount).on('change', function(){
            calculateTotal();
            // calculateTotalAdisc();
        });

         $('input[name$="[biaya_tambahan]"]', $form).on('keyup', function(){
            calculateTotalKeseluruhan();
        });

        $('input[name$="[biaya_tambahan]"]', $form).on('change', function(){
            calculateTotalKeseluruhan();
        });

        // $('.type', $newItemRow).bootstrapSwitch();
        // handleBootstrapSelectType($('.type', $newItemRow));
    };

    var addItemAccRow = function(){

        var numRow = $('tbody tr', $tableAddAccountTitipan).length;
        var 
            $rowContainer         = $('tbody', $tableAddAccountTitipan),
            $newItemRow           = $(tplItemAccRow(itemCounter++)).appendTo( $rowContainer ),
            // $btnAddAccount  = $('.add_row', $newItemRow),
            $btnSearchAccountTitipan  = $('.search-account-titipan', $newItemRow)

            ;

        // handle delete btn
        handleBtnDeleteTitipan( $('.del-this-plus', $newItemRow) );
      

        // handle button search item
        // handleAddAcc($btnAddAccount);

        handleBtnSearchAccountTitipan($btnSearchAccountTitipan);

        ///////////////////////

         $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTitipan).on('keyup', function(){
            calculateTotalTitipan();
        });

        $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTitipan).on('change', function(){
            calculateTotalTitipan();
        });

         $('input[name$="[biaya_tambahan]"]', $form).on('keyup', function(){
            calculateTotalKeseluruhan();
        });

        $('input[name$="[biaya_tambahan]"]', $form).on('change', function(){
            calculateTotalKeseluruhan();
        });


        // $('.type', $newItemRow).bootstrapSwitch();
        // handleBootstrapSelectType($('.type', $newItemRow));

    };
     
     var handleBtnSearchSales = function($btn){
    //     var rowId  = $btn.closest('tr').prop('id');
    //     // console.log(rowId);

    //     $btn.popover({ 
    //         html : true,
    //         container : '.page-content',
    //         placement : 'right',
    //         content: '<input type="hidden" name="rowItemId"/>'

    //     }).on("show.bs.popover", function(){

    //         var $popContainer = $(this).data('bs.popover').tip();

    //         $popContainer.css({minWidth: '720px', maxWidth: '720px'});

    //         if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

    //         $lastPopoverItem = $btn;

    //         $popoverItemContent.show();

    //     }).on('shown.bs.popover', function(){

    //         var 
    //             $popContainer = $(this).data('bs.popover').tip(),
    //             $popcontent   = $popContainer.find('.popover-content')
    //             ;

    //         // record rowId di popcontent
    //         $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
    //         // pindahkan $popoverItemContent ke .popover-conter
    //         $popContainer.find('.popover-content').append($popoverItemContent);

    //     }).on('hide.bs.popover', function(){
    //         //pindahkan kembali $popoverItemContent ke .page-content
    //         $popoverItemContent.hide();
    //         $popoverItemContent.appendTo($('.page-content'));

    //         $lastPopoverItem = null;

    //     }).on('hidden.bs.popover', function(){
    //         // console.log('hidden.bs.popover')
    //     }).on('click', function(e){
    //         e.preventDefault();
    //     });
    };

    var handleDataTableItems = function(){
        paket_batch_id = $('input#paket_batch_id').val();
       oTable = $tablePaketItem.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            // 'sAjaxSource'              : baseAppUrl + 'listing_alat_obat',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket_item_batch_view/' + paket_batch_id,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                ]
        });       
        $('#table_paket_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_paket_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

                $tablePaketItem.on('draw.dt', function (){
                    
                });
            
    };

    var handleDataTableItemsTitipan = function(){
        paket_batch_id = $('input#paket_batch_id').val();
        oTable2 = $tablePaketTindakan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket_tindakan_batch_view/' + paket_batch_id,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                ]
        });       
        $('#table_paket_tindakan_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_paket_tindakan_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tablePaketTindakan.on('draw.dt', function (){
            
        });

    };

    jQuery('#table_paket_item .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
            } else {
                $(this).attr("checked", false);
            }                    
        });
        jQuery.uniform.update(set);
    });

     jQuery('#table_paket_tindakan .group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
            } else {
                $(this).attr("checked", false);
            }                    
        });
        jQuery.uniform.update(set);
    });

    var handleAccountSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableAddAccount),
                $itemCodeEl = null,
                $itemNameEl = null, 
                $itemHargaEl = null,
                $itemQtyEl  = $('input[name$="[name]"]', $row)
                ;                
            // console.log(itemTarget);
           
                $itemIdEl       = $('input[name$="[account_id]"]', $row);
                $itemCodeEl     = $('label[name$="[kode]"]', $row);
                $itemCodeIn     = $('input[name$="[kode]"]', $row);
                $itemNameEl     = $('label[name$="[nama]"]', $row);
                $itemNameIn     = $('input[name$="[nama]"]', $row);
                $itemHargaEl    = $('label[name$="[harga]"]', $row);
                $itemHargaIn    = $('input[name$="[harga]"]', $row);
                $('.search-account', $tableAddAccount).popover('hide');
           
            $itemIdEl.val($(this).data('item')['id']);
            $itemCodeEl.text($(this).data('item')['kode']);
            $itemCodeIn.val($(this).data('item')['kode']);
            $itemNameEl.text($(this).data('item')['nama']);
            $itemNameIn.val($(this).data('item')['nama']);
            $itemHargaEl .text(mb.formatRp(parseInt($(this).data('item')['harga'])));
            $itemHargaIn .val($(this).data('item')['harga']);
            
            addItemRow();
            calculateTotal();
            e.preventDefault();   
        });     
    };

    var handleAccountTitipanSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');SS
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableAddAccountTitipan),
                $itemKodeEl = null,
                $itemNamaEl = null, 
                $itemHargaEl = null, 
                $itemSubTotalEl = null 
                // $itemQtyEl  = $('input[name$="[name]"]', $row)
                ;                
            // console.log(itemTarget);
           
                $itemidEl = $('input[name$="[tindakan_id]"]', $row);
                $itemKodeEl = $('label[name$="[kode_titipan]"]', $row);
                $itemNamaEl = $('label[name$="[nama_titipan]"]', $row);
                $itemHargaEl = $('label[name$="[harga_titipan]"]', $row);
                $itemHargaIn = $('input[name$="[harga_tindakan]"]', $row);
                $('.search-account-titipan', $tableAddAccountTitipan).popover('hide');
           
            $itemidEl.val($(this).data('item')['id']);
            $itemKodeEl.text($(this).data('item')['kode']);
            $itemNamaEl.text($(this).data('item')['nama']);
            $itemHargaEl.text(mb.formatRp(parseInt($(this).data('item')['harga'])));
            $itemHargaIn.val($(this).data('item')['harga']);
            
            addItemAccRow();
            calculateTotalTitipan();
            e.preventDefault();   
        });     
    };


    var handleBtnDelete = function($btn){
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableAddAccount)

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableAddAccount).length == 0){
                addItemRow();
                // addItemAccRow();
            }
            e.preventDefault();
        });

        
    };

    var handleBtnDeleteTitipan = function($btn){
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableAddAccountTitipan)

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableAddAccountTitipan).length == 0){
                // addItemRow();
                addItemAccRow();
            }
            e.preventDefault();
        });

        
    };

   

    var handleBtnSearchAccount = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            $popoverItemContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleBtnSearchAccountTitipan = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            $popoverItemContentTindakan.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContentTindakan ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContentTindakan);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverItemContentTindakan ke .page-content
            $popoverItemContentTindakan.hide();
            $popoverItemContentTindakan.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    // var handleItemSearchSelect = function($btn){
    //     $btn.on('click', function(e){
    //         alert('di klik');
    //         var 
    //             $parentPop  = $(this).parents('.popover').eq(0),
    //             rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
    //             $row        = $('#'+rowId, $tableAddAccount),
    //             $itemCodeEl = null,
    //             $itemNameEl = null
    //            ;                
    //         // console.log(itemTarget);
    //             $itemidEl = $('input[name$="[account_id]"]', $row);
    //             $itemCodeEl = $('input[name$="[code]"]', $row);
    //             $itemNameEl = $('input[name$="[name]"]', $row);
    //             $('.search-account', $tableAddAccount).popover('hide');
               
           
            
    //           $itemidEl.val($(this).data('item')['id']);
    //         $itemCodeEl.val($(this).data('item')['account_no']);
    //         $itemNameEl.val($(this).data('item')['name']);
    //           addItemRow();

    //         e.preventDefault();   
    //     });     
    // };

    // var handleDTItems = function(){
    //     $tableAccountSearch.dataTable({
    //         "aoColumnDefs": [
    //             { "aTargets": [ 0 ] }
    //         ],
    //         "aaSorting": [[1, 'asc']],
    //          "aLengthMenu": [
    //             [5, 10, 15, 20, -1],
    //             [5, 10, 15, 20, "All"] // change per page values here
    //         ],
    //         // set the initial value
    //         "iDisplayLength": 5,
    //     });       
    //     $('#table_account_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
    //     $('#table_account_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

    //     var $btnSelects = $('a.select', $tableAccountSearch);
    //     handleItemSearchSelect( $btnSelects );

    //     $tableAccountSearch.on('draw.dt', function (){
    //         var $btnSelect = $('a.select', this);
    //         handleItemSearchSelect( $btnSelect );
            
    //     } );

    //     $popoverItemContent.hide();   
             
    // };

    var handleBootstrapSelect = function($btn,name) {
        $btn.on('change', function(){

             var 
                rowId = parseInt(itemCounter-1),
                rowPlusId = parseInt(itemCounter-2) || parseInt(itemCounter-3) || parseInt(itemCounter-4) || parseInt(itemCounter-5),
                $row     = $('#item_row_'+rowId, $tableAddAccount),
                $rowPlus     = $('.row_plus', $tableAddAccount);
        
            if($(this).prop('checked'))
            {
                // var name = $(this).data('name');
                // alert(name);
                $rowPlus.show();
                $('input[name$="[name]"]', $rowPlus).val(name);
                $('input[name$="[account_type]"]',$rowPlus).val(1);
                $('input[name$="[name]"]',$rowPlus).attr('readonly','readonly');
            }
            
            else{
                $('input[name$="[name]"]', $rowPlus).val('');
                $('input[name$="[account_type]"]',$rowPlus).val('');
                $('input[name$="[name]"]',$rowPlus).attr('readonly','readonly');
                $rowPlus.hide();
            }
           
        });

        
    };

    var handleBootstrapSelectType = function($selector)
    {
        $selector.on('switchChange.bootstrapSwitch', function (event, state) {
            console.log($(this).parent().parent().prop('class'));
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

 

    var handleDatePickers = function () {
        var time = new Date($('#waktu_selesai').val());
        if (jQuery().datetimepicker) {
            $('.date', $form).datetimepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy hh:ii:ss',
                autoclose: true,
                update : time

            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

var handleCheckBoxChange = function(){
    $('input#acc_payable').on('change', function()
    {
        if ($('input#acc_payable').prop('checked')) {
            // alert('CEKBOX DI KLIK checked');
             $('input[name$="[name]"]').val('Akun Hutang Supplier Bersangkutan');
             addItemRow();
        }else{
            alert('CEKBOX DI KLIK UNchecked');
        }
    }
)};
   
    

 var handleDropdownTypeChange = function()
    {
        $('#tipe_transaksi').on('change', function(){
            var tipeId = this.value;
            var $type_t = $('label#type_t');
            
            if(tipeId == 5 )
            {
                $('div.section-1').addClass('hidden');
            }
            else
            {
                var $check = $('input#acc_payable');

                $('div.section-1').removeClass('hidden');
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_account_type',
                    data     : {tipeId: tipeId},
                    dataType : 'json',
                    success  : function( results ) {
                        var name = results[0]["nama"];
                        $type_t.text(results[0]["subjek"]);
                        // $check.removeProp('checked');
                        $check.attr('data-name',results[0]["nama"]);

                    handleBootstrapSelect($check,name);

                    }
                });
                
            }


            //    oTable.fnSettings().sAjaxSource = baseAppUrl + 'listing_transaction_detail/' + tipeId;
            // oTable.fnClearTable(); 
        });
    }

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

    var handleSelectCabang = function(){
        $('select#tipe_transaksi').on('change', function(){
            var id = $(this).val();
            
            oTable.api().ajax.url(baseAppUrl + 'listing_alat_obat/' + id).load();
         
            
        });
    }



    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/paket/';
        handleValidation();
        calculateTotalKeseluruhan();
        initForm();
        handleSelectCabang();
        handleDatePickers();
        // handleBootstrapSelect();
        handleConfirmSave();
        // handleDTItems();
        handleDataTableItems();
        handleDataTableItemsTitipan();
        // handleDropdownTypeChange();
 
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});