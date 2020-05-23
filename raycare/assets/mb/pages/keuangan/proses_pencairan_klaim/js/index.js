mb.app.proses_klaim = mb.app.proses_klaim || {};
(function(o){

    var 
		baseAppUrl               = '',
		$tabelProsesKlaim        = $('#table_proses_klaim'),
		$tabelProsesKlaimHistory        = $('#table_proses_klaim_history');

    var handleDataTable = function() 
    {
    	$tabelProsesKlaim.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/1',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'asc']],
			'columns'               : [
				{ 'name' : 'proses_klaim.periode_tindakan periode_tindakan','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.no_surat no_surat','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.jumlah_tindakan jumlah_tindakan','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.jumlah_tarif_riil jumlah_tarif_riil','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.jumlah_tarif_ina jumlah_tarif_ina','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.jumlah_tindakan_verif jumlah_tindakan_verif','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.jumlah_tarif_ina_verif jumlah_tarif_ina_verif','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.status status','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.id id','visible' : true, 'searchable': false, 'orderable': false },
        		],
        	'aoColumnDefs': [{
				'aTargets': [8],
				'mRender': function (data, type, full) {
						var stat 	 = data;
						var status = '';

						
						if(stat == 1)
						{
							status = '<div class="text-center"><span class="label label-danger">Menunggu Pencairan</span></div>';
						}
						
						
						return status;
					}

				}]
        });
        $tabelProsesKlaim.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			
		});
    }

    var handleDataTableHistory = function() 
    {
    	$tabelProsesKlaimHistory.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_history/2',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'asc']],
			'columns'               : [
				{ 'name' : 'proses_klaim.periode_tindakan periode_tindakan','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.no_surat no_surat','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.jumlah_tarif_ina jumlah_tarif_ina','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.jumlah_tindakan_verif jumlah_tindakan_verif','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.jumlah_tarif_ina_verif jumlah_tarif_ina_verif','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.jumlah_admin jumlah_admin','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.total_diterima total_diterima','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.tanggal tanggal','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.tanggal_terima tanggal_terima','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.status status','visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'proses_klaim.id id','visible' : true, 'searchable': false, 'orderable': false },
        		],
        	'aoColumnDefs': [{
				'aTargets': [9],
				'mRender': function (data, type, full) {
						var stat 	 = data;
						var status = '';

						
						if(stat == 2)
						{
							status = '<div class="text-center"><span class="label label-success">Selesai</span></div>';
						}
						
						
						return status;
					}

				}]
        });
        $tabelProsesKlaimHistory.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			
		});
    }

    

    var initform = function()
    {
    	handleDataTable();
    	handleDataTableHistory();
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/proses_pencairan_klaim/';
        initform();
    };
 }(mb.app.proses_klaim));


// initialize  mb.app.home.table
$(function(){
    mb.app.proses_klaim.init();
});