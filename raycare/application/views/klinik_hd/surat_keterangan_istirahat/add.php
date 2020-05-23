<?php
	$form_attr = array(
	    "id"            => "form_add_surat_istirahat", 
	    "name"          => "form_add_surat_istirahat", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."klinik_hd/surat_keterangan_istirahat/save", $form_attr, $hidden);
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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Buat Surat Keterangan Istirahat', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat surat keterangan istirahat ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="<?=base_url()?>klinik_hd/surat_keterangan_istirahat/history"><i class="fa fa-history"></i>  <?=translate("History", $this->session->userdata("language"))?></a>
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
					$form_alamat = '';
					$form_kel_alamat  = '';
				    $form_kec_alamat  = '';
				    $form_kota_alamat = '';

					if($pasien_id != null)
					{
						$id_pasien = $pasien_id;
						$data_pasien = $this->pasien_m->get($id_pasien);
						$no_rekmed = $data_pasien->no_member;
						$nama_pasien = $data_pasien->nama;
						
						$umur_pasien = date_diff(date_create($data_pasien->tanggal_lahir), date_create('today'))->y.' Tahun';

						if ($umur_pasien < 1) {
							$umur_pasien = translate('Dibawah 1 tahun', $this->session->userdata('language'));
						}
											
						$form_pekerjaan   = $this->info_umum_m->get($data_pasien->pekerjaan_id);
						$nama_pekerjaan = (count($form_pekerjaan)!=0)?$form_pekerjaan->nama:'-';

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
							<label class="col-md-12 bold"><?=translate("Lama Istirahat", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<div class="input-group">
									<input type="number" class="form-control" id="lama_istirahat" name="lama_istirahat" min="0" value="1" required>
									<span class="input-group-addon">
										&nbsp;Hari&nbsp;
									</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Tanggal Mulai", $this->session->userdata("language"))?>: <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<div class="input-group date" id="div_tanggal_mulai">
									<input type="text" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?=date('d M Y')?>" readonly required>
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Tanggal Akhir", $this->session->userdata("language"))?>: <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<div class="input-group date" id="div_tanggal_akhir">
									<input type="text" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="<?=date('d M Y')?>" readonly required>
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
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
								<label class="col-md-2 bold"><?=translate("Nama", $this->session->userdata("language"))?></label>
								<label class="col-md-1 bold">:</label>
								<label class="col-md-9" id="label_nama_pasien"><?=$nama_pasien?></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-2 bold"><?=translate("Umur", $this->session->userdata("language"))?></label>
								<label class="col-md-1 bold">:</label>
								<label class="col-md-9" id="label_umur_pasien"><?=$umur_pasien?></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-2 bold"><?=translate("Pekerjaan", $this->session->userdata("language"))?></label>
								<label class="col-md-1 bold">:</label>
								<label class="col-md-9" id="label_pekerjaan_pasien"><?=$nama_pekerjaan?></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-2 bold"><?=translate("Alamat", $this->session->userdata("language"))?></label>
								<label class="col-md-1 bold">:</label>
								<label class="col-md-9" id="label_alamat_pasien"><?=$form_alamat.$form_kel_alamat.$form_kec_alamat.$form_kota_alamat?></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="col-md-12"><?=translate("Berdasarkan  hasil pemeriksaan, bahwa yang bersangkutan dalam keadaan : Sakit. Maka perlu Istirahat selama :", $this->session->userdata("language"))?> <label id="label_hari">...</label> hari. Terhitung mulai tanggal  <label id="label_tanggal_awal">.............</label> s/d <label id="label_tanggal_akhir">..........</label>. Demikian harap maklum.</label>
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




