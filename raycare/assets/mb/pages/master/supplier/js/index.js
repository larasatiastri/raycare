mb.app.supplier = mb.app.supplier || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableSupplier = $('#table_supplier');

    var handleDataTable = function() 
    {
    	oTableItem = $tableSupplier.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'stateSave'             : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
			'columns'               : [
				{ 'visible' : false, 'name' : 'supplier.id id', 'searchable': false, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier.kode kode','searchable': true, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier.nama nama','searchable': true, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier.rating rating','searchable': true, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier.orang_yang_bersangkutan orang_yang_bersangkutan','searchable': true, 'orderable': false },
				{ 'visible' : true, 'name' : 'supplier.id id','searchable': false, 'orderable': false },
        		]
        });
        $tableSupplier.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg    = $anchor.data('confirm');

					handleDeleteRow(id,msg);
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

	var handleBtnGrab = function(){
        $('a#btn_grab').click(function(){

            $.ajax({
                type     : 'POST',
                url      :  mb.baseUrl() + 'grab_data/get_data_item',
                dataType : 'json',
                success  : function( results ) {
                    oTableItem.api().ajax.url(baseAppUrl +  'listing/').load();
                }
            });
        });
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/supplier/';
        handleDataTable();
        handleBtnGrab();
    };
 }(mb.app.supplier));


// initialize  mb.app.home.table
$(function(){
    mb.app.supplier.init();
});