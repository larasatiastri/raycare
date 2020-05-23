mb.app.cabang = mb.app.cabang || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableKotakSampah = $('#table_kotak_sampah');

    var handleDataTable = function() 
    {
    	$tableKotakSampah.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tableKotakSampah.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

			 $('a[name="restore[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                    var msg = $(this).data('confirm');

                    handleRestoreRow(id, msg);
            }); 					
		});
    }

    var handleRestoreRow = function(id, msg){
        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'restore/'+id;
            } 
        });
    };

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/kotak_sampah/';
        handleDataTable();
    };
 }(mb.app.cabang));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.init();
});