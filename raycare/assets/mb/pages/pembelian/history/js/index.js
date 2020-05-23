mb.app.daftar_permintaan_po = mb.app.daftar_permintaan_po || {};
(function(o){

    var 
        baseAppUrl                      = '',
        $tablePembelian                 = $('#table_pembelian');
        $tablePermintaanPembelianProses = $('#table_permintaan_proses');
        $popoverItemContent             = $('#popover_item_content'), 
        $lastPopoverItem                = null,
        $tablePilihItem                 = $('#table_pilih_item'),
        $tablePilihItemTidakTerdaftar   = $('#table_pilih_item_tidak_terdaftar'),
        theadFilterTemplate             = $('#thead-filter-template').text();
        theadFilterTemplate2            = $('#thead-filter-template2').text();

    var handleDataTableProses = function() 
    {
        $tablePermintaanPembelianProses.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_permintaan/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });

        // $(theadFilterTemplate).appendTo($('thead', $tablePermintaanPembelianProses));
        // $('#pembelian_status').on('change', function(){
        //     var iStat   = this.value;

        //     $tablePermintaanPembelianProses.api().ajax.url(baseAppUrl + 'listing_permintaan/' + iStat).load();
        //     // $tablePermintaanPembelianProses.fnClearTable();
        // });
        $tablePermintaanPembelianProses.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker

            $('a[name="info[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');

                    oTablePilihItem.api().ajax.url(baseAppUrl + 'listing_pilih_item/' + id).load();
                    oTablePilihItemTidakTerdaftar.api().ajax.url(baseAppUrl + 'listing_pilih_item_tidak_terdaftar/' + id).load();
                    
            });

            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
            });

            var $btnSearchItem  = $('.pilih-item');
            handleBtnSearchItem($btnSearchItem);
        });
    }

    var handleDataTablePembelian = function() 
    {
        $tablePembelian.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pembelian/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
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
        // $(theadFilterTemplate2).appendTo($('thead', $tablePembelian));
        // $('#pembelian_baru_status').on('change', function(){
        //     var iStat   = this.value;

        //     // alert($('input[name="tipe_supplier"]:checked').val());

        //     $tablePembelian.api().ajax.url(baseAppUrl + 'listing_pembelian/' + iStat).load();
        //     // $tablePermintaanPembelianProses.fnClearTable();
        // });
        $tablePembelian.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker

            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
            });

            $('a.buat-ulang', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');
                          id_sup = $anchor.data('supplier');
                   handleBuatUlang(id, msg, id_sup) 
            });    
        });
    }

    var handlePerpanjangTanggal = function(){
        $('a#load_table').click(function()
        {
            $tablePembelian.api().ajax.url(baseAppUrl + 'listing_pembelian').load();
        });
    }

    var handleDeleteRow = function(id,msg){

        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'delete/' +id;
            } 
        });
    
    };

    var handleBuatUlang = function(id,msg,id_sup){

        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'proses_pembelian/' +id+'/'+id_sup;
            } 
        });
    
    };

     var initform = function()
    {
        // alert("a");
        
    }

    var handleBtnSearchItem = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

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

    var handlePilihItem = function(){
        oTablePilihItem = $tablePilihItem.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_item',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true }
                ]
        });       
        $('#table_pilih_item_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContent.hide();        
    };

    var handlePilihItemTidakTerdaftar = function(){
        oTablePilihItemTidakTerdaftar = $tablePilihItemTidakTerdaftar.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_item_tidak_terdaftar',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true }
                ]
        });       
        $('#table_pilih_item_tidak_terdaftar_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_tidak_terdaftar_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContent.hide();        
    };


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/history/';
        // handleDataTablePermintaan();
        handleDataTableProses();
        handleDataTablePembelian();
        // handleDataTableDraft();
        handlePerpanjangTanggal();
        handlePilihItem();
        handlePilihItemTidakTerdaftar();
        initform();
    };
 }(mb.app.daftar_permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_permintaan_po.init();
});