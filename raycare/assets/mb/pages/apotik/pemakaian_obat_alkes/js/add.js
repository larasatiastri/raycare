mb.app.pemakaian_obat_alkes = mb.app.pemakaian_obat_alkes || {};
mb.app.pemakaian_obat_alkes.add = mb.app.pemakaian_obat_alkes.add || {};


(function(o){
    
     var 
        baseAppUrl          = '',
        $form               = $('#form_pemakaian_obat_alkes'),
        $tableItemSearch    = $('#table_item_search'),
        $tableKeluarBrg     = $('#table_pengeluaran_item'),
        $popoverItemContent = $('#popover_item_content'),
        $lastPopoverItem    = null,
        tplItemRow          = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter         = 0;

    var initForm = function(){ 
        var 
            $btnSearchItem = $('.search-item', $tableKeluarBrg),
            $btnDeletes    = $('.del-this', $tableKeluarBrg);

        addItemRow();
    };

    var addItemRow = function(){

        var numRow = $('tbody tr', $tableKeluarBrg).length;


        if(numRow > 0 && ! isValidLastRow() ) return;

        var numRow = $('tbody tr', $tableKeluarBrg).length;
        var 
            $rowContainer      = $('tbody', $tableKeluarBrg),
            $newItemRow        = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem     = $('.search-item', $newItemRow),
            $btnSearchSupplier = $('.search-supplier', $newItemRow)
            ;

        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
      
        // handle button search item
        handleBtnSearchItem($btnSearchItem);

    };

    var isValidLastRow = function()
    {
        var 
            $itemCodeEls    = $('input[name$="[kode]"]',$tableKeluarBrg),
            $qtyELs         = $('input[name$="[jumlah]"]',$tableKeluarBrg),
            itemCode        = $itemCodeEls.eq($qtyELs.length-1).val(),
            qty             = $qtyELs.eq($qtyELs.length-1).val() * 1
        ;

        return (itemCode != '')
    }


    var handleDataTableItems = function(){
        oTableItem = $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_search_item/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'item.nama kode','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'item.nama nama', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });       
       
        $tableItemSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handleItemSelect($btnSelect);
        });
            
        $popoverItemContent.hide();        

    };

    var handleItemSelect = function($btn){
        $btn.on('click', function(e){
            // alert('di klik');
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableKeluarBrg),
                $rowClass    = $('.row_item', $tableKeluarBrg)
                ;                
            // console.log(itemTarget);
           
                $itemIdEl       = $('input[name$="[item_id]"]', $row);
                $itemCodeIn     = $('input[name$="[kode]"]', $row);
                $itemNameIn     = $('input[name$="[nama]"]', $row);
                $itemSatuanEl    = $('select[name$="[satuan_id]"]', $row);
                $btnAddIdentitas    = $('button.add-identitas', $row);
                $('.search-item', $tableKeluarBrg).popover('hide');
                

                itemId = $(this).data('item')['id'];
                isIdentitas = $(this).data('item')['is_identitas'];
                    
                $itemIdEl.val($(this).data('item')['id']);
                $itemCodeIn.val($(this).data('item')['kode']);
                $itemNameIn.val($(this).data('item')['nama']);
                

                var satuan = $(this).data('satuan'),
                    primary = $(this).data('satuan_primary');
                
                $itemSatuanEl.empty();
                $.each(satuan, function(key, value) {
                    $itemSatuanEl.append($("<option></option>").attr("value", value.id).text(value.nama));
                    $itemSatuanEl.val(primary.id);
                });

                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'get_stok',
                    data     : {item_id:itemId, item_satuan_id : primary.id},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        
                        $('div#item_stok', $row).text(results.stok);
                       
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });

                if(isIdentitas == 0){
                    $btnAddIdentitas.hide();
                    $('input[name$="[jumlah]"]', $row).removeAttr('readonly');
                }else{
                    $btnAddIdentitas.show();
                    $('input[name$="[jumlah]"]', $row).attr('readonly','readonly');
                }

                $btnAddIdentitas.attr('href',baseAppUrl+'add_identitas/'+rowId+'/'+itemId+'/'+$itemSatuanEl.val());
               
                $itemSatuanEl.removeAttr('disabled');

                $itemSatuanEl.on('change', function(){
                    var id = $(this).val();

                    $btnAddIdentitas.attr('href',baseAppUrl+'add_identitas/'+rowId+'/'+itemId+'/'+id);

                    $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'get_stok',
                        data     : {item_id:itemId, item_satuan_id : id},
                        dataType : 'json',
                        beforeSend : function(){
                            Metronic.blockUI({boxed: true });
                        },
                        success  : function( results ) {
                            
                            $('div#item_stok', $row).text(results.stok);
                           
                        },
                        complete : function(){
                            Metronic.unblockUI();
                        }
                    });
                });

                addItemRow();
                

            e.preventDefault();   
        });     
    };


    var handleBtnDelete = function($btn){
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableKeluarBrg)

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableKeluarBrg).length == 0){
                addItemRow();
                // addItemAccRow();
            }
            e.preventDefault();
        });
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
    

   

    var handleConfirmSave = function(){
        $('a#confirm_save', $form).click(function() {
            // alert('klik');
            if (! $form.valid()) return;
            
            var  clickcounter1=0;
            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                    clickcounter1++;
                   // alert(clickcounter1);
                    if(clickcounter1==1)
                    {
                        $('#save', $form).click();
                    }
                }
            });
        });
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


    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/pemakaian_obat_alkes/';
        handleDataTableItems(); 
        handleValidation();
        initForm();
        handleDatePickers();
        handleConfirmSave();
    
        // handleDropdownTypeChange();
 
    };

}(mb.app.pemakaian_obat_alkes.add));

$(function(){    
    mb.app.pemakaian_obat_alkes.add.init();
});