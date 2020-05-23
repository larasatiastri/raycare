mb.app.history_pendaftaran = mb.app.history_pendaftaran || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tablehistory = $('#table_history'),
        $tablehistoryAll = $('#table_history_all');

    var handleDataTable = function() 
    {
    	$tablehistory.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
				{ 'name' : 'pendaftaran_tindakan.id id','visible' : false, 'searchable': true, 'orderable': true },
				{ 'name' : 'pendaftaran_tindakan.created_date tanggal','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'penjamin.nama nama_penjamin','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien_penjamin.no_kartu no_kartu','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.ref_kode_rs_rujukan asal_rujukan','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.ref_nomor_rujukan no_rujukan','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'poliklinik.nama nama_poli','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'a.nama nama_dokter','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'b.nama nama_fo','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pendaftaran_tindakan.keterangan keterangan','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pendaftaran_tindakan.status status','visible' : true, 'searchable': false, 'orderable': false },
				{ 'name' : 'pendaftaran_tindakan.status status','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
		$("#table_history").on("draw.dt", function() {
	       $('.btn', this).tooltip();
		});

		$tablehistoryAll.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_history',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
				{ 'name' : 'pendaftaran_tindakan.id id','visible' : false, 'searchable': true, 'orderable': true },
				{ 'name' : 'pendaftaran_tindakan.created_date tanggal','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'penjamin.nama nama_penjamin','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien_penjamin.no_kartu no_kartu','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.ref_kode_rs_rujukan asal_rujukan','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.ref_nomor_rujukan no_rujukan','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'poliklinik.nama nama_poli','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'a.nama nama_dokter','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'b.nama nama_fo','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pendaftaran_tindakan.keterangan keterangan','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pendaftaran_tindakan.status status','visible' : true, 'searchable': false, 'orderable': false },
				{ 'name' : 'pendaftaran_tindakan.status status','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
		$("#table_history_all").on("draw.dt", function() {
	       $('.btn', this).tooltip();
		});


        
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'reservasi/pendaftaran_tindakan/';
        handleDataTable();
    };
 }(mb.app.history_pendaftaran));


// initialize  mb.app.home.table
$(function(){
    mb.app.history_pendaftaran.init();
});