mb.app.daftar_permintaan_po = mb.app.daftar_permintaan_po || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableDetailPembelian   = $('#table_detail_pembelian_view'),
        $tableDataPersetujuan   = $('#table_data_persetujuan'),
        $popoverItemContent = $('#popover_item_content'), 
        $lastPopoverContent      = null,
        $btnInfo        = $('.lihat-persetujuan')
        ;

    var initform = function()
    {
        $lastPopoverContent      = null;
        var $rowItems = $('.data-item');

        $.each($rowItems, function(idx, rowItem){
            var rowName = $(this).closest('tr').prop('id'),
                rowId  = $('tr#'+rowName);

            var $btn = $('a.lihat-persetujuan', rowId),
                id = $btn.data('id');
                
            $btn.popover({ 
                html : true,
                container : '.page-content',
                placement : 'bottom',
                content: '<input type="hidden" name="rowItemId"/>'

            }).on("show.bs.popover", function(){

                oTablePersetujuan.api().ajax.url(baseAppUrl + 'listing_data_persetujuan_history/' + id).load();

                var $popContainer = $(this).data('bs.popover').tip();

                $popContainer.css({minWidth: '1024px', maxWidth: '1024px'});

                if ($lastPopoverContent != null) $lastPopoverContent.popover('hide');

                $lastPopoverContent = $btn;

                $popoverItemContent.show();

            }).on('shown.bs.popover', function(){

                var 
                    $popContainer = $(this).data('bs.popover').tip(),
                    $popcontent   = $popContainer.find('.popover-content')
                    ;

                // record rowId di popcontent
                $('input[name="rowItemId"]', $popcontent).val(rowId);
                
                // pindahkan $popoverItemContent ke .popover-conter
                $popContainer.find('.popover-content').append($popoverItemContent);

            }).on('hide.bs.popover', function(){
                //pindahkan kembali $popoverItemContent ke .page-content
                $popoverItemContent.hide();
                $popoverItemContent.appendTo($('.page-content'));

                $lastPopoverItem = null;

            }).on('hidden.bs.popover', function(){
                // console.log('hidden.bs.popover')
            }).on('click', function(e){
                e.preventDefault();
            });

        });
    }

  
    var handleDataTablePersetujuan = function() 
    {
        oTablePersetujuan = $tableDataPersetujuan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_data_persetujuan_history/',
                'type' : 'POST',
            },          
            'info'                  : false,
            'paginate'              : false,
            'filter'                : false,
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'searchable': true, 'orderable': true },
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
        $tableDataPersetujuan.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker     
        });

        $popoverItemContent.hide();        
    }


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/persetujuan_po/';
        handleDataTablePersetujuan();
        initform();
    };
 }(mb.app.daftar_permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_permintaan_po.init();
});