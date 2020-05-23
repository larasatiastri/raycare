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
						"autofocus"			=> true,
						"class"			=> "form-control", 
						"placeholder"	=> translate("Dokter Pengirim", $this->session->userdata("language")), 
						"value"			=> $flash_form_data['dokter_pengirim'],
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
						$nama_ref_pasien = array(
							"id"			=> "nama_ref_pasien",
							"name"			=> "nama_ref_pasien",
							"autofocus"			=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Referensi Pasien", $this->session->userdata("language")), 
							"value"			=> $flash_form_data['nama_ref_pasien'],
							"help"			=> $flash_form_data['nama_ref_pasien'],
						);

						$id_ref_pasien = array(
							"id"			=> "id_ref_pasien",
							"name"			=> "id_ref_pasien",
							"autofocus"			=> true,
							"class"			=> "form-control hidden", 
							"placeholder"	=> translate("ID Referensi Pasien", $this->session->userdata("language")), 
							"value"			=> $flash_form_data['id_ref_pasien'],
							"help"			=> $flash_form_data['id_ref_pasien'],
						);
						echo form_input($nama_ref_pasien);
						echo form_input($id_ref_pasien);
					?>
					<span class="input-group-btn">
						<a class="btn btn-primary pilih-pasien" title="<?=translate('Pilih Pasien', $this->session->userdata('language'))?>">
							<i class="fa fa-search"></i>
							<span>&nbsp;Pilih Pasien</span>
						</a>
					</span>
				</div>
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
						"autofocus"		=> true,
						"class"			=> "form-control", 
						"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
						"value"			=> $flash_form_data['keterangan'],
						"help"			=> $flash_form_data['keterangan'],
					);
					echo form_textarea($keterangan);				
				?>
			</div>
			
		</div>
	</div>
</div>