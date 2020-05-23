mb.app.proses_terima_setoran = mb.app.proses_terima_setoran || {};
mb.app.proses_terima_setoran.view = mb.app.proses_terima_setoran.view || {};

// mb.app.proses_terima_setoran.view namespace
(function(o){

    var 
        $form                   = $('#form_proses_terima_setoran'),
        $tableTitipsetoran      = $('#table_titip_setoran'),
        $lastPopoverItem         = null;


    var handleDataTable = function(){
        oTable = $tableTitipsetoran.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_titip_setoran/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'desc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        
        $tableTitipsetoran.on('draw.dt', function (){
           var $items = $('.item-unlist', this);

            $.each($items, function(idx, col){
                var
                    $col            = $(col),
                    dataItem        = $col.data('item');

                // console.log(dataIdentitas);
                $col.popover({
                    html : true,
                    container : 'body',
                    placement : 'bottom',
                    content: function(){
                        
                        var html = '<div class="table-scrollable">';
                            html += '<table class="table table-striped table-hover">';
                            html += '<tr class="heading bold">';
                            html += '<td class="text-center">Nomor</td>'
                            html += '<td class="text-center">Tanggal</td>'
                            html += '<td class="text-center">Diminta Oleh</td>'
                            html += '<td class="text-center">Jumlah</td>'
                            html += '<td class="text-center">Keperluan</td>'
                            html += '</tr>';

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left inline-button-table">' + item.nomor_permintaan + '</td>'
                            html += '<td class="text-left inline-button-table">' + item.tanggal + '</td>'
                            html += '<td class="text-left inline-button-table">' + item.nama_minta + '</td>'
                            html += '<td class="text-left inline-button-table">' + mb.formatRp(parseInt(item.nominal_setujui)) + '</td>'
                            html += '<td class="text-left">' + item.keperluan + '</td>'
                            html += '</tr>';

                        });
                        html += '</table>';
                        html += '</div>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverItem !== null) $lastPopoverItem.popover('hide');
                    $lastPopoverItem = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverItem = null;
                }).on('click', function(e){

                });
            });


                
        });       
    };


    
    o.init = function(){
        $form.validate();
        baseAppUrl = mb.baseUrl() + 'keuangan/proses_terima_setoran/';
        handleDataTable();
    };

}(mb.app.proses_terima_setoran.view));


// initialize  mb.app.proses_terima_setoran.view
$(function(){
    mb.app.proses_terima_setoran.view.init();

});