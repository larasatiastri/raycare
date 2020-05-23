mb.app.surat_pengantar = mb.app.surat_pengantar || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tablesurat_pengantar = $('#tabel_surat_pengantar');

    var handleDataTable = function() 
    {
    	$tablesurat_pengantar.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'pagingType'		    : 'full_numbers',
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'name':'surat_pengantar.no_surat no_surat','visible' : true, 'searchable': false, 'orderable': false },
				{ 'name':'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name':'user.nama nama_dokter_buat','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name':'surat_pengantar.nama_rs_poliklinik nama_rs_poliklinik','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name':'surat_pengantar.nama_dokter nama_dokter','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name':'surat_pengantar.created_date tanggal','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name':'surat_pengantar.id id','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tablesurat_pengantar.on('draw.dt', function (){
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
		
		} );
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
        baseAppUrl = mb.baseUrl() + 'klinik_hd/surat_pengantar/';
        handleDataTable();
    };
 }(mb.app.surat_pengantar));


// initialize  mb.app.home.table
$(function(){
    mb.app.surat_pengantar.init();
});