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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Hasil Cek Laboratorium ", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-primary" id="btn_lab_prev">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Sebelumnya", $this->session->userdata("language"))?>
			</a>
			<a class="btn btn-circle btn-primary" id="btn_lab_next">
				<?=translate("Selanjutnya", $this->session->userdata("language"))?>
				<i class="fa fa-chevron-right"></i>
			</a>
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
					<div class="col-md-12">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Tanggal", $this->session->userdata("language"))?> : </label>
								<label class="col-md-12" id="label_tgl_lab">-</label>
								<input type="hidden" name="tanggal_sekarang" id="tanggal_sekarang" value="<?=date('Y-m-d')?>"></input>
								<input type="hidden" name="tanggal_lab" id="tanggal_lab" value=""></input>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("No. Lab", $this->session->userdata("language"))?> :</label>
								<label class="col-md-12 text-left" id="label_nomor_lab">-</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Lab Klinik", $this->session->userdata("language"))?> : </label>
								<label class="col-md-12 text-left" id="label_klinik_lab">-</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
								<label class="col-md-12 text-left" id="label_dokter_lab">-</label>
							</div>
						</div>
						<div class="table-scrollable">
								<table class="table table-striped table-hover" id="table_detail_periksa">
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
									
									</tbody>
								</table>
						</div>
				<br>
				<div id="gambar_lab">
				
				</div>
					</div>
					
				</div>
			</div>
		</div>
		
	</div>
</div>
<?=form_close()?>