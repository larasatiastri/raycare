mb.app.stockOpname = mb.app.stockOpname || {};
mb.app.stockOpname.edit = mb.app.stockOpname.edit || {};

// mb.app.stockOpname.edit namespace
(function(o){

    var $form               = $('#form_edit_stock_opname'),
        baseAppUrl          = '',
    	$tableItemSearch    = $('#table_item_search'),
    	$tableStockOpname   = $('#table_edit_stock_opname'),
        $popoverItemContent = $('#popover_item_content'),
        $lastPopoverItem    = null,
        tplItemRow          = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter         = 9
        $id                 = $('input#warehouse_id').val();
    ;


    var initForm = function(){
        // alert($('input#id').val());
    	var 
            $btnSearchItems = $('.search-item', $tableStockOpname),
            // $btnTemplate = $('.choose_template', $form),
            $btnDeletes = $('.del-this', $tableStockOpname);
            $btnDeletesItemDb = $('.del-item-db', $tableStockOpname);


        // handleBtnTamplate($btnTemplate);    
        handleBtnSearchItem($btnSearchItems, 'initial');  

        $.each($btnDeletes, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        $.each($btnDeletesItemDb, function(idx, btn){
            handleBtnDeleteDB( $(btn) );
        });

        addItemRow();
    }

    var addItemRow = function(){

        var numRow = $('tbody tr', $tableStockOpname).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer         = $('tbody', $tableStockOpname),
            $newItemRow           = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),

            $btnSearchItem        = $('.search-item', $newItemRow)

            // $inputNumber          = $('input[name$="[qty]"], input[name$="[cost]"]', $newItemRow)
            ;

        // disable dulu $btnSearchItemResult
        // $btnSearchItemOld.prop('disabled', true);

        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
        $('.del-this', $newItemRow).tooltip();

        // handle btn use all stock
        // handleBtnUseAllStock( $('.use-all-stock', $newItemRow) );
        // handle button search item
        handleBtnSearchItem($btnSearchItem, 'initial');
        // handleBtnSearchItem($btnSearchItemResult, 'result');

    };

    var handleDataTableItem = function(){
         
        $tableItemSearch.dataTable({
            "stateSave"               : true,
            'processing'              : true,
            'serverSide'              : true,
            'ajax'                  : {
                'url' :  baseAppUrl + 'listingInventory/' + $id,
                'type' : 'POST',
            }, 
           
            'pageLength'           : 10,
            'lengthMenu'              : [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            'order'                : [[0, 'asc']],
            'columns'                : [
               { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false }
                ],
             'createdRow' : function( row, data, dataIndex ) {
                     var $btnSelect = $('a.select', row);
                     var $btnSelect2 = $('select[name$="[item_satuan1]"]',row);
                     var $btnSelect3 = $('select[name$="[item_satuan1]"] option:selected', row);

                      $.ajax
                            ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getjumlahsistem",  
                                data:  {item_satuan_id:$('select[name$="[item_satuan1]"]',row).val(),item_id:$('a.select', row).data('item').item_id,warehouse_id:$id},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                    
                                  $('input[name$="[item_jumlah_sistem]"]',row).val(data);
                                
                                }
                   
                            });

                     $('select[name$="[item_satuan1]"]',row).on( 'change', function () {
                        
                          $.ajax
                            ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getjumlahsistem",  
                                data:  {item_satuan_id:this.value,item_id:$('a.select', row).data('item').item_id,warehouse_id:$id},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                    
                                  $('input[name$="[item_jumlah_sistem]"]',row).val(data);
                                
                                }
                   
                            });
                     });
                    handleItemSearchSelect($btnSelect,$btnSelect2,row);
                }
        });      
        $('#table_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        // var $btnSelects = $('a.select', $tableItemSearch);
        // handleItemSearchSelect( $btnSelects );

        // $tableItemSearch.on('draw.dt', function (){
        //     $('.btn', this).tooltip();
        //     var $btnSelect = $('a.select', this);
        //     handleItemSearchSelect( $btnSelect );
            
        // } );

        $popoverItemContent.hide();        
    };


    var handleBtnSearchItem = function($btn, target){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/><input type="hidden" name="itemTarget"/>'

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
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            $('input:hidden[name="itemTarget"]', $popcontent).val(target);
            
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

    var handleBtnDelete = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableStockOpname);

        $btn.on('click', function(e){
            $row.remove();
            addItemRow();

            if($('tbody>tr', $tableStockOpname).length == 0){
                addItemRow();
            }
            e.preventDefault();
        });
    };

    var handleBtnDeleteDB = function($btn){
        var 
            rowId    = $btn.closest('tr').prop('id'),
            $row     = $('#'+rowId, $tableStockOpname);

        $btn.on('click', function(e){
            bootbox.confirm('Are you sure want to delete this item?', function(result){
                if (result==true) {

                    $('input[name$="[is_deleted]"]', $row).val(1);   

                    $row.hide(); //hide
                    if($('tbody>tr', $tableStockOpname).length == 0){
                        addItemRow();
                    }
                }
            });
            e.preventDefault();
        });
    };


   var handleItemSearchSelect = function($btn,$satuan,row){
        $btn.on('click', function(e){
           
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                itemTarget  = $('input:hidden[name="itemTarget"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableStockOpname),
                $classrow   = $('.table-edit-stock-opname', $tableStockOpname),
                $itemIdEl   = $('input[name$="[item_id]"]', $row),
                $itemCodeEl = $('input[name$="[code]"]', $row),
                $itemNameEl = $('input[name$="[name]"]', $row),
                $itemQtyEl = $('input[name$="[system_qty]"]', $row),
                $itemSatuanEl = $('input[name$="[item_satuan_id]"]', $row),
                $itemSatuanTextEl = $('input[name$="[item_satuan_text]"]', $row),
                ItemIdAll = $('input[name$="[item_id]"]', $classrow)
                itemId = $(this).data('item').item_id
                ;                
                // console.log($itemIdEl);
                
                found = false;
                // $.each(ItemIdAll,function(idx, value){
                //     // alert(itemId);
                //     if(itemId == this.value)
                //     {
                //         found = true;
                //     }
                // });
 
                $.each($('.table-edit-stock-opname', $tableStockOpname),function(idx, value){
                    // alert(itemId);
                    
                    if($('input[name$="[item_id]"]', this).val() == itemId && $('input[name$="[item_satuan_id]"]', this).val()==$satuan.val())
                    {
                        found = true;
                    }
                });
                
                if(found == false)
                {
                    $itemIdEl.val($(this).data('item').item_id);
                    $itemCodeEl.val($(this).data('item').code);
                    $itemNameEl.val($(this).data('item').name);
                   // $itemQtyEl.val($(this).data('item').system_qty);
                   $itemQtyEl.val($('input[name$="[item_jumlah_sistem]"]',row).val());
                    $itemSatuanEl.val($satuan.val());
                    $itemSatuanTextEl.val($('select[name$="[item_satuan1]"] option:selected', row).text());
                    // $itemQtyEl.val($(this).data('item').qty);
                   

                    addItemRow();
                    
                }
             $('.search-item', $tableStockOpname).popover('hide');
            e.preventDefault();   
        });     
    };

    var isValidLastRow = function(){
        
        var 

                $itemCodeEls = $('input[name$="[code]"]', $tableStockOpname),
                itemCode    = $itemCodeEls.eq($itemCodeEls.length-1).val()
            ;
            return (itemCode != '');

    };

    var handleConfirmSave = function(){
		$('a#confirm_save', $form).click(function() {
			if (! $form.valid()) return;

			var msg = $(this).data('confirm');
		    bootbox.confirm(msg, function(result) {
		        if (result==true) {
                     $.ajax
                            ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "checkmodifieddate",  
                                data:  {modifieddate:$("#modifieddate").val(),id:$("#id").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                    
                                    if(data=='sukses')
                                    {
                                        $('#save', $form).click();
                                    }else{
                                         bootbox.confirm("data sudah dirubah", function(result) {
                                            if (result==true) 
                                            {

                                                window.open(baseAppUrl + '/edit/' + $("#id").val() + '/' + $("#pk").val() + '/' + $("#pk2").val(),'_blank');
                                            }
                                        })
                                    }
                                
                                }
                   
                            });

		          //  $('#save', $form).click();
		            // var win = window.open(mb.baseUrl() +'var/stockopname/stockopname.pdf', '_blank');
					// win.focus();
		        }
		    });
		});
	};


	

	

	
    o.init = function(){
    	$form.validate();
        baseAppUrl = mb.baseUrl() + 'apotik/stock_opname/';
    	handleConfirmSave();
        handleDataTableItem();
    	initForm();
    };

}(mb.app.stockOpname.edit));


// initialize  mb.app.stockOpname.edit
$(function(){
	mb.app.stockOpname.edit.init();

});