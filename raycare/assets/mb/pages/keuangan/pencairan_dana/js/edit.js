mb.app.permintaan_biaya = mb.app.permintaan_biaya || {};
mb.app.permintaan_biaya.edit = mb.app.permintaan_biaya.edit || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form = $('#form_edit_user_level');
        $tableUserlevel = $('#table_user_level');
        $tambahRow = $('#tambahRow');
        $tablePersetujuan       = $('#table_persetujuan', $form);

        $lastPopoverItem        = null,
        // $lastPopoverSupplier    = null,
        tplItemRow              = $.validator.format( $('#tpl_item_row').text() ),
        numRow = $('tbody tr', $tablePersetujuan).length,
        itemCounter             = 1 + numRow;
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

        //alert(itemCounter);

        addItemRow();  
    };

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
        // handle delete btn
        handleBtnDelete( $('a.del-this', $newItemRow) );
    };

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

    var handleTambahRow = function(){
        $('a#tambah_row').click(function() {
            addItemRow();
        });
    };

    var handleMultiSelect = function () {
        $('#multi_select').multiSelect();   
    };

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

    var handleDatePickers = function () {
        var time = new Date($('#tanggal').val());
        if (jQuery().datepicker) {
            $('.date-picker', $form).datepicker({
                rtl: Metronic.isRTL(),
                format : 'dd-M-yyyy',
                orientation: "left",
                autoclose: true,
                update : time

            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    // mb.app.home.table properties
    o.init = function(){
        handleTambahRow();
        baseAppUrl = mb.baseUrl() + 'keuangan/permintaan_biaya/';
        handleValidation();
        handleConfirmSave();
        handleMultiSelect();
        handleDataTable();
        handleDatePickers();
        initForm();
        //alert('1');
    };
 }(mb.app.permintaan_biaya.edit));


// initialize  mb.app.home.table
$(function(){
    mb.app.permintaan_biaya.edit.init();
});