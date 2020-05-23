mb.app.pembelian_history = mb.app.pembelian_history || {};
(function(o){

    var 
        baseAppUrl            = '',
        $tablePembelianBaru   = $('#table_pembelian_baru');
        $tablePembelianHistory = $('#table_pembelian_history');


    var initform = function()
    {    
         $('input[name="tipe_supplier"]').on('click', function(){
            iStatTipe   = this.value;

            $tablePembelianHistory.api().ajax.url(baseAppUrl + 'listing_history/' + iStatTipe).load();
        });
    }
    
    var handleDataTableHistory = function() 
    {
    	$tablePembelianHistory.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_history/1' ,
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : false, 'searchable': false, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });

        
        $tablePembelianHistory.on('draw.dt', function (){
			$('.btn', this).tooltip();
        });
    }

    
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/pembelian_alkes/';
        handleDataTableHistory();
       
        initform();
    };
 }(mb.app.pembelian_history));


// initialize  mb.app.home.table
$(function(){
    mb.app.pembelian_history.init();
});