mb.app.userlevel = mb.app.userlevel || {};
mb.app.userlevel.add = mb.app.userlevel.add || {};
(function(o){

    var 
        baseAppUrl                  = '',
        $form                       = $('#form_add_user_level');
        $tableUserlevel             = $('#table_user_level');
        $tableUserlevel_item        = $('#table_user_level_item');
        $tableUserlevel_supplier    = $('#table_user_level_supplier');
        $tableUserlevel_customer    = $('#table_user_level_customer');
        $tambahRow                  = $('#tambahRow');
        $tablePersetujuan           = $('#table_persetujuan', $form);
        $tablePersetujuanBiaya           = $('#table_persetujuan_biaya', $form);
        $tablePersetujuanItem       = $('#table_persetujuan_item', $form);
        $tablePersetujuanCustomer   = $('#table_persetujuan_customer', $form);
        $tablePersetujuanSupplier   = $('#table_persetujuan_supplier', $form);

        $lastPopoverItem        = null,
        // $lastPopoverSupplier    = null,
        tplItemRow                      = $.validator.format( $('#tpl_item_row').text() ),
        tplItemRowPersetujuanBiaya                      = $.validator.format( $('#tpl_item_row_biaya').text() ),
        tplItemRowPersetujuanItem       = $.validator.format( $('#tpl_item_row_item').text() ),
        tplItemRowPersetujuanSupplier   = $.validator.format( $('#tpl_item_row_supplier').text() ),
        tplItemRowPersetujuanCustomer   = $.validator.format( $('#tpl_item_row_customer').text() ),
        itemCounter                     = 1;
        itemCounterBiaya                     = 1;
        itemCounterPersetujuanItem      = 1;
        itemCounterPersetujuanSupplier  = 1;
        itemCounterPersetujuanCustomer  = 1;

    var initForm = function(){
        //alert("a");
        // tambah 1 row kosong pertama
        $btnDelete = $('a.del-this', $tablePersetujuan);

        $.each($btnDelete, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        $btnDeleteBiaya = $('a.del-this', $tablePersetujuanBiaya);

        $.each($btnDeleteBiaya, function(idx, btn){
            handleBtnDeleteBiaya( $(btn) );
        });

        $btnDeletePersetujuanItem = $('a.del-this', $tablePersetujuanItem);

        $.each($btnDeletePersetujuanItem, function(idx, btn){
            handleBtnDeletePersetujuanItem( $(btn) );
        });

        $btnDeletePersetujuanCustomer = $('a.del-this', $tablePersetujuanCustomer);

        $.each($btnDeletePersetujuanCustomer, function(idx, btn){
            handleBtnDeletePersetujuanCustomer( $(btn) );
        });

        $btnDeletePersetujuanSupplier = $('a.del-this', $tablePersetujuanSupplier);

        $.each($btnDeletePersetujuanSupplier, function(idx, btn){
            handleBtnDeletePersetujuanSupplier( $(btn) );
        });


        addItemRow();
        addItemRowBiaya();
        addItemRowPersetujuanItem();
        addItemRowPersetujuanSupplier();
        addItemRowPersetujuanCustomer();


        $('a#addRow').on('click', function(){
            addItemRow();
        });

        $('a#addRowBiaya').on('click', function(){
            addItemRowBiaya();
        });

        $('a#addRowItem').on('click', function(){
            addItemRowPersetujuanItem();
        });

        $('a#addRowSupplier').on('click', function(){
            addItemRowPersetujuanSupplier();
        });

        $('a#addRowCustomer').on('click', function(){
            addItemRowPersetujuanCustomer();
        });
    };

    ///////////////////////////////////////////////////////////////////////////////////////

    var addItemRow = function(){

        var numRow = $('tbody tr', $tablePersetujuan).length;
        console.log('numrow ' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer         = $('tbody', $tablePersetujuan),
            $newItemRow           = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem        = $('.search-item', $newItemRow)
            // $inputNumber          = $('input[name$="[qty]"], input[name$="[cost]"]', $newItemRow)
            ;
            $('input#numRow').val(itemCounter-1);
        // handle delete btn
        handleBtnDelete( $('a.del-this', $newItemRow) );
    };

    var addItemRowBiaya = function(){

        var numRow = $('tbody tr', $tablePersetujuanBiaya).length;
        console.log('numrow ' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        if( numRow > 0 && ! isValidLastRowBiaya() ) return;

        var 
            $rowContainer         = $('tbody', $tablePersetujuanBiaya),
            $newItemRow           = $(tplItemRowPersetujuanBiaya(itemCounterBiaya++)).appendTo( $rowContainer ),
            $btnSearchItem        = $('.search-item', $newItemRow)
            // $inputNumber          = $('input[name$="[qty]"], input[name$="[cost]"]', $newItemRow)
            ;
            $('input#numRowBiaya').val(itemCounterBiaya-1);
        // handle delete btn
        handleBtnDeleteBiaya( $('a.del-this', $newItemRow) );
    };

    var addItemRowPersetujuanItem = function(){

        var numRow = $('tbody tr', $tablePersetujuanItem).length;
        console.log('numrow ' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        if( numRow > 0 && ! isValidLastRowPersetujuanItem() ) return;

        var 
            $rowContainer         = $('tbody', $tablePersetujuanItem),
            $newItemRow           = $(tplItemRowPersetujuanItem(itemCounterPersetujuanItem++)).appendTo( $rowContainer ),
            $btnSearchItem        = $('.search-item', $newItemRow)
            // $inputNumber          = $('input[name$="[qty]"], input[name$="[cost]"]', $newItemRow)
            ;
             $('input#numRowItem').val(itemCounterPersetujuanItem-1);
        // handle delete btn
        handleBtnDeletePersetujuanItem( $('a.del-this', $newItemRow) );
    };

    var addItemRowPersetujuanSupplier = function(){

        var numRow = $('tbody tr', $tablePersetujuanSupplier).length;
        console.log('numrow ' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        if( numRow > 0 && ! isValidLastRowPersetujuanSupplier() ) return;

        var 
            $rowContainer         = $('tbody', $tablePersetujuanSupplier),
            $newItemRow           = $(tplItemRowPersetujuanSupplier(itemCounterPersetujuanSupplier++)).appendTo( $rowContainer ),
            $btnSearchItem        = $('.search-item', $newItemRow)
            // $inputNumber          = $('input[name$="[qty]"], input[name$="[cost]"]', $newItemRow)
            ;
             $('input#numRowSupplier').val(itemCounterPersetujuanSupplier-1);
        // handle delete btn
        handleBtnDeletePersetujuanSupplier( $('a.del-this', $newItemRow) );
    };

    var addItemRowPersetujuanCustomer = function(){

        var numRow = $('tbody tr', $tablePersetujuanCustomer).length;
        console.log('numrow ' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        if( numRow > 0 && ! isValidLastRowPersetujuanCustomer() ) return;

        var 
            $rowContainer         = $('tbody', $tablePersetujuanCustomer),
            $newItemRow           = $(tplItemRowPersetujuanCustomer(itemCounterPersetujuanCustomer++)).appendTo( $rowContainer ),
            $btnSearchItem        = $('.search-item', $newItemRow)
            // $inputNumber          = $('input[name$="[qty]"], input[name$="[cost]"]', $newItemRow)
            ;
             $('input#numRowCustomer').val(itemCounterPersetujuanCustomer-1);
        // handle delete btn
        handleBtnDeletePersetujuanCustomer( $('a.del-this', $newItemRow) );
    };

    /////////////////////////////////////////////////////////////////////////////////////////

    var isValidLastRow = function(){
        
        var 
                $itemCodeEls = $('input[name$="[name]"]', $tablePersetujuan),
                // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
                itemCode    = $itemCodeEls.eq($itemCodeEls.length-1).val()
                // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
            ;

       // var rowId    = $this('tr').prop('id');
        //alert(rowId);
            // console.log('itemcode ' + itemCode + ' processqty ' + processQty);
            return (itemCode != '');

    };

    var isValidLastRowBiaya = function(){
        
        var 
                $itemCodeEls = $('input[name$="[name]"]', $tablePersetujuanBiaya),
                // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
                itemCode    = $itemCodeEls.eq($itemCodeEls.length-1).val()
                // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
            ;

       // var rowId    = $this('tr').prop('id');
        //alert(rowId);
            // console.log('itemcode ' + itemCode + ' processqty ' + processQty);
            return (itemCode != '');

    };

    var isValidLastRowPersetujuanItem = function(){
        
        var 
                $itemCodeEls = $('input[name$="[name]"]', $tablePersetujuanItem),
                // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
                itemCode    = $itemCodeEls.eq($itemCodeEls.length-1).val()
                // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
            ;

       // var rowId    = $this('tr').prop('id');
        //alert(rowId);
            // console.log('itemcode ' + itemCode + ' processqty ' + processQty);
            return (itemCode != '');

    };

    var isValidLastRowPersetujuanSupplier = function(){
        
        var 
                $itemCodeEls = $('input[name$="[name]"]', $tablePersetujuanSupplier),
                // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
                itemCode    = $itemCodeEls.eq($itemCodeEls.length-1).val()
                // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
            ;

       // var rowId    = $this('tr').prop('id');
        //alert(rowId);
            // console.log('itemcode ' + itemCode + ' processqty ' + processQty);
            return (itemCode != '');

    };

    var isValidLastRowPersetujuanCustomer = function(){
        
        var 
                $itemCodeEls = $('input[name$="[name]"]', $tablePersetujuanCustomer),
                // $qtyEls = $('input[name$="[qty]"]', $tableAddPhone),
                itemCode    = $itemCodeEls.eq($itemCodeEls.length-1).val()
                // qty         = $qtyEls.eq($qtyEls.length-1).val() * 1
            ;

       // var rowId    = $this('tr').prop('id');
        //alert(rowId);
            // console.log('itemcode ' + itemCode + ' processqty ' + processQty);
            return (itemCode != '');

    };

    /////////////////////////////////////////////////////////////////////////////////////////////

    var handleBtnDelete = function($btn){
        var numRow = $('tbody tr', $tablePersetujuan).length;
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuan);

        $btn.on('click', function(e){
            
                // bootbox.confirm('Are you sure as to delete this item?', function(result){
                    // if (result==true) {
                        //if(! isValidLastRow() ) return;
                        $row.remove();
                        if($('tbody>tr', $tablePersetujuan).length == 0){
                            addItemRow();
                        }
                        // focusLastItemCode();
                     // }
                // });
            
            
            e.preventDefault();
        });
    };

    var handleBtnDeleteBiaya = function($btn){
        var numRow = $('tbody tr', $tablePersetujuan).length;
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuan);

        $btn.on('click', function(e){
            
                // bootbox.confirm('Are you sure as to delete this item?', function(result){
                    // if (result==true) {
                        //if(! isValidLastRow() ) return;
                        $row.remove();
                        if($('tbody>tr', $tablePersetujuanBiaya).length == 0){
                            addItemRowBiaya();
                        }
                        // focusLastItemCode();
                     // }
                // });
            
            
            e.preventDefault();
        });
    };

    var handleBtnDeletePersetujuanItem = function($btn){
        var numRow = $('tbody tr', $tablePersetujuanItem).length;
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuanItem);

        $btn.on('click', function(e){
            
                // bootbox.confirm('Are you sure as to delete this item?', function(result){
                    // if (result==true) {
                        //if(! isValidLastRow() ) return;
                        $row.remove();
                        if($('tbody>tr', $tablePersetujuanItem).length == 0){
                            addItemRowPersetujuanItem();
                        }
                        // focusLastItemCode();
                     // }
                // });
            
            
            e.preventDefault();
        });
    };

    var handleBtnDeletePersetujuanSupplier = function($btn){
        var numRow = $('tbody tr', $tablePersetujuanSupplier).length;
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuanSupplier);

        $btn.on('click', function(e){
            
                // bootbox.confirm('Are you sure as to delete this item?', function(result){
                    // if (result==true) {
                        //if(! isValidLastRow() ) return;
                        $row.remove();
                        if($('tbody>tr', $tablePersetujuanSupplier).length == 0){
                            addItemRowPersetujuanSupplier();
                        }
                        // focusLastItemCode();
                     // }
                // });
            
            
            e.preventDefault();
        });
    };

    var handleBtnDeletePersetujuanCustomer = function($btn){
        var numRow = $('tbody tr', $tablePersetujuanCustomer).length;
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuanCustomer);

        $btn.on('click', function(e){
            
                // bootbox.confirm('Are you sure as to delete this item?', function(result){
                    // if (result==true) {
                        //if(! isValidLastRow() ) return;
                        $row.remove();
                        if($('tbody>tr', $tablePersetujuanCustomer).length == 0){
                            addItemRowPersetujuanCustomer();
                        }
                        // focusLastItemCode();
                     // }
                // });
            
            
            e.preventDefault();
        });
    };

    //////////////////////////////////////////////////////////////////////////////////////////////

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
			// if (! $form.valid()) return;

            var i = 0;
            var msg = $(this).data('confirm');
			var proses = $(this).data('proses');
		    bootbox.confirm(msg, function(result) {
                if (result==true) {
                    Metronic.blockUI({boxed: true, message: proses});
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

    var handleTambahRow = function(){
        $('a#tambah_row').click(function() {
            addItemRow();
            addItemRowPersetujuanItem();
            addItemRowPersetujuanSupplier();
            addItemRowPersetujuanCustomer();
        });
    };

    var handleMultiSelect = function () {
        $('#multi_select').multiSelect();   
    };

    ///////////////////////////////////////////////////////////////////////////////

    var handleDataTable = function() 
    {
        $tableUserlevel.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'paginate'              : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing/add',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableUserlevel.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker
            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
            });
            var $btnSelects = $('a#select', $tableUserlevel);
            handleItemSearchSelect($btnSelects);             

        });
    };

    var handleDataTable_Persetujuan_Item = function() 
    {
        $tableUserlevel_item.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'paginate'              : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_p_item/add',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableUserlevel_item.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker
            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
            });
            var $btnSelects = $('a#select-p-item', $tableUserlevel_item);
            handleItemSearchSelect_persetujuan_item($btnSelects);             

        });
    };

    var handleDataTable_Persetujuan_Supplier = function() 
    {
        $tableUserlevel_supplier.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'paginate'              : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_p_supplier/add',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableUserlevel_supplier.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker
            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
            });
            var $btnSelects = $('a#select-p-supplier', $tableUserlevel_supplier);
            handleItemSearchSelect_persetujuan_supplier($btnSelects);             

        });
    };

    var handleDataTable_Persetujuan_Customer = function() 
    {
        $tableUserlevel_customer.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'paginate'              : false,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_p_customer/add',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });
        $tableUserlevel_customer.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker
            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
            });
            var $btnSelects = $('a#select-p-customer', $tableUserlevel_customer);
            handleItemSearchSelect_persetujuan_customer($btnSelects);             

        });
    };

    //////////////////////////////////////////////////////////////////////////////

    var handleItemSearchSelect = function($btn){
        $btn.on('click', function(e){
            //alert('a');
            //var numRow = $('tbody tr', $tablePersetujuan).length;
            //alert(numRow);
            // var numRow = itemCounter++ - 1;
            var numRow = itemCounter -1;
            //alert(numRow);
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val()
                $row        = $('#item_row_'+numRow, $tablePersetujuan),

                $itemIdEl = null,
                $itemNameEl = null; 
                //$itemQtyEl  = $('input[name$="[stock][qty]"]', $row);                
            // console.log(itemTarget);
            
                $itemIdEl = $('input[name$="[id]"]', $row);
                $itemNameEl = $('input[name$="[name]"]', $row);
                $itemLabelNameEl = $('label[name$="[lblname]"]', $row);
                //$('.search-item-init', $tableProcessItem).popover('hide');
                // $('.search-item-result', $tableProcessItem).prop('disabled', false);
            
            $itemIdEl.val($(this).data('item').id);
            $itemNameEl.val($(this).data('item').nama);
            $itemLabelNameEl.text($(this).data('item').nama);
            //calculateTotal();
            $('#closeModal').click();
            e.preventDefault();
            addItemRow();
        });     
    };

    var handleItemSearchSelect_persetujuan_item = function($btn){
        $btn.on('click', function(e){
            //alert('a');
            //var numRow = $('tbody tr', $tablePersetujuan).length;
            //alert(numRow);
            // var numRow = itemCounter++ - 1;
            var numRow = itemCounterPersetujuanItem -1;
            //alert(numRow);
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val()
                $row        = $('#item_row_'+numRow, $tablePersetujuanItem),

                $itemIdEl = null,
                $itemNameEl = null; 
                //$itemQtyEl  = $('input[name$="[stock][qty]"]', $row);                
            // console.log(itemTarget);
            
                $itemIdEl = $('input[name$="[id]"]', $row);
                $itemNameEl = $('input[name$="[name]"]', $row);
                $itemLabelNameEl = $('label[name$="[lblname]"]', $row);
                //$('.search-item-init', $tableProcessItem).popover('hide');
                // $('.search-item-result', $tableProcessItem).prop('disabled', false);
            
            $itemIdEl.val($(this).data('item').id);
            $itemNameEl.val($(this).data('item').nama);
            $itemLabelNameEl.text($(this).data('item').nama);
            //calculateTotal();
            $('#closeModalItem').click();
            e.preventDefault();
            addItemRowPersetujuanItem();
        });     
    };

    var handleItemSearchSelect_persetujuan_supplier = function($btn){
        $btn.on('click', function(e){
            //alert('a');
            //var numRow = $('tbody tr', $tablePersetujuan).length;
            //alert(numRow);
            // var numRow = itemCounter++ - 1;
            var numRow = itemCounterPersetujuanSupplier -1;
            //alert(numRow);
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val()
                $row        = $('#item_row_'+numRow, $tablePersetujuanSupplier),

                $itemIdEl = null,
                $itemNameEl = null; 
                //$itemQtyEl  = $('input[name$="[stock][qty]"]', $row);                
            // console.log(itemTarget);
            
                $itemIdEl = $('input[name$="[id]"]', $row);
                $itemNameEl = $('input[name$="[name]"]', $row);
                $itemLabelNameEl = $('label[name$="[lblname]"]', $row);
                //$('.search-item-init', $tableProcessItem).popover('hide');
                // $('.search-item-result', $tableProcessItem).prop('disabled', false);
            
            $itemIdEl.val($(this).data('item').id);
            $itemNameEl.val($(this).data('item').nama);
            $itemLabelNameEl.text($(this).data('item').nama);
            //calculateTotal();
            $('#closeModalSupplier').click();
            e.preventDefault();
            addItemRowPersetujuanSupplier();
        });     
    };

    var handleItemSearchSelect_persetujuan_customer = function($btn){
        $btn.on('click', function(e){
            //alert('a');
            //var numRow = $('tbody tr', $tablePersetujuan).length;
            //alert(numRow);
            // var numRow = itemCounter++ - 1;
            var numRow = itemCounterPersetujuanCustomer -1;
            //alert(numRow);
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val()
                $row        = $('#item_row_'+numRow, $tablePersetujuanCustomer),

                $itemIdEl = null,
                $itemNameEl = null; 
                //$itemQtyEl  = $('input[name$="[stock][qty]"]', $row);                
            // console.log(itemTarget);
            
                $itemIdEl = $('input[name$="[id]"]', $row);
                $itemNameEl = $('input[name$="[name]"]', $row);
                $itemLabelNameEl = $('label[name$="[lblname]"]', $row);
                //$('.search-item-init', $tableProcessItem).popover('hide');
                // $('.search-item-result', $tableProcessItem).prop('disabled', false);
            
            $itemIdEl.val($(this).data('item').id);
            $itemNameEl.val($(this).data('item').nama);
            $itemLabelNameEl.text($(this).data('item').nama);
            //calculateTotal();
            $('#closeModalCustomer').click();
            e.preventDefault();
            addItemRowPersetujuanCustomer();
        });     
    };

    ///////////////////////////////////////////////////////////////////////////////////

    // mb.app.home.table properties
    o.init = function(){
        handleTambahRow();
        baseAppUrl = mb.baseUrl() + 'master/user_level/';
        handleValidation();
        handleConfirmSave();
        handleMultiSelect();
        // handleDataTable();
        handleDataTable_Persetujuan_Item();
        handleDataTable_Persetujuan_Supplier();
        handleDataTable_Persetujuan_Customer();
        initForm();
        //alert('1');
    };
 }(mb.app.userlevel.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.userlevel.add.init();
});