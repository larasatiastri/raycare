<?php

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_add_permintaan_biaya", 
		"name"			=> "form_add_permintaan_biaya", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."keuangan/permintaan_biaya/save", $form_attr,$hidden);
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
	                                    <input type="file" name="upl" id="pdf_file_{0}" data-url="'.base_url().'upload_new/upload_pdf" multiple />
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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pencairan Dana", $this->session->userdata("language"))?></span>
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

			<div class="row">

				<div class="col-md-12" id="section-reimburse">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Biaya", $this->session->userdata("language"))?></span>
							</div>
							
						</div>
						<div class="form-body">
						    <div class="portlet-body">
								<?php
								$option_biaya = array(
									''	=> translate('Pilih', $this->session->userdata('language')).'...'
								);

								$biaya = $this->biaya_m->get_by(array('is_active' => 1));
								$biaya = object_to_array($biaya);

								foreach ($biaya as $row) {
									$option_biaya[$row['id']] = $row['nama'];
								}

								$form_upload_bon = '
									<div class="row">
									<div class="form-group hidden">
										<label class="col-md-12">'.translate("ID", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
											<input class="form-control" id="id_bon{0}" name="bon[{0}][id]">
										</div>
									</div>
									<div class="form-group hidden">
										<label class="col-md-12">'.translate("Active", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
											<input class="form-control" id="is_active_bon{0}" name="bon[{0}][is_active]">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="col-md-12">
												<div class="input-group">
													<input class="form-control" id="no_bon_{0}" name="bon[{0}][no_bon]" placeholder="No. Dokumen">
													<span class="input-group-btn">
														<a class="btn red-intense del-this" id="btn_delete_upload_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="col-md-12">'.form_dropdown('bon[{0}][biaya_id]', $option_biaya, '', 'id="biaya_id_bon_{0}" class="form-control"').'</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon">
															Rp.
														</span>
														<input class="form-control nominal_bon" id="nominal_bon_{0}" name="bon[{0}][nominal_bon]">
													</div>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="col-md-12">
												<input class="form-control nominal_bon" id="isian_1_{0}" name="bon[{0}][nominal_bon]" placeholder="Isian 1">
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="col-md-12">
												<input class="form-control nominal_bon" id="isian_2_{0}" name="bon[{0}][nominal_bon]" placeholder="Isian 2">
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="col-md-12">
												<input class="form-control nominal_bon" id="keterangan_{0}" name="bon[{0}][nominal_bon]" placeholder="Keterangan">
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="col-md-12">
												<div class="input-group date">
													<input type="text" class="form-control" id="bon_tanggal_{0}" name="bon[{0}][tanggal]" placeholder="Tanggal" value="'.date('d M Y').'"readonly >
													<span class="input-group-btn">
														<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-2 hidden">
										<div class="form-group">
											<div class="col-md-12">
												<textarea class="form-control" id="keterangan_{0}" name="bon[{0}][keterangan]" placeholder="Keterangan" cols="8" rows="3" ></textarea>
											</div>
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<div class="col-md-12">
												<input type="hidden" name="bon[{0}][url]" id="bon_url_{0}">
												<div id="upload_{0}">
													<span class="btn default btn-file">
														<span class="fileinput-new">'.translate('Upload Dokumen', $this->session->userdata('language')).'</span>		
														<input type="file" class="upl_invoice" name="upl" id="upl_{0}" data-url="'.base_url().'upload_new/upload_photo" multiple />
													</span>

												<ul class="ul-img">
												</ul>

												</div>
											</div>
										</div>
									</div>
									</div><!-- penutup  -->
									';
								?>

								<input type="hidden" id="tpl-form-upload" value="<?=htmlentities($form_upload_bon)?>">
								<div class="form-body" >
									<ul class="list-unstyled" id="list_reimburse">
									</ul>
								</div>
								<div class="actions">
								<a class="btn green add-upload">
									<i class="fa fa-plus"></i> <?=translate("Tambah", $this->session->userdata("language"))?>
								</a>
							</div>
                            </div>
						</div>
					</div>
				</div>
			</div>
			<?php
				$confirm_save       = translate('Anda yakin untuk menambahkan pencairan dana ini?',$this->session->userdata('language'));
				$submit_text        = translate('Simpan', $this->session->userdata('language'));
				$reset_text         = translate('Reset', $this->session->userdata('language'));
				$back_text          = translate('Kembali', $this->session->userdata('language'));
			?>
			<div class="form-actions right">    
		        <a class="btn default" href="javascript:history.go(-1)">
		        	<i class="fa fa-chevron-left"></i>
		        	<?=$back_text?>
		        </a>
		        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
		        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
		        <a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal">
		        	<i class="fa fa-check"></i>
		        	<?=$submit_text?>
		        </a>
			</div>
		</div>
	</div>
</div>


