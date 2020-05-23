mb.app.stockOpname = mb.app.stockOpname || {};

// mb.app.stockOpname namespace
(function(o){
	//simpan #table_user sebagai jquery object
	var $tablestockOpname = $('#table_stock_opname');
	var $tablestockOpnameHistory = $('#table_stock_opname_history');
    var theadFilterTemplate     = $('#thead-filter-template').text();
	var baseAppUrl = '';

	var initForm = function(){
		var warehouse_id = $('#select_warehouse').val();

		$('input#id_warehouse').val(warehouse_id)
        $('a.add-so').attr('href', baseAppUrl + 'add/' + warehouse_id);

	}


	var handleSelectWarehouse = function(){
        $('#select_warehouse').on('change', function(e){

            $('input#id_warehouse').val($('#select_warehouse').val());

            var warehouse_id =  $('input#id_warehouse').val();
            $('a.add-so').attr('href', baseAppUrl + 'add/' + warehouse_id);
        })
    }
	
	var handleDataTable = function(){
		//setup datatable
		 
	oTable1=$tablestockOpname.dataTable({
			'processing'              : true,
			'serverSide'              : true,
			 'language'              : mb.DTLanguage(),
			 'ajax'                  : {
                'url' :   baseAppUrl + 'listing/' + $('input#id_warehouse').val(),
                'type' : 'POST',
            }, 
			 
			'pageLength'           : 10,
			'lengthMenu'              : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                : [[1, 'asc']],
			'columns'                : [
				{ 'name' : 'stok_opname.no_stok_opname', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'user.nama',  'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'gudang_orang.nama',  'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'stok_opname.tanggal_mulai',  'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'stok_opname.tanggal_selesai',  'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false }
        		]
			});//.fnSetFilteringDelay(500);
		$('#table_stock_opname_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
		$('#table_stock_opname_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline");

		$('#select_warehouse').on( 'change', function () {
            var iStat   = parseInt(this.value);
            oTable1.api().ajax.url(baseAppUrl + 'listing/' + iStat).load();
           
        });

		$tablestockOpname.on('draw.dt', function (){

 				$('.btn', this).tooltip();
 		 
			// action for delete stockOpname
			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
                    var msg = $(this).data('confirm');

					handleDeleteRow(id, msg);
			});	
			// action for restore stockOpname
			$('a[name="restore[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					var msg = $(this).data('confirm');

					handleRestoreRow(id, msg);
			});	
			
		} );
	};

	var handleDeleteRow = function(id, msg){
		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'delete/'+id + '/' + $("#id_warehouse").val();
			} 
		});   	
	};

	var handleRestoreRow = function(id, msg){
		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'restore/'+id + '/' + $("#id_warehouse").val();
			} 
		});
	};

	var handleDataTableHistory = function(){
		     
	oTable2=$tablestockOpnameHistory.dataTable({
			'processing'              : true,
			'serverSide'              : true,
			 'language'              : mb.DTLanguage(),
			 'ajax'                  : {
                'url' :  baseAppUrl + 'listingHistory/' + $('input#id_warehouse').val(),
                'type' : 'POST',
            }, 
			 
			'pageLength'           : 10,
			'lengthMenu'              : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                : [[1, 'desc']],
			'columns'                : [
				{'name' : 'stok_opname.no_stok_opname', 'visible' : true, 'searchable': true, 'orderable': true },
				{'name' : 'user.nama', 'visible' : true, 'searchable': true, 'orderable': true },
				{'name' : 'gudang_orang.nama', 'visible' : true, 'searchable': true, 'orderable': true },
				 
				{'name' : 'stok_opname.tanggal_mulai', 'visible' : true, 'searchable': true, 'orderable': true },
				{'name' : 'stok_opname.tanggal_akhir', 'visible' : true, 'searchable': true, 'orderable': true },
				{'name' : 'stok_opname.keterangan', 'visible' : true, 'searchable': true, 'orderable': true },
				{'visible' : true, 'searchable': false, 'orderable': false }
        	],
        	 
		});//.fnSetFilteringDelay(500);
 		$tablestockOpnameHistory.on('draw.dt', function (){
 				$('.btn', this).tooltip();
 		});
		$('#table_stock_opname_history_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
		$('#table_stock_opname_history_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline");
       
		
		$('#select_warehouse').on( 'change', function () {
            var iStat   = parseInt(this.value);
			oTable2.api().ajax.url(baseAppUrl + 'listingHistory/' + iStat).load();
            
			// $tablestockOpnameHistory.fnSettings().sAjaxSource = baseAppUrl + 'listingHistory/' + iStat;
   //          $tablestockOpnameHistory.fnClearTable();
            $('#status').val(2);

        });

		$('#status').on( 'change', function () {
            var iStat   = parseInt(this.value);
            oTable2.api().ajax.url(baseAppUrl + 'listingHistory/' + $('input#id_warehouse').val() + '/' + iStat).load();
            // $tablestockOpnameHistory.fnSettings().sAjaxSource = baseAppUrl + 'listingHistory/' + $('input#id_warehouse').val() + '/' + iStat;
            // $tablestockOpnameHistory.fnClearTable();
        });
	};
   	
   	// mp.app.stockOpname properties
	o.init = function(){
		 
		baseAppUrl = mb.baseUrl() + 'apotik/stock_opname/';
		initForm();
		handleDataTable();
		handleDataTableHistory();
		handleSelectWarehouse();
		//another init
	};
	// o.resetPassword = resetPassword;

}(mb.app.stockOpname));

$(function(){    
	mb.app.stockOpname.init();
});