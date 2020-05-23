mb.app.stok_opname_online = mb.app.stok_opname_online || {};
(function(o){

    var 
        baseAppUrl          = '',
        $tableStokOpname    = $('#table_stok_opname'),
        $tableItemSearch    = $('#table_item_search'),
        $popoverItemContent = $('#popover_item_content'),
        $lastPopoverItem    = null,
        $btnSet             = $('a#set')
        $btnUnSet           = $('a#simpan')
        ;

    var initform = function()
    {
    	$('select#tipe_sub_kategori').on('change', function(){
    		iStatTipe = $(this).val();

            oTableItemSearch.api().ajax.url(baseAppUrl + 'listing_search_item/' + iStatTipe).load();
    	})

    	$('select#tipe_gudang').on('change', function(){
    		iStatTipe = $(this).val();

    		
    	})

    	$btnSearchItem    = $('a#pilih-item');
    	handleBtnSearchItem($btnSearchItem);
    	handleBtnSet();
        handleBtnUnSet();
    }

    var handleDataTable = function() 
    {
    	oTableStokOpname = $tableStokOpname.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
            'stateSave'             : true,
            'pagingType'            : 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing/' + $('input#set_gudang').val() +'/'+ $('input#set_kategori').val() +'/'+ $('input#set_sub_kategori').val() +'/'+ $('input#id_item').val(),
				'type' : 'POST',
			},			
			'pageLength'			: 50,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'asc']],
			'columns'               : [
				{ 'name' : 'item.id id', 'visible' : false, 'searchable': false, 'orderable': false },
				{ 'name' : 'item.kode kode', 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'name' : 'item.nama nama', 'visible' : true, 'searchable': true, 'orderable': false },
				{ 'visible' : true, 'searchable': true, 'orderable': false },
                { 'name' : 'inventory.jumlah jumlah', 'visible' : true, 'searchable': true, 'orderable': false },
                { 'visible' : false, 'searchable': false, 'orderable': false },
        		]
        });
        $tableStokOpname.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

			$('a[name="delete[]"]', this).click(function(){
					var $anchor = $(this),
					      id    = $anchor.data('id');
					      msg    = $anchor.data('confirm');

					handleDeleteRow(id,msg);
			});

            $('input[name="jumlah[]"]', this).on('change', function(){
                    // alert($(this).val());
                    var  row = $(this).data('row');
                    $('a#save_stok_'+ row).attr('data-jumlah', $(this).val());
            })

            $('input[name="jumlah[]"]', this).on('keyup', function(){
                    // alert($(this).val());
                    var  row = $(this).data('row');
                    $('a#save_stok_'+ row).attr('data-jumlah', $(this).val());

            })

			$('a.input-stok', this).click(function(){
				 var $anchor = $(this),
                    id    = $anchor.data('row');

				handleEditRow(id);
			});

			$('.input-stok-back', this).click(function(){
				var $anchor = $(this),
                    id    = $anchor.data('row');

				handleBackRow(id);
			});

			$('.input-stok-save', this).click(function(){
				var $anchor = $(this),
                    id    = $anchor.data('row');
                    gudang_id = $anchor.data('item').gudang_id;
                    item_id = $anchor.data('item').item_id;
                    item_satuan_id = $anchor.data('item').item_satuan_id;
                    item_satuan_nama = $anchor.data('item').satuan;
                    jumlah_awal = $('label#jumlahEl_'+id).text();
                    data_jumlah = $('input#jumlah_'+ id).val();

                    // alert(data_jumlah);
				    handleSaveRow(id, gudang_id, item_id, item_satuan_id, data_jumlah, jumlah_awal);
                    $('label#jumlahEl_'+id).text($('input#jumlah_'+id).val()+' '+item_satuan_nama)
			});				
		});


    }

    var handleBtnSet = function()
    {
    	$btnSet.on('click', function(e){
            e.preventDefault();

    		var gudang_id = $('select#tipe_gudang').val(),
                kategori_id     = $('select#tipe_kategori').val(),
                sub_kategori_id = $('select#tipe_sub_kategori').val(),
                item_id         = $('input#id_item').val();
                table           = $('#table_stok_opname').dataTable();
                // Toggle the visibility
                var bVis = table.fnSettings().aoColumns[5].bVisible;
                table.fnSetColumnVis( 5, bVis ? false : true );

            $btnSet.addClass('hidden');
            $('a#simpan').removeClass('hidden');
            $('#tipe_gudang').attr('disabled', true);
            $('#tipe_kategori').attr('disabled', true);
            $('#tipe_sub_kategori').attr('disabled', true);
            $('input#set_gudang').val($('select#tipe_gudang').val());
            $('input#set_kategori').val($('select#tipe_kategori').val());
            $('input#set_sub_kategori').val($('select#tipe_sub_kategori').val());

        	$.ajax({
	            type: "POST",
	            url: baseAppUrl + "save_so_set",
	            data: {gudang_id: gudang_id, kategori_id: kategori_id, sub_kategori_id: sub_kategori_id, item_id: item_id},
                dataType : 'json',
                beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
	            success:  function(data){
	                // alert("---"+data);
	                // bootbox.alert(msg);
	                // window.location.reload(true);
	                // oTable.fnDraw();
                    $('input#so_id').attr('value',data.so_online_id);
    				oTableStokOpname.api().ajax.url(baseAppUrl + 'listing/' + $('select#tipe_gudang').val() +'/'+ $('select#tipe_kategori').val() +'/'+ $('select#tipe_sub_kategori').val() +'/'+ $('input#id_item').val()).load();
	               $('a#simpan').attr('href', baseAppUrl + 'modal_keterangan/'+data.so_online_id);
                },
                complete : function(){
                        Metronic.unblockUI();
                    }
	        });
    	})
    }

    var handleBtnUnSet = function()
    {
        $btnUnSet.on('click', function(e){
            e.preventDefault();

            var gudang_id = $('input#set_gudang').val(),
                kategori_id = $('input#set_kategori').val(),
                sub_kategori_id = $('input#set_sub_kategori').val(),
                item_id = $('input#id_item').val();
                table = $('#table_stok_opname').dataTable();
                // Toggle the visibility
                var bVis = table.fnSettings().aoColumns[5].bVisible;
                table.fnSetColumnVis( 5, bVis ? false : true );

            //$('a#simpan').addClass('hidden');
            //$('a#set').removeClass('hidden');
            $('#tipe_gudang').attr('disabled', false);
            $('#tipe_kategori').attr('disabled', false);
            $('#tipe_sub_kategori').attr('disabled', false);


            $formSOOnline = $('#form_index_so_online');

            // $.ajax({
            //     type: "POST",
            //     url: baseAppUrl + "delete_so_set",
            //     data: {gudang_id: gudang_id, kategori_id: kategori_id, sub_kategori_id: sub_kategori_id, item_id: item_id},
            //     dataType : 'json',
            //     beforeSend : function(){
            //             Metronic.blockUI({boxed: true });
            //         },
            //     success:  function(data){
                    
            //         oTableStokOpname.api().ajax.url(baseAppUrl + 'listing/').load();
            //     },
            //     complete : function(){
            //             Metronic.unblockUI();
            //         }
            // });

        })
    }

    var handleEditRow = function(id){
        // alert(id);
        $('#input_jumlah_'+id).removeClass('hidden');
        $('#jumlahEl_'+id).addClass('hidden');

        $('a#save_stok_'+id).removeClass('hidden');
        $('a#back_stok_'+id).removeClass('hidden');

        $('a#input_stok_'+id).addClass('hidden');
        // $('a#cancel').removeClass('hidden');
       
    }

    var handleBackRow = function(id){
        // alert(id);
        $('#input_jumlah_'+id).addClass('hidden');
        $('#jumlahEl_'+id).removeClass('hidden');

        $('a#save_stok_'+id).addClass('hidden');
        $('a#back_stok_'+id).addClass('hidden');

        $('a#input_stok_'+id).removeClass('hidden');
        // $('a#cancel').removeClass('hidden');
       
    }


    var handleSaveRow = function(id, gudang_id, item_id, item_satuan_id, data_jumlah, jumlah_awal){
        // alert(id);
        $('#input_jumlah_'+id).addClass('hidden');
        $('#jumlahEl_'+id).removeClass('hidden');

        $('a#save_stok_'+id).addClass('hidden');
        $('a#back_stok_'+id).addClass('hidden');

        $('a#input_stok_'+id).removeClass('hidden');
        // $('a#cancel').removeClass('hidden');
        var so_id = $('input#so_id').val();
       
       $.ajax({
            type: "POST",
            url: baseAppUrl + "data_inventory",
            data: {gudang_id: gudang_id, item_id: item_id, item_satuan_id: item_satuan_id, jumlah: data_jumlah, jumlah_awal: jumlah_awal, so_id: so_id},
            dataType : 'json',
            beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
            success:  function(data){
        
            },
            complete : function(){
                    Metronic.unblockUI();
                }
        });
    }

    var handleBtnSearchItem = function($btn){
        var rowId  = $btn.closest('tr').prop('id');
        // var rowStatus  = $btn.closest('tr').prop('class');
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
            $('input[name="rowItemId"]', $popcontent).val(rowId);
            
            // pindahkan $popoverItemContent ke .popover-conter
            $popContainer.find('.popover-content').append($popoverItemContent);

        }).on('hide.bs.popover', function(){
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();

        });
    };

    var handleDataTableItems = function(){
        oTableItemSearch = $tableItemSearch.dataTable({
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_search_item/',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[0, 'asc']],
            'columns'               : [
                { 'visible' : false, 'searchable': false, 'orderable': false },
                { 'name' : 'item.kode kode', 'visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': true, 'orderable': true },
                { 'visible' : true, 'searchable': false, 'orderable': true },
                ]
        });
        var $btnSelects = $('a.select', $tableItemSearch);
        handleItemSelect( $btnSelects );

        $tableItemSearch.on('draw.dt', function (){
            $('.btn', this).tooltip();

            var $btnSelect = $('a.select', this);
            handleItemSelect( $btnSelect );
            
        } );

        $popoverItemContent.hide();        
    };

    var handleItemSelect = function($btn){
        $btn.on('click', function(e){
            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $IdItem       = $('input[name="id_item"]'),
                $NamaItem     = $('input[name="nama_item"]'),
                $itemCodeEl     = null,
                $itemNameEl     = null;        


            $('#pilih-item').popover('hide');  

            $IdItem.val($(this).data('item').id);
            $NamaItem.val($(this).data('item').nama);          
            // alert($itemIdEl.val($(this).data('item').id));

            e.preventDefault();
        });     
    };

    var handleDeleteRow = function(id,msg){

		bootbox.confirm(msg, function(result) {
			if(result==true) {
				location.href = baseAppUrl + 'delete/' +id;
			} 
		});
	
	};

	var handleSelectSubKategori = function(){
        $('select#tipe_kategori').on('change', function(){

            //$('input#warehouse_id').val($(this).val());
            // $btnSet.attr('data-kategori', $(this).val());
            var $subKategori_select = $('select#tipe_sub_kategori');
            
            $.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'get_sub_kategori',
                data     : {id_kategori: $(this).val()},
                dataType : 'json',
                beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                success  : function( results ) {
                    // $kelas_select.val('Pilih Kelas');
                    $subKategori_select.empty();

                    $.each(results, function(key, value) {
                        $subKategori_select.append($("<option></option>")
                            .attr("value", value.id).text(value.nama));
                        $subKategori_select.val('');
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
        baseAppUrl = mb.baseUrl() + 'apotik/stok_opname_online/';
        handleDataTable();
        handleDataTableItems();
        handleSelectSubKategori();
        initform();
    };
 }(mb.app.stok_opname_online));


// initialize  mb.app.home.table
$(function(){
    mb.app.stok_opname_online.init();
});