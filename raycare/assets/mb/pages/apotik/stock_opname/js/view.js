mb.app.stockOpname = mb.app.stockOpname || {};
mb.app.stockOpname.view = mb.app.stockOpname.view || {};

// mb.app.view namespace
(function(o){
	//simpan #table_user sebagai jquery object
	var $tableview = $('#table_stock_opname');
	var baseAppUrl = '';

	var handleDataTable = function(){
		    
		$tableview.dataTable({
			'processing'              : true,
		     'serverSide'              : true,
		     'language'              : mb.DTLanguage(),
		     'ajax'                  : {
                'url' : baseAppUrl + 'listingView/' + $('input#id').val(),
                'type' : 'POST',
            },  
		       
		      'pageLength'           : 10,
		      'lengthMenu'              : [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
		      'order'                : [[0, 'asc']],
		      'columns'                : [
		        { 'name' : 'item.kode', 'visible' : true, 'searchable': true, 'orderable': true },
		        { 'name' : 'item.nama', 'visible' : true, 'searchable': true, 'orderable': true },
		        { 'name' : 'item_satuan.nama', 'visible' : true, 'searchable': true, 'orderable': true },
		      ]
		    });//.fnSetFilteringDelay(500);
		    $('#table_stock_opname_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
		    $('#table_stock_opname_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline");
	};
   	
   	// mp.app.view properties
	o.init = function(){
		baseAppUrl = mb.baseUrl() + 'apotik/stock_opname/';
		handleDataTable();
		//another init
		//another init
	};
	// o.resetPassword = resetPassword;

}(mb.app.stockOpname.view));

$(function(){    
	mb.app.stockOpname.view.init();
});