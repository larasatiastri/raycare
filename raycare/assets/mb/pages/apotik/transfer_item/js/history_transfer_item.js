mb.app.Transfer_item = mb.app.Transfer_item || {};
(function(o){

    var 
        baseAppUrl          = '',
        $tableUserlevel 	= $('#table_user_level'),
        $tableHistoryTransferItem 	= $('#table_history_transfer_item'),
		$lastPopoverItem    = null;

    var handleDataTable = function() 
    {
    	oTable_history = $tableHistoryTransferItem.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_history_transfer_item',
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
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });

        $( '#gudang').on( 'change', function () {
            // alert($(this).val());
            // var item_kategori_id = $('select#select_so_history').val();
            // alert(item_kategori_id);
            oTable_history.api().ajax.url(baseAppUrl + 'listing_history_transfer_item/' + $(this).val()).load();
          // alert($(this).val());
        });

        $tableHistoryTransferItem.on('draw.dt', function (){
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

			

			/////////////////tooltip///////////////////////////

            var $colItems = $('.show-notes', this);

            $.each($colItems, function(idx, colItem){
                var
                    $colItem = $(colItem),
                    itemsData = $colItem.data('content');
            
            $colItem.popover({
                html : true,
                container : 'body',
                placement : 'bottom',
                content: function(){

                    var html = '<table class="table table-striped table-hover">';
                        html += '<tr>';
                        html += '<td>'+itemsData+'</td>';
                        html += '</tr>';
                        html += '</table>';
                        return html;
                }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '720px'});

                    if ($lastPopoverItem !== null) $lastPopoverItem.popover('hide');
                    $lastPopoverItem = $colItem;
                }).on('hide.bs.popover', function(){
                    $lastPopoverItem = null;
                }).on('click', function(e){
                });
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
        baseAppUrl = mb.baseUrl() + 'apotik/transfer_item/';
        handleDataTable();
        // handleDataTableKirim();	
        // handleDataTableTerima();	
        // handlePilihItem();
    };
 }(mb.app.Transfer_item));


// initialize  mb.app.home.table
$(function(){
    mb.app.Transfer_item.init();
});