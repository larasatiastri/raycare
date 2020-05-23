<?php
	$form_attr = array(
	    "id"            => "form_add_dokter_sppd", 
	    "name"          => "form_add_dokter_sppd", 
	    "autocomplete"  => "off",
	    "class"			=> "form-horizontal", 
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add",
        "id"		=> $pk_value
    );

    echo form_open(base_url()."klinik_hd/persetujuan_surat_sppd/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$pasien = $this->pasien_m->get($form_data['pasien_id']);
	$pasien_alamat = $this->pasien_alamat_m->get_by(array('pasien_id' => $form_data['pasien_id'], 'is_primary' => 1),true);

	$umur_pasien = date_diff(date_create($pasien->tanggal_lahir), date_create('today'))->y.' Tahun';

    if ($umur_pasien < 1) {
        $umur_pasien = translate('Dibawah 1 tahun', $this->session->userdata('language'));
    }
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-note font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("View Surat Pengantar HD 3x", $this->session->userdata("language"))?></span>
			<span class="caption-helper"><?php echo '<label class="control-label ">'.date('d M Y').'</label>'; ?></span>
		</div>
		<div class="actions">

            <?php $msg = translate("Apakah anda yakin akan menyetujui surat dokter sppd ini?",$this->session->userdata("language"));?>
				<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
					<i class="fa fa-mail-reply "></i>
					<?=translate("Kembali", $this->session->userdata("language"))?>
				</a>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="alert alert-danger display-hide">
	        <button class="close" data-close="alert"></button>
	        <?=$form_alert_danger?>
	    </div>
	    <div class="alert alert-success display-hide">
	        <button class="close" data-close="alert"></button>
	        <?=$form_alert_success?>
	    </div>
		<div class="form-body">
			<div class="row">
				<div class="col-md-5">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<?=translate("Form Input", $this->session->userdata("language"))?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="form-group">
								<div class="col-md-12">
									<label class="col-md-12"><?=translate("Dengan hormat,", $this->session->userdata("language"))?></label>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label class="col-md-12"><?=translate("Mohon penanganan tindakan Hemodialisis terhadap pasien :", $this->session->userdata("language"))?></label>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-12">
									<label class="col-md-2"><?=translate("No. RM", $this->session->userdata("language"))?></label>
									<label class="col-md-1">:</label>
									<label class="col-md-9"><?=$pasien->no_member?></label>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label class="col-md-2"><?=translate("Nama", $this->session->userdata("language"))?></label>
									<label class="col-md-1">:</label>
									<label class="col-md-9"><?=$pasien->nama?></label>
									
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label class="col-md-2"><?=translate("Umur", $this->session->userdata("language"))?></label>
									<label class="col-md-1">:</label>
									<label class="col-md-9"><?=$umur_pasien?></label>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label class="col-md-2"><?=translate("Alamat", $this->session->userdata("language"))?></label>
									<label class="col-md-1">:</label>
									<label class="col-md-9"><?=$pasien_alamat->alamat?></label>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label class="col-md-2"><?=translate("Diagnosis", $this->session->userdata("language"))?></label>
									<label class="col-md-1">:</label>
									<label class="col-md-9"><?=$form_data['diagnosa1']?></label>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label class="col-md-2"><?=translate("Diagnosis Tambahan", $this->session->userdata("language"))?></label>
									<label class="col-md-1">:</label>
									<label class="col-md-9"><?=$form_data['diagnosa2']?></label>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label class="col-md-12"><?=translate("Pasien merupakan Hemodialisis rutin 2x seminggu, dan dibutuhkan tindakan tambahan hemodialisis 3x seminggu untuk pasien karena ", $this->session->userdata("language"))?><?=$form_data['alasan']?></label>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-12">
									<label class="col-md-12"><?=translate("Jakarta, ", $this->session->userdata("language"))?><?=date('d M Y', strtotime($form_data['tanggal']))?></label>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				<div class="col-md-7">
		    		<div class="portlet light bordered" id="section-gambar">
		    			<div class="portlet-title">
		    				<div class="caption">
		    					<?=translate("Foto", $this->session->userdata("language"))?>
		    				</div>
		    			</div>
							<div class="form-body" >
							<div class="row">
								<?php
									if(count($form_data_gambar)){

										$i = 0;
										foreach ($form_data_gambar as $gambar) {

											if($gambar['url'] != '')
										    {
										        $url = explode('/', $gambar['url']); 
										        
										            $image_user = config_item('base_dir').config_item('site_img_sppd_dir').$gambar['surat_dokter_sppd_id'].'/'.$gambar['url'];
										        	$file_img = $gambar['url'];
										        
										    }
										    else
										    {
										        $image_user = config_item('base_dir').config_item('site_img_sppd_dir').'global/global.png';
										        $file_img = 'global.png';
										    }
											?>
											<div class="col-md-4">
											<div class="form-group">
												<div class="col-md-12">
													<ul class="ul-img" id="upload">
														<li class="working">
														<div class="thumbnail">
														<a class="fancybox-button" title="<?=$file_img?>" href="<?=$image_user?>" data-rel="fancybox-button"><img src="<?=$image_user?>" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;" ></a>
														</div>
														<span></span></li>
													</ul>
												</div>
											</div>
											</div>

											<?php

											$i++;
										}
									}
								?>
							</div>
						</div>
		    		</div>
		    	</div>
				<div class="col-md-12">
					<div class="portlet light bordered">
						<div class="portlet-title">
		    				<div class="caption">
		    					<?=translate("History Tindakan", $this->session->userdata("language"))?>
		    				</div>
		    			</div>
		    			<div class="portlet-body">
		    				<table class="table table-bordered table-striped table-hover" id="tabel_tindakan" style="font-size:12px;">
			    				<thead>
			    					<th>Tanggal</th>
			    					<th>BB Awal</th>
			    					<th>BB Akhir</th>
			    					<th>Waktu</th>
			    					<th>QB</th>
			    					<th>QD</th>
			    					<th>UFG</th>
			    				</thead>
			    				<tbody>
			    				<?php
			    					$tanggal = date('Y-m-d', strtotime($form_data['tanggal']));

									$data_tindakan = $this->tindakan_hd_m->get_data_tiga_bulan($form_data['pasien_id'], $tanggal)->result_array();

									foreach ($data_tindakan as $tindakan) {
										?>

											<tr>
												<td><?=date('D, d M Y', strtotime($tindakan['tanggal']))?></td>
												<td><?=$tindakan['berat_awal']?></td>
												<td><?=$tindakan['berat_akhir']?></td>
												<td><?=$tindakan['time_of_dialysis'].' Jam'?></td>
												<td><?=$tindakan['quick_of_blood']?></td>
												<td><?=$tindakan['quick_of_dialysis']?></td>
												<td><?=$tindakan['uf_goal']?></td>
											</tr>
										<?php
									}
								?>
			    				</tbody>
							</table>    
		    			</div>
					</div>
				</div>
				
			</div>
			
		
			
		</div>
	</div>
	
	<div class="portlet-body form">

		
		<?=form_close()?>
	</div>
</div>