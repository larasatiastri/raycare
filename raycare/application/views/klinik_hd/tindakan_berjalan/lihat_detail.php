<?php
	$form_attr = array(
	    "id"            => "form_add_item", 
	    "name"          => "form_add_item", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."master/item/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Detail Tindakan", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-default" href="<?=base_url()?>klinik_hd/tindakan_berjalan">
			<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3">	
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Informasi", $this->session->userdata("language"))?>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<div class="alert alert-danger display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_danger?>
					    </div>
					    <div class="alert alert-success display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_success?>
					    </div>
					    <input type="hidden" id="tindakanid" value="<?=$form_data_kiri[0]['tindakan_id']?>">
					    <input type="hidden" id="tindakanhdid" value="<?=$form_data_kiri[0]['tindakan_id']?>">
					    <input type="hidden" id="tggl" value="<?=$form_data_kiri[0]['tanggal']?>">
					    <input type="hidden" id="pasienid" value="<?=$pasien_id?>">
					    <input type="hidden" id="observasiid" name="observasiid">
					    <input type="hidden" id="id" name="id"value="<?=$id?>">

	<!-- 		    <div class="row"> -->
			    	<!-- <div class="col-md-6"> -->
			    		<div class="form-group">
							<label class="col-md-12"><?=translate("No.Transaksi", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
							 	<label class="control-label"><?=$form_data_kiri[0]['no_transaksi']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
						 
								<div class="col-md-12">
							 		<label class="control-label"><?=($form_data_kiri[0]['tanggal']!= NULL || $form_data_kiri[0]['tanggal']!='')?date('d M Y',strtotime($form_data_kiri[0]['tanggal'])):'-'?></label>
								</div>
						</div>

			    		<div class="form-group">
							<label class="col-md-12"><?=translate("No.Pasien", $this->session->userdata("language"))?> :</label>
						 
								<div class="col-md-12">
							 		<label class="control-label"><?=$form_data_kiri[0]['no_member']?></label>
								</div>
							 
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
						 
								<div class="col-md-12">
							 		<label class="control-label"><?=$form_data_kiri[0]['nama']?></label>
								</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Dialisis Terakhir", $this->session->userdata("language"))?> :</label>
						 
								<div class="col-md-12">
							 		<label class="control-label"><?=($form_data_kiri[0]['tanggal_terakhir']!= NULL || $form_data_kiri[0]['tanggal_terakhir']!='')?date('d M Y',strtotime($form_data_kiri[0]['tanggal'])):'-'?></label>
								</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
						 
								<div class="col-md-12">
							 		<label class="control-label"><?=$form_data_kiri[0]['keterangan']?></label>
								</div>
						</div>

						
			    	<!-- </div> -->

			    	<!-- <div class="col-md-6"> -->
			    		<div class="form-group">
							<label class="col-md-12"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
						 
								<div class="col-md-12">
							 		<label class="control-label"><?=$form_data_kanan[0]['nama']?></label>
								</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Berat Awal", $this->session->userdata("language"))?> :</label>
						 
								<div class="col-md-12">
							 		<label class="control-label"><?=$form_data_kanan[0]['berat_awal']?> Kg</label>
								</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Berat Akhir", $this->session->userdata("language"))?> :</label>
						 
								<div class="col-md-12">
							 		<label class="control-label"><?=$form_data_kanan[0]['berat_akhir']?> Kg</label>
								</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Rujukan", $this->session->userdata("language"))?> :</label>
						 
								<div class="col-md-12">
							 		<label class="control-label"><?=$form_data_kanan[0]['nama_poli']?></label>
								</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("No.Bed", $this->session->userdata("language"))?> :</label>
						 
								<div class="col-md-12">
							 		<label class="control-label"><?=$form_data_kanan[0]['kode']?></label>
								</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Klaim", $this->session->userdata("language"))?> :</label>
						 
								<div class="col-md-12">
							 		<label class="control-label"><?=$form_data_kanan[0]['nama_penjamin']?></label>
								</div>
						</div>
			    	<!-- </div> -->
			    <!-- </div> -->
				</div>
			</div>
		</div>
		</div>
		<div class="col-md-9">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Item yang digunakan", $this->session->userdata("language"))?>
					</div> 
				</div>
				 
				<div class="portlet-body form">
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Waktu Tindakan :", $this->session->userdata("language"))?></label>
							<label class="] col-md-9"><?=$assesment['waktu']?></label>
							
							
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Type of Dialyzer :", $this->session->userdata("language"))?></label>
							
							<div class="col-md-9">
								<?php 
									
							 		($assesment['dialyzer_new']) ? $new = 'checked' : $new 	= '';
							 		($assesment['dialyzer_reuse']) ? $reuse = 'checked' : $reuse 	= '';

								?>
								<div class="radio-list">
									<label class="radio-inline">
									<input type="radio" id="new_" value="1" data-type="1" <?=$new?> name="dializer" disabled class=""> New</label>
									<label class="radio-inline">
									<input type="radio" id="reuse_" value="1" data-type="2" <?=$reuse?> name="dializer" disabled class=""> Reuse </label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Dialyzer :", $this->session->userdata("language"))?></label>
							<label class="col-md-9"><?=$assesment['dialyzer']?></label>
						</div>
						<div class="table-container">
							<div class="table-actions-wrapper" >
								<span>
									<?=translate("Search", $this->session->userdata("language"))?>:&nbsp;<input type="text" aria-controls="table_user" class="table-group-action-input form-control input-small input-inline input-sm"></input>			
								</span>
									
							</div>
							<table class="table table-striped table-bordered table-hover" id="table_provision">
							<thead>
								<tr>
									<th width="10%">
										<center> <?=translate("Waktu Pemberian", $this->session->userdata("language"))?></center>
									</th>
									<th width="15%">
										<center> <?=translate("Nama Item", $this->session->userdata("language"))?></center>
									</th>
									
									<th width="10%">
										<center> <?=translate("Jumlah Item", $this->session->userdata("language"))?></center>
									</th>
									<th width="15%">
										<center> <?=translate("Nama Perawat", $this->session->userdata("language"))?></center>
									</th>
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
	</div>
	
	
</div>	


<?=form_close()?>










