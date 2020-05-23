<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<?=translate('Informasi Lain', $this->session->userdata('language'))?>
		</div>
	</div>
	<div class="portlet-body">

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Dokter Pengirim", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<?php
					$dokter_pengirim = array(
						"id"			=> "dokter_pengirim",
						"name"			=> "dokter_pengirim",
						"class"			=> "form-control", 
						"placeholder"	=> translate("Dokter Pengirim", $this->session->userdata("language")), 
						"value"			=> $form_data['dokter_pengirim'],
						"help"			=> $flash_form_data['dokter_pengirim'],
					);
					echo form_input($dokter_pengirim);
				?>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Referensi Dari Pasien Lain", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<div class="input-group">
					<?php

						$get_ref_pasien = $this->pasien_m->get_by(array('id' => $form_data['pasien_id']));
						$data_ref_pasien = object_to_array($get_ref_pasien);
						$ref_pasien = '';
						
						foreach ($data_ref_pasien as $data) {
							$ref_pasien = $data['nama'];
						}

						$nama_ref_pasien = array(
							"id"			=> "nama_ref_pasien",
							"name"			=> "nama_ref_pasien",
							"class"			=> "form-control", 
							"placeholder"	=> translate("Referensi Pasien", $this->session->userdata("language")), 
							"value"			=> $ref_pasien,
							"help"			=> $flash_form_data['nama_ref_pasien'],
						);

						$id_ref_pasien = array(
							"id"			=> "id_ref_pasien",
							"name"			=> "id_ref_pasien",
							"class"			=> "form-control hidden", 
							"placeholder"	=> translate("ID Referensi Pasien", $this->session->userdata("language")), 
							"value"			=> $form_data['pasien_id'],
							"help"			=> $flash_form_data['id_ref_pasien'],
						);
						echo form_input($nama_ref_pasien);
						echo form_input($id_ref_pasien);
					?>
					<span class="input-group-btn">
						<a class="btn grey-cascade pilih-pasien" title="<?=translate('Pilih Pasien', $this->session->userdata('language'))?>">
							<i class="fa fa-search"></i>
							<span>&nbsp;Pilih Pasien</span>
						</a>
					</span>
				</div>
				
				
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

					foreach ($data_pasien_penyakit as $data) {
						$pasien_penyakit_id[$data['penyakit_id']] = $data['penyakit_id'] ;
					}


					$penyakit_bawaan = $this->penyakit_m->get_by(array('tipe' => 1));
					$penyakit_bawaan_array = object_to_array($penyakit_bawaan);
					
					$penyakit_bawaan_option = array(
						''	=> '',
					);

					foreach ($penyakit_bawaan_array as $select) {
				        $penyakit_bawaan_option[$select['id']] = $select['nama'];
				    }

					echo form_dropdown('penyakit_bawaan[]', $penyakit_bawaan_option, $pasien_penyakit_id, "id=\"multi_select_penyakit_bawaan\" class=\"multi-select\" multiple=\"multiple\"");
						
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

					foreach ($data_pasien_penyakit as $data) {
						$pasien_penyakit_id[$data['penyakit_id']] = $data['penyakit_id'] ;
					}

					$penyakit_penyebab = $this->penyakit_m->get_by(array('tipe' => 2));
					$penyakit_penyebab_array = object_to_array($penyakit_penyebab);
					
					$penyakit_penyebab_option = array(
						''	=> '',
					);

				    foreach ($penyakit_penyebab_array as $select) {
				        $penyakit_penyebab_option[$select['id']] = $select['nama'];
				    }
					echo form_dropdown('penyakit_penyebab[]', $penyakit_penyebab_option, $pasien_penyakit_id, "id=\"multi_select_penyakit_penyebab\" class=\"multi-select\" multiple=\"multiple\"");
						
				?>
			</div>
			
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<?php
					$keterangan = array(
						"id"			=> "keterangan",
						"name"			=> "keterangan",
						"rows"			=> 5, 
						"class"			=> "form-control", 
						"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
						"value"			=> $form_data['keterangan'],
						"help"			=> $flash_form_data['keterangan'],
					);
					echo form_textarea($keterangan);				
				?>
			</div>
			
		</div>
	</div>
</div>