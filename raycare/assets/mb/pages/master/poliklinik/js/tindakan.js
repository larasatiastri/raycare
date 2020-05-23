 

mb.app.cabang = mb.app.cabang || {};

(function(o){

    var  
     	$form = $('#form_tindakan'),
        baseAppUrl              = '',
        $tableCabang = $('#table_order_item'),
        $tabletindakan = $('#table_addperson'),
        $tableOrderItem         = $('#table_order_item2', $form),
        $tableItemSearch        = $('#table_item_search'),
        $popoverSupplierContent = $('#popover_supplier_content'), 
        $popoverItemContent     = $('#popover_item_content'), 
        $btnSearchSupplier      = $('.search-supplier', $form),
        $btnAddItem             = $('.add-item', $form),
        $lastPopoverItem        = null,
        theadFilterTemplate     = $('#thead-filter-template').text(),
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter             = 1;
         
    var handleDataTable = function() 
    {
    	var bool;
    	if($("#flag").val()==1){
    		bool=true;
    	}else{
    		bool=false;
    	}
    	
    	  oTable=$tableCabang.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_tindakan/' + $("#pk").val() + '/1',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				//{ 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : bool, 'searchable': false, 'orderable': false },
        		]
        });
        $tableCabang.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="select[]"]', this).click(function(){

					var $anchor = $(this),
					      id    = $anchor.data('id');
					     
					oTable2.api().ajax.url(baseAppUrl + 'listing_tindakan_2/' + id).load();
					$("#pk2", $('#modaltindakan')).val(id);
					$("#date", $('#modaltindakan')).val('');
					$("#harga", $('#modaltindakan')).val('');
					 
					if(!$(".alert-danger", $('#modaltindakan')).is(":visible")){
						$(".alert-danger", $('#modaltindakan')).hide("");

					}
					 
					//oTable2.api().ajax.reload();
					//handleDeleteRow(id,msg,1);
			});	

			 
		} );
    }

	var handleDataTable2 = function() 
    {

    	  oTable2=$tabletindakan.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_tindakan_2',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
				//{ 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },

				 
        		]
        });
        $tabletindakan.on('draw.dt', function (){
			// $('.btn', this).tooltip();
			// // action for delete locker
			// $('a[name="select[]"]', this).click(function(){
				 
			// 		var $anchor = $(this),
			// 		      id    = $anchor.data('id');
			// 		      msg    = $anchor.data('confirm');

			// 		handleDeleteRow(id,msg,1);
			// });	

			 
		} );
    }
    var handleDeleteRow = function(id,msg,type){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				//location.href = baseAppUrl + 'delete/' +id;
				$.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "deleteajax",  
                                data:  {id:id,type:type},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                     // $('#accept', $form).click();
                                   mb.showMessage(data[0],data[1],data[2]);
                                    oTable.api().ajax.reload();
                                // oTable.ajax.url(baseAppUrl + 'listing').load();
                                 //$tableCabang.dataTable.ajax.reload();
        						 //  oTable.fnReloadAjax();
                                    //location.href = mb.baseUrl() + 'approval/finance2/';
                                }
                   
                       });
                        // oTable.ajax.url(baseAppUrl + 'listing').load();
                        
			} 
		});
	
	};

	 var handleConfirmSave = function(){
		$('a#confirm_save', $('#modaltindakan')).click(function() {
 
		  if (! $('#modaltindakan').valid()) return;

			var msg = $(this).data('confirm');
		    bootbox.confirm(msg, function(result) {
		        if (result==true) {
		         //   $('#save', $form).click();
                  $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "insertharga",  
                                data:  {tggl:$("#date").val(),harga:$("#harga").val(),id:$("#pk2").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                      
                                   mb.showMessage(data[0],data[1],data[2]);
                                   oTable2.api().ajax.url(baseAppUrl + 'listing_tindakan_2/' + $("#pk2").val()).load();
                                    //location.href = mb.baseUrl() + 'approval/finance2/';
                                    $("#harga").val('');
                                    $("#date").val('');
                                }
                   
                        });
		        }
		    });
		});
	};

	 var handleValidation = function() {
        var error1   = $('.alert-danger', $('#modaltindakan'));
        var success1 = $('.alert-success', $('#modaltindakan'));

        $('#modaltindakan').validate({
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

    var handleDatePickers = function () {
        if (jQuery().datepicker) {
            $('.date-picker', $('#modaltindakan')).datepicker({
                rtl: Metronic.isRTL(),
                format : 'd M yyyy',
                autoclose: true
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var initForm = function(){
        //handleDTItems();
         handleDataTable();
         handleDTItems();
        // handle button add row item
            
 		var 
            $btnsSearchItem = $('.search-item', $tableOrderItem),
            $btnsDelete     = $('.del-this', $tableOrderItem);
 
        $.each($btnsSearchItem, function(idx, btn){

            handleBtnSearchItem($(btn));

        });

        $.each($btnsDelete, function(idx, btn){
            handleBtnDeleteItem($(btn));

        });
     

        for(x=1;x<=$("#jml").val();x++){
            addItemRow(0);
        }
        addItemRow(1);
        var x=0;
 		 
        $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getdataajax",  
                                data:  {pk:$("#pk").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
 
 									$.each(data, function(key, value) {
 
                                          x++;
                                          	
                                        $('#tindakan_idt_' + x).val(value.idt);
                                        $('#tindakan_id_' + x).val(value.id);
                                        $('#tindakan_code_' + x).val(value.kode);
                                        $('#tindakan_nama_' + x).val(value.nama);
                                        $('#tindakan_harga_' + x).val(mb.formatRp(parseFloat(value.harga)));
                                      
                                         
                                    });
                                    
                                }
                   
                       });

  
    };

     var addItemRow = function(y){
        var numRow = $('tbody tr', $tableOrderItem).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tableOrderItem),
            $newItemRow    = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem = $('.search-item', $newItemRow),
            $btnDelete     = $('.del-this', $newItemRow),
            $btnSave    = $('.save-this', $newItemRow),
            $btnDollar    = $('.select', $newItemRow),
            $checkMultiply = $('input:checkbox[name$="[is_multiply]"]', $newItemRow)
        ;

        if(y==0){
        	 $btnSave.hide();
             $btnSearchItem.attr('disabled','disabled');
             $btnDollar.show();
           
        } else
        {
        	//$btnSave.show();
            $btnSave.attr('disabled','disabled');
            $btnDelete.attr('disabled','disabled');
            $btnSearchItem.removeAttr('disabled');
            $btnDollar.hide();
        }

        handleBtnSearchItem($btnSearchItem);
        handleBtnDeleteItem($btnDelete);
        handleBtnSaveItem($btnSave,$btnSearchItem,$btnDollar);
        handleBtnDollar($btnDollar);
       // handleCheck($checkMultiply);
        
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
          // var maxWidth = parseInt(0.9*$('#form_add_cabang').width());
             var maxWidth = 700;

            $popContainer.css({minWidth: maxWidth+'px', maxWidth: maxWidth+'px'});
            // $popContainer.css({minWidth: '720px', maxWidth: '720px'});

           if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

           // $popoverItemContent.show();

        }).on('shown.bs.popover', function(){
  
            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popContent   = $popContainer.find('.popover-content');

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popContent).val(rowId);
            // $('input:hidden[name="itemTarget"]', $popcontent).val(target);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContent);
            $popoverItemContent.show();
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

     var handleTableItemSearchSelectBtn = function(){
            // console.log($btn.length);
        var $btnsSelect = $('a.select', $tableItemSearch);

        $.each($btnsSelect, function(idx, btn){

            $(btn).on('click', function(e){
                var 
                    $parentPop   = $(this).parents('.popover').eq(0),
                    rowId        = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                    $row         = $('#'+rowId, $tableOrderItem),
                    $rowClass    = $('.table_item', $tableOrderItem),
                    $itemIdEl    = $('input[name$="[tindakan_id]"]', $row),
                    ItemIdAll    = $('input[name$="[tindakan_id]"]', $rowClass),
                    $itemCodeEl  = $('input[name$="[code]"]', $row),
                    $itemNameEl  = $('input[name$="[nama]"]', $row), 
                    $itemStock   = $('input[name$="[harga]"]', $row),                   
                                    
                    $itemUnitEl  = $('label.unit', $row),                  
                    $divMultiple = $('label.multiply',$row),
                    itemId       = $(this).data('item').id
                    ;                
                
                               
                // console.log($itemIdEl);
                
                found = false;
                $.each(ItemIdAll,function(idx, value){
                    // alert(itemId);
                    if(itemId == this.value)
                    {
                        found = true;
                    }
                });
                
                if(found == false)
                {
                  //  alert('llll');
                    $itemIdEl.val($(this).data('item').id);
                    $itemCodeEl.val($(this).data('item').kode);
                    $itemNameEl.val($(this).data('item').nama);
                    $itemStock.val(mb.formatRp(parseFloat($(this).data('item').harga)));
                    // $itemStock.val($(this).data('item').stock);
                    // $itemQtyEl.val($(this).data('item').minimum_order);
                    // $itemUnitEl.text($(this).data('item').packaging);
      
                   // $divMultiple.text($(this).data('item').kelipatan_order+'/'+$(this).data('item').minimum_order);
                  // $("#popover_item_content").popover('hide');
                    //$('.search-item', $tableOrderItem).popover('hide');
                    // $('.search-item', $tableOrderItem).popover().on('click', function() {
                    //     alert('hi');
                    //      $(this).popover('hide');
 
                    // });
 
                   // addItemRow();
                   $('.save-this', $row).removeAttr('disabled');
                    $('.del-this', $row).removeAttr('disabled')
                     // $btnSave.removeAttr('disabled');
                     // $btnDelete.removeAttr('disabled');
                }
                $('.search-item', $tableOrderItem).popover('hide');
                 if(found == false){
                    if($row.closest("tr").is(":last-child")) 
                    {
                         addItemRow(1);
                    }
                   // addItemRow();
                }
                 e.preventDefault();   
            });     
        });
        
    };

     var handleBtnDeleteItem = function($btn){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableOrderItem);

        $btn.on('click', function(e){
            if(!$('input[name$="[idt]"]', $row).val())
            {
                $row.remove();
            }else{
                bootbox.confirm('Apakah anda yakin menghapus tindakan ini?', function(result){
                if (result==true) 
                {
                    $.ajax
                        ({ 
                                type: 'POST',
                                url: baseAppUrl +  "deletetindakanajax",  
                                data:  {id:$('input[name$="[idt]"]', $row).val(),pk:$("#pk").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 

                                    //$btn.hide();
                                   mb.showMessage(data[0],data[1],data[2]);
                                }
                   
                       });
                    $row.remove();
                    
                 }
                });
            }
            if($('tbody>tr', $tableOrderItem).length == 0){
                        addItemRow();
             }

            e.preventDefault();
        });

    };

    var handleBtnSaveItem = function($btn,$btn2,$btn3){

        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableOrderItem);

        $btn.on('click', function(e){
        	//alert($("#tindakan_code_" + ).val());
        	// alert(rowId);
        	// alert($('input[name$="[code]"]', $row).val());
            bootbox.confirm('Apakah anda yakin menyimpan tindakan ini?', function(result){
                if (result==true) {
                	$.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "saveajax",  
                                data:  {tindakan_id:$('input[name$="[tindakan_id]"]', $row).val(),pk:$("#pk").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 
                                	$btn.hide();
                                    $btn2.attr('disabled','disabled');
                                    $btn3.show();
                                    
                                    $('input[name$="[idt]"]', $row).val(data[3]);
                                    mb.showMessage(data[0],data[1],data[2]);
                                }
                   
                       });
                    // $row.remove();
                    // if($('tbody>tr', $tableOrderItem).length == 0){
                    //     addItemRow();
                    // }
                }
            });

            e.preventDefault();
        });

    };

var handleBtnDollar = function($btn){
          
        var 
            rowId = $btn.closest('tr').prop('id'),
            $row  = $('#'+rowId, $tableOrderItem);

        $btn.on('click', function(e){
            $("#harga").val('');
         $("#date").val('');
            //alert($("#tindakan_code_" + ).val());
            // alert(rowId);
            $("#pk2").val($('input[name$="[idt]"]', $row).val());
        // alert($('input[name$="[idt]"]', $row).val());

            oTable2.api().ajax.url(baseAppUrl + 'listing_tindakan_2/' + $('input[name$="[idt]"]', $row).val()).load();

            e.preventDefault();
        });

    };

    var handleDTItems = function(){
 
       $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                //{ 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
       

        $tableItemSearch.on('draw.dt', function (){
             handleTableItemSearchSelectBtn();
         } );

      //   //sembunyikan #popover_item_content
        $popoverItemContent.hide();
    }; 
    // mb.app.home.table properties
    o.init = function(){
 
        baseAppUrl = mb.baseUrl() + 'master/poliklinik/';

        //handleDataTable();
        handleDataTable2();
        handleDatePickers();
         handleValidation();
         initForm(); 
        handleConfirmSave();
    };
 }(mb.app.cabang));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.init();
});