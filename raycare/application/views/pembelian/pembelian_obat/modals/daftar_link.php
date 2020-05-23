<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pilih Permintaan Detail", $this->session->userdata("language"))?></span>
	</div>
</div>
<div class="modal-body">
	<div class="portlet">
		<div class="portlet-body">
            <input type="hidden" id="item_id" value="<?=$id?>">
            <input type="hidden" id="row_id" value="<?=$rowId?>">
			<input type="hidden" id="satuan_id" value="<?=$satuanId?>">
			<table class="table table-striped table-bordered table-hover" id="table_pilih_permintaan_terdaftar">
				<thead>
                <tr role="row" class="heading">
                    <th scope="col" ><div class="text-center" style="width:150px;"><?=translate("ID", $this->session->userdata("language"))?></div></th>
                    <th scope="col" ><div class="text-center" style="width:100px !important;"><?=translate("Tanggal", $this->session->userdata("language"))?></div></th>
                    <th scope="col" style="width:150px;"><div class="text-center"><?=translate("User (User Level)", $this->session->userdata("language"))?></div></th>
                    <th scope="col" ><div class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></div></th>
                    <th scope="col" ><div class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></div></th>
                    <th scope="col" ><div class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></div></th>
                    <th scope="col" ><div class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></div></th>
                    <th scope="col" ><div class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?></div></th>
                    <th scope="col" ><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>
                </tr>
            	</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
    <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
    <a class="btn default closePopupModal" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
</div>

<script>
	$( document ).ready(function() {
	    handleDataTablePermintaanTerdaftar();
	});	

		function handleDataTablePermintaanTerdaftar(){
        	var $tablePermintaanTerdaftar    = $('#table_pilih_permintaan_terdaftar');
	        oTablePermintaanTerdaftar = $tablePermintaanTerdaftar.dataTable({
	            'processing'            : true,
	            'serverSide'            : true,
	            'language'              : mb.DTLanguage(),
	            'ajax'                  : {
	                'url' : mb.baseUrl() + 'pembelian/pembelian_obat/listing_permintaan_terdaftar/'+$('input#item_id').val()+'/'+$('input#row_id').val()+'/'+$('input#satuan_id').val(),
	                'type' : 'POST',
	            },          
	            'pageLength'            : 10,
	            'lengthMenu'            : [[10, 25, 50, 100], [10, 25, 50, 100]],
	            'order'                 : [[0, 'asc']],
	            'columns'               : [
	                { 'visible' : false, 'searchable': false, 'orderable': true },
	                { 'visible' : true, 'searchable': false, 'orderable': true },
	                { 'visible' : true, 'searchable': false, 'orderable': true },
	                { 'visible' : true, 'searchable': false, 'orderable': true },
	                { 'visible' : false, 'searchable': false, 'orderable': true },
	                { 'visible' : false, 'searchable': false, 'orderable': true },
	                { 'visible' : false, 'searchable': false, 'orderable': true },
	                { 'visible' : false, 'searchable': false, 'orderable': true },
	                { 'visible' : true, 'searchable': false, 'orderable': true }           
	            ]
	        });

	        
	        $tablePermintaanTerdaftar.on('draw.dt', function (){
	            $('.btn', this).tooltip();

	            var $btnSelect = $('a.select', this);
	            handlePermintaanTerdaftarSelect( $btnSelect );
	            
	        } );    
	    };

    function handlePermintaanTerdaftarSelect($btn){
        $btn.on('click', function(e){
            var 
            //     $parentPop      = $(this).parents('.popover').eq(0),
            //     rowId           = $('input:hidden[name="rowItemId"]', $parentPop).val(),
                $User       = $('label[name="user"]'),
                $Tanggal     = $('label[name="tanggal_popup"]'),
                $SubjekPopup     = $('label[name="subjek_popup"]'),
                $KeteranganPopup     = $('textarea[name="keterangan_popup"]'),
                $KodeItem   = $('label[name="kode_item"]'),
                $NamaItem   = $('label[name="nama_item"]'),
                $JumlahItem   = $('label[name="jml_pesan"]'),
                // $SatuanAlokasiItem   = $('input[name="satuan"]'),
                $TipePermintaan     = $('input[name="tipe_permintaan"]'),
                $TipePembelian     = $('input[name="tipe_pembelian"]'),
                $OrderPembelian     = $('input[name="order_pembelian"]'),
                // $IdDetail     = $('input[name="jumlah_alokasi"]'),
                $itemCodeEl     = null,
                $itemNameEl     = null;               

            // console.log($itemIdEl)
            
            // $itemIdEl.val($(this).data('item').id);            
            // $itemCodeEl.val($(this).data('item').code);
            $User.text($(this).data('item').user +' ('+ $(this).data('item').user_level+')');
            $Tanggal.text($(this).data('item').tanggal);
            $SubjekPopup.text($(this).data('item').subjek);
            $KeteranganPopup.val($(this).data('item').keterangan);
            $KodeItem.text($(this).data('item').kode);
            $NamaItem.text($(this).data('item').nama_item);
            $JumlahItem.text($(this).data('item').jumlah_item + ' ' + $(this).data('item').nama_satuan);
            $TipePermintaan.val($(this).data('tipe')),
            $TipePembelian.val($(this).data('tipe_pembelian')),
            $OrderPembelian.val($(this).data('item').id),
            // $IdDetail.val($(this).data('item').id_detail),
            // $SatuanAlokasiItem.text($('select[name$="[satuan]"]').val());


            // oTableItemSearch.api().ajax.url(baseAppUrl + 'listing_search_item/' + $(this).data('item').id).load();
            // alert($itemIdEl.val($(this).data('item').id));

            e.preventDefault();
        });     
    };
</script>