mb.app.pemakaian_obat_alkes = mb.app.pemakaian_obat_alkes || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tablePengeluranBrg = $('#table_pemakaian_obat_alkes'),
        $lastPopoverItem = null;

    var handleDataTable = function() 
    {
    	$tablePengeluranBrg.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tablePengeluranBrg.on('draw.dt', function (){
			var $colItems = $('.item-unlist', this);

            $.each($colItems, function(idx, colItem){
                var
                    $colItem = $(colItem),
                    dataItem = $colItem.data('item');
            
            $colItem.popover({
                html : true,
                container : 'body',
                placement : 'bottom',
                content: function(){

                    var html = '<table class="table table-striped table-hover">';
                            html += '<tr class="heading bold">';
                            html += '<td class="text-center">Kode</td>'
                            html += '<td class="text-center">Nama</td>'
                            html += '<td class="text-center">BN</td>'
                            html += '<td class="text-center">Jumlah</td>'
                            html += '</tr>';

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.kode + '</td>'
                            html += '<td class="text-left">' + item.nama + '</td>'
                            html += '<td class="text-left">' + item.bn_sn_lot + '</td>'
                            html += '<td class="text-left">' + item.jumlah + ' ' + item.nama_satuan +'</td>'
                            html += '</tr>';

                        });
                        html += '</table>';
                        return html;
                }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '720px'});

                    if ($lastPopoverItem !== null) $lastPopoverItem.popover('hide');
                    $lastPopoverItem = $colItem;
                }).on('hide.bs.popover', function(){
                    $lastPopoverItem = null;
                }).on('click', function(e){
                });
            });		
		});
    }


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/pemakaian_obat_alkes/';
        handleDataTable();
    };
 }(mb.app.pemakaian_obat_alkes));


// initialize  mb.app.home.table
$(function(){
    mb.app.pemakaian_obat_alkes.init();
});