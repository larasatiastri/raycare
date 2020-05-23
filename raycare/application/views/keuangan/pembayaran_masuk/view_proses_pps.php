<?php

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_proses_pps", 
		"name"			=> "form_proses_pps", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "proses_pps",
		"pps_id"	=> $pps_id,
		"id"		=> $pk_value
	);


	echo form_open(base_url()."keuangan/pembayaran_masuk/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

   
    $bank = $this->bank_m->get_by(array('id' => $form_data['bank_id']), true);

   
?>	
<div class="row">
	<div class="col-md-8">
		<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
		<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Penambahan Modal", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate('Tanggal', $this->session->userdata('language'))?> :</label>
						<label class="col-md-12"><?=date('d M Y', strtotime($form_data['tanggal']))?></label>
					</div>
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate('Nominal', $this->session->userdata('language'))?> :</label>
						<label class="col-md-12"><?=formatrupiah($form_data['nominal'])?></label>
						<input class="form-control" type="hidden" id="nominal" name="nominal" value="<?=$form_data['nominal']?>">
					</div>
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
						<label class="col-md-12"><?=terbilang($form_data['nominal']).' Rupiah'?></label>
					</div>
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
						<input class="form-control" type="hidden" id="keterangan" name="keterangan" value="<?=$form_data['keterangan']?>">
						<label class="col-md-12"><?=$form_data['keterangan']?></label>

					</div>
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate("Kirim Ke Bank", $this->session->userdata("language"))?> : </label>
						<label class="col-md-12"><?=$bank->nob.' A/C No.'. $bank->acc_number?></label>
						<input class="form-control" type="hidden" id="bank_id" name="bank_id" value="<?=$form_data['bank_id']?>">

					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate('Bank Pengaju', $this->session->userdata('language'))?> :</label>
						<label class="col-md-12"><?=$form_data['nama_bank_pengirim']?></label>
						<input class="form-control" type="hidden" id="nama_bank_pengirim" name="nama_bank_pengirim" value="<?=$form_data['nama_bank_pengirim']?>">					
					</div>
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate('No. Rekening Pengaju', $this->session->userdata('language'))?> :</label>
						<label class="col-md-12"><?=$form_data['nomor_rekening_pengirim']?></label>
						<input class="form-control" type="hidden" id="nomor_rekening_pengirim" name="nomor_rekening_pengirim" value="<?=$form_data['nomor_rekening_pengirim']?>">
					</div>
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate('Atas Nama', $this->session->userdata('language'))?> :</label>
						<label class="col-md-12"><?=$form_data['atas_nama_pengirim']?></label>
						<input class="form-control" type="hidden" id="atas_nama_pengirim" name="atas_nama_pengirim" value="<?=$form_data['atas_nama_pengirim']?>">
					</div>
					<?php

						if($form_data['url'] != ''){?>
					<div class="form-group">
						<label class="col-md-12 bold"><?=translate('Dokumen Bukti', $this->session->userdata('language'))?> :</label>
						<div class="col-md-12">
							<input type="hidden" name="url" id="url" value="<?=$form_data['url']?>">
							<div id="upload">
							

							<ul class="ul-img">
								<li class="working">
									<div class="thumbnail">
										<a class="fancybox-button" title="<?=$form_data['url']?>" href="<?=config_item('site_img_pengajuan_ps').$form_data['id'].'/'.$form_data['url']?>" data-rel="fancybox-button">
										<img src="<?=config_item('site_img_pengajuan_ps').$form_data['id'].'/'.$form_data['url']?>" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a>
									</div>
									<span></span>
								</li>
							</ul>

							</div>
						</div>
					</div>

					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>
	</div>
	<div class="col-md-4">
		<div class="portlet light bordered" >
			<div class="portlet-title">
				<div class="caption">
				<i class="icon-plus font-blue-sharp"></i>
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Penambahan Modal", $this->session->userdata("language"))?></span>
				</div>
			</div>
			<div class="portlet-body form">
			<div class="form-body">
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate('Tanggal Terima', $this->session->userdata('language'))?> :</label>
					<label class="col-md-12"><?=date('d M Y', strtotime($form_data_rk['tanggal']))?></label>
					
				</div>
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate('Nomor Rekening Koran', $this->session->userdata('language'))?> :</label>
					<label class="col-md-12"><?=$form_data_rk['nomor']?></label>

				</div>
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate('Nomor Voucher', $this->session->userdata('language'))?> :</label>
					<label class="col-md-12"><?=$form_data_voucher['nomor_voucher']?></label>
				</div>
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate('Nomor Voucher Manual', $this->session->userdata('language'))?> :</label>
					<label class="col-md-12"><?=$form_data_voucher['nomor_voucher_manual']?></label>
				</div>
			</div>
			<?php
				$confirm_save       = translate('Anda yakin untuk menerima penambahan modal ini?',$this->session->userdata('language'));
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
		        
			</div>
        </div>
	</div><!-- end of section-payment -->
	</div>
</div>



