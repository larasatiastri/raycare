mb.app.daftar_dokumen_reimburse = mb.app.daftar_dokumen_reimburse || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableDaftarDokReimburse = $('#table_daftar_dokumen_reimburse'),
        $tableDaftarDokReimburseHistory = $('#table_daftar_dokumen_reimburse_history');

    var handleDataTableDaftarFaktur = function() 
    {
        $tableDaftarDokReimburse.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing',
                'type' : 'POST',
            },          
            'pageLength'            : 25,
            'lengthMenu'            : [[25, 50, 100], [25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $tableDaftarDokReimburse.on('draw.dt', function (){
            handleFancybox();

            $('a.receive', this).click(function(){
                var id = $(this).data('id'),
                    msg = $(this).data('confirm');

                handleSelectFaktur(id, msg);
            }); 
        });
    }

    var handleDataTableDaftarFakturHistory = function() 
    {
    	$tableDaftarDokReimburseHistory.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/1',
				'type' : 'POST',
			},			
			'pageLength'			: 25,
			'lengthMenu'            : [[25, 50, 100], [25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
        		]
        });
        $tableDaftarDokReimburseHistory.on('draw.dt', function (){
            handleFancybox();
		});
    }	

    function handleFancybox() {
        if (!jQuery.fancybox) {
            return;
        }

        if ($(".fancybox-button").size() > 0) {
            $(".fancybox-button").fancybox({
                groupAttr: 'data-rel',
                prevEffect: 'none',
                nextEffect: 'none',
                closeBtn: true,
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            });
        }
    };

    var handleSelectFaktur = function(id, msg){
        bootbox.confirm(msg, function(result){
            if(result==true) {
                location.href = baseAppUrl + 'update/' +id;
            } 
        });
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'akunting/daftar_dokumen_reimburse/';
        handleDataTableDaftarFaktur();
        handleDataTableDaftarFakturHistory();
    };
 }(mb.app.daftar_dokumen_reimburse));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_dokumen_reimburse.init();
});