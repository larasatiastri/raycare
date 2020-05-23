<?php
	$form_attr = array(
	    "id"            => "form_add_proses_klaim", 
	    "name"          => "form_add_proses_klaim", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."klaim/proses_klaim/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$user_direktur = $this->user_m->get_by(array('user_level_id' => 22, 'is_active' => 1));
	$penerima = $this->petugas_bpjs_m->get_by(array('jabatan' => 2, 'is_active' => 1));
	$verif = $this->petugas_bpjs_m->get_by(array('jabatan' => 3, 'is_active' => 1));

	$diserahkan_option = array();
	$diterima_option = array();
	$verif_option = array();

	foreach ($user_direktur as $direktur) 
	{
		$diserahkan_option[$direktur->id] = $direktur->nama;
	};

	foreach ($penerima as $penerima) 
	{
		$diterima_option[$penerima->id] = $penerima->nama;
	};

	foreach ($verif as $verif) 
	{
		$verif_option[$verif->id] = $verif->nama;
	};


?>

<div class="form-body">
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Proses Klaim BPJS', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan memproses klaim bpjs ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Informasi', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Tanggal Surat", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<div class="input-group date" id="date">
									<input type="text" class="form-control" id="tanggal_surat" name="tanggal_surat" value="<?=date('d M Y')?>" readonly required>
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("No Surat", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<input class="form-control" name="no_surat" id="no_surat" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("No Surat Perjanjian", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<input class="form-control" name="no_surat_perjanjian" id="no_surat_perjanjian" value="#OL#116/RHS-BPJS/XII/2018" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Periode Tindakan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<div class="input-group input-medium-date date" id="month">
									<input type="text" class="form-control" id="periode_tindakan" name="periode_tindakan" value="<?=date('F Y', strtotime(" -1 month"))?>" readonly required>
									<span class="input-group-btn">
										<button class="btn default date-set" type="button">
											<i class="fa fa-calendar"></i>
										</button>
									</span>
								</div>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Jumlah Tindakan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<input class="form-control" name="jumlah_tindakan" id="jumlah_tindakan" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Jumlah Tarif Riil RS", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<input class="form-control" name="jumlah_tarif_riil" id="jumlah_tarif_riil" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Jumlah Tarif INA CBGs", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<input class="form-control" name="jumlah_tarif_ina" id="jumlah_tarif_ina" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Diserahkan oleh", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<?php
									echo form_dropdown('diserahkan_oleh', $diserahkan_option, '','id="diserahkan_oleh" class="form-control" required');
								?>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Diterima oleh", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<?php
									echo form_dropdown('penerima_id', $diterima_option, '','id="penerima_id" class="form-control" required');
								?>
							</div>	
						</div>
						<div class="form-group hidden">
							<label class="control-label col-md-4"><?=translate("Diverifikasi oleh", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<?php
									echo form_dropdown('verif_id', $verif_option, '','id="verif_id" class="form-control" required');
								?>
							</div>	
						</div>
						
					</div>
				</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
		
	</div><!-- end of <div class="row"> -->

	</div>
</div>


<?=form_close()?>





