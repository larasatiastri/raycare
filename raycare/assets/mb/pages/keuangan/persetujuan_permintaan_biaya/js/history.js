mb.app.persetujuan_permintaan_biaya = mb.app.persetujuan_permintaan_biaya || {};
(function(o){

    var 
        baseAppUrl              = '',
        $form   = $('#form_index'),
        $table1 = $('#table_persetujuan_permintaan_pembayaran_history'),
        $lastPopoverItem = null
        ;

    var handleDataTable = function() 
    {
    	$table1.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_history',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
            'filter'                : true,
            'paginate'              : true,
            'info'                  : false,
            'pagingType'            : 'full_numbers',
			'columns'               : [
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
        		]
        });
        $table1.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
      
        });
    }

   

	

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/persetujuan_permintaan_biaya/';
        handleDataTable();

    };
 }(mb.app.persetujuan_permintaan_biaya));


// initialize  mb.app.home.table
$(function(){
    mb.app.persetujuan_permintaan_biaya.init();
});