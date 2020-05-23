mb.app.persetujuan_permintaan_po = mb.app.persetujuan_permintaan_po || {};
(function(o){

    var 
        baseAppUrl                      = '',
        $tablePermintaanPembelian       = $('#table_persetujuan_permintaan_po');
        $tablePermintaanPembelianProses = $('#table_permintaan_pembelian_proses');
        $tableDraftPermintaan           = $('#table_draft_permintaan');
        $popoverItemContent             = $('#popover_item_content'), 
        $popoverItemContentHistory             = $('#popover_item_content_history'), 
        $lastPopoverIdentitas           = null,
		$lastPopoverItem                = null,
        $tablePilihItem                 = $('#table_pilih_item'),
        $tablePilihItemHistory                 = $('#table_pilih_item_history'),
        $tablePilihItemTidakTerdaftar   = $('#table_pilih_item_tidak_terdaftar'),
		$tablePilihItemTidakTerdaftarHistory   = $('#table_pilih_item_tidak_terdaftar_history'),
        theadFilterTemplate             = $('#thead-filter-template').text();

    var handleDataTablePermintaan = function() 
    {
    	$tablePermintaanPembelian.dataTable({
           	'processing'            : true,
            'serverSide'            : true,
			'stateSave'             : true,
			'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
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
        $tablePermintaanPembelian.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

			// $('a[name="info[]"]', this).click(function(){
			// 		var $anchor = $(this),
			// 		      id    = $anchor.data('id');

   //                  oTablePilihItem.api().ajax.url(baseAppUrl + 'listing_pilih_item/' + id).load();
   //                  oTablePilihItemTidakTerdaftar.api().ajax.url(baseAppUrl + 'listing_pilih_item_tidak_terdaftar/' + id).load();
                    
			// });

            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
            });

            // var $btnSearchItem  = $('.pilih-item');
            // handleBtnSearchItem($btnSearchItem);

            // $popoverItemContent.hide();

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
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_proses',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
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
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_draft',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
			'columns'               : [
				{ 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
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
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true }
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
                { 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true }
                ]
        });       
        $('#table_pilih_item_tidak_terdaftar_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_tidak_terdaftar_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContentHistory.hide();        
    };



    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/persetujuan_permintaan_po/';
        handleDataTablePermintaan();
        handleDataTableProses();
        // handlePilihItem();
        // handlePilihItemTidakTerdaftar();
        handlePilihItemHistory();
        handlePilihItemTidakTerdaftarHistory();
        initform();

    };
 }(mb.app.persetujuan_permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.persetujuan_permintaan_po.init();
});