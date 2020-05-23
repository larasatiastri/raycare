<?php
	$form_attr = array(
		"id"			=> "form_proses", 
		"name"			=> "form_proses", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."keuangan/persetujuan_permintaan_biaya/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$confirm_save       = translate('Anda yakin akan menyetujui permintaan biaya ini ?',$this->session->userdata('language'));
	$confirm_reject     = translate('Anda yaking akan menolak permintaan biaya ini ?',$this->session->userdata('language'));
	$submit_text        = translate('Proses', $this->session->userdata('language'));
	$reset_text         = translate('Tolak', $this->session->userdata('language'));
	$back_text          = translate('Kembali', $this->session->userdata('language'));

	$user_minta = $this->user_m->get($form_data['diminta_oleh_id']);

    $user_level_id = $user_minta->user_level_id;
    $user_level_minta = $this->user_level_m->get($user_level_id);
   
?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-check font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Persetujuan Permintaan Biaya", $this->session->userdata("language"))?> <?=$form_data['nomor_permintaan']?></span>
		</div>
		
	</div>
	<div class="portlet-body form">
		<div class="form-wizard">
			<div class="row">
				<div class="col-md-3">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Informasi Permintaan", $this->session->userdata("language"))?></span>
							</div>
						</div>
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
						 
						<div class="form-group hidden">
							<div class="col-md-12">
								<label><?=translate('ID Permintaan', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?=$form_data['id']?></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="bold"><?=translate('Tanggal', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?=date('d-M-Y', strtotime($form_data['tanggal']))?></label>
							</div>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label class="bold"><?=translate('Diminta Oleh', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?=$user_minta->nama.' ('.$user_level_minta->nama.')'?></label>
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
							</div>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label class="bold"><?=translate('Nominal', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?=formatrupiah($form_data['nominal'])?></label>
							</div>
						</div>
						<?php
							if($form_data_persetujuan['tipe'] == 3){
						?>
						<div class="form-group">								
							<div class="col-md-12">
								<label class="bold"><?=translate('Jumlah Biaya', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?=formatrupiah($form_data['nominal'] + $form_data['sisa'])?></label>
							</div>
						</div>
						<?php
								if($form_data['sisa'] > 0){
								?>
						<div class="form-group">								
							<div class="col-md-12">
								<label class="bold"><?=translate('Selisih', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?=formatrupiah($form_data['sisa'])?></label>
							</div>
						</div>

								<?php
								}
							}
						?>
						<div class="form-group">								
							<div class="col-md-12">
								<label class="bold"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?='#'.terbilang($form_data['nominal']).' Rupiah#'?></label>
							</div>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label class="bold"><?=translate('Keperluan', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?=$form_data['keperluan']?></label>
							</div>
						</div>	
						<div class="form-group hidden">
							<div class="col-md-12">
								<?php
									$nominal_setujui = array(
										"name"        => "nominal_setujui",
										"id"          => "nominal_setujui",
										"autofocus"   => true,
										"class"       => "form-control", 
										"placeholder" => translate("Nominal", $this->session->userdata("language")), 
										"required"    => "required",
										"value"		=> $form_data['nominal']
									);
									if($form_data['sisa'] > 0){
										$nominal_setujui['readonly'] = 'readonly';
									}else{
										unset($nominal_setujui['readonly']);
									}

									echo form_input($nominal_setujui);
								?>
							</div>
						</div>						
					</div>
				</div>
				<?php
				$hidden = '';
				if($form_data_persetujuan['tipe'] == 3){
					$hidden = 'hidden';
					$form_upload_bon = '';
					$form_data_bon = $this->permintaan_biaya_barang_m->get_data_detail($form_data['id'])->result_array();

					if(count($form_data_bon) != 0){
						
						foreach ($form_data_bon as $key => $bon) {
							$form_upload_bon .= '<tr>
							<td>'.$bon['kode_item'].'</td>
							<td style="vertical-align: top !important;">'.$bon['nama_item'].'</td>
							<td style="vertical-align: top !important;">'.$bon['jumlah'].' '.$bon['nama_satuan'].'</td>
							<td style="vertical-align: top !important;">'.formatrupiah($bon['harga']).'</td>
							<td style="vertical-align: top !important;">'.formatrupiah($bon['jumlah'] * $bon['harga']).'</td>
							</tr>';
						}	
					}
				?>
					<div class="col-md-9">
						<div class="portlet light bordered">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject"><?=translate("Daftar Item Kasbon", $this->session->userdata("language"))?></span>
								</div>
							</div>
							<div class="form-body">
							    <div class="portlet-body">
	                               <table class="table table-bordered table-hover">
										<thead>
										<tr role="row" class="heading">
											<th class="text-center" width="5%">
										 		Kode
											</th>
											<th class="text-center" width="10%">
												Nama
											</th>
											<th class="text-center" width="8%">
												Jumlah
											</th>
											<th class="text-center" width="10%">
												Harga
											</th>
											<th class="text-center" width="25%">
												Sub Total
											</th>
										</tr>
										</thead>
										<tbody>
											<?=$form_upload_bon?>
										</tbody>
									</table>  
	                            </div>
							</div>
						</div>
					</div>

				<?php
				}
			?>
				<div class="col-md-9">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Persetujuan", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-body tabel-scrollable">
						    <div class="portlet-body">
						    	<input type="hidden" class="form-control" id="permintaan_biaya_id" name="permintaan_biaya_id" value="<?=$form_data['id']?>"></input>
						    	<input type="hidden" class="form-control" id="persetujuan_permintaan_biaya_id" name="persetujuan_permintaan_biaya_id" value="<?=$form_data_persetujuan['persetujuan_permintaan_biaya_id']?>"></input>
						    	<input type="hidden" class="form-control" id="order" name="order" value="<?=$order?>"></input>
						    	<input type="hidden" class="form-control" id="tipe" name="tipe" value="<?=$form_data_persetujuan['tipe']?>"></input>
                                <input type="hidden" class="form-control" id="tipe_dana" name="tipe_dana" value="<?=$form_data['tipe']?>"></input>
                            
								<?php
									if($form_data['tipe'] == 1){
										?>
										<div class="form-group <?=$hidden?>">
											<label class="col-md-12">
												<?=translate("Nominal", $this->session->userdata("language"))?> :
											</label>		
											<div class="col-md-12">
												<div class="input-group">
												<span class="input-group-addon">
													Rp.
												</span>
													<?php
														$nominal_setujui = array(
															"name"        => "nominal_setujui",
															"id"          => "nominal_setujui",
															"autofocus"   => true,
															"class"       => "form-control", 
															"placeholder" => translate("Nominal", $this->session->userdata("language")), 
															"required"    => "required",
															"value"		=> $form_data['nominal']
														);
														if($form_data['sisa'] > 0){
															$nominal_setujui['readonly'] = 'readonly';
														}else{
															unset($nominal_setujui['readonly']);
														}

														echo form_input($nominal_setujui);
													?>
												<span class="input-group-btn">
													<a type="button" class="btn btn-primary view-agree" data-toggle="modal" data-target="#modal_view" href="<?=base_url()?>keuangan/persetujuan_permintaan_biaya/modal_view/<?=$form_data['id']?>"><i class="fa fa-eye"></i></a>
												</span>
												</div>
											</div>
										</div>
										<?php
									}
									if($form_data['tipe'] == 2){
										$form_upload_bon = '';
										$i = 0;
										if(count($form_data_detail) != 0){
											foreach ($form_data_detail as $key => $bon) {

												$biaya = $this->biaya_m->get_by(array('id' => $bon['biaya_id']), true);

												$form_upload_bon .= '<tr>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][id_bon] id="biaya_id_bon_'.$i.'" value="'.$bon['id'].'"><input type="hidden" name="biaya['.$i.'][biaya_id] id="biaya_id_'.$i.'" value="'.$bon['biaya_id'].'">'.$biaya->nama.'</td>
												<td><a class="fancybox-button" title="'.$bon['url'].'" href="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" alt="Smiley face" class="img-responsive" ></a></td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][no_bon] id="biaya_no_bon_'.$i.'" value="'.$bon['no_bon'].'">'.$bon['no_bon'].'</td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][tanggal] id="biaya_tanggal_'.$i.'" value="'.$bon['tgl_bon'].'">'.date('d M Y', strtotime($bon['tgl_bon'])).'</td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][total_bon] id="biaya_total_bon_'.$i.'" value="'.$bon['total_bon'].'">'.formatrupiah($bon['total_bon']).'</td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][keterangan] id="biaya_keterangan_'.$i.'" value="'.$bon['keterangan'].'">'.$bon['keterangan'].'</td>
												</tr>';

												$i = $i + 1;
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

								<div class="form-group">
									<label class="col-md-12 bold" >
										<?=translate("Keterangan", $this->session->userdata("language"))?> :
									</label>
									<div class="col-md-12">
										
										<?php
											$keterangan = array(
												"name"        => "keterangan",
												"id"          => "keterangan",
												"class"       => "form-control",
												"rows"        => 10, 
												"placeholder" => translate("Keterangan", $this->session->userdata("language")), 
											);
											echo form_textarea($keterangan);
										?>
										<!-- <textarea rows="6" class="form-control" id="description" name="description"></textarea> -->
									</div>
								</div>
                            </div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-actions right">    
		        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
		        	<i class="fa fa-chevron-left"></i>
		        	<?=$back_text?>
		        </a>
		        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
		        <button type="button" id="reject" class="btn btn-primary hidden" ><?=$reset_text?></button>
		        <a id="confirm_reject" class="btn btn-circle red-intense" href="<?=base_url()?>keuangan/persetujuan_permintaan_biaya/reject_proses/<?=$form_data_persetujuan['persetujuan_permintaan_biaya_id']?>/<?=$form_data_persetujuan['permintaan_biaya_id']?>/<?=$form_data_persetujuan['order']?>" data-toggle="modal" data-target="#popup_modal_proses">
		        	<i class="fa fa-times"></i>
		        	<!-- <i class="fa fa-floppy-o"></i> -->
		        	<?=$reset_text?>
		        </a>
		        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
		        <a id="confirm_save" class="btn btn-circle btn-primary" data-confirm="<?=$confirm_save?>" >
		        	<i class="fa fa-check"></i>
		        	<?=$submit_text?>
		        </a>
			</div>

		</div>	
	</div>
</div>

<?=form_close();?>

<div class="modal fade bs-modal-lg" id="modal_view" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>
<div class="modal fade bs-modal-lg" id="popup_modal_proses" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>



