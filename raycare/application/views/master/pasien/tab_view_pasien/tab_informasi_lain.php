<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Informasi Lain', $this->session->userdata('language'))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Faskes", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-3">
				<?php

					$faskes_id = '';
					$check_faskes = '';
					$hide_faskes = '';
					$hide_faskes_lain = 'hidden';
					$nama_faskes = '';
					$faskes_temp_id = '';
					if ($form_data['is_faskes'] == 1) {
						$faskes_id = $form_data['faskes_id'];
						$check_faskes = 'readonly';
					}else{
						$hide_faskes = 'hidden';
						$faskes_id = 'lain-lain';
						$hide_faskes_lain = '';
						$data_fakses_temp = $this->faskes_temp_m->get($form_data['faskes_id']);

						$faskes_temp = object_to_array($data_fakses_temp);
						$faskes_temp_id = $faskes_temp['id'];
						$nama_faskes = $faskes_temp['nama'];

					}
					
					$faskes = $this->faskes_m->get_by(array('id' => $form_data['faskes_id']));
					$data_faskes = object_to_array($faskes);
					
					// die_dump($this->db->last_query());
					$faskes = '';

					// die_dump(count($cara_masuk_array));

				    foreach ($data_faskes as $select) {
				        $faskes = $select['nama'];
				    }

				    $faskes_option['lain-lain'] = "Lain-Lain";



					// echo form_dropdown('faskes', $faskes_option, $faskes_id, "id=\"faskes\" class=\"form-control\"");
				?>
				<label class="control-label <?=$hide_faskes?>"><?=$faskes?></label>
				<label class="control-label <?=$hide_faskes_lain?>"><?=$nama_faskes?></label>

			</div>
			<label class="control-label col-md-1 faskes hidden"><?=translate("Input Faskes", $this->session->userdata("language"))?> :</label>
			<div class="col-md-2">
				<input type="hidden" id="tambah_faskes" name="tambah_faskes" class="form-control faskes <?=$hide_faskes_lain?>" value="<?=$nama_faskes?>">
				<input type="hidden" id="id_faskes_temp" name="id_faskes_temp" class="form-control faskes" value="<?=$faskes_temp_id?>">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Nama Marketing", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-3">
				<label class="control-label"><?=$form_data['marketing']?></label>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Dokter Pengirim", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-3">
				<label class="control-label"><?=$form_data['dokter_pengirim']?></label>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Referensi Dari Pasien Lain", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-3">
				<?php

					$get_ref_pasien = $this->pasien_m->get_by(array('id' => $form_data['pasien_id']));
					$data_ref_pasien = object_to_array($get_ref_pasien);
					$ref_pasien = '';
					
					foreach ($data_ref_pasien as $data) {
						$ref_pasien = $data['nama'];
					}
				?>
				<label class="control-label"><?=$ref_pasien?></label>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Penyakit Bawaan", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<?php
					$pasien_penyakit = $this->pasien_penyakit_m->get_by
										(
											array(
												'pasien_id' => $form_data['id'],
												'is_active' => '1'
											)
										);
					$data_pasien_penyakit = object_to_array($pasien_penyakit);

					$pasien_penyakit_id = array(
						''	=> '',

						);

					echo '<ul style="list-style: none; padding-left: 0px;">';
					foreach ($data_pasien_penyakit as $data) {
						$pasien_penyakit_id[$data['penyakit_id']] = $data['penyakit_id'] ;
						$penyakit_bawaan = $this->penyakit_m->get_by(array('id' => $data['penyakit_id'], 'tipe' => '1'));
						$penyakit_bawaan_array = object_to_array($penyakit_bawaan);

						foreach ($penyakit_bawaan_array as $penyakit_bawaan) {
							echo '<li>'.$penyakit_bawaan['nama'].'</li>';
						}
					}

					echo '</ul>';
				?>
			</div>
			
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Penyakit Penyebab", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<?php
					$pasien_penyakit = $this->pasien_penyakit_m->get_by
										(
											array(
												'pasien_id' => $form_data['id'],
												'is_active' => '1'
											)
										);
					$data_pasien_penyakit = object_to_array($pasien_penyakit);

					$pasien_penyakit_id = array(
						''	=> '',
						
						);

					echo '<ul style="list-style: none; padding-left: 0px;">';
					foreach ($data_pasien_penyakit as $data) {
						$pasien_penyakit_id[$data['penyakit_id']] = $data['penyakit_id'] ;
						$penyakit_penyebab = $this->penyakit_m->get_by(array('id' => $data['penyakit_id'], 'tipe' => '2'));
						$penyakit_penyebab_array = object_to_array($penyakit_penyebab);

						foreach ($penyakit_penyebab_array as $penyakit_penyebab) {
							echo '<li>'.$penyakit_penyebab['nama'].'</li>';
						}
					}

					echo '</ul>';	
				?>
			</div>
			
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-3">
				<label class="control-label"><?=$form_data['keterangan']?></label>
			</div>
			
		</div>
	</div>
</div>