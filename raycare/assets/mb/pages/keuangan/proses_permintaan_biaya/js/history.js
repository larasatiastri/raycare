mb.app.permintaan_biaya = mb.app.permintaan_biaya || {};
(function(o){

    var 
        baseAppUrl              = '',
        $table1 = $('#table_proses_permintaan_biaya_history');

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
			'order'                	: [[1, 'desc']],
            'filter'                : true,
            'paginate'              : true,
            'info'                  : false,
            'pagingType'            : 'full_numbers',
			'columns'               : [
				{ 'visible' : true, 'searchable': false, 'orderable': false },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $table1.on('draw.dt', function (){
			$('.btn', this).tooltip();	

           
		});
    }

   

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/proses_permintaan_biaya/';
        handleDataTable();
  

    };
 }(mb.app.permintaan_biaya));


// initialize  mb.app.home.table
$(function(){
    mb.app.permintaan_biaya.init();
});