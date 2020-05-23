mb.app.cabang = mb.app.cabang || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tablePaket = $('#table_batch');

    var handleDataTable = function() 
    {
    	paket_id = $('input#paket_id').val()
    	oTable = $tablePaket.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_batch/'+ paket_id,
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[2, 'asc']],
			'columns'               : [
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tablePaket.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

			$('a[name="delete_batch[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg    = $anchor.data('confirm');

					handleDeleteRow(id,msg);
			});

			$('a[data-action="move_order"]', this).click(function(){
				var paket_id = $(this).data('paket_id');
				var id = $(this).data('id');
				var command = $(this).data('command');
				var order = $(this).data('order');
				changeOrder(paket_id,id,order,command);
				//cegah hiperlink default action 
				event.preventDefault();
			});

		});
    }

    var changeOrder = function(paket_id,id,order,command){
		$.ajax({
		  url: baseAppUrl + "change_order",
		  type : 'POST',
		  data : {paket_id:paket_id,id:id,order:order,command:command},
		  // context: document.body
		}).done(function( data ) {
			// console.log(data);
            oTable.api().ajax.url(baseAppUrl + 'listing_batch/' + paket_id).load();


		});
	};


    var handleDeleteRow = function(id,msg){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'delete_batch/' +id;
			} 
		});
	
	};

	

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/paket/';
        handleDataTable();
    };
 }(mb.app.cabang));


// initialize  mb.app.home.table
$(function(){
    mb.app.cabang.init();
});