<form class="form-horizontal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">
			<span class="caption-subject font-blue-sharp bold uppercase"><?= $item['nama'] .' ('. $satuan['nama'].')'?></span>
		</h4>
	</div>
	<div class="modal-body">
	<?php
		if($data_history['transaksi_tipe'] == 4){
			$pmb = $this->pmb_m->get_by(array('id' => $data_history['transaksi_id']), true);
			$pmb_po = $this->pmb_po_detail_m->get_by(array('pmb_id' => $data_history['transaksi_id']), true);
			$supplier = $this->supplier_m->get_by(array('id' => $pmb->supplier_id), true);
			$po = $this->pembelian_m->get_by(array('id' => $pmb_po->po_id), true);

	?>
		<div class="form-group">
			<label class="col-md-2 control-label"><?=translate("SUMBER", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
				<label class="control-label">PMB</label>
			</div>
			
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label"><?=translate("No. PMB", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
				<label class="control-label"><a href="http://simrhs.com/raycaretrial/gudang/barang_datang_farmasi/view/PMB-01-2018-0007/110/WH-05-2016-002/pmb" target="_blank"><?=$pmb->no_pmb?></a></label>
			</div>
			
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
				<label class="control-label"><?=$supplier->nama?></label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label"><?=translate("No. PO", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
				<label class="control-label"><?=$po->no_pembelian?></label>
			</div>
		</div>

	<?php
		}if($data_history['transaksi_tipe'] == 10 || $data_history['transaksi_tipe'] == 11){
			$transfer_item = $this->transfer_item_m->get_by(array('id' => $data_history['transaksi_id']), true);
	?>
		<div class="form-group">
			<label class="col-md-2 control-label"><?=translate("SUMBER", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
				<label class="control-label">Transfer Item</label>
			</div>
			
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label"><?=translate("No. Transfer Item", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
				<label class="control-label"><?=$transfer_item->no_transfer?></label>
			</div>
			
		</div>
	<?php
		}if($data_history['transaksi_tipe'] == 3){
	?>
		<div class="form-group">
			<label class="col-md-2 control-label"><?=translate("SUMBER", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
				<label class="control-label">Penjualan Obat</label>
			</div>
			
		</div>
	<?php
		}if($data_history['transaksi_tipe'] == 14){
	?>
		<div class="form-group">
			<label class="col-md-2 control-label"><?=translate("SUMBER", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
				<label class="control-label">Resep Dokter</label>
			</div>
			
		</div>
	<?php
		}if($data_history['transaksi_tipe'] == 15){
	?>
		<div class="form-group">
			<label class="col-md-2 control-label"><?=translate("SUMBER", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
				<label class="control-label">Box Paket Tindakan</label>
			</div>
			
		</div>
	<?php
		}if($data_history['transaksi_tipe'] == 16){
	?>
		<div class="form-group">
			<label class="col-md-2 control-label"><?=translate("SUMBER", $this->session->userdata("language"))?> :</label>
			<div class="col-md-3">
				<label class="control-label">Pengeluaran Dialyzer Baru</label>
			</div>
			
		</div>
	<?php
		}
	?>
	
		
	</div>
	<div class="modal-footer">
		<a class="btn default" data-dismiss="modal"><?=translate('Kembali', $this->session->userdata('language'))?></a>
	</div>
</form>
<script type="text/javascript">

	$(document).ready(function() 
	{
	   	baseAppUrl = mb.baseUrl() + 'laporan/kartu_stok_item/';

	});


</script>