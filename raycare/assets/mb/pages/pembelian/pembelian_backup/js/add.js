mb.app.daftar_permintaan_po = mb.app.daftar_permintaan_po || {};
(function(o){

    var 
        baseAppUrl                      = '',
        $form                           = $('#form_add_pembelian');
        $tableDetailPembelian           = $('#table_detail_pembelian'),
        $tablePenawaran                 = $('#table_penawaran'),
        $tableItemSearch                = $('#table_item_search'),
        $tablePilihSupplier             = $('#table_pilih_supplier'),
        $tablePilihCabang               = $('#table_pilih_cabang'),
        $tablePilihCustomer             = $('#table_pilih_customer'),
        $tableCariPermintaan            = $('#table_search_permintaan'),
        $tablePermintaanTerdaftar       = $('#table_pilih_permintaan_terdaftar'),
        $tablePermintaanTidakTerdaftar  = $('#table_pilih_permintaan_tidak_terdaftar'),
        $btnSearchPenerima              = $('.pilih-penerima-cabang'),
        $btnSearchPenerimaCustomer      = $('.pilih-penerima-customer'),
        $popoverItemContent             = $('#popover_item_content'), 
        $popoverItemContentPembelian    = $('#popover_item_content_pembelian'), 
        $popoverPenerimaContent         = $('#popover_penerima_content_cabang'), 
        $popoverPenerimaContentCustomer = $('#popover_penerima_content_customer'), 
        $popoverJumlahPesan             = $('#popover_jumlah_pesan'), 
        $lastPopoverItem                = null,
        $lastPopoverItemPembelian       = null,
        $lastPopoverPenerima            = null,
        $lastPopoverPenerimaCustomer    = null,
        $lastPopoverJumlahPesan         = null,
        tplItemRow                      = $.validator.format( $('#tpl_item_row').text() ),
        tplPenawaranRow                 = $.validator.format( $('#tpl_penawaran_row').text() ),
        itemCounter                     = 0,
        penawaranCounter                = 0
        ;

    var 
        $btnSearchItemPembelian   = $('.search-item', $tableDetailPembelian),
        $btnDeletes               = $('.del-this', $tableDetailPembelian),
        $btnDeletesPermintaan     = $('.del-this', $tableCariPermintaan),
        $btnSearchPermintaan      = $('.search-jumlah', $tableDetailPembelian);
        $btnSearchPermintaanTabel = $('.search-tabel', $tableDetailPembelian);
        $btnAddPenawaran          = $('a.add-upload');
        // $btnSearchPermintaan = $('.search-jumlah', $tableDetailPembelian)
    ;
        

    var initform = function()
    {    

        var $btnSearchSupplier  = $('.pilih-supplier');
        handleBtnSearchSupplier($btnSearchSupplier);

        handleBtnSearchPenerima($btnSearchPenerima);        
        handleBtnSearchPenerimaCustomer($btnSearchPenerimaCustomer);

        handleBtnAddPenawaran($btnAddPenawaran);

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
                $btnSearchPermintaan.removeClass("hidden");
            }
            else if(iStatTipe == 2)
            {
                $('a.pilih-penerima-cabang').addClass("hidden");
                $('a.pilih-penerima-customer').removeClass("hidden");
                $btnSearchPermintaan.addClass("hidden");
            }
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
        $('input#diskon_nominal').on('keyup',function()
        {
            calculateTotalDiskon();

        });

        $('input#diskon_nominal').on('change',function()
        {
            calculateTotalDiskon();   
     
        });

        $('input#pph').on('keyup',function()
        {
            calculateTotal();

        });

        $('input#pph').on('change',function()
        {
            calculateTotal();   
     
        });

        $('input#pph_nominal').on('keyup',function()
        {
            calculateTotalPPh();

        });

        $('input#pph_nominal').on('change',function()
        {
            calculateTotalPPh();   
     
        });

        $('input#dp').on('keyup',function()
        {
            calculateTotalDP();

        });

        $('input#dp').on('change',function()
        {
            calculateTotalDP();   
     
        });

        $('input#dp_nominal').on('keyup',function()
        {
            calculateTotalDPNominal();

        });

        $('input#dp_nominal').on('change',function()
        {
            calculateTotalDPNominal();   
     
        });

        $('input#bunga_persen').on('keyup',function()
        {
            calculateTotalBungaPersen();

        });

        $('input#bunga_persen').on('change',function()
        {
            calculateTotalBungaPersen();   
     
        });

        $('input#bunga_nominal').on('keyup',function()
        {
            calculateTotalBungaNominal();

        });

        $('input#bunga_nominal').on('change',function()
        {
            calculateTotalBungaNominal();   
     
        });

         $('input#biaya_tambahan').on('keyup',function()
        {
            calculateTotal();

        });

        $('input#biaya_tambahan').on('change',function()
        {
            calculateTotal();   
     
        });

        $('input#pembulatan').on('keyup',function()
        {
            calculateTotal();

        });

        $('input#pembulatan').on('change',function()
        {
            calculateTotal();   
     
        });


        handleJenisTenorChange();
        
        handleBtnSearchItemPembelian($btnSearchItemPembelian);
        handleBtnSearchPermintaan($btnSearchPermintaan);    

        // handle delete btn
        $.each($btnDeletes, function(idx, btn){
            handleBtnDeletePembelian( $(btn) );
        });

        $.each($btnDeletesPermintaan, function(idx, btn){
            handleBtnDeletePermintaan( $(btn) );
        });

        $popoverJumlahPesan.hide();

        addItemRow();
        calculateTotal();
        addPenawaranRow();
    }
    var addPenawaranRow = function()
    { 
        var numRow = $('tbody tr', $tablePenawaran).length;

        var 
            $rowContainer     = $('tbody', $tablePenawaran),
            $newItemRow       = $(tplPenawaranRow(penawaranCounter++)).appendTo( $rowContainer );

            handleUploadify(penawaranCounter);
            handleBtnDeletePenawaran($('button.del-this-penawaran', $newItemRow));
           
    }

    var handleJenisTenorChange = function(){
        $('select#jenis_tenor').on('change', function(){
            var tipe = $(this).val(),
                $selectJenisBayar = $('select#jenis_bayar');

            if(tipe == 1){
                $selectJenisBayar.empty();

                $selectJenisBayar.append('<option value="0" data-kali="0">Pilih..</option><option value="1" data-kali="1">Harian</option>');
                handleJenisBayarChange();
            }else if(tipe == 2){
                $selectJenisBayar.empty();

                $selectJenisBayar.append('<option value="0" data-kali="0">Pilih..</option><option value="1" data-kali="30">Harian</option><option value="2" data-kali="1">Bulanan</option>');
                handleJenisBayarChange();
            }else if(tipe == 3){
                $selectJenisBayar.empty();

                $selectJenisBayar.append('<option value="0" data-kali="0">Pilih..</option><option value="1" data-kali="365">Harian</option><option value="2" data-kali="12">Bulanan</option><option value="3" data-kali="1">Tahunan</option>');
                handleJenisBayarChange();
            }
        });
    }

    var handleJenisBayarChange = function(){
        $('select#jenis_bayar').on('change', function(){
            var kali = parseInt($('option:selected', this).attr('data-kali')),
                jml = parseInt($('input#lama_tenor').val());

            $('input#kelipatan').val(jml*kali);


        });
    }

    var addItemRow = function()
    {    
        var numRow = $('tbody tr', $tableDetailPembelian).length;

        console.log('numrow' + numRow);

        // if (numRow > 0 && ! isValidLastRow()) return;
        var 
            $rowContainer     = $('tbody', $tableDetailPembelian),
            $newItemRow       = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItemPembelian    = $('.search-item', $newItemRow);
            $btnSearchPermintaan = $('.search-jumlah', $newItemRow);

            $btnSearchPermintaan.attr('data-row', numRow);
            $btnSearchPermintaanTabel.attr('data-row', numRow);


        var jenis = $('input[name="is_single"]:checked').val();

        // alert(jenis);

        if(jenis == 1){
            $('input[name$="[jumlah]"]', $tableDetailPembelian).removeAttr('readonly');
            $('a.add-jumlah', $tableDetailPembelian).addClass('hidden');
        }if(jenis == 0){
            $('input[name$="[jumlah]"]', $tableDetailPembelian).attr('readonly');
            $('a.add-jumlah', $tableDetailPembelian).removeClass('hidden');
        }


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

        handleDatePickers();

        // handle delete btn
        handleBtnDeletePembelian( $('.del-this', $newItemRow) );

        // handle button search item
        handleBtnSearchItemPembelian($btnSearchItemPembelian);
        handleBtnSearchPermintaan($btnSearchPermintaan);
        
    };

    var isValidLastRow = function()
    {
        var 
            $itemCodeEls    = $('input[name$="[code]"]',$tableDetailPembelian),
            $qtyELs         = $('input[name$="[qty]"]',$tableDetailPembelian),
            itemCode        = $itemCodeEls.eq($qtyELs.length-1).val(),
            qty             = $qtyELs.eq($qtyELs.length-1).val() * 1
        ;

        return (itemCode != '' && qty > 0)
    }

   
    var handleBtnDeletePembelian = function($btn)
    {
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
            e.preventDefault();
        });
    };

    var handleBtnDeletePermintaan = function($btn)
    {
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableCariPermintaan);

        $btn.on('click', function(e){
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
                // if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableCariPermintaan).length == 0){
                    }
                    // focusLastItemCode();
                // }
            // });
            e.preventDefault();
        });
    };

    var calculateTotal = function()
    {
        var 
            $rows     = $('tbody>tr', $tableDetailPembelian), 
            $sub_total = $('.sub_total', $tableDetailPembelian),
            cost = 0,
            totalCost = 0,
            grandTotal = 0,
            grandTotalAll = 0,
            grandTotalAD = 0,
            grandTotalAT = 0
        ;

        $.each($rows, function(idx, row)
        {
            var 
                $row     = $(row), 
                itemCode = $('label[name$="[item_kode]"]', $row).text(),
                harga = parseFloat($('input[name$="[item_harga]"]', $row).val()),
                diskon     = parseFloat($('input[name$="[item_diskon]"]', $row).val()*1),
                jumlah     = parseFloat($('input[name$="[jumlah]"]', $row).val()*1)
            ;
                // alert($('input[name$="[item_harga]"]', $row).val());

            if (itemCode != '' ){
                cost = (harga*jumlah)-((harga*jumlah)*diskon/100);
                // alert(cost);
                totalCost = cost;
                
                $('label[name$="[item_sub_total]"]', $row).text(mb.formatRp(totalCost));
                $('input[name$="[item_sub_total]"]', $row).val(totalCost);

            }

        });

        $.each($sub_total, function(){
            // alert(parseFloat($(this).val()));
            grandTotal = grandTotal + parseFloat($(this).val());
        });

        $('input#total').val(mb.formatTanpaRp(grandTotal));
        $('input#total_hidden').val(grandTotal);

        grandTotalAD = grandTotal - parseFloat(grandTotal * parseFloat($('input#diskon').val())/100);

        $('input#total_after_disc').val(mb.formatTanpaRp(grandTotalAD));
        $('input#total_after_disc_hidden').val(grandTotalAD);

        grandTotalAT =  grandTotalAD + parseFloat(grandTotalAD * parseFloat($('input#pph').val())/100);       
        
        $('input#total_after_tax').val(mb.formatTanpaRp(grandTotalAT));
        $('input#total_after_tax_hidden').val(grandTotalAT);

        grandTotalAll =  grandTotalAT - parseFloat($('input#pembulatan').val());       
        $('input#grand_total').val(mb.formatTanpaRp(grandTotalAll));
        $('input#grand_total_hidden').val(grandTotalAll);
        
        dp = parseFloat($('input#dp').val());

        dp_nominal = (dp/100)*grandTotalAll;
        $('input#sisa_bayar').val(mb.formatTanpaRp(grandTotalAll-dp_nominal));
        $('input#sisa_bayar_hidden').val(grandTotalAll-dp_nominal);
        $('input#diskon_nominal').val(parseFloat(grandTotal * parseFloat($('input#diskon').val())/100));
        $('input#pph_nominal').val(parseFloat(grandTotalAD * parseFloat($('input#pph').val())/100));
        // $('#total_before_discount_hidden').val(totalCost);
    };
    var calculateTotalDiskon = function()
    {
        var 
            grandTotal = parseFloat($('input#total_hidden').val()),
            diskon_nominal = $('input#diskon_nominal').val();

            diskon_persen = parseFloat(diskon_nominal * 100)/grandTotal;
            diskon_persen = diskon_persen;


        $('input#diskon').val(diskon_persen);

        grandTotalAll = grandTotal - parseFloat(grandTotal * parseFloat($('input#diskon').val())/100);
        grandTotalAll =  grandTotalAll + parseFloat(grandTotalAll * parseFloat($('input#pph').val())/100);
        grandTotalAll =  grandTotalAll - parseFloat($('input#pembulatan').val());
        $('input#grand_total').val(mb.formatTanpaRp(grandTotalAll));
        $('input#grand_total_hidden').val(grandTotalAll);
        dp = parseFloat($('input#dp').val());

        dp_nominal = (dp/100)*grandTotalAll;
        $('input#sisa_bayar').val(mb.formatTanpaRp(grandTotalAll-dp_nominal));
        $('input#sisa_bayar_hidden').val(grandTotalAll-dp_nominal);

        // $('#total_before_discount_hidden').val(totalCost);
    };

    var calculateTotalPPh = function()
    {
        var 
            grandTotal = parseFloat($('input#total_hidden').val()),
            pph_nominal = $('input#pph_nominal').val();

            pph_persen = parseInt(pph_nominal * 100)/grandTotal;
            pph_persen = pph_persen;


        $('input#pph').val(pph_persen);

        grandTotalAll = grandTotal - parseFloat(grandTotal * parseFloat($('input#diskon').val())/100);
        grandTotalAll =  grandTotalAll + parseFloat(grandTotalAll * parseFloat($('input#pph').val())/100);
        grandTotalAll =  grandTotalAll - parseFloat($('input#pembulatan').val());
        $('input#grand_total').val(mb.formatTanpaRp(grandTotalAll));
        $('input#grand_total_hidden').val(grandTotalAll);
        dp = parseFloat($('input#dp').val());

        dp_nominal = (dp/100)*grandTotalAll;
        $('input#sisa_bayar').val(mb.formatTanpaRp(grandTotalAll-dp_nominal));
        $('input#sisa_bayar_hidden').val(grandTotalAll-dp_nominal);

        // $('#total_before_discount_hidden').val(totalCost);
    };

    var calculateTotalDP = function()
    {
        var grandTotal = parseFloat($('input#grand_total_hidden').val());

        dp = parseFloat($('input#dp').val());

        dp_nominal = (dp/100)*grandTotal;

        $('input#dp_nominal').val(dp_nominal);
        $('input#sisa_bayar').val(mb.formatTanpaRp(grandTotal-dp_nominal));
        $('input#sisa_bayar_hidden').val(grandTotal-dp_nominal);
    };

    var calculateTotalDPNominal = function()
    {
        var grandTotal = parseFloat($('input#grand_total_hidden').val());

        dp_nominal = parseFloat($('input#dp_nominal').val());

        dp = (dp_nominal*100)/grandTotal;

        $('input#dp').val(dp);
        $('input#sisa_bayar').val(mb.formatTanpaRp(grandTotal-dp_nominal));
        $('input#sisa_bayar_hidden').val(grandTotal-dp_nominal);
    };

    var calculateTotalBungaPersen = function()
    {
        var sisaBayar = parseFloat($('input#sisa_bayar_hidden').val());

        bunga_persen = parseFloat($('input#bunga_persen').val());
        kelipatan = parseFloat($('input#kelipatan').val());

        bunga_nominal = (bunga_persen/100)*sisaBayar;

        $('input#bunga_nominal').val(bunga_nominal);
        $('input#setoran').val((sisaBayar + bunga_nominal) / kelipatan);
        $('input#total_bayar').val(parseFloat($('input#setoran').val()) * kelipatan);
    };

    var calculateTotalBungaNominal = function()
    {
        var sisaBayar = parseFloat($('input#sisa_bayar_hidden').val());

        bunga_nominal = parseFloat($('input#bunga_nominal').val());
        kelipatan = parseFloat($('input#kelipatan').val());

        bunga_persen = (bunga_nominal*100)/sisaBayar;

        $('input#bunga_persen').val(bunga_persen);
        $('input#setoran').val((sisaBayar + bunga_nominal) / kelipatan);
        $('input#total_bayar').val(parseFloat($('input#setoran').val()) * kelipatan);

    };

    var handleBtnSearchItemPembelian = function($btn)
    {
        var rowId  = $btn.closest('tr').prop('id');
        // var rowStatus  = $btn.closest('tr').prop('class');
        console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '1024px', maxWidth: '1024px'});

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
            oTableItemSearch.api().ajax.url(baseAppUrl + 'listing_search_item/' + $('input#id_supplier').val()).load();
        });
    };

    var handleDataTableItems = function()
    {
        oTableItemSearch = $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_search_item/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'name' : 'item.kode kode', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },               
                ]
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
                $classrow   = $('.table_item_beli', $tableDetailPembelian),
                ItemIdAll = $('input[name$="[item_id]"]', $classrow),
                itemId = $(this).data('item').id
                ;        


           
                $itemId              = $('input[name$="[item_id]"]', $row);
                $itemKode            = $('input[name$="[item_kode]"]', $row);
                $itemNama            = $('input[name$="[item_nama]"]', $row);
                $itemSatuan          = $('select[name$="[satuan]"]', $row);
                $SearchSatuan        = $('input[name$="[satuan_nama]"]', $row),
                $SearchSatuanId      = $('input[name$="[satuan_id]"]', $row),
                $itemHarga           = $('input[name$="[item_harga]"]', $row),
                $itemHargaLama       = $('label[name$="[item_harga_lama]"]', $row);
                $itemStok            = $('input[name$="[stok]"]', $row);
                $itemLabelKode       = $('label[name$="[item_kode]"]', $row);
                $itemLabelNama       = $('label[name$="[item_nama]"]', $row);
                $itemLabelSyarat     = $('label[name$="[item_syarat]"]', $row);
                $itemLabelStok       = $('label[name$="[stok]"]', $row);
                $itemLabelHarga      = $('label[name$="[item_harga]"]', $row);
                $itemLabelTotal      = $('label[name$="[item_total]"]', $row);
                $btnSearchPermintaan = $('a.search-jumlah', $row);

                $('.search-item', $tableDetailPembelian).popover('hide');
                
                found = false;
                // $.each(ItemIdAll, function(idx, value){
                //     // alert(itemId);
                //     if(itemId == this.value)
                //     {
                //         found = true;
                //     }
                // });
                // console.log($itemId);      
                

                var primary = $(this).data('satuan_primary').id;
                var harga = $(this).data('item').harga;
                var name = $(this).data('satuan_primary').nama;
                var stok = $(this).data('info')[0].stok;
                // alert(name);

                $('a.add-jumlah', $row).attr('href', baseAppUrl + 'add_jumlah/'+rowId+'/'+$(this).data('item').id+'/'+primary);
                $('a.add-jumlah', $row).removeAttr('disabled');

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
                                
                            });
                            $itemSatuan.val(primary);
                            $itemHarga.val(parseInt(harga));
                            $itemHargaLama.val(parseInt(harga));
                            
                        },

                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });
                    if ($itemKode.val() == "") {
                        $itemId.val($(this).data('item').id);            
                        $itemKode.val($(this).data('item').item_kode);
                        $itemNama.val($(this).data('item').item_nama);
                        $SearchSatuan.val(name);
                        $SearchSatuanId.val(primary);
                        $itemLabelKode.text($(this).data('item').item_kode);
                        $itemLabelNama.text($(this).data('item').item_nama);
                        $itemLabelStok.text(stok);
                        $itemStok.val(stok);
                        $itemLabelSyarat.text($(this).data('item').min_order +'/'+ $(this).data('item').max_order);
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
                        $SearchSatuanId.val(primary);
                        $itemLabelKode.text($(this).data('item').item_kode);
                        $itemLabelNama.text($(this).data('item').item_nama);
                        $itemLabelStok.text(stok);
                        $itemStok.val(stok);
                        $itemLabelSyarat.text($(this).data('item').min_order +'/'+ $(this).data('item').max_order);
                        // $itemLabelHarga.text($(this).data('item').harga);
                        $btnSearchPermintaan.attr('data-id', $(this).data('item').id);
                        $btnSearchPermintaan.attr('data-satuan', primary);
                    }
                    
                }

                // $itemSatuan.on('change', function(){
                //     row = $(this).data('row');

                //     $btnSearchPermintaan.attr('data-satuan', $(this).val());

                //     $itemLabelHarga.text($(this).data('harga'));
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
            $itemSatuanNama      = $('input#items_satuan_id_' + row),
            $itemHarga         = $('input#items_harga_' + row),
            $itemHargaLama         = $('input#items_harga_lama_' + row),
            $itemLabelHarga    = $('label#items_hargaEl_' + row),
            $itemLabelSyarat   = $('label#item_syarat_'+ row);
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
            $itemSatuanNama.val($(this).val());
            $itemHarga.val($harga);
            $itemHargaLama.val($harga);
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

            $popContainer.css({minWidth: '1024px', maxWidth: '1024px'});

            if ($lastPopoverJumlahPesan != null) $lastPopoverJumlahPesan.popover('hide');

            $lastPopoverJumlahPesan = $btn;

            $popoverJumlahPesan.show();

            var idRow = $('tr#'+ rowId);

            $('label[name="satuan_alokasi"]').text($('input[name$="[satuan_nama]"]', idRow).val());
            $('a#tambah-link').attr('href', baseAppUrl+'daftar_link/'+$(this).data('id')+'/'+parseInt($(this).data('row'))+'/'+$(this).data('satuan'));

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverJumlahPesan);
            
            $('#table_search_permintaan > tbody').remove();
            var tbody = $('<tbody></tbody>');
            $('table#table_search_permintaan').append(tbody);
            $('table#table_search_permintaan > tbody').append($('div#tabel_simpan_data_'+parseInt($(this).data('row'))).html());



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
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false }              ]
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

            $popContainer.css({minWidth: '1150px', maxWidth: '1150px'});

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
        oTableSupplier = $tablePilihSupplier.dataTable({
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
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        
        $('#table_pilih_supplier_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_supplier_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tablePilihSupplier.on('draw.dt', function (){
            var $btnSelect = $('a.select-supplier', this);
            handlePilihSupplierSelect( $btnSelect );   
        });



        $popoverItemContent.hide();
    }

    var handlePilihSupplierSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $IdSupplier     = $('input[name="id_supplier"]'),
                $NamaSupplier   = $('input[name="nama_supplier"]'),
                $KontakSupplier = $('input[name="kontak_supplier"]'),
                $AlamatSupplier = $('textarea[name="alamat_supplier"]'),
                $EmailSupplier  = $('input[name="email_supplier"]'),
                $tipePembayaran = $('select[name="tipe_pembayaran"]'),              
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('.pilih-supplier').popover('hide');          

            var pembayaran = $(this).data('pembayaran');

            lama_tempo = '';
            $tipePembayaran.empty();
            $tipePembayaran.append($("<option></option>").attr("value", '').text('Pilih Pembayaran'));
            $.each(pembayaran, function(key, value) 
            {
                lama_tempo = '';
                if (value.lama_tempo != null) {
                    lama_tempo = value.lama_tempo + ' hari';
                };
                $tipePembayaran.append($("<option></option>").attr({"value": value.id, "data-tempo": value.lama_tempo}).text(value.nama + ' ' + lama_tempo));
            });
            
            $IdSupplier.val($(this).data('item').id);
            $NamaSupplier.val($(this).data('item').nama);
            $KontakSupplier.val($(this).data('item').kontak_person +' ('+ $(this).data('item').no_telp +')');
            $AlamatSupplier.val($(this).data('item').alamat+','+$(this).data('item').kelurahan+','+$(this).data('item').kecamatan+','+$(this).data('item').kota+','+$(this).data('item').propinsi+','+$(this).data('negara'));
            $EmailSupplier.val($(this).data('email').email); 

            e.preventDefault();
        });     
    };


    var handleBtnSearchPenerima = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '1024px', maxWidth: '1024px'});

            if ($lastPopoverPenerima != null) $lastPopoverPenerima.popover('hide');

            $lastPopoverPenerima = $btn;

            $popoverPenerimaContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPenerimaContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverPenerimaContent.hide();
            $popoverPenerimaContent.appendTo($('.page-content'));

            $lastPopoverPenerima = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableCabang = function() 
    {
        $tablePilihCabang.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_penerima_cabang/' ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        
        $('#table_pilih_cabang_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_cabang_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tablePilihCabang.on('draw.dt', function (){
            var $btnSelect = $('a.select-cabang', this);
            handlePilihCabangSelect( $btnSelect );
            
        } );

        $popoverPenerimaContent.hide();
    }

    var handlePilihCabangSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $IdPenerima       = $('input[name="id_penerima"]'),
                $NamaPenerima     = $('input[name="nama_penerima"]'),
                $KontakPenerima     = $('input[name="kontak_penerima"]'),
                $AlamatPenerima     = $('textarea[name="alamat_penerima"]'),
                $EmailPenerima     = $('input[name="email_penerima"]'),
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('.pilih-penerima-cabang').popover('hide');   

            if($(this).data('item').tipe == 2)
            {
                $btnSearchPermintaan.show();
                $btnSearchItemPembelian.attr('data-tipe', '2');

            }
            else
            {
                // $btnSearchPermintaan.addClass('hidden');
                $btnSearchItemPembelian.attr('data-tipe', '1');
            }       

            if($(this).data('item').penanggung_jawab != null){
                kontak = $(this).data('item').penanggung_jawab;
            }else{
                kontak = '-';
            }

            if($(this).data('item').no_telp != null){
                no_telp = ' ('+ $(this).data('item').no_telp +')';
            }else{
                no_telp ='';
            }
            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $IdPenerima.val($(this).data('item').id);
            $NamaPenerima.val($(this).data('item').nama_cabang);
            $KontakPenerima.val(kontak +no_telp);
            $AlamatPenerima.val($(this).data('item').alamat+', '+$(this).data('item').kelurahan+', '+$(this).data('item').kecamatan+', '+$(this).data('item').kota+', '+$(this).data('item').propinsi+', '+$(this).data('negara'));
            $EmailPenerima.val($(this).data('item').email);
            // alert($itemIdEl.val($(this).data('item').id));

            e.preventDefault();
        });     
    };

    var handleBtnSearchPenerimaCustomer = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '1024px', maxWidth: '1024px'});

            if ($lastPopoverPenerimaCustomer != null) $lastPopoverPenerimaCustomer.popover('hide');

            $lastPopoverPenerimaCustomer = $btn;

            $popoverPenerimaContentCustomer.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPenerimaContentCustomer);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverPenerimaContentCustomer.hide();
            $popoverPenerimaContentCustomer.appendTo($('.page-content'));

            $lastPopoverPenerimaCustomer = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableCustomer = function() 
    {
        $tablePilihCustomer.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_penerima_customer/' ,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        
        $('#table_pilih_customer_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_customer_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

         $tablePilihCustomer.on('draw.dt', function (){
            var $btnSelect = $('a.select-customer', this);
            handlePilihCustomerSelect( $btnSelect );
            
        } );

        $popoverPenerimaContentCustomer.hide();
    }

    var handlePilihCustomerSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $IdPenerima       = $('input[name="id_penerima"]'),
                $NamaPenerima     = $('input[name="nama_penerima"]'),
                $KontakPenerima     = $('input[name="kontak_penerima"]'),
                $AlamatPenerima     = $('textarea[name="alamat_penerima"]'),
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('.pilih-penerima-customer').popover('hide');  

            if($(this).data('tipe') == 1)
            {
                $btnSearchPermintaan.addClass('hidden');
            }        

            // console.log($itemIdEl)
            if($(this).data('item').orang_bersangkutan != null){
                kontak = $(this).data('item').orang_bersangkutan;
            }else{
                kontak = '-';
            }

            if($(this).data('item').no_telp != null){
                no_telp = ' ('+ $(this).data('item').no_telp +')';
            }else{
                no_telp ='';
            }

            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $IdPenerima.val($(this).data('item').id);
            $NamaPenerima.val($(this).data('item').nama_customer);
            $KontakPenerima.val(kontak + no_telp);
            $AlamatPenerima.val($(this).data('item').alamat+', '+$(this).data('item').kelurahan+', '+$(this).data('item').kecamatan+', '+$(this).data('item').kota+', '+$(this).data('item').propinsi+', '+$(this).data('item').negara);
          
            // alert($itemIdEl.val($(this).data('item').id));

            e.preventDefault();
        });     
    };


    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true,
                format : 'dd M yyyy'
            });

            $('.date').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true,
                format : 'dd M yyyy'
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }

        /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
    }

    var handleDateTimePickers = function () {

        $('.tanggal').datetimepicker({
            rtl: Metronic.isRTL(),
            orientation: "left",
            autoclose: true,
            format : 'dd MM yyyy hh:ii',

        });
    }

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            if (! $form.valid()) return;

            var i = 0;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses...'});
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

    var handleConfirmSaveDraft = function(){
        $('a#confirm_save_draft', $form).click(function() {
            if (! $form.valid()) return;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                $('input#save_draft').val(1);
                    $('#save', $form).click();
                }
            });
        });
    };

    function handleUploadify(counter)
    {
        counter = parseInt(counter) - 1;
        var ul = $('#upload_'+counter+' ul');

       
        // Initialize the jQuery File Upload plugin
        $('#pdf_file_'+counter).fileupload({

            // This element will accept file drag/drop uploading
            dropZone: $('#drop_'+counter),
            dataType: 'json',
            // This function is called when a file is added to the queue;
            // either via the browse button, or via drag/drop:
            add: function (e, data) {

                tpl = $('<li class="working"><div class="thumbnail"></div><span></span></li>');

                // Initialize the knob plugin
                tpl.find('input').knob();

                // Listen for clicks on the cancel icon
                tpl.find('span').click(function(){

                    if(tpl.hasClass('working')){
                        jqXHR.abort();
                    }

                    tpl.fadeOut(function(){
                        tpl.remove();
                    });

                });

                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
            },
            done: function(e, data){

            var filename = data.result.filename;
                var filename = filename.replace(/ /g,"_");
                // alert(filename);
                // tpl.find('div.thumbnail').html('<a class="fancybox-button" href="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseUrl()+'assets/mb/var/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a>');
                $('input#penawaran_url_' + counter ).attr('value',filename);
                // Add the HTML to the UL element
                // ul.html(tpl);
                // data.context = tpl.prependTo(ul);

                // handleFancybox();
                Metronic.unblockUI();
                    // data.context = tpl.prependTo(ul);

            },

            progress: function(e, data){

                // Calculate the completion percentage of the upload
                Metronic.blockUI({boxed: true});
            },


            fail:function(e, data){
                // Something has gone wrong!
                alert(data.result.filename);
                bootbox.alert('File Tidak Dapat Diupload');
                Metronic.unblockUI();
            }
        });


        // Prevent the default action when a file is dropped on the window
        $(document).on('drop dragover', function (e) {
            e.preventDefault();
        });

        // Helper function that formats the file sizes
        function formatFileSize(bytes) {
            if (typeof bytes !== 'number') {
                return '';
            }

            if (bytes >= 1000000000) {
                return (bytes / 1000000000).toFixed(2) + ' GB';
            }

            if (bytes >= 1000000) {
                return (bytes / 1000000).toFixed(2) + ' MB';
            }

            return (bytes / 1000).toFixed(2) + ' KB';
        } 
    }
    
    function handleFancybox() {
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

    var handleBtnAddPenawaran = function($btn) {
        $btn.click(function() {
            addPenawaranRow();
        });
    };

    var handleBtnDeletePenawaran = function($btn)
    {
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePenawaran);

        $btn.on('click', function(e){
            $row.remove();
            if($('tbody>tr', $tablePenawaran).length == 0){
                addPenawaranRow();
            }
            e.preventDefault();
        });
    };

    var handleChangeJenisKirim = function(){
        $('input[name="is_single"]').on('change', function(){

            var single = $(this).val();

            if(single == 1){
                $('div#tgl_kirim').removeClass('hidden');
                $('input#tanggal_kirim').attr('required','required');
                $('input[name$="[jumlah]"]', $tableDetailPembelian).removeAttr('readonly');
                $('a.add-jumlah', $tableDetailPembelian).addClass('hidden');
            }if(single == 0){
                $('div#tgl_kirim').addClass('hidden');
                $('input#tanggal_kirim').removeAttr('required');
                $('input[name$="[jumlah]"]', $tableDetailPembelian).attr('readonly','readonly');
                $('a.add-jumlah', $tableDetailPembelian).removeClass('hidden');
            }
        });
    };


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/pembelian/';
        initform();
        handleDatePickers();
        handleDataTableSupplier();
        handleDataTableCabang();
        handleDataTableCustomer();
        handleDataTableItems();
        handleConfirmSave();
        handleConfirmSaveDraft();
        handleDateTimePickers();
        handleChangeJenisKirim();
        handleFancybox();
    };
 }(mb.app.daftar_permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_permintaan_po.init();
});