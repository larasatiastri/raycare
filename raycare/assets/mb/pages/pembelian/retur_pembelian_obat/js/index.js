mb.app.retur_pembelian_obat = mb.app.retur_pembelian_obat || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_add_retur_pembelian_obat'),
        $tableAddAccount        = $('#table_daftar_tindakan', $form),
        $popoverPenjualanContent = $('#popover_item_content'), 
        $popoverItemContent   = $('#popover_item_content'),
        $lastPopoverPenjualan    = null,
        $lastPopoverItem      = null,
        $tablePilihPembelian     = $('#table_pembelian'),
        $tableTambahItem      = $('#tabel_tambah_item',$form),
        $tablePaketSearch     = $('#table_pilih_paket'),
        $tableItemSearch      = $('#table_pilih_item'),
        $tablePaketSearch     = $('#table_pilih_paket'),
        $tableTindakanSearch  = $('#table_pilih_tindakan'),
        tplItemRow            = $.validator.format($('#tpl_item_row',$form).text()),
        tplFormPayment          = '<li class="fieldset">' + $('#tpl-form-payment', $form).val() + '<hr></li>',
        regExpTpl               = new RegExp('_ID_0', 'g'),   // 'g' perform global, case-insensitive
        paymentCounter          = 0,
        itemCounter           = 0;

    var initForm = function(){

        var $btnSearchPembelian  = $('.pilih-pembelian', $form);
        handleBtnSearchPembelian($btnSearchPembelian);

        $('select#supplier_id').select2();

        handleDatePickers();
        

    };

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
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

    var handleBtnSearchPembelian = function($btn){
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

            if ($lastPopoverPenjualan != null) $lastPopoverPenjualan.popover('hide');

            $lastPopoverPenjualan = $btn;

            $popoverPenjualanContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPenjualanContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPenjualanContent ke .page-content
            $popoverPenjualanContent.hide();
            $popoverPenjualanContent.appendTo($('.page-content'));

            $lastPopoverPenjualan = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handlePilihPenjualan = function(){
        oTablePembelian = $tablePilihPembelian.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pembelian/' + $('select#supplier_id').val(),
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'pembelian.id id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name':'pembelian.tanggal_pesan tanggal_pesan','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pembelian.no_pembelian no_po','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'supplier.nama nama_sup','visible' : true, 'searchable': false, 'orderable': false },
                { 'name':'pembelian.id id','visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#table_pilih_pembelian_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_pembelian_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        //var $btnSelects = $('a.select-pembelian', $tablePilihPembelian);
        //handlePilihPembelianSelect( $btnSelects );

        $tablePilihPembelian.on('draw.dt', function (){
            var $btnSelect = $('a.select-pembelian', this);
            handlePilihPembelianSelect( $btnSelect );
            
        });

        $popoverPenjualanContent.hide();        
    };

    var handlePilihPembelianSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $tableItem  = $('table#tabel_tambah_item tbody'),
                detailItem = $(this).data('item_detail');

            $('input#pembelian_id').val($(this).data('item').id);
            $('input#no_pembelian').val($(this).data('item').no_po);
            $('input#tanggal_pesan').val($(this).data('item').tanggal_pesan);

            var xhtml = '';
            $.each(detailItem, function(idx, detail){
                xhtml += '<tr>';
                xhtml += '<td><input type="hidden" name="item['+idx+'][item_id]" class="form-control" value="'+detail.item_id+'">'+detail.kode_item+'</td>';
                xhtml += '<td><input type="hidden" name="item['+idx+'][po_id]" class="form-control" value="'+detail.po_id+'">'+detail.nama_item+'</td>';
                xhtml += '<td><input type="hidden" name="item['+idx+'][po_detail_id]" class="form-control" value="'+detail.po_detail_id+'">'+detail.nama_satuan+'</td>';
                xhtml += '<td><input type="hidden" name="item['+idx+'][item_satuan_id]" class="form-control" value="'+detail.item_satuan_id+'">'+detail.bn_sn_lot+'</td>';
                xhtml += '<td><input type="hidden" name="item['+idx+'][bn_sn_lot]" class="form-control" value="'+detail.bn_sn_lot+'">'+detail.expire_date+'</td>';
                xhtml += '<td><input type="hidden" name="item['+idx+'][expire_date]" class="form-control" value="'+detail.expire_date+'">'+detail.jumlah_diterima+'</td>';
                xhtml += '<td><input type="hidden" name="item['+idx+'][harga_beli_primary]" class="form-control" value="'+detail.harga_beli_primary+'">'+mb.formatRp(parseFloat(detail.harga_beli_primary))+'</td>';
                xhtml += '<td><input type="number" name="item['+idx+'][jml_retur]" class="form-control" min="0" max="'+detail.jumlah_diterima+'" value="0"></td>';
                xhtml += '<td><input type="hidden" name="item['+idx+'][sub_total]" class="form-control" class="subtotal"><label name="item['+idx+'][sub_total]" >'+mb.formatRp(parseInt(0))+'</label></td>';
                xhtml += '</tr>';
            });

            $tableItem.html(xhtml);

            $('input[name$="[jml_retur]"]', $tableItem).on('change keyup', function(){
                calculateTotal();

            });
                

            e.preventDefault();
            $('.pilih-pembelian', $form).click();
        });    
    };

    var handleChangeSupplier = function(){
        $('select#supplier_id').on('change', function(){
            oTablePembelian.api().ajax.url(baseAppUrl +  'listing_pembelian/' + $(this).val() ).load();
        });
    }

    var calculateTotal = function()
    {
        var 
            $tableItem  = $('table#tabel_tambah_item'),
            $rows     = $('tbody>tr', $tableItem), 
            $sub_total = $('input[name$="[sub_total]"]', $tableItem),
            cost = 0,
            grandTotal = 0
        ;

        $.each($rows, function(idx, row)
        {
            var 
                $row     = $(row), 
                itemCode = $('input[name$="[item_id]"]', $row).val(),
                harga = parseFloat($('input[name$="[harga_beli_primary]"]', $row).val()),
                jumlah     = parseFloat($('input[name$="[jml_retur]"]', $row).val()*1)
            ;
                // alert($('input[name$="[item_harga]"]', $row).val());

                cost = harga*jumlah;
                // alert(cost);
                totalCost = cost;
                $('input[name$="[sub_total]"]', $row).val(totalCost);
                $('input[name$="[sub_total]"]', $row).attr('value', totalCost);
                $('label[name$="[sub_total]"]', $row).text(mb.formatRp(totalCost));

            

        });

        $.each($sub_total, function(){
            // alert(parseFloat($(this).val()));
            grandTotal = grandTotal + parseFloat($(this).val());
        });

        $('th#total').text(mb.formatRp(grandTotal));
        $('input#total_hidden').val(grandTotal);
        $('input#total_hidden').attr('value',grandTotal);

    };

    var handleJwertyEnter = function($nopasien){

        jwerty.key('enter', function() {
            
            var NomorPasien = $nopasien.val();

            searchPasienByNomorAndFill(NomorPasien);

            // cegah ENTER supaya tidak men-trigger form submit
            return false;

        }, this, $nopasien );
    }

    var searchPasienByNomorAndFill = function(NomorPasien)
    {
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'search_pasien_by_nomor',
            data     : {no_pasien:NomorPasien},   
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
            },
            success : function(result){
                if(result.success === true)
                {
                    var $namaRefPasien     = $('input[name="nama_ref_pasien"]'),
                        $IdRefPasien       = $('input[name="id_ref_pasien"]'),
                        $noRekmedPasien    = $('input[name="no_rekmed"]');

                    var data = result.rows;

                    $noRekmedPasien.val(data.no_ktp);
                    $IdRefPasien.val(data.id);
                    $namaRefPasien.val(data.nama);

                    pekerjaan = '-';
                    if(data.nama_pekerjaan !== null)
                    {
                        pekerjaan = data.nama_pekerjaan;
                    }

                    $('#label_nama_pasien').text(data.nama);
                    $('#label_umur_pasien').text(parseInt(data.umur)+' Tahun');
                    $('#label_alamat_pasien').text(data.alamat);
                    $('#label_pekerjaan_pasien').text(pekerjaan);


                }
                else if(result.success === false)
                {
                    mb.showMessage('error',result.msg,'Informasi');
                    $('input#no_member').focus();
                }
            },
            complete : function()
            {
                Metronic.unblockUI();
            }
        });
    }

    function addItemRowPaket()
    {
        var numRow = $('tbody tr', $tableTambahItem).length;

        if (numRow > 0 && ! isValidLastRow()) return;
        var 
            $rowContainer         = $('tbody', $tableTambahItem),
            $newItemRow           = $(tplItemRow(itemCounter++)).appendTo( $rowContainer )
            ;
        $('input[name$="[kode]"]', $newItemRow).focus();

        $btnSearchItem = $('button.search-item', $newItemRow);
        handleBtnSearchItem($btnSearchItem);
        handleCountSubTotalItem();
        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
      
    };
    

     
    var handleTambahRow = function()
    {
        $('a.add-item').click(function() {
            addItemRowPaket();
        });
    };

    var handleBtnDelete = function($btn)
    {
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableTambahItem)

        $btn.on('click', function(e){            
            $row.remove();
            handleCountSubTotalItem();
            if($('tbody>tr', $tableTambahItem).length == 0){
                addItemRowPaket();
                handleCountSubTotalItem();
            }
            e.preventDefault();
        });
    };

    var isValidLastItemRow = function()
    {      
        var 
            $itemNotes = $('input[name$="[name]"]', $tableTambahItem ),
            itemNote    = $itemNotes.val()           
        
        return (itemNote != '');
    };
    
    var handleBtnSearchItem = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '640px', maxWidth: '420px'});

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

    var handleDataTableItems = function(){
        oTable = $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'item.id id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'item.nama nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_pilih_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tableItemSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);        
            handleItemSelect( $btnSelect );       
        });
            
         
    };

    
    var handleItemSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');
            var 
                $parentPop   = $(this).parents('.popover').eq(0),
                rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row         = $('#'+rowId, $tableTambahItem),
                $rowClass    = $('.row_item', $tableTambahItem);                
           
                $itemIdEl       = $('input[name$="[item_id]"]', $row);
                $itemCodeIn     = $('input[name$="[kode]"]', $row);
                $itemNameIn     = $('input[name$="[name]"]', $row);
                $itemSatuanEl    = $('select[name$="[satuan_id]"]', $row);
                $itemQtyEl    = $('input[name$="[qty]"]', $row);
                $btnAddIdentitas    = $('button.add-identitas', $row);

                itemId = $(this).data('item')['id'];
                isIdentitas = $(this).data('item')['is_identitas'];
                    
                $itemIdEl.val($(this).data('item')['id']);
                $itemCodeIn.val($(this).data('item')['kode']);
                $itemNameIn.val($(this).data('item')['nama']);
                $itemQtyEl.val(0);

                $gudangId = $('select#gudang_id');

                harga_item = 0;
                if($(this).data('harga')['harga'] == undefined){
                    harga_item = 0;
                }else{
                    harga_item = parseInt($(this).data('harga')['harga']);
                }
                            
                $('div#item_harga', $row).text(mb.formatRp(harga_item));
                $('div#item_sub_total', $row).text(mb.formatRp(0*harga_item));
                $('input[name$="[harga]"]', $row).val(harga_item);
                $('input[name$="[sub_total]"]', $row).val(0*harga_item);
                

                var satuan = $(this).data('satuan'),
                    primary = $(this).data('satuan_primary');

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_inventory',
                    data     : {item_id:itemId, item_satuan_id : primary.id, gudang_id : $gudangId.val()},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {

                        if(results.success == true){
                            $('button.add-identitas', $row).removeAttr('disabled');
                        }else{
                            $('button.add-identitas', $row).attr('disabled','disabled');
                        }
                       
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
                
                $itemSatuanEl.empty();
                $.each(satuan, function(key, value) {
                    $itemSatuanEl.append($("<option></option>").attr("value", value.id).text(value.nama));
                    $itemSatuanEl.val(primary.id);
                });

                $('button.search-item', $tableTambahItem).popover('hide');

                handleCountSubTotalItem();

                if(isIdentitas == 0){
                    $btnAddIdentitas.hide();
                    $('input[name$="[qty]"]', $row).removeAttr('readonly');
                }else{
                    $btnAddIdentitas.show();
                    $('input[name$="[qty]"]', $row).attr('readonly','readonly');
                }

                $btnAddIdentitas.attr('href',baseAppUrl+'add_identitas/'+rowId+'/'+itemId+'/'+$itemSatuanEl.val() + '/' + $gudangId.val());
               
                $itemSatuanEl.removeAttr('disabled');

                $itemSatuanEl.on('change', function(){
                    var id = $(this).val();

                    $btnAddIdentitas.attr('href',baseAppUrl+'add_identitas/'+rowId+'/'+itemId+'/'+id+ '/' + $gudangId.val());
                    $('input[name$="[qty]"]', $row).val(0);
                    $('div#identitas_row', $row).html('');

                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'get_inventory',
                        data     : {item_id:itemId, item_satuan_id : $(this).val(), gudang_id : $gudangId.val()},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {

                            if(results.success == true){
                                $('button.add-identitas', $row).removeAttr('disabled');
                            }else{
                                $('button.add-identitas', $row).attr('disabled','disabled');
                            }
                           
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });

                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'get_harga',
                        data     : {item_id:itemId, item_satuan_id : id},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            
                            var harga = 0;

                            if(results.success == true){
                                harga = parseInt(results.harga);

                            }

                            $('div#item_harga', $row).text(mb.formatRp(harga));
                            $('div#item_sub_total', $row).text(mb.formatRp(harga));
                            $('input[name$="[harga]"]', $row).val(harga);
                            $('input[name$="[sub_total]"]', $row).val(harga);
                            handleCountSubTotalItem();
                           
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });

                });
                addItemRowPaket();

            e.preventDefault();   
        });     
    };

    var handleCountSubTotalItem = function() {
        var $itemSubTotIn = $('input[name$="[sub_total]"]', $tableTambahItem);

        var grand_total = 0;
        $.each($itemSubTotIn, function(idx, subTot){
            var sub_total = parseInt($(this).val());

            grand_total = grand_total + sub_total;

        });

        $('th#total', $tableTambahItem).text(mb.formatRp(grand_total));
        $('input#total_hidden', $tableTambahItem).val(grand_total);


        var $diskon = $('input#diskon', $tableTambahItem);

        if($diskon.val() == ''){
            total_all = grand_total - parseInt(0);
        }else{
            total_all = grand_total - parseInt($diskon.val());
        }
        

        $('th#grand_total', $tableTambahItem).text(mb.formatRp(total_all));
        $('input#grand_total_hidden', $tableTambahItem).val(total_all);  
        $('input[name$="[nominal]"]', $form).val(total_all);  

    };

    var handleDiskon = function(){
        var $diskon = $('input#diskon', $tableTambahItem);

        total_all = 0;
        $('input#diskon', $tableTambahItem).on('change', function() {

            grand_total = $('input#total_hidden', $tableTambahItem).val(); 
            total_all = grand_total - parseInt($(this).val());

            $('th#grand_total', $tableTambahItem).text(mb.formatRp(total_all));
            $('input#grand_total_hidden', $tableTambahItem).val(total_all);
            $('input[name$="[nominal]"]', $form).val(total_all);  
            
        });


    }

    var handleTerdaftar = function()
    {
        $('a#btn_terdaftar').on('click', function(){
            $('a#btn_tidak_terdaftar').removeClass('btn-primary');
            $('a#btn_tidak_terdaftar').addClass('btn-default');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');

            $('input#id_ref_pasien').attr('required','required');
            $('input#no_rekmed').attr('required','required');


            $('div.pasien_terdaftar').removeClass('hidden');
            $('input#nama_ref_pasien').attr('readonly','readonly');
            $('textarea#alamat_pasien').attr('readonly','readonly');
            $('input#tipe_pasien').val(1);
        });

        $('a#btn_tidak_terdaftar').on('click', function(){
            $('a#btn_terdaftar').removeClass('btn-primary');
            $('a#btn_terdaftar').addClass('btn-default');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');

            $('input#id_ref_pasien').removeAttr('required');
            $('input#no_rekmed').removeAttr('required');


            $('div.pasien_terdaftar').addClass('hidden');
            $('input#nama_ref_pasien').removeAttr('readonly');
            $('textarea#alamat_pasien').removeAttr('readonly');
            $('input#tipe_pasien').val(2);
        });
    }

    var isValidLastRow = function()
    {
        var 
            $itemCodeEls    = $('input[name$="[kode]"]',$tableTambahItem),
            $qtyELs         = $('input[name$="[qty]"]',$tableTambahItem),
            itemCode        = $itemCodeEls.eq($qtyELs.length-1).val(),
            qty             = $qtyELs.eq($qtyELs.length-1).val() * 1
        ;

        return (itemCode != '')
    }

    var handleUploadify = function()
    {
        var ul = $('#upload ul');

       
        // Initialize the jQuery File Upload plugin
        $('#upl').fileupload({

            // This element will accept file drag/drop uploading
            dropZone: $('#drop'),
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
                var filetype = data.files[0].type;

                if(filetype == 'image/jpeg' || filetype == 'image/png' || filetype == 'image/gif')
                {
                    tpl.find('div.thumbnail').html('<a class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button"><img src="'+mb.baseDir()+'cloud/temp/'+filename+'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>');
                }
                else
                {
                    tpl.find('div.thumbnail').html('<a target="_blank" class="fancybox-button" title="'+filename+'" href="'+mb.baseDir()+'cloud/temp/'+filename+'" data-rel="fancybox-button">'+filename+'</a>');
                }
                
                $('input#url').attr('value',filename);
                // // Add the HTML to the UL element
                ul.html(tpl);
                // data.context = tpl.appendTo(ul);
                
                handleFancybox();
                Metronic.unblockUI();

                    // data.context = tpl.appendTo(ul);

            },

            progress: function(e, data){

                // Calculate the completion percentage of the upload
                Metronic.blockUI({boxed: true});
            },


            fail:function(e, data){
                // Something has gone wrong!
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
        baseAppUrl = mb.baseUrl() + 'pembelian/retur_pembelian/';
        handleValidation();
        handleConfirmSave();
        handlePilihPenjualan();
        handleChangeSupplier();
        handleDataTableItems();
        initForm();
    };
 }(mb.app.retur_pembelian_obat));


// initialize  mb.app.home.table
$(function(){
    mb.app.retur_pembelian_obat.init();
});