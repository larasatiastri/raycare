mb.app.stockOpname = mb.app.stockOpname || {};
mb.app.stockOpname.stockOpname = mb.app.stockOpname.stockOpname || {};

// mb.app.stockOpname.stockOpname namespace
(function(o){

    var $form = $('#form_konfirmasiInputResult');
	var $tableKonfirmasi = $('#table_konfirmasi');
	var baseAppUrl = '';

    var handleDataTable = function(){
    // alert($('input#warehouse_id').val());
       
        //setup datatable
        oTable = $tableKonfirmasi.dataTable({
              'processing'              : true,
              'serverSide'              : true,
              'ajax'                  : {
                'url' : baseAppUrl + 'listingItemKonfirmasi/' + $('input#id').val() + '/' + $('input#warehouse_id').val(),
                'type' : 'POST',
            },   
            
              'pageLength'           : 10,
              'lengthMenu'              : [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
              'order'                : [[0, 'asc']],
              'columns'                : [
                { 'name' : 'item.kode', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item_satuan.nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
              ]
        });//.fnSetFilteringDelay(500);
        $('#table_konfirmasi_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_konfirmasi_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline");

        $tableKonfirmasi.on('draw.dt', function (){
            $('a.edit', this).click(function(){
                var $anchor = $(this),
                    id    = $anchor.data('id');

                handleEditRow(id);
            }); 

            $('a.cancel', this).click(function(){
                var $anchor = $(this),
                    id    = $anchor.data('id');

                handleCancelRow(id);
            }); 

            $('a.save', this).click(function(){
                var $anchor = $(this),
                    id    = $anchor.data('id'),
                    msg   = $anchor.data('confirm');

                handleSaveRow(id, msg);
            }); 
        })
    
    };

	var handleEditRow = function(id){
        $('input#input_qty_'+id).removeClass('hidden');
        $('span#counted_qty_'+id).addClass('hidden');
        
        $('a#btn_edit_'+id).addClass('hidden');
        $('a#btn_save_'+id).removeClass('hidden');
        $('a#btn_cancel_'+id).removeClass('hidden');
    }

    var handleCancelRow = function(id){
        $('input#input_qty_'+id).addClass('hidden');
        $('span#counted_qty_'+id).removeClass('hidden');
        
        $('a#btn_edit_'+id).removeClass('hidden');
        $('a#btn_save_'+id).addClass('hidden');
        $('a#btn_cancel_'+id).addClass('hidden');

    }

    var handleSaveRow = function(id, msg){

        var input_value = $('input#input_qty_'+id).val();
        $.ajax({
            type: "POST",
            url: baseAppUrl + "save_qty",
            data: {id: id, value: input_value},
            success:  function(data){
                // alert("---"+data);
                bootbox.alert(msg);
                // window.location.reload(true);
                oTable.fnDraw();
            }
        });

        // oTable.fnDraw();

    }

    var handleConfirmSave = function(){
		$('a#confirm_save', $form).click(function() {
			if (! $form.valid()) return;

			var msg = $(this).data('confirm');
		    bootbox.confirm(msg, function(result) {
		        if (result==true) {
		            $('#save', $form).click();
                    // window.location.href = baseAppUrl;
		        }
		    });
		});
	};
	
    o.init = function(){
    	$form.validate();
    	baseAppUrl = mb.baseUrl() + 'apotik/stock_opname/';
        handleDataTable();
        handleConfirmSave();
    };

}(mb.app.stockOpname.stockOpname));


// initialize  mb.app.stockOpname.stockOpname
$(function(){
	mb.app.stockOpname.stockOpname.init();

});