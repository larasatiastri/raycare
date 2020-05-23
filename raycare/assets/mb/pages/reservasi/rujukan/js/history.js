mb.app.rujukan = mb.app.rujukan || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableRujukan 			= $('#table_history_rujukan'),
	    theadFilterTemplate     = $('#thead-filter-template').text();

    var handleDataTable = function() 
    {
    	tanggal = $('input#date').val();
    	// status  = $('#status').val();
    	// alert(tanggal);
    	oTable  = $tableRujukan.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_history_rujukan/0' + '/' + tanggal,
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
		$('#table_history_rujukan_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
		$('#table_history_rujukan_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline");
        $(theadFilterTemplate).appendTo($('thead', $tableRujukan));

        $tableRujukan.on('draw.dt', function (){
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

			$('#status').on( 'change', function () {
				// alert('klik');
    		var tanggal  =  $('input#date').val();
    			// alert($(this).val());

            	oTable.api().ajax.url(baseAppUrl + 'listing_history_rujukan/' + $(this).val() + '/' + tanggal).load();

        	});			
		
		});
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
        baseAppUrl = mb.baseUrl() + 'reservasi/rujukan/';
        handleDataTable();
    };
 }(mb.app.rujukan));


// initialize  mb.app.home.table
$(function(){
    mb.app.rujukan.init();
});