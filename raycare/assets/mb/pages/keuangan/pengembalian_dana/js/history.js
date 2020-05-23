mb.app.pengembalian_dana = mb.app.pengembalian_dana || {};
(function(o){

    var 
        baseAppUrl              = '',
        $table1 = $('#table_pengembalian_dana');

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
				{ 'name' : 'pengajuan_pemegang_saham.tanggal tanggal', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pengajuan_pemegang_saham.nomor_pengajuan nomor_pengajuan','visible' : true, 'searchable': true, 'orderable': false },               
                { 'name' : 'user.nama nama_dibuat_oleh','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pengajuan_pemegang_saham.nominal nominal','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pengajuan_pemegang_saham.keterangan keterangan','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pengajuan_pemegang_saham.status status','visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'pengajuan_pemegang_saham.id id','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $table1.on('draw.dt', function (){
			
		});
    }

   

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pengembalian_dana/';
        handleDataTable();
  

    };
 }(mb.app.pengembalian_dana));


// initialize  mb.app.home.table
$(function(){
    mb.app.pengembalian_dana.init();
});