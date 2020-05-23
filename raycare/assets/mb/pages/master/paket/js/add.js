mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl                  = '',
        $form                       = $('#form_addtemplate'),
        $tableAddAccount            = $('#table_add_account', $form),
        $tableAddAccountTitipan     = $('#table_add_account_titipan', $form),
        $tableAccountSearch         = $('#table_account_search'),
        $tableItemSearch            = $('#table_item_search'),
        $tableItemSearchTitipan     = $('#table_item_search_tindakan'),
        $tableInformation           = $('#table_information'),
        $popoverItemContent         = $('#popover_item_content'),
        $popoverItemContentTindakan = $('#popover_item_content_tindakan'),
        $lastPopoverItem            = null,
        tplItemRow                  = $.validator.format( $('#tpl_item_row').text() ),
        tplItemAccRow               = $.validator.format( $('#tpl_item_acc_row').text() ),
        itemCounter                 = 9,
        totalBayar                  = 0
    ;

    var initForm = function(){

        var 
            $btnSearchAccount        = $('.search-account', $tableAddAccount),
            $btnSearchAccountTitipan = $('.search-account-titipan', $tableAddAccountTitipan),
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


    var handleSelectCabang = function()
    {
        $('select#tipe_transaksi').on('change', function()
        {
            var id   = $(this).val(),
                poli = $('#poli').val('');

            var $poli = $('#poli');
            

            oTable.api().ajax.url(baseAppUrl + 'listing_alat_obat/' + id).load();
         
            ///////////////multi-select poliklinik////////////////////
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_cabang_id',
                data     : {id: id},
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    $poli.empty();
                   
                    $.each(results, function(key, value) {
                        $poli.append($("<option></option>")
                            .attr("value", value.poliklinik_id).text(value.nama));
                        $poli.val('');

                    });
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });
                
        });
    }

    var calculateTotalKeseluruhan = function(){


        $('input#biaya_tambahan').on('change', function(){
            
            var cash = parseInt($(this).val());
            var bayar = parseInt($('input#total_bayar_hidden').val());
            var tindakan = parseInt($('input#total_tindakan_hidden').val());

                // alert(cash);

            totalBayar = bayar + tindakan + cash;
            
            $('input#total_keseluruhan').val(mb.formatTanpaRp(totalBayar));
            $('input#total_keseluruhan_hidden').val(totalBayar);

            if (!isNaN(totalBayar)){

            $('input#total_keseluruhan').val(mb.formatTanpaRp(totalBayar));
            $('input#total_keseluruhan_hidden').val(totalBayar);
            
            } else {

            $('input#total_keseluruhan').val(0);
            $('input#total_keseluruhan_hidden').val(0);

            }

        });
    }


    var calculateTotal = function(){
        // alert('masuk function');
        var 
            $rows     = $('tbody>tr', $tableAddAccount), 
            totalCost = 0,
            total_alat_obat = 0
        ;

        $.each($rows, function(idx, row)
        {
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
                $('input#total_bayar').val(mb.formatTanpaRp(total_alat_obat));
                $('input#total_bayar_hidden').val(total_alat_obat);

            }

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
                $('input#total_tindakan').val(mb.formatTanpaRp(total_tindakan));
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


    var handleDataTableItems = function()
    {
       oTable = $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            // 'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_alat_obat//',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'item.kode', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
   
        $( '#select_so_history').on( 'change', function () {
            // alert($(this).val());
            //$tableCabangHistory.fnFilter( this.value, 4);
            var cabang_id = $('select#tipe_transaksi').val();
            // alert(cabang_id);
            oTable.api().ajax.url(baseAppUrl + 'listing_alat_obat/'+ cabang_id +'/'+$(this).val()).load();
          // alert($(this).val());
        });
            // oTable.api().ajax.url(baseAppUrl + 'listing_alat_obat/' + id).load();

                $tableItemSearch.on('draw.dt', function (){
                    $('.btn', this).tooltip();
                    
                    var $btnSelect = $('a.select', this);
                    
                    handleAccountSelect( $btnSelect );


                    
                });
            
            $popoverItemContent.hide();        

    };

    var handleDataTableItemsTitipan = function(){
        oTable2 = $tableItemSearchTitipan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_tindakan',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_item_search_titipan_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_search_titipan_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tableItemSearchTitipan.on('draw.dt', function (){
            var $btnSelect = $('a.select-tindakan', this);
            handleAccountTitipanSelect( $btnSelect );

            var grandtotal_tindakan =  $('input[name="total_tindakan"]', this).val();
            // var grandtotal_credit =  $('input[name="grandtotal_credit"]', this).val();

            $('input[name$="[jumlah_tindakan]"]', this).on('change keyup', function(){
                var total_tindakan = 0;
                var $subtotal_tindakan = $('label[name$="[sub_total_titipan]"]', $tableAddAccountTitipan);

                $.each($subtotal_tindakan, function(){
                    total_tindakan = subtotal_tindakan + parseInt($(this).val());
                });

        

                $('#total_tindakan').val(mb.formatRp(parseInt(total_tindakan)));
                // if(total_debit != grandtotal_debit && total_debit != grandtotal_credit)
                // {
                //     $('a#confirm_jurnal', $form).addClass('hidden');
                //     // alert('bebas');
                // }
                // else
                // {
                //     $('a#confirm_jurnal', $form).removeClass('hidden');
                // }
                // handleCompareTotal();    
                
            });
            
        } );

        $popoverItemContentTindakan.hide();        
    };

    
  

    var handleAccountSelect = function($btn)
    {
        $btn.on('click', function(e){

            var 
                $parentPop   = $(this).parents('.popover').eq(0),
                rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row         = $('#'+rowId, $tableAddAccount),
                $rowClass    = $('.table_item', $tableAddAccount),
                $itemCodeEl  = null,
                $itemNameEl  = null, 
                $itemHargaEl = null,
                $itemQtyEl   = $('input[name$="[name]"]', $row)
                
                $itemIdEl     = $('input[name$="[account_id]"]', $row);
                itemIdElAll     = $('input[name$="[account_id]"]', $rowClass);
                $itemCodeEl   = $('label[name$="[kode]"]', $row);
                $itemCodeIn   = $('input[name$="[kode]"]', $row);
                $itemNameEl   = $('label[name$="[nama]"]', $row);
                $itemNameIn   = $('input[name$="[nama]"]', $row);
                $itemHargaEl  = $('label[name$="[harga]"]', $row);
                $itemHargaIn  = $('input[name$="[harga]"]', $row);
                $itemSatuanEl = $('select[name$="[satuan]"]', $row);
                
                itemId = $(this).data('item')['id'];
            ;                

            found = false;
            $.each(itemIdElAll,function(idx, value){
                // alert(this.value);
                if(itemId == this.value)
                {
                    found = true;
                }
            });


            $('.search-account', $tableAddAccount).popover('hide');

            if(found == false)
            {
           
                $itemIdEl.val($(this).data('item')['id']);
                $itemCodeEl.text($(this).data('item')['kode']);
                $itemCodeIn.val($(this).data('item')['kode']);
                $itemNameEl.text($(this).data('item')['nama']);
                $itemNameIn.val($(this).data('item')['nama']);
                $itemHargaEl .text(mb.formatRp(parseInt($(this).data('item')['harga'])));
                $itemHargaIn .val($(this).data('item')['harga']);

                var satuan = $(this).data('satuan'),
                    primary = $(this).data('satuan_primary');
                
                $itemSatuanEl.empty();
                $.each(satuan, function(key, value) {
                    $itemSatuanEl.append($("<option></option>")
                        .attr("value", value.id).text(value.nama));
                    $itemSatuanEl.val(primary.id);
                });
            }

            if(found == false)
            {
                if($row.closest("tr").is(":last-child")) 
                {
                     addItemRow();
                }
               // addItemRow();
            }

            $itemSatuanEl.on('change', function(){
                handeSelectSatuan($row, $(this).val());
            });

            // addItemRow();
            calculateTotal();
            e.preventDefault();   
        });     
    };

    var handleAccountTitipanSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');SS
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $rowClass       = $('.row_plus', $tableAddAccountTitipan),
                $row            = $('#'+rowId, $tableAddAccountTitipan),
                $itemKodeEl     = null,
                $itemNamaEl     = null, 
                $itemHargaEl    = null, 
                $itemSubTotalEl = null 
                // $itemQtyEl  = $('input[name$="[name]"]', $row)
            ;                
            // console.log(itemTarget);
           
            $itemidEl    = $('input[name$="[tindakan_id]"]', $row);
            itemIdElAll  = $('input[name$="[tindakan_id]"]', $rowClass);
            $itemKodeEl  = $('label[name$="[kode_titipan]"]', $row);
            $itemNamaEl  = $('label[name$="[nama_titipan]"]', $row);
            $itemHargaEl = $('label[name$="[harga_titipan]"]', $row);
            $itemHargaIn = $('input[name$="[harga_tindakan]"]', $row);
            
            itemId = $(this).data('item')['id'];

            found = false;
            $.each(itemIdElAll,function(idx, value){
                // alert(itemId);
                if(itemId == this.value)
                {
                    found = true;
                }
            });


            if(found == false)
            {
                $itemidEl.val($(this).data('item')['id']);
                $itemKodeEl.text($(this).data('item')['kode']);
                $itemNamaEl.text($(this).data('item')['nama']);
                $itemHargaEl.text(mb.formatRp(parseInt($(this).data('item')['harga'])));
                $itemHargaIn.val($(this).data('item')['harga']);
            }

            $('.search-account-titipan', $tableAddAccountTitipan).popover('hide');

            if(found == false)
            {
                if($row.closest("tr").is(":last-child")) 
                {
                    addItemAccRow();
                    // addItemRow(1);
                }
               // addItemRow();
            }
            
            calculateTotalTitipan();
            e.preventDefault();   
        });     
    };

    var handeSelectSatuan = function($row, id) 
    {
        $itemHargaEl    = $('label[name$="[harga]"]', $row);
        $itemHargaIn    = $('input[name$="[harga]"]', $row);
        $itemSubHargaEl = $('label[name$="[sub_total]"]', $row);
        $itemSubHargaIn = $('input[name$="[sub_total]"]', $row);
        $itemSatuanEl   = $('select[name$="[satuan]"]', $row);

        // $('label[name$="[sub_total]"]', $row).text(mb.formatRp(totalCost));
        // $('input[name$="[sub_total]"]', $row).val(totalCost);
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_satuan_harga',
            data     : {id: id},
            dataType : 'json',
            success  : function( results ) {
                if(results.success === true)
                {
                    $itemHargaEl .text(mb.formatRp(parseInt(results.rows.harga)));
                    $itemHargaIn .val(results.rows.harga);

                    $itemSubHargaEl .text(mb.formatRp(parseInt(results.rows.harga)));
                    $itemSubHargaIn .val(results.rows.harga);

                    calculateTotal();
                }
                else
                {
                    alert('Satuan ini tidak memiliki harga. Silahkan pilih satuan lain');
                    $itemSatuanEl.focus();
                }
            }
        });         

    }


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
            calculateTotal();
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
            calculateTotalTitipan();
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


    var handleSelectPoli = function(id){

        $('#poli').select2({

            placholder : 'Pilih Poli',
            allowClear : true

        });
    }

    var handleSelect2 = function () {

        $("#poli").select2({
            // tags: ["developer@oriensjaya.com", "admin@oriensjaya.com", "amir@oriensjaya.com", "udin@oriensjaya.com", "usro@oriensjaya.com", "ujang@oriensjaya.com", "ipul@oriensjaya.com"]
        });

    };



    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/paket/';
        handleValidation();
        initForm();
        calculateTotalKeseluruhan();
        handleSelectCabang();
        handleSelect2();    
        handleConfirmSave();
        handleDataTableItems();
        handleDataTableItemsTitipan();
 
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});