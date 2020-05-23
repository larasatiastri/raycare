<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Gunakan Item Tersimpan", $this->session->userdata("language"))?></span>
	</h4>
</div>
<div class="modal-body">
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Waktu', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
            <input class="form-control hidden" id="pasien_id" name="pasien_id" value="<?=$pasien_id?>">
            <input class="form-control hidden" id="tindakan_hd_id" name="tindakan_hd_id" value="<?=$tindakan_hd_id?>">
            <input class="form-control hidden" id="simpan_item_id" name="simpan_item_id" value="<?=$simpan_item_id?>">
            <input class="form-control hidden" id="item_id_simpan" name="item_id_simpan" value="<?=$item_id?>">
            <input class="form-control hidden" id="satuan_id_simpan" name="satuan_id_simpan" value="<?=$item_satuan_id?>">
			<input class="form-control" id="waktu_item_tersimpan" name="waktu_item_tersimpan" value="<?=date('H:i')?>">
		</div>
	</div>
	<?php 
		$item = $this->item_m->get($item_id);
		
	?>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Gunakan Item', $this->session->userdata('language'))?> :</label>
		<?php
		if($item->is_identitas == 1)
		{	
		?>
			<div class="col-md-2">
				<div class="input-group">
					<input type="text" class="form-control" readonly id="jumlah_item_tersimpan" name="jumlah_item_tersimpan">
					<span class="input-group-btn">
	                    <a class="btn btn-primary btn-modal-identitas" href="<?=base_url()?>klinik_hd/edit_transaksi/modal_simpan_identitas/<?=$pasien_id?>/<?=$item_id?>/<?=$item_satuan_id?>" data-toggle="modal" data-target="#modal_identitas"><i class="fa fa-info"></i></a>
	                </span>

				</div>
			</div>
			<div class="col-md-2">
				<label class="control-label"><?=str_replace('_',' ',$satuan)?></label>
			</div>
		<?php
		}
		else
		{
			$item_tersimpan = $this->item_tersimpan_m->get_data_by_simpan_item_id($simpan_item_id)->row(0);
		?>
			<div class="col-md-6">
				<div class="input-group">
					<input type="number" class="form-control"  min="1" max="<?=$item_tersimpan->jumlah?>" id="jumlah_item_tersimpan" name="jumlah_item_tersimpan">
					<span class="input-group-addon">
					<i><?=str_replace('_',' ',$satuan)?></i>
	                </span>
				</div>
			</div>
			<input class="form-control hidden" id="stock" name="stock" value="<?=$item_tersimpan->jumlah?>"></input>
		<?php
		}
		?>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Dokter', $this->session->userdata('language'))?> :</label>
		<div class="col-md-4">
			<input class="form-control" id="perawat_item_tersimpan" name="perawat_item_tersimpan" value="<?=$this->session->userdata('nama_lengkap')?>" readonly>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
		<div class="col-md-5">
			<textarea class="form-control" id="keterangan_item_tersimpan" name="keterangan_item_tersimpan" rows="4" ></textarea>
		</div>
	</div>

	<div class="form-group hidden">
		<label class="control-label col-md-4">Dummy Identitas :</label>
		<div class="col-md-8">
			<div id="simpan_identitas_detail"> </div>
		</div>
			
	</div>
</div>
<div class="modal-footer">
	<a class="btn default" id="btn-close" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
	<a class="btn btn-primary simpan_item"><?=translate('OK', $this->session->userdata('language'))?></a>
</div>

<script type="text/javascript">

	$(document).ready(function() 
	{
	   	baseAppUrl = mb.baseUrl() + 'klinik_hd/edit_transaksi/';
        // initForm();
        handleBtnSave();

	});

	function handleBtnSave()
	{
		$('a.simpan_item').click(function()
		{	

			$.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'save_simpan_item',
                data     : $('#form_item_tersimpan').serialize(),
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( result ) 
                {
        			$('a#reload-table-tersimpan').click();
        			$('a#reload-table-digunakan').click();
        			$('a#btn-close').click();
        			
                    mb.showMessage(result[0],result[1],result[2]);
                },
                complete : function(){
                    Metronic.unblockUI();
                }
            });

		})
	}

</script>