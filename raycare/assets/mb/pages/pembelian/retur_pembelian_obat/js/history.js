mb.app.retur_pembelian = mb.app.retur_pembelian || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableBeliObat = $('#table_retur_pembelian_obat');

    var handleDataTable = function() 
    {
    	$tableBeliObat.dataTable({
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
				{ 'name' : 'retur_pembelian.tanggal tanggal', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'supplier.nama nama_supplier','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'retur_pembelian.no_retur no_retur','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'retur_pembelian.no_surat_jalan no_surat_jalan','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'retur_pembelian.tipe tipe','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'retur_pembelian.nominal nominal','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'retur_pembelian.keterangan keterangan','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'user.nama nama_dibuat_oleh','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'retur_pembelian.status status','visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'retur_pembelian.tanggal tanggal','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tableBeliObat.on('draw.dt', function (){
			$('.btn', this).tooltip();	
            	
		});
    }   

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/retur_pembelian/';
        handleDataTable();
    };
 }(mb.app.retur_pembelian));


// initialize  mb.app.home.table
$(function(){
    mb.app.retur_pembelian.init();
});