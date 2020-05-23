<?php
	$form_attr = array(
	    "id"            => "form_add_dokter_sppd", 
	    "name"          => "form_add_dokter_sppd", 
	    "autocomplete"  => "off",
	    "class"			=> "form-horizontal", 
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."klinik_hd/surat_dokter_sppd/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-note font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Buat Surat", $this->session->userdata("language"))?></span>
			<span class="caption-helper"><?php echo '<label class="control-label ">'.date('d M Y').'</label>'; ?></span>
		</div>
		<div class="actions">

            <?php $msg = translate("Apakah anda yakin akan membuat surat dokter sppd ini?",$this->session->userdata("language"));?>
				<a class="btn btn-circle btn-default" href="<?=base_url()?>klinik_hd/surat_dokter_sppd/history">
					<i class="fa fa-history"></i>
					<?=translate("History", $this->session->userdata("language"))?>
				</a>
				<a id="confirm_save" class="btn btn-circle btn-default hidden" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
					<i class="fa fa-floppy-o"></i>
					<?=translate("Simpan", $this->session->userdata("language"))?>
				</a>
				<a id="check_required" class="btn btn-circle btn-primary" >
					<i class="fa fa-floppy-o"></i>
					<?=translate("Simpan", $this->session->userdata("language"))?>
				</a>
				<a id="show_modal" class="btn btn-circle btn-default hidden" data-target="#modal_review" data-toggle="modal">
					<i class="fa fa-floppy-o"></i>
					<?=translate("Simpan", $this->session->userdata("language"))?>
				</a>
	            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			
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
				<div class="col-md-4">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<?=translate("Form Input", $this->session->userdata("language"))?>
							</div>
						</div>
						<div class="portlet-body">
						<div class="row">
							<div class="col-md-3">
								<div class="profile-userpic">
									<img id="side_img_pasien" src="<?=base_url().config_item('site_img_pasien')?>global/global_medium.png" class="img-responsive" alt="">
								</div>
							</div>	
							<div class="col-md-9">
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("No. RM", $this->session->userdata("language"))?> :</label>
									<div class="col-md-8">
										<div class="input-group">
											<?php
												$no_member = array(
													"id"			=> "no_member",
													"required" 		=> "required",
													"name"			=> "no_member",
													"class"			=> "form-control",
													"placeholder"	=> translate("No. Pasien", $this->session->userdata("language"))
												);

												$id_pasien = array(
													"id"			=> "id_pasien",
													"name"			=> "id_pasien",
													"class"			=> "form-control hidden",
													"placeholder"	=> translate("Pasien", $this->session->userdata("language"))
												);
												echo form_input($no_member);
												echo form_input($id_pasien);
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
									<label class="control-label col-md-4"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
									<label class="col-md-8" id="label_nama_pasien"></label>	
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Umur", $this->session->userdata("language"))?> :</label>
									<label class="col-md-8" id="label_umur_pasien"></label>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
									<label class="col-md-8" id="label_alamat_pasien"></label>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Diagnosis", $this->session->userdata("language"))?> :</label>
									<div class="col-md-8">
										<?php
											$diagnosa1 = array(
												"id"			=> "diagnosa1",
												"required" 		=> "required",
												"name"			=> "diagnosa1",
												"class"			=> "form-control",
												"readonly"		=> "readonly",
												"type" 			=> "text",
												"value"			=> "CKD on HD stg V on HD rutin"
											);

											echo form_input($diagnosa1);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Diagnosis Tambahan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-8">
										<?php
											$diagnosa2 = array(
												"id"			=> "diagnosa2",
												"name"			=> "diagnosa2",
												"class"			=> "form-control",
												"type" 			=> "text"
											);

											echo form_input($diagnosa2);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alasan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-8">
										<?php
											$alasan = array(
												"id"			=> "alasan",
												"name"			=> "alasan",
												"class"			=> "form-control",
												"rows"		=> 4,
												"type" 			=> "text",
												"required"		=> "required"
											);

											echo form_textarea($alasan);
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>	

				<div class="col-md-8">
					<div class="portlet light bordered">
		    			<div class="portlet-title">
		    				<div class="caption">
		    					<?=translate("Foto", $this->session->userdata("language"))?>
		    				</div>
		    				
		    			</div>
		    			<div class="portlet-body">
		    			<div class="row">
		    				<div class="col-md-4">
								<div class="form-group hidden">
									<label class="col-md-12"><?=translate("ID", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<input class="form-control" id="id_gambar0" name="gambar[0][id]">
									</div>
								</div>
								<div class="form-group hidden">
									<label class="col-md-12"><?=translate("Active", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<input class="form-control" id="is_active_gambar0" name="gambar[0][is_active]">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?><span class="required">*</span> :</label>
									<div class="col-md-9">
										<div class="input-group">
											<input class="form-control" id="nama_0" name="gambar[0][nama]" placeholder="Nama Gambar" readonly required>
											<span class="input-group-btn">
												<a class="btn red-intense del-this" id="btn_delete_upload_0" title="<?=translate('Remove', $this->session->userdata('language'))?>"><i class="fa fa-times"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Gambar", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
									<div class="col-md-9">
										<input type="hidden" name="gambar[0][url]" id="gambar_url_0" >
										<div id="upload_0">
											<span class="btn default btn-file">
												<span class="fileinput-new"><?=translate('Pilih Gambar', $this->session->userdata('language'))?></span>		
												<input type="file" class="upl_invoice" name="upl" id="upl_0" data-url="<?=base_url()?>upload/upload_photo" multiple />
											</span>

										<ul class="ul-img">
										</ul>

										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group hidden">
									<label class="col-md-12"><?=translate("ID", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<input class="form-control" id="id_gambar1" name="gambar[1][id]">
									</div>
								</div>
								<div class="form-group hidden">
									<label class="col-md-12"><?=translate("Active", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<input class="form-control" id="is_active_gambar1" name="gambar[1][is_active]">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
									<div class="col-md-9">
										<div class="input-group">
											<input class="form-control" id="nama_1" name="gambar[1][nama]" placeholder="Nama Gambar" readonly>
											<span class="input-group-btn">
												<a class="btn red-intense del-this" id="btn_delete_upload_1" title="<?=translate('Remove', $this->session->userdata('language'))?>"><i class="fa fa-times"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Gambar", $this->session->userdata("language"))?>:</label>
									<div class="col-md-9">
										<input type="hidden" name="gambar[1][url]" id="gambar_url_1">
										<div id="upload_1">
											<span class="btn default btn-file">
												<span class="fileinput-new"><?=translate('Pilih Gambar', $this->session->userdata('language'))?></span>		
												<input type="file" class="upl_invoice" name="upl" id="upl_1" data-url="<?=base_url()?>upload/upload_photo" multiple />
											</span>

										<ul class="ul-img">
										</ul>

										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group hidden">
									<label class="col-md-12"><?=translate("ID", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<input class="form-control" id="id_gambar2" name="gambar[2][id]">
									</div>
								</div>
								<div class="form-group hidden">
									<label class="col-md-12"><?=translate("Active", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<input class="form-control" id="is_active_gambar2" name="gambar[2][is_active]">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
									<div class="col-md-9">
										<div class="input-group">
											<input class="form-control" id="nama_2" name="gambar[2][nama]" placeholder="Nama Gambar" readonly>
											<span class="input-group-btn">
												<a class="btn red-intense del-this" id="btn_delete_upload_2" title="<?=translate('Remove', $this->session->userdata('language'))?>"><i class="fa fa-times"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?=translate("Gambar", $this->session->userdata("language"))?>:</label>
									<div class="col-md-9">
										<input type="hidden" name="gambar[2][url]" id="gambar_url_2">
										<div id="upload_2">
											<span class="btn default btn-file">
												<span class="fileinput-new"><?=translate('Pilih Gambar', $this->session->userdata('language'))?></span>		
												<input type="file" class="upl_invoice" name="upl" id="upl_2" data-url="<?=base_url()?>upload/upload_photo" multiple />
											</span>

										<ul class="ul-img">
										</ul>

										</div>
									</div>
								</div>
							</div>
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
		    			<div class="table-scrollable">
		    				<table class="table table-bordered table-striped table-hover" style="font-size:11px;">
								<thead>
			    					<th>Tanggal</th>
			    					<th>BB Awal</th>
			    					<th>BB Akhir</th>
			    					<th>Waktu</th>
			    					<th>QB</th>
			    					<th>QD</th>
			    					<th>UFG</th>
			    				</thead>
			    				<tbody id="tabel_tindakan">

			    				</tbody>
							</table>    
		    			</div>
					</div>
				</div>
		</div>
	</div>
</div>	

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row" class="">
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

<div class="modal fade" id="modal_review" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog modal-md">
        <div class="modal-content">
        	<div class="modal-header">
            <div class="caption">
                <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Preview", $this->session->userdata("language"))?></span>
            </div>
        </div>
        <div class="modal-body form">
            <div class="form-group">
	            <div class="col-md-12">
	                <label class="col-md-12"><?=translate('Dengan hormat, ', $this->session->userdata('language'))?></label>
	            </div>
            </div>
            <div class="form-group">
	            <div class="col-md-12">
	                <label class="col-md-12"><?=translate('Mohon penanganan tindakan Hemodialisis terhadap pasien :', $this->session->userdata('language'))?></label>
	            </div>
            </div>
            <div class="form-group">
				<div class="col-md-12">
					<label class="col-md-2"><?=translate("No. RM", $this->session->userdata("language"))?></label>
					<label class="col-md-1">:</label>
					<label class="col-md-9" id="label_norekmed"></label>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label class="col-md-2"><?=translate("Nama", $this->session->userdata("language"))?></label>
					<label class="col-md-1">:</label>
					<label class="col-md-9" id="label_nama_pasien_prev"></label>					
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label class="col-md-2"><?=translate("Umur", $this->session->userdata("language"))?></label>
					<label class="col-md-1">:</label>
					<label class="col-md-9" id="label_umur_pasien_prev"></label>					
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label class="col-md-2"><?=translate("Alamat", $this->session->userdata("language"))?></label>
					<label class="col-md-1">:</label>
					<label class="col-md-9" id="label_alamat_pasien_prev"></label>					
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label class="col-md-2"><?=translate("Diagnosis", $this->session->userdata("language"))?></label>
					<label class="col-md-1">:</label>
					<label class="col-md-9" id="label_diagnosis1">CKD on HD stg V on HD rutin</label>					
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label class="col-md-2"><?=translate("Diagnosis Tambahan", $this->session->userdata("language"))?></label>
					<label class="col-md-1">:</label>
					<label class="col-md-9" id="label_diagnosis2"></label>					
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label class="col-md-12"><?=translate("Pasien merupakan Hemodialisis rutin 2x seminggu, dan dibutuhkan tindakan tambahan hemodialisis 3x seminggu untuk pasien karena ", $this->session->userdata("language"))?><label id="label_alasan_hd"></label></label>
				</div>
			</div>
			
            
        </div>
        <div class="modal-footer">
            <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
            <button type="button" class="btn green-haze hidden" id="btnOK">OK</button>
            <a class="btn btn-primary" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
        </div>
        </div>
    </div>
</div>

<?=form_close()?>


