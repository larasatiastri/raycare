mb.app.resep_obat = mb.app.resep_obat || {};
mb.app.resep_obat.view_resep_history = mb.app.resep_obat.view_resep_history || {};

// mb.app.resep_obat.add namespace
(function(o){

    var 
        baseAppUrl              = '',
        $form                   = $('#form_view_resep_obat'),
        $errorTop               = $('.alert-danger', $form),
        $succesTop              = $('.alert-success', $form),
        $tableItemDigunakan       = $('#table_item_digunakan', $form),
        $tableItemSearch        = $('#table_item_search'),
        $popoverItemContent     = $('#popover_item_content'), 
        $lastPopoverItem        = null,
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter             = $('input#item_counter').val();
        ;


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
                $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control locker
            },

            success: function (label) {
                $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control locker
            } 
        });   
    }

    var initForm = function(){
        var 
            $btnSearchItem = $('.search-item', $tableItemDigunakan),
            $btnDeletes    = $('.del-this', $tableItemDigunakan);
            $btnDeletesDb  = $('.del-this-db', $tableItemDigunakan),
        
        handleBtnSearchItem($btnSearchItem);    

        // handle delete btn
        $.each($btnDeletes, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        $.each($btnDeletesDb, function(idx, btn){
            handleBtnDeleteDB( $(btn) );
        });
        // tambah 1 row kosong pertama
        // addItemRow();
    };

// tambah row di table item details
    var addItemRow = function(){
        
        var numRow = $('tbody tr', $tableItemDigunakan).length;

        console.log('numrow' + numRow);

        // if (numRow > 0 && ! isValidLastRow()) return;

        var 
            $rowContainer     = $('tbody', $tableItemDigunakan),
            $newItemRow       = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            // $newGetItemRow = $(tplGetItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem    = $('.search-item', $newItemRow);

        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );

        // handle button search item
        handleBtnSearchItem($btnSearchItem);

    };

     var isValidLastRow = function(){
        var 
            $itemCodeEls    = $('input[name$="[code]"]',$tableItemDigunakan),
            $qtyELs         = $('input[name$="[qty]"]',$tableItemDigunakan),
            itemCode        = $itemCodeEls.eq($qtyELs.length-1).val(),
            qty             = $qtyELs.eq($qtyELs.length-1).val() * 1
        ;

        return (itemCode != '' && qty > 0)
    }

   
    var handleBtnDelete = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableItemDigunakan);

        $btn.on('click', function(e){
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
                // if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableItemDigunakan).length == 0){
                        addItemRow();
                    }
                    // focusLastItemCode();
                // }
            // });
            e.preventDefault();
        });
    };

    var handleBtnDeleteDB = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableItemDigunakan);

        $btn.on('click', function(e){
            bootbox.confirm('Are you sure want to delete this item?', function(result){
                if (result==true) {

                    $('input[name$="[is_delete]"]', $row).attr('value',1);
                    // $row.hide(); //hide
                    if($('tbody>tr', $tableItemDigunakan).length == 0){
                        addItemRow();
                    }
                }
            });
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
            $('input[name="rowItemId"]', $popcontent).val(rowId);
            
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
        $tableItemSearch.dataTable({
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
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'item.kode', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },               ]
        });

        var $btnSelects = $('a.select', $tableItemSearch);
        handleItemSearchSelect( $btnSelects );

        $tableItemSearch.on('draw.dt', function (){
            var $btnSelect = $('a.select', this);
            handleItemSearchSelect( $btnSelect );
            
        } );

        $popoverItemContent.hide();        
    };

    var handleItemSearchSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input[name="rowItemId"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableItemDigunakan),
                $itemId   = null,
                $itemKode = null,
                $itemNama = null
                ;        


           
                $itemId        = $('input[name$="[item_id]"]', $row);
                $itemKode      = $('input[name$="[item_kode]"]', $row);
                $itemNama      = $('input[name$="[item_nama]"]', $row);
                $itemSatuan    = $('select[name$="[satuan]"]', $row);

                $itemLabelKode = $('label[name$="[item_kode]"]', $row);
                $itemLabelNama      = $('label[name$="[item_nama]"]', $row);


                $('.search-item', $tableItemDigunakan).popover('hide');
            
                console.log($itemId);
            
            $.ajax
            ({ 

                    type: 'POST',
                    url: baseAppUrl +  "get_item_satuan",  
                    data:  {item_id : $(this).data('item').id},  
                    dataType : 'json',
                    success:function(results)          //on recieve of reply
                    { 
                        $itemSatuan.empty();

                        //munculin index pertama Pilih..
                        $itemSatuan.append($("<option></option>")
                                .attr("value", "").text("Pilih.."));
                            $itemSatuan.val('');

                        //munculin semua data dari hasil post
                        $.each(results, function(key, value) {
                            $itemSatuan.append($("<option></option>")
                                .attr("value", value.id).text(value.nama));
                            $itemSatuan.val('');

                        });
                    
                    }
       
            });

            // console.log($itemIdEl)
            
            $itemId.val($(this).data('item').id);            
            $itemKode.val($(this).data('item').item_kode);
            $itemNama.val($(this).data('item').item_nama);

            $itemLabelKode.text($(this).data('item').item_kode);
            $itemLabelNama.text($(this).data('item').item_nama);


            // alert($itemIdEl.val($(this).data('item').id));


            e.preventDefault();
            addItemRow();
        });     
    };

    var handleConfirmSave = function(){
        var numRow = $('tbody tr', $tableItemDigunakan).length;
        $('a#confirm_save', $form).click(function(e) {
            
            if (! $form.valid() && numRow > 0) return;

            var msg = $(this).data('confirm');
            bootbox.confirm(msg, function(result) {
                if (result==true) {
                     $('#save',$form).click();  
                }
            });
            e.preventDefault();
        });
    };

   var handleDateRangePickers = function () {
        if (!jQuery().daterangepicker) {
            return;
        }

        $('#defaultrange', $form).daterangepicker({
                format: 'MM/DD/YYYY',
                separator: ' to ',
                startDate: moment().subtract('days', 29),
                endDate: moment(),
                minDate: '01/01/2013',
                maxDate: '12/31/2100',
            },
            function (start, end) {
                console.log("Callback has been called!");
                $('#defaultrange input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );        
    }

    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/resep_obat/';
        handleValidation();
        handleDataTableItems();
        initForm(); 
        handleConfirmSave();
        handleDateRangePickers();
    };

}(mb.app.resep_obat.view_resep_history));


// initialize  mb.app.resep_obat.add
$(function(){
    mb.app.resep_obat.view_resep_history.init();
});