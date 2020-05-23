<?php
	$form_attr = array(
	    "id"            => "form_view_hasil_lab", 
	    "name"          => "form_view_hasil_lab", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);

    $pasien = $this->pasien_m->get_by(array('id' => $data_hasil_lab['pasien_id']), true);
    $pasien_alamat = $this->pasien_alamat_m->get_by(array('pasien_id' => $data_hasil_lab['pasien_id'], 'is_primary' => 1, 'is_active' => 1), true);
    $pasien_telepon = $this->pasien_telepon_m->get_by(array('pasien_id' => $data_hasil_lab['pasien_id'], 'is_primary' => 1, 'is_active' => 1), true);
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-list font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Hasil Cek Laboratorium ", $this->session->userdata("language")).' '.$lab_klinik['nama']?></span>
		</div>
			</div>
	<div class="portlet-body form">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject uppercase">
				      <?=translate("Informasi", $this->session->userdata("language"))?>
				    </span>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-2 bold"><?=translate("No. Lab", $this->session->userdata("language"))?> :</label>
							<label class="col-md-2 text-left"><?=$data_hasil_lab['no_hasil_lab']?></label>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 bold"><?=translate("Tanggal", $this->session->userdata("language"))?> : </label>
							<label class="col-md-2 text-left"><?=date('d/m/Y', strtotime($data_hasil_lab['tanggal']))?></label>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 bold"><?=translate("Pengirim", $this->session->userdata("language"))?> : </label>
							<label class="col-md-2">Klinik Raycare</label>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 bold"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
							<label class="col-md-2"><?=$data_hasil_lab['dokter']?></label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-2 bold"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
							<label class="col-md-4"><?=$pasien->nama?></label>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 bold"><?=translate("Kelamin", $this->session->userdata("language"))?> : </label>
							<label class="col-md-4"><?=($pasien->gender == 'L')?'Laki - laki' : 'Perempuan'?></label>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 bold"><?=translate("Usia", $this->session->userdata("language"))?> :</label>
							<label class="col-md-4"><?=$data_hasil_lab['usia']?></label>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 bold"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
							<label class="col-md-4"><?=$pasien_alamat->alamat?></label>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 bold"><?=translate("Telepon", $this->session->userdata("language"))?> :</label>
							<label class="col-md-4"><?=$pasien_telepon->nomor?></label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject uppercase">
				      <?=translate("Detail Pemeriksaan", $this->session->userdata("language"))?>
				    </span>
				</div>
				
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-hover" id="table_detail_periksan">
					<thead>
						<tr>
							<th class="text-left" ><?=translate("Pemeriksaan", $this->session->userdata("language"))?> </th>
							<th class="text-left" width="2%"><?=translate("Hasil", $this->session->userdata("language"))?></th>
							<th class="text-left" width="10%"><?=translate("Nilai Normal", $this->session->userdata("language"))?></th>
							<th class="text-left" width="2%"><?=translate("Satuan", $this->session->userdata("language"))?></th>
							<th class="text-left" width="15%"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
						</tr>
					</thead>

					<tbody>
					<?php
						foreach ($data_hasil_lab_detail as $key => $detail) {

							$periksa_lab = $this->pemeriksaan_lab_m->get_by(array('id' => $detail['pemeriksaan_lab_id']), true);
							$periksa_lab_detail = $this->pemeriksaan_lab_detail_m->get_by(array('id' => $detail['pemeriksaan_lab_detail_id']), true);
					?>
						<tr>
							<td><?=$detail['pemeriksaan']?></td>
							<td><?=$detail['hasil']?></td>
							<td><?=$detail['nilai_normal']?></td>
							<td><?=$detail['satuan']?></td>
							<td><?=$detail['keterangan']?></td>
						</tr>
					<?php
						}
					?>
					</tbody>
				</table>

				<br>
				<div id="gambar_lab">
				<?php
					foreach ($data_hasil_lab_dokumen as $key => $dokumen) :
				?>
					<ul class="list-inline blog-images" style="display: inline;" id="upload_sign">               
						<li>                
						<a class="fancybox-button" target="_blank" title="<?=$dokumen['url']?>" href="<?=config_item('base_dir')?>cloud/<?=config_item('site_dir')?>pages/tindakan/input_hasil_lab_manual/images/<?=$dokumen['hasil_lab_id']?>/<?=$dokumen['url']?>" data-rel="fancybox-button"><img src="<?=config_item('base_dir')?>cloud/<?=config_item('site_dir')?>pages/tindakan/input_hasil_lab_manual/images/<?=$dokumen['hasil_lab_id']?>/<?=$dokumen['url']?>" alt="Tidak ditemukan"></a> <span></span>
						</li> 
					</ul>
				<?php
					endforeach;				
				?>
				</div>
			</div>
		</div>

		<div class="form-actions right">
			<a class="btn btn-circle btn-default" href="<?=base_url()?>tindakan/input_hasil_lab_manual">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
		</div>

	</div>
</div>
<?=form_close()?>