<?php

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_add_permintaan_biaya", 
		"name"			=> "form_add_permintaan_biaya", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "revisi",
		"id" => $pk_value
	);

	echo form_open(base_url()."keuangan/permintaan_biaya/save", $form_attr,$hidden);
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$confirm_save       = translate('Anda yakin akan mengubah permintaan biaya ini ?',$this->session->userdata('language'));
	$submit_text        = translate('Simpan', $this->session->userdata('language'));
	$reset_text         = translate('Reset', $this->session->userdata('language'));
	$back_text          = translate('Kembali', $this->session->userdata('language'));

 //    /////////////////////////////////////////////////////////////////////

    $user_level_id = $this->session->userdata('level_id');
    // die_dump($user_level_id);

    if($form_data['status'] == 1)
    {
        $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
    
    } elseif($form_data['status'] == 2){

        $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';

    }elseif($form_data['status'] == 3){

        $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

    } elseif($form_data['status'] == 4 || $form_data['status'] == 19){

        $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
    } elseif($form_data['status'] == 5 || $form_data['status'] == 18){

        $status = '<div class="text-center"><span class="label label-md label-success">Diproses</span></div>';
    } elseif($form_data['status'] == 16 ){

        $status = '<div class="text-center"><span class="label label-warning">Menunggu Verifikasi Keuangan</span></div>';
    }


?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-search font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Revisi Permintaan Biaya", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="note note-danger note-bordered">
		<p>
			<b> NOTE: </b><?=$form_data['keterangan_revisi']?>
		</p>
	</div>
	<div class="portlet-body form">
	<div class="row">
		<div class="col-md-3">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="form-group">

					<label class="col-md-12"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
					<div class="col-md-12">
						<div class="input-group date">
							<input type="text" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?=date('d M Y', strtotime($form_data['tanggal']))?>"readonly >
							<span class="input-group-btn">
								<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<?php

							$nama = $this->user_m->get_by(array('id' => $form_data['diminta_oleh_id']), true);
							$nama_user = object_to_array($nama);
							// die_dump($nama);

						?>
						<input class="form-control hidden" id="nomer_{0}" name="user_level_id" value="<?=$nama_user['user_level_id']?>">
						<input class="form-control input-sm hidden" id="nomer_{0}" name="id_ref_pasien" value="<?=$form_data['diminta_oleh_id']?>" required placeholder="ID Referensi Pasien">
						<input class="form-control" id="nomer_{0}" name="nama_ref_user" value="<?=$nama_user['nama']?>" placeholder="Diminta Oleh" required readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12"><?=translate('Tipe', $this->session->userdata('language'))?> :</label>
					<div class="col-md-12">
						<?php
							$tipe = '';
							
							if($form_data['tipe'] == 1){
								$tipe = 'Kas';
							}if($form_data['tipe'] == 2){
								$tipe = 'Reimburse / Pencairan';
							}

						?>
						<label><?=$tipe?></label>
					</div>
				</div>

				<div class="form-group hidden">
					<label class="col-md-12"><?=translate('Tipe', $this->session->userdata('language'))?> :</label>
					<div class="col-md-12">
						<?php
							$check_kasbon = '';
							$check_rembes = '';
							
							if($form_data['tipe'] == 1){
								$check_kasbon = 'checked="checked"';
								$check_rembes = '';
							}if($form_data['tipe'] == 2){
								$check_kasbon = '';
								$check_rembes = 'checked="checked"';
							}

						?>
						<div class="radio-list">
							<label class="radio-inline">
							<input type="radio" id="kasbon" value="1" data-type="1" name="tipe" class="form-control" <?=$check_kasbon?> required> Kasbon</label>
							<label class="radio-inline">
							<input type="radio" id="rembes" value="2" data-type="2" name="tipe" class="form-control" <?=$check_rembes?> required> Rembes </label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-12"><?=translate('Nominal', $this->session->userdata('language'))?> :</label>
					<div class="col-md-12">
						<input class="form-control" type="text" id="nominal_show" name="nominal_show" placeholder="Nominal" value="<?=formatrupiah($form_data['nominal'])?>" readonly>
						<input class="form-control" type="hidden" id="nominal" name="nominal" placeholder="Nominal" value="<?=$form_data['nominal']?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-12"><?=translate("Terbilang", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group">
							<label class="control-label" id="terbilang"><?='#'.terbilang($form_data['nominal']).' Rupiah#'?></label>
						</div>
					</div>
				</div>	
				<!-- <div class="form-group">
							<label class="col-md-12"><?=translate('Keperluan', $this->session->userdata('language'))?></label>
							<div class="col-md-12">
								<?php
									$keperluan = array(
										"name"        => "keperluan",
										"id"          => "keperluan",
										"class"       => "form-control",
										"required"	  => "required",
										"rows"        => 10, 
										"value"		  => $form_data['keperluan'],
										"placeholder" => translate("Keperluan", $this->session->userdata("language")),
										
									);
									echo form_textarea($keperluan);
								?>
							</div>
						</div>	 -->
			</div>
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject"><?=translate("Persetujuan", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="form-body">
				    <div class="portlet-body">
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label"><?=translate('Status', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label class="control-label"><?=$status?></label>
							</div>
						</div>
	                </div>
	                <div class="portlet-body">
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label"><?=translate('Nominal Disetujui', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label class="control-label"><?=formatrupiah($form_data['nominal_setujui'])?></label>
							</div>
						</div>
	                </div>
	                <div class="portlet-body">
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label class="control-label"><?='#'.terbilang($form_data['nominal_setujui']).' Rupiah#'?></label>
							</div>
						</div>
	                </div>
					
				</div>
			</div>
		</div>
		<?php
		$i = 0;
		if($form_data['tipe'] == 1){
		?>
		<div class="col-md-9" id="section-biaya">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject"><?=translate("Biaya", $this->session->userdata("language"))?></span>
					</div>
					
				</div>
				<div class="form-body">
				    <div class="portlet-body">
						<?php
		                    $btn_del            = '<div class="text-center"><button class="btn red-intense del-this-biaya" title="Delete"><i class="fa fa-times"></i></button></div>';
		                    
							$option_biaya = array(
								''	=> translate('Pilih', $this->session->userdata('language')).'...'
							);

							$biaya = $this->biaya_m->get_by(array('is_active' => 1));
							$biaya = object_to_array($biaya);

							foreach ($biaya as $row) {
								$option_biaya[$row['id']] = $row['nama'];
							}

		                    $nominal = array(
		                        'id'          => 'biaya_nominal_'.$i.'',
		                        'name'        => 'biaya['.$i.'][nominal]',
		                        'class'       => 'form-control',
		                        'value'		  => $form_data['nominal']
		                    );
		                    $keterangan = array(
		                        'id'          => 'biaya_keterangan_'.$i.'',
		                        'name'        => 'biaya['.$i.'][keterangan]',
		                        'class'       => 'form-control',
		                        'required'       => 'required',
		                        'value'		  => $form_data['keperluan']
		                    );

		                    $item_cols = array(
								'biaya_jenis'   => form_dropdown('biaya['.$i.'][biaya_id]', $option_biaya, $form_data['biaya_id'], 'id="biaya_id_biaya_'.$i.'" class="form-control" required'),
								'biaya_nominal' => '<div class="input-group">
														<span class="input-group-addon">
															Rp.
														</span>
														<input class="form-control nominal_biaya" id="nominal_biaya_'.$i.'" name="biaya['.$i.'][nominal]" placeholder="Nominal Bon" value="'.$form_data['nominal'].'" required>
													</div>
													<span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>',
								'biaya_keterangan' => form_input($keterangan)
		                    );

		                    // gabungkan $item_cols jadi string table row
		                    $item_row_template =  '<tr id="item_row_{0}" style="vertical-align:top;"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
		                ?>
						<span id="tpl_biaya_row" class="hidden"><?=htmlentities($item_row_template)?></span>
						<div class="form-body">
							<div class="table-responsive">
	                            <table class="table table-condensed table-striped table-bordered table-hover" id="table_tambah_biaya">
	                                <thead>
	                                    <tr role="row">
	                                        <th class="text-center" width="20%"><?=translate("Biaya", $this->session->userdata('language'))?></th>
	                                        <th class="text-center" width="20%"><?=translate("Jumlah", $this->session->userdata('language'))?></th>
	                                        <th class="text-center" width="40%"><?=translate("Keterangan", $this->session->userdata('language'))?></th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <!-- <?//=$item_row?> -->
	                                </tbody>
	                            </table>
	                        </div>
						</div>
                    </div>
				</div>
			</div>
		</div>
		<?php
		}

		if($form_data['tipe'] == 2){
		?>
		<div class="col-md-9" id="section-reimburse">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject"><?=translate("Biaya", $this->session->userdata("language"))?></span>
					</div>
					<div class="actions">
						<a class="btn btn-icon-only btn-default btn-circle add-upload">
							<i class="fa fa-plus"></i>
						</a>
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
							<div class="form-group">
								<label class="col-md-12">'.translate("No. Bon", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<div class="input-group">
										<input class="form-control" id="no_bon_{0}" name="bon[{0}][no_bon]" placeholder="No. Bon">
										<span class="input-group-btn">
											<a class="btn red-intense del-this" id="btn_delete_upload_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12">'.translate("Biaya", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">'.form_dropdown('bon[{0}][biaya_id]', $option_biaya, '', 'id="biaya_id_bon_{0}" class="form-control"').'</div>
							</div>
							<div class="form-group">
								<label class="col-md-12">'.translate("Nominal Bon", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
										<div class="input-group">
											<span class="input-group-addon">
												Rp.
											</span>
											<input class="form-control nominal_bon" id="nominal_bon_{0}" name="bon[{0}][nominal_bon]" placeholder="Nominal Bon">
										</div>
										<span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12">'.translate("Tanggal", $this->session->userdata("language")).' :<span class="required">*</span></label>
								<div class="col-md-12">
									<div class="input-group date">
										<input type="text" class="form-control" id="bon_tanggal_{0}" name="bon[{0}][tanggal]" placeholder="Tanggal" value="'.date('d M Y').'"readonly >
										<span class="input-group-btn">
											<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12">'.translate("Keterangan", $this->session->userdata("language")).' :</label>
								<div class="col-md-12">
									<textarea class="form-control" id="keterangan_{0}" name="bon[{0}][keterangan]" cols="8" rows="6" ></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12">'.translate("Upload Bon", $this->session->userdata("language")).' :<span class="required">*</span></label>
								<div class="col-md-12">
									<input type="hidden" name="bon[{0}][url]" id="bon_url_{0}">
									<div id="upload_{0}">
										<span class="btn default btn-file">
											<span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>		
											<input type="file" class="upl_invoice" name="upl" id="upl_{0}" data-url="'.base_url().'upload_new/upload_photo" multiple />
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
							<ul class="list-unstyled" id="list_reimburse">
							<?php
								if(count($form_data_detail) != 0){
									$form_data_detail = object_to_array($form_data_detail);

									$option_biaya = array(
										''	=> translate('Pilih', $this->session->userdata('language')).'...'
									);

									$biaya = $this->biaya_m->get_by(array('is_active' => 1));
									$biaya = object_to_array($biaya);

									foreach ($biaya as $row) {
										$option_biaya[$row['id']] = $row['nama'];
									}

									$i = 0;
									foreach ($form_data_detail as $key => $bon) {

									?>
									<li class="fieldset">
										<div class="form-group hidden">
											<label class="col-md-12"><?=translate("ID", $this->session->userdata("language"))?> :</label>
											<div class="col-md-12">
												<input class="form-control" id="id_bon_<?=$i?>" name="bon[<?=$i?>][id]" value="<?=$bon['id']?>">
											</div>
										</div>
										<div class="form-group hidden">
											<label class="col-md-12"><?=translate("Active", $this->session->userdata("language"))?> :</label>
											<div class="col-md-12">
												<input class="form-control" id="is_active_bon_<?=$i?>" name="bon[<?=$i?>][is_active]" value="<?=$bon['is_active']?>">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-12"><?=translate("No. Bon", $this->session->userdata("language"))?> :</label>
											<div class="col-md-12">
												<div class="input-group">
													<input class="form-control" id="no_bon_<?=$i?>" name="bon[<?=$i?>][no_bon]" placeholder="No. Bon" value="<?=$bon['no_bon']?>">
													<span class="input-group-btn">
														<a class="btn red-intense del-this-db" id="btn_delete_upload_<?=$i?>" title="<?=translate('Remove', $this->session->userdata('language'))?>" data-id="<?=$bon['id']?>"><i class="fa fa-times"></i></a>
													</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-12"><?=translate("Biaya", $this->session->userdata("language"))?> :</label>
											<div class="col-md-12"><?=form_dropdown('bon['.$i.'][biaya_id]', $option_biaya, $bon['biaya_id'], 'id="biaya_id_bon_<?=$i?>" class="form-control"')?></div>
										</div>
										<div class="form-group">
											<label class="col-md-12"><?=translate("Nominal Bon", $this->session->userdata("language"))?> :</label>
											<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon">
															Rp.
														</span>
														<input class="form-control nominal_bon" id="nominal_bon_<?=$i?>" name="bon[<?=$i?>][nominal_bon]" placeholder="Nominal Bon" value="<?=$bon['total_bon']?>">
													</div>
													<span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-12"><?=translate("Tanggal", $this->session->userdata("language"))?> :<span class="required">*</span></label>
											<div class="col-md-12">
												<div class="input-group date">
													<input type="text" class="form-control" id="bon_tanggal_<?=$i?>" name="bon[<?=$i?>][tanggal]" placeholder="Tanggal" value="<?=date('d M Y', strtotime($bon['tgl_bon']))?>" readonly >
													<span class="input-group-btn">
														<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
													</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
											<div class="col-md-12">
												<textarea class="form-control" id="keterangan_<?=$i?>" name="bon[<?=$i?>][keterangan]" cols="8" rows="6" value="<?=$bon['keterangan']?>" ></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-12"><?=translate("Upload Bon", $this->session->userdata("language"))?> :<span class="required">*</span></label>
											<div class="col-md-12">
												<input type="hidden" name="bon[<?=$i?>][url]" id="bon_url_<?=$i?>" value="<?=$bon['url']?>" >
												<div id="upload_<?=$i?>">
													<span class="btn default btn-file">
														<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>		
														<input type="file" class="upl_invoice" name="upl" id="upl_<?=$i?>" data-url="<?=base_url()?>upload_new/upload_photo" multiple />
													</span>

												<ul class="ul-img">
													<li class="working">
														<div class="thumbnail">
															<a class="fancybox-button" title="<?=$bon['url']?>" href="<?=config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url']?>" data-rel="fancybox-button">
															<img src="<?=config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url']?>" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a>
														</div>
														<span></span>
													</li>
												</ul>

												</div>
											</div>
										</div>
										<hr>
									</li>
									<?php
										$i = $i + 1;
									}
								}
							?>
							</ul>
						</div>
                    </div>
				</div>
			</div>

		</div>

		<?php }
		?>
		<input type="hidden" id="jml_baris" name="jml_baris" value="<?=$i?>">
		<!-- end of pilih item -->

		</div>
							<div class="form-actions right">    
		        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
		        	<i class="fa fa-chevron-left"></i>
		        	<?=translate('Kembali', $this->session->userdata('language'));?>
		        </a>
		        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
		        <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal">
		        	<i class="fa fa-check"></i>
		        	<?=$submit_text?>
		        </a>
		        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
			</div>
	</div>

</div>


<?=form_close();?>

