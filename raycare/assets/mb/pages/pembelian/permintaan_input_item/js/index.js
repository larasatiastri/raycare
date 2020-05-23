mb.app.input_item = mb.app.input_item || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableCabang = $('#table_input_item');

    var handleDataTable = function() 
    {
    	$tableCabang.dataTable({
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
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tableCabang.on('draw.dt', function (){
						
		});
    }


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/permintaan_input_item/';
        handleDataTable();
    };
 }(mb.app.input_item));


// initialize  mb.app.home.table
$(function(){
    mb.app.input_item.init();
});