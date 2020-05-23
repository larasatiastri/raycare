mb.app.permintaan_dialyzer_baru = mb.app.permintaan_dialyzer_baru || {};
(function(o){

    var 
        baseAppUrl             = '',
        $tablePermintaanDialyzer        = $('#table_permintaan_dialyzer_baru');
        $tablePermintaanDialyzerHistory = $('#table_permintaan_dialyzer_baru_history');

    var handleDataTable = function() 
    {
        $tablePermintaanDialyzer.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'filter'                : false,
            'info'                  : false,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing/1',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'desc']],
            'columns'               : [
                { 'name' : 'permintaan_dialyzer_baru.id id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'cabang.nama nama_cabang', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'permintaan_dialyzer_baru.no_permintaan no_permintaan', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'pasien.nama nama_pasien', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'a.nama nama_minta', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'user_level.nama nama_level','visible' : true, 'searchable': false, 'orderable': true },
                { 'name' : 'user_level.nama nama_level','visible' : true, 'searchable': false, 'orderable': true },             
                { 'name' : 'user_level.nama nama_level','visible' : true, 'searchable': false, 'orderable': true },             
            ]
        });

        $tablePermintaanDialyzer.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker
            

            $('a[name="delete_resep[]"]', this).click(function(){
                var $anchor = $(this),
                      id    = $anchor.data('id');
                      msg    = $anchor.data('confirm');

                handleDeleteResep(id,msg);
            }); 
        } );

    }

    var handleDataTableHistory = function() 
    {
		$tablePermintaanDialyzerHistory.dataTable({
	       	'processing'            : true,
            'serverSide'            : true,
           
			'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_history/2',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
				{ 'name' : 'permintaan_dialyzer_baru.id id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'cabang.nama nama_cabang', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'permintaan_dialyzer_baru.no_permintaan no_permintaan', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.nama nama_pasien', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'a.nama nama_minta', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'permintaan_dialyzer_baru.created_date created_date', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'permintaan_dialyzer_baru.modified_date modified_date', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'user_level.nama nama_level','visible' : true, 'searchable': false, 'orderable': true },
                { 'name' : 'c.nama nama_apoteker', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'b.nama nama_terima', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama_dialyzer', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'permintaan_dialyzer_baru.bn_sn_lot bn_sn_lot','visible' : true, 'searchable': false, 'orderable': true },             
                { 'name' : 'permintaan_dialyzer_baru.expired_date expired_date','visible' : true, 'searchable': false, 'orderable': true },             
				{ 'name' : 'permintaan_dialyzer_baru.status status','visible' : true, 'searchable': false, 'orderable': true },	     		
            ]
	    });

        $tablePermintaanDialyzerHistory.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			

            $('a[name="delete_resep[]"]', this).click(function(){
                var $anchor = $(this),
                      id    = $anchor.data('id');
                      msg    = $anchor.data('confirm');

                handleDeleteResep(id,msg);
            }); 
		} );

    }

    var handleDeleteResep = function(id,msg) {
        
        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'delete/' +id;
            } 
        });
    };


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/permintaan_dialyzer_baru/';
        handleDataTable();
        handleDataTableHistory();
    };
 }(mb.app.permintaan_dialyzer_baru));


// initialize  mb.app.home.table
$(function(){
    mb.app.permintaan_dialyzer_baru.init();
});