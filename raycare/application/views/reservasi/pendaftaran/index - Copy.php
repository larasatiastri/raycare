<?php
	$form_attr = array(
	    "id"            => "form_pendaftaran_tindakan", 
	    "name"          => "form_pendaftaran_tindakan", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."reservasi/pendaftaran_tindakan/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-cursor font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pendaftaran Rawat Jalan", $this->session->userdata("language"))?></span>
			<span class="caption-helper"><?php echo '<label class="control-label current_time">'.date('d M Y').'</label>'; ?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-default tambah-pasien" href="<?=base_url()?>master/pasien/add">
				<i class="fa fa-plus"></i>
				<?=translate('Registrasi Pasien', $this->session->userdata('language'))?>
			</a>

			<?php $msg = translate("Apakah anda yakin akan membuat pendaftaran tindakan ini?",$this->session->userdata("language"));?>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?>
			</a>
            <button type="submit" id="save" class="btn default hidden" >
            	
            	<?=translate("Simpan", $this->session->userdata("language"))?>
            </button>
		</div>
	</div>
	<input type="hidden" id="cabangid" name="cabangid" value="<?=$this->session->userdata('cabang_id')?>">
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

					 
							<div class="portlet light">
									<div class="portlet-title tabbable-line">
										<div class="caption">
											<?=translate("Informasi", $this->session->userdata("language"))?></span>
										</div>
										<ul class="nav nav-tabs">
											<li class="active">
												<a href="#tab_2_1" data-toggle="tab" id="daftar">
												Daftar </a>
											</li>
											<li>
												<a href="#tab_2_2" data-toggle="tab" class="hidden" id="rujukan ">
												Rujukan <span class='badge badge-primary hidden' id="notifrujukan" style='width:15px;height:15px'></span> </a>
											</li>
											<li>
												<a href="#tab_2_3" data-toggle="tab" class="hidden" id="bayar">
												Bayar <span class='badge badge-info hidden' id="notifbayar" style='width:15px;height:15px'></span></a>
											</li>
											<li>
												<a href="#tab_2_4" data-toggle="tab" class="hidden" id="klaim">
												 Klaim <span class='badge badge-warning hidden' id="notifklaim" style='width:15px;height:15px'></span>
														 
													 
												</a>
											</li>
											
											<li>
												<a href="#tab_2_5" data-toggle="tab" class="hidden" id="upload">
												Upload <span class='badge badge-danger hidden' id="notifupload" style='width:15px;height:15px'></span></a>
											</li>
											<li>
												<a href="#tab_2_6" data-toggle="tab" class="hidden" id="jadwal">
												Jadwal </a>
											</li>
											<li>
												<a href="#tab_2_7" data-toggle="tab" class="hidden" id="pasien">
													 
												Pasien
														 
												 </a>
											</li>
										</ul>
									</div>
								<div class="portlet-body form">
							<div class="tab-content">
								<div class="tab-pane fade active in" id="tab_2_1">
									<div class="portlet-body">
										<div class="row">
											
											<div class="col-md-6">
													<div class="portlet light bordered">
												<div class="row">
													<div class= "col-md-8">
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
																<div class="col-md-8">
																	<?php
																		$nama_pasien = array(
																			"id"			=> "nama_pasien",
																			"name"			=> "nama_pasien",
																			"autofocus"			=> true,
																			"class"			=> "form-control", 
																			"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
																			"value"			=> $flash_form_data['nama_pasien'],
																			"help"			=> $flash_form_data['nama_pasien'],
																			"readonly"		=> "readonly",
																		);

																		echo form_input($nama_pasien);
																	?>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
																<div class="col-md-8">
																	<?php
																		$alamat_pasien = array(
																			"id"			=> "alamat_pasien",
																			"name"			=> "alamat_pasien",
																			"autofocus"			=> true,
																			"class"			=> "form-control", 
																			"placeholder"	=> translate("Alamat", $this->session->userdata("language")), 
																			"value"			=> $flash_form_data['alamat_pasien'],
																			"help"			=> $flash_form_data['alamat_pasien'],
																			"readonly"		=> "readonly",
																		);

																		echo form_input($alamat_pasien);
																	?>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Gender", $this->session->userdata("language"))?> :</label>
																<div class="col-md-8">
																	<?php
																		$gender_pasien = array(
																			"id"			=> "gender_pasien",
																			"name"			=> "gender_pasien",
																			"autofocus"			=> true,
																			"class"			=> "form-control", 
																			"placeholder"	=> translate("Gender", $this->session->userdata("language")), 
																			"value"			=> $flash_form_data['gender_pasien'],
																			"help"			=> $flash_form_data['gender_pasien'],
																			"readonly"		=> "readonly",
																		);

																		echo form_input($gender_pasien);
																	?>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Tempat, Tgl Lahir", $this->session->userdata("language"))?> :</label>
																<div class="col-md-8">
																	<?php
																		$tgl_lahir_pasien = array(
																			"id"			=> "tgl_lahir_pasien",
																			"name"			=> "tgl_lahir_pasien",
																			"autofocus"			=> true,
																			"class"			=> "form-control", 
																			"placeholder"	=> translate("Tempat, Tgl Lahir", $this->session->userdata("language")), 
																			"value"			=> $flash_form_data['tgl_lahir_pasien'],
																			"help"			=> $flash_form_data['tgl_lahir_pasien'],
																			"readonly"		=> "readonly",
																		);

																		echo form_input($tgl_lahir_pasien);
																	?>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Umur", $this->session->userdata("language"))?> :</label>
																<div class="col-md-8">
																	<?php
																		$umur = array(
																			"id"			=> "umur",
																			"name"			=> "umur",
																			"autofocus"			=> true,
																			"class"			=> "form-control", 
																			"placeholder"	=> translate("Umur", $this->session->userdata("language")), 
																			"value"			=> $flash_form_data['umur'],
																			"help"			=> $flash_form_data['umur'],
																			"readonly"		=> "readonly",
																		);

																		echo form_input($umur);
																	?>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-md-4"><?=translate("Telepon", $this->session->userdata("language"))?> :</label>
																<div class="col-md-8">
																	<?php
																		$telepon_pasien = array(
																			"id"			=> "telepon_pasien",
																			"name"			=> "telepon_pasien",
																			"autofocus"			=> true,
																			"class"			=> "form-control", 
																			"placeholder"	=> translate("Telepon", $this->session->userdata("language")), 
																			"value"			=> $flash_form_data['telepon_pasien'],
																			"help"			=> $flash_form_data['telepon_pasien'],
																			"readonly"		=> "readonly",
																		);

																		echo form_input($telepon_pasien);
																	?>
																</div>
															</div>
														</div>

														<div class="col-md-4">
															<div class="col-md-3">
															<table width="100px" border="1" style="border:0px">
															<tr>
																<td id="imgpasien" style="padding-left:5px;padding-top:5px;padding-right:5px;padding-bottom:5px;width:100px;height:100px;border:0px;"> </td>
															</tr>
															</table>
														</div>
														</div>
													</div>
												 </div>
												

											</div>
									 		<div class="col-md-6">
									 			<div class="portlet light bordered">
													<div class="form-group">
														<label class="control-label col-md-4"><?=translate("No. Rekam Medik", $this->session->userdata("language"))?> :</label>
														<div class="col-md-8">													
															<div class="input-group">
															<?php
																$nama_ref_pasien = array(
																	"id"			=> "no_member",
																	"name"			=> "no_member",
																	"autofocus"			=> true,
																	"class"			=> "form-control", 
																	"placeholder"	=> translate("No. Rekam Medik", $this->session->userdata("language")), 
																	"value"			=> $flash_form_data['nama_ref_pasien'],
																	"help"			=> $flash_form_data['nama_ref_pasien'],
																	// "readonly"			=> "readonly",
																	"required"		=>"required"
																);

																$id_ref_pasien = array(
																	"id"			=> "id_pasien",
																	"name"			=> "id_pasien",
																	"autofocus"			=> true,
																	"class"			=> "form-control hidden", 
																	"placeholder"	=> translate("ID Referensi Pasien", $this->session->userdata("language")), 
																	"value"			=> $flash_form_data['id_ref_pasien'],
																	"help"			=> $flash_form_data['id_ref_pasien'],
																	"required"		=>"required"
																);
																echo form_input($nama_ref_pasien);
																echo form_input($id_ref_pasien);
															?>
																<span class="input-group-btn">
																	<a class="btn btn-primary pilih-pasien" title="<?=translate('Pilih Pasien', $this->session->userdata('language'))?>">
																		<i class="fa fa-search"></i>
																	</a>
																</span>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-md-4"><?=translate("Kode Poli", $this->session->userdata("language"))?> :</label>
														<div class="col-md-8">
															<?php
																$cabang_id = $this->session->userdata('cabang_id');
																$poliklinik = $this->poliklinik_m->getdataall($cabang_id);
																$poliklinik_sub_option = $poliklinik->result_array();
																$sub_option = array('' => "Pilih..");
															    foreach ($poliklinik_sub_option as $select) {
															        $sub_option[$select['id']] = $select['nama'];
															    }

																echo form_dropdown('poliklinik', $sub_option, '', "id=\"poliklinik_sub\" class=\"form-control\" required=\"required\" ");
															?>
														</div>
														<div class="col-md-2">
															<label class="control-label font-blue-sharp" id="status" ><div id="status2"></div></label>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-md-4"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
														<div class="col-md-8">
															<?php
																$sub = array();
																echo form_dropdown('dokter', $sub, '', "id=\"dokter\" class=\"form-control\" required=\"required\" ");
															?>
														</div>
													</div>
													<div class="form-group" id="ant" style="display:none">
														<label class="control-label col-md-4"><?=translate("No. Antrian", $this->session->userdata("language"))?> :</label>
														<div class="col-md-8">
															 <div id="noantrian"></div>
															 <input type="hidden" id="noantrianval" name="noantrianval">
														</div>
													</div>

													<div style="margin-bottom:20px"></div>
												 
													<div class="row" id="ant2" style="display:none">	
														<div class="col-md-12">
															<table class="table table-striped table-bordered table-hover table-condensed" id="table_antrian">
															<thead>
															<tr>
																<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
																<th class="text-center"><?=translate("Antrian", $this->session->userdata("language"))?> </th>
														 
															</tr>
															</thead>
															<tbody>
													
															</tbody>
															</table>
												 		</div>
													 </div>
												 </div>
											</div><!-- AKHIR DARI COL-MD-6 -->
									 
								</div>
								</div>
								</div>
								<div class="tab-pane fade" id="tab_2_2">
									<div class="portlet-body">
										 <?php include('tab_pendaftaran_tindakan/rujukan.php') ?>
									</div>
								</div>
								<div class="tab-pane fade" id="tab_2_3">
									<div class="portlet-body">
										<table class="table table-striped table-bordered table-hover table-condensed" id="table_pembayaran">
										<thead>
										<tr role="row">
											<th class="text-center"><?=translate("Transaksi", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("Poliklinik", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("Rupiah", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("Status", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("Status", $this->session->userdata("language"))?> </th>
										</tr>
										</thead>
										<tbody>
										 
										</tbody>
										</table>
									</div>
								</div>
								<div class="tab-pane fade" id="tab_2_4">
									<div class="portlet-body">
									 <?php include('tab_pendaftaran_tindakan/klaim.php') ?>
									</div>
								</div>
								<div class="tab-pane fade" id="tab_2_5">
									<?php include('tab_pendaftaran_tindakan/upload.php') ?>
									 
								</div>
								<div class="tab-pane fade" id="tab_2_6">
									<?php include('tab_pendaftaran_tindakan/jadwal.php') ?>
								</div>
								<div class="tab-pane fade" id="tab_2_7">
									<?php include('tab_pendaftaran_tindakan/data.php') ?>
								</div>

								
							</div>
						</div>
					</div>
  
		<?=form_close()?>
	</div>
	</div>
</div>

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No Member', $this->session->userdata('language'))?></div></th>
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

<div class="modal fade" id="ajax_notes" role="basic" aria-hidden="true" style="display:none">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">               
        </div>
    </div>
</div>

     <div class="modal fade" id="ajax_notes3" role="basic" aria-hidden="true" style="display:none">
        <div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
        <input type="hidden" id="id" name="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title caption-subject font-blue-sharp bold uppercase"><?=translate('Update Dokumen Pasien', $this->session->userdata('language'))?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="alert alert-danger display-hide">
                            <button class="close" data-close="alert"></button>
                            <?=$form_alert_danger?>
                        </div>
                        <div class="alert alert-success display-hide">
                            <button class="close" data-close="alert"></button>
                            <?=$form_alert_success?>
                        </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Nama Pasien", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                         <label class="control-label" id="nama2" name="nama2"></label>
                                    </div>

                                </div>
                                <div class="col-md-12"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Jenis Dokumen", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                         <label class="control-label" id="jenisdokumen2" name="jenisdokumen2"></label>
                                        
                                        
                                    </div>

                                </div>
                                <div class="col-md-12"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Nama Dokumen", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                         <label class="control-label" id="namadokumen2" name="namadokumen2" ></label>
                                         
                                    </div>

                                </div>
                                <div class="col-md-12"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("No.Dokumen", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                         <label class="control-label" id="nodokumen2" name="nodokumen2"></label>
                                   
                                    </div>

                                </div>
                                <div class="col-md-12"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Tipe", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                     <div class="input-group">
                                     <label class="control-label" id="kel22" name="kel22"> </label>
                                  </div>
                                </div>
                                 <div class="col-md-12"></div>
                            </div>
                        <div class="style" style="margin-top: 10px;">
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
                                <div class="col-md-4">
                                    <div class="input-group input-medium-date date date-picker">
                                        <input class="form-control" id="date2" readonly required="required" value="<?=date('d M Y')?>">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn default date-set">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4"><?=translate("Dokumen", $this->session->userdata("language"))?></label>
                                 
                                <div class="col-md-4">
                                    <input type="file" id="uploaddokumen2" name="uploaddokumen2" class="uploadbutton up-this" value="" required="required" />
                               
                                </div>
                                 <div class="col-md-12"></div>
                                <input type="hidden" id="uploadfilename2" name="uploadfilename2" required="required" />
                            </div> 
                            <div id="uploadchoosen_file_container_12" name="uploadchoosen_file_container_12" style="display:none" >
                                <div class="form-group">
                                    <label class="control-label col-md-4"></label>
                                    <div class="col-md-4">
                                        <label id="uploadchoosen_file_12" name="uploadchoosen_file_12">
                                           
                                        </label>
                                    </div>
                                    <div class="col-md-12"></div>
                                </div>
                            </div>
                        </div>
                    </div>
 
                </div>
                <div class="modal-footer">
                    <div class="form-actions fluid">    
                        <div class="col-md-12">
                             
                            <button type="reset" class="btn default" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></button>
                            <a id="confirm_save3" class="btn btn-primary" href="#" data-confirm="<?=$msg2?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
                            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
                        </div>      
                    </div>
                    <!-- <button type="button" class="btn default" data-dismiss="modal">Simpan</button> -->
                </div>
            </div>
        </div>
    </div>
</form>
 

<form id="modalpic" name="modalpic"    autocomplete="off">
    <?
    	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
      $msg 					  = translate("Apakah anda yakin akan menambah dokumen ini?",$this->session->userdata("language"));
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