mb.app.permintaan_po = mb.app.permintaan_po || {};
(function(o){

    var 
        baseAppUrl             = '',
        $tableDetailPermintaan = $('#tabel_detail_permintaan'),
        $tableUserPersetujuan  = $('#table_user_persetujuan'),
        $popoverItemContent    = $('#popover_item_content'), 
        $lastPopoverItem       = null;

   
    var initform = function(){
        handleDataTabelPersetujuan();

        $btnInfoSetuju = $('a.info', $tableDetailPermintaan);

        $btnInfoSetuju.click(function(){
            var id = $(this).data('id'),
                permintaan_id = $(this).data('permintaan_id'),
                user_level_id = $(this).data('user_level_id'),
                order = $(this).data('order');

            oTablePersetujuan.api().ajax.url(baseAppUrl + 'listing_pilih_item_user_view_history/' + permintaan_id +'/'+ user_level_id +'/'+ id +'/'+ order).load();

        });
        handleBtnInfoSetuju($btnInfoSetuju);

    };

    var handleDataTabelPersetujuan = function(){
        oTablePersetujuan = $tableUserPersetujuan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_item_user_view_history',
                'type' : 'POST',
            },        
            'filter'                : false,
            'info'                  : false,
            'paginate'              : false,  
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });       
        $('#table_pilih_item_user_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_user_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContent.hide();
    };

    var handleBtnInfoSetuju = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '1024px', maxWidth: '1024px'});

            if ($lastPopoverItem != null) $lastPopoverItem.popover('hide');

            $lastPopoverItem = $btn;

            $popoverItemContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/permintaan_po/';
 
        initform();

    };
 }(mb.app.permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.permintaan_po.init();
});