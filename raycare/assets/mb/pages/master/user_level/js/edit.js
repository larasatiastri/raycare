mb.app.userlevel = mb.app.userlevel || {};
mb.app.userlevel.edit = mb.app.userlevel.edit || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form                   = $('#form_edit_user_level');
        $tableUserlevel         = $('#table_user_level');
        $tambahRow              = $('#tambahRow');
        $tableUserlevel_item        = $('#table_user_level_item');
        $tableUserlevel_supplier    = $('#table_user_level_supplier');
        $tableUserlevel_customer    = $('#table_user_level_customer');
        $tablePersetujuan           = $('#table_persetujuan', $form);
        $tablePersetujuanBiaya           = $('#table_persetujuan_biaya', $form);
        $tablePersetujuanItem       = $('#table_persetujuan_item', $form);
        $tablePersetujuanCustomer   = $('#table_persetujuan_customer', $form);
        $tablePersetujuanSupplier   = $('#table_persetujuan_supplier', $form);

        $lastPopoverItem        = null,
        // $lastPopoverSupplier    = null,
        tplItemRow                      = $.validator.format( $('#tpl_item_row').text() ),
        tplItemRowPersetujuanBiaya                      = $.validator.format( $('#tpl_item_row_biaya').text() ),
        numRow                          = $('tbody tr', $tablePersetujuan).length,
        tplItemRowPersetujuanItem       = $.validator.format( $('#tpl_item_row_item').text() ),
        numRowPersetujuanItem           = $('tbody tr', $tablePersetujuanItem).length,
        tplItemRowPersetujuanSupplier   = $.validator.format( $('#tpl_item_row_supplier').text() ),
        numRowPersetujuanSupplier       = $('tbody tr', $tablePersetujuanSupplier).length,
        tplItemRowPersetujuanCustomer   = $.validator.format( $('#tpl_item_row_customer').text() ),
        numRowPersetujuanCustomer       = $('tbody tr', $tablePersetujuanCustomer).length,
        itemCounter                     = 0 + parseInt($('input#numRow').val()),
        itemCounterBiaya                = 0 + parseInt($('input#numRowBiaya').val()),
        itemCounterPersetujuanItem      = 1 + numRowPersetujuanItem,
        itemCounterPersetujuanSupplier  = 1 + numRowPersetujuanSupplier,
        itemCounterPersetujuanCustomer  = 1 + numRowPersetujuanCustomer

        ;

    var initForm = function(){
        //alert("a");
        // tambah 1 row kosong pertama
        $btnDelete = $('a.del-this', $tablePersetujuan);
        $btnDeletesDb = $('a.del-item-db', $tablePersetujuan),
        
        $.each($btnDelete, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        $.each($btnDeletesDb, function(idx, btn){
            handleBtnDeleteDB( $(btn) );
        });

        $btnDeleteBiaya = $('a.del-this', $tablePersetujuanBiaya);
        $btnDeleteBiayaDb = $('a.del-this-db', $tablePersetujuanBiaya);


        $.each($btnDeleteBiaya, function(idx, btn){
            handleBtnDeleteBiaya( $(btn) );
        });

        $.each($btnDeleteBiayaDb, function(idx, btn){
            handleBtnDeleteBiayaDb( $(btn) );
        });

        //////

        $btnDeletePersetujuanItem = $('a.del-this', $tablePersetujuanItem);
        $btnDeletesDbPersetujuanItem = $('a.del-item-db', $tablePersetujuanItem),
        
        $.each($btnDeletePersetujuanItem, function(idx, btn){
            handleBtnDeletePersetujuanItem( $(btn) );
        });

        $.each($btnDeletesDbPersetujuanItem, function(idx, btn){
            handleBtnDeleteDBPersetujuanItem( $(btn) );
        });

        //////

        $btnDeletePersetujuanSupplier = $('a.del-this', $tablePersetujuanSupplier);
        $btnDeletesDbPersetujuanSupplier = $('a.del-item-db', $tablePersetujuanSupplier),
        
        $.each($btnDeletePersetujuanSupplier, function(idx, btn){
            handleBtnDeletePersetujuanSupplier( $(btn) );
        });

        $.each($btnDeletesDbPersetujuanSupplier, function(idx, btn){
            handleBtnDeleteDBPersetujuanSupplier( $(btn) );
        });

        //////

        $btnDeletePersetujuanCustomer = $('a.del-this', $tablePersetujuanCustomer);
        $btnDeletesDbPersetujuanCustomer = $('a.del-item-db', $tablePersetujuanCustomer),
        
        $.each($btnDeletePersetujuanCustomer, function(idx, btn){
            handleBtnDeletePersetujuanCustomer( $(btn) );
        });

        $.each($btnDeletesDbPersetujuanCustomer, function(idx, btn){
            handleBtnDeleteDBPersetujuanCustomer( $(btn) );
        });

        //////

        //alert(itemCounter);

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

    //////////////////////////////////////////////////////////////////////

    var addItemRow = function(){

        var numRow = $('tbody tr', $tablePersetujuan).length;
        console.log('numrow' + numRow);
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
        // handle delete btn
        handleBtnDeletePersetujuanCustomer( $('a.del-this', $newItemRow) );
    };
    
    /////////////////////////////////////////////////////////////////////////
    

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

    ////////////////////////////////////////////////////////////////////////////////

    var handleBtnDelete = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuan);

        $btn.on('click', function(e){
            //alert(rowId);
            //bootbox.confirm('Are you sure want to delete this item?', function(result){
                //if (result==true) {
                    $row.remove();
                    if($('tbody>tr>add_row', $tablePersetujuan).length == 0){
                        addItemRow();
                    }
                    // focusLastItemCode();
                    //calculateTotal();
                //}
            //});
            // e.preventDefault();
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
                        if($('tbody>tr>add_row', $tablePersetujuanItem).length == 0){
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
                        if($('tbody>tr>add_row', $tablePersetujuanSupplier).length == 0){
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
                        if($('tbody>tr>add_row', $tablePersetujuanCustomer).length == 0){
                            addItemRowPersetujuanCustomer();
                        }
                        // focusLastItemCode();
                     // }
                // });
            
            
            e.preventDefault();
        });
    };

    /////////////////////////////////////////////////////////////////////////////////

    var handleBtnDeleteDB = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuan);

        $btn.on('click', function(e){
            bootbox.confirm('Apakah anda yakin menghapus persetujuan ini?', function(result){
                if (result==true) {

                    $('input[name$="[delete]"]', $row).attr('value',1);
                    $row.hide(); //hide
                    if($('tbody>tr', $tablePersetujuan).length == 0){
                        addItemRow();
                    }
                }
            });
            e.preventDefault();
        });
    };

    var handleBtnDeleteBiayaDb = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuanBiaya);

        $btn.on('click', function(e){
            bootbox.confirm('Apakah anda yakin menghapus persetujuan ini?', function(result){
                if (result==true) {

                    $('input[name$="[delete]"]', $row).attr('value',1);
                    $row.hide(); //hide
                    if($('tbody>tr', $tablePersetujuanBiaya).length == 0){
                        addItemRowBiaya();
                    }
                }
            });
            e.preventDefault();
        });
    };

    var handleBtnDeleteDBPersetujuanItem = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuanItem);

        $btn.on('click', function(e){
            bootbox.confirm('Apakah anda yakin menghapus persetujuan ini?', function(result){
                if (result==true) {

                    $('input[name$="[delete]"]', $row).attr('value',1);
                    $row.hide(); //hide
                    if($('tbody>tr', $tablePersetujuanItem).length == 0){
                        addItemRow();
                    }
                }
            });
            e.preventDefault();
        });
    };

    var handleBtnDeleteDBPersetujuanSupplier = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuanSupplier);

        $btn.on('click', function(e){
            bootbox.confirm('Apakah anda yakin menghapus persetujuan ini?', function(result){
                if (result==true) {

                    $('input[name$="[delete]"]', $row).attr('value',1);
                    $row.hide(); //hide
                    if($('tbody>tr', $tablePersetujuanSupplier).length == 0){
                        addItemRow();
                    }
                }
            });
            e.preventDefault();
        });
    };

    var handleBtnDeleteDBPersetujuanCustomer = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tablePersetujuanCustomer);

        $btn.on('click', function(e){
            bootbox.confirm('Apakah anda yakin menghapus persetujuan ini?', function(result){
                if (result==true) {

                    $('input[name$="[delete]"]', $row).attr('value',1);
                    $row.hide(); //hide
                    if($('tbody>tr', $tablePersetujuanCustomer).length == 0){
                        addItemRow();
                    }
                }
            });
            e.preventDefault();
        });
    };

    //////////////////////////////////////////////////////////////////////////////////////////////////

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
            bootbox.confirm(msg, function(result) {
		        if (result==true) {
                    i = parseInt(i) + 1;
                    if(i === 1)
                    {
    		            $.ajax({
                            type     : 'POST',
                            url      : baseAppUrl + 'check_modified',
                            data     : {id:$('input[name="id"]').val(), modified_date : $('input[name="modified_date"]').val()},
                            dataType : 'json',
                            beforeSend : function(){
                                Metronic.blockUI({boxed: true });
                            },
                            success  : function( results ) {
                               if(results.success == true)
                               {
                                  $('#save', $form).click();
                               }    
                               else
                               {
                                    bootbox.confirm(results.msg, function(result) {
                                        if(result == true)
                                        {
                                            window.open($('#open_new_tab', $form).attr("href"));
                                        }
                                    });
                               }
                            },
                            complete : function(){
                                Metronic.unblockUI();
                            }
                        });                        
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

    /////////////////////////////////////////

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



    ////////////////////////////////////////////////////////////////////////////

    var handleItemSearchSelect = function($btn){
        $btn.on('click', function(e){
            //alert('a');
            //var numRow = $('tbody tr', $tablePersetujuan).length;
            //alert(numRow);
            var numRow = itemCounter++ - 1;
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
            var numRow = itemCounterPersetujuanItem++ - 1;
            // var numRow = itemCounterPersetujuanItem -1;
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
            var numRow = itemCounterPersetujuanSupplier++ - 1;
            // var numRow = itemCounterPersetujuanSupplier -1;
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
            var numRow = itemCounterPersetujuanCustomer++ - 1;
            // var numRow = itemCounterPersetujuanCustomer -1;
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

    /////////////////////////////////////////////////////////////////////////////

    // mb.app.home.table properties
    o.init = function(){
        handleTambahRow();
        baseAppUrl = mb.baseUrl() + 'master/user_level/';
        handleValidation();
        handleConfirmSave();
        handleMultiSelect();
        handleDataTable();
        handleDataTable_Persetujuan_Item();
        handleDataTable_Persetujuan_Supplier();
        handleDataTable_Persetujuan_Customer();
        initForm();
        //alert('1');
    };
 }(mb.app.userlevel.edit));


// initialize  mb.app.home.table
$(function(){
    mb.app.userlevel.edit.init();
});