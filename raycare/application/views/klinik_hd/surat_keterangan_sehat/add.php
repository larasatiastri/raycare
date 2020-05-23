<?php
	$form_attr = array(
	    "id"            => "form_add_surat_sehat", 
	    "name"          => "form_add_surat_sehat", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."klinik_hd/surat_keterangan_sehat/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>

<div class="form-body">
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Buat Surat Keterangan Sehat', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat surat keterangan sehat ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="<?=base_url()?>klinik_hd/surat_keterangan_sehat/history"><i class="fa fa-history"></i>  <?=translate("History", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Informasi', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
				<?php

					$id_pasien = '';
					$no_rekmed = '';
					$nama_pasien = '';
					$nama_pekerjaan = '';
					$umur_pasien = ''; 
					$gender_pasien = '';
					$form_alamat = '';
					$form_kel_alamat  = '';
				    $form_kec_alamat  = '';
				    $form_kota_alamat = '';
				    $gol_darah = '-';

					if($pasien_id != null)
					{
						$id_pasien = $pasien_id;
						$data_pasien = $this->pasien_m->get($id_pasien);
						$no_rekmed = $data_pasien->no_member;
						$nama_pasien = $data_pasien->nama;
						
						$gender_pasien = $data_pasien->gender;
						if($gender_pasien == 'L')
						{
							$gender_pasien = 'Laki - Laki';
						}
						if($gender_pasien == 'P')
						{
							$gender_pasien = 'Perempuan';
						}
						$umur_pasien = date_diff(date_create($data_pasien->tanggal_lahir), date_create('today'))->y.' Tahun';

						if ($umur_pasien < 1) {
							$umur_pasien = translate('Dibawah 1 tahun', $this->session->userdata('language'));
						}
											
						$form_pekerjaan   = $this->info_umum_m->get($data_pasien->pekerjaan_id);
						$nama_pekerjaan = (count($form_pekerjaan)!=0)?$form_pekerjaan->nama:'-';

						$form_gol_darah   = $this->info_umum_m->get($data_pasien->golongan_darah_id);
						$gol_darah = (count($form_gol_darah)!=0)?$form_gol_darah->nama:'-';

						$alamat_pasien = $this->pasien_alamat_m->get_data_alamat($data_pasien->id)->result_array();
						$alamat_pasien = $alamat_pasien[0];
				        $data_lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $alamat_pasien['kode_lokasi']),true);

				        if(count($data_lokasi))
				        {
				            $form_kel_alamat  = $data_lokasi->nama_kelurahan;
				            $form_kec_alamat  = $data_lokasi->nama_kecamatan;
				            $form_kota_alamat = $data_lokasi->nama_kabupatenkota;            
				        }

				        $rt = '';
						$rw = '';
						if ($alamat_pasien['rt_rw'] != NULL) 
						{
							$rt_rw = explode('_', $alamat_pasien['rt_rw']);
							$rt = " RT. ".$rt_rw[0];
							$rw = " RW. ".$rt_rw[1];
						}
						
						$form_alamat = $alamat_pasien['alamat'].$rt.$rw;
						$form_kel_alamat = ($form_kel_alamat != '')? " Kel. ".$form_kel_alamat:'';
						$form_kec_alamat = ($form_kec_alamat != '')?  " Kec. ".$form_kec_alamat:'';
						$form_kota_alamat = ($form_kota_alamat != '')?  " ".$form_kota_alamat:'';
					}
				?>
				<div class="form-body" id="section-diagnosis">
					<div class="form-group">
							<label class="col-md-12 bold"><?=translate("No. Rekam Medis", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-12">
								<div class="input-group">
									<input class="form-control" id="no_rekmed" name="no_rekmed" value="<?=$no_rekmed?>" placeholder="<?=translate("No. Rekam Medis", $this->session->userdata("language"))?>" required>
									<span class="input-group-btn">
										<a class="btn grey-cascade pilih-pasien" title="<?translate('Pilih Pasien', $this->session->userdata('language'))?>">
											<i class="fa fa-search"></i>
										</a>
									</span>
								</div>
								<input class="form-control hidden" id="id_ref_pasien" name="id_ref_pasien" value="<?=$id_pasien?>" required placeholder="<?=translate("ID Referensi Pasien", $this->session->userdata("language"))?>">
							</div>	
						</div>
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Pasien", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-12">
									<input class="form-control" id="nama_ref_pasien" name="nama_ref_pasien" value="<?=$nama_pasien?>" readonly  required placeholder="<?=translate("Nama Pasien", $this->session->userdata("language"))?>">
							</div>	
						</div>
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Golongan Darah", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-12">
									<input class="form-control" id="gol_darah" name="gol_darah" value="<?=$gol_darah?>" readonly placeholder="<?=translate("Golongan Darah", $this->session->userdata("language"))?>">
							</div>	
						</div>
				
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Tinggi Badan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<div class="input-group">
									<input type="text" class="form-control" id="tinggi_badan" name="tinggi_badan" min="0" value="0" required>
									<span class="input-group-addon">
										&nbsp;Cm&nbsp;
									</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Berat Badan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<div class="input-group">
									<input type="text" class="form-control" id="berat_badan" name="berat_badan" min="0" value="0" required>
									<span class="input-group-addon">
										&nbsp;Kg&nbsp;
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Tekanan Darah", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<div class="input-group">
									<input type="text" class="form-control" id="td_atas" name="td_atas" min="0" value="0" required>
									<span class="input-group-addon">
										&nbsp; / &nbsp;
									</span>
									<input type="text" class="form-control" id="td_bawah" name="td_bawah" min="0" value="0" required>
								</div>
							</div>
						</div>



						

						
						
				</div>
		</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
		<div class="col-md-8">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('VIEW', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-2"><?=translate("Nama", $this->session->userdata("language"))?></label>
								<label class="col-md-1">:</label>
								<label class="col-md-9" id="label_nama_pasien"><?=$nama_pasien?></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-2"><?=translate("Umur", $this->session->userdata("language"))?></label>
								<label class="col-md-1">:</label>
								<label class="col-md-9" id="label_umur_pasien"><?=$umur_pasien?></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-2"><?=translate("Jenis Kelamin", $this->session->userdata("language"))?></label>
								<label class="col-md-1">:</label>
								<label class="col-md-9" id="label_jenis_kelamin"><?=$gender_pasien?></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-2"><?=translate("Pekerjaan", $this->session->userdata("language"))?></label>
								<label class="col-md-1">:</label>
								<label class="col-md-9" id="label_pekerjaan_pasien"><?=$nama_pekerjaan?></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-2"><?=translate("Alamat", $this->session->userdata("language"))?></label>
								<label class="col-md-1">:</label>
								<label class="col-md-9" id="label_alamat_pasien"><?=$form_alamat.$form_kel_alamat.$form_kec_alamat.$form_kota_alamat?></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-12"><?=translate("Setelah melalui pemeriksaan, dinyatakan", $this->session->userdata("language"))?> <b><?=translate('SEHAT', $this->session->userdata('language'))?></b></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-12"><?=translate("Demikian Surat Keterangan Sehat ini dibuat untuk dapat dipergunakan seperlunya.", $this->session->userdata("language"))?> </label>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-12"><u><?=translate("Keterangan", $this->session->userdata("language"))?> :</u></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6">
								<label class="col-md-6"><?=translate("Tinggi Badan", $this->session->userdata("language"))?> :</label>
								<label class="" id="label_tinggi_badan"> Cm</label>
							</div>
							<div class="col-md-6">
								<label class=""><?=translate("Tekanan Darah", $this->session->userdata("language"))?> :</label>
								<label class="" id="label_tekanan_darah"> mmHg</label>
							</div>	
						</div>
						<div class="form-group">
							<div class="col-md-6">
								<label class="col-md-6"><?=translate("Berat Badan", $this->session->userdata("language"))?> :</label>
								<label class="" id="label_berat_badan"> Kg</label>
							</div>		
							<div class="col-md-6">
								<label class=""><?=translate("Golongan Darah", $this->session->userdata("language"))?> :</label>
								<label class="" id="label_gol_darah"><?=$gol_darah?></label>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end of <div class="portlet light bordered"> -->
		</div><!-- end of <div class="col-md-8"> -->
	</div><!-- end of <div class="row"> -->

	</div>
</div>


<?=form_close()?>

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No. RM', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tempat, Tanggal Lahir', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 




