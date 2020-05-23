<?php
	$form_attr = array(
		"id"			=> "form_selesaikan_tindakan", 
		"name"			=> "form_selesaikan_tindakan", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
	);
	echo form_open(base_url()."klinik_hd/transaksi_perawat/save_selesaikan", $form_attr,$hidden);
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Umum", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('No. Transaksi', $this->session->userdata('language'))?> :</label>
						<div class="col-md-7">
							<input class="form-control hidden" id="tindakan_hd_id" name="tindakan_hd_id" value="<?=$form_tindakan['id']?>">
							<input class="form-control hidden" id="tindakan_hd_penaksiran_id" name="tindakan_hd_penaksiran_id" value="<?=$form_assesment[0]['id']?>">
							<input class="form-control hidden" id="bed_id" name="bed_id" value="<?=$pk_value?>">
							<label class="control-label bold"><?=$form_tindakan['no_transaksi']?></label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Dialisis Terakhir', $this->session->userdata('language'))?> :</label>
						<div class="col-md-7">
							<label class="control-label"><?=date('d F Y', strtotime($form_tindakan['tanggal']))?></label>
						</div>
					</div>
					<div class="form-group">
						<?php 
							$dokter = $this->user_m->get($form_tindakan['dokter_id']);
						?>
						<label class="control-label col-md-4"><?=translate('Dokter', $this->session->userdata('language'))?> :</label>
						<div class="col-md-7">
							<label class="control-label"><?=$dokter->nama?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('No. Bed', $this->session->userdata('language'))?> :</label>
						<div class="col-md-7">
							<?php 
								$bed = $this->bed_m->get($form_tindakan['bed_id']);

							?>
							<label class="control-label"><?=$bed->kode.' - '.$bed->nama?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
						<div class="col-md-7">
							<label class="control-label" style="text-align:left"><?=$form_tindakan['keterangan']?></label>
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('No. Pasien', $this->session->userdata('language'))?> :</label>
						<div class="col-md-7">
							<label class="control-label bold"><?=$form_pasien['no_member']?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Nama Pasien', $this->session->userdata('language'))?> :</label>
						<div class="col-md-7">
							<label class="control-label"><?=$form_pasien['nama']?></label>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Klaim', $this->session->userdata('language'))?> :</label>
						<div class="col-md-7">
							<?php 
								$penjamin = new stdClass();
								$penjamin->nama = '-';
								if ($form_tindakan['penjamin_id']) {
									$penjamin = $this->penjamin_m->get($form_tindakan['penjamin_id']);
								}
							?>
							<label class="control-label"><?=$penjamin->nama?></label>
						</div>
					</div>
				</div>
				<?php
					$url = array();
		            if ($form_pasien['url_photo'] != '') 
		            {
		                $url = explode('/', $form_pasien['url_photo']);
		                // die(dump($row['url_photo']));
		                if (file_exists(FCPATH.config_item('site_img_pasien').'foto/'.$form_pasien['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').'foto/'.$form_pasien['url_photo'])) 
		                {
		                    $img_url = base_url().config_item('site_img_pasien').'foto/'.$form_pasien['url_photo'];
		                }
		                else
		                {
		                    $img_url = base_url().config_item('site_img_pasien').'global/global.png';
		                }
		            } else {

		                $img_url = base_url().config_item('site_img_pasien').'global/global.png';
		            }

				?>
				<div class="col-md-2 text-center">
					<img class="img-thumbnail" src="<?=$img_url?>" style="max-width:120px">
				</div>
			</div>


			<div class="portlet" style="margin-top: 20px;">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Selesai Tindakan", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body">
				<div class="row">
				<div class="col-md-6">
					<div class="survey_pertanyaan">
						<!-- <div class="form-group"> -->
								<?php 
									$survey = $this->pertanyaan_surey_m->get_by(array('tipe' => 1, 'poliklinik_id' => 1, 'is_active' => 1));
									// die_dump($survey);
									
									$i = 1;
									foreach ($survey as $data_survey) 
									{
										echo '<div class="form-group">';
										echo '<label class="control-label col-md-6">';
										echo $data_survey->pertanyaan.' :</label>';
										echo '<input class="form-control hidden" id="survey_id_'.$i.'" name="pertanyaan['.$i.'][survey_id]" value="'.$data_survey->id.'">';
										echo '<input class="form-control hidden" id="nilai_'.$i.'" name="pertanyaan['.$i.'][nilai]">';


										echo '<div class="col-md-6">
												<div class="radio-list">';
										for ($z = $data_survey->range_awal ; $z <= $data_survey->range_akhir ; $z++) 
										{ 
											if ($z == 0) 
											{
												echo '<label class="radio-inline"><input type="radio" class="form-control radio_nilai" data-row="'.$i.'" name="nilai_survey_'.$i.'" id="" value="0"> ';
												echo translate('Sangat Buruk', $this->session->userdata('language'));
												echo '</label>';
											}
											if ($z == 1) 
											{
												echo '<label class="radio-inline"><input type="radio" class="form-control radio_nilai" data-row="'.$i.'" name="nilai_survey_'.$i.'" id="" value="1"> ';
												echo translate('Buruk', $this->session->userdata('language'));
												echo '</label>';
											}
											if ($z == 2) 
											{
												echo '<label class="radio-inline"><input type="radio" class="form-control radio_nilai" data-row="'.$i.'" name="nilai_survey_'.$i.'" id="" value="2"> ';
												echo translate('Biasa', $this->session->userdata('language'));
												echo '</label>';
											}
											if ($z == 3) 
											{
												echo '<label class="radio-inline"><input type="radio" class="form-control radio_nilai" data-row="'.$i.'" name="nilai_survey_'.$i.'" id="" value="3"> ';
												echo translate('Baik', $this->session->userdata('language'));
												echo '</label>';
											}
											if ($z == 4) 
											{
												echo '<label class="radio-inline"><input type="radio" class="form-control radio_nilai" data-row="'.$i.'" name="nilai_survey_'.$i.'" id="" value="4"> ';
												echo translate('Sangat Baik', $this->session->userdata('language'));
												echo '</label>';
											}

										}

										echo '</div>';
										echo '</div>';
										echo '</div>';

										$i++;
									}
								?>
							
						<!-- </div>	 -->
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate('Berat Badan Awal', $this->session->userdata('language'))?> :</label>
						<div class="col-md-4">
							<label class="control-label"><?=$form_tindakan['berat_awal']?> Kg</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate('Berat Badan Kering', $this->session->userdata('language'))?> :</label>
						<div class="col-md-4">
							<label class="control-label"><?=$form_pasien['berat_badan_kering']?> Kg</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate('Berat Badan Akhir', $this->session->userdata('language'))?> :</label>
						<div class="col-md-2">
							<div class="input-group">
								<input class="form-control" id="berat_akhir" name="berat_akhir" required>
								<span class="input-group-addon">
									&nbsp;Kg&nbsp;
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate('Reuse Dializer', $this->session->userdata('language'))?> :</label>
						<div class="col-md-4">
							<div class="radio-list">
								<label class="radio-inline">
								<input type="radio" name="reuse_dializer" id="ya" value="1" checked> <?=translate('Ya', $this->session->userdata('language'))?></label>
								<label class="radio-inline">
								<input type="radio" name="reuse_dializer" id="tidak" value="0"> <?=translate('Tidak', $this->session->userdata('language'))?> </label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate('Keluhan', $this->session->userdata('language'))?> :</label>
						<div class="col-md-4">
							<textarea class="form-control" name="keluhan_selesai" rows="5"></textarea>
						</div>
					</div>	
					<div class="form-group hidden">
						<label class="control-label col-md-3"><?=translate('Assesment', $this->session->userdata('language'))?> :</label>
						<div class="col-md-8">
							<textarea class="form-control" readonly name="assessment_cgs_selesai" rows="5"><?=$form_assesment[0]['assessment_cgs']?></textarea>
						</div>
					</div>	
				</div>	
				</div>	

					
				</div>
			</div>
		</div>
		<div class="form-actions hidden">
			<button class="btn btn-primary" id="simpan_selesai_tindakan">Simpan</button>
		</div>
	</div>		
</div>