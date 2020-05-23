<div class="portlet light">
	<div class="portlet-body form">
	 
			<div class="row">
				<div class="col-md-6">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Umum", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="row">
						<div class="col-md-9">
							<div class="portlet-body form">
							<div class="form-body">
								 
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("No. Pasien", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label" id="data_no_member"></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Keterangan Daftar", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label"  id="data_keterangan"> </label>
									</div>
								</div>
 								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Nama Lengkap", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label" id="data_nama"> </label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Tempat, Tanggal Lahir", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										<label class="control-label" id="data_tempat_lahir"> </label> 
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Agama", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label" id="data_agama"> </label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Golongan Darah", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										  <label class="control-label" id="data_nama2"> </label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Status", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										  <label class="control-label" id="data_status"> </label>
									</div>
								</div>
								 
								
						</div>	
					</div>
						</div>
						<div class="col-md-3">
							<div class="col-md-3">
									<table width="100px" border="1" style="border-color:black">
										<tr>
											<td id="imgpasien2" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;width:100px;height:100px"> </td>
										</tr>
										</table>
							</div>
						</div>
						</div>
						
				</div>
				</div>
				<div class="col-md-6">
					<div class="portlet light" id="section-telepon">
						 <div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Telepon", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="form-body" id="data_telepon">
							 	<div class="form-group">
									<label class="control-label col-md-3"> </label>
									
									<div class="col-md-4">
										 <label class="control-label"> </label>
									</div>
								</div>
							</div>	
						</div>
					</div>
				</div>
					
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="portlet light" id="section-alamat">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Data Surat Kelayakan Anggota', $this->session->userdata('language'))?></span>
							</div>
							 
						</div>
						<input type="hidden" id="pk" name="pk" >
						<div class="portlet-body">
							<div class="form-body form">
								 
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kode Cabang", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label" id="data_kodecabang"></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kode Rumah Sakit Rujukan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"  id="data_rujukan"></label>
									</div>
								</div>
 								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Tanggal Rujukan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label" id="data_tgglrujukan"></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Nomor Rujukan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										  <label class="control-label" id="data_nomorrujukan"></label>
									</div>
								</div>
							 
								
						</div>	
						</div>
					</div>

					 
				</div>

				<div class="col-md-6">
					<div class="portlet light" id="section-alamat">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Alamat', $this->session->userdata('language'))?></span>
							</div>
							 
						</div>
						<input type="hidden" id="pk" name="pk" >
						<div class="portlet-body">
							<div class="form-body form">
								 
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label" id="data_nama_subjek"></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label" id="data_alamat"></label>
									</div>
								</div>
 								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("RT / RW", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label" id="data_rt_rw"></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kel / Desa", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										  <label class="control-label" id="data_kelurahan"></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kecamatan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										  <label class="control-label" id="data_kecamatan"></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kota", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										  <label class="control-label" id="data_kota"></label>
									</div>
								</div>
							 
								
						</div>	
						</div>
					</div>

					 
				</div>
				 
		</div>

		<div class="row">
				<div class="col-md-6">
					<div class="portlet light" id="section-alamat">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Informasi Lain', $this->session->userdata('language'))?></span>
							</div>
							 
						</div>
						<input type="hidden" id="pk" name="pk" >
						<div class="portlet-body">
							<div class="form-body form">
								 
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Dokter Pengirim", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label" id="data_dokter_pengirim"></label>
									</div>
								</div>
								 <div class="form-group">
									<label class="control-label col-md-4"><?=translate("Frekuensi Perawatan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label" id="data_frekuensi_perawatan"></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Penyakit Bawaan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4" id="data_penyakit_bawaan"> 
										 
										  	<label class="control-label"></label>
										 
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Penyakit Penyebab", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"  id="data_penyakit_penyebab">
										 
										  	<label class="control-label"></label>
										 
									</div>
								</div>
								 
								
						</div>	
						</div>
					</div>

					 
				</div>
				<div class="col-md-6">
					<div class="portlet light" id="section-alamat">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Dokumen Pelengkap', $this->session->userdata('language'))?></span>
							</div>
							<div class="actions" id="act1">
	            					<a id="histori" class="btn btn-primary">
	                				<i class="fa fa-plus"></i>
	                				<span class="hidden-480">
	                     				<?=translate("Histori", $this->session->userdata("language"))?>
	                				</span>
	            				</a>
	        				</div>
	        				<div class="actions" id="act2" style="display:none">
	            					<a id="kembali" class="btn btn-primary">
	                				<i class="fa fa-plus"></i>
	                				<span class="hidden-480">
	                     				<?=translate("Kembali", $this->session->userdata("language"))?>
	                				</span>
	            				</a>
	        				</div>
						</div>
						<div class="portlet-body">
							<ul class="nav nav-tabs">
							<li  class="active">
							<a href="#tindakan1" data-toggle="tab" id="tab1">
									<?=translate('Pelengkap', $this->session->userdata('language'))?> </a>
							</li>
 
							<li >
								<a href="#rujukan1" data-toggle="tab" id="tab2">
									<?=translate('Rekam Medis', $this->session->userdata('language'))?> </a>
							</li>
							 
						</ul>
					<div class="tab-content">
							<div class="tab-pane active" id="tindakan1" >
								<?php include('tab_data_pasien/paket.php') ?>
							</div>
							 
							<div class="tab-pane" id="rujukan1">
								<?php include('tab_data_pasien/paket2.php') ?>
							</div>
							 
					</div>
						</div>
					</div>
 
			</div>
		</div>

		<?php $msg = translate("Apakah anda yakin akan membuat data pasien ini?",$this->session->userdata("language"));?>
	 
	 
	</div>
</div>
 




