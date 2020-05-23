mb.app.pasien_meninggal = mb.app.pasien_meninggal || {};
(function(o){

    var 
        baseAppUrl            = '',
        $tablePasienMeninggal = $('#table_pasien_meninggal');

    var handleDataTable = function() 
    {
    	$tablePasienMeninggal.dataTable({
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
				{ 'visible' : true, 'name' : 'pasien_meninggal.tanggal_meninggal tanggal_meninggal','searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'pasien_meninggal.lokasi_meninggal lokasi_meninggal','searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'pasien_meninggal.keterangan keterangan','searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'pasien.no_member no_member','searchable': false, 'orderable': false },
        		]
        });
    };

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/pasien_meninggal/';
        handleDataTable();
    };
 }(mb.app.pasien_meninggal));


// initialize  mb.app.home.table
$(function(){
    mb.app.pasien_meninggal.init();
});