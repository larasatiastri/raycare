mb.app.activityJournal = mb.app.activityJournal || {};

// mb.app.activityJournal namespace
(function(o){
	//simpan #table_user sebagai jquery object
	var $tableJournalTemplate = $('#table_jurnal_template');
	var $form = $('#form_jurnal_template');
	var baseAppUrl = '';

	
    var handleDataTable = function(){
         oTable = $tableJournalTemplate.dataTable({
            'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
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
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        }); 
    };
    

	var handleConfirmSave = function(){
		$('a#confirm_save', $form).click(function() {
			if (! $form.valid()) return;

			var msg = $(this).data('confirm');
		    bootbox.confirm(msg, function(result) {
		        if (result==true) {
		            $('#save', $form).click();
		        }
		    });
		});
	};

	
		var handleDeleteRow = function(){
		$('a[name="delete[]"]').click(function(){
				
			var $anchor = $(this),
			      id    = $anchor.data('id');

			bootbox.confirm('Are you sure to delete this Journals?', function(result) {
				if(result==true) {
					location.href = baseAppUrl + 'delete/' +id;
				} 
			});   

				
		});	
	
	
	};
   	
   	// mp.app.activityJournal properties
	o.init = function(){
		baseAppUrl = mb.baseUrl() + 'akunting/jurnal_template/';
		handleDataTable();
		handleConfirmSave();
		handleDeleteRow();

	};

}(mb.app.activityJournal));

$(function(){    
	mb.app.activityJournal.init();
});