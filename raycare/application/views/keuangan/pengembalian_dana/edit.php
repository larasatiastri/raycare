<?php

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_add_pengajuan_pemegang_saham", 
		"name"			=> "form_add_pengajuan_pemegang_saham", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "edit",
		"id"		=> $pk_value
	);


	echo form_open(base_url()."keuangan/pengembalian_dana/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

    $user_level_id = $this->session->userdata('level_id');
    $user_id = $this->session->userdata('user_id');
    $nama_user = $this->session->userdata('nama_lengkap');

    $data_bank = $this->bank_m->get_by(array('is_active' => 1));

	$bank_option = array();

	foreach ($data_bank as $bank) 
	{
		$bank_option[$bank->id] = $bank->nob.' A/C No.'. $bank->acc_number;
	}
   
?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
		<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Edit Pengembalian Dana", $this->session->userdata("language"))?> #<?=$data_pps['nomor_pengajuan']?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-12"><?=translate('Tanggal', $this->session->userdata('language'))?> :</label>
						<div class="col-md-12">
							<div class="input-group date">
								<input type="text" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?=date('d M Y', strtotime($data_pps['tanggal']))?>"readonly required>
								<span class="input-group-btn">
									<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12"><?=translate('Nominal', $this->session->userdata('language'))?> :</label>
						<div class="col-md-12">
							<div class="input-group">
								<span class="input-group-addon">&nbsp;IDR&nbsp;</span>
								<input class="form-control text-right" type="text" id="nominal_show" name="nominal_show" placeholder="Nominal" required value="<?=$data_pps['nominal']?>">
								
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
						<label class="col-md-12" id="terbilang">#<?=terbilang($data_pps['nominal']) ?> Rupiah#</label>
					</div>
					<div class="form-group">
						<label class="col-md-12"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
						<div class="col-md-12">
							<?php
								$keterangan = array(
									"name"        => "keterangan",
									"id"          => "keterangan",
									"class"       => "form-control",
									"required"	  => "required",
									"value"		  => $data_pps['keterangan'],
									"rows"        => 3, 
									"placeholder" => translate("Keterangan", $this->session->userdata("language")),
									
								);
								echo form_textarea($keterangan);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12"><?=translate("Kirim Ke Bank", $this->session->userdata("language"))?> : </label>
						<div class="col-md-12">
							<?php
								echo form_dropdown('bank_id', $bank_option, $data_pps['bank_id'], 'id="bank_id" class="form-control" required');
							?>
						</div>	
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-12"><?=translate('Bank Pengirim', $this->session->userdata('language'))?> :</label>
						<div class="col-md-12">
							<input class="form-control" type="text" id="nama_bank_pengirim" name="nama_bank_pengirim" placeholder="Bank Pengirim" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12"><?=translate('No. Rekening Pengirim', $this->session->userdata('language'))?> :</label>
						<div class="col-md-12">
							<input class="form-control" type="text" id="nomor_rekening_pengirim" name="nomor_rekening_pengirim" placeholder="No. Rekening Pengirim" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12"><?=translate('Atas Nama', $this->session->userdata('language'))?> :</label>
						<div class="col-md-12">
							<input class="form-control" type="text" id="atas_nama_pengirim" name="atas_nama_pengirim" placeholder="Atas Nama" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12"><?=translate('Upload Bukti', $this->session->userdata('language'))?> :</label>
						<div class="col-md-12">
						<input type="hidden" name="url" id="url" required="">
						<div id="upload">
							<span class="btn default btn-file">
								<span class="fileinput-new"><?=translate('Pilih Gambar', $this->session->userdata('language'))?></span>		
								<input type="file" class="upl_invoice" name="upl" id="upl" data-url="<?=base_url()?>upload_new/upload_photo" multiple />
							</span>

						<ul class="ul-img">
						</ul>

						</div>
					</div>
				</div>
			</div>
		</div>
				
			<?php
				$confirm_save       = translate('Anda yakin untuk menyimpan pengembalian dana ini?',$this->session->userdata('language'));
				$submit_text        = translate('Simpan', $this->session->userdata('language'));
				$reset_text         = translate('Reset', $this->session->userdata('language'));
				$back_text          = translate('Kembali', $this->session->userdata('language'));
			?>
			<div class="form-actions right">    
		        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
		        	<i class="fa fa-chevron-left"></i>
		        	<?=$back_text?>
		        </a>
		        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
		        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
		        <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal">
		        	<i class="fa fa-check"></i>
		        	<?=$submit_text?>
		        </a>
			</div>
		</div>
	</div>
</div>


