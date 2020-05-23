mb.app.pengajuan_pembayaran_kasbon = mb.app.pengajuan_pembayaran_kasbon || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form   = $('#form_index'),
        $table1 = $('#table_pengajuan_pembayaran_kasbon'),
        $table2 = $('#table_pengajuan_pembayaran_kasbon_history'),
        $lastPopoverItem = null
        ;

    var handleDataTable = function() 
    {
        $table1.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'desc']],
            'filter'                : true,
            'paginate'              : true,
            'info'                  : true,
            'pagingType'            : 'full_numbers',
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $table1.on('draw.dt', function (){
        });
    }

    var handleDataTableHistory = function() 
    {
    	$table2.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_history',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
            'filter'                : true,
            'paginate'              : true,
            'info'                  : true,
            'pagingType'            : 'full_numbers',
			'columns'               : [
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $table2.on('draw.dt', function (){
		});
    }

	

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pengajuan_pembayaran_kasbon/';
        handleDataTable();
        handleDataTableHistory();
    };
 }(mb.app.pengajuan_pembayaran_kasbon));


// initialize  mb.app.home.table
$(function(){
    mb.app.pengajuan_pembayaran_kasbon.init();
});