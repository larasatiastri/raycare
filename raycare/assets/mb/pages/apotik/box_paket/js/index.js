mb.app.box_paket = mb.app.box_paket || {};
(function(o){

    var 
        baseAppUrl            = '',
        $form                 = $('#form_add_box_paket'),
        $popoverItemContent   = $('#popover_item_content'),
        $lastPopoverItem      = null,
        $tableTambahItem      = $('#tabel_tambah_item',$form),
        $tableItemSearch      = $('#table_pilih_item'),
        tplItemRow            = $.validator.format($('#tpl_item_row',$form).text()),
        itemCounter           = 0;

    var initForm = function(){

        
        addItemRowPaket();
        handleTambahRow();
        handleSelectJenisPaket();

    };

    var handleSelectJenisPaket = function(){
        var $selectJenis = $('select#box_paket_id');

        $selectJenis.on('change', function(){
            var jenis_paket = $(this).val(),
                array_jenis = jenis_paket.split("_"),
                harga = parseInt(array_jenis[1]);

            $('input#box_paket_id_hidden').val(array_jenis[0]);
            $('input#harga').val(mb.formatRp(harga));
            $('input#harga_hidden').attr("value", array_jenis[1]);
            $('input#tipe_paket').attr("value", array_jenis[2]);

            $gudangId = $('select#gudang_id');


            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_item_box_paket',
                data     : {box_paket_id:array_jenis[0], gudang_id : $gudangId.val()},
                dataType : 'html',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {

                    $('table#tabel_tambah_item tbody').html(results);
                   
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });



            
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
            if($('tbody>tr', $tableTambahItem).length == 0){
                addItemRowPaket();
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

    };

    var handleDiskon = function(){
        var $diskon = $('input#diskon', $tableTambahItem);

        total_all = 0;
        $('input#diskon', $tableTambahItem).on('change', function() {

            grand_total = $('input#total_hidden', $tableTambahItem).val(); 
            total_all = grand_total - parseInt($(this).val());

            $('th#grand_total', $tableTambahItem).text(mb.formatRp(total_all));
            $('input#grand_total_hidden', $tableTambahItem).val(total_all);
            
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

    

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/box_paket/';
        handleValidation();
        handleConfirmSave();
        handleDataTableItems();
        initForm();
    };
 }(mb.app.box_paket));


// initialize  mb.app.home.table
$(function(){
    mb.app.box_paket.init();
});