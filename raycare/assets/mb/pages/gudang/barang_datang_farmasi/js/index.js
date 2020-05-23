mb.app.gudang = mb.app.gudang || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableBarangDatang = $('#table_barang_datang_farmasi'),
        $tablePO = $('#table_pembelian_datang'),
        $lastPopoverIdentitas = null,
        $tableDraftBarangDatang = $('#table_draft_barang_datang_farmasi')
        ;

    var initform = function()
    {
        
    }

    var handleDataTable = function() 
    {
    	$tableBarangDatang.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'name' : 'pmb.id id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'pmb.no_pmb no_pmb', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pmb.no_surat_jalan no_surat_jalan', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pmb.tanggal tanggal', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'supplier.nama supplier_nama', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembelian.keterangan keterangan', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $tableBarangDatang.on('draw.dt', function (){
            $('.btn', this).tooltip();
            // action for delete locker
        });

        $tablePO.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_pembelian_datang',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'asc']],
			'columns'               : [
				{ 'name' : 'pembelian.id po_id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'pembelian.tanggal_pesan tanggal_pesan', 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'pembelian.no_pembelian no_pembelian', 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'supplier.nama supplier_nama', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'supplier.nama supplier_nama', 'visible' : true, 'searchable': false, 'orderable': false },
				{ 'name' : 'pembelian_detail_tanggal_kirim.tanggal_kirim tanggal_kirim', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'user.nama pj_po','visible' : true, 'searchable': false, 'orderable': false },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tablePO.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

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
                            html += '<td class="text-left">' + item.jumlah_kirim + ' ' + item.nama_satuan +'</td>'
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

    }

    var handleDataTableDraft = function() 
    {
        $tableDraftBarangDatang.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_draft',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'name' : 'pmb.id id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'pmb.catatan_gudang catatan_gudang', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pmb.tanggal tanggal', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'pembelian.keterangan keterangan', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                ]
        });
        $tableDraftBarangDatang.on('draw.dt', function (){
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
                location.href = baseAppUrl + 'delete_draft/' +id;
            } 
        });
    
    };

	var handleSelectGudang = function(){
        $('select#gudang_id').on('change',function(){
            // alert($('input[name="tipe_supplier"]:checked').val());
            $tableBarangDatang.api().ajax.url(baseAppUrl + 'listing/' + $('input[name="tipe_supplier"]:checked').val() + '/' + $(this).val()).load();
        });
    }

    var handleTipeSupplier = function(){
        $('input[name="tipe_supplier"]').on('change',function(){
            // alert($('select#gudang_id option:selected').val());
            $tableBarangDatang.api().ajax.url(baseAppUrl + 'listing/' + $(this).val() + '/' + $('select#gudang_id option:selected').val()).load();
        });
    }

    var handleTambah = function(){
        $('a#tambah').on('click', function(){
            if ($('select#gudang_id option:selected').val() != '') {
                $('a#modal_add').click();
            }else{
                $('select#gudang_id').focus();
            }
        });
    }
    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'gudang/barang_datang_farmasi/';
        handleDataTable();
        handleDataTableDraft();
        handleSelectGudang();
        handleTipeSupplier();
        handleTambah();
        // handleDataTableInfoItem();
        // handleDataTableHistoryPecah();
        initform();
    };
 }(mb.app.gudang));


// initialize  mb.app.home.table
$(function(){
    mb.app.gudang.init();
});