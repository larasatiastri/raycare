mb.app.permintaan_biaya = mb.app.permintaan_biaya || {};
(function(o){

    var 
        baseAppUrl              = '',
        $table1 = $('#table_permintaan_biaya');

    var handleDataTable = function() 
    {
    	$table1.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
            'filter'                : true,
            'paginate'              : true,
            'info'                  : false,
            'pagingType'            : 'full_numbers',
			'columns'               : [
				{ 'name' : 'permintaan_biaya.tanggal tanggal', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'user.nama nama_dibuat_oleh','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'permintaan_biaya.tipe tipe','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'permintaan_biaya.nominal nominal','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'permintaan_biaya.keperluan keperluan','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'permintaan_biaya.tanggal tanggal','visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'permintaan_biaya.tanggal tanggal','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $table1.on('draw.dt', function (){
			$('.btn', this).tooltip();	

            	
		});
    }

   

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/daftar_kasbon_kecil/';
        handleDataTable();
  

    };
 }(mb.app.permintaan_biaya));


// initialize  mb.app.home.table
$(function(){
    mb.app.permintaan_biaya.init();
});