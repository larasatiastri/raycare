mb.app.view = mb.app.view || {};


(function(o){
    
     var 
        baseAppUrl                    = '',
        $form                         = $('#form_add_kirim_item'),
        $tableAddItem                 = $('#table_add_transfer_item', $form),
        $tablePilihItem               = $('#table_item_search'),
        $popoverItemContent           = $('#popover_item_content'),
        $lastPopoverItem              = null,
        tplItemRow                    = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter                   = 0,
        totalBayar                    = 0;

    var initForm = function(){
    
        addItemRow();
    };

    var addItemRow = function(){

        if(! isValidLastRow()) return;

        var numRow = $('tbody tr', $tableAddItem).length;
        var 
            $rowContainer         = $('tbody', $tableAddItem),
            $newItemRow           = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem  = $('.search-item', $newItemRow)
            ;

        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
      
        // handle button search item
        handleBtnSearchItem($('.search-item', $newItemRow));
    }



    var handleDataTableItems = function(){

       gudang_id = $('input#pk_value').val();
       // user_level_id = $('input#user_level_id').val();
       oTable = $tablePilihItem.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            // 'sAjaxSource'              : baseAppUrl + 'listing_alat_obat',
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item/' + gudang_id,
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'item.id item_id', 'visible' : false, 'searchable': false, 'orderable': false },
                { 'name' : 'item.kode item_kode','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama item_nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item_satuan.nama satuan', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'inventory.bn_sn_lot bn', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'inventory.expire_date ed', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });       
        $('#table_item_search_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_search_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown


        $tablePilihItem.on('draw.dt', function (){
            
            $('.btn', this).tooltip();

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
                $row         = $('#'+rowId, $tableAddItem),
                $rowClass    = $('.row_item', $tableAddItem),
                $itemCodeEl  = null,
                $itemNameEl  = null, 
                $itemHargaEl = null,
                $itemQtyEl   = $('input[name$="[name]"]', $row);                
           
                itemIdElAll   = $('input[name$="[item_id]"]', $rowClass);
                $itemIdIn     = $('input[name$="[item_id]"]', $row);
                $itemCodeEl   = $('label[name$="[item_kode]"]', $row);
                $itemCodeIn   = $('input[name$="[item_kode]"]', $row);
                $itemNameEl   = $('label[name$="[item_nama]"]', $row);
                $itemNameIn   = $('input[name$="[nama]"]', $row);
                $itemSatuanEl   = $('label[name$="[item_satuan]"]', $row);
                $itemSatuanIn   = $('input[name$="[id_satuan]"]', $row);
                $itemBNEl  = $('label[name$="[item_bn]"]', $row);
                $itemBNIn  = $('input[name$="[bn]"]', $row);
                $itemEDEl  = $('label[name$="[item_ed]"]', $row);
                $itemEDIn  = $('input[name$="[ed]"]', $row);
                $itemJmlIn  = $('input[name$="[jumlah_kirim]"]', $row);

                itemId = $(this).data('item')['item_id'];

                found = false;
                $.each(itemIdElAll,function(idx, value){
                    // alert(this.value);
                    if(itemId == this.value)
                    {
                        found = true;
                    }
                });
           
                $('.search-item', $tableAddItem).popover('hide');

                
                    $itemIdIn.val($(this).data('item')['item_id']);
                    $itemCodeEl.text($(this).data('item')['item_kode']);
                    $itemCodeIn.val($(this).data('item')['item_kode']);
                    $itemNameEl.text($(this).data('item')['item_nama']);
                    $itemNameIn.val($(this).data('item')['item_nama']);
                    $itemSatuanEl.text($(this).data('item')['satuan']);
                    $itemSatuanIn.val($(this).data('item')['satuan_id']);
                    $itemBNIn.val($(this).data('item')['bn']);
                    $itemBNEl.text($(this).data('item')['bn']);
                    $itemEDEl.text($(this).data('item')['ed']);
                    $itemEDIn.val($(this).data('item')['ed']);

                    $itemJmlIn.val($(this).data('item')['jumlah']);
                    $itemJmlIn.attr('max',$(this).data('item')['jumlah']);
                    $itemJmlIn.attr('min',1);
                    $itemJmlIn.focus();
                    $itemJmlIn.select();
                    

                    addItemRow();

            e.preventDefault();   

        });     
    };


    var handleBtnDelete = function($btn){
        var 
            rowId           = $btn.closest('tr').prop('id'),
            $row            = $('#'+rowId, $tableAddItem)

        $btn.on('click', function(e){            
            $row.remove();
            if($('tbody>tr', $tableAddItem).length == 0){
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

            $popContainer.css({  minWidth: '720px', maxWidth: '720px'});

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
        if (jQuery().datepicker) {
            $('.date', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd M yyyy',
                orientation: "left",
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

     var isValidLastRow = function()
    {
         var 
            $itemCodeEls    = $('input[name$="[item_kode]"]',$tableAddItem),
            $qtyELs         = $('input[name$="[jumlah_kirim]"]',$tableAddItem),
            itemCode        = $itemCodeEls.eq($qtyELs.length-1).val(),
            qty             = $qtyELs.eq($qtyELs.length-1).val() * 1
        ;

        return (itemCode != '')
    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/transfer_item/';
        handleValidation();
        initForm();
        handleDatePickers();
        handleConfirmSave();
        handleDataTableItems();
     };

}(mb.app.view));

$(function(){    
    mb.app.view.init();
});