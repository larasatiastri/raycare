<div class="portlet light">
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_pasien", 
			    "name"          => "form_add_pasien", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."master/pasien/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');
		?>

		  
	 
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
								
								<div class="form-group hidden">
									<label class="control-label col-md-5"><?=translate("ID", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										<input class="form-control" value="<?=$form_pasien['id']?>" id="id_pasien" name="id_pasien">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("No. Pasien", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label"><?=$form_pasien['no_member']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Keterangan Daftar", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label"><?=$form_pasien['keterangan']?></label>
									</div>
								</div>
 								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Nama Lengkap", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label"><?=$form_pasien['nama']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Tempat, Tanggal Lahir", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										<label class="control-label"><?=$form_pasien['tempat_lahir']?></label>,  <label class="control-label"><?=date('d M Y',strtotime($form_pasien['tanggal_lahir']))?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Agama", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label"><?=$form_agama['nama']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Golongan Darah", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										  <label class="control-label"><?=($form_goldar != '')?$form_goldar['nama']:'Tidak Diketahui'?></label>
									</div>
								</div>
								 
								
						</div>	
					</div>
						</div>
						<?php
							$url = array();
				            if ($form_pasien['url_photo'] != '') 
				            {
				                if (file_exists(FCPATH.config_item('site_img_pasien').$form_pasien['no_member'].'/foto/'.$form_pasien['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$form_pasien['no_member'].'/foto/'.$form_pasien['url_photo'])) 
				                {
				                    $img_url = base_url().config_item('site_img_pasien').$form_pasien['no_member'].'/foto/'.$form_pasien['url_photo'];
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
								<img class="img-thumbnail" src="<?=$img_url?>" style="max-width:100px">
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
								<div class="alert alert-danger display-hide">
							        <button class="close" data-close="alert"></button>
							        <?=$form_alert_danger?>
							    </div>
							    <div class="alert alert-success display-hide">
							        <button class="close" data-close="alert"></button>
							        <?=$form_alert_success?>
							    </div>
							    	 
								<div class="form-group">
									<label class="control-label col-md-3"><?=(isset($form_sbjk_tlp['nama']))?$form_sbjk_tlp['nama']:'-'?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=(count($form_tlp))?$form_tlp[0]['nomor']:'-'?></label>
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
						<div class="portlet-body">
							<div class="form-body form">
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kode Cabang", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=$form_pasien['ref_kode_cabang']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kode Rumah Sakit Rujukan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=$form_pasien['ref_kode_rs_rujukan']?></label>
									</div>
								</div>
 								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Tanggal Rujukan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=($form_pasien['ref_tanggal_rujukan'] != NULL || $form_pasien['ref_tanggal_rujukan'] != '')?date('d M Y',strtotime($form_pasien['ref_tanggal_rujukan'])):'-'?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Nomor Rujukan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										  <label class="control-label"><?=$form_pasien['ref_nomor_rujukan']?></label>
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
						<div class="portlet-body">
						<?php
							$rt_rw = explode('_', $form_alamat[0]['rt_rw']);
						?>
							<div class="form-body form">
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=(isset($form_sbjk_alamat['nama']))?$form_sbjk_alamat['nama']:'-'?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-7">
										 <label class="control-label"><?=$form_alamat[0]['alamat']?></label>
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
										  <label class="control-label"><?=($form_kel_alamat != '')?$form_kel_alamat:'-'?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kecamatan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										  <label class="control-label"><?=($form_kec_alamat != '')?$form_kec_alamat:'-' ?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kota", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										  <label class="control-label"><?=($form_kota_alamat != '')?$form_kota_alamat:'-' ?></label>
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
						<div class="portlet-body">
							<div class="form-body form">
								
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Dokter Pengirim", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <label class="control-label"><?=$form_pasien['dokter_pengirim']?></label>
									</div>
								</div>
								 
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Penyakit Bawaan", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4"> 
										<? foreach($form_penyakit as $row){?>
										  <label class="control-label"><?if($row['tipe']==1){?><?=$row['nama']?>,<?}?></label>
										 <?}?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Penyakit Penyebab", $this->session->userdata("language"))?> :</label>
									
									<div class="col-md-4">
										 <? foreach($form_penyakit as $row){?>
										  <label class="control-label"><?if($row['tipe']==2){?><?=$row['nama']?>,<?}?></label>
										 <?}?>
									</div>
								</div>
							</div>	
						</div>
					</div>

					 
				</div>
				<div class="col-md-6 hidden">
					<div class="portlet light" id="section-alamat">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Dokumen Pelengkap', $this->session->userdata('language'))?></span>
							</div>
							<div class="actions" id="act1">
	            					<a id="histori" class="btn btn-primary">
	                				<i class="fa fa-book"></i>
	                				<span class="hidden-480">
	                     				<?=translate("History", $this->session->userdata("language"))?>
	                				</span>
	            				</a>
	        				</div>
	        				<div class="actions" id="act2" style="display:none">
	            					<a id="kembali" class="btn grey-cascade">
	                				<i class="fa fa-share"></i>
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
	 
		<?=form_close()?>
	</div>
</div>
 
<form id="modalpic" name="modalpic" autocomplete="off">
    <?
        $msg = translate("Apakah anda yakin akan menambah dokumen ini?",$this->session->userdata("language"));
    ?>
    <div class="modal fade" id="ajax_notes2" role="basic" aria-hidden="true" style="display:none">
        <div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-body">
                    <div class="row">
                            <div class="form-group">
        					 	<div id="gambar" align="center"></div>
        			        </div>
                      
                        </div>
                    </div>
                    <div class="modal-footer">
                    <div class="form-actions fluid">	
    				    <div class="col-md-12">
    				    	 
                            <button type="reset" class="btn default" data-dismiss="modal"><?=translate("Ok", $this->session->userdata("language"))?></button>
                             
        		      	</div>		
                    </div>
                    <!-- <button type="button" class="btn default" data-dismiss="modal">Simpan</button> -->
                </div>
 
                </div>
                 
            </div>

        </div>
   
</form>



