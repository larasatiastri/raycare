<div class="portlet light">
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_claim_pasien", 
			    "name"          => "form_claim_pasien", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
				"command"            => "edit_penjamin",
				"id_pasien"          => $id_pasien,
				"id_pasien_penjamin" => $id_pasien_penjamin,
		    );

		    echo form_open(base_url()."master/pasien/save_penjamin", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');
		?>

		<div class="form-body">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penjamin Pasien", $this->session->userdata("language"))?></span>
					</div>
					<?php $msg = translate("Apakah anda yakin akan mengubah penjamin pasien ini?",$this->session->userdata("language"));?>
					<div class="actions">	
						<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
						<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
				        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
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
					    <div class="form-group hidden">
							<label class="control-label col-md-2"><?=translate("Id Pasien", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-3 hidden">
								<?php
									$id_pasien_form = array(
										"id"			=> "id_pasien",
										"name"			=> "id_pasien",
										"autofocus"		=> true,
										"class"			=> "form-control", 
										"placeholder"	=> translate("ID Pasien", $this->session->userdata("language")), 
										"value"			=> $id_pasien,
										"help"			=> $flash_form_data['id_pasien'],
									);
									echo form_input($id_pasien_form);
								?>

							</div>
							
						</div>

						<div class="form-group">
							<label class="control-label col-md-2 hidden"><?=translate("Row Saat Ini", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-3 hidden">
								<?php
									$row = array(
										"id"			=> "row",
										"name"			=> "row",
										"autofocus"		=> true,
										"class"			=> "form-control", 
										"placeholder"	=> translate("Row Saat Ini", $this->session->userdata("language")), 
										"value"			=> "dokumen_0",
										"help"			=> $flash_form_data['row'],
									);
									echo form_input($row);
								?>

							</div>
							
						</div>
					    <div class="form-group">
							<label class="control-label col-md-2 hidden"><?=translate("ID Pasien Penjamin", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-3">
								<?php
									$pasien_penjamin_array = array(
										"id"			=> "id_pasien_penjamin",
										"name"			=> "id_pasien_penjamin",
										"autofocus"		=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("ID Pasien Penjamin", $this->session->userdata("language")), 
										"value"			=> $id_pasien_penjamin,
										"help"			=> $flash_form_data['id_pasien_penjamin'],
									);
									echo form_input($pasien_penjamin_array);
								?>

							</div>
							
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 hidden"><?=translate("Penjamin ID", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-3">
								<?php
									
									$data_pasien_penjamin = $this->pasien_penjamin_m->get_by(array('id' => $id_pasien_penjamin));
									
									$penjamin_id = "";
									$no_kartu = "";
									foreach ($data_pasien_penjamin as $data)
									{
										$penjamin_id          = $data->penjamin_id;
										$no_kartu             = $data->no_kartu;
										$penjamin_kelompok_id = $data->penjamin_kelompok_id;
									}

									$penjamin_id_array = array(
										"id"			=> "penjamin_id",
										"name"			=> "penjamin_id",
										"autofocus"		=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Penjamin ID", $this->session->userdata("language")), 
										"value"			=> $penjamin_id,
										"help"			=> $flash_form_data['id_pasien_penjamin'],
									);
									echo form_input($penjamin_id_array);
								?>

							</div>
							
						</div>

						<div class="form-group">
							<label class="control-label col-md-2 hidden"><?=translate("Penjamin Kelompok ID", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-3">
								<?php
									
									$penjamin_kelompok_id_array = array(
										"id"			=> "penjamin_kelompok_id",
										"name"			=> "penjamin_kelompok_id",
										"autofocus"		=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Penjamin Kelompok ID", $this->session->userdata("language")), 
										"value"			=> $penjamin_kelompok_id,
										"help"			=> $flash_form_data['penjamin_kelompok_id'],
									);
									echo form_input($penjamin_kelompok_id_array);
								?>

							</div>
							
						</div>
						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Pasien", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-3">
								<?php
									$data_pasien = $this->pasien_m->get($id_pasien);
								?>
								<label class="control-label"><?=$data_pasien->nama?></label>
							</div>
						</div>

						<?php
							if($penjamin_id != 1)
							{
						?>
							<div class="form-group">
								<label class="control-label col-md-2"><?=translate("Penjamin", $this->session->userdata("language"))?> :</label>
								
								<div class="col-md-3">
									<?php
										$penjamin = $this->penjamin_m->get_by(array('is_suspended' => '0', 'id !=' => '1'));
										// die(dump($this->db->last_query()));
										$penjamin_option = array(
										    '' => translate('Pilih..', $this->session->userdata('language'))
										);

										foreach ($penjamin as $data)
										{
										    $penjamin_option[$data->id] = $data->nama;
										}
										echo form_dropdown('penjamin', $penjamin_option, $penjamin_id, "id=\"penjamin\" class=\"form-control\" disabled");
									?>
								</div>

								<div class="col-md-2" id="show_claim_kelompok">
									
								</div>
							</div>
							<div class="form-group">
							<label class="control-label col-md-2"><?=translate("No Kartu", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-3">
								<?php
									$no_kartu = array(
										"id"			=> "no_kartu",
										"name"			=> "no_kartu",
										"autofocus"		=> true,
										"class"			=> "form-control", 
										"placeholder"	=> translate("No Kartu", $this->session->userdata("language")), 
										"value"			=> $no_kartu,
										"help"			=> $flash_form_data['no_kartu'],
									);
									echo form_input($no_kartu);
								?>

							</div>
							
						</div>
						
						<?php
							}
							else
							{
								$penjamin = $this->penjamin_m->get_by(array('is_suspended' => '0', 'id' => $penjamin_id), true);
						?>
							<div class="form-group">
								<label class="control-label col-md-2"><?=translate("Penjamin", $this->session->userdata("language"))?> :</label>
								
								<div class="col-md-3">
									<input type="hidden" class="form-control" id="penjamin" name="penjamin" value="<?=$penjamin_id?>"></input>
									<input type="hidden" class="form-control" id="no_kartu" name="no_kartu" value=""></input>
									<label class="control-label"><?=$penjamin->nama?></label>
								</div>

								<div class="col-md-2" id="show_claim_kelompok">
									
								</div>
							</div>
						<?php
							}
						?>



						<div id="show_claim">
						</div>
					</div>
				</div>
			</div>
		</div>

		
	</div>
</div>
<?=form_close()?>





