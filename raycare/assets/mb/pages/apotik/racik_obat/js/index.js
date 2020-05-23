mb.app.racik_obat = mb.app.racik_obat || {};
(function(o){

    var 
		baseAppUrl                      = '',
		$popoverKomposisiContent        = $('#popover_komposisi_content'), 
		$popoverKomposisiHistoryContent = $('#popover_komposisi_history_content'), 
		$lastPopoverKomposisi           = null,
		$lastPopoverKomposisiHistory    = null,
		$lastPopoverKeterangan          = null,
		$tableResep                     = $('#table_resep');
		$tableResepHistory              = $('#table_resep_history');
		$tableResepManual               = $('#table_resep_manual');
		$tableResepManualHistory        = $('#table_resep_manual_history');
		$tableKomposisiItem             = $('#table_komposisi_item');
		$tableKomposisiItemHistory      = $('#table_komposisi_item_history');
		$tableKomposisiManual           = $('#table_komposisi_manual');
		$tableKomposisiManualHistory    = $('#table_komposisi_manual_history');

    var handleDataTable = function() 
    {
		$tableResep.dataTable({
	       	'processing'            : true,
			'serverSide'            : true,
			'stateSave'             : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
                { 'name' : 'resep_obat_racikan.id id', 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'name' : '`user`.nama nama_dokter', 'visible' : true, 'searchable': false, 'orderable': true },
				{ 'name':'pasien.nama nama_pasien', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name':'resep_obat_racikan.nama nama_resep', 'visible' : true, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': true },
				{ 'name':'resep_obat_racikan.keterangan keterangan', 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': false, 'orderable': false },	    		]
	    });

        $tableResep.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      pasien_id    = $anchor.data('pasien_id');
					      msg    = $anchor.data('confirm');

					handleDeleteRow(id,msg, pasien_id);
			});

			$('a[name="restore[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg    = $anchor.data('confirm');

					handleRestoreRow(id,msg);
			});	

			$('a[name="komposisi[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');

                    $tableKomposisiItem.api().ajax.url(baseAppUrl + 'listing_komposisi_item/' + id).load();
                    $tableKomposisiManual.api().ajax.url(baseAppUrl + 'listing_komposisi_manual/' + id).load();
                    
            });

            var $btnKomposisiItem  = $('.komposisi-item');
            handleBtnKomposisiItem($btnKomposisiItem);

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
                    $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '360px'});

                    if ($lastPopoverKeterangan !== null) $lastPopoverKeterangan.popover('hide');
                    $lastPopoverKeterangan = $colItem;
                }).on('hide.bs.popover', function(){
                    $lastPopoverKeterangan = null;
                }).on('click', function(e){
                });
            });
		
		} );
    }

    var handleDataTableHistory = function() 
    {
		$tableResepHistory.dataTable({
	       	'processing'            : true,
			'serverSide'            : true,
			'stateSave'             : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_history/',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false},	    		]
	    });

        $tableResepHistory.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      pasien_id    = $anchor.data('pasien_id');
					      msg    = $anchor.data('confirm');

					handleDeleteRow(id,msg, pasien_id);
			});

			$('a[name="restore[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg    = $anchor.data('confirm');

					handleRestoreRow(id,msg);
			});	

			$('a[name="komposisi_history[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');

                    $tableKomposisiItemHistory.api().ajax.url(baseAppUrl + 'listing_komposisi_item_history/' + id).load();
                    $tableKomposisiManualHistory.api().ajax.url(baseAppUrl + 'listing_komposisi_manual_history/' + id).load();
                    
            });

            var $btnKomposisiHistoryItem  = $('.komposisi-history-item');
            handleBtnKomposisiHistoryItem($btnKomposisiHistoryItem);

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
                        $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '360px'});

                        if ($lastPopoverKeterangan !== null) $lastPopoverKeterangan.popover('hide');
                        $lastPopoverKeterangan = $colItem;
                    }).on('hide.bs.popover', function(){
                        $lastPopoverKeterangan = null;
                    }).on('click', function(e){
                    });
                });
		
		} );
    }


    var handleDataTableResepManual = function() 
    {
		$tableResepManual.dataTable({
	       	'processing'            : true,
			'serverSide'            : true,
			'stateSave'             : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_resep_manual/',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
                { 'name' : 'tindakan_resep_obat_manual.id id', 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'name' : '`user`.nama nama_dokter', 'visible' : true, 'searchable': false, 'orderable': true },
				{ 'name':'pasien.nama nama_pasien', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name':'resep_obat_racikan.keterangan keterangan', 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': false, 'orderable': false },	    		]
	    });

        $tableResepManual.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker
            var $btnKomposisiItem  = $('.komposisi-item');
            handleBtnKomposisiItem($btnKomposisiItem);

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
                        $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '360px'});

                        if ($lastPopoverKeterangan !== null) $lastPopoverKeterangan.popover('hide');
                        $lastPopoverKeterangan = $colItem;
                    }).on('hide.bs.popover', function(){
                        $lastPopoverKeterangan = null;
                    }).on('click', function(e){
                    });
                });
		
		} );
    }

    var handleDataTableResepManualHistory = function() 
    {
		$tableResepManualHistory.dataTable({
	       	'processing'            : true,
			'serverSide'            : true,
			'stateSave'             : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_resep_manual_history/',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[0, 'desc']],
			'columns'               : [
                { 'name' : 'tindakan_resep_obat_manual.id id', 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'name' : '`user`.nama nama_dokter', 'visible' : true, 'searchable': false, 'orderable': true },
				{ 'name':'pasien.nama nama_pasien', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'name':'resep_obat_racikan.keterangan keterangan', 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },	    		]
	    });

        $tableResepManualHistory.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

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
                        $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '360px'});

                        if ($lastPopoverKeterangan !== null) $lastPopoverKeterangan.popover('hide');
                        $lastPopoverKeterangan = $colItem;
                    }).on('hide.bs.popover', function(){
                        $lastPopoverKeterangan = null;
                    }).on('click', function(e){
                    });
                });
		
		} );
    }
    var handleDataTableKomposisiItem = function(){
        $tableKomposisiItem.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'filter'                : false,
            'paginate'              : false,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_komposisi_item',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'item.id id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'item.kode item_kode','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama item_nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true }
                ]
        });

        $tableKomposisiItem.on('draw.dt', function (){
            $('.btn', this).tooltip();         
        } );    
    };

    var handleDataTableKomposisiItemHistory = function(){
        $tableKomposisiItemHistory.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_komposisi_item_history',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'item.id id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'name' : 'item.kode item_kode','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama item_nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true }
                ]
        });

        $tableKomposisiItemHistory.on('draw.dt', function (){
            $('.btn', this).tooltip();         
        } );    
    };

    var handleDataTableKomposisiManual = function(){
        $tableKomposisiManual.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'filter'                : false,
            'paginate'              : false,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_komposisi_manual',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'resep_obat_racikan_detail_manual.id id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'resep_obat_racikan_detail_manual.keterangan keterangan','visible' : true, 'searchable': true, 'orderable': false },
                ]
        });

        $tableKomposisiManual.on('draw.dt', function (){
            $('.btn', this).tooltip();
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
                    $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '360px'});

                    if ($lastPopoverKeterangan !== null) $lastPopoverKeterangan.popover('hide');
                    $lastPopoverKeterangan = $colItem;
                }).on('hide.bs.popover', function(){
                    $lastPopoverKeterangan = null;
                }).on('click', function(e){
                });
            });         
        });    
    };

    var handleDataTableKomposisiManualHistory = function(){
        $tableKomposisiManualHistory.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_komposisi_manual_history',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                { 'name' : 'resep_obat_racikan_detail_manual.id id', 'visible' : false, 'searchable': false, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': false },
                { 'name' : 'resep_obat_racikan_detail_manual.keterangan keterangan','visible' : true, 'searchable': true, 'orderable': true },
                ]
        });

        $tableKomposisiManualHistory.on('draw.dt', function (){
            $('.btn', this).tooltip();  
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
                    $(this).data("bs.popover").tip().css({minWidth: '360px', maxWidth: '360px'});

                    if ($lastPopoverKeterangan !== null) $lastPopoverKeterangan.popover('hide');
                    $lastPopoverKeterangan = $colItem;
                }).on('hide.bs.popover', function(){
                    $lastPopoverKeterangan = null;
                }).on('click', function(e){
                });
            });       
        } );    
    };

    var handleBtnKomposisiItem = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // alert($btn.data('id'));

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverKomposisi != null) $lastPopoverKomposisi.popover('hide');

            $lastPopoverKomposisi = $btn;

            $popoverKomposisiContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverKomposisiContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverKomposisiContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverKomposisiContent.hide();
            $popoverKomposisiContent.appendTo($('.page-content'));

            $lastPopoverKomposisi = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleBtnKomposisiHistoryItem = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // alert($btn.data('id'));

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px'});

            if ($lastPopoverKomposisiHistory != null) $lastPopoverKomposisiHistory.popover('hide');

            $lastPopoverKomposisiHistory = $btn;

            $popoverKomposisiHistoryContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverKomposisiHistoryContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverKomposisiHistoryContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverKomposisiHistoryContent.hide();
            $popoverKomposisiHistoryContent.appendTo($('.page-content'));

            $lastPopoverKomposisiHistory = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDeleteRow = function(id,msg, pasien_id){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'delete/' +id;
			} 
		});
	
	};

	var handleRestoreRow = function(id,msg){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'restore/' +id;
			} 
		});
	
	};

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/racik_obat/';
        handleDataTable();
        handleDataTableHistory();
        handleDataTableResepManual();
        handleDataTableResepManualHistory();
        handleDataTableKomposisiItem();
        handleDataTableKomposisiItemHistory();
        handleDataTableKomposisiManual();
        handleDataTableKomposisiManualHistory();
        $popoverKomposisiContent.hide();
        $popoverKomposisiHistoryContent.hide();

    };
 }(mb.app.racik_obat));


// initialize  mb.app.home.table
$(function(){
    mb.app.racik_obat.init();
});