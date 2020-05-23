mb.app.pasien_pindah = mb.app.pasien_pindah || {};
(function(o){

    var 
        baseAppUrl            = '',
        $tablePasienPindah = $('#table_pasien_pindah');

    var handleDataTable = function() 
    {
    	$tablePasienPindah.dataTable({
           	'processing'            : true,
            'serverSide'            : true,
			'stateSave'             : true,
			'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : true, 'name' : 'pasien.no_member no_member','searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'pasien.nama nama','searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_traveling.tanggal_surat tanggal_surat','searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_traveling.rs_tujuan rs_tujuan','searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'surat_traveling.alasan_pindah alasan_pindah','searchable': true, 'orderable': true },
                { 'visible' : true, 'name' : 'pasien.no_member no_member','searchable': false, 'orderable': false },
				{ 'visible' : true, 'name' : 'pasien.no_member no_member','searchable': false, 'orderable': false },
        		]
        });
    };

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/pasien_pindah/';
        handleDataTable();
    };
 }(mb.app.pasien_pindah));


// initialize  mb.app.home.table
$(function(){
    mb.app.pasien_pindah.init();
});