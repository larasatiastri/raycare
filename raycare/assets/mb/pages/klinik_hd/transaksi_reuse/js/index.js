mb.app.transaksi_reuse = mb.app.transaksi_reuse || {};
(function(o){

    var 
        baseAppUrl              = '',
        $tablePasien = $('#table_dialyzer');

    var handleDataTable = function() 
    {
    	oTable = $tablePasien.dataTable({
           	'processing'            : true,
			'serverSide'            : true,
			'stateSave'				: true,
			'pagingType'			: 'full_numbers',
			'language'              : mb.DTLanguage(),
			'ajax'              	: {
				'url' : baseAppUrl + 'listing',
				'type' : 'POST',
			},			
			'pageLength'			: 10,
			'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
			'order'                	: [[1, 'desc']],
			'columns'               : [
				{ 'visible' : false, 'name' : 'simpan_item.id id', 'searchable': false, 'orderable': true },
				{ 'visible' : true, 'name' : 'pasien.nama nama_pasien', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'item.nama nama_item', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'simpan_item.index idx', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'simpan_item.bn_sn_lot bn_sn_lot','searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'simpan_item.volume volume', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'simpan_item.status_reuse status_reuse', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'simpan_item.created_date created_date', 'searchable': true, 'orderable': true },
				{ 'visible' : true, 'name' : 'simpan_item.id id', 'searchable': false, 'orderable': false },
        		]
        });
        $tablePasien.on('draw.dt', function (){
			$('.btn', this).tooltip();
			// action for delete locker

			$('a.edit', this).click(function(){
				var $anchor = $(this),
				    index    = $anchor.data('index');

				$('div#action_edit_'+index).addClass('hidden');
				$('div#action_save_cancel_'+index).removeClass('hidden');

				$('div#status_tampil_'+index).addClass('hidden');
				$('div#select_status_'+index).removeClass('hidden');

				$('div#index_tampil_'+index).addClass('hidden');
				$('div#index_ubah_'+index).removeClass('hidden');

				$('div#volume_tampil_'+index).addClass('hidden');
				$('div#volume_ubah_'+index).removeClass('hidden');
			});

			$('a.batal', this).click(function(){
				var $anchor = $(this),
				    index    = $anchor.data('index');

				$('div#action_edit_'+index).removeClass('hidden');
				$('div#action_save_cancel_'+index).addClass('hidden');

				$('div#status_tampil_'+index).removeClass('hidden');
				$('div#select_status_'+index).addClass('hidden');

				$('div#index_tampil_'+index).removeClass('hidden');
				$('div#index_ubah_'+index).addClass('hidden');

				$('div#volume_tampil_'+index).removeClass('hidden');
				$('div#volume_ubah_'+index).addClass('hidden');
			});

			$('a.simpan', this).click(function(){
				var $anchor = $(this),
				    index   = $anchor.data('index'),
				    msg		= $anchor.data('msg');



				var i = 0;

				var id = $('input#simpan_item_id_'+index).val(),
				 	idx = $('input#index_'+index).val(),
				 	volume = $('input#volume_'+index).val(),
				 	status = $('select#status_'+index).val();

				bootbox.confirm(msg, function(result) {
                if (result==true) {
                    i = parseInt(i) + 1;
                    if(i == 1)
                    {
                        $.ajax({
                        type     : 'POST',
                        url      : baseAppUrl + 'save',
                        data     : {id:id, idx:idx, volume:volume, status:status},
                        dataType : 'json',
                        success  : function( results ) {
                            
                            if(results.success == true){
                                mb.showToast('success',results.msg,'Berhasil');
                                oTable.api().ajax.url(baseAppUrl + 'listing').load();

                            }
                            if(results.success == false){
                                mb.showToast('error',results.msg,'Gagal');
                                oTable.api().ajax.url(baseAppUrl + 'listing').load();
                            }

                            
                        }
                    });     
                    }
                }
            });
				
			});

						
		});
    }

	

    // mb.app.home.table properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_reuse/';
        handleDataTable();
    };
 }(mb.app.transaksi_reuse));


// initialize  mb.app.home.table
$(function(){
    mb.app.transaksi_reuse.init();
});