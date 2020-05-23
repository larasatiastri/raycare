mb.app.buat_invoice = mb.app.buat_invoice || {};
mb.app.buat_invoice.edit = mb.app.buat_invoice.edit || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_edit_invoice'),
        $popoverPasienContent = $('#popover_pasien_content'), 
        $popoverItemContent   = $('#popover_item_content'),
        $lastPopoverPasien    = null,
        $lastPopoverItem      = null,
        $tablePilihPasien     = $('#table_pilih_pasien'),
        $tableTambahItem      = $('#tabel_tambah_item',$form),
        $tablePaketSearch     = $('#table_pilih_paket'),
        $tableItemSearch      = $('#table_pilih_item'),
        $tablePaketSearch     = $('#table_pilih_paket'),
        $tableTindakanSearch  = $('#table_pilih_tindakan'),
        tplItemRow            = $.validator.format($('#tpl_item_row',$form).text()),
        itemCounter           = $('input#jumlah_data').val();

    var initForm = function(){

        var $btnSearchPasien  = $('.pilih-pasien', $form);
        handleBtnSearchPasien($btnSearchPasien);
        
        handleTambahRow();

        handleBtnDeleteDb( $('.del-this-db', $tableTambahItem) );
        handleSelectSatuan( $('.satuan_db', $tableTambahItem) );

        $('select#jenis_invoice', $form).on('change', function(){
            jenis = $(this).val();

            if(jenis == 2){
                $('input[name$="[harga]"]', $tableTambahItem ).removeAttr('readonly');
            }else{
                $('input[name$="[harga]"]', $tableTambahItem ).attr('readonly','readonly');
            }
        });

        $('input[name="penanggung"]', $form).on("change", function(){
            var value = $(this).val();

            if(value == 1){
                oTablePaket.api().ajax.url(baseAppUrl +  'listing_paket/1').load();
            }
            if(value == 2){
                oTablePaket.api().ajax.url(baseAppUrl +  'listing_paket/2').load();
            }
        });

        var value_default = $('input[name="penanggung"]', $form).val();

            if(value_default == 1){
                oTablePaket.api().ajax.url(baseAppUrl +  'listing_paket/1').load();
            }
            if(value_default == 2){
                oTablePaket.api().ajax.url(baseAppUrl +  'listing_paket/2').load();
            }
    };

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

    var handleBtnSearchPasien = function($btn){
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

            if ($lastPopoverPasien != null) $lastPopoverPasien.popover('hide');

            $lastPopoverPasien = $btn;

            $popoverPasienContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverPasienContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverPasienContent.hide();
            $popoverPasienContent.appendTo($('.page-content'));

            $lastPopoverPasien = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handlePilihPasien = function(){
        $tablePilihPasien.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_pasien',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'pasien.id id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name':'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'pasien.tanggal_lahir tanggal_lahir','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'pasien.nama nama','visible' : true, 'searchable': false, 'orderable': false }
                ]
        });       
        $('#table_pilih_pasien_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_pasien_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        var $btnSelects = $('a.select', $tablePilihPasien);
        handlePilihPasienSelect( $btnSelects );

        $tablePilihPasien.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handlePilihPasienSelect( $btnSelect );
            
        } );

        $popoverPasienContent.hide();        
    };

    var handlePilihPasienSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $namaRefPasien   = $('input[name="nama_ref_pasien"]'),
                $IdRefPasien   = $('input[name="id_ref_pasien"]'),
                $noRekmedPasien   = $('input[name="no_rekmed"]'),
                $itemCodeEl = null,
                $itemNameEl = null
                ;        


            $('.pilih-pasien', $form).popover('hide');          

            pekerjaan = '-';
            if($(this).data('item').nama_pekerjaan !== null)
            {
                pekerjaan = $(this).data('item').nama_pekerjaan;
            }

            $noRekmedPasien.val($(this).data('item').no_ktp);
            $IdRefPasien.val($(this).data('item').id);
            $namaRefPasien.val($(this).data('item').nama);


            $('#label_nama_pasien').text($(this).data('item').nama);
            $('#label_umur_pasien').text(parseInt($(this).data('item').umur)+' Tahun');
            $('#label_alamat_pasien').text($(this).data('item').alamat);
            $('#label_pekerjaan_pasien').text(pekerjaan);

            e.preventDefault();
        });     
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



    function addItemRow()
    {
        if(! isValidLastItemRow() ) return;

        var numRow = $('tbody tr', $tableTambahItem).length;
        var 
            $rowContainer         = $('tbody', $tableTambahItem),
            $newItemRow           = $(tplItemRow(itemCounter++)).appendTo( $rowContainer )
            ;
        $('input[name$="[kode]"]', $newItemRow).focus();
        $('input[name$="[id]"]', $newItemRow).val('');
        $('input[name$="[tipe_item]"]', $newItemRow).val('');
        $('input[name$="[kode]"]', $newItemRow).val('');
        $('input[name$="[name]"]', $newItemRow).val('');
        $('input[name$="[qty]"]', $newItemRow).val('');
        $('input[name$="[harga]"]', $newItemRow).val('');
        $('select[name$="[tipe]"]', $newItemRow).val('');
        $('input[name$="[sub_total]"]', $newItemRow).val('');


        $btnSearchItem = $('button.search-item', $newItemRow);
        
        handleBtnSearchItem($btnSearchItem);
        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
      
    };

     
    var handleTambahRow = function()
    {
        $('a.add-item').click(function() {
            addItemRow();
        });
    };

    var handleBtnDelete = function($btn)
    {
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableTambahItem);

        $btn.on('click', function(e){  

            $row.remove();    
            
            if($('tbody>tr', $tableTambahItem).length == 0){
                addItemRow();
            }
            e.preventDefault();
        });
    };

    var handleBtnDeleteDb = function($btn)
    {

        $btn.on('click', function(e){  
            var 
                index = $(this).data('index'),
                $row  = $('#item_row_'+index, $tableTambahItem),
                id_db = $(this).data('id');

            if(id_db != undefined)
            {
                var msg = $(this).data('confirm');

                bootbox.confirm(msg, function(result){
                    if(result == true)
                    {
                        $('input[name$="[is_deleted]"]', $row).attr('value',1);    
                        $row.hide();
                    }
                });                
            }
            if($('tbody>tr', $tableTambahItem).length == 0){
                addItemRow();
            }
            e.preventDefault();
        });
    };

    var handleSelectSatuan = function($select){
        $select.on('change', function(){
            var id = $(this).val(),
                index = $(this).data('index'),
                $row  = $('#item_row_'+index, $tableTambahItem),
                $itemQtyIn = $('input[name$="[qty]"]', $row);


            itemId = $('input[name$="[id]"]', $row).val();
            //alert(itemId);

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_harga',
                data     : {item_id:itemId, item_satuan_id : id},
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    
                    var harga = parseInt(results.harga);

                    $('input[name$="[harga]"]', $row).val(harga);
                    $('input[name$="[sub_total]"]', $row).val(harga * $itemQtyIn.val());
                    
                    
                   
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });

        });
    }

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
                { 'name' : 'item.nama nama','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_pilih_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tableItemSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);        
            handleItemSelect( $btnSelect );       
        });
            
        $popoverItemContent.hide();        
    };

    var handleDataTablePaket = function(){
        oTablePaket = $tablePaketSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_paket/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'paket.id id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'paket.nama nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'paket.nama nama','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_pilih_paket_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_paket_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tablePaketSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);        
            handlePaketSelect( $btnSelect );       
        });
            
        $popoverItemContent.hide();        
    };

    var handleDataTableTindakan = function(){
        oTableTindakan = $tableTindakanSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_tindakan/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'tindakan.id id','visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'tindakan.nama nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'tindakan.nama nama','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
        $('#table_pilih_tindakan_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_tindakan_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $tableTindakanSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);        
            handleItemSelect( $btnSelect );       
        });
            
        $popoverItemContent.hide();        
    };

    var handleItemSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');
            var 
                $parentPop   = $(this).parents('.popover').eq(0),
                rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row         = $('#'+rowId, $tableTambahItem),
                $rowClass    = $('.row_item', $tableTambahItem);                
           
                $itemIdEl     = $('input[name$="[id]"]', $row);
                $itemCodeIn   = $('input[name$="[kode]"]', $row);
                $itemNameIn   = $('input[name$="[name]"]', $row);
                $itemHargaIn  = $('input[name$="[harga]"]', $row);
                $itemQtyIn    = $('input[name$="[qty]"]', $row);
                $itemSubTotIn = $('input[name$="[sub_total]"]', $row);
                $itemTipeItemIn = $('input[name$="[tipe_item]"]', $row);
                $itemSatuanEl    = $('select[name$="[satuan_id]"]', $row);

                itemId = $(this).data('item')['id'];

                $itemIdEl.val($(this).data('item')['id']);
                $itemCodeIn.val($(this).data('item')['kode']);
                $itemNameIn.val($(this).data('item')['nama']);
                $itemHargaIn.val($(this).data('item')['harga']);
                $itemQtyIn.val(1);
                $itemSubTotIn.val(1 * ($(this).data('item')['harga']));
                $itemTipeItemIn.val($(this).data('item')['tipe_item']);
                var satuan = $(this).data('satuan'),
                    primary = $(this).data('satuan_primary');
                
                $itemSatuanEl.empty();
                $.each(satuan, function(key, value) {
                    $itemSatuanEl.append($("<option></option>").attr("value", value.id).text(value.nama));
                    $itemSatuanEl.val(primary.id);
                });

                $itemHargaIn.val($(this).data('item')['harga']);
                $itemQtyIn.val(1);
                $itemSubTotIn.val(1 * ($(this).data('item')['harga']));
                $itemTipeItemIn.val($(this).data('item')['tipe_item']);

                $itemSatuanEl.on('change', function(){
                    var id = $(this).val();

                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'get_harga',
                        data     : {item_id:itemId, item_satuan_id : id},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            
                            var harga = parseInt(results.harga);

                            $('input[name$="[harga]"]', $row).val(harga);
                            $('input[name$="[sub_total]"]', $row).val(harga * $itemQtyIn.val());
                            
                            
                           
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });

                });

                $('button.search-item', $tableTambahItem).popover('hide');

                handleCountSubTotalItem($row);

                addItemRow();

            e.preventDefault();   
        });     
    };

    var handlePaketSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');
            var 
                $parentPop   = $(this).parents('.popover').eq(0),
                rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row         = $('#'+rowId, $tableTambahItem),
                $rowClass    = $('.row_item', $tableTambahItem);                
           
                $itemIdEl     = $('input[name$="[id]"]', $row);
                $itemCodeIn   = $('input[name$="[kode]"]', $row);
                $itemNameIn   = $('input[name$="[name]"]', $row);
                $itemHargaIn  = $('input[name$="[harga]"]', $row);
                $itemQtyIn    = $('input[name$="[qty]"]', $row);
                $itemSubTotIn = $('input[name$="[sub_total]"]', $row);
                $itemTipeItemIn = $('input[name$="[tipe_item]"]', $row);


                itemId = $(this).data('item')['id'];

                $itemIdEl.val($(this).data('item')['id']);
                $itemCodeIn.val($(this).data('item')['kode']);
                $itemNameIn.val($(this).data('item')['nama']);
                $itemHargaIn.val($(this).data('item')['harga_total']);
                $itemQtyIn.val(1);
                $itemSubTotIn.val(1 * ($(this).data('item')['harga_total']));
                $itemTipeItemIn.val($(this).data('item')['tipe_item']);



                $('button.search-item', $tableTambahItem).popover('hide');

                handleCountSubTotalItem($row);
                addItemRowPaket();

            e.preventDefault();   
        });     
    };

    var handleCountSubTotalItem = function($row) {
        var $itemQtyIn    = $('input[name$="[qty]"]', $row),
            $itemSubTotIn = $('input[name$="[sub_total]"]', $row),
            $itemHargaIn  = $('input[name$="[harga]"]', $row);

        $($itemQtyIn).on('change keyup', function() {
            var qty = $(this).val(),
                hrg = $itemHargaIn.val();

            $itemSubTotIn.val(qty * hrg);
        });

        $($itemHargaIn).on('change keyup', function() {
            var qty = $itemQtyIn.val(),
                hrg = $(this).val();

            $itemSubTotIn.val(qty * hrg);
        });
    };

    var handleTerdaftar = function()
    {
        $('a#btn_terdaftar').on('click', function(){
            $('a#btn_tidak_terdaftar').removeClass('btn-primary');
            $('a#btn_tidak_terdaftar').addClass('btn-default');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');


            $('div.pasien_terdaftar').removeClass('hidden');
            $('input#nama_ref_pasien').attr('readonly','readonly');
            $('input#tipe_pasien').val(1);
        });

        $('a#btn_tidak_terdaftar').on('click', function(){
            $('a#btn_terdaftar').removeClass('btn-primary');
            $('a#btn_terdaftar').addClass('btn-default');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');


            $('div.pasien_terdaftar').addClass('hidden');
            $('input#nama_ref_pasien').removeAttr('readonly');
            $('input#tipe_pasien').val(2);
        });
    }

    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'reservasi/buat_invoice/';
        handleJwertyEnter($('input#no_rekmed'));
        handleDatePicker();
        handleTerdaftar();
        handleValidation();
        handleConfirmSave();
        handlePilihPasien();
        handleDataTableItems();
        handleDataTablePaket();
        handleDataTableTindakan();
        initForm();
    };
 }(mb.app.buat_invoice.edit));


// initialize  mb.app.home.table
$(function(){
    mb.app.buat_invoice.edit.init();
});