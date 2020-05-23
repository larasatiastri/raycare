mb.app.verifikasi_pendaftaran = mb.app.verifikasi_pendaftaran || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableHistoryVerifikasiPendaftaran = $('#table_history_verifikasi_pendaftaran');

    var handleDataTable = function() 
    {
    	$tableHistoryVerifikasiPendaftaran.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'stateSave'             : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_history',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'name' : 'pasien.nama nama_pasien', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pendaftaran_tindakan.created_date tanggal', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'poliklinik.nama nama_poliklinik', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien_penjamin.no_kartu no_kartu', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : '`user`.nama nama_user', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'user_verif.nama user_verif', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pendaftaran_tindakan.tanggal_verif tanggal_verif', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'user_verif.nama user_verif', 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tableHistoryVerifikasiPendaftaran.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      pasien_id    = $anchor.data('pasien_id');
					      msg    = $anchor.data('confirm');

					handleDeleteRow(id,msg, pasien_id);
			});
		
		} );
    }

    var handleDeleteRow = function(id,msg, pasien_id){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'delete/' +id +'/' + pasien_id;
			} 
		});
	
	};

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klaim/verifikasi_pendaftaran/';
        handleDataTable();
    };
 }(mb.app.verifikasi_pendaftaran));


// initialize  mb.app.home.table
$(function(){
    mb.app.verifikasi_pendaftaran.init();
});