mb.app.kategori_sub_kategori = mb.app.kategori_sub_kategori || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableKategori 			= $('#table_kategori');
        $tableSubKategori 		= $('#table_sub_kategori');

    var handleDataTableKategori = function() 
    {
    	$tableKategori.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' 	: baseAppUrl + 'listing',
				'type' 	: 'POST',
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
        
        $tableKategori.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg   = $anchor.data('confirm');

					handleDeleteRow(id,msg);
			});				
		} );
    }

    var handleDataTableSubKategori = function() 
    {
    	$tableSubKategori.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' 	: baseAppUrl + 'listing_sub',
				'type' 	: 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        
        $tableSubKategori.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg   = $anchor.data('confirm');

					handleDeleteRowSub(id,msg);
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

	var handleDeleteRowSub = function(id,msg){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'delete_sub/' +id;
			} 
		});
	
	};

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/kategori_sub_kategori/';
        handleDataTableKategori();
        handleDataTableSubKategori();
    };
 }(mb.app.kategori_sub_kategori));


// initialize  mb.app.home.table
$(function(){
    mb.app.kategori_sub_kategori.init();
});