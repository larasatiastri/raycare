mb.app.daftar_permintaan_po = mb.app.daftar_permintaan_po || {};
(function(o){

    var 
        baseAppUrl                      = '',
        $tableDaftarPabrik              = $('#table_daftar_pabrik');
        $tableDraftPermintaan           = $('#table_draft_permintaan');
        $popoverItemContent             = $('#popover_item_content'), 
		$lastPopoverItem                = null,
        $tablePilihItem                 = $('#table_pilih_item'),
        $tablePilihItemTidakTerdaftar   = $('#table_pilih_item_tidak_terdaftar'),
        theadFilterTemplate             = $('#thead-filter-template').text();

    var handleDataTable_Pabrik = function() 
    {
    	$tableDaftarPabrik.dataTable({
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
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tableDaftarPabrik.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

			$('a[name="info[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');

                    oTablePilihItem.api().ajax.url(baseAppUrl + 'listing_pilih_item/' + id).load();
                    // oTablePilihItemTidakTerdaftar.api().ajax.url(baseAppUrl + 'listing_pilih_item_tidak_terdaftar/' + id).load();
                    
			});

            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
            });

            var $btnSearchItem  = $('.pilih-item');
            handleBtnSearchItem($btnSearchItem);

            $popoverItemContent.hide();

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
		});

    }

    var handleDeleteRow = function(id,msg){

        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'delete/' +id;
            } 
        });
    
    };

    

    

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

    var handlePilihItem = function(){
        oTablePilihItem = $tablePilihItem.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
            'pagingType'            : 'full_numbers',
                'url' : baseAppUrl + 'listing_pilih_item',
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
        $('#table_pilih_item_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_item_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContent.hide();        
    };

    var handlePilihItemTidakTerdaftar = function(){
        oTablePilihItemTidakTerdaftar = $tablePilihItemTidakTerdaftar.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'pagingType'            : 'full_numbers',
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
        baseAppUrl = mb.baseUrl() + 'master/pabrik/';
        handleDataTable_Pabrik();
        // handleDataTableProses();
        handlePilihItem();
        // handlePilihItemTidakTerdaftar();
        // handlePilihItemHistory();
        // handlePilihItemTidakTerdaftarHistory();
        initform();

    };
 }(mb.app.daftar_permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_permintaan_po.init();
});