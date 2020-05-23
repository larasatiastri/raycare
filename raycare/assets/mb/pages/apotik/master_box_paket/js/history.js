mb.app.master_box_paket = mb.app.master_box_paket || {};
(function(o){

    var 
        baseAppUrl          = '',
        $tableBoxPaket  = $('#table_master_box_paket'),
        $popoverItemContent = $('#popover_item_content'), 
		$lastPopoverItem    = null;

    var handleDataTable = function() 
    {
    	oTableBoxPaket = $tableBoxPaket.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                ]
        });



        $tableBoxPaket.on('draw.dt', function (){
            $('.btn', this).tooltip();
        

            var $colItems = $('a.pilih-item', this);

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
                            html += '<td class="text-center">Jumlah</td>'
                            html += '</tr>';

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.kode_item + '</td>'
                            html += '<td class="text-left">' + item.nama_item + '</td>'
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
        baseAppUrl = mb.baseUrl() + 'apotik/master_box_paket/';
        handleDataTable();
    };
 }(mb.app.master_box_paket));


// initialize  mb.app.home.table
$(function(){
    mb.app.master_box_paket.init();
});