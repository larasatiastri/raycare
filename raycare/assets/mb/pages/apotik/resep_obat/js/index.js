mb.app.resep_obat = mb.app.resep_obat || {};
(function(o){

    var 
        baseAppUrl             = '',
        $popoverItemContent    = $('#popover_item_content'), 
        $lastPopoverItem       = null,
        $lastPopoverIdentitas  = null,
        $lastPopoverIdentitasManual  = null,
        $tableResepObat        = $('#table_resep_obat');
        $tableBoxPaket        = $('#table_box_paket');
        $tableBoxPaketHistory        = $('#table_box_paket_history');
        $tableResepObatHistory = $('#table_resep_obat_history');
        $tableResepObatBatal  = $('#table_item_batal');

    var handleDataTable = function() 
    {
		$tableBoxPaket.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_box_paket/1',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'desc']],
            'columns'               : [
                { 'name' : 'permintaan_box_paket.id id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'pasien.nama nama_pasien', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'bed.kode kode_bed', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'dr.nama nama_dokter', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'permintaan_box_paket.id id','visible' : true, 'searchable': false, 'orderable': true },             
            ]
        });

        $tableBoxPaket.on('draw.dt', function(){
            $('a[name="delete_box_paket[]"]', this).click(function(){
                var $anchor = $(this),
                      id    = $anchor.data('id');
                      msg    = $anchor.data('confirm');

                handleDeleteBoxPaket(id,msg);
            });     
        });

        $tableBoxPaketHistory.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_box_paket_history',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'desc']],
            'columns'               : [
                { 'name' : 'permintaan_box_paket.id id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'permintaan_box_paket.no_permintaan no_permintaan', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'pasien.nama nama_pasien', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'bed.kode kode_bed', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'permintaan_box_paket.kode_box_paket kode_box_paket', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'dr.nama nama_dokter', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'permintaan_box_paket.id id','visible' : true, 'searchable': false, 'orderable': true },             
            ]
        });

        $tableResepObat.dataTable({
	       	'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
			'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/1',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
				{ 'name' : 'tindakan_resep_obat.id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'tindakan_resep_obat.nomor_resep nomor_resep', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'pasien.nama nama_pasien', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'dr.nama nama_dokter', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name' : 'user_level.nama nama_level','visible' : true, 'searchable': false, 'orderable': true },
				{ 'name' : 'user_level.nama nama_level','visible' : true, 'searchable': false, 'orderable': true },	    		
            ]
	    });

        $tableResepObat.on('draw.dt', function (){
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
                            html += '<td class="text-center">Dosis</td>'
                            html += '</tr>';

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.kode_item + '</td>'
                            html += '<td class="text-left">' + item.nama_item + '</td>'
                            html += '<td class="text-left">' + item.jumlah + ' ' + item.nama_satuan +'</td>'
                            html += '<td class="text-left">' + item.dosis + '</td>'
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

            var $identitasItemManual = $('.pilih-item-manual', this);

            $.each($identitasItemManual, function(idx, col){
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
                            html += '<td class="text-center">Keterangan</td>'
                            html += '</tr>';

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.keterangan + '</td>'
                            html += '</tr>';

                        });
                        html += '</table>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverIdentitasManual !== null) $lastPopoverIdentitasManual.popover('hide');
                    $lastPopoverIdentitasManual = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverIdentitasManual = null;
                }).on('click', function(e){

                });
            });

	        var $btnInfoItem  = $('.info-item');
            handleBtnInfoItem($btnInfoItem);

            $('a[name="delete_resep[]"]', this).click(function(){
                var $anchor = $(this),
                      id    = $anchor.data('id');
                      msg    = $anchor.data('confirm');

                handleDeleteResep(id,msg);
            });

            	
		});	
    }

    var handleDataTableProses = function() 
    {
        $tableResepObatBatal.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_batal',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'desc']],
            'columns'               : [

                { 'name' : 'pasien.nama nama_pasien','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.kode kode','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item_satuan.nama satuan','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'inventory.bn_sn_lot bn_sn_lot','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'inventory.expire_date expire_date','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'user.nama nama_user','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': false, 'orderable': false },           
            ]
        });      

        $tableResepObatBatal.on('draw.dt', function (){
            $('a[name="delete[]"]', this).click(function(){
                var $anchor = $(this),
                      id    = $anchor.data('id');
                      msg    = $anchor.data('confirm');

                handleDeleteItem(id,msg);
            });

        });
    }


    var handleDataTableHistory = function(){
        $tableResepObatHistory.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_history/2',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'desc']],
            'columns'               : [

                { 'name' : 'tindakan_resep_obat.id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'tindakan_resep_obat.nomor_resep nomor_resep', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'pasien.nama nama_pasien', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'dr.nama nama_dokter', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'user_level.nama nama_level','visible' : true, 'searchable': false, 'orderable': true },
                { 'name' : 'user.nama nama_user','visible' : true, 'searchable': false, 'orderable': true },                
                { 'name' : 'user.nama nama_user','visible' : true, 'searchable': false, 'orderable': true },                
            ]
        });

        $tableResepObatHistory.on('draw.dt', function (){
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
                            html += '<td class="text-center">Dosis</td>'
                            html += '</tr>';

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.kode_item + '</td>'
                            html += '<td class="text-left">' + item.nama_item + '</td>'
                            html += '<td class="text-left">' + item.jumlah + ' ' + item.nama_satuan +'</td>'
                            html += '<td class="text-left">' + item.dosis + '</td>'
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

             var $identitasItemManual = $('.pilih-item-manual', this);

            $.each($identitasItemManual, function(idx, col){
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
                            html += '<td class="text-center">Keterangan</td>'
                            html += '</tr>';

                        $.each(dataItem, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-left">' + item.keterangan + '</td>'
                            html += '</tr>';

                        });
                        html += '</table>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverIdentitasManual !== null) $lastPopoverIdentitasManual.popover('hide');
                    $lastPopoverIdentitasManual = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverIdentitasManual = null;
                }).on('click', function(e){

                });
            });

            var $btnInfoItem  = $('.info-item');
            handleBtnInfoItem($btnInfoItem);            
        } );    
    };

    var handleBtnInfoItem = function($btn){
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

    var handleDeleteItem = function(id,msg) {
        
        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'delete_item_batal/' +id;
            } 
        });
    };

    var handleDeleteResep = function(id,msg) {
        
        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'delete/' +id;
            } 
        });
    };

    var handleDeleteBoxPaket = function(id,msg) {
        
        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'delete_box_paket/' +id;
            } 
        });
    };



    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/resep_obat/';
        handleDataTable();
        handleDataTableProses();
        handleDataTableHistory();
        $popoverItemContent.hide();
    };
 }(mb.app.resep_obat));


// initialize  mb.app.home.table
$(function(){
    mb.app.resep_obat.init();
});