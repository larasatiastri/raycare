mb.app.stockOpname = mb.app.stockOpname || {};
mb.app.stockOpname.viewHistory = mb.app.stockOpname.viewHistory || {};

// mb.app.viewHistory namespace
(function(o){
	//simpan #table_user sebagai jquery object
	var $tableviewHistoryHistory = $('#table_stock_opname_history');
	var baseAppUrl = '';

	var handleDataTableHistory = function(){
		$tableviewHistoryHistory.dataTable({
              'processing'              : true,
              'serverSide'              : true,
              'language'              : mb.DTLanguage(),
              'ajax'                  : {
                'url' : baseAppUrl + 'listingHistoryItem/' + $('input#id').val() + '/' + $('input#warehouse_id').val(),
                'type' : 'POST',
              },
             
              'pageLength'           : 10,
              'lengthMenu'              : [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
              'order'                : [[0, 'asc']],
              'columns'                : [
                { 'name' : 'item.kode', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item_satuan.nama', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
              ]
        });//.fnSetFilteringDelay(500);
        $('#table_stock_opname_history_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_stock_opname_history_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline");
		
	};

   	// mp.app.stockOpname.viewHistory properties
	o.init = function(){
		baseAppUrl = mb.baseUrl() + 'apotik/stock_opname/';

		handleDataTableHistory();
		//another init
		//another init
	};

}(mb.app.stockOpname.viewHistory));

$(function(){    
	mb.app.stockOpname.viewHistory.init();
});