mb.app.upload_dokumen_klaim = mb.app.upload_dokumen_klaim || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableTindakanHD = $('#table_os_tindakan_hd');

    var handleDataTable = function() 
    {
    	oTable = $tableTindakanHD.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'pagingType'			: 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
			'columns'               : [
				{ 'visible' : true, 'name' : 'outstanding_upload_dokumen_klaim.id id', 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'name' : 'tindakan_hd_history.tanggal tanggal', 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'name' : 'pasien.nama nama_pasien', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'outstanding_upload_dokumen_klaim.id id', 'searchable': false, 'orderable': false },
        		]
        });
        $tableTindakanHD.on('draw.dt', function (){
					
		});
    }

    var handleBtnRefresh = function(){
    	$('a#btn_refresh_table').click(function(){
    		oTable.api().ajax.url(baseAppUrl +  'listing').load();

    	});
    }

	

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/upload_dokumen_klaim/';
        handleDataTable();
        handleBtnRefresh();
    };
 }(mb.app.upload_dokumen_klaim));


// initialize  mb.app.home.table
$(function(){
    mb.app.upload_dokumen_klaim.init();
});