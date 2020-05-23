<?php

	$form_attr = array(
		"id"			=> "form_add_pengajuan_pembayaran_kasbon", 
		"name"			=> "form_add_pengajuan_pembayaran_kasbon", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "upload_cek",
		"id"		=> $pk_value
	);


	echo form_open(base_url()."keuangan/pengajuan_pembayaran_kasbon/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

    $user_id = $form_data['created_by'];
    $user = $this->user_m->get($user_id);
    $user_level_id = $user->user_level_id;
    $nama_user = $user->nama;

    $user_setuju_id = $form_data['disetujui_oleh'];
    $user_setuju = $this->user_m->get($user_setuju_id);

    $tgl_setuju = date('d M Y', strtotime($form_data['created_date']));


?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
		<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("PENGAJUAN PEMBAYARAN KASBON", $this->session->userdata("language"))?></span>
		</div>

		<?php
			$confirm_save       = translate('Anda yakin untuk mengupdate pengajuan pembayaran kasbon ini?',$this->session->userdata('language'));
			$submit_text        = translate('Simpan', $this->session->userdata('language'));
			$reset_text         = translate('Reset', $this->session->userdata('language'));
			$back_text          = translate('Kembali', $this->session->userdata('language'));
		?>
		<div class="actions">    
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
	<?php
	if($form_data['status'] == 3){
		?>
	<div class="note note-danger note-bordered">
		<p>
			 NOTE: <?=$form_data['keterangan_tolak'].'. Created By: '.$user_setuju->nama.', Created Date: '.$tgl_setuju?>
		</p>
	</div>
	<?php
	}
	?>
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
		</div>
		<div class="form-wizard">
			<div class="row">
				<div class="col-md-3">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?> :</span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('Tanggal', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12"><?=date('d M Y', strtotime($form_data['tanggal']))?></label>
						</div>
						 
						<div class="form-group">
							<label class="col-md-12"><?=translate('Dibuat Oleh', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12"><?=$nama_user?></label>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate('Subjek', $this->session->userdata('language'))?> :</label>
							<input class="form-control" type="hidden" name="subjek" id="subjek" value="<?=$form_data['subjek']?>"></input>		
							<label class="col-md-12"><?=$form_data['subjek']?></label>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('No. Cek', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12"><?=$form_data['no_cek']?></label>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate('Nominal', $this->session->userdata('language'))?> :</label>
							<input class="form-control" type="hidden" name="nominal" id="nominal" value="<?=$form_data['nominal']?>"></input>		
							<label class="col-md-12"><?=formatrupiah($form_data['nominal'])?></label>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate('Terbilang', $this->session->userdata('language'))?></label>
							<label class="col-md-12" id="terbilang">#<?=terbilang($form_data['nominal'])?> Rupiah #</label>

						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Kirim dari Bank", $this->session->userdata("language"))?> :</label>
							<input class="form-control" type="hidden" name="bank_id" id="bank_id" value="<?=$form_data['bank_id']?>"></input>		
								<?php
									$banks = $this->bank_m->get($form_data['bank_id']);	
								?>
							<label class="col-md-12"><?=$banks->nob.' a/n '.$banks->acc_name?></label>
						</div>

					</div>
					
				</div>
				<div class="col-md-9" id="section-Keterangan">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Kasbon", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-body">
						    <div class="portlet-body">
								<table class="table table-condensed table-striped table-bordered table-hover" id="tabel_kasbon">
	                                <thead>
	                                    <tr>
	                                        <th class="text-center" width="15%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="20%"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="15%"><?=translate("Rupiah", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("Keperluan", $this->session->userdata("language"))?> </th>
	                                    </tr>
	                                </thead>
	                                <tbody>	
	                                	<?php
	                                		foreach ($form_data_detail as $detail) {
	                                			?>
	                                		<tr>
	                                			<td class="text-center"><?=date('d M Y', strtotime($detail['tanggal']))?></td>
	                                			<td class="text-left"><?=$detail['diminta_oleh']?></td>
	                                			<td class="text-right"><?=formatrupiah($detail['nominal_setujui'])?></td>
	                                			<td class="text-left"><?=$detail['keperluan']?></td>
	                                		</tr>
	                                			<?php
	                                		}
	                                	?>
	                                </tbody>
	                            </table>
                            </div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Upload Cek", $this->session->userdata("language"))?> :</span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<input type="hidden" name="url_cek" id="url_cek">
								<div id="upload">
									<span class="btn default btn-file">
										<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>	
										<input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload/upload_photo" />
									</span>

									<ul class="ul-img">
									<!-- The file uploads will be shown here -->
									</ul>

								</div>
							</div>
						</div>

					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>


