mb.app.pasien = mb.app.pasien || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tablePenjamin = $('#table_penjamin');
        $idPasien = $('input#id_pasien').val();

    var handleDataTable = function() 
    {
    	$tablePenjamin.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_penjamin/' + $idPasien,
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tablePenjamin.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    		= $anchor.data('id');
					      pasien_id    	= $anchor.data('pasien_id');
					      msg    		= $anchor.data('confirm');

					handleDeleteRow(id,pasien_id,msg);
			});				
		} );
    }

    var handleDeleteRow = function(id,pasien_id,msg){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				
				location.href = baseAppUrl + 'delete_syarat_penjamin/' +id +'/' + pasien_id;
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
        baseAppUrl = mb.baseUrl() + 'master/pasien/';
        handleDataTable();
    };
 }(mb.app.pasien));


// initialize  mb.app.home.table
$(function(){
    mb.app.pasien.init();
});