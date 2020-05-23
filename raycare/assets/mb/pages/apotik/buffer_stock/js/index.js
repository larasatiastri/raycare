mb.app.buffer_stock = mb.app.buffer_stock || {};
(function(o){

    var 
        baseAppUrl        = '',
        $tableBufferStock = $('#table_buffer_stok');

    var handleDataTable = function() 
    {
        function fnFormatDetails(oTable, nTr, rows) {
            
            var sOut = '<table>';
            sOut += '<thead>';
            sOut += '<tr><td><b>Batch Number</b></td><td><b>Expire Date</b></td><td><b>Jumlah</b></td></tr>';
            sOut += '</thead>';
            
            sOut += '<tbody>';
            $.each(rows, function(idx, row){
                var bn_sn_lot = '-';
                var expire_date = '-';

                if(row.bn_sn_lot != null){ bn_sn_lot = row.bn_sn_lot; }
                if(row.expire_date != null){ expire_date = row.expire_date; }
                sOut += '<tr>';
                sOut += '<td>'+bn_sn_lot+'</td>';
                sOut += '<td>'+expire_date+'</td>';
                sOut += '<td>'+row.jumlah+' '+row.nama_satuan+'</td>';
                sOut += '</tr>';
            });
            sOut += '</tbody>';
            sOut += '</table>';

            return sOut;
        }

        /*
         * Insert a 'details' column to the table
         */
        var nCloneTh = document.createElement('th');
        nCloneTh.className = "table-checkbox";
        nCloneTh.innerHTML = '<span class="row-details row-details-close" id="head_sign"></span>';

        var nCloneTd = document.createElement('td');
        nCloneTd.innerHTML = '<span class="row-details row-details-close"></span>';

        $tableBufferStock.find('thead tr').each(function () {
            this.insertBefore(nCloneTh, this.childNodes[0]);
        });

        $tableBufferStock.find('tbody tr').each(function () {
            this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
        });

        var gudang_id = $('select#gudang').val();

        oTable = $tableBufferStock.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing/'+gudang_id+'/0/0/1',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'info'                  : false,
            'paginate'              : false,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'visible' : true, 'name' : 'item.kode kode', 'searchable': false, 'orderable': false },
                { 'visible' : true, 'name' : 'item.kode kode', 'searchable': true, 'orderable': false },
                { 'visible' : true, 'name' : 'item.nama nama','searchable': true, 'orderable': false },
                { 'visible' : true, 'name' : 'item.buffer_stok buffer_stok','searchable': true, 'orderable': false },
                { 'visible' : true, 'name' : 'sum(inventory.jumlah) stok', 'searchable': false, 'orderable': false },
                { 'visible' : true, 'name' : 'item_satuan.nama unit','searchable': true, 'orderable': false },
                { 'visible' : true, 'name' : 'item_satuan.nama unit','searchable': false, 'orderable': false },
                ]
        });

        $tableBufferStock.on('click', ' tbody td .row-details', function () {
            var nTr = $(this).parents('tr')[0];
                rows = $(this).data('detail');


            if (oTable.fnIsOpen(nTr)) {
                /* This row is already open - close it */
                $(this).addClass("row-details-close").removeClass("row-details-open");
                $(' thead th .row-details').addClass("row-details-close").removeClass("row-details-open");
                oTable.fnClose(nTr);
            } else {
                /* Open this row */
                $(this).addClass("row-details-open").removeClass("row-details-close");
                $(' thead th .row-details').addClass("row-details-open").removeClass("row-details-close");
                oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr, rows), 'details');
            }
        });

        $tableBufferStock.on('click', ' thead th .row-details', function () {

            $row_detail = $('tbody tr td span.row-details', $tableBufferStock).click();   
            
        });
         
    };

    var handleBtnCari = function(){
        $('a#cariinventory').click(function(){
            var gudang_id = $('select#gudang').val(),
                kategori_id = $('select#kategori').val(),
                sub_kategori_id = $('select#sub_kategori').val(),
                tipe_id = $('select#pilihan').val();

            oTable.api().ajax.url(baseAppUrl +  'listing' + '/' + gudang_id + '/' + kategori_id + '/' + sub_kategori_id+'/'+tipe_id).load();
            $('a#printinventory').attr('href',baseAppUrl +  'cetak_stok' + '/' + gudang_id + '/' + kategori_id + '/' + sub_kategori_id+'/'+tipe_id);
            $('a#printinventory').attr('target',"_blank");
        });
        $('a#printinventory').click(function(){
            $(this).attr('href',baseAppUrl +  'cetak_stok' + '/' + gudang_id + '/' + kategori_id + '/' + sub_kategori_id+'/'+tipe_id);
            $(this).attr('target',"_blank");
        });

    };

    var handleSelectKategori = function(){
         $('select#gudang').on('change', function(){
            var gudang_id = $(this).val(),
                kategori_id = $('select#kategori').val(),
                sub_kategori_id = $('select#sub_kategori').val(),
                tipe_id = $('select#pilihan').val();

            $('a#printinventory').attr('href',baseAppUrl +  'cetak_stok' + '/' + gudang_id + '/' + kategori_id + '/' + sub_kategori_id+'/'+tipe_id);
            $('a#printinventory').attr('target',"_blank");
        });

        $('select#kategori').on('change', function(){


            var gudang_id = $('select#gudang').val(),
                kategori_id = $(this).val(),
                sub_kategori_id = $('select#sub_kategori').val(),
                tipe_id = $('select#pilihan').val(),
                $sub_kategori = $('select#sub_kategori');

            $('a#printinventory').attr('href',baseAppUrl +  'cetak_stok' + '/' + gudang_id + '/' + kategori_id + '/' + sub_kategori_id+'/'+tipe_id);
            $('a#printinventory').attr('target',"_blank");

            $.ajax
            ({ 
                type: 'POST',
                url: baseAppUrl +  "get_sub_kategori",  
                data:  {kategori_id:kategori_id},  
                dataType : 'json',
                beforeSend : function() {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses'});
                },
                success:function(data){ 
                    $sub_kategori.empty();

                    $sub_kategori.html($("<option></option>")
                                .attr("value", 0).text('---Semua Sub Kategori---'));

                    $.each(data, function(idx, data){
                        $sub_kategori.append($("<option></option>")
                                .attr("value", data.id).text(data.nama+' ['+data.kode+']'));
                    });
                },
                complete : function(){
                    Metronic.unblockUI();
                }

            });

        });

        $('select#sub_kategori').on('change', function(){
            var gudang_id = $('select#gudang').val(),
                kategori_id = $('select#kategori').val(),
                sub_kategori_id = $(this).val(),
                tipe_id = $('select#pilihan').val();

            $('a#printinventory').attr('href',baseAppUrl +  'cetak_stok' + '/' + gudang_id + '/' + kategori_id + '/' + sub_kategori_id+'/'+tipe_id);
            $('a#printinventory').attr('target',"_blank");
        });

        $('select#pilihan').on('change', function(){
            var gudang_id = $('select#gudang').val(),
                kategori_id = $('select#kategori').val(),
                sub_kategori_id = $('select#sub_kategori').val(),
                tipe_id = $(this).val();

            $('a#printinventory').attr('href',baseAppUrl +  'cetak_stok' + '/' + gudang_id + '/' + kategori_id + '/' + sub_kategori_id+'/'+tipe_id);
            $('a#printinventory').attr('target',"_blank");
        });



        
    };

    var initForm = function(){
        handleDataTable(); 
        handleBtnCari();
        handleSelectKategori();
    };
 
    // mb.app.home.table properties
    o.init = function(){    
        baseAppUrl = mb.baseUrl() + 'apotik/buffer_stock/';
        initForm();
    };

     
 }(mb.app.buffer_stock));


// initialize  mb.app.home.table
$(function(){
    mb.app.buffer_stock.init();
});