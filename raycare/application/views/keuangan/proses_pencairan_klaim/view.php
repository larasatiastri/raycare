<?php
	$form_attr = array(
	    "id"            => "form_add_proses_klaim", 
	    "name"          => "form_add_proses_klaim", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "proses",
        "id_kwitansi" => $data_klaim_kwitansi['id'],
        "proses_klaim_id" => $data_klaim_kwitansi['proses_klaim_id']
    );

    echo form_open(base_url()."keuangan/proses_pencairan_klaim/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$data_bank = $this->bank_m->get_by(array('id' => $data_klaim_kwitansi['bank_id']), true);
	$data_bank = object_to_array($data_bank);



?>

<div class="form-body">
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('View History Terima Pencairan BPJS', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan memproses klaim bpjs ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
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
							<label class="control-label col-md-4"><?=translate("Tanggal Kwitansi", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label> <?=date('d M Y', strtotime($data_klaim['tanggal']))?></label>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Tanggal Terima", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label> <?=date('d M Y', strtotime($data_klaim['tanggal_terima']))?></label>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("No Kwitansi", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
							<label> <?=$data_klaim_kwitansi['no_kwitansi']?></label>
							</div>	
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Periode Tindakan", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label><?=date('M Y', strtotime($data_klaim['periode_tindakan']))?></label>
								<input class="form-control hidden" name="periode_tindakan" id="periode_tindakan" value="<?=$data_klaim['periode_tindakan']?>" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("No. FPK", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label><?=$data_klaim['no_fpk']?></label>
								<input class="form-control hidden" name="no_fpk" id="no_fpk" value="<?=$data_klaim['no_fpk']?>" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Tanggal FPK", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label> <?=date('d M Y', strtotime($data_klaim['tanggal_proses']))?></label>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Jumlah Tindakan", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label><?=$data_klaim['jumlah_tindakan']?></label>
								<input class="form-control hidden" name="jumlah_tindakan" id="jumlah_tindakan" value="<?=$data_klaim['jumlah_tindakan']?>" required></input>
							</div>		
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Jumlah Tarif Riil RS", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label><?=formatrupiah($data_klaim['jumlah_tarif_riil'])?></label>
								<input class="form-control hidden" name="jumlah_tarif_riil" id="jumlah_tarif_riil" value="<?=$data_klaim['jumlah_tarif_riil']?>" required></input>
							</div>		
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Jumlah Tarif INA CBGs", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label><?=formatrupiah($data_klaim['jumlah_tarif_ina_verif'])?></label>
								<input class="form-control hidden" name="jumlah_terima" id="jumlah_terima" value="<?=$data_klaim['jumlah_tarif_ina_verif']?>" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Biaya Administrasi", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label><?=formatrupiah($data_klaim_kwitansi['jumlah_admin'])?></label>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Jumlah Diterima", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label><?=formatrupiah($data_klaim_kwitansi['total_diterima'])?></label>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Terbilang", $this->session->userdata("language"))?> : </label>
							<div class="col-md-8">
								<label><?='# '.terbilang($data_klaim_kwitansi['total_diterima']).' Rupiah #'?></label>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Bank Penerima", $this->session->userdata("language"))?> : </label>
							<div class="col-md-8">
								<label><?=$data_bank['nob'].' - '.$data_bank['acc_number'].' a/n '.$data_bank['acc_name']?></label>
								
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





