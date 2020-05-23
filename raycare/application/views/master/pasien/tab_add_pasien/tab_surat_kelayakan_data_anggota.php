<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<?=translate('Surat Data Kelayakan Anggota', $this->session->userdata('language'))?>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Kode Cabang", $this->session->userdata("language"))?> :</label>
			<?php 
				$cabang = $this->cabang_m->get($this->session->userdata('cabang_id'));
			?>
			<div class="col-md-8">
				<?php
					$kode_cabang = array(
						"id"			=> "kode_cabang_rujukan",
						"name"			=> "kode_cabang_rujukan",
						"autofocus"		=> true,
						"class"			=> "form-control", 
						"readonly"		=> "form-readonly", 
						"placeholder"	=> translate("Otomatis", $this->session->userdata("language")), 
						"value"			=> $cabang->kode,
						"help"			=> $cabang->kode,
					);
					echo form_input($kode_cabang);
				?>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Asal Faskes Tk.1/Klinik/Puskesmas", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<div class="input-group">
					<?php
						$faskes_1 = array(
							"id"			=> "faskes_1",
							"name"			=> "faskes_1",
							"class"			=> "form-control", 
							"readonly"		=> "readonly", 
							"placeholder"	=> translate("Asal Faskes Tk.1/Klinik/Puskesmas", $this->session->userdata("language")), 
							"value"			=> $flash_form_data['faskes_1'],
							"help"			=> $flash_form_data['faskes_1'],
						);
						echo form_input($faskes_1);

						$id_faskes_1 = array(
							"id"			=> "id_faskes_1",
							"name"			=> "id_faskes_1",
							"class"			=> "form-control", 
							"type"			=> "hidden"
						);
						echo form_input($id_faskes_1);
					?>
					<span class="input-group-btn">
						<a class="btn btn-primary search_faskes" data-toggle="modal" data-target="#modal_faskes_1" title="<?=translate('Cari', $this->session->userdata('language'))?>" href="<?=base_url()?>master/pasien/search_faskes"><i class="fa fa-search"></i></a>
					</span>
				</div>
				<span class="help-block">
					Isi dengan nama faskes yang tercantum dalam kartu BPJS pasien.
				</span>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Asal RS Rujukan / Traveling", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<div class="input-group">
					<?php
						$asal_faskes = array(
							"id"			=> "asal_faskes",
							"name"			=> "asal_faskes",
							"class"			=> "form-control", 
							"readonly"			=> "readonly", 
							"placeholder"	=> translate("Asal RS Rujukan / Traveling", $this->session->userdata("language")), 
							"value"			=> $flash_form_data['asal_faskes'],
							"help"			=> $flash_form_data['asal_faskes'],
						);
						echo form_input($asal_faskes);
					?>
					<span class="input-group-btn">
						<a class="btn btn-primary search_faskes" data-toggle="modal" data-target="#modal_faskes" title="<?=translate('Cari', $this->session->userdata('language'))?>" href="<?=base_url()?>master/pasien/search_faskes_1"><i class="fa fa-search"></i></a>
					</span>
				</div>
				<span class="help-block">
					Isi dengan nama faskes yang tercantum dalam surat rujukan puskesmas.
				</span>
			</div>
		</div>

		<div class="form-group faskes hidden">
			<label class="control-label col-md-4"><?=translate("Kode Faskes", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<?php
					$kode_faskes = array(
						"id"			=> "kode_faskes",
						"name"			=> "kode_faskes",
						"class"			=> "form-control", 
						"readonly"			=> "readonly", 
						"placeholder"	=> translate("Kode Faskes", $this->session->userdata("language")), 
						"value"			=> $flash_form_data['kode_faskes'],
						"help"			=> $flash_form_data['kode_faskes'],
					);
					echo form_input($kode_faskes);

					$id_faskes = array(
						"id"			=> "id_faskes",
						"name"			=> "id_faskes",
						"class"			=> "form-control", 
						"type"			=> "hidden"
					);
					echo form_input($id_faskes);
				?>
			</div>
		</div>

		<div class="form-group faskes hidden">
			<label class="control-label col-md-4"><?=translate("Regional", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<?php
					$regional = array(
						"id"			=> "regional",
						"name"			=> "regional",
						"class"			=> "form-control", 
						"readonly"			=> "readonly", 
						"placeholder"	=> translate("Regional", $this->session->userdata("language")), 
						"value"			=> $flash_form_data['regional'],
						"help"			=> $flash_form_data['regional'],
					);
					echo form_input($regional);
				?>
			</div>
		</div>

		

		<div class="form-group hidden">
			<label class="control-label col-md-4"><?=translate("Kode RS / Puskesmas Rujukan", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<?php
					$kode_rs_rujukan = array(
						"id"			=> "kode_rs_rujukan",
						"name"			=> "kode_rs_rujukan",
						"autofocus"			=> true,
						"class"			=> "form-control", 
						"placeholder"	=> translate("Kode RS / Puskesmas Rujukan", $this->session->userdata("language")), 
						"value"			=> $flash_form_data['kode_rs_rujukan'],
						"help"			=> $flash_form_data['kode_rs_rujukan'],
					);
					echo form_input($kode_rs_rujukan);
				?>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Tanggal Rujukan RS/Faskes", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<div class="input-group date" id="tanggal_rujukan">
					<input type="text" class="form-control" id="tanggal_rujukan" name="tanggal_rujukan" readonly >
					<span class="input-group-btn">
						<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
					</span>
				</div>
				<span class="help-block">
					Isi dengan tanggal rujukan dari RS/Faskes yang merujuk langsung ke Klinik Raycare.
				</span>
			</div>

		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Nomor Rujukan", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<?php
					$nomer_rujukan = array(
						"id"			=> "nomer_rujukan",
						"name"			=> "nomer_rujukan",
						"autofocus"		=> true,
						"class"			=> "form-control",
					    "placeholder"	=> translate("No Rujukan", $this->session->userdata("language")),
					);
					echo form_input($nomer_rujukan);
				?>
				<span class="help-block">
					Isi dengan nomor rujukan dari Faskes tk 1 yang ada di kartu BPJS. Jika rujukan langsung dari RS maka isi dengan tanda strip(-)
				</span>
			</div>
		</div>
		<div class="form-group faskes">
			<label class="control-label col-md-4"><?=translate("Marketing", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-8">
				<?php
					$marketing_option = array(
						''		=> translate('Pilih', $this->session->userdata('language')).'...'
					);
					$data_marketing = $this->user_m->get_by('user_level_id = 20 AND is_active = 1' );

					foreach ($data_marketing as $row) {
						$marketing_option[$row->id] =  $row->nama;
					}
					echo form_dropdown('id_marketing', $marketing_option, '', 'id="id_marketing" class="form-control"');

					$nama_marketing = array(
						"id"			=> "nama_marketing",
						"name"			=> "nama_marketing",
						"type"			=> "hidden"
					);
					echo form_input($nama_marketing);
					
				?>
			</div>
		</div>
	</div>
</div>