mb.app.cabang = mb.app.cabang || {};
mb.app.cabang.add = mb.app.cabang.add || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form = $('#form_add_cabang');

        $errorTop               = $('.alert-danger', $form),
        $successTop             = $('.alert-success', $form),
        $tableOrderItem         = $('#table_order_item', $form),
        $tableSupplierSearch    = $('#table_supplier_search'),
        $tableItemSearch        = $('#table_item_search'),
        $popoverSupplierContent = $('#popover_supplier_content'), 
        $popoverItemContent     = $('#popover_item_content'), 
        $btnSearchSupplier      = $('.search-supplier', $form),
        $btnAddItem             = $('.add-item', $form),
        $lastPopoverItem        = null,
        theadFilterTemplate     = $('#thead-filter-template').text(),
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        itemCounter             = 1
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

    var initForm = function(){
        handleDTItems();
        

        // handle button add row item
        $btnAddItem.on('click', function(e){
            addItemRow();
             
            e.preventDefault();
        });        

        var 
            $btnsSearchItem = $('.search-item', $tableOrderItem),
            $btnsDelete     = $('.del-this', $tableOrderItem);
 
        $.each($btnsSearchItem, function(idx, btn){

            handleBtnSearchItem($(btn));

        });

        $.each($btnsDelete, function(idx, btn){
            handleBtnDeleteItem($(btn));

        });

        // tambah 1 row order item kosong
        for(x=1;x<=$("#jml").val();x++){
            addItemRow();
        }
        addItemRow();
        var y=0;
        $.ajax
                        ({ 
         
                                type: 'POST',
                                url: baseAppUrl +  "getdata",  
                                data:  {id:$("#pk").val()},  
                                dataType : 'json',
                                success:function(data)          //on recieve of reply
                                { 

                                     $.each(data, function(key, value) {
                                        
                                        y++;
                                        $('#tindakan_id_' + y).val(value.id);
                                        $('#tindakan_nama_' + y).val(value.nama);
                                        $('#tindakan_code_' + y).val(value.kode);
                                        $('#tindakan_harga_' + y).val(value.kode);
                                         
                                        });
                                }
                   
                       });
        
    };

     var addItemRow = function(){
        var numRow = $('tbody tr', $tableOrderItem).length;

        // console.log('numrow' + numRow);
        // cek baris terakhir bener apa nggak?? ga ada yg tau
        
        // if( numRow > 0 && ! isValidLastRow() ) return;

        var 
            $rowContainer  = $('tbody', $tableOrderItem),
            $newItemRow    = $(tplItemRow(itemCounter++)).appendTo( $rowContainer ),
            $btnSearchItem = $('.search-item', $newItemRow),
            $btnDelete     = $('.del-this', $newItemRow),
            $checkMultiply = $('input:checkbox[name$="[is_multiply]"]', $newItemRow)
            ;

        handleBtnSearchItem($btnSearchItem);

        handleBtnDeleteItem($btnDelete);
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
      //   oTabelItem = $tableItemSearch.dataTable({
      //       'bProcessing'              : true,
      //       'bServerSide'              : true,
      //       'sServerMethod'            : 'POST',
      //       'oLanguage'                : mb.DTLanguage(),
      //       'sAjaxSource'              : baseAppUrl + 'listing_item',
      //       'iDisplayLength'           : 10,
      //       'aLengthMenu'              : [[2, 5, 10, 25, 50, 100], [2, 5, 10, 25, 50, 100]],
      //       'aaSorting'                : [[0, 'asc']],
      //       'aoColumns'                : [
      //           { 'bVisible' : true, 'bSearchable': false, 'bSortable': false },
      //           { 'bVisible' : true, 'bSearchable': true, 'bSortable': true },
      //           { 'bVisible' : true, 'bSearchable': true, 'bSortable': true },
                
      //           { 'bVisible' : true, 'bSearchable': false, 'bSortable': false },
      //           ]
      //   });
      //   $('#table_item_search_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
      //   $('#table_item_search_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

      // //  handleTableItemSearchSelectBtn();

        $tableItemSearch.on('draw.dt', function (){
             handleTableItemSearchSelectBtn();
         } );

      //   //sembunyikan #popover_item_content
        $popoverItemContent.hide();
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
                    $itemStock.val($(this).data('item').harga);
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
                }
                $('.search-item', $tableOrderItem).popover('hide');
                 if(found == false){
                     if($row.closest("tr").is(":last-child")) 
                    {
                         addItemRow();
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
            // bootbox.confirm('Are you sure want to delete this item?', function(result){
            //     if (result==true) {
                    $row.remove();
                    if($('tbody>tr', $tableOrderItem).length == 0){
                        addItemRow();
                    }
            //     }
            // });

            e.preventDefault();
        });

    };

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/poliklinik/';
       
     //   alert('hi');
        handleValidation();
          initForm(); 
      //    handleDTItems();
        handleConfirmSave();
    };
 }(mb.app.cabang.add));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.add.init();
});