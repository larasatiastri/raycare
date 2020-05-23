mb.app.pasien = mb.app.pasien || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tablePasien = $('#table_pasien');

    var handleDataTable = function() 
    {
    	var cabang_id = $('select#cabang_id').val();

    	oTable = $tablePasien.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'stateSave'				: true,
			'pagingType'			: 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/' + cabang_id +'/0',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
			'columns'               : [
				{ 'name' : 'pasien.id id','visible' : false, 'searchable': false, 'orderable': true },
				{ 'name' : 'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.tanggal_lahir tanggal_lahir','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien_alamat.alamat alamat','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'cabang.nama nama_cabang','visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tablePasien.on('draw.dt', function (){
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
		} );
    }

    var handleDeleteRow = function(id,msg, pasien_id){

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

	var handleChangeCabang = function(){

		$(".date").datepicker( {
            format: "M",
            viewMode: "months", 
            minViewMode: "months",
            autoclose : true,
        });

		$('a#refresh').on('click', function(){
			var cabang_id = $('select#cabang_id').val();
			    month = $('input#month_year').val();

			    if(month == ""){
			    	month = 0;
			    }

			oTable.api().ajax.url(baseAppUrl + 'listing/' + cabang_id+'/'+month).load();
		});

		$('a#reset').on('click', function(){
			$('select#cabang_id').val(0);
			$('input#month_year').val('');



			oTable.api().ajax.url(baseAppUrl + 'listing/0/0').load();
		});
	}

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/pasien/';
        handleDataTable();
        handleChangeCabang();
    };
 }(mb.app.pasien));


// initialize  mb.app.home.table
$(function(){
    mb.app.pasien.init();
});