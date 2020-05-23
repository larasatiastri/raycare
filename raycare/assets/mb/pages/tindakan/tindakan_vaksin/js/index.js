mb.app.tindakan_vaksin = mb.app.tindakan_vaksin || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_add_tindakan_vaksin'),
        $popoverPasienContent = $('#popover_pasien_content'), 
        $popoverItemContent   = $('#popover_item_content'),
        $lastPopoverPasien    = null,
        $lastPopoverItem      = null,
        $tablePilihPasien     = $('#table_pilih_pasien'),
        $tableTambahItem      = $('#tabel_tambah_item',$form),
        $tableItemSearch      = $('#table_pilih_item'),
        $tableHistoryVaksin     = $('#tabel_history_vaksin'),
        tplItemRow            = $.validator.format($('#tpl_item_row',$form).text()),
        itemCounter           = 0;

    var initForm = function(){

        var $btnSearchPasien  = $('.pilih-pasien', $form);
        handleBtnSearchPasien($btnSearchPasien);
        
        addItemRowPaket();
        //addItemRowObat();
        handleTambahRow();

        $('select.select2').select2();
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

    var handleDataTableHistory = function(){
        oTableVaksin = $tableHistoryVaksin.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_history_vaksin',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name':'tindakan_vaksin.id id','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'master_vaksin.nama nama_vaksin','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'tindakan_vaksin.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'a.nama nama_dokter','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'b.nama nama_perawat','visible' : true, 'searchable': false, 'orderable': true },
                { 'name':'cabang.nama nama_cabang','visible' : true, 'searchable': false, 'orderable': true },
                ]
        });       

        $tableHistoryVaksin.on('draw.dt', function (){
            
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

        var $btnSelects = $('a.select-pasien', $tablePilihPasien);
        handlePilihPasienSelect( $btnSelects );

        $tablePilihPasien.on('draw.dt', function (){
            var $btnSelect = $('a.select-pasien', this);
            handlePilihPasienSelect( $btnSelect );
            
        });

        $popoverPasienContent.hide();        
    };

    var handlePilihPasienSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $namaRefPasien  = $('input[name="nama_ref_pasien"]'),
                $IdRefPasien    = $('input[name="id_ref_pasien"]'),
                $noRekmedPasien = $('input[name="no_rekmed"]'),
                $alamatPasien   = $('textarea[name="alamat_pasien"]'),
                $itemCodeEl     = null,
                $itemNameEl     = null
                ;        


            $('.pilih-pasien', $form).popover('hide');          

            pekerjaan = '-';
            if($(this).data('item').nama_pekerjaan !== null)
            {
                pekerjaan = $(this).data('item').nama_pekerjaan;
            }

            $noRekmedPasien.val($(this).data('item').no_member);
            $IdRefPasien.val($(this).data('item').id);
            $namaRefPasien.val($(this).data('item').nama);
            $alamatPasien.val($(this).data('item').alamat);

            var pasien_id = $(this).data('item').id;
            var vaksin_id = $('select#master_vaksin_id').val();

            oTableVaksin.api().ajax.url(baseAppUrl + 'listing_history_vaksin/'+pasien_id+'/'+vaksin_id).load();

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
                $itemQtyEl.val(1);

                $gudangId = $('select#gudang_id');

                harga_item = 0;
                if($(this).data('harga')['harga'] == undefined){
                    harga_item = 0;
                }else{
                    harga_item = parseInt($(this).data('harga')['harga']);
                }
                            
                $('div#item_harga', $row).text(mb.formatRp(harga_item));
                $('div#item_sub_total', $row).text(mb.formatRp(1*harga_item));
                $('input[name$="[harga]"]', $row).val(harga_item);
                $('input[name$="[sub_total]"]', $row).val(1*harga_item);
                

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

                               
                $itemSatuanEl.removeAttr('disabled');

                $itemSatuanEl.on('change', function(){
                    var id = $(this).val();

                    $btnAddIdentitas.attr('href',baseAppUrl+'add_identitas/'+rowId+'/'+itemId+'/'+id+ '/' + $gudangId.val());
                    $('input[name$="[qty]"]', $row).val(0);
                    $('div#identitas_row', $row).html('');

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

        $('th#grand_total', $tableTambahItem).text(mb.formatRp(grand_total));
        $('input#grand_total_hidden', $tableTambahItem).val(grand_total);

        
    };

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

    
    var handleDatePickers = function () {
        var time = new Date($('#tanggal').val());
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                orientation: "left",
                autoclose: true,
                update : time

            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var handleChangeVaksin = function(){
        $('select#master_vaksin_id').on('change', function(){

            var pasien_id = $('input[name="id_ref_pasien"]').val();
            var vaksin_id = $(this).val();

            oTableVaksin.api().ajax.url(baseAppUrl + 'listing_history_vaksin/'+pasien_id+'/'+vaksin_id).load();
        });

        $('input[name="is_lanjut"]').on('change', function(){
            var lanjut = $(this).val();

            if(lanjut == 1){
                $('div#div_lanjut').removeClass('hidden');
                $('input#tanggal_lanjut').attr('required','required');
            }if(lanjut == 0){
                $('div#div_lanjut').addClass('hidden');
                $('input#tanggal_lanjut').removeAttr('required');
            }
        });
    }
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'tindakan/tindakan_vaksin/';
        handleJwertyEnter($('input#no_rekmed'));
        handleTerdaftar();
        handleValidation();
        handleConfirmSave();
        handlePilihPasien();
        handleDataTableItems();
        initForm();
        handleDatePickers();
        handleDataTableHistory();
        handleChangeVaksin();
    };
 }(mb.app.tindakan_vaksin));


// initialize  mb.app.home.table
$(function(){
    mb.app.tindakan_vaksin.init();
});