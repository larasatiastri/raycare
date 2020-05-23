mb.app.userlevel = mb.app.userlevel || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableUserlevel = $('#table_user_level');

    var handleDataTable = function() 
    {
    	$tableUserlevel.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'stateSave'				: true,
			'language'              : mb.DTLanguage(),
			'pagingType'		    : 'full_numbers',
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/index',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tableUserlevel.on('draw.dt', function (){
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
        baseAppUrl = mb.baseUrl() + 'master/user_level/';
        handleDataTable();
    };
 }(mb.app.userlevel));


// initialize  mb.app.home.table
$(function(){
    mb.app.userlevel.init();
});