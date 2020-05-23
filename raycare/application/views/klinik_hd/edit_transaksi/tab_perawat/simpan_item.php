<?php
	$form_attr = array(
		"id"			=> "form_simpan_item", 
		"name"			=> "form_simpan_item", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
	);
	echo form_open(base_url()."#", $form_attr,$hidden);

?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Paket", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Nomor Transaksi :", $this->session->userdata("language"))?></label>
				<div class="col-md-6">
					<label class="control-label" id="nomor_transakski"></label>
						  
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Tanggal Transaksi :", $this->session->userdata("language"))?></label>
				<div class="col-md-6">
					<label class="control-label" id="tanggal_transakski"></label>
				</div>
			</div>

			<!-- <div class="form-group">
				<label class="control-label col-md-3"><?=translate("Nomor Pasien :", $this->session->userdata("language"))?></label>
				<div class="col-md-6">
					<label class="control-label" id="no_pasien"></label>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Nama Pasien :", $this->session->userdata("language"))?></label>
				<div class="col-md-6">
					<label class="control-label" id="nama_pasien"></label>
				</div>
			</div> -->

			
		</div>
	</div>

	<!-- Items Inside Package -->
	<div class="portlet-title" style="margin-top:20px;">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Items Inside Package", $this->session->userdata("language"))?></span>
		</div>
		
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<table class="table table-striped table-hover table-bordered" id="table_didalam_paket">
				<thead>
					<tr class="heading">
						<th class="text-center" width="5%"><?=translate('No', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Nama Paket', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Jatah', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Digunakan', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Sisa', $this->session->userdata('language'))?></th>
						<th class="text-center" width="10%"><?=translate('Save', $this->session->userdata('language'))?></th>
					</tr>
				</thead>
				<tbody>
					<tr class="text-center">
						<td>1</td>
						<td>Paket Re-Use - Cimino</td>
						<td>Bloodline</td>
						<td>1 pcs</td>
						<td>0 pcs</td>
						<td>1 pcs</td>
						<td>
							<input class="form-control" value="0">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>


	<!-- Items Outside Package -->
	<div class="portlet-title" style="margin-top:20px;">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Items Outside Package", $this->session->userdata("language"))?></span>
		</div>
		
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<table class="table table-striped table-hover table-bordered" id="table_diluar_paket">
				<thead>
					<tr class="heading">
						<th class="text-center" width="5%"><?=translate('No', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Digunakan', $this->session->userdata('language'))?></th>
						<th class="text-center" width="10%"><?=translate('Save', $this->session->userdata('language'))?></th>
					</tr>
				</thead>
				<tbody>
					<tr class="text-center">
						<td>1</td>
						<td>Neurobion Injeksi</td>
						<td>1 ampoul</td>
						<td>
							<input class="form-control" value="0">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>


	<!-- Use of saved items -->
	<div class="portlet-title" style="margin-top:20px;">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Use of saved items", $this->session->userdata("language"))?></span>
		</div>
		
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<table class="table table-striped table-hover table-bordered" id="table_simpan_item">
				<thead>
					<tr class="heading">
						<th class="text-center" width="5%"><?=translate('No', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
						<th class="text-center"><?=translate('Digunakan', $this->session->userdata('language'))?></th>
						<th class="text-center" width="10%"><?=translate('Save', $this->session->userdata('language'))?></th>
					</tr>
				</thead>
				<tbody>
					<tr class="text-center">
						<td>1</td>
						<td>Neurobion Injeksi</td>
						<td>
							<input type="checkbox">
						</td>
						<td>
							<input type="checkbox">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="form-actions">
			<?php $msg = translate("Apakah anda yakin akan membuat kategori ini?",$this->session->userdata("language"));?>
			<div class="col-md-offset-3 col-md-9">
    			<!-- <a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a> -->
				<!-- <a class="btn btn-primary" data-confirm="<?=$msg?>" id="confirm_save" data-toggle="modal"><?=translate('Selesaikan Tindakan', $this->session->userdata('language'))?></a> -->
                <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			</div>
		</div>
	</div>
	<?=form_close()?>
		<!-- END FORM-->
</div>