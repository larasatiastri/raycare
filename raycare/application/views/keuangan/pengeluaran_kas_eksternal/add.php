<?php

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_pengeluaran_kas_eksternal", 
		"name"			=> "form_pengeluaran_kas_eksternal", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."keuangan/pengeluaran_kas_eksternal/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

    $user_level_id = $this->session->userdata('level_id');
    $user_id = $this->session->userdata('user_id');
    $nama_user = $this->session->userdata('nama_lengkap');
    // die_dump($user_level_id);

    $btn_del_bon = '<div class="text-center"><button class="btn red-intense del-this-bon" title="Hapus Bon"><i class="fa fa-times"></i></button></div>';

	$item_cols_bon = array(// style="width:156px;
		'bon_upload' => '<div class="input-group">
									<input id="bon_url_{0}" name="bon[{0}][url]" class="form-control" readonly>
									<span class="input-group-btn" id="upload_{0}">
	                                <span class="btn default btn-file">
	                                    <span class="fileinput-new">'.translate('Pilih File', $this->session->userdata('language')).'</span>       
	                                    <input type="file" name="upl" id="pdf_file_{0}" data-url="'.base_url().'upload/upload_pdf" multiple />
	                                </span>
	                                </span>
	                            </div>',
		'action'           => $btn_del_bon,
	);

	$item_row_template_bon =  '<tr id="item_row_bon_{0}" ><td>' . implode('</td><td>', $item_cols_bon) . '</td></tr>';
?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
		<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Kasbon", $this->session->userdata("language"))?></span>
		</div>

		<?php
			$confirm_save       = translate('Anda yakin untuk menambahkan permintaan biaya ini?',$this->session->userdata('language'));
			$submit_text        = translate('Simpan', $this->session->userdata('language'));
			$reset_text         = translate('Reset', $this->session->userdata('language'));
			$back_text          = translate('Kembali', $this->session->userdata('language'));
		?>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=$back_text?>
	        </a>
	        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
	        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
	        <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal">
	        	<i class="fa fa-check"></i>
	        	<?=$submit_text?>
	        </a>
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
		</div>
		<div class="form-wizard">
			<div class="row">
				<div class="col-md-3">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<div class="input-group date">
									<input type="text" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?=date('d M Y')?>"readonly >
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						 
						<div class="form-group">
							<div class="col-md-12">
									<input class="form-control hidden" id="nomer_{0}" name="user_level_id" value="<?=$user_level_id?>">
									<input class="form-control input-sm hidden" id="nomer_{0}" name="id_ref_pasien" value="<?=$user_id?>" required placeholder="ID Referensi Pasien">
									<input class="form-control input-sm hidden" id="nomer_{0}" name="cabang_id" value="<?=$flash_form_data["cabang_id"]?>" placeholder="Kasir Titip Uang ID">
									<input class="form-control" id="nomer_{0}" name="nama_ref_user" value="<?=$nama_user?>" placeholder="Diminta Oleh" required readonly>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<div class="input-group">
									<span class="input-group-addon">
										Rp.
									</span>
									<input class="form-control" type="number" id="nominal" name="nominal" placeholder="Nominal" required>								
								</div>
								<span class="help-block">Jangan menggunakan titik (.) dan koma (,)</span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('Terbilang', $this->session->userdata('language'))?></label>
							<label class="col-md-12" id="terbilang"></label>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('Keperluan', $this->session->userdata('language'))?></label>
							<div class="col-md-12">
								<?php
									$keperluan = array(
										"name"        => "keperluan",
										"id"          => "keperluan",
										"class"       => "form-control",
										"rows"        => 10, 
										"placeholder" => translate("Keperluan", $this->session->userdata("language")),
										"required"    => "required" 
									);
									echo form_textarea($keperluan);
								?>
							</div>
						</div>
					</div>					
				</div>
				<div class="col-md-9">
					<div class="portlet light bordered" id="section-bon">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Upload Bon", $this->session->userdata("language"))?></span>
							</div>
							<div class="actions">
								<a class="btn btn-icon-only btn-default btn-circle add-upload">
									<i class="fa fa-plus"></i>
								</a>
							</div>
						</div>
						<div class="form-body">
						    <div class="portlet-body">
								<div class="portlet-body">
									<?php
										$form_upload_bon = '
											<div class="form-group hidden">
												<label class="control-label col-md-2">'.translate("ID", $this->session->userdata("language")).' :</label>
												<div class="col-md-7">
													<input class="form-control" id="id_bon{0}" name="bon[{0}][id]">
												</div>
											</div>
											<div class="form-group hidden">
												<label class="control-label col-md-2">'.translate("Active", $this->session->userdata("language")).' :</label>
												<div class="col-md-7">
													<input class="form-control" id="is_active_bon{0}" name="bon[{0}][is_active]">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2">'.translate("No. Bon", $this->session->userdata("language")).' :</label>
												<div class="col-md-7">
													<div class="input-group">
														<input class="form-control" id="no_bon_{0}" name="bon[{0}][no_bon]" placeholder="No. Bon">
														<span class="input-group-btn">
															<a class="btn red-intense del-this" id="btn_delete_upload_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2">'.translate("Total Bon", $this->session->userdata("language")).' :</label>
												<div class="col-md-7">
													<input class="form-control" required id="total_bon_{0}" name="bon[{0}][total_bon]" placeholder="Total Bon">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2">'.translate("Tanggal", $this->session->userdata("language")).' :<span class="required">*</span></label>
												<div class="col-md-7">
													<div class="input-group date">
														<input type="text" class="form-control" id="bon_tanggal_{0}" name="bon[{0}][tanggal]" placeholder="Tanggal" value="'.date('d M Y').'"readonly >
														<span class="input-group-btn">
															<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2">'.translate("Keterangan", $this->session->userdata("language")).' :</label>
												<div class="col-md-7">
													<textarea class="form-control" id="keterangan_{0}" name="bon[{0}][keterangan]" cols="8" rows="6" ></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2">'.translate("Upload Bon", $this->session->userdata("language")).' :<span class="required">*</span></label>
												<div class="col-md-7">
													<input type="hidden" required name="bon[{0}][url]" id="bon_url_{0}">
													<div id="upload_{0}">
														<span class="btn default btn-file">
															<span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>		
															<input type="file" class="upl_invoice" name="upl" id="upl_{0}" data-url="'.base_url().'upload/upload_photo" multiple />
														</span>

													<ul class="ul-img">
													</ul>

													</div>
												</div>
											</div>
											
											';
										?>

										<input type="hidden" id="tpl-form-upload" value="<?=htmlentities($form_upload_bon)?>">
										<div class="form-body" >
											<ul class="list-unstyled">
											</ul>
										</div>
									        
									        
							   		</div>
								</div>
			                </div>
							
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>


