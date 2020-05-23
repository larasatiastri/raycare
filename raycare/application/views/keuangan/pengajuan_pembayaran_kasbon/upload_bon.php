<?php

	$form_attr = array(
		"id"			=> "form_add_pengajuan_pembayaran_kasbon", 
		"name"			=> "form_add_pengajuan_pembayaran_kasbon", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "upload_bon",
		"id"		=> $pk_value
	);


	echo form_open(base_url()."keuangan/pengajuan_pembayaran_kasbon/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

    $user_id = $form_data['created_by'];
    $user = $this->user_m->get($user_id);
    $user_level_id = $user->user_level_id;
    $nama_user = $user->nama;

    $user_setuju_id = $form_data['disetujui_oleh'];
    $user_setuju = $this->user_m->get($user_setuju_id);

    $tgl_setuju = date('d M Y', strtotime($form_data['created_date']));


?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
		<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("PENGAJUAN PEMBAYARAN KASBON", $this->session->userdata("language"))?></span>
		</div>

		<?php
			$confirm_save       = translate('Anda yakin untuk mengupdate pengajuan pembayaran kasbon ini?',$this->session->userdata('language'));
			$submit_text        = translate('Simpan', $this->session->userdata('language'));
			$reset_text         = translate('Reset', $this->session->userdata('language'));
			$back_text          = translate('Kembali', $this->session->userdata('language'));
		?>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=$back_text?>
	        </a>
	        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
	        <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal">
	        	<i class="fa fa-check"></i>
	        	<?=$submit_text?>
	        </a>
	        
		</div>
	</div>
	<?php
	if($form_data['status'] == 3){
		?>
	<div class="note note-danger note-bordered">
		<p>
			 NOTE: <?=$form_data['keterangan_tolak'].'. Created By: '.$user_setuju->nama.', Created Date: '.$tgl_setuju?>
		</p>
	</div>
	<?php
	}
	?>
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
								<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?> :</span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('Tanggal', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12"><?=date('d M Y', strtotime($form_data['tanggal']))?></label>
						</div>
						 
						<div class="form-group">
							<label class="col-md-12"><?=translate('Dibuat Oleh', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12"><?=$nama_user?></label>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate('Subjek', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12"><?=$form_data['subjek']?></label>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('No. Cek', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12"><?=$form_data['no_cek']?></label>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate('Nominal', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12"><?=formatrupiah($form_data['nominal'])?></label>
							<input type="hidden" class="form-control" type="number" id="nominal" name="nominal" value="<?=$form_data['nominal']?>">

						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate('Terbilang', $this->session->userdata('language'))?></label>
							<label class="col-md-12" id="terbilang">#<?=terbilang($form_data['nominal'])?> Rupiah #</label>

						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Kirim dari Bank", $this->session->userdata("language"))?> :</label>		
							<input class="form-control" type="hidden" name="bank_id" id="bank_id" value="<?=$form_data['bank_id']?>"></input>		

								<?php
									$banks = $this->bank_m->get($form_data['bank_id']);	
								?>
							<label class="col-md-12"><?=$banks->nob.' a/n '.$banks->acc_name?></label>
						</div>
						<?php
							if($form_data['status'] >= 4){
								?>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Bukti Cek", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<input type="hidden" name="url_cek" id="url_cek" value="<?=$form_data['url_cek']?>">
									<div id="upload">
										<ul class="ul-img">
										<li class="working">
											<div class="thumbnail">
												<a class="fancybox-button" title="<?=$form_data['url_cek']?>" href="<?=base_url().'assets/mb/pages/keuangan/pengajuan_pembayaran_kasbon/images/'.$pk_value.'/'.$form_data['url_cek']?>" data-rel="fancybox-button"><img src="<?=base_url().'assets/mb/pages/keuangan/pengajuan_pembayaran_kasbon/images/'.$pk_value.'/'.$form_data['url_cek']?>" alt="Smiley face" class="img-thumbnail" ></a>
											</div>
										</li>
										</ul>

									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-12"><?=translate('Total Biaya', $this->session->userdata('language'))?> :</label>
								<label class="col-md-12" id="label_total_biaya"></label>

							</div>

							<div class="form-group">
								<label class="col-md-12"><?=translate('Sisa Kasbon', $this->session->userdata('language'))?></label>
								<div class="col-md-12">
									<div class="input-group">
										<span class="input-group-addon">
											Rp.
										</span>
										<input class="form-control" type="number" id="nominal_sisa" name="nominal_sisa" placeholder="Nominal Sisa" required>
										
									</div>
									<span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-12"><?=translate('Terbilang Sisa', $this->session->userdata('language'))?></label>
								<label class="col-md-12" id="terbilang_sisa"></label>

							</div>

							<div class="form-group">
								<div class="col-md-12">
									<input type="hidden" name="url_bukti_setor" id="url_bukti_setor">
									<div id="upload">
										<span class="btn default btn-file">
											<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>	
											<input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload/upload_photo" />
										</span>

										<ul class="ul-img-setor">
										<!-- The file uploads will be shown here -->
										</ul>

									</div>
								</div>
							</div>
								<?php
							}
						?>

					</div>
					
				</div>
				<div class="col-md-9" id="section-Keterangan">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Kasbon", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-body">
						    <div class="portlet-body">
								<table class="table table-condensed table-striped table-bordered table-hover" id="tabel_kasbon">
	                                <thead>
	                                    <tr>
	                                        <th class="text-center" width="15%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="20%"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="15%"><?=translate("Rupiah", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="15%"><?=translate("Biaya Real", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("Keperluan", $this->session->userdata("language"))?> </th>
											<th class="text-center" ><?=translate("PO", $this->session->userdata("language"))?> </th>
	                                    </tr>
	                                </thead>
	                                <tbody>	
	                                	<?php
	                                		$i = 0;
	                                		foreach ($form_data_detail as $detail) {
	                                			?>
	                                		<tr id="item_row_<?=$detail['permintaan_biaya_id']?>">
	                                			<td class="text-center"><input type="hidden" class="form-control" id="kasbon_tanggal_<?=$i?>" name="kasbon[<?=$i?>][tanggal]" value="<?=date('d M Y', strtotime($detail['tanggal']))?>"><?=date('d M Y', strtotime($detail['tanggal']))?></td>
	                                			<td class="text-left"><input type="hidden" class="form-control" id="kasbon_id_<?=$i?>" name="kasbon[<?=$i?>][id]" value="<?=$detail['permintaan_biaya_id']?>"><?=$detail['diminta_oleh']?></td>
	                                			<td class="text-right"><?=formatrupiah($detail['nominal_setujui'])?></td>
	                                			<td class="text-right"><div class="input-group">
															<span class="input-group-addon">
																Rp.
															</span>
															<input class="form-control" type="number" id="kasbon_total_<?=$i?>" name="kasbon[<?=$i?>][total]" placeholder="Nominal" required value="<?=$detail['nominal_setujui']?>">
															
														</div></td>
	                                			<td class="text-left"><div id="container_bon"></div><?=$detail['keperluan']?></td>
	                                			<td class="text-left">
	                                				<div class="input-group">
														<?php
															$no_po = array(
																"id"        => "kasbon_no_po_".$i,
																"name"      => "kasbon[".$i."][no_po]",
																"autofocus" => true,
																"class"     => "form-control", 
																"readonly"  => "readonly"
															);

															$id_po = array(
																"id"          => "kasbon_id_po_".$i,
																"name"        => "kasbon[".$i."][id_po]",
																"autofocus"   => true,
																"class"       => "form-control hidden",
															);
															echo form_input($no_po).form_input($id_po);
														?>
														<span class="input-group-btn">
															<a class="btn btn-primary pilih-po" title="<?=translate('Pilih PO', $this->session->userdata('language'))?>">
																<i class="fa fa-search"></i>
															</a>
														</span>
													</div>
	                                			</td>
	                                		</tr>
	                                			<?php
	                                			$i++;
	                                		}
	                                	?>
	                                </tbody>
	                            </table>
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
												<label class="col-md-12">'.translate("ID", $this->session->userdata("language")).' :</label>
												<div class="col-md-12">
													<input class="form-control" id="id_bon{0}" name="bon[{0}][id]">
													<input class="form-control" id="is_active_bon{0}" name="bon[{0}][is_active]">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12">'.translate("Pilih Kasbon", $this->session->userdata("language")).' :</label>
												<div class="col-md-12">';

												$kasbon_option = array();
												foreach ($form_data_detail as $detail) {
													$kasbon_option[$detail['permintaan_biaya_id']] = date('d M Y', strtotime($detail['tanggal'])).' - '.formatrupiah($detail['nominal_setujui']).' ['.$detail['diminta_oleh'].' ('.$detail['keperluan'].')]';
												}
										$form_upload_bon .=	form_dropdown('bon[{0}][kasbon_id]', $kasbon_option, '', 'class="form-control" id="kasbon_id_bon{0}"');	
										$form_upload_bon .=	'</div>
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
													<input class="form-control" required id="total_bon_{0}" name="bon[{0}][total_bon]" placeholder="Total Bon">
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
										<div class="form-body" id="list_containt">
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

<div class="modal fade bs-modal-lg" id="modal_bon" role="basic" aria-hidden="true">
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
<div class="modal fade bs-modal-lg" id="modal_po" role="basic" aria-hidden="true">
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

<div id="popover_item_content" class="row" style="display:none;">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_po">
            <thead>
                <tr>
					<th class="text-center" width="1%"><?=translate("No PO", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="20%"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Nominal", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				</tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


