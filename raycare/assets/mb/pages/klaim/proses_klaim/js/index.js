mb.app.proses_klaim = mb.app.proses_klaim || {};
(function(o){

    var 
		baseAppUrl               = '',
		$tabelProsesKlaim        = $('#table_proses_klaim');
		$tabelProsesKlaimHistory = $('#table_proses_klaim_history');

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
							status = '<div class="text-center"><span class="label label-warning">Menunggu Verifikasi</span></div>';
						}
						if(stat == 2)
						{
							status = '<div class="text-center"><span class="label label-info">Proses Verifikasi</span></div>';
						}
						if(stat == 3)
						{
							status = '<div class="text-center"><span class="label label-success">Selesai Verifikasi</span></div>';
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
				'url' : baseAppUrl + 'listing/2',
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
							status = '<div class="text-center"><span class="label label-warning">Menunggu Verifikasi</span></div>';
						}
						if(stat == 2)
						{
							status = '<div class="text-center"><span class="label label-info">Proses Verifikasi</span></div>';
						}
						if(stat == 3)
						{
							status = '<div class="text-center"><span class="label label-success">Selesai Verifikasi</span></div>';
						}
						if(stat == 4)
						{
							status = '<div class="text-center"><span class="label label-success">Selesai Proses</span></div>';
						}
						
						
						return status;
					}

				}]
        });
        $tabelProsesKlaimHistory.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
				
		} );
    }

    var handleDeleteRow = function(id,msg){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'delete/' +id;
			} 
		});
	
	};

    var initform = function()
    {
    	$('a.reset').on('click', function(){
    		alert('a');
    		$tabelProsesKlaim.api().ajax.url(baseAppUrl + 'listing/').load();
    	})
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klaim/proses_klaim/';
        handleDataTable();
        handleDataTableHistory();
        initform();
        // handleDatePickers();
    };
 }(mb.app.proses_klaim));


// initialize  mb.app.home.table
$(function(){
    mb.app.proses_klaim.init();
});