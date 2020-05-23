<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Item Diluar Paket", $this->session->userdata("language"))?></span>
	</h4>
</div>
<div class="modal-body">
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Waktu', $this->session->userdata('language'))?> :</label>
		<div class="col-md-4">
            <input class="form-control hidden" id="tindakan_hd_id" name="tindakan_hd_id" value="<?=$tindakan_hd_id?>">
			<input class="form-control" id="waktu_diluar_paket" name="waktu_diluar_paket" value="<?=date('H:i')?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Item', $this->session->userdata('language'))?> :</label>
		<div class="col-md-4">
			<div class="input-group">
				<input class="form-control" id="item" name="item">
				<span class="input-group-btn">
					<a class="btn btn-primary search-item"><i class="fa fa-search"></i></a>
				</span>
			</div>
		</div>
	</div>
    <div class="form-group">
        <label class="control-label col-md-4"><?=translate('Nama Item', $this->session->userdata('language'))?> :</label>
        <div class="col-md-4">
            <input class="form-control hidden" id="inventory_id" name="inventory_id">
            <input class="form-control hidden" id="stock" name="stock">
            <input class="form-control hidden" id="harga_beli" name="harga_beli">
            <input class="form-control hidden" id="item_id" name="item_id">
            <input class="form-control hidden" id="gudang_id" name="gudang_id">
            <input class="form-control hidden" id="pmb_id" name="pmb_id">
            <input class="form-control" id="nama_item" name="nama_item">
        </div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Jumlah', $this->session->userdata('language'))?> :</label>
		<div class="col-md-2" id="input_jumlah">
			<div class="input-group">
				<input type="text" class="form-control" readonly id="jumlah_inventory" name="jumlah_inventory">
				<span class="input-group-btn">
                    <a class="btn btn-primary btn-modal-identitas" href="<?=base_url()?>klinik_hd/transaksi_dokter/modal_inventori_identitas" data-toggle="modal" data-target="#modal_identitas"><i class="fa fa-info"></i></a>
                </span>
			</div>
		</div>
        <div class="col-md-3">
            <?php 
                $satuan_option = array(
                    '' => 'Pilih Satuan'
                );
                echo form_dropdown("satuan", $satuan_option, "", " id='satuan' class='form-control' ");
             ?>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Dokter', $this->session->userdata('language'))?> :</label>
		<div class="col-md-4">
			<input class="form-control" id="perawat_diluar_paket" name="perawat_diluar_paket" value="<?=$this->session->userdata('nama_lengkap')?>" readonly>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
		<div class="col-md-6">
			<!-- <input class="form-control input-small hidden" id="id_bed" name="id_bed"> -->
			<textarea class="form-control" id="keterangan_diluar_paket" name="keterangan_diluar_paket" rows="5" ></textarea>
		</div>
	</div>

    <div class="form-group hidden">
        <label class="control-label col-md-4">Dummy Identitas :</label>
        <div class="col-md-8">
            <div id="inventory_identitas_detail"> </div>
        </div>
            
    </div>
	
</div>
<div class="modal-footer">
	<a class="btn default modal_batal" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
	<a class="btn btn-primary modal_ok" data-dismiss="modal"><?=translate('OK', $this->session->userdata('language'))?></a>
</div>



<!-- POPOVER ITEM DILUAR PAKET (TAB TAGIHAN PAKET) -->
<div id="popover_item_content" class="row">
    <div class="col-md-12">
    	<div class="portlet light">
    		
    		<div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group hidden">
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                    $get_gudang = $this->gudang_m->get_by(array('is_active' => 1));

                                    $gudang = object_to_array($get_gudang);

                                    $gudang_option = array(
                                        '0' => translate('Semua Gudang', $this->session->userdata('language'))
                                    );
                                    foreach ($gudang as $data) {
                                        $gudang_option[$data['id']] = $data['nama'];
                                    }

                                    echo form_dropdown('gudang', $gudang_option, "", "id=\"gudang\" class=\"form-control\""); 
                                ?>  
                            </div>
                            <div class="col-md-6">
                                <?php 
                                    $kategori_option = array(
                                        '0' => 'Semua Kategori'
                                    );
                                    $kategori = $this->item_kategori_m->get_by(array('is_active' => 1));
                                    foreach ($kategori as $row) 
                                    {
                                        $kategori_option[$row->id] = $row->nama;
                                    }
                                    echo form_dropdown("kategori", $kategori_option, "", " id='kategori' class='form-control' ");
                                ?>  
                            </div>
                        </div>                          

                    </div>
                    <table class="table table-striped table-bordered table-hover" id="table_item_search">
                        <thead>
                            <tr role="row" class="heading">
                                <th><div class="text-center"><?=translate('Kode Item', $this->session->userdata('language'))?></div></th>
                                <th><div class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></div></th>
                                <th><div class="text-center"><?=translate('Tipe Item', $this->session->userdata('language'))?></div></th>
                                <th><div class="text-center"><?=translate('Gudang', $this->session->userdata('language'))?></div></th>
                                <th><div class="text-center"><?=translate('Keterangan', $this->session->userdata('language'))?></div></th>
                                <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
    		</div>
    	</div>
    </div>
</div> 

<script type="text/javascript">
	
    $(document).ready(function() 
    {

        baseAppUrl          = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
        $popoverItemContent = $('#popover_item_content');
        $lastPopoverItem    = null,

        initForm();
        handleItemSearch();
        handleSelectItem();
        handleBtnSave();
        handleSatuanChange();

    });


    function handleSatuanChange()
    {
        // var selected = $('select#satuan').val();
        $('select#satuan').on('change', function()
        {   
            handleReset();
        })

    }

    function handleReset()
    {
        if ($('input#jumlah_inventory').val() != '') 
        {
            bootbox.confirm("Apakah anda akan mereset item yang sudah diisi sebelumnya?", function(result) {
                if (result==true) 
                {
                    $('input#jumlah_inventory').val('');
                    $('#inventory_identitas_detail').html('');
                }
            });
        };
    }

    function handleBtnSave()
    {
        $('a.modal_ok').click(function()
        {

            $.ajax ({ 
                type: "POST",
                url: baseAppUrl + "save_item_diluar_paket",  
                data:  $('#form_item_diluar').serialize(),
                dataType : "json",
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(result)         
                { 
                    // mb.showMessage(result[0],result[1],result[2]);
                    $('a.reload-table2').click();
                },
                complete : function() {
                    Metronic.unblockUI();
                }
            }); 
        })
    }

	function initForm(){

		var $btnSearchItem = $('.search-item');
        handleBtnSearchItem($btnSearchItem);  

        $('.btn-modal-identitas').attr("disabled", true);
        
        $popoverItemContent.hide();

        $('.modal_batal').on('click', function(){
            $('.search-item').popover('hide');          
        });

        $('.modal_ok').on('click', function(){
            $('.search-item').popover('hide');          
        });
	}

	function handleItemSearch(){
  
        oTableItemSearch = $("#table_item_search").dataTable(
        {
            'processing'            : true,
            'serverSide'            : true,
            'language'              : mb.DTLanguage(),
            'ajax'                  : {
                'url' : baseAppUrl + 'listing_item_diluar_paket/0/0',
                'type' : 'POST',
            },          
            'pageLength'            : 10,
            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
            'order'                 : [[1, 'asc']],
            'columns'               : [
                
                { 'name' : 'item.kode kode','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': true, 'orderable': true },
                { 'name' : 'item.id id','visible' : true, 'searchable': false, 'orderable': true },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': false, 'orderable': true },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': false, 'orderable': true },
                { 'name' : 'item.nama nama','visible' : true, 'searchable': false, 'orderable': false },
            ],
        });

        $("#table_item_search").on('draw.dt', function ()
        {
            var $btnSelects = $('a.select', this);
            handleItemSearchSelect( $btnSelects );
            
        });
    };

    var handleItemSearchSelect = function($btn)
    {
        $btn.on('click', function(e){

            var 
                $parentPop      = $(this).parents('.popover').eq(0),
                rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $inventoryId    = $('input[name="inventory_id"]'),
                $stock    = $('input[name="stock"]'),
                $hargaBeli    = $('input[name="harga_beli"]'),
                $itemId         = $('input[name="item_id"]'),
                $kodeItem       = $('input[name="item"]'),
                $namaItem       = $('input[name="nama_item"]'),
                $itemSatuanEl   = $('select[name="satuan"]')
            ;        


            $inventoryId.val($(this).data('item').inventory_id);
            $itemId.val($(this).data('item').item_id);
            $kodeItem.val($(this).data('item').kode);
            $namaItem.val($(this).data('item').nama);
            $stock.val($(this).data('item').jumlah);
            $hargaBeli.val($(this).data('item').harga_beli);

            var satuan = $(this).data('satuan');
            var identitas = $(this).data('item').is_identitas;
            if(identitas == 1)
            {
                $('div#input_jumlah').html('<div class="input-group"> <input type="text" class="form-control" readonly id="jumlah_inventory" name="jumlah_inventory"> <span class="input-group-btn"> <a class="btn btn-primary btn-modal-identitas" href="" data-toggle="modal" data-target="#modal_identitas"><i class="fa fa-info"></i></a> </span> </div>');
                $('.btn-modal-identitas').attr("disabled", false);


                $itemSatuanEl.empty();
                $.each(satuan, function(key, value) {
                    $itemSatuanEl.append($("<option></option>").attr("value", value.id).text(value.nama));
                    $('a.btn-modal-identitas').attr("href", baseAppUrl + 'modal_inventori_identitas/'+value.gudang_id+'/'+value.item_id+'/'+$('select#satuan').val());


                    $('select#satuan').on('change', function()
                    {
                        $('a.btn-modal-identitas').attr("href", baseAppUrl + 'modal_inventori_identitas/'+value.gudang_id+'/'+value.item_id+'/'+ $(this).val());

                    });
                });
            }
            else
            {
                $('div#input_jumlah').html('<input type="number" min="1" max="'+$(this).data('item').jumlah+'" class="form-control"  id="jumlah_inventory" name="jumlah_inventory">');
                $itemSatuanEl.empty();
                $.each(satuan, function(key, value) {
                    $itemSatuanEl.append($("<option></option>").attr("value", value.id).text(value.nama));
                    $('input[name="gudang_id"]').val(value.gudang_id);
                    $('input[name="pmb_id"]').val(value.pmb_id);
                });
            }


            handleReset();

            $('.search-item').popover('hide');          
           
            e.preventDefault();
        });     
    };

    function handleSelectItem()
    {
        $('select#satuan').on('change', function()
        {
            $('a.btn-modal-identitas').attr("data-satuan", $(this).val());

        })

        $('select#gudang').on('change', function()  
        {
            var kat_id = $('#kategori').val();
            oTableItemSearch.api().ajax.url(baseAppUrl + 'listing_item_diluar_paket/' + $(this).val() + '/' + kat_id).load() ;
            var $btnSelects = $('a.select', $("#table_item_search"));
            handleItemSearchSelect( $btnSelects );
        });   

        $('select#kategori').on('change', function()
        {
            var gudang_id = $('#gudang').val();   
            oTableItemSearch.api().ajax.url(baseAppUrl + 'listing_item_diluar_paket/' + gudang_id + '/' + $(this).val()).load();
            var $btnSelects = $('a.select', $("#table_item_search"));
            handleItemSearchSelect( $btnSelects );
            
        }); 

    }

	function handleBtnSearchItem($btn){

		var rowId  = $btn.closest('tr').prop('id');
        // console.log(rowId);

        $btn.popover({ 
            html : true,
            container : '.page-content',
            placement : 'bottom',
            content: '<input type="hidden" name="rowItemId"/>'

        }).on("show.bs.popover", function(){

            var $popContainer = $(this).data('bs.popover').tip();

            $popContainer.css({minWidth: '720px', maxWidth: '720px', zIndex: '99999'});

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
            //pindahkan kembali $popoverItemContent ke .page-content
            $popoverItemContent.hide();
            $popoverItemContent.appendTo($('.page-content'));

            $lastPopoverItem = null;

        }).on('hidden.bs.popover', function(){
            // console.log('hidden.bs.popover')
        }).on('click', function(e){
            e.preventDefault();
        });
	}

</script>