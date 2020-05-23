mb.app.proses_pembayaran_transaksi = mb.app.proses_pembayaran_transaksi || {};
(function(o){

    var 
        baseAppUrl              = '',
        $table1 = $('#table_pembayaran_transaksi'),
        $table2 = $('#table_pembayaran_transaksi_history');

    var handleDataTable = function() 
    {

        function fnFormatDetails(oTable, nTr, rows, rowsRevisi) {
            
            var sOut = '<table>';
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

        $table1.find('thead tr').each(function () {
            this.insertBefore(nCloneTh, this.childNodes[0]);
        });

        $table1.find('tbody tr').each(function () {
            this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
        });

    	oTable = $table1.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
            'stateSave'             : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
            'filter'                : true,
            'paginate'              : true,
            'info'                  : false,
            'pagingType'            : 'full_numbers',
			'columns'               : [
                { 'name' : 'pembayaran_status.created_date tanggal', 'visible' : true, 'searchable': false, 'orderable': false },
				{ 'name' : 'pembayaran_status.created_date tanggal', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'user.inisial inisial','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.tipe_transaksi tipe_transaksi','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.transaksi_nomor transaksi_nomor','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.nominal nominal','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.status status','visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'b.nama nama_level_proses','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.waktu_akhir waktu_akhir','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.waktu_akhir waktu_akhir','visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        

        /* Add event listener for opening and closing details
         * Note that the indicator for showing which row is open is not controlled by DataTables,
         * rather it is done here
         */
        $table1.on('click', ' tbody td .row-details', function () {
            var nTr = $(this).parents('tr')[0];
                rows = $(this).data('row');
                rowsRevisi = $(this).data('row_revisi');

            if (oTable.fnIsOpen(nTr)) {
                /* This row is already open - close it */
                $(this).addClass("row-details-close").removeClass("row-details-open");
                oTable.fnClose(nTr);
            } else {
                /* Open this row */
                $(this).addClass("row-details-open").removeClass("row-details-close");
                oTable.fnOpen(nTr, fnFormatDetails(oTable, nTr, rows, rowsRevisi), 'details');
            }
        });
    }

    var handleDataTableHistory = function() 
    {

        function fnFormatDetailsHistory(oTable, nTr, rows, rowsRevisi) {
            
            var sOut = '<table>';
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

        $table2.find('thead tr').each(function () {
            this.insertBefore(nCloneTh, this.childNodes[0]);
        });

        $table2.find('tbody tr').each(function () {
            this.insertBefore(nCloneTd.cloneNode(true), this.childNodes[0]);
        });

        oTableHistory = $table2.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_history',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'desc']],
            'filter'                : true,
            'paginate'              : true,
            'info'                  : false,
            'pagingType'            : 'full_numbers',
            'columns'               : [
                { 'name' : 'pembayaran_status.created_date tanggal', 'visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'pembayaran_status.created_date tanggal', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'user.inisial inisial','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.tipe_transaksi tipe_transaksi','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.transaksi_nomor transaksi_nomor','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.nominal nominal','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.status status','visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'b.nama nama_level_proses','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.waktu_akhir waktu_akhir','visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembayaran_status.waktu_akhir waktu_akhir','visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        

        /* Add event listener for opening and closing details
         * Note that the indicator for showing which row is open is not controlled by DataTables,
         * rather it is done here
         */
        $table2.on('click', ' tbody td .row-details', function () {
            var nTr = $(this).parents('tr')[0];
                rows = $(this).data('row');
                rowsRevisi = $(this).data('row_revisi');

            if (oTableHistory.fnIsOpen(nTr)) {
                /* This row is already open - close it */
                $(this).addClass("row-details-close").removeClass("row-details-open");
                oTableHistory.fnClose(nTr);
            } else {
                /* Open this row */
                $(this).addClass("row-details-open").removeClass("row-details-close");
                oTableHistory.fnOpen(nTr, fnFormatDetailsHistory(oTableHistory, nTr, rows, rowsRevisi), 'details');
            }
        });
    }
   

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'keuangan/proses_pembayaran_transaksi/';
        handleDataTable();
        handleDataTableHistory();
  

    };
 }(mb.app.proses_pembayaran_transaksi));


// initialize  mb.app.home.table
$(function(){
    mb.app.proses_pembayaran_transaksi.init();
});