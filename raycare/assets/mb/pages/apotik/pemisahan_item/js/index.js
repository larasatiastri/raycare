mb.app.pemisahan_item = mb.app.pemisahan_item || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tableDaftarInventory = $('#table_daftar_item'),
        $popoverInfoContent = $('#popover_info_item'), 
        $lastPopoverSatuan = null,
		$lastPopoverInfo      = null,
        $tableInfoItem = $('#table_info_item');
        $tableHistoryPecah = $('#table_history_pecah');

    var initform = function()
    {

        $('.cari_listing').on('click', function(){

            gudang = $('select#tipe_gudang').val();
            kategori = $('select#kategori').val();
            sub_kategori = $('select#sub_kategori').val();

            $tableDaftarInventory.api().ajax.url(baseAppUrl + 'listing/' + gudang + '/' + kategori + '/' + sub_kategori).load();

        })

        // $('select#tipe_gudang').on('change', function(){
        //     iStatTipe   = this.value;

        //     // alert(this.value);

        //     $tableDaftarInventory.api().ajax.url(baseAppUrl + 'listing/' + iStatTipe).load();
        //     // $tableHistoryPecah.api().ajax.url(baseAppUrl + 'listing_history_pecah/' + iStatTipe).load();
        // });
    }

    var handleDataTable = function() 
    {
    	$tableDaftarInventory.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
            'paginate'              : false,
            'filter'                : false,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/' + $('select#tipe_gudang').val(),
				'type' : 'POST',
			},			
			'pageLength'			: 50,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : false, 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': false, 'orderable': false },
        		]
        });
        $tableDaftarInventory.on('draw.dt', function (){
			$('.btn', this).tooltip();

            var $colSatuan = $('.show-popover', this);

            $.each($colSatuan, function(idx, col){
                var
                    $col = $(col),
                    satuanData     = $col.data('satuan');

                // console.log(satuanData);
                $col.popover({
                    html : true,
                    container : 'body',
                    placement : 'left',
                    content: function(){
                        
                        var html = '';
                        $.each(satuanData, function(idx, satuan){
                            html += '<div class="text-left">' + satuan.jumlah_item + ' ' + satuan.satuan + '</div>'
                        });
                        return html;
                    }
                }).on("show.bs.popover", function(){
                    $(this).data("bs.popover").tip().css({minWidth: '150px', maxWidth: '720px'});
                    if ($lastPopoverSatuan !== null) $lastPopoverSatuan.popover('hide');
                    $lastPopoverSatuan = $col;
                }).on('hide.bs.popover', function(){
                    $lastPopoverSatuan = null;
                }).on('click', function(e){

                });
            });

			$('a[name="info[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id'),
                          id_gudang = $anchor.data('id_gudang');

                    oTableInfoItem.api().ajax.url(baseAppUrl + 'listing_info_item/' + id + '/' +id_gudang).load();                    
			});

		var $btnSearchInfo  = $('.pilih-info');
        handleBtnSearchInfo($btnSearchInfo);

		});

    }

    var handleDataTableHistoryPecah = function() 
    {
    	$tableHistoryPecah.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_history_pecah',
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
        $tableHistoryPecah.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

		});

    }

    var handleBtnSearchInfo = function($btn){
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

            if ($lastPopoverInfo != null) $lastPopoverInfo.popover('hide');

            $lastPopoverInfo = $btn;

            $popoverInfoContent.show();

        }).on('shown.bs.popover', function(){

            var 
                $popContainer = $(this).data('bs.popover').tip(),
                $popcontent   = $popContainer.find('.popover-content')
                ;

            // record rowId di popcontent
            $('input:hidden[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverInfoContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverPasienContent ke .page-content
            $popoverInfoContent.hide();
            $popoverInfoContent.appendTo($('.page-content'));

            $lastPopoverInfo = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
    };

    var handleDataTableInfoItem = function() 
    {
    	oTableInfoItem = $tableInfoItem.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing_info_item',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'visible' : true, 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'searchable': true, 'orderable': true }
        		]
        });

        $('#table_info_item_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
        $('#table_info_item_wrapper .dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        $popoverInfoContent.hide();   
    }

    var handlePilihKategori = function()
    {
        $('#kategori').on('change', function(){
            
            $sub_kategori = $('select#sub_kategori');

            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_sub_kategori',
                data     : {id_kategori: $(this).val()},
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                    
                    $sub_kategori.empty();

                    $sub_kategori.append($("<option></option>")
                        .attr("value", '').text('Pilih Sub Kategori'));

                    $.each(results, function(key, value) {
                        $sub_kategori.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $sub_kategori.val('');

                    });

                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });

        })
    }
	

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'apotik/pemisahan_item/';
        handleDataTable();
        handleDataTableInfoItem();
        handleDataTableHistoryPecah();
        initform();
        handlePilihKategori();
    };
 }(mb.app.pemisahan_item));


// initialize  mb.app.home.table
$(function(){
    mb.app.pemisahan_item.init();
});