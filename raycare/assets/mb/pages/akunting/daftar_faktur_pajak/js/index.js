mb.app.daftar_faktur_pajak = mb.app.daftar_faktur_pajak || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableDaftarFakturPajak = $('#table_daftar_faktur_pajak'),
        $tableDaftarFakturPajakHistory = $('#table_daftar_history_faktur_pajak');

    var handleDataTableDaftarFaktur = function() 
    {
        $tableDaftarFakturPajak.dataTable({
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
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $tableDaftarFakturPajak.on('draw.dt', function (){
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
    	$tableDaftarFakturPajakHistory.dataTable({
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
        		]
        });
        $tableDaftarFakturPajakHistory.on('draw.dt', function (){
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
        baseAppUrl = mb.baseUrl() + 'akunting/daftar_faktur_pajak/';
        handleDataTableDaftarFaktur();
        handleDataTableDaftarFakturHistory();
    };
 }(mb.app.daftar_faktur_pajak));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_faktur_pajak.init();
});