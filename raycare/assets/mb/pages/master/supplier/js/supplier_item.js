mb.app.pasien = mb.app.pasien || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableSupplierItem = $('#table_supplier_item');
        $supplierId = $('input#supplier_id');

    var handleDataTable = function() 
    {
    	oTable = $tableSupplierItem.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'stateSave'             : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_supplier_item/' + $supplierId.val(),
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
			'columns'               : [
				{ 'visible' : false, 'name' : 'supplier_item.id id','searchable': false, 'orderable': true },
				{ 'visible' : true, 'name' : 'item.kode item_kode','searchable': true, 'orderable': false },
				{ 'visible' : true, 'name' : 'item.nama item_nama','searchable': true, 'orderable': false },
				{ 'visible' : true, 'name' : 'item_satuan.nama satuan_nama','searchable': true, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier_item.id id','searchable': false, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier_item.id id','searchable': false, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier_item.id id','searchable': false, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier_item.id id','searchable': false, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier_item.id id','searchable': false, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier_harga_item.tanggal_efektif tanggal_efektif','searchable': true, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier_item.id id','searchable': false, 'orderable': false },
        		]
        });
        oTable.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id'),
					      msg    = $anchor.data('confirm');

					handleDeleteRow(id, msg);
			});	
			// action for restore region
			$('a[name="restore[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id'),
					      msg    = $anchor.data('confirm');

					handleRestoreRow(id, msg);
			});
			
			$('a[name="not_supply[]"]', this).click(function(){
				var $anchor = $(this),
				      id    = $anchor.data('id');
				      msg    = $anchor.data('confirm');
				bootbox.confirm(msg, function(result) {
					if(result==true) {
						$.ajax({
			                type     : 'POST',
			                url      : baseAppUrl + 'update_status_supply',
			                data     : {supplier_item_id : id, 'is_supply' : '0'},
			                dataType : 'json',
			                beforeSend : function(){
			                    Metronic.blockUI({boxed: true });
			                },
			                success  : function( results ) {
			                        oTable.api().ajax.url(baseAppUrl + 'listing_supplier_item/' + $('input#supplier_id').val()).load();
			                        $('#closeModal').click();
			                },
			                complete : function(){
			                    Metronic.unblockUI();
			                }
			            });
					} 
				});   	
			});

			$('a[name="supply[]"]', this).click(function(){
				var $anchor = $(this),
				      id    = $anchor.data('id');
				      msg    = $anchor.data('confirm');
				bootbox.confirm(msg, function(result) {
					if(result==true) {
						$.ajax({
			                type     : 'POST',
			                url      : baseAppUrl + 'update_status_supply',
			                data     : {supplier_item_id : id, 'is_supply' : '1'},
			                dataType : 'json',
			                beforeSend : function(){
			                    Metronic.blockUI({boxed: true });
			                },
			                success  : function( results ) {
			                        oTable.api().ajax.url(baseAppUrl + 'listing_supplier_item/' + $('input#supplier_id').val()).load();
			                        $('#closeModal').click();
			                },
			                complete : function(){
			                    Metronic.unblockUI();
			                }
			            });
					} 
				});   	
			});	

			$('a[name="not_flexible[]"]', this).click(function(){
				var $anchor = $(this),
				      id    = $anchor.data('id');
				      msg    = $anchor.data('confirm');
				bootbox.confirm(msg, function(result) {
					if(result==true) {
						$.ajax({
			                type     : 'POST',
			                url      : baseAppUrl + 'update_flexible',
			                data     : {supplier_item_id : id, 'is_harga_flexible' : '0'},
			                dataType : 'json',
			                beforeSend : function(){
			                    Metronic.blockUI({boxed: true });
			                },
			                success  : function( results ) {
			                        oTable.api().ajax.url(baseAppUrl + 'listing_supplier_item/' + $('input#supplier_id').val()).load();
			                       // $('#closeModal').click();
			                },
			                complete : function(){
			                    Metronic.unblockUI();
			                }
			            });
					} 
				});   	
			});	

			$('a[name="flexible[]"]', this).click(function(){
				var $anchor = $(this),
				      id    = $anchor.data('id');
				      msg    = $anchor.data('confirm');
				bootbox.confirm(msg, function(result) {
					if(result==true) {
						$.ajax({
			                type     : 'POST',
			                url      : baseAppUrl + 'update_flexible',
			                data     : {supplier_item_id : id, 'is_harga_flexible' : '1'},
			                dataType : 'json',
			                beforeSend : function(){
			                    Metronic.blockUI({boxed: true });
			                },
			                success  : function( results ) {
			                        oTable.api().ajax.url(baseAppUrl + 'listing_supplier_item/' + $('input#supplier_id').val()).load();
			                       // $('#closeModal').click();
			                },
			                complete : function(){
			                    Metronic.unblockUI();
			                }
			            });
					} 
				});   	
			});	

			$('a[name="not_pph[]"]', this).click(function(){
				var $anchor = $(this),
				      id    = $anchor.data('id');
				      msg    = $anchor.data('confirm');
				bootbox.confirm(msg, function(result) {
					if(result==true) {
						$.ajax({
			                type     : 'POST',
			                url      : baseAppUrl + 'update_pph',
			                data     : {supplier_item_id : id, 'is_pph' : '0'},
			                dataType : 'json',
			                beforeSend : function(){
			                    Metronic.blockUI({boxed: true });
			                },
			                success  : function( results ) {
			                        oTable.api().ajax.url(baseAppUrl + 'listing_supplier_item/' + $('input#supplier_id').val()).load();
			                       // $('#closeModal').click();
			                },
			                complete : function(){
			                    Metronic.unblockUI();
			                }
			            });
					} 
				});   	
			});	

			$('a[name="pph[]"]', this).click(function(){
				var $anchor = $(this),
				      id    = $anchor.data('id');
				      msg    = $anchor.data('confirm');
				bootbox.confirm(msg, function(result) {
					if(result==true) {
						$.ajax({
			                type     : 'POST',
			                url      : baseAppUrl + 'update_pph',
			                data     : {supplier_item_id : id, 'is_pph' : '1'},
			                dataType : 'json',
			                beforeSend : function(){
			                    Metronic.blockUI({boxed: true });
			                },
			                success  : function( results ) {
			                        oTable.api().ajax.url(baseAppUrl + 'listing_supplier_item/' + $('input#supplier_id').val()).load();
			                       // $('#closeModal').click();
			                },
			                complete : function(){
			                    Metronic.unblockUI();
			                }
			            });
					} 
				});   	
			});
		} );
    }

    var handleDeleteRow = function(id, msg){
			bootbox.confirm(msg, function(result) {
				if(result==true) {
					  location.href = baseAppUrl + 'delete_supplier_item/' + id + '/' + $('input#supplier_id').val();
				} 
			});   	
	};

	var handleRestoreRow = function(id, msg){
		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'restore_supplier_item/' + id + '/' + $('input#supplier_id').val();
			} 
		});
	};

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/supplier/';
        handleDataTable();
    };
 }(mb.app.pasien));


// initialize  mb.app.home.table
$(function(){
    mb.app.pasien.init();
});