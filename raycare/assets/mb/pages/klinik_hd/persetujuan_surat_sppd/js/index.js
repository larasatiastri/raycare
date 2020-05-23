mb.app.persetujuan_surat_sppd = mb.app.persetujuan_surat_sppd || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tablePasien = $('#table_pasien');
        $tablePasienHistory = $('#table_pasien_history');

    var handleDataTable = function() 
    {
    	$tablePasien.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'stateSave'				: true,
			'pagingType'			: 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/1',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
			'columns'               : [
				{ 'visible' : false, 'name' : 'surat_dokter_sppd.id id', 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'name' : 'pasien.nama nama', 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'name' : 'user.nama dokter', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_dokter_sppd.tanggal tanggal', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_dokter_sppd.diagnosa1 diagnosa1','searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_dokter_sppd.alasan alasan', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_dokter_sppd.status status', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_dokter_sppd.id id', 'searchable': false, 'orderable': false },
        		]
        });
        $tablePasien.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg    = $anchor.data('confirm');

					handleDeleteRow(id,msg);
			});

						
		});

		$tablePasienHistory.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'stateSave'				: true,
			'pagingType'			: 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/2',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
			'columns'               : [
				{ 'visible' : false, 'name' : 'surat_dokter_sppd.id id', 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'name' : 'pasien.nama nama', 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'name' : 'user.nama dokter', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_dokter_sppd.tanggal tanggal', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_dokter_sppd.diagnosa1 diagnosa1','searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_dokter_sppd.alasan alasan', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_dokter_sppd.status status', 'searchable': true, 'orderable': true },
        		]
        });
    }

    var handleDeleteRow = function(id,msg){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'delete/' +id;
			} 
		});
	
	};

	

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/persetujuan_surat_sppd/';
        handleDataTable();
    };
 }(mb.app.persetujuan_surat_sppd));


// initialize  mb.app.home.table
$(function(){
    mb.app.persetujuan_surat_sppd.init();
});