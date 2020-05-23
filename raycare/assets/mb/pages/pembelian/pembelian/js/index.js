mb.app.daftar_permintaan_po = mb.app.daftar_permintaan_po || {};
(function(o){

    var 
        baseAppUrl            = '',
        $tablePembelianBaru   = $('#table_pembelian_baru');
        $tablePembelianProses = $('#table_pembelian_proses');
        $tablePembelianLunas = $('#table_pembelian_lunas');
        $tableDraftPermintaan = $('#table_draft_permintaan');
        $popoverItemContent   = $('#popover_item_content'), 
        $lastPopoverItem      = null,
        $lastPopoverCetak     = null,
        $tablePilihCetak      = $('#table_pilih_cetak'),
        theadFilterTemplate   = $('#thead-filter-template').text();


    var initform = function()
    {    
         $('input[name="tipe_supplier"]').on('click', function(){
            iStatTipe   = this.value;

            $tablePembelianBaru.api().ajax.url(baseAppUrl + 'listing/' + iStatTipe + '/').load();
            $tablePembelianProses.api().ajax.url(baseAppUrl + 'listing_proses/' + iStatTipe).load();
            $tableDraftPermintaan.api().ajax.url(baseAppUrl + 'listing_draft/' + iStatTipe).load();
        });
    }

    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "right",
                autoclose: true
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    }

    var handleDataTablePermintaan = function() 
    {
    	$tablePembelianBaru.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/1/',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
			'columns'               : [
				{ 'visible' : false, 'searchable': false, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });

        $tablePembelianBaru.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

            $('a[name="delete[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleDeleteRow(id,msg);
            });

            $('a[name="proses[]"]', this).click(function(){
                    var $anchor = $(this),
                          id    = $anchor.data('id');
                          msg    = $anchor.data('confirm');

                    handleProsesRow(id,msg);
            });

						
		});
    }

    var handleDataTableProses = function() 
    {
    	$tablePembelianProses.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_proses/1' ,
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
				{ 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });

        
        $tablePembelianProses.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

        var $colCetak = $('.no_cetak', this);

            $.each($colCetak, function(idx, col){
                var
                    $col      = $(col),
                    cetakData = $col.data('cetak');

                // console.log(cetakData);
                $col.popover({
                    html : true,
                    container : 'body',
                    placement : 'bottom',
                    content: function(){
                        
                        var html = '<table class="table table-striped table-hover" style="margin-bottom: 0px;">';
                            html += '<tr class="heading bold">';
                            html += '<td class="text-center">No Cetak</td>'
                            html += '<td class="text-center">User</td>'
                            html += '<td class="text-center">Tanggal Cetak</td>'
                            html += '</tr>';

                        $.each(cetakData, function(idx, item){
                            html += '<tr">';
                            html += '<td class="text-center">' + item.no_cetak + '</td>'
                            html += '<td class="text-center">' + item.nama + '</td>'
                            html += '<td class="text-center">' + item.created_date + '</td>'
                            html += '</tr>';

                        });
                        html += '</table>';
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '350px', maxWidth: '720px'});
                    if ($lastPopoverCetak !== null) $lastPopoverCetak.popover('hide');
                    $lastPopoverCetak = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverCetak = null;
                }).on('click', function(e){

                });
            });


		});

        $tablePembelianLunas.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'pagingType'            : 'full_numbers',
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_lunas' ,
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
    }

    var handleDataTableDraft = function() 
    {
    	$tableDraftPermintaan.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_draft/1',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
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

    var handleProsesRow = function(id,msg){

        bootbox.confirm(msg, function(result) {
            if(result==true) {
                location.href = baseAppUrl + 'proses/' +id;
            } 
        });
    
    };


    var handleBtnSearchCetak = function($btn){
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

    var handlePilihCetak = function(){
        oTablePilihItem = $tablePilihCetak.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_pembelian_cetak',
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
        $('#table_pilih_cetak_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_pilih_cetak_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverItemContent.hide();        
    };

    var handlePerpanjangTanggal = function(){
        $('a#load_table').click(function()
        {
            $tablePembelianProses.api().ajax.url(baseAppUrl + 'listing_proses/' + $('input[name="tipe_supplier"]:checked').val()).load();
        });
    }

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'pembelian/pembelian/';
        handleDataTablePermintaan();
        handleDataTableProses();
        handleDataTableDraft();
        handlePilihCetak();
        handlePerpanjangTanggal();
        // handleUpdatePerpanjang();
        handleDatePickers();
        initform();
    };
 }(mb.app.daftar_permintaan_po));


// initialize  mb.app.home.table
$(function(){
    mb.app.daftar_permintaan_po.init();
});