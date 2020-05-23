mb.app.buat_invoice = mb.app.buat_invoice || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableInvoice = $('#table_invoice');

    var handleDataTable = function() 
    {
    	$tableInvoice.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'pagingType'		    : 'full_numbers',
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},		
			'stateSave'				: true,			
			'pageLength'			: 25,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'name':'invoice.created_date tanggal','visible' : true, 'searchable': false, 'orderable': false },
				{ 'name':'invoice.waktu_tindakan waktu','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name':'invoice.no_invoice no_invoice','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name':'invoice.jenis_invoice jenis_invoice','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name':'invoice.nama_penjamin nama_penjamin','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name':'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name':'user.nama nama_resepsionis','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name':'invoice.harga harga','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name':'invoice.id id','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tableInvoice.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg    = $anchor.data('confirm');

					handleDeleteRow(id,msg);
			});

			$('a[name="restore[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg    = $anchor.data('confirm');

					handleRestoreRow(id,msg);
			});	
		
		} );
    }

    var handleDeleteRow = function(id,msg){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'delete/' +id;
			} 
		});
	
	};

	var handleRestoreRow = function(id,msg){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'restore/' +id;
			} 
		});
	
	};

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'reservasi/buat_invoice/';
        handleDataTable();
    };
 }(mb.app.buat_invoice));


// initialize  mb.app.home.table
$(function(){
    mb.app.buat_invoice.init();
});