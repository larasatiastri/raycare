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
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $(theadFilterTemplate2).appendTo($('thead', $tablePembelian));
        $('#pembelian_baru_status').on('change', function(){
            var iStat   = this.value;

            // alert($('input[name="tipe_supplier"]:checked').val());

            $tablePembelian.api().ajax.url(baseAppUrl + 'listing_pembelian/' + iStat).load();
            // $tablePermintaanPembelianProses.fnClearTable();
        });
        $tablePembelian.on('draw.dt', function (){
           // $('.btn', this).tooltip();
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
                   handleDeleteRow(id, msg) 
            });    
        });
    }

    var handleDeleteRow = function(id,msg){

        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'delete/' +id;
            } 
        });
    
    };

     var initform = function()
    {
        // alert("a");
        
    }


    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/persetujuan_po/';
        // handleDataTablePermintaan();
        handleDataTablePembelian();
        // handleDataTableDraft();
        initform();
    };
 }(mb.app.daftar_permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_permintaan_po.init();
});