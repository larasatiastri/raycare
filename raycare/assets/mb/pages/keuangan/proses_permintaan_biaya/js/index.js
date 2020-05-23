mb.app.permintaan_biaya = mb.app.permintaan_biaya || {};
(function(o){

    var 
        baseAppUrl              = '',
        $table1 = $('#table_proses_permintaan_biaya');

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
                { 'name' : 'permintaan_biaya.nominal_setujui nominal_setujui','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'permintaan_biaya.keperluan keperluan','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'permintaan_biaya.status status','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'permintaan_biaya.tanggal tanggal','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $table1.on('draw.dt', function (){
			$('.btn', this).tooltip();	

            $('a.revisi', this).click(function() {
                var msg = $(this).data('confirm'),
                    id = $(this).data('id');

                bootbox.confirm(msg, function(results){
                    if(results == true){
                        location.href = baseAppUrl + 'revisi/'+id;
                    }
                });
            });	
		});
    }

   

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/proses_permintaan_biaya/';
        handleDataTable();
  

    };
 }(mb.app.permintaan_biaya));


// initialize  mb.app.home.table
$(function(){
    mb.app.permintaan_biaya.init();
});