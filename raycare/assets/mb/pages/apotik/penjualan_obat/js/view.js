mb.app.penjualan_obat = mb.app.penjualan_obat || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableJualObat = $('#table_penjualan_obat');

    var handleDataTable = function() 
    {
    	$tableJualObat.dataTable({
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
				{ 'name' : 'penjualan_obat.tanggal tanggal', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'penjualan_obat.no_penjualan no_penjualan','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'penjualan_obat.nama_pasien nama_pasien','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'penjualan_obat.alamat_pasien alamat_pasien','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'penjualan_obat.grand_total grand_total','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'user.nama nama_dibuat_oleh','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'penjualan_obat.tanggal tanggal','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tableJualObat.on('draw.dt', function (){
			$('.btn', this).tooltip();	
            	
		});
    }   

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/penjualan_obat/';
        handleDataTable();
    };
 }(mb.app.penjualan_obat));


// initialize  mb.app.home.table
$(function(){
    mb.app.penjualan_obat.init();
});