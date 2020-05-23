mb.app.daftar_pasien = mb.app.daftar_pasien || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tablePasien = $('#table_pasien');

    var handleDataTable = function() 
    {
    	$tablePasien.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'stateSave'				: true,
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
				{ 'name' : 'pasien.id id','visible' : false, 'searchable': false, 'orderable': true },
				{ 'name' : 'pasien.nama nama','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.no_member no_member','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.tanggal_lahir tanggal_lahir','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien_alamat.alamat alamat','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'cabang.nama nama_cabang','visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.id id','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tablePasien.on('draw.dt', function (){
			$('.btn', this).tooltip();
					
		} );
    }

   

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/daftar_pasien/';
        handleDataTable();
    };
 }(mb.app.daftar_pasien));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_pasien.init();
});