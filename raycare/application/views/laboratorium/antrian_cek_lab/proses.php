<?php
	$form_attr = array(
	    "id"            => "form_add_cek_lab", 
	    "name"          => "form_add_cek_lab", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."laboratorium/antrian_cek_lab/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$check_terdaftar = '';
	$check_umum = '';
	$pasien_id = '';
	$no_rekmed = '-';
	$nama_pasien = '';
	$tanggal_lahir = '';
	$umur_pasien = '';
	$check_lk = '';
	$check_pr = '';
	$no_telp = '';
	$alamat = '';

	if($pendaftaran['tipe_pasien'] == 1){
		$check_terdaftar = '';
		$check_umum = 'hidden';
		$pasien_id = $pendaftaran['pasien_id'];

		$data_pasien = $this->pasien_m->get_by(array('id' => $pendaftaran['pasien_id']), true);
		$data_pasien_alamat = $this->pasien_alamat_m->get_by(array('pasien_id' => $pendaftaran['pasien_id'], 'is_primary' => 1), true);
		$data_pasien_telepon = $this->pasien_telepon_m->get_by(array('pasien_id' => $pendaftaran['pasien_id'], 'is_primary' => 1), true);

		$no_rekmed = $data_pasien->no_member;
		$nama_pasien = $data_pasien->nama;
		$tanggal_lahir = date('d M Y', strtotime($data_pasien->tanggal_lahir));

		$datetime1 = new DateTime();
        $datetime2 = new DateTime($data_pasien->tanggal_lahir);
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('%y Tahun %m Bulan %d Hari');

		$umur_pasien = $elapsed;

		$check_lk = ($data_pasien->gender == 'L')?'checked':'';
		$check_pr = ($data_pasien->gender == 'P')?'checked':'';

		$no_telp = $data_pasien_telepon->nomor;
		$alamat = $data_pasien_alamat->alamat;


	}if($pendaftaran['tipe_pasien'] == 2){
		$check_terdaftar = 'hidden';
		$check_umum = '';

		$no_rekmed = '-';
		$nama_pasien = $pendaftaran['nama_pasien'];
		$tanggal_lahir = date('d M Y');

		$datetime1 = new DateTime();
        $datetime2 = new DateTime();
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('%y Tahun %m Bulan %d Hari');

		$umur_pasien = $elapsed;

		$check_lk = '';
		$check_pr = '';

		$no_telp = $pendaftaran['no_telp'];
		$alamat = '';
	}
?>


<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Formulir Tindakan Lab', $this->session->userdata('language'))?></span>
		</div>

	</div>
	
	<div class="portlet-body form">
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Informasi', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="row"> 
						<div class="col-md-6"> 

						<div class="form-body" id="section-diagnosis">
						<div class="form-group">
			   	           	<div class="col-md-12">
	          					<div class="btn-group btn-group-justified">
									<a id="btn_terdaftar" class="btn btn-primary <?=$check_terdaftar?>">
										<?=translate("Pasien Terdaftar", $this->session->userdata("language"))?>
									</a>
									<a id="btn_tidak_terdaftar" class="btn btn-default <?=$check_umum?>">
										<?=translate("Umum", $this->session->userdata("language"))?>
									</a>
								</div>
			              	</div>
		              	</div>
						<input class="form-control hidden" id="tipe_pasien" name="tipe_pasien" value="<?=$pendaftaran['tipe_pasien']?>" >
						<input class="form-control hidden" id="pendaftaran_id" name="pendaftaran_id" value="<?=$pendaftaran['id']?>" >
						<input type="hidden" class="form-control" id="tanggal_periksa" name="tanggal_periksa" value="<?=date('d M Y')?>" readonly >
							<div class="form-group pasien_terdaftar">
								<label class="col-md-4"><?=translate("No. Rekam Medis", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<label class="col-md-8"><?=translate("Nama Pasien", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-4">
										<input class="form-control" id="no_rekmed" name="no_rekmed" value="<?=$no_rekmed?>" readonly placeholder="<?=translate("No. Rekam Medis", $this->session->userdata("language"))?>" required>
										
									<input class="form-control hidden" id="id_ref_pasien" name="id_ref_pasien" value="<?=$pasien_id?>"  placeholder="<?=translate("ID Referensi Pasien", $this->session->userdata("language"))?>">
								</div>	
								<div class="col-md-8">
										<input class="form-control" id="nama_ref_pasien" name="nama_ref_pasien" value="<?=$nama_pasien?>" readonly  required placeholder="<?=translate("Nama Pasien", $this->session->userdata("language"))?>">
								</div>	
							</div>
							
							<div class="form-group">
								<label class="col-md-6"><?=translate("Tanggal Lahir", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<label class="col-md-6"><?=translate("Umur", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-6">
									<div class="input-group date" id="tanggal_lahir">
										<input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?=$tanggal_lahir?>" readonly >

										<span class="input-group-btn">
											<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>	
								<div class="col-md-6">
									<input class="form-control" id="umur_pasien" name="umur_pasien" value="<?=$umur_pasien?>" readonly  required placeholder="<?=translate("Umur Pasien", $this->session->userdata("language"))?>">
								</div>	
							</div>
							<div class="form-group">
								
								<label class="col-md-6"><?=translate("Jenis Kelamin", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<label class="col-md-6"><?=translate("No. Telp", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								
								<div class="col-md-6">
									 <div class="radio-list">
				                            <label class="radio-inline">
				                                <div class="radio-inline" style="padding-left:0px !important;">
				                                    <span class="">
				                                        <input type="radio" name="jenis_kelamin"  <?=$check_lk?> value="L" id="jk-laki" required>
				                                    </span>
				                                </div> 

				                                <span>Laki-Laki</span> 
				                            </label>
				                            <label class="radio-inline">
				                                <div class="radio-inline"  >
				                                    <span class="">
				                                        <input type="radio" name="jenis_kelamin"  <?=$check_pr?> value="P" id="jk-perempuan" required>
				                                    </span>
				                                </div> 
				                                <span>Perempuan</span> 
				                            </label>
				                        </div>
								</div>	
								<div class="col-md-6">
									<input class="form-control" id="no_telp" name="no_telp" value="<?=$no_telp?>"  required placeholder="<?=translate("No. Telp", $this->session->userdata("language"))?>">
								</div>	
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
										<textarea class="form-control" id="alamat_pasien" name="alamat_pasien" value="<?=$alamat?>" required placeholder="<?=translate("Alamat Pasien", $this->session->userdata("language"))?>"></textarea>
								</div>	
							</div>
						</div>
					</div><!-- end of <div class="portlet-body"> -->	
					<div class="col-md-6"> 
						<div class="form-group">
							<label class="col-md-12"><?=translate("Dokter Pengirim", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
									<input class="form-control" id="dokter_pengirim" name="dokter_pengirim" value="" required placeholder="<?=translate("Dokter Pengirim", $this->session->userdata("language"))?>">
							</div>	
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> : </label>
							<div class="col-md-12">
									<textarea class="form-control" id="alamat" name="alamat" value=""  placeholder="<?=translate("Alamat", $this->session->userdata("language"))?>"></textarea>
							</div>	
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("No. Telp/HP", $this->session->userdata("language"))?> : </label>
							<div class="col-md-12">
									<input class="form-control" id="no_telp_dokter" name="no_telp_dokter" value=""  placeholder="<?=translate("No. Telp", $this->session->userdata("language"))?>">
									<input type="hidden" class="form-control" id="total_bayar" name="total_bayar" value="">
							</div>	
						</div>
						<div class="form-group">
								<label class="col-md-12"><?=translate("Diagnosa Klinis", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
										<textarea class="form-control" id="diagnosa_klinis" name="diagnosa_klinis" value=""   required placeholder="<?=translate("Diagnosa Klinis", $this->session->userdata("language"))?>"></textarea>
								</div>	
							</div>

					</div>
						</div>
					</div>	
				</div>	
			</div>
			<div class="col-md-12"> 
				<div class="portlet light bordered">
					<div class="portlet-title"> 
						<div class="caption text-right"> 
								<span class="caption-subject font-blue-sharp bold" id="total_bayar">Rp. 0</span>
						</div>
					
					</div>
					<div class="portlet-body">
						<?php
							foreach ($kategori_lab as $key => $kategori) {

								$nama_kategori = $this->kategori_pemeriksaan_lab_m->get_by(array('tipe' => $kategori['tipe'], 'is_active' => 1));
								$nama_kategori = object_to_array($nama_kategori);
						?>
								<div class="portlet box blue-sharp">
									<div class="portlet-title"  style="margin-bottom: 0px !important;">
										<div class="caption"><input type="hidden" class="form-control" name="input[<?=$key?>][urutan]" id="input_<?=$key?>" value="<?=$kategori['urutan']?>"><?=$kategori['tipe']?></div>						
									</div>
									<div class="portlet-body">
									<div class="row"> 
										<?php
											foreach ($nama_kategori as $key => $nam_kat) {
												$pemeriksaan = $this->pemeriksaan_lab_m->get_data_join_tindakan($nam_kat['id'])->result_array();
										?>
										
											<div class="col-md-3"> 
												<table>
													<thead>
														<tr><th colspan="3"><input type="hidden" class="form-control" name="input_kategori_<?=$kategori['urutan']?>[<?=$key?>][id]" id="input_kategori_<?=$kategori['urutan']?>_<?=$key?>" value="<?=$nam_kat['id']?>"><?=$nam_kat['nama']?></th></tr>
													</thead>
													<tbody>
														<?php
															foreach ($pemeriksaan as $keys => $periksa) {
														?>
															<tr>
																<td><?=$periksa['kode']?></td>
																<td><input type="checkbox" data-pemeriksaan_id="<?=$periksa['id']?>" data-nominal="<?=$periksa['harga']?>" class="form-control" name="input_<?=$nam_kat['id']?>[<?=$keys?>][pilih]" id="input_pilih_<?=$nam_kat['id']?>_<?=$keys?>" value="<?=$periksa['id']?>"><input type="hidden" class="form-control" name="input_<?=$nam_kat['id']?>[<?=$keys?>][kode]" id="input_kode_<?=$nam_kat['id']?>_<?=$keys?>" value="<?=$periksa['kode']?>"><input type="hidden" class="form-control" name="input_<?=$nam_kat['id']?>[<?=$keys?>][nama]" id="input_nama_<?=$nam_kat['id']?>_<?=$keys?>" value="<?=$periksa['nama']?>"><input type="hidden" class="form-control" name="input_<?=$nam_kat['id']?>[<?=$keys?>][tindakan_id]" id="input_tindakan_id_<?=$nam_kat['id']?>_<?=$keys?>" value="<?=$periksa['tindakan_id']?>"><input type="hidden" class="form-control" name="input_<?=$nam_kat['id']?>[<?=$keys?>][harga]" id="input_harga_<?=$nam_kat['id']?>_<?=$keys?>" value="<?=$periksa['harga']?>"></td>
																<td><?=$periksa['nama']?></td>
															</tr>
														<?php
															}
														?>
														
													</tbody>
												</table>
											</div>
											
										<?
											}
										?>
										</div>	
									</div>
								</div>
						<?php
							}
						?>
					</div>
				</div>
			</div><!-- end of <div class="col-md-4"> -->
	</div><!-- end of <div class="row"> -->
	<?php $msg = translate("Apakah anda yakin akan menyimpan data cek lab ini?",$this->session->userdata("language"));?>
	<div class="form-actions right">	
		<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
		<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="glyphicon glyphicon-floppy-disk"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
	</div>
</div>
	</div>



<?=form_close()?>


