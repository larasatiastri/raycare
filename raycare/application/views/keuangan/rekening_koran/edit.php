<?php

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_add_rekening_koran", 
		"name"			=> "form_add_rekening_koran", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command" => "edit",
		"id"      => $pk_value
	);


	echo form_open(base_url()."keuangan/rekening_koran/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

    $user_level_id = $this->session->userdata('level_id');
    $user_id = $this->session->userdata('user_id');
    $nama_user = $this->session->userdata('nama_lengkap');
    // die_dump($user_level_id);

    $arus_kas = $this->rekening_koran_m->get_by(array('keuangan_arus_kas_id' => $data_rekening['id']), true);
    $arus_kas = object_to_array($arus_kas);

    // die(dump($arus_kas));

    $check_debet = '';
    $check_kredit = '';
    if($data_rekening['debit_credit'] == 'c' || $data_rekening['debit_credit'] == 'C'){
		$check_debet = 'checked="checked"';
		$check_kredit = '';

	}if($data_rekening['debit_credit'] == 'd'|| $data_rekening['debit_credit'] == 'D'){
		$check_kredit = 'checked="checked"';
		$check_debet = '';
	}

    $readonly = '';
    if($data_rekening['tipe'] != 0){
    	$readonly = 'readonly="readonly"';
    }
?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
		<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Rekening Koran", $this->session->userdata("language"))?></span>
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


			<div class="row">
				<div class="col-md-12">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-group">
							<input type="hidden" class="form-control" id="rekening_koran_id" name="rekening_koran_id" value="<?=$arus_kas['id']?>">	
							<input type="hidden" class="form-control" id="jumlah_kas" name="jumlah_kas" value="<?=$data_rekening['rupiah']?>">	
							<input type="hidden" class="form-control" id="tanggal_kas" name="tanggal_kas" value="<?=date('d M Y', strtotime($data_rekening['tanggal']))?>">	
							<input type="hidden" class="form-control" id="bank_kas" name="bank_kas" value="<?=date('d M Y', strtotime($data_rekening['bank_id']))?>">	

							<label class="control-label col-md-4"><?=translate('Tanggal', $this->session->userdata('language'))?> :</label>
							<div class="col-md-7">
								<div class="input-group date">
									<input type="text" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?=date('d M Y', strtotime($data_rekening['tanggal']))?>"readonly >
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Bank", $this->session->userdata("language"))?> :</label>
							<div class="col-md-7">
								<?php
									$banks = $this->bank_m->get_by(array('is_active' => 1));

									$bank_option = array();

									foreach ($banks as $bank) {
										$bank_option[$bank->id] = $bank->nob.' a/n '.$bank->acc_name.' - '.$bank->acc_number;
									}

									echo form_dropdown('bank_id', $bank_option, $data_rekening['bank_id'], 'id="bank_id" class="form-control"');
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate('Tipe', $this->session->userdata('language'))?> :</label>
							<div class="col-md-7">
								<div class="radio-list">
									<label class="radio-inline">
									<input type="radio" id="debit" value="c" data-type="d" name="debit_credit" class="form-control" <?=$check_debet?> required> Debit</label>
									<label class="radio-inline">
									<input type="radio" id="kredit" value="d" data-type="c" name="debit_credit" class="form-control" <?=$check_kredit?> required> Kredit </label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Nomor", $this->session->userdata("language"))?> :</label>
							<div class="col-md-7">
								<input class="form-control" id="nomor" name="nomor" placeholder="Nomor" value="<?=$arus_kas['nomor']?>">	
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Nomor BG / Cek", $this->session->userdata("language"))?> :</label>
							<div class="col-md-7">
								<input class="form-control" id="nomor_cek" name="nomor_cek" placeholder="No.BG / Cek" value="<?=$arus_kas['nomor_cek']?>">	
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Jumlah", $this->session->userdata("language"))?> :</label>
							<div class="col-md-7">
									<div class="input-group">
										<span class="input-group-addon">
											Rp.
										</span>
										<input class="form-control" id="jumlah" name="jumlah" placeholder="Nominal" <?=$readonly?> value="<?=$data_rekening['rupiah']?>">
									</div>
									<span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate('Terbilang', $this->session->userdata('language'))?></label>
							<label class="col-md-7" id="terbilang"><?='# '.terbilang($data_rekening['rupiah']).' Rupiah #'?></label>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate('Keterangan', $this->session->userdata('language'))?></label>
							<div class="col-md-7">
								<?php
									$keterangan = array(
										"name"        => "keterangan",
										"id"          => "keterangan",
										"class"       => "form-control",
										"rows"        => 10, 
										"placeholder" => translate("Keterangan", $this->session->userdata("language")),
										"value"		  => $data_rekening['keterangan']
										
									);
									echo form_textarea($keterangan);
								?>
							</div>
						</div>
					</div>
				</div>		
			</div>
			<?php
				$confirm_save       = translate('Anda yakin untuk menambahkan rekening koran ini?',$this->session->userdata('language'));
				$submit_text        = translate('Simpan', $this->session->userdata('language'));
				$reset_text         = translate('Reset', $this->session->userdata('language'));
				$back_text          = translate('Kembali', $this->session->userdata('language'));
			?>
			<div class="form-actions right">    
		        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
		        	<i class="fa fa-chevron-left"></i>
		        	<?=$back_text?>
		        </a>
		        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
		        <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal">
		        	<i class="fa fa-check"></i>
		        	<?=$submit_text?>
		        </a>
			</div>
		</div>
	</div>
</div>


