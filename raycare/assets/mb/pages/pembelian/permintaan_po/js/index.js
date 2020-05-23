mb.app.permintaan_po = mb.app.permintaan_po || {};
(function(o){

    var 
        baseAppUrl                           = '',
        $tablePermintaanPembelian            = $('#table_permintaan_pembelian');
        $tablePermintaanPembelianProses      = $('#table_permintaan_pembelian_proses');
        $tableDraftPermintaan                = $('#table_draft_permintaan');
        $popoverItemContent                  = $('#popover_item_content'), 
        $popoverItemContentHistory           = $('#popover_item_content_history'), 
        $lastPopoverIdentitas                = null,
        $lastPopoverItem                     = null,
        $tablePilihItem                      = $('#table_pilih_item'),
        $tablePilihItemHistory               = $('#table_pilih_item_history'),
        $tablePilihItemTidakTerdaftar        = $('#table_pilih_item_tidak_terdaftar'),
        $tablePilihItemTidakTerdaftarHistory = $('#table_pilih_item_tidak_terdaftar_history'),
        theadFilterTemplate                  = $('#thead-filter-template').text();

        var handleDataTablePermintaan = function() 
    {
        function fnFormatDetails(oTable, nTr, rows, rowsRevisi, posisi) {

            var sOut =  '<ol class="cd-multi-steps text-top">';

            $.each(rows, function(idx, row){
                
                //var step = parseInt(idx+1);
                var status = 'class=""';
                var last = false;


                if(row.user_level_id == posisi){
                    status = 'class="current"'; 
                   // alert('test');
                }

                if(row.status != null){ 
                    if(row.status == 1){
                        status = 'class=""'; 
                    }if(row.status == 2){
                        status = 'class="visited"'; 
                        //step = '<i class="fa fa-check"></i>';
                        last = true;
                    }if(row.status == 3){
                        status = 'class="visited"';
                        //step = '<i class="fa fa-check"></i>';
                        last = true;
                    }if(row.status == 4){
                        status = 'class="visited"';
                        //step = '<i class="fa fa-check"></i>';
                        last = true;
                    }
                }

                sOut += '<li '+status+'><em>'+row.nama_level+'</em></li>';


            });
            sOut += '</ol>';

            sOut += '<table>';
            sOut += '<thead>';
            sOut += '<tr><td><b>Posisi</b></td><td><b>Yang Memproses</b></td><td><b>Waktu Proses</b></td><td><b>Waktu Tunggu</b></td><td><b>Status</b></td><td><b>Keterangan</b></td></tr>';
            sOut += '</thead>';
            
            sOut += '<tbody>';
            $.each(rows, function(idx, row){
                var inisial = '-';
                var tanggal_proses = '-';
                var waktu_tunggu = '-';
                var keterangan = '-';
                var status = '-';

                if(row.inisial != null){ inisial = row.inisial; }
                if(row.tanggal_proses != null){ tanggal_proses = row.tanggal_proses; }
                if(row.waktu_tunggu != null){ waktu_tunggu = row.waktu_tunggu; }
                if(row.keterangan != null){ keterangan = row.keterangan; }
                if(row.status != null){ 
                    if(row.status == 1){
                        status = '<div class="text-center"><span class="label label-md label-warning">Dibaca</span></div>'; 
                    }if(row.status == 2){
                        status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>'; 
                    }if(row.status == 3){
                        status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>'; 
                    }if(row.status == 4){
                        status = '<div class="text-center"><span class="label label-md label-danger">Ajukan Revisi</span></div>'; 
                    }
                }

                sOut += '<tr>';
                sOut += '<td><i>'+row.nama_level+'</i></td>';
                sOut += '<td>'+inisial+'</td>';
                sOut += '<td>'+tanggal_proses+'</td>';
                sOut += '<td>'+waktu_tunggu+'</td>';
                sOut += '<td>'+status+'</td>';
                sOut += '<td>'+keterangan+'</td>';
                sOut += '</tr>';
            });
            sOut += '</tbody>';
            sOut += '</table>';

            if(Object.keys(rowsRevisi).length>0){
                sOut += '<table>';
                sOut += '<thead>';
                sOut += '<tr><td colspan="6"><b>Persetujuan Revisi</b></td></tr>';
                sOut += '<tr><td><b>Posisi</b></td><td><b>Yang Memproses</b></td><td><b>Waktu Proses</b></td><td><b>Waktu Tunggu</b></td><td><b>Status</b></td><td><b>Keterangan</b></td></tr>';
                sOut += '</thead>';
                
                sOut += '<tbody>';
                $.each(rowsRevisi, function(idx, rowRevisi){
                    var inisial = '-';
                    var tanggal_proses = '-';
                    var waktu_tunggu = '-';
                    var keterangan = '-';
                    var status = '-';

                    if(rowRevisi.inisial != null){ inisial = rowRevisi.inisial; }
                    if(rowRevisi.tanggal_proses != null){ tanggal_proses = rowRevisi.tanggal_proses; }
                    if(rowRevisi.waktu_tunggu != null){ waktu_tunggu = rowRevisi.waktu_tunggu; }
                    if(rowRevisi.keterangan != null){ keterangan = rowRevisi.keterangan; }
                    if(rowsRevisi.status != null){ 
                        if(rowsRevisi.status == 1){
                            status = '<div class="text-center"><span class="label label-md label-warning">Dibaca</span></div>'; 
                        }if(rowsRevisi.status == 2){
                            status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>'; 
                        }if(rowsRevisi.status == 3){
                            status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>'; 
                        }if(rowsRevisi.status == 4){
                            status = '<div class="text-center"><span class="label label-md label-danger">Ajukan Revisi</span></div>'; 
                        }
                    }

                    sOut += '<tr>';
                    sOut += '<td><i>'+rowRevisi.nama_level+'</i></td>';
                    sOut += '<td>'+inisial+'</td>';
                    sOut += '<td>'+tanggal_proses+'</td>';
                    sOut += '<td>'+waktu_tunggu+'</td>';
                    sOut += '<td>'+status+'</td>';
                    sOut += '<td>'+keterangan+'</td>';
                    sOut += '</tr>';
                });
                sOut += '</tbody>';
                sOut += '</table>';    
            }

            return sOut;
                    }

        /*
         * Insert a 'details' column to the table
         */
        var nCloneTh = document.createElement('th');
        nCloneTh.className = "table-checkbox";

        var nCloneTd = document.createElement('td');
        nCloneTd.innerHTML = '<span class="row-details row-details-close"></span>';

        $tablePermintaanPembelian.find('thead tr').each(function () {
            this.insertBefore(nCloneTh, this.childNodes[0]);
        });

        $tablePermintaanPembelian.find('tbody tr').each(function () {
            this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
        });

        oTable = $tablePermintaanPembelian.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_status',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'permintaan_status.created_date tanggal', 'visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'permintaan_status.created_date tanggal', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'user.inisial inisial','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'permintaan_status.transaksi_nomor transaksi_nomor','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'order_permintaan_barang.subjek subjek','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'permintaan_status.status status','visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'b.nama nama_level_proses','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'permintaan_status.waktu_akhir waktu_akhir','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'permintaan_status.waktu_akhir waktu_akhir','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
    
         $tablePermintaanPembelian.on('draw.dt', function (){
            $('.btn', this).tooltip();

        
            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
            });

            /////////////////tooltip///////////////////////////

            var $colItems = $('.show-notes', this);

            $.each($colItems, function(idx, colItem){
                var
                $colItem = $(colItem),
                itemsData = $colItem.data('content');
            
            $colItem.popover({
                html : true,
                container : 'body',
                placement : 'bottom',
                content: function(){

                    var html = '<table class="table table-striped table-hover">';
                        html += '<tr>';
                        html += '<td>'+itemsData+'</td>';
                        html += '</tr>';
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

            /////////////////////////////////////popover terdaftar////////////////////////////////////////////////////

            var $identitasItem = $('.pilih-item', this);

            $.each($identitasItem, function(idx, col){
                var
                    $col            = $(col),
                    dataItem        = $col.data('item');

                // console.log(dataIdentitas);
                $col.popover({
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
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverIdentitas !== null) $lastPopoverIdentitas.popover('hide');
                    $lastPopoverIdentitas = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverIdentitas = null;
                }).on('click', function(e){

                });
            });

            /////////////////////////////////////popover tidak terdaftar////////////////////////////////////////////////////

            var $identitasItem = $('.item-unlist', this);

            $.each($identitasItem, function(idx, col){
                var
                    $col            = $(col),
                    dataItem        = $col.data('item');

                // console.log(dataIdentitas);
                $col.popover({
                    html : true,
                    container : 'body',
                    placement : 'bottom',
                    content: function(){
                        
                        var html = '<table class="table table-striped table-hover">';
                            html += '<tr class="heading bold">';
                            html += '<td class="text-center">Nama</td>'
                            html += '<td class="text-center">Jumlah</td>'
                            html += '</tr>';

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.nama + '</td>'
                            html += '<td class="text-left">' + item.jumlah + ' ' + item.satuan +'</td>'
                            html += '</tr>';

                        });
                        html += '</table>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverIdentitas !== null) $lastPopoverIdentitas.popover('hide');
                    $lastPopoverIdentitas = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverIdentitas = null;
                }).on('click', function(e){

                });
            });
        });
        /* Add event listener for opening and closing details
         * Note that the indicator for showing which row is open is not controlled by DataTables,
         * rather it is done here
         */
        $tablePermintaanPembelian.on('click', ' tbody td .row-details', function () {
            var nTr = $(this).parents('tr')[0];
                rows = $(this).data('row');
                rowsRevisi = $(this).data('row_revisi');
                posisi = $(this).data('posisi');

            if (oTable.fnIsOpen(nTr)) {
                /* This row is already open - close it */
                $(this).addClass("row-details-close").removeClass("row-details-open");
                oTable.fnClose(nTr);
            } else {
                /* Open this row */
                $(this).addClass("row-details-open").removeClass("row-details-close");
                oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr, rows, rowsRevisi, posisi), 'details');
            }
        });

    }

    var handleProgressStep = function()
    {
        $('.step').each(function(index, element) {
            // element == this
            $(element).not('.active').addClass('done');
            $('.done').html('<i class="icon-ok"></i>');
            if($(this).is('.active')) {
              return false;
            }
          });    
    }


    var handleDeleteRow = function(id,msg){

        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'delete/' +id;
            } 
        });
    
    };

    var handleDataTableProses = function() 
    {
        $tablePermintaanPembelianProses.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_proses',
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
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });

        $(theadFilterTemplate).appendTo($('thead', $tablePermintaanPembelianProses));
        $('#pembelian_status').on('change', function(){
            var iStat   = this.value;

            $tablePermintaanPembelianProses.api().ajax.url(baseAppUrl + 'listing_proses/' + iStat).load();
            // $tablePermintaanPembelianProses.fnClearTable();
        });
        $tablePermintaanPembelianProses.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker

            $('a[name="info_history[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');

                    oTablePilihItemhistory.api().ajax.url(baseAppUrl + 'listing_pilih_item_history/' + id).load();
                    oTablePilihItemTidakTerdaftarHistory.api().ajax.url(baseAppUrl + 'listing_pilih_item_tidak_terdaftar_history/' + id).load();
                    
            });

            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
            });

            var $btnSearchItem  = $('.pilih-item-history');
            handleBtnSearchItemHistory($btnSearchItem);


            /////////////////tooltip///////////////////////////

            var $colItems = $('.show-notes-proses', this);

            $.each($colItems, function(idx, colItem){
                var
                    $colItem = $(colItem),
                    itemsData = $colItem.data('content');
            
            $colItem.popover({
                    html : true,
                    container : 'body',
                    placement : 'left',
                    content: function(){

                        var html = '<table class="table table-striped table-hover">';
                            html += '<tr>';
                            html += '<td>'+itemsData+'</td>';
                            html += '</tr>';
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
        
        $popoverItemContentHistory.hide();        

    }

    var handleDataTableDraft = function() 
    {
        $tableDraftPermintaan.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_draft',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'desc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $tableDraftPermintaan.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker

            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
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

    var handleBtnSearchItemHistory = function($btn){
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

            $popoverItemContentHistory.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContentHistory ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContentHistory);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverItemContentHistory.hide();
            $popoverItemContentHistory.appendTo($('.page-content'));

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
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
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
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });       
        $('#table_pilih_item_tidak_terdaftar_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_tidak_terdaftar_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContent.hide();        
    };

    var handlePilihItemHistory = function(){
        oTablePilihItemhistory = $tablePilihItemHistory.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_item_history',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });       
        $('#table_pilih_item_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContentHistory.hide();        
    };

    var handlePilihItemTidakTerdaftarHistory = function(){
        oTablePilihItemTidakTerdaftarHistory = $tablePilihItemTidakTerdaftarHistory.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pilih_item_tidak_terdaftar_history',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false }
                ]
        });       
        $('#table_pilih_item_tidak_terdaftar_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_tidak_terdaftar_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContentHistory.hide();        
    };



    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/permintaan_po/';
        handleDataTablePermintaan();
        handleDataTableProses();
        initform();

    };
 }(mb.app.permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.permintaan_po.init();
});