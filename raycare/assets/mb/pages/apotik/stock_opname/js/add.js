mb.app.stockOpname = mb.app.stockOpname || {};
mb.app.stockOpname.add = mb.app.stockOpname.add || {};

// mb.app.stockOpname.add namespace
(function(o){

    var 
        baseAppUrl          = '',
        $form               = $('#form_add_stock_opname'),
        $tableItemSearch    = $('#table_item_search'),
        $tableTemplate      = $('#table_template'),
        $tableDetail        = $('#table_detail'),
    	$tableStockOpname   = $('#table_add_stock_opname'),
        $popoverItemContent = $('#popover_item_content'),
        $popoverTemplate    = $('#popover_template'),
        $lastPopoverItem    = null,
        tplItemRow          = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter         = 9
        $id                 = $('input#warehouse_id').val();
    ;

    // alert($id);

    var initForm = function(){
    	var 
            $btnSearchItems = $('.search-item', $tableStockOpname),
            $btnTemplate = $('.choose_template', $form),
            $btnDeletes = $('.del-this', $tableStockOpname);


        handleBtnTamplate($btnTemplate);    
        handleBtnSearchItem($btnSearchItems, 'initial');    
        $.each($btnDeletes, function(idx, btn){
            handleBtnDelete( $(btn) );
        });

        addItemRow();
    }

    var addItemRow = function(data){

        // alert(data);
        var numRow = $('tbody tr', $tableStockOpname).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tableStockOpname),
            $newItemRow    = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $classrow      = $('.table-stock-opname', $tableStockOpname),               
            ItemIdAll      = $('input[name$="[item_id]"]', $classrow),
                  
            $btnSearchItem = $('.search-item', $newItemRow)
            ;

        // disable dulu $btnSearchItemResult
        // $btnSearchItemResult.prop('disabled', true);
        if(data != undefined)
        {

            var itemId         = data.item_id;

            found = false;
            // $.each(ItemIdAll,function(idx, value){
            //     // alert(itemId);
            //     if(itemId == this.value)
            //     {
            //         found = true;
            //     }
            // });
            // if(found == true)
            // {
            //     $newItemRow.remove();
            // }

            // if(found == false)
            // {
                
            //     $('input[name$="[item_id]"]', $newItemRow).val(data.item_id);
            //     $('input[name$="[code]"]', $newItemRow).val(data.kode);
            //     $('input[name$="[name]"]', $newItemRow).val(data.nama);                
            //     $('input[name$="[system_qty]"]', $newItemRow).val(data.system_qty);  
            //     $('input[name$="[item_satuan_id]"]', $newItemRow).val(data.item_satuan_id); 
            //     $('input[name$="[satuan_text]"]', $newItemRow).val(data.nama_satuan);              
            // }

                $.each($('.table-stock-opname', $tableStockOpname),function(idx, value){
                    // alert(itemId);
                    
                    if($('input[name$="[item_id]"]', this).val() == itemId && $('input[name$="[item_satuan_id]"]', this).val()==data.item_satuan_id)
                    {
                        found = true;
                    }
                });
                
                if(found == false)
                {
                    $('input[name$="[item_id]"]', $newItemRow).val(data.item_id);
                    $('input[name$="[code]"]', $newItemRow).val(data.kode);
                    $('input[name$="[name]"]', $newItemRow).val(data.nama);
                    $('input[name$="[system_qty]"]', $newItemRow).val(data.system_qty);
                    $('input[name$="[item_satuan_id]"]', $newItemRow).val(data.item_satuan_id);
                    $('input[name$="[satuan_text]"]', $newItemRow).val(data.nama_satuan);
                    
                }
        }
        // handle delete btn
        handleBtnDelete( $('.del-this', $newItemRow) );
        
 
                $('.del-this', $newItemRow).tooltip();
        
        // handle btn use all stock
        // handle button search item
        handleBtnSearchItem($btnSearchItem, 'initial');

    };
   

    var handleDataTableTemplate = function(){
       
        $tableTemplate.dataTable({
            "stateSave"               : true,
            'processing'              : true,
            'serverSide'              : true,
            'sServerMethod'            : 'POST',
             'ajax'                  : {
                'url'  :baseAppUrl + 'listingTemplate/' + $id, 
                'type' : 'POST',
            },  
           
            'pageLength'           : 10,
            'lengthMenu'              : [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            'order'                : [[0, 'asc']],
            'columns'                : [
                { 'name':'stok_opname_template.nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $tableTemplate.on('draw.dt', function (){
            // action for delete access
             $('.btn', this).tooltip();
            $('a[name="view[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          tableDetail.api().ajax.url(baseAppUrl + 'listingDetailTemplate/'+id).load();
                // tableDetail.fnSettings().sAjaxSource = baseAppUrl + 'listingDetailTemplate/'+id;
                // tableDetail.fnClearTable();
            }); 

            $('a[name="select[]"]', this).click(function(){
                    var $anchor = $(this),
                          items    = $anchor.data('item');
 
                    $('tr#item_row_'+(itemCounter-1)).remove();
                    $.each(items, function(key, value) {
                        
                       
                        addItemRow(value);
                    });
                    addItemRow();

                    $('#choose_template').popover('hide');
                    $lastPopoverItem = null;
            }); 
            
        } );
        $('#table_template_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_template_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
        

        $popoverTemplate.hide();        
    };

    var handleDataTableDetailTemplate = function(){
           
        tableDetail = $tableDetail.dataTable({
            "pageLength"               : true,
            'processing'              : true,
            'serverSide'              : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listingDetailTemplate/0',
                'type' : 'POST',
            }, 
            
           
            'pageLength'           : 10,
             'pagingType'            :'full_numbers',
            'lengthMenu'              : [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            'order'                : [[0, 'asc']],
            'columns'                : [
                { 'name':'item.kode','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'item.nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name':'item_satuan.nama','visible' : true, 'searchable': true, 'orderable': true },
                ]
        });   

        $('#table_template_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_template_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown        
    };

    var handleTemplateView = function($btn){
        $btn.on('click', function(e){
            
            $('#data-detail').show();
            e.preventDefault();   
        });     
    };

    var handleTemplateSelect = function($btn){
        $btn.on('click', function(e){
            

            e.preventDefault();   
        });     
    };


    var handleBtnTamplate = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'left',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            $popoverTemplate.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            
            // pindahkan $popoverTemplate ke .popover-conter
            $popContainer.find('.popover-content').append($popoverTemplate);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverTemplate ke .page-content
            $popoverTemplate.hide();
            $popoverTemplate.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
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
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableStockOpname).length == 0){
                        addItemRow();
                    }
            //     }
            // });
            e.preventDefault();
        });
    };



    var handleDataTableItem = function(){
        // alert(id);
       
        $tableItemSearch.dataTable({
            "stateSave"               : true,
            'processing'              : true,
            'serverSide'              : true,
            'ajax'                  : {
                'url' : baseAppUrl + 'listingInventory/' + $id,
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
        //     var $btnSelect = $('a.select', this);
        //     var  $btnSelect2 = $('select[name$="[item_satuan1]"]',this);
           
        //       handleItemSearchSelect($btnSelect,$btnSelect2);
        //     $('.btn', this).tooltip();
            
        // } );

        $popoverItemContent.hide();        
    };

    var handleItemSearchSelect = function($btn,$satuan,row){
        $btn.on('click', function(e){
           
            var 
                $parentPop  = $(this).parents('.popover').eq(0),
                rowId       = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                itemTarget  = $('input:hidden[name="itemTarget"]', $parentPop).val(),
                $row        = $('#'+rowId, $tableStockOpname),
                $classrow   = $('.table-stock-opname', $tableStockOpname),
                $itemIdEl   = $('input[name$="[item_id]"]', $row),
                $itemCodeEl = $('input[name$="[code]"]', $row),
                $itemNameEl = $('input[name$="[name]"]', $row),
                $itemQtyEl = $('input[name$="[system_qty]"]', $row),
                $itemSatuanEl = $('input[name$="[item_satuan_id]"]', $row),
                $itemSatuanTextEl = $('input[name$="[satuan_text]"]', $row),
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

                $.each($('.table-stock-opname', $tableStockOpname),function(idx, value){
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
                    // $.ajax
                    //         ({ 
         
                    //             type: 'POST',
                    //             url: baseAppUrl +  "getidjumlahsistem",  
                    //             data:  {item_satuan_id:$('select[name$="[item_satuan1]"] option:selected', row).val(),item_id:$('a.select', row).data('item').item_id,warehouse_id:$id},  
                    //             dataType : 'json',
                    //             success:function(data)          //on recieve of reply
                    //             { 
                                    
                    //               $('input[name$="[item_id_jumlah_sistem]"]',row).val(data);
                                
                    //             }
                   
                    //         });

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
		        	$('#save', $form).click();
		        	// var win = window.open(mb.baseUrl() +'var/stockopname/stock_opname.pdf', '_blank');
					// win.focus();
		            
		            // $('a#print').click();
		        }
		    });
		});
	};


	var handleEnableCommit = function(){
		$('input[name="remove[]"]', $form).click(function(){
			if($(this).prop('checked'))
			{
				// bootbox.alert('Test');
				$('a#confirm_save', $form).removeAttr('disabled');
			}		
		});
	};

	var handlePopupItem = function(){
		$('a#add_item', $form).click(function(){
			params  = 'width='+screen.width;
		 	params += ', height='+screen.height;
		 	params += ', top=0, left=0'
		 	params += ', fullscreen=yes';


			var win = window.open(mb.baseUrl() +'warehouse/rawGoods/stockOpname/seachItem', "SearchItem", params);
			win.focus();

		});
	};

	var handlePopupUser = function(){
		$('input#add_user', $form).click(function(){
			params  = 'width='+screen.width;
		 	params += ', height='+screen.height;
		 	params += ', top=0, left=0'
		 	params += ', fullscreen=yes';


			var win = window.open(mb.baseUrl() +'warehouse/rawGoods/stockOpname/seachUser', "SearchUSer", params);
			win.focus();

		});
	};

	var handlePopupTemplate = function(){
		$('a#choose_template', $form).click(function(){
			params  = 'width='+screen.width;
		 	params += ', height='+screen.height;
		 	params += ', top=0, left=0'
		 	params += ', fullscreen=yes';


			var win = window.open(mb.baseUrl() +'warehouse/rawGoods/stockOpname/seachTemplate', "SearchUSer", params);
			win.focus();

		});
	};

	var handleShowTemplateName = function(){
		$('input#save_template', $form).click(function(){
			if($(this).prop('checked'))
			{
				$('#template_name', $form).show('slow');
				$('input#template_name', $form).attr('required','required');
			}
			else
			{
				$('#template_name', $form).hide('slow');
				$('input#template_name', $form).removeAttr('required');
			}
				
		});
	};


    var nEditing = null;
    $('#table_template a#view').live('click', function (e) {
        e.preventDefault();

        this.innerHTML = '<i class="fa fa-times"></i>';
        

        // if ($(this).attr("data-mode") == "new") {
        //     var nRow = $(this).parents('tr')[0];
        //     oTable.fnDeleteRow(nRow);
        // } else {
        //     restoreRow(oTable, nEditing);
        //     nEditing = null;
        // }
    });

	
    o.init = function(){
    	$form.validate();
        baseAppUrl = mb.baseUrl() + 'apotik/stock_opname/';
    	handleConfirmSave();
    	handleEnableCommit();
    	// handlePopupItem();
    	handlePopupUser();
    	handleShowTemplateName();
    	// handlePopupTemplate();
        handleDataTableDetailTemplate();
    	handleDataTableItem();
        handleDataTableTemplate();
    	initForm();
    };

}(mb.app.stockOpname.add));


// initialize  mb.app.stockOpname.add
$(function(){
	mb.app.stockOpname.add.init();

});