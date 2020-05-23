mb.app.pembayaran_masuk = mb.app.pembayaran_masuk || {};
(function(o){

    var 
        baseAppUrl              = '',
        $table1 = $('#table_pembayaran_masuk');

    var handleDataTable = function() 
    {


    	oTable = $table1.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
            'stateSave'              : true,
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
				{ 'name' : 'pembayaran_masuk.created_date tanggal', 'visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'user.inisial inisial','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.tipe_transaksi tipe_transaksi','visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'pembayaran_status.transaksi_nomor transaksi_nomor','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.nominal nominal','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.status status','visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'pembayaran_status.waktu_akhir waktu_akhir','visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'pembayaran_status.waktu_akhir waktu_akhir','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        

    }

   

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/pembayaran_masuk/';
        handleDataTable();
  

    };
 }(mb.app.pembayaran_masuk));


// initialize  mb.app.home.table
$(function(){
    mb.app.pembayaran_masuk.init();
});