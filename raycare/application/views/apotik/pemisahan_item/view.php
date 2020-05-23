<?php

	$form_attr = array(
	    "id"            => "form_add_gudang", 
	    "name"          => "form_add_gudang", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
		    echo form_open(base_url()."klinik_hd/surat_dokter_sppd/save", $form_attr);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="portlet light">
	<div class="portlet-body form">
		<div class="form-body">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption font-blue-sharp bold uppercase"><?=translate("Pemisahan Item", $this->session->userdata("language"))?></span>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
					<div class="form-body">
						<div class="alert alert-danger display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_danger?>
					    </div>
					    <div class="alert alert-success display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_success?>
					    </div>
					    <div class="form-group">
							<label class="control-label col-md-3"><?=translate("Gudang", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php
									$nama_gudang = array(
										"id"			=> "nama_gudang",
										"name"			=> "nama_gudang",
										"autofocus"			=> true,
										"class"			=> "form-control", 
										"readonly"		=> "readonly",
										"value"			=> "Gudang 1",
										"style"			=> "background-color: transparent;border: 0px solid;"

									);

									$id_gudang = array(
										"id"			=> "id_supplier",
										"name"			=> "id_supplier",
										"autofocus"			=> true,
										"class"			=> "form-control hidden",
										"placeholder"	=> translate("Pasien", $this->session->userdata("language"))
									);
									echo form_input($nama_gudang);
									echo form_input($id_gudang);
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php
									$tanggal = array(
										"id"			=> "tanggal_pecah",
										"name"			=> "tanggal_pecah",
										"autofocus"			=> true,
										"class"			=> "form-control",
										"readonly"		=> "readonly",
										"style"			=> "background-color: transparent;border: 0px solid;"

									);

									echo form_input($tanggal);
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
							<div class="col-md-3">
								<?php
									$subjek = array(
										"id"			=> "subjek",
										"name"			=> "subjek",
										"autofocus"			=> true,
										"class"			=> "form-control",
										"readonly"		=> "readonly",
										"style"			=> "background-color: transparent;border: 0px solid;"

									);

									echo form_input($subjek);
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
							<div class="col-md-3">
								<?php
									$keterangan = array(
										"id"			=> "keterangan",
										"name"			=> "keterangan",
										"autofocus"			=> true,
										"rows"			=> 5,
										"class"			=> "form-control",
										"placeholder"	=> translate("Keterangan", $this->session->userdata("language")),
										"readonly"		=> "readonly",
										"style"			=> "background-color: transparent;border: 0px solid;"

									);
									echo form_textarea($keterangan);
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Kode", $this->session->userdata("language"))?> :</label>
							<div class="col-md-1">
								<?php
									$kode_item = array(
										"id"			=> "kode_item",
										"name"			=> "kode_item",
										"autofocus"			=> true,
										"class"			=> "form-control", 
										"readonly"		=> "readonly",
										"style"			=> "background-color: transparent;border: 0px solid;"

									);

									echo form_input($kode_item);
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php
									$nama_item = array(
										"id"			=> "nama_item",
										"name"			=> "nama_item",
										"autofocus"			=> true,
										"class"			=> "form-control", 
										"readonly"		=> "readonly",
										"style"			=> "background-color: transparent;border: 0px solid;"

									);

									echo form_input($nama_item);
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="portlet">
				<div class="col-md-6">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption font-blue-sharp bold uppercase"><?=translate("Stok awal per satuan", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover" id="table_daftar_item">
								<thead>
								<tr class="heading">
									<th class="text-center hidden"><?=translate("ID", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
								</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-center">30</td>
										<td class="text-center">dus</td>
									</tr>
									<tr>
										<td class="text-center">25</td>
										<td class="text-center">box</td>
									</tr>
									<tr>
										<td class="text-center">5</td>
										<td class="text-center">strip</td>
									</tr>
									<tr>
										<td class="text-center">30</td>
										<td class="text-center">tablet</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption font-blue-sharp bold uppercase"><?=translate("Rumus konversi", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover" id="table_daftar_item">
								<thead>
								<tr class="heading">
									<th class="text-center hidden"><?=translate("ID", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Operasional", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
								</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-center">1</td>
										<td class="text-center">dus</td>
										<td class="text-center">=</td>
										<td class="text-center">10</td>
										<td class="text-center">box</td>
									</tr>
									<tr>
										<td class="text-center">1</td>
										<td class="text-center">box</td>
										<td class="text-center">=</td>
										<td class="text-center">20</td>
										<td class="text-center">strip</td>
									</tr>
									<tr>
										<td class="text-center">1</td>
										<td class="text-center">strip</td>
										<td class="text-center">=</td>
										<td class="text-center">25</td>
										<td class="text-center">tablet</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption font-blue-sharp bold uppercase"><?=translate("Proses Konversi", $this->session->userdata("language"))?></span>
				</div>
			</div>
			<div class="portlet-body">

				<table class="table table-striped table-bordered table-hover" id="table_history">
					<thead>
					<tr class="heading">
						<th class="text-center hidden"><?=translate("ID", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("No.", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center">1</td>
							<td class="text-center">1</td>
							<td class="text-center">dus</td>
							<td class="text-center">konversi</td>
							<td class="text-center">10</td>
							<td class="text-center">box</td>
							<td></td>
						</tr>
						<tr>
							<td class="text-center">2</td>
							<td class="text-center">2</td>
							<td class="text-center">box</td>
							<td class="text-center">konversi</td>
							<td class="text-center">20</td>
							<td class="text-center">strip</td>
							<td></td>
						</tr>
						<tr>
							<td class="text-center">3</td>
							<td class="text-center">5</td>
							<td class="text-center">dus</td>
							<td class="text-center">konversi</td>
							<td class="text-center">25000</td>
							<td class="text-center">tablet</td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption font-blue-sharp bold uppercase"><?=translate("Stok akhir asli dikonversi", $this->session->userdata("language"))?></span>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table_history">
					<thead>
					<tr class="heading">
						<th class="text-center hidden"><?=translate("ID", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
					</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center">30</td>
							<td class="text-center">dus</td>
						</tr>
						<tr>
							<td class="text-center">25</td>
							<td class="text-center">box</td>
						</tr>
						<tr>
							<td class="text-center">5</td>
							<td class="text-center">strip</td>
						</tr>
						<tr>
							<td class="text-center">30</td>
							<td class="text-center">tablet</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
			  <?php $msg= translate("Apakah anda yakin akan menyimpan Pemisahan Item ini?", $this->session->userdata("language"));?>
			<div class="form-actions fluid">	
				<div class="col-md-offset-1 col-md-9">
					<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
				</div>		
			</div>	
		</div>
	</div>
</div>
