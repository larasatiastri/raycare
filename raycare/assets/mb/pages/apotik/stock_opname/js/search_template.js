mb.app.stockOpname = mb.app.stockOpname || {};
mb.app.stockOpname.searchTemplate = mb.app.stockOpname.searchTemplate || {};

// mb.app.stockOpname.searchTemplate namespace
(function(o){
	//simpan #table_user sebagai jquery object
	var $tableSeachItem = $('#table_template');
	var baseAppUrl = '';

	// setup list user datatable
	// handle checkbox activate/deactivate password
	// handle reset password action
	var handleDataTable = function(){
		//setup datatable
		$tableSeachItem.dataTable({
				"aoColumnDefs": [
	                { "aTargets": [ 0 ] }
	            ],
	            "aaSorting": [[1, 'asc']],
	             "aLengthMenu": [
	                [10, 15, 20, -1],
	                [10, 15, 20, "All"] // change per page values here
	            ],
	            // set the initial value
	            "iDisplayLength": 10,
			});//.fnSetFilteringDelay(500);

		$tableSeachItem.on('draw.dt', function (){
			// action for select searchTemplate
			$('a[name="select[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');

					handleSelectRow(id);
			});	

			$('a[name="view[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');

					handleViewDetail(id);
			});	
			
			
		});
	};

	var handleSelectRow = function(id){

		$('a[name="select[]"]').click(function(){
			var $anchor = $(this),
			    id    = $anchor.data('id');

			$('#item_temporary',opener.document).show();
			window.close();
				
		});		
	};

	var handleViewDetail = function(id){
		$('a[name="view[]"]').click(function(){
			var $anchor = $(this),
			    id    = $anchor.data('id');

			$('#data-detail').show();

				
		});		
	};
   	
   	// mp.app.searchTemplate properties
	o.init = function(){
		baseAppUrl = mb.baseUrl() + 'master/item/suppliers/';
		handleDataTable();
		// handleSelectRow();
		handleViewDetail();
		//another init
		//another init
	};
	// o.resetPassword = resetPassword;

}(mb.app.stockOpname.searchTemplate));

$(function(){    
	mb.app.stockOpname.searchTemplate.init();
});