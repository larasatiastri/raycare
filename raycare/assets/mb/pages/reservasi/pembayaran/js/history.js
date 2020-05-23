mb.app.pembayaran = mb.app.pembayaran || {};
mb.app.pembayaran.add = mb.app.pembayaran.add || {};

// mb.app.pembayaran.add namespace
(function(o){

    var 
        baseAppUrl              = '',
        $lastPopoverCetak = null,
        $tableHistoryPembayaran = $('#table_history_pembayaran')
    ;


    var initForm = function(){
       
    };


    var handleDataTable = function()
    {
        $tableHistoryPembayaran.dataTable(
        {
            'processing'            : true,
            'serverSide'            : true,
            // 'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_history',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'desc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },               
            ]
        });

        $tableHistoryPembayaran.on('draw.dt', function (){
            $('.btn').tooltip();

            var $colCetak = $('.no_cetak', this);

            $.each($colCetak, function(idx, col){
                var
                    $col = $(col),
                    cetakData     = $col.data('cetak');

                // console.log(cetakData);
                $col.popover({
                    html : true,
                    container : 'body',
                    placement : 'bottom',
                    content: function(){
                        
                        var html = '<table class="table table-striped table-hover">';
                            html += '<tr class="heading bold">';
                            html += '<td class="text-center">No Cetak</td>'
                            html += '<td class="text-center">Tanggal Cetak</td>'
                            html += '</tr>';


                        $.each(cetakData, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-center">' + item.no_cetak + '</td>'
                            html += '<td class="text-center">' + item.created_date + '</td>'
                            html += '</tr>';

                        });
                        html += '</table>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '280px', maxWidth: '720px'});
                    if ($lastPopoverCetak !== null) $lastPopoverCetak.popover('hide');
                    $lastPopoverCetak = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverCetak = null;
                }).on('click', function(e){

                });
            });
            
        } );

    };

    o.init = function()
    {
        baseAppUrl = mb.baseUrl() + 'reservasi/pembayaran/';
        initForm(); 
        handleDataTable();
       
    };

}(mb.app.pembayaran.add));

// initialize  mb.app.pembayaran.add
$(function(){
    mb.app.pembayaran.add.init();
});