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
										 <label class="control-label"><?=$form_data5[0]['no_member']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Tanggal Daftar", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label"><?=date('d-M-Y', strtotime($form_data5[0]['tanggal_daftar']))?></label>
									</div>
								</div>
 								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Nama Lengkap", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label"><?=$form_data5[0]['nama']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Tempat, Tanggal Lahir", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										<label class="control-label"><?=$form_data5[0]['tempat_lahir']?></label>,  <label class="control-label"><?=date('d M Y',strtotime($form_data5[0]['tanggal_lahir']))?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Agama", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label"><?=$form_data5[0]['nama1']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Golongan Darah", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										  <label class="control-label"><?=$form_data5[0]['nama2']?></label>
									</div>
								</div>
								 
								
						</div>	
					</div>
						</div>
						<?php
							$url = array();
				            if ($form_data3[0]['url_photo'] != '' && $form_data3[0]['url_photo'] != 'global.png') 
				            {
				                $url = explode('/', $form_data3[0]['url_photo']);
				                // die(dump($row['url_photo']));
				                if (file_exists(FCPATH.config_item('site_img_pasien').$form_data5[0]['no_member'].'/foto/'.$form_data3[0]['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$form_data5[0]['no_member'].'/foto/'.$form_data3[0]['url_photo'])) 
				                {
				                    $img_url = base_url().config_item('site_img_pasien').$form_data5[0]['no_member'].'/foto/'.$form_data3[0]['url_photo'];
				                }
				                else
				                {
				                    $img_url = base_url().config_item('site_img_pasien').'global/global.png';
				                }
				            } else {

				                $img_url = base_url().config_item('site_img_pasien').'global/global.png';
				            }

						?>
						<div class="col-md-3">
							<div class="col-md-3">
										<table width="100px" border="1" style="border-color:black">
									<tr>
										<td style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px">
										<img src="<?=$img_url?>" style="max-width:100px">
										</td>
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
							<div class="form-body">
							 
							    <?foreach ($form_data6 as $row) {?>
							    	 
								<div class="form-group">
									<label class="control-label col-md-3"><?=$form_data6[0]['nama']?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=$form_data6[0]['nomor']?></label>
									</div>
								</div>
								<?}?>
								 
								
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
						<input type="hidden" id="pk" name="pk" value="<?=$pk?>">
						<div class="portlet-body">
							<div class="form-body form">
								 
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kode Cabang", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=$form_data5[0]['kodecabang']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kode Rumah Sakit Rujukan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=$form_data5[0]['rujukan']?></label>
									</div>
								</div>
 								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Tanggal Rujukan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=date('d M Y',strtotime($form_data5[0]['tgglrujukan']))?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Nomor Rujukan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										  <label class="control-label"><?=$form_data5[0]['nomorrujukan']?></label>
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
						<input type="hidden" id="pk" name="pk" value="<?=$pk?>">
						<div class="portlet-body">
							<div class="form-body form">
								 <?php
								 	$rt_rw = explode('_', $form_data7[0]['rt_rw']);
								 ?>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=$form_data7[0]['nama']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label"><?=$form_data7[0]['alamat']?></label>
									</div>
								</div>
 								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("RT / RW", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=$rt_rw[0].'/'.$rt_rw[1]?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kel / Desa", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										  <label class="control-label"><?=(count($form_data8) != 0)?$form_data8[0]['kelurahan']:'-'?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kecamatan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										  <label class="control-label"><?=(count($form_data8) != 0)?$form_data8[0]['kecamatan']:'-'?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kota", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										  <label class="control-label"><?=(count($form_data8) != 0)?$form_data8[0]['kota']:'-'?></label>
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
						<input type="hidden" id="pk" name="pk" value="<?=$pk?>">
						<div class="portlet-body">
							<div class="form-body form">
								 
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Dokter Pengirim", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=$form_data5[0]['dokter_pengirim']?></label>
									</div>
								</div>
								 
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Penyakit Bawaan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										<? foreach($form_data9 as $row){?>
										  <label class="control-label"><?if($row['tipe']==1){?><?=$row['nama']?><?}?></label>
										 <?}?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Penyakit Penyebab", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <? foreach($form_data9 as $row){?>
										  <label class="control-label"><?if($row['tipe']==2){?><?=$row['nama']?><?}?></label>
										 <?}?>
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
						</div>
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover" id="table_dokumen_pelengkap1">
								<thead>
									<tr role="row" class="heading">
										<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="8%"><?=translate("Hasil Lab", $this->session->userdata("language"))?> </th>
									</tr>
								</thead>
								<tbody>
								
								</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>

		<?php $msg = translate("Apakah anda yakin akan membuat data pasien ini?",$this->session->userdata("language"));?>
	 
	 
	</div>
</div>
 




