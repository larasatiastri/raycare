mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl              = '',
        $form                   = $('#form_edittemplate'),
        $tableAddAccount        = $('#table_add_account', $form),
        $tableAddAccountTindakan = $('#table_add_account_tindakan', $form),
        $tableAccountSearch     = $('#table_account_search'),
        $tableItemSearch        = $('#table_item_search'),
        $tableItemSearchTindakan = $('#table_item_search_tindakan'),
        $tableInformation       = $('#table_information'),
        $popoverItemContent     = $('#popover_item_content'),
        $popoverItemContentTindakan     = $('#popover_item_content_tindakan'),
        $lastPopoverItem        = null,
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        tplItemAccRow           = $.validator.format( $('#tpl_item_acc_row').text() ),
        itemCounter             = $('input#count_obat').val(),
        itemCounter2            = $('input#count_tindakan').val(),
        totalBayar              = 0
        ;

    var initForm = function(){
      


        var 
            $btnSearchAccount         = $('.search-account', $tableAddAccount),
            $btnSearchAccountTindakan = $('.search-account-tindakan', $tableAddAccountTindakan),
            $btnDeletes               = $('.del-this', $tableAddAccount);
            $btnDeletesDB             = $('.del-db', $tableAddAccount);
            $satuan                   = $('.bs-select-satuan', $tableAddAccount);
            $btnDeletesTindakan       = $('.del-this-plus', $tableAddAccountTindakan);
            $btnDeletesTindakanDB     = $('.del-this-plus-db', $tableAddAccountTindakan);


        handleBtnSearchAccount($btnSearchAccount);  

        handleBtnSearchAccountTindakan($btnSearchAccountTindakan);  


        // handle delete btn
        $.each($btnDeletes, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        $.each($btnDeletesDB, function(idx, btn){
            handleBtnDeleteDB( $(btn) );
        });

        ////////////////////////////////

        $.each($btnDeletesTindakan, function(idx, btn){
            handleBtnDeleteTindakan( $(btn) );
        });

        $.each($btnDeletesTindakanDB, function(idx, btn){
            handleBtnDeleteTindakanDB( $(btn) );
        });

        $.each($satuan, function(idx, btn){
            handleSetHargaPersatuan( $(btn) );
        });


        ///////////////////////////////

        // tambah 1 row kosong pertama
        addItemAccRow();
        addItemRow();

        // $('.row_plus', $tableAddAccount).hide();
        // $('.row_plus', $tableAddAccountTindakan).hide();

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

        $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTindakan).on('keyup', function(){
            calculateTotalTindakan();
        });

        $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTindakan).on('change', function(){
            calculateTotalTindakan();
        });

        calculateTotalTindakan();

        //////////////////////////////////////////////////////////////////////////////
        
        $('input[name$="[biaya_tambahan]"]', $form).on('keyup', function(){
            calculateTotalKeseluruhan();
        });

        $('input[name$="[biaya_tambahan]"]', $form).on('change', function(){
            calculateTotalKeseluruhan();
        });

        calculateTotalKeseluruhan();



        


    };

    // untuk mengeset poliklinik dari awal
    var handleSetPoliklinik = function(){

        var id          = $('select#tipe_transaksi').val(),
            $poli       = $('#poli'),
            data_pilih  = new Array();


        console.log($('input#id').val());
        
        // value poliklinik yang sudah dipilih
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_data_cabang_id',
            data     : {paket_id: $('input#id').val()},
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success  : function( results ) {

                $.each(results, function(key, value) 
                {   
                    data_pilih[key] = value.poliklinik_id;
                });

                console.log(data_pilih);

                // set poliklinik berdasarkan cabang
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_cabang_id',
                    data     : {id: id},
                    dataType : 'json',
                    success  : function( results ) 
                    {
                        $poli.empty();
                       
                        $.each(results, function(key, value) {

                            $poli.append($("<option></option>").attr("value", value.poliklinik_id).text(value.nama));
                            $("#poli").select2('val', data_pilih);

                        });
                    }
                });
            },
            complete : function() {
                Metronic.unblockUI();
            }
        });

    }

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
                
                $('input#total_keseluruhan').val(mb.formatTanpaRp(totalBayar));
                $('input#total_keseluruhan_hidden').val(totalBayar);

                if (!isNaN(totalBayar)){

                $('input#total_keseluruhan').val(mb.formatTanpaRp(totalBayar));
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
                is_delete = $('input[name$="[is_delete]"]', $row).val(),
                itemCode = $('label[name$="[kode]"]', $row).text(),
                harga = $('input[name$="[harga]"]', $row).val(),
                jumlah     = $('input[name$="[jumlah]"]', $row).val()*1
                ;
                // alert(itemCode);

            if (itemCode != '' && is_delete == ''){
                totalCost = harga*jumlah;
                
                $('label[name$="[sub_total]"]', $row).text(mb.formatRp(totalCost));
                $('input[name$="[sub_total]"]', $row).val(totalCost);

                 total_alat_obat = total_alat_obat + totalCost;
                $('input#total_bayar').val(mb.formatTanpaRp(total_alat_obat));
                $('input#total_bayar_hidden').val(total_alat_obat);

            }
            // alert(totalCost);

        });

        // $('#total_before_discount_hidden').val(totalCost);
    };
    
    var calculateTotalTindakan = function(){
        // alert('masuk function');
        var 
            $rows     = $('tbody>tr', $tableAddAccountTindakan), 
            totalCost = 0,
            total_tindakan = 0;

        $.each($rows, function(idx, row){
            var 
                $row     = $(row), 
                is_delete = $('input[name$="[is_delete]"]', $row).val(),
                itemCode = $('label[name$="[kode_tindakan]"]', $row).text(),
                harga    = $('input[name$="[harga_tindakan]"]', $row).val(),
                jumlah   = $('input[name$="[jumlah_tindakan]"]', $row).val()*1
                ;
                // alert(itemCode);

            if (itemCode != 0 && is_delete == ''){
                // totalCost += harga*jumlah;
                totalCost = harga*jumlah;

                // alert(totalCost);
                
                $('label[name$="[sub_total_tindakan]"]', $row).text(mb.formatRp(totalCost));
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
            $('input#biaya_tambahan').val('');
            $('input#total_keseluruhan').val('');
            $('input#total_keseluruhan_hidden').val('');
            // calculateTotalAdisc();
        });

        $('input[name$="[jumlah]"]', $tableAddAccount).on('change', function(){
            calculateTotal();
            $('input#biaya_tambahan').val('');
            $('input#total_keseluruhan').val('');
            $('input#total_keseluruhan_hidden').val('');
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

        var numRow = $('tbody tr', $tableAddAccountTindakan).length;
        var 
            $rowContainer         = $('tbody', $tableAddAccountTindakan),
            $newItemRow           = $(tplItemAccRow(itemCounter2++)).appendTo( $rowContainer ),
            // $btnAddAccount  = $('.add_row', $newItemRow),
            $btnSearchAccountTindakan  = $('.search-account-tindakan', $newItemRow)

            ;

        // handle delete btn
        handleBtnDeleteTindakan( $('.del-this-plus', $newItemRow) );
      

        // handle button search item
        // handleAddAcc($btnAddAccount);

        handleBtnSearchAccountTindakan($btnSearchAccountTindakan);

        ///////////////////////

         $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTindakan).on('keyup', function(){
            calculateTotalTindakan();
            $('input#biaya_tambahan').val('');
            $('input#total_keseluruhan').val('');
            $('input#total_keseluruhan_hidden').val('');
        });

        $('input[name$="[jumlah_tindakan]"]', $tableAddAccountTindakan).on('change', function(){
            calculateTotalTindakan();
            $('input#biaya_tambahan').val('');
            $('input#total_keseluruhan').val('');
            $('input#total_keseluruhan_hidden').val('');
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
     

    var handleDataTableItems = function(){
       oTable = $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            // 'sAjaxSource'              : baseAppUrl + 'listing_alat_obat',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_alat_obat/',
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
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
     
        var id = $('select#tipe_transaksi').val(); 
        var kategori = $( '#select_so_history').val();
        // alert(id);
            oTable.api().ajax.url(baseAppUrl + 'listing_alat_obat/' + id + '/' + kategori).load();

       $( '#select_so_history').on( 'change', function () {
            // alert($(this).val());
            //$tableCabangHistory.fnFilter( this.value, 4);
            var cabang_id = $('select#tipe_transaksi').val();
            oTable.api().ajax.url(baseAppUrl + 'listing_alat_obat/'+ cabang_id +'/'+$(this).val()).load();
            // alert($(this).val());
        });

                $tableItemSearch.on('draw.dt', function (){
                    
                    var $btnSelect = $('a.select', this);
                    
                    handleAccountSelect( $btnSelect );


                    
                });
            
            $popoverItemContent.hide();        

    };

    var handleDataTableItemsTindakan = function(){
        oTable2 = $tableItemSearchTindakan.dataTable({
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
        $('#table_item_search_tindakan_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_search_tindakan_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tableItemSearchTindakan.on('draw.dt', function (){
            var $btnSelect = $('a.select-tindakan', this);
            handleAccountTindakanSelect( $btnSelect );

            var grandtotal_tindakan =  $('input[name="total_tindakan"]', this).val();
            // var grandtotal_credit =  $('input[name="grandtotal_credit"]', this).val();

            $('input[name$="[jumlah_tindakan]"]', this).on('change keyup', function(){
                var total_tindakan = 0;
                var $subtotal_tindakan = $('label[name$="[sub_total_tindakan]"]', $tableAddAccountTindakan);

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

    
    var handleAccountSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');
            var 
                $parentPop   = $(this).parents('.popover').eq(0),
                rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row         = $('#'+rowId, $tableAddAccount),
                $rowClass    = $('.table_item', $tableAddAccount),
                $itemCodeEl  = null,
                $itemNameEl  = null, 
                $itemHargaEl = null,
                $itemQtyEl   = $('input[name$="[name]"]', $row)
            ;                
            // console.log(itemTarget);
           
            $itemIdEl     = $('input[name$="[account_id]"]', $row);
            itemIdElAll   = $('input[name$="[item_id]"]', $rowClass);
            $itemId       = $('input[name$="[item_id]"]', $row);
            $itemCodeEl   = $('label[name$="[kode]"]', $row);
            $itemCodeIn   = $('input[name$="[kode]"]', $row);
            $itemNameEl   = $('label[name$="[nama]"]', $row);
            $itemNameIn   = $('input[name$="[nama]"]', $row);
            $itemHargaEl  = $('label[name$="[harga]"]', $row);
            $itemHargaIn  = $('input[name$="[harga]"]', $row);
            $itemSatuanEl = $('select[name$="[satuan]"]', $row);

            itemId = $(this).data('item')['id'];

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
                // $itemIdEl.val($(this).data('item')['id']);
                $itemId.val($(this).data('item')['id']);
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
            
            $('input#biaya_tambahan').val("");
            $('input#total_keseluruhan').val("");

            $itemSatuanEl.on('change', function(){
                handeSelectSatuan($row, $(this).val());
                $('input#biaya_tambahan').val("");
                $('input#total_keseluruhan').val("");

            });
            
            
            // addItemRow();
            calculateTotal();
            e.preventDefault();   
        });     
    };

    var handleAccountTindakanSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');SS
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row            = $('#'+rowId, $tableAddAccountTindakan),
                $rowClass       = $('.row_plus', $tableAddAccountTindakan),
                $itemKodeEl     = null,
                $itemNamaEl     = null, 
                $itemHargaEl    = null, 
                $itemSubTotalEl = null 
                // $itemQtyEl  = $('input[name$="[name]"]', $row)
            ;                
           
            $itemidEl    = $('input[name$="[tindakan_id]"]', $row);
            itemIdElAll  = $('input[name$="[tindakan_id]"]', $rowClass);
            $itemid      = $('input[name$="[paket_tindakan_id]"]', $row);
            $itemKodeEl  = $('label[name$="[kode_tindakan]"]', $row);
            $itemKodeIn  = $('input[name$="[kode_tindakan]"]', $row);
            $itemNamaEl  = $('label[name$="[nama_tindakan]"]', $row);
            $itemNamaIn  = $('input[name$="[nama_tindakan]"]', $row);
            $itemHargaEl = $('label[name$="[harga_tindakan]"]', $row);
            $itemHargaIn = $('input[name$="[harga_tindakan]"]', $row);

            itemId       = $(this).data('item')['id'];

            found = false;
            $.each(itemIdElAll,function(idx, value){
                // alert(this.value);
                if(itemId == this.value)
                {
                    found = true;
                }
            });
           
            if(found == false)
            {
                $itemidEl.val($(this).data('item')['id']);
                $itemKodeEl.text($(this).data('item')['kode']);
                $itemKodeIn.val($(this).data('item')['kode']);
                $itemNamaEl.text($(this).data('item')['nama']);
                $itemNamaIn.val($(this).data('item')['nama']);
                $itemHargaEl.text(mb.formatRp(parseInt($(this).data('item')['harga'])));
                $itemHargaIn.val($(this).data('item')['harga']);
            }

            $('.search-account-tindakan', $tableAddAccountTindakan).popover('hide');

            $('input#biaya_tambahan').val("");
            $('input#total_keseluruhan').val("");

            if(found == false)
            {
                if($row.closest("tr").is(":last-child")) 
                {
                    addItemAccRow();
                    // addItemRow(1);
                }
            }
            
            // addItemAccRow();
            calculateTotalTindakan();
            e.preventDefault();   
        });     
    };

    var handleSetHargaPersatuan = function($btn){
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableAddAccount)

        $btn.on('change', function(e){    

            $itemHargaEl    = $('label[name$="[harga]"]', $row);
            $itemHargaIn    = $('input[name$="[harga]"]', $row);
            $itemSubHargaEl = $('label[name$="[sub_total]"]', $row);
            $itemSubHargaIn = $('input[name$="[sub_total]"]', $row);
            $itemSatuanEl   = $('select[name$="[satuan]"]', $row);

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_satuan_harga',
                data     : {id: $(this).val()},
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

            $('input#biaya_tambahan').val("");
            $('input#total_keseluruhan').val("");
            // calculateTotal();

            e.preventDefault();
        });
    };


    var handleBtnDelete = function($btn){
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableAddAccount)

        $btn.on('click', function(e){    
            
            $('input[name$="[sub_total]"]', $row).val(0);

            $row.remove();
            if($('tbody>tr', $tableAddAccount).length == 1){        // == 0
                addItemRow();
                // addItemAccRow();
            }
            $('input#biaya_tambahan').val("");
            $('input#total_keseluruhan').val("");
            calculateTotal();

            e.preventDefault();
        });
    };


    var handleBtnDeleteDB = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableAddAccount);

        $btn.on('click', function(e){
            bootbox.confirm('Are you sure want to delete this item?', function(result){
                if (result==true) {
                    
                    $('input[name$="[is_delete]"]', $row).val(1);   
                    $row.hide(); //hide

                    $('input[name$="[sub_total]"]', $row).val(0);
                    // $('input[name$="[sub_total]"', $row).val(0);


                    
                    if($('tbody>tr', $tableAddAccount).length == 0){
                        addItemRow();
                    }
                    $('input#biaya_tambahan').val("");
                    $('input#total_keseluruhan').val("");
                    calculateTotal();
                }
            });
            e.preventDefault();
        });
    };

    var handleBtnDeleteTindakan = function($btn){
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableAddAccountTindakan)

        $btn.on('click', function(e){  
            $('input[name$="[sub_total_tindakan]"]', $row).val(0);

            $row.remove();
            if($('tbody>tr', $tableAddAccountTindakan).length == 1){
                // addItemRow();
                addItemAccRow();
            }
            $('input#biaya_tambahan').val("");
            $('input#total_keseluruhan').val("");
            calculateTotalTindakan();
            e.preventDefault();
        });

        
    };

    var handleBtnDeleteTindakanDB = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableAddAccountTindakan);

        $btn.on('click', function(e){
            bootbox.confirm('Are you sure want to delete this item?', function(result){
                if (result==true) {
                    
                    $('input[name$="[is_delete]"]', $row).val(1);   
                    $row.hide(); //hide

                    $('input[name$="[sub_total]"]', $row).val(0);

                    
                    if($('tbody>tr', $tableAddAccountTindakan).length == 0){
                        addItemRow();
                    }
                    $('input#biaya_tambahan').val("");
                    $('input#total_keseluruhan').val("");
                    calculateTotalTindakan();

                }
            });
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

    var handleBtnSearchAccountTindakan = function($btn){
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
                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'check_modified_paket',
                        data     : {id:$('input[name="id"]').val(), modified_date : $('input[name="modified_date"]').val()},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                           if(results.success == true)
                           {
                              $('#save', $form).click();
                           }    
                           else
                           {
                                bootbox.confirm(results.msg, function(result) {
                                    if(result == true)
                                    {
                                        window.open($('#open_new_tab', $form).attr("href"));
                                    }
                                });
                           }
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });
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

    var handleSelectCabang = function(){
        $('select#tipe_transaksi').on('change', function(){
            var id = $(this).val();
            var $poli = $('#poli');

            $poli.select2('val', "");

            oTable.api().ajax.url(baseAppUrl + 'listing_alat_obat/' + id).load();
         

           ///////////////multi-select poliklinik////////////////////
         $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_cabang_id',
                data     : {id: id},
                dataType : 'json',
                success  : function( results ) {
                    $poli.empty();
                   
                    $.each(results, function(key, value) {
                        $poli.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $poli.val('');

                    });
                }
            });
            
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


    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/paket/';
        handleValidation();
        calculateTotalKeseluruhan();
        initForm();
        handleSelectCabang();
        handleSelect2();    
        handleConfirmSave();
        handleDataTableItems();
        handleDataTableItemsTindakan();
        handleSetPoliklinik();
 
    };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});