mb.app.stok_obat = mb.app.stok_obat || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableStokObat = $('#table_stok_obat');

    var handleDataTable = function() 
    {
    	$tableStokObat.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : true, 'name' : 'item.kode kode', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'item.nama nama', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'sum(inventory.jumlah) jumlah', 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'name' : 'item_satuan.nama unit', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'item.kode kode', 'searchable': false, 'orderable': false },
        		]
        });
        $tableStokObat.on('draw.dt', function (){
						
		} );
    }


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/stok_obat/';
        handleDataTable();
    };
 }(mb.app.stok_obat));


// initialize  mb.app.home.table
$(function(){
    mb.app.stok_obat.init();
});