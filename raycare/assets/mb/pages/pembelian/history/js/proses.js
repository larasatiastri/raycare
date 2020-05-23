mb.app.daftar_permintaan_po = mb.app.daftar_permintaan_po || {};
(function(o){

    var 
        baseAppUrl                      = '',
        $form                           = $('#form_add_pembelian');
        $popoverItemContent             = $('#popover_item_content'), 
        $popoverItemContentPembelian    = $('#popover_item_content_pembelian'), 
        $popoverPenerimaContent         = $('#popover_penerima_content_cabang'), 
        $popoverPenerimaContentCustomer = $('#popover_penerima_content_customer'), 
        $popoverJumlahPesan             = $('#popover_jumlah_pesan'), 
        $tableDetailPembelian           = $('#table_detail_pembelian'),
        $tableItemSearch                = $('#table_item_search'),
        $tablePilihSupplier             = $('#table_pilih_supplier'),
        $tableCariPermintaan            = $('#table_search_permintaan'),
        $lastPopoverItem                = null,
        $lastPopoverItemPembelian       = null,
        $lastPopoverJumlahPesan         = null,
        tplItemRow                      = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter                     = $('input#counter').val();
        ;

    var 
        $btnSearchItemPembelian = $('.search-item', $tableDetailPembelian),
        $btnDeletes    = $('.del-this', $tableDetailPembelian),
        $btnSearchPermintaan = $('.search-jumlah', $tableDetailPembelian);
        $btnSearchPermintaanTabel = $('.search-tabel', $tableDetailPembelian);
        $btnDeleteItemsDB = $('.del-item-db', $tableDetailPembelian);

        // $btnSearchPermintaan = $('.search-jumlah', $tableDetailPembelian)
    ;
        

    var initform = function()
    {    

        var $btnSearchSupplier  = $('.pilih-supplier');
        handleBtnSearchSupplier($btnSearchSupplier);

        // $btnSearchItemPembelian.on('click', function(){
        //     alert('tes');
        // })

        $('input[name="tipe_supplier"]').on('click', function(){
            iStatTipe   = this.value;

            // alert(iStatTipe);
            $tablePilihSupplier.api().ajax.url(baseAppUrl + 'listing_supplier/' + iStatTipe).load();
            // $tablePermintaanPembelianProses.fnClearTable();
        });

        $('input[name="tipe_penerima"]').on('click', function(){
            iStatTipe   = this.value;

            // alert(iStatTipe);
            $('input[name="tipe"]').val(iStatTipe);
            if(iStatTipe == 1)
            {
                $('a.pilih-penerima-cabang').removeClass("hidden");
                $('a.pilih-penerima-customer').addClass("hidden");
            }
            else if(iStatTipe == 2)
            {
                $('a.pilih-penerima-cabang').addClass("hidden");
                $('a.pilih-penerima-customer').removeClass("hidden");
            }
            // $tablePermintaanPembelianProses.fnClearTable();
        });

        $('input[name$="[jumlah]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

            if($('input[name$="[jumlah]"]').val() < $('input[name$="[jumlah_min]"]').val())
            {
                bootbox.confirm('Jumlah Item Tidak Boleh Kurang Dari Permintaan', function(result) {
                        if (result==true) {
                            
                        }
                    });
            }
        });

        $('input[name$="[jumlah]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   

            if($('input[name$="[jumlah]"]').val() < $('input[name$="[jumlah_min]"]').val())
            {
                bootbox.confirm('Jumlah Item Tidak Boleh Kurang Dari Permintaan', function(result) {
                        if (result==true) {
                            
                        }
                    });
            }         
        });

        $('input[name$="[item_diskon]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

        });

        $('input[name$="[item_diskon]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   
     
        });

        $('input[name$="[item_harga]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

        });

        $('input[name$="[item_harga]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   
     
        });

        $('input#diskon').on('keyup',function()
        {
            calculateTotal();

        });

        $('input#diskon').on('change',function()
        {
            calculateTotal();   
     
        });

        $('input#pph').on('keyup',function()
        {
            calculateTotal();

        });

        $('input#pph').on('change',function()
        {
            calculateTotal();   
     
        });

         $('input#biaya_tambahan').on('keyup',function()
        {
            calculateTotal();

        });

        $('input#biaya_tambahan').on('change',function()
        {
            calculateTotal();   
     
        });

        handleBtnSearchItemPembelian($btnSearchItemPembelian);

        handleBtnSearchPermintaan($btnSearchPermintaan);    
        handleBtnSearchPermintaan($btnSearchPermintaanTabel);    

        // handle delete btn
        $.each($btnDeletes, function(idx, btn){
            handleBtnDeletePembelian( $(btn) );
        });

        $.each($btnDeleteItemsDB, function(idx, btn){
            handleBtnDeleteItemsDB( $(btn) );
        });

        addItemRow();
        calculateTotal();

        $popoverJumlahPesan.hide();
    }

    var addItemRow = function(){
        
        var numRow = $('tbody tr', $tableDetailPembelian).length;

        console.log('numrow' + numRow);

        // if (numRow > 0 && ! isValidLastRow()) return;

        var 
            $rowContainer     = $('tbody', $tableDetailPembelian),
            $newItemRow       = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            // $newGetItemRow = $(tplGetItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItemPembelian    = $('.search-item', $newItemRow);
            $btnSearchPermintaan = $('.search-jumlah', $newItemRow);
            $btnSearchPermintaanTabel = $('.search-tabel', $tableDetailPembelian);

            // $btnSearchPermintaan.attr('data-row', numRow);
            // $btnSearchPermintaanTabel.attr('data-row', numRow);


        $('input[name$="[jumlah]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

            if($('input[name$="[jumlah]"]').val() < $('input[name$="[jumlah_min]"]').val())
            {
                bootbox.confirm('Jumlah Item Tidak Boleh Kurang Dari Permintaan', function(result) {
                        if (result==true) {
                            
                        }
                    });
            }
        });

        $('input[name$="[jumlah]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   

            if($('input[name$="[jumlah]"]').val() < $('input[name$="[jumlah_min]"]').val())
            {
                bootbox.confirm('Jumlah Item Tidak Boleh Kurang Dari Permintaan', function(result) {
                        if (result==true) {
                            
                        }
                    });
            }         
        });

        if($('input[name$="[jumlah]"]', $tableDetailPembelian).val != 0)
        {
            calculateTotal();   
        };

        $('input[name$="[item_diskon]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

        });

        $('input[name$="[item_diskon]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   
     
        });

        $('input[name$="[item_harga]"]', $tableDetailPembelian).on('keyup',function()
        {
            calculateTotal();

        });

        $('input[name$="[item_harga]"]', $tableDetailPembelian).on('change',function()
        {
            calculateTotal();   
     
        });
        
        // handle delete btn
        handleBtnDeletePembelian( $('.del-this', $newItemRow) );

        // handle button search item
        handleBtnSearchItemPembelian($btnSearchItemPembelian);
        handleBtnSearchPermintaan($btnSearchPermintaan);
        
        calculateTotal();
    };

     var isValidLastRow = function(){
        var 
            $itemCodeEls    = $('input[name$="[code]"]',$tableDetailPembelian),
            $qtyELs         = $('input[name$="[qty]"]',$tableDetailPembelian),
            itemCode        = $itemCodeEls.eq($qtyELs.length-1).val(),
            qty             = $qtyELs.eq($qtyELs.length-1).val() * 1
        ;

        return (itemCode != '' && qty > 0)
    }

   
    var handleBtnDeletePembelian = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableDetailPembelian);

        $btn.on('click', function(e){
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
                // if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableDetailPembelian).length == 0){
                        addItemRow();
                    }
                    // focusLastItemCode();
                // }
            // });
            calculateTotal();
            e.preventDefault();
        });
    };

    var calculateTotal = function(){
        // alert('masuk function');
        var 
            $rows     = $('tbody>tr', $tableDetailPembelian), 
            $sub_total = $('.sub_total', $tableDetailPembelian),
            cost = 0,
            totalCost = 0,
            grandTotal = 0,
            grandTotalAll = 0
        ;

        $.each($rows, function(idx, row)
        {
            var 
                $row     = $(row), 
                itemCode = $('label[name$="[item_kode]"]', $row).text(),
                harga = parseInt($('input[name$="[item_harga]"]', $row).val()),
                diskon     = parseInt($('input[name$="[item_diskon]"]', $row).val()*1),
                jumlah     = parseInt($('input[name$="[jumlah]"]', $row).val()*1)
            ;
                // alert($('input[name$="[item_harga]"]', $row).val());

            if (itemCode != '' ){
                cost = harga-(harga*diskon/100);
                // alert(cost);
                totalCost = cost*jumlah;

                // alert(totalCost);
                
                $('label[name$="[item_sub_total]"]', $row).text(mb.formatRp(parseInt(totalCost)));
                $('input[name$="[item_sub_total]"]', $row).val(parseInt(totalCost));

            }

        });

        $.each($sub_total, function(){
            // alert(parseInt($(this).val()));
            grandTotal = grandTotal + parseInt($(this).val());
        });

        $('input#total').val(mb.formatTanpaRp(grandTotal));

        grandTotalAll = grandTotal - parseInt(grandTotal * parseInt($('input#diskon').val())/100) + parseInt(grandTotal * parseInt($('input#pph').val())/100) + parseInt($('input#biaya_tambahan').val());
        $('input#grand_total').val(mb.formatTanpaRp(grandTotalAll));
        // $('#total_before_discount_hidden').val(totalCost);

        // $('#total_before_discount_hidden').val(totalCost);
    };

    var handleBtnSearchItemPembelian = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // var rowStatus  = $btn.closest('tr').prop('class');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverItemPembelian != null) $lastPopoverItemPembelian.popover('hide');

            $lastPopoverItemPembelian = $btn;

            $popoverItemContentPembelian.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContentPembelian);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverItemContentPembelian.hide();
            $popoverItemContentPembelian.appendTo($('.page-content'));

            $lastPopoverItemPembelian = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableItems = function(){
        oTableItemSearch = $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_search_item/' + $('input#id_supplier').val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'item.kode kode', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : false, 'searchable': false, 'orderable': true },               ]
        });

        var $btnSelects = $('a.select', $tableItemSearch);
        handleItemSearchSelectPembelian( $btnSelects );

        $tableItemSearch.on('draw.dt', function (){
            $('.btn', this).tooltip();

            var $btnSelect = $('a.select', this);
            handleItemSearchSelectPembelian( $btnSelect );
            
        } );

        $popoverItemContentPembelian.hide();        
    };

    var handleItemSearchSelectPembelian = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input[name="rowItemId"]', $parentPop).val(),
                statusRow       = $('input[name="rowStatus"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableDetailPembelian),
                $row_status = '',
                $itemId   = null,
                $itemKode = null,
                $itemNama = null,
                $classrow   = $('.table_item', $tableDetailPembelian),
                ItemIdAll = $('input[name$="[item_id]"]', $classrow),
                itemId = $(this).data('item').id
                ;        


           
                $itemId        = $('input[name$="[item_id]"]', $row);
                $itemKode      = $('input[name$="[item_kode]"]', $row);
                $itemNama      = $('input[name$="[item_nama]"]', $row);
                $itemSatuan    = $('select[name$="[satuan]"]', $row);
                $SearchSatuan  = $('input[name$="[satuan_nama]"]', $row),
                $itemHarga  = $('input[name$="[item_harga]"]', $row),
                $itemLabelKode = $('label[name$="[item_kode]"]', $row);
                $itemLabelNama = $('label[name$="[item_nama]"]', $row);
                $itemLabelSyarat = $('label[name$="[item_syarat]"]', $row);
                $itemLabelStok = $('label[name$="[stok]"]', $row);
                $itemLabelHarga = $('label[name$="[item_harga]"]', $row);
                $itemLabelTotal = $('label[name$="[item_total]"]', $row);
                $btnSearchPermintaan = $('a.search-jumlah', $row);


                $('.search-item', $tableDetailPembelian).popover('hide');
                
                found = false;
                // $.each(ItemIdAll, function(idx, value){
                //     // alert(itemId);
                //     if(itemId == this.value)
                //     {
                //         found = true;
                //     }

                //     // alert(this.value);
                // });

                
                // console.log($itemId);

                var primary = $(this).data('satuan_primary').id;
                var name = $(this).data('satuan_primary').nama;
                
                // alert(name);

                if(found == false)
                {
                    $.ajax
                    ({ 
                        type: 'POST',
                        url: baseAppUrl +  "get_item_satuan",  
                        data:  {item_id : $(this).data('item').id, supplier_id: $(this).data('item').id_sup},  
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success:function(results)          //on recieve of reply
                        { 
                            $itemSatuan.empty();

                            //munculin semua data dari hasil post
                            $.each(results, function(key, value) {
                                $itemSatuan.prepend($("<option></option>")
                                    .attr({ "value" : value.id, "data-harga" : value.harga, "data-min" : value.minimum_order, "data-max" : value.kelipatan_order }).text(value.nama));
                                $itemSatuan.val(primary);
                                if(value.is_primary)
                                {
                                    $itemLabelSyarat.text(value.minimum_order +'/'+ value.kelipatan_order);
                                    $itemLabelHarga.text(mb.formatRp(parseInt(value.harga), 10));
                                    $itemHarga.val(parseInt(value.harga));
                                }

                            });
                            
                        },

                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });
                    if ($itemKode.val() == "") {
                        $itemId.val($(this).data('item').id);            
                        $itemKode.val($(this).data('item').item_kode);
                        $itemNama.val($(this).data('item').item_nama);
                        // $itemHarga.val(parseInt($(this).data('item').harga));
                        $SearchSatuan.val(name);
                        $itemLabelKode.text($(this).data('item').item_kode);
                        $itemLabelNama.text($(this).data('item').item_nama);
                        $itemLabelStok.text($(this).data('info').stok);
                        $itemLabelSyarat.text($(this).data('item').min_order +'/'+ $(this).data('item').max_order);
                        // $itemLabelHarga.text(mb.formatRp(parseInt($(this).data('item').harga), 10));
                        $btnSearchPermintaan.attr('data-id', $(this).data('item').id);
                        $btnSearchPermintaan.attr('data-satuan', primary);

                        addItemRow();
                        e.preventDefault();
                    }else{
                        $itemId.val($(this).data('item').id);            
                        $itemKode.val($(this).data('item').item_kode);
                        $itemNama.val($(this).data('item').item_nama);
                        // $itemHarga.val(parseInt($(this).data('item').harga));
                        $SearchSatuan.val(name);
                        $itemLabelKode.text($(this).data('item').item_kode);
                        $itemLabelNama.text($(this).data('item').item_nama);
                        $itemLabelStok.text($(this).data('info').stok);
                        $itemLabelSyarat.text($(this).data('item').min_order +'/'+ $(this).data('item').max_order);
                        // $itemLabelHarga.text($(this).data('item').harga);
                        $btnSearchPermintaan.attr('data-id', $(this).data('item').id);
                        $btnSearchPermintaan.attr('data-satuan', primary);
                    }
                    
                }

                //  $itemSatuan.on('change', function(){
                //         handeSelectSatuan($row, $(this).val());
                //         $btnSearchPermintaan.attr('data-satuan', $(this).val());
                // });

                // addItemRow();
                calculateTotal();
                handleSelectSatuan();
            
                // oTablePermintaanTerdaftar.api().ajax.url(baseAppUrl + 'listing_permintaan_terdaftar/0' + $(this).data('item').id).load();            
                // oTableCariPermintaan.api().ajax.url(baseAppUrl + 'listing_search_permintaan/0' + $(this).data('item').id).load();            
            
        });     
    };

    var handleSelectSatuan = function()
    {   

        $('select.satuan').on('change', function(){

            row = $(this).data('row');

            $('select#item_satuan_'+ row).attr('readonly');
            $('a#search_jumlah_permintaan_'+ row).attr('data-satuan', $(this).val());

            $itemSatuanId      = $('input#items_satuan_nama_' + row),
            $itemHarga       = $('input#items_harga_' + row),
            $itemLabelHarga  = $('label#items_hargaEl_' + row),
            $itemLabelSyarat = $('label#item_syarat_'+ row);
            // $SearchSatuan    = $('input#items_satuan_nama_',+ $row),

            // $itemKonversi      = $('input#items_konversi_' + row);
            // $totalStockPrimary = $('input#items_total_stock_' + row);
            // $itemJumlah        = $('input#items_jumlah_' + row);

            $harga    = $('option:selected', this).attr('data-harga');
            $min_order    = $('option:selected', this).attr('data-min');
            $max_order    = $('option:selected', this).attr('data-max');
            $text    = $('option:selected', this).text();
            // alert($('label#items_harga_' + row));
            // $konversi = $('option:selected', this).attr('data-konversi');

            $itemSatuanId.val($text);
            $itemHarga.val($harga);
            $itemLabelHarga.text(mb.formatRp(parseInt($harga)));
            $itemLabelSyarat.text($min_order+'/'+$max_order);


            $('label[name="satuan_alokasi"]').text($itemSatuanId.val());
            $('a#tambah-link').attr('href', baseAppUrl+'daftar_link/'+$('a#search_jumlah_permintaan_'+ row).data('id')+'/'+row+'/'+$(this).val());
        //     $total_stock = $totalStockPrimary.val();

        //     // utk set jumlah max inputnya sesuai total seluruh stock / konversi
        //     $max = parseInt($total_stock / $konversi);
        //     $itemJumlah.attr('max', $max);

            calculateTotal();

        })

    };


    var handleBtnSearchPermintaan = function($btn){
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

            if ($lastPopoverJumlahPesan != null) $lastPopoverJumlahPesan.popover('hide');

            $lastPopoverJumlahPesan = $btn;

            $popoverJumlahPesan.show();

        }).on('shown.bs.popover', function(e){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            var idRow = $('tr#'+ rowId);

            $('label[name="satuan_alokasi"]').text($('input[name$="[satuan_nama]"]', idRow).val());
            $('a#tambah-link').attr('href', baseAppUrl+'daftar_link/'+$(this).data('id')+'/'+parseInt($(this).data('row'))+'/'+$(this).data('satuan'));
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverJumlahPesan);

            $('#table_search_permintaan > tbody').remove();
            var tbody = $('<tbody></tbody>');
            $('table#table_search_permintaan').append(tbody);
            $('table#table_search_permintaan > tbody').append($('div#tabel_simpan_data_'+parseInt($(this).data('row'))).html());
            // $('#table_search_permintaan > tbody').html($('div#simpan_data_'+parseInt($(this).data('row')+1)).html());


        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverJumlahPesan.hide();
            $popoverJumlahPesan.appendTo($('.page-content'));

            $lastPopoverJumlahPesan = null;


        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
            // handleBtnSearchPermintaan();
        });
    };

    var handleDataTableSearchPermintaan = function(){
        oTableCariPermintaan = $tableCariPermintaan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_search_permintaan/0',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true }              ]
        });

        // var $btnSelects = $('a.select', $tableItemSearch);
        // handleItemSearchSelectPembelian( $btnSelects );

        // $tableItemSearch.on('draw.dt', function (){
        //     var $btnSelect = $('a.select', this);
        //     handleItemSearchSelectPembelian( $btnSelect );
            
        // } );

        $popoverJumlahPesan.hide();        
    };



    var handleBtnSearchSupplier = function($btn){
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
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

     var handleDataTableSupplier = function() 
    {
        $tablePilihSupplier.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_supplier/1' ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        
        $('#table_pilih_supplier_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_supplier_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tablePilihSupplier.on('draw.dt', function (){
            var $btnSelect = $('a.select-supplier', this);
            handlePilihSupplierSelect( $btnSelect );
            
        } );

        $popoverItemContent.hide();
    }

    var handlePilihSupplierSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $IdSupplier       = $('input[name="id_supplier"]'),
                $NamaSupplier     = $('input[name="nama_supplier"]'),
                $KontakSupplier     = $('input[name="kontak_supplier"]'),
                $AlamatSupplier     = $('textarea[name="alamat_supplier"]'),
                $EmailSupplier     = $('input[name="email_supplier"]'),
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('.pilih-supplier').popover('hide');          

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $IdSupplier.val($(this).data('item').id);
            $NamaSupplier.val($(this).data('item').nama);
            $KontakSupplier.val($(this).data('item').kontak_person +' ('+ $(this).data('item').no_telp +')');
            $AlamatSupplier.val($(this).data('item').alamat+','+$(this).data('item').kelurahan+','+$(this).data('item').kecamatan+','+$(this).data('item').kota+','+$(this).data('item').propinsi+','+$(this).data('item').negara);
            $EmailSupplier.val($(this).data('item').email); 

            // oTableItemSearch.api().ajax.url(baseAppUrl + 'listing_search_item/' + $(this).data('item').id).load();
            // alert($itemIdEl.val($(this).data('item').id));

            e.preventDefault();
        });     
    };

    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }

        /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
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

    var handleBtnDeleteItemsDB = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableDetailPembelian);

        $btn.on('click', function(e){
            bootbox.confirm('Are you sure want to delete this item?', function(result){
                if (result==true) {

                    $('input[name$="[is_deleted]"]', $row).attr('value', 1);   

                    $row.hide(); //hide
                    if($('tbody>tr', $tableDetailPembelian).length == 0){
                        addItemRow();

                    }
                }
            });
            calculateTotal();
            e.preventDefault();
        });
    };

    $('input#check').on('change', function(){
        var checked = $(this).is(":checked");
        if(checked){
            // alert('a');
            $('textarea#info_supplier').removeAttr('readonly', true);
        }
        else {

            $('textarea#info_supplier').attr('readonly', true);
        }
    });

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/history/';
        initform();
        handleDatePickers();
        handleDataTableSupplier();
        handleDataTableItems();
        handleConfirmSave();
        // handleConfirmSaveDraft();
        // handleDataTableSearchPermintaan();
        // handleDataTablePermintaanTerdaftar();
        // handleDataTablbePermintaanTidakTerdaftar();
        // handleSaveModalLink();
    };
 }(mb.app.daftar_permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_permintaan_po.init();
});