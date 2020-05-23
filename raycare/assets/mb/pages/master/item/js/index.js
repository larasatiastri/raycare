mb.app.item = mb.app.item || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableItem = $('#table_item');

    var handleDataTable = function() 
    {
    	var months    = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
					date      = new Date(),
					day       = date.getDate(),
					month     = date.getMonth(),
					yy        = date.getYear(),
					year      = (yy < 1000) ? yy + 1900 : yy,
					curr_hour = date.getHours(),
					curr_min  = date.getMinutes(),
					curr_sec  = date.getSeconds();
					tanggal   = year +':' + months[month] + ':' + day;
		$tableItem.dataTable({
	       	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/' + tanggal,
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
				{ 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
	    		]
	    });

        $tableItem.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      pasien_id    = $anchor.data('pasien_id');
					      msg    = $anchor.data('confirm');

					handleDeleteRow(id,msg, pasien_id);
			});

			$('a[name="restore[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg    = $anchor.data('confirm');

					handleRestoreRow(id,msg);
			});	

			$('select[name="item_satuan[]"]', this ).on( 'change', function () {
	            var iStat   = parseInt(this.value),
	            	rowId     = $(this).data('row_id');
	            //alert('satuan id = ' + iStat + ' item_id = ' + $(this).data('item_id'));
	            // alert(rowId);
				//alert(tanggal);
	            $.ajax({
		            type     : 'POST',
		            url      : baseAppUrl + 'get_harga_satuan',
		            data     : {satuan_id: iStat, 
		            			item_id : $(this).data('item_id'), tanggal : tanggal },
		            dataType : 'json',
		            success  : function( results ) {
		                $.each(results, function(key, value) {
		                	var harga = parseInt(value.harga);

		                	
		                    $('label#'+rowId).text(mb.formatRp(harga));
		                });
		            }
		        });

	            

	            // $tableSalesPromot.fnSettings().sAjaxSource = baseAppUrl + 'listing/' +iStat;
	            // $tableSalesPromot.fnClearTable();
	        });			
		} );
    }

    var handleDeleteRow = function(id,msg, pasien_id){

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
        baseAppUrl = mb.baseUrl() + 'master/item/';
        handleDataTable();
    };
 }(mb.app.item));


// initialize  mb.app.home.table
$(function(){
    mb.app.item.init();
});