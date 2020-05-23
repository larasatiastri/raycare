<?php

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_proses_permintaan_biaya", 
		"name"			=> "form_proses_permintaan_biaya", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "proses_reimburse",
		"id" => $pk_value
	);
	if($flag == 'keuangan'){
		echo form_open(base_url()."keuangan/proses_pembayaran_transaksi/save", $form_attr,$hidden);

	}if($flag == 'reimburse'){
		echo form_open(base_url()."keuangan/proses_pembayaran_reimburse/save", $form_attr,$hidden);

	}else{
		echo form_open(base_url()."keuangan/pembayaran_transaksi/save", $form_attr,$hidden);
	}
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$submit_text = translate('Setujui', $this->session->userdata('language'));
	$confirm_save = translate('Anda yakin akan memproses permintaan biaya ini?', $this->session->userdata('language'));

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

    } elseif($form_data['status'] == 4){

        $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
    }

?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-check font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Verifikasi Dokumen Reimburse", $this->session->userdata("language"))?></span> <span><?=$form_data['nomor_permintaan']?></span>
		</div>
		
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
					<label class="col-md-12 bold"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
					<div class="col-md-12">
						<label class="control-label"><?=date('d M Y', strtotime($form_data['tanggal']))?></label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group">
							<?php

								$nama = $this->user_m->get_by(array('id' => $form_data['diminta_oleh_id']), true);
								$nama_user = object_to_array($nama);
								// die_dump($nama);

							?>
							<label class="control-label"><?=$nama_user['nama']?></label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-12 bold"><?=translate('Tipe', $this->session->userdata('language'))?> :</label>
					<div class="col-md-12">
						<?php
							$tipe = '';
							
							if($form_data['tipe'] == 1){
								$tipe = 'Kasbon';
							}if($form_data['tipe'] == 2){
								$tipe = 'Reimburse / Pencairan';
							}

						?>
						<label><?=$tipe?></label>
						<input type="hidden" class="form-control" id="tipe" name="tipe" value="<?=$form_data['tipe']?>"> </input>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate("Nominal", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group">
							<input type="hidden" class="form-control" id="nominal" name="nominal" value="<?=$form_data['nominal_setujui']?>"> </input>
							<label class="control-label"><?=formatrupiah($form_data['nominal_setujui'])?></label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12 bold"><?=translate("Terbilang", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
							<label><?='#'.terbilang($form_data['nominal_setujui']).' Rupiah#'?></label>
					</div>
				</div>
				<!-- <div class="form-group">
					<label class="col-md-12"><?=translate("Keperluan", $this->session->userdata("language"))?> :</label>
					<label class="col-md-12"><strong><?=$form_data['keperluan']?></strong></label>
				</div> -->
						
			</div>

		</div>

		<?php
			if(count($form_data_detail) != 0){
				?>
				<div class="col-md-9">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Bukti Pembayaran", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-body">
						    <div class="portlet-body">
								<div class="portlet-body">
									<?php
											if($form_data['tipe'] == 1){
												$form_upload_bon = '';
												if(count($form_data_detail) != 0){
													foreach ($form_data_detail as $key => $bon) {

														$biaya = $this->biaya_m->get_by(array('id' => $bon['biaya_id']), true);

														$form_upload_bon .= '<tr>
														<td style="vertical-align: top !important;">'.$biaya->nama.'</td>
														<td style="vertical-align: top !important;">'.date('d M Y', strtotime($bon['tgl_bon'])).'</td>
														<td style="vertical-align: top !important;">'.formatrupiah($bon['total_bon']).'</td>
														<td style="vertical-align: top !important;">'.$bon['keterangan'].'</td>
														</tr>';
													}	
												}
										?>
												
		                                <table class="table table-bordered table-hover">
											<thead>
												<tr role="row" class="heading">
													<th class="text-center" width="30%">
														 Biaya
													</th>
													<th class="text-center" width="10%">
														 Tgl. Bon
													</th>
													<th class="text-center" width="15%">
														 Total Bon
													</th>
													<th class="text-center" width="45%">
														 Keterangan
													</th>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>  
										<?php
											}
											if($form_data['tipe'] == 2){
												$form_upload_bon = '';
												if(count($form_data_detail) != 0){
													foreach ($form_data_detail as $key => $bon) {

														$biaya = $this->biaya_m->get_by(array('id' => $bon['biaya_id']), true);

														$form_upload_bon .= '<tr>
														<td style="vertical-align: top !important;">'.$biaya->nama.'</td>
														<td><a class="fancybox-button" title="'.$bon['url'].'" href="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" alt="Smiley face" class="img-responsive" ></a></td>
														<td style="vertical-align: top !important;">'.$bon['no_bon'].'</td>
														<td style="vertical-align: top !important;">'.date('d M Y', strtotime($bon['tgl_bon'])).'</td>
														<td style="vertical-align: top !important;">'.formatrupiah($bon['total_bon']).'</td>
														<td style="vertical-align: top !important;">'.$bon['keterangan'].'</td>
														</tr>';
													}	
												}
										?>
			                               <table class="table table-bordered table-hover">
												<thead>
													<tr role="row" class="heading">
														<th class="text-center" width="15%">
															 Biaya
														</th>
														<th class="text-center" width="20%">
													 		Image
														</th>
														<th class="text-center" width="12%">
															 No. Bon
														</th>
														<th class="text-center" width="13%">
															 Tgl. Bon
														</th>
														<th class="text-center" width="15%">
															 Total Bon
														</th>
														<th class="text-center" width="25%">
															 Keterangan
														</th>
													</tr>
												</thead>
												<tbody>
													<?=$form_upload_bon?>
												</tbody>
											</table>  
										<?php
											}
										?>  
								</div>
			                </div>
							
						</div>
					</div>
					
				</div>
				<?php
			}
			else{
		?>
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
										<label class="col-md-12">'.translate("Biaya", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">'.form_dropdown('bon[{0}][biaya_id]', $option_biaya, '', 'id="biaya_id_bon_{0}" class="form-control"').'</div>
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
										<label class="col-md-12">'.translate("Total Bon", $this->session->userdata("language")).' :</label>
										<div class="col-md-12">
												<div class="input-group">
													<span class="input-group-addon">
														Rp.
													</span>
													<input class="form-control" required id="total_bon_{0}" name="bon[{0}][total_bon]" placeholder="Total Bon">
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
		<?php
			}
		?>
		
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

