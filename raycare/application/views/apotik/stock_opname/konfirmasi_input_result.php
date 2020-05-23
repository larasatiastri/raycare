
<?php
	$form_attr = array(
		"id"			=> "form_konfirmasiInputResult", 
		"name"			=> "form_konfirmasiInputResult", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);
	$hidden = array(
		"command"	=> "konfirmasi"
	);
	$id = array(
		'id'    => 'id',
    	'type'  => 'hidden',
    	'value' => $pk_value
	);
	echo form_open(base_url()."apotik/stock_opname/save", $form_attr,$hidden, $id);
?>	
<div class="portlet light">
    <div class="portlet-body form">
<div class="row">
	<div class="col-md-12">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Konfirmasi', $this->session->userdata('language'))?></span>
				</div>
			</div><input type="hidden" id="pk" name="pk" value="<?=$wareid?>">
			<div class="portlet-body form">
				<div class="form-body">
					<div class="form-group"></div>
					<div class="form-group hidden">
						<label class="control-label col-md-3">ID</label>
						<div class="col-md-1">
							<input class="form-control" id="id" name="id" value="<?=$pk_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">
							<?=translate('Orang', $this->session->userdata('language'))?> :
						</label>
						<div class="col-md-3">
							<label class="control-label"><?=$form_data_people['nama']?></label>
							<input type="hidden" name="warehouse_people_id" value="<?=$form_data['gudang_orang_id']?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">
							<?=translate('Gudang', $this->session->userdata('language'))?> :
						</label>
						<div class="col-md-3">
							<label class="control-label"><?=$form_data_warehouse['nama']?></label>
							<input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$form_data_warehouse['id']?>">
						</div>
					</div>
					
					<div class="form-group"></div>

					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Item Stok Opname', $this->session->userdata('language'))?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-hover table-bordered table-striped table-condensed" id="table_konfirmasi">
								<thead>
									<tr class="heading">
										<th class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></th>
										<th class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></th>
										<th class="text-center"><?=translate('Satuan', $this->session->userdata('language'))?></th>
										<th class="text-center"><?=translate('Jumlah Sistem', $this->session->userdata('language'))?></th>
										<th class="text-center"><?=translate('Jumlah Hitung', $this->session->userdata('language'))?></th>
										<th class="text-center" width="15%"><?=translate('Aksi', $this->session->userdata('language'))?></th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
				</div>
				<?php 
	            	$confirm_save       = translate('Apakah anda yakin untuk meng-insert jumlah stok opname ini?',$this->session->userdata('language'));
	            ?>
				<div class="form-actions fluid">
					<div class="col-md-12 text-right">
						<a class="btn default" href="javascript:history.go(-1);"><?=translate('Back', $this->session->userdata('language'))?></a>
						<a id="confirm_save" class="btn green-haze" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=translate('Confirm', $this->session->userdata('language'))?></a>
				        <button type="submit" id="save" class="btn default hidden" >Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>