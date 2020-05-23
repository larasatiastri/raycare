<?php

	$form_attr = array(
		"id"			=> "form_add_pengajuan_pembayaran_kasbon", 
		"name"			=> "form_add_pengajuan_pembayaran_kasbon", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("VIEW PENGAJUAN PEMBAYARAN KASBON", $this->session->userdata("language"))?></span>
		</div>

		<?php
			$confirm_save       = translate('Anda yakin untuk menambahkan permintaan biaya ini?',$this->session->userdata('language'));
			$submit_text        = translate('Simpan', $this->session->userdata('language'));
			$reset_text         = translate('Reset', $this->session->userdata('language'));
			$back_text          = translate('Kembali', $this->session->userdata('language'));
		?>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=$back_text?>
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
							<label class="col-md-12"><?=$form_data['subjek']?></label>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('No. Cek', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12"><?=$form_data['no_cek']?></label>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate('Nominal', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12"><?=formatrupiah($form_data['nominal'])?></label>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate('Terbilang', $this->session->userdata('language'))?></label>
							<label class="col-md-12" id="terbilang">#<?=terbilang($form_data['nominal'])?> Rupiah #</label>

						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Kirim dari Bank", $this->session->userdata("language"))?> :</label>		
								<?php
									$banks = $this->bank_m->get($form_data['bank_id']);	
								?>
							<label class="col-md-12"><?=$banks->nob.' a/n '.$banks->acc_name?></label>
						</div>

						<?php
							if($form_data['status'] >= 4){
								?>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Bukti Cek", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<input type="hidden" name="url_cek" id="url_cek" value="<?=$form_data['url_cek']?>">
									<div id="upload">
										<ul class="ul-img">
										<li class="working">
											<div class="thumbnail">
												<a class="fancybox-button" title="<?=$form_data['url_cek']?>" href="<?=base_url().'assets/mb/pages/keuangan/pengajuan_pembayaran_kasbon/images/'.$pk_value.'/'.$form_data['url_cek']?>" data-rel="fancybox-button"><img src="<?=base_url().'assets/mb/pages/keuangan/pengajuan_pembayaran_kasbon/images/'.$pk_value.'/'.$form_data['url_cek']?>" alt="Smiley face" class="img-thumbnail" ></a>
											</div>
										</li>
										</ul>

									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate('Total Biaya', $this->session->userdata('language'))?> :</label>
								<label class="col-md-12" id="label_total_biaya"><?=formatrupiah($form_data['nominal']-$form_data['sisa_kasbon'])?></label>

							</div>

							<div class="form-group">
								<label class="col-md-12"><?=translate('Sisa Kasbon', $this->session->userdata('language'))?> :</label>
								<label class="col-md-12"><?=formatrupiah($form_data['sisa_kasbon'])?></label>
							</div>

							<div class="form-group">
								<label class="col-md-12"><?=translate('Terbilang Sisa', $this->session->userdata('language'))?> :</label>
								<label class="col-md-12" id="terbilang_sisa"># <?=terbilang($form_data['sisa_kasbon'])?> Rupiah #</label>

							</div>

							<div class="form-group">
								<label class="col-md-12"><?=translate("Bukti Setor", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<input type="hidden" name="url_setor_sisa" id="url_setor_sisa" value="<?=$form_data['url_setor_sisa']?>">
									<div id="upload">
										<ul class="ul-img">
										<li class="working">
											<div class="thumbnail">
												<a class="fancybox-button" title="<?=$form_data['url_setor_sisa']?>" href="<?=base_url().'assets/mb/pages/keuangan/pengajuan_pembayaran_kasbon/images/'.$pk_value.'/'.$form_data['url_setor_sisa']?>" data-rel="fancybox-button"><img src="<?=base_url().'assets/mb/pages/keuangan/pengajuan_pembayaran_kasbon/images/'.$pk_value.'/'.$form_data['url_setor_sisa']?>" alt="Smiley face" class="img-thumbnail" ></a>
											</div>
										</li>
										</ul>

									</div>
								</div>
							</div>
								<?php
							}
						?>

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
				<?php
					if($form_data['status'] >= 4){
				?>
				<div class="col-md-9">
					<div class="portlet light bordered" id="section-bon">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Upload Bon", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-body">
				    		<div class="portlet-body">
				    		<?php

							$form_upload_bon = '';

							foreach ($form_data_detail as $detail) {
								$form_data_bon = $this->permintaan_biaya_bon_m->get_by(array('permintaan_biaya_id' => $detail['permintaan_biaya_id']));
								if(count($form_data_bon) != 0){
									$form_data_bon = object_to_array($form_data_bon);
									foreach ($form_data_bon as $key => $bon) {
										$form_upload_bon .= '<tr>
										<td><a class="fancybox-button" title="'.$bon['url'].'" href="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" alt="Smiley face" class="img-responsive" ></a></td>
										<td style="vertical-align: top !important;">'.$detail['keperluan'].'</td>
										<td style="vertical-align: top !important;">'.$bon['no_bon'].'</td>
										<td style="vertical-align: top !important;">'.date('d M Y', strtotime($bon['tgl_bon'])).'</td>
										<td style="vertical-align: top !important;">'.formatrupiah($bon['total_bon']).'</td>
										<td style="vertical-align: top !important;">'.$bon['keterangan'].'</td>
										</tr>';
									}	
								}
							}
							
							?>
							<table class="table table-bordered table-hover">
								<thead>
								<tr role="row" class="heading">
									<th class="text-center" width="8%">
								 		Image
									</th>
									<th class="text-center" width="8%">
								 		Keperluan
									</th>
									<th class="text-center" width="10%">
										 No. Bon
									</th>
									<th class="text-center" width="8%">
										 Tgl. Bon
									</th>
									<th class="text-center" width="10%">
										 Total Bon
									</th>
									<th class="text-center" width="25%">
										 Keterangan
									</th>
								</tr>
								</thead>
								<tbody>
									<?=$form_upload_bon?>
								</tbody>
							</table>  
				    		</div>
				    	</div>
					</div>
				</div>
				<?php
					}
				?>
			</div>
		</div>
	</div>
</div>


