<?php
	$form_attr = array(
		"id"			=> "form_proses", 
		"name"			=> "form_proses", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "persetujuan_biaya_besar",
		"id"		=> $pk_value
	);


	echo form_open(base_url()."keuangan/pembayaran_transaksi/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$confirm_save       = translate('Anda yakin akan memproses pencairan dana ini?',$this->session->userdata('language'));
	$confirm_reject     = translate('Anda yakin akan menolak permintaan biaya ini ?',$this->session->userdata('language'));
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
			<i class="icon-share-alt font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pencairan Dana", $this->session->userdata("language"))?></span> <span><?=$form_data['nomor_permintaan']?></span>
		</div>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=$back_text?>
	        </a>
	        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
	        <a id="confirm_save" class="btn btn-circle btn-primary" data-confirm="<?=$confirm_save?>" >
	        	<i class="fa fa-check"></i>
	        	<?=$submit_text?>
	        </a>
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
								<label><?=translate('Tanggal', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><b><?=date('d-M-Y', strtotime($form_data['tanggal']))?></b></label>
							</div>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Diminta Oleh', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><b><?=$user_minta->nama.' ('.$user_level_minta->nama.')'?></b></label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate('Tipe', $this->session->userdata('language'))?> :</label>
							<div class="col-md-12">
								<?php
									$tipe = '';
									
									if($form_data['tipe'] == 1){
										$tipe = 'Kasbon';
									}if($form_data['tipe'] == 2){
										$tipe = 'Reimburse / Pencairan';
									}

								?>
								<label><b><?=$tipe?></b></label>
							</div>
						</div>

						<?php
							if($form_data['tipe'] == 1){
								$biaya = $this->biaya_m->get_by(array('id' => $form_data['biaya_id']), true);
						?>
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Jenis Biaya', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><b><?=$biaya->nama?></b></label>
							</div>
						</div>
						<?php }
						?>
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Nominal', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><b><?=formatrupiah($form_data['nominal'])?></b></label>
							</div>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><b><?='#'.terbilang($form_data['nominal']).' Rupiah#'?></b></label>
							</div>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Keperluan', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><b><?=$form_data['keperluan']?></b></label>
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
						<div class="form-group hidden">
							<div class="col-md-12">
								<?php
									$keperluan	 = array(
										"name"        => "keperluan",
										"id"          => "keperluan",
										"autofocus"   => true,
										"class"       => "form-control", 
										"value"		=> $form_data['keperluan']
									);
									
									echo form_input($keperluan	);
								?>
							</div>
						</div>						
					</div>
				</div>
				
				<div class="col-md-9">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Detail Biaya", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-body">
						    <div class="portlet-body">
						    	<input type="hidden" class="form-control" id="permintaan_biaya_id" name="permintaan_biaya_id" value="<?=$form_data['id']?>"></input>
						    	
                                <input type="hidden" class="form-control" id="tipe_dana" name="tipe_dana" value="<?=$form_data['tipe']?>"></input>
                            
								<?php
									
									if($form_data['tipe'] == 1 && $form_data['nominal'] >= 1000000){

										foreach ($permintaan_biaya_bayar as $minta_biaya) {
											$data_bank = $this->bank_m->get_by(array('id' => $minta_biaya['bank_id']), true);											

											$biaya_tambahan = $this->biaya_m->get_by(array('id' => $minta_biaya['biaya_tambahan_id']), true);

											$label_biaya_tambahan = '-';
											$jml_biaya_tmbahan = 0;
											if(count($biaya_tambahan) != 0){
												$label_biaya_tambahan = $biaya_tambahan->nama;
												$jml_biaya_tmbahan = $minta_biaya['jumlah_biaya'];
											}
											$biaya = $this->biaya_m->get_by(array('id' => $form_data['biaya_id']), true);


											$attrs_identitas_bank_name = array(
												'id'          => 'bank_name_biaya_bon_0',
												'name'        => 'biaya_bon[0][bank_nama]',
												'class'       => 'form-control',
												'placeholder' => translate('Bank', $this->session->userdata('language')),
												'value'		  => $minta_biaya['nama_bank']
											);

											$attrs_identitas_bank_nomor = array(
												'id'    => 'bank_nomor_biaya_bon_0',
												'name'  => 'biaya_bon[0][bank_no_rek]',
												'class' => 'form-control',
												'data-index' => 0,
												'placeholder' => translate('No. Rekening', $this->session->userdata('language')),
												'value'		  => $minta_biaya['nomor_tipe']
											);

											$attrs_identitas_bank_no_giro = array(
												'id'    => 'bank_no_giro_biaya_bon_0',
												'name'  => 'biaya_bon[0][bank_no_giro]',
												'class' => 'form-control',
												'data-index' => 0,
												'placeholder' => translate('No. Giro', $this->session->userdata('language')),
												'value'		  => $minta_biaya['nomor_tipe']

											);

											$attrs_identitas_bank_no_cek = array(
												'id'    => 'bank_no_cek_biaya_bon_0',
												'name'  => 'biaya_bon[0][bank_no_cek]',
												'class' => 'form-control',
												'data-index' => 0,
												'placeholder' => translate('No. Cek', $this->session->userdata('language')),
												'value'		  => $minta_biaya['nomor_tipe']

											);

											$attrs_identitas_bank_penerima_cek = array(
												'id'    => 'bank_penerima_cek_biaya_bon_0',
												'name'  => 'biaya_bon[0][bank_penerima_cek]',
												'class' => 'form-control',
												'placeholder' => translate('Penerima', $this->session->userdata('language')),
												'value'		  => $minta_biaya['penerima']

											);

											$keterangan = explode("\n", $form_data['keperluan']);

											$jenis_bayar = '';
											$hidden_cek = '';
											$hidden_giro = '';
											$hidden_transfer = '';
											
											if($minta_biaya['pembayaran_tipe'] == 1){
												$jenis_bayar = 'Cek';
												$hidden_cek = '';
												$hidden_giro = 'class="hidden"';
												$hidden_transfer = 'class="hidden"';
											}if($minta_biaya['pembayaran_tipe'] == 2){
												$jenis_bayar = 'Giro';
												$hidden_cek = 'class="hidden"';
												$hidden_giro = '';
												$hidden_transfer = 'class="hidden"';
											}if($minta_biaya['pembayaran_tipe'] == 3){
												$jenis_bayar = 'Transfer';
												$hidden_cek = 'class="hidden"';
												$hidden_giro = 'class="hidden"';
												$hidden_transfer = '';
											}

										
										?>
										<div class="table-scrollable">
											<table class="table table-bordered table-hover" id="table_bayar">
												<thead>
													<tr role="row" class="heading">
														<th class="text-center" width="10%">
															 Biaya
														</th>
														<th class="text-center" width="10%">
															 Total Biaya
														</th>
														<th class="text-center" width="15%">
															 Keterangan
														</th>
														<th class="text-center" width="1%">
															 Jenis Bayar
														</th>
														
														<th class="text-center" width="40%">
															Identitas
														</th>
														<th class="text-center" width="20%">
															Bank
														</th>
														<th class="text-center" width="5%">
															Tgl Buat
														</th>
														<th class="text-center" width="9%">
															Jatuh Tempo
														</th>
														<th class="text-center" width="15%">
															Biaya Lain
														</th>
														
													</tr>
												</thead>
												<tbody>

													<tr id="item_row_0">
														<td style="vertical-align: top !important;"><input type="hidden" id="id_biaya_bon_0" name="biaya_bon[0][biaya_id]" value="<?=$form_data['biaya_id']?>"><?=$biaya->nama?></td>
														<td style="vertical-align: top !important;"><input type="hidden" id="id_biaya_bon_0" name="biaya_bon[0][total_biaya]" value="<?=$form_data['nominal']?>"><?=formatrupiah($form_data['nominal'])?></td>
														<td style="vertical-align: top !important;"><input type="hidden" id="id_biaya_bon_0" name="biaya_bon[0][keterangan]" value="<?=$form_data['keperluan']?>"><?=implode("</br>", $keterangan)?></td>
														<td style="vertical-align: top !important;"><input type="hidden" data-index="0" id="cek_biaya_bon_0" name="biaya_bon[0][tipe]" value="<?=$minta_biaya['pembayaran_tipe']?>"><?=$jenis_bayar?></td>
														
														<td style="vertical-align: top !important;">
															<div id="pilih_trf" <?=$hidden_transfer?>>
																<label class="col-md-12"><?=translate("Bank", $this->session->userdata("language"))?> : </label>
																<label class="col-md-12"><?=$minta_biaya['nama_bank']?> </label>
																<label class="col-md-12"><?=translate("No. Rekening", $this->session->userdata("language"))?> :</label>
																<label class="col-md-12"><?=$minta_biaya['nomor_tipe']?> </label>
															</div>
															<div id="pilih_giro" <?=$hidden_giro?>>
																<div class="form-group">
																	<label class="col-md-12"><?=translate("Bank", $this->session->userdata("language"))?> : </label>
																	<label class="col-md-12"><?=$minta_biaya['nama_bank']?> </label>
																	<label class="col-md-12"><?=translate("No. Giro", $this->session->userdata("language"))?> :</label>
																	<label class="col-md-12"><?=$minta_biaya['nomor_tipe']?> </label>
																</div>
															</div>
															<div id="pilih_cek" <?=$hidden_cek?>>
																<div class="form-group">
																	<label class="col-md-12"><?=translate("No. Cek", $this->session->userdata("language"))?> : </label>
																	<label class="col-md-12"><?=$minta_biaya['nomor_tipe']?> </label>
																	<label class="col-md-12"><?=translate("Penerima", $this->session->userdata("language"))?> :</label>
																	<label class="col-md-12"><?=$minta_biaya['penerima']?> </label>
																</div>
															</div>
														</td>
														<td style="vertical-align: top !important;"><input type="hidden" id="bank_id_biaya_bon_0" name="biaya_bon[0][bank_id]" value="<?=$minta_biaya['bank_id']?>"><?=$data_bank->nob.' a/n '.$data_bank->acc_name.'-'.$data_bank->acc_number?></td>
														<td style="vertical-align: top !important;"><input type="hidden" id="tanggal_biaya_bon_0" name="biaya_bon[0][tanggal]" value="<?=date('Y-m-d', strtotime($minta_biaya['tanggal']))?>"><?=date('d M Y', strtotime($minta_biaya['tanggal']))?></td>
														<td style="vertical-align: top !important;"><?=date('d M Y', strtotime($minta_biaya['jatuh_tempo']))?></td>
														<td style="vertical-align: top !important;" style="width:150px"><input type="hidden" id="jenis_biaya_0" name="biaya_bon[0][biaya_tambah_id]" value="<?=$minta_biaya['biaya_tambahan_id']?>"><input type="hidden" id="jenis_biaya_nama_0" name="biaya_bon[0][biaya_tambah_nama]" value="<?=$label_biaya_tambahan?>"><input type="hidden" id="jenis_biaya_jumlah_0" name="biaya_bon[0][nominal]" value="<?=$minta_biaya['jumlah_biaya']?>"><div class="form-group">
										                        <label class="col-md-12"><?=translate("Jenis Biaya", $this->session->userdata("language"))?> :</label>
										                        <label class="col-md-12"><?=$label_biaya_tambahan?></label>
										                        
										                    </div>
										                    <div class="form-group">
										                        <label class="col-md-12"><?=translate("Jumlah Biaya", $this->session->userdata("language"))?> :</label>
										                        <label class="col-md-12"><?=formatrupiah($jml_biaya_tmbahan)?></label>
										                    </div>
										                </td>
														</tr>
												</tbody>
												<tfoot>
													<tr>
														<th colspan="6" class="text-right" id="th_jenis_bayar_0"><?=$jenis_bayar?></th>
														<th class="text-right" id="th_bank_bayar_0"><?=$data_bank->nob?></th>
														<th class="text-right" id="th_no_cek_bayar_0"><?=$minta_biaya['nomor_tipe']?></th>
														<th class="text-right" id="th_total_cek_bayar_0"><?=formatrupiah($form_data['nominal'])?></th>
													</tr>
													<tr>
														<th colspan="7" class="text-right">Total Kasbon</th>
														<th colspan="2" class="text-right"><?=formatrupiah($form_data['nominal_setujui'])?></th>
													</tr>
													<tr>
														<th colspan="7" class="text-right">Total Biaya Tambahan</th>
														<th colspan="2" class="text-right" id="biaya_tambahan"><?=formatrupiah($jml_biaya_tmbahan)?></th>
													</tr>
													<tr>
														<th colspan="7" class="text-right"><input type="hidden" class="form-control" id="total_bayar" name="total_bayar" value="<?=($form_data['nominal_setujui']+$jml_biaya_tmbahan)?>">Total Pembayaran</th>
														<th colspan="2" class="text-right" id="th_total_bayar"><?=formatrupiah($form_data['nominal_setujui']+$jml_biaya_tmbahan)?></th>

													</tr>
													<tr>
														<th colspan="9" class="text-left" id="th_terbilang">Terbilang : # <?=terbilang($form_data['nominal_setujui']+$jml_biaya_tmbahan)?> #</th>
													</tr>
												</tfoot>
											</table> 
										</div>
										
										<?php
										}
									}
									

								if($form_data['tipe'] == 2 && $form_data['nominal'] >= 1000000){

								?>
								<div class="table-scrollable">
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
												<th class="text-center" width="1%">
													 Jenis Bayar
												</th>
												
												<th class="text-center" width="40%">
													Identitas
												</th>
												<th class="text-center" width="20%">
													Bank
												</th>
												<th class="text-center" width="5%">
													Tgl Buat
												</th>
												<th class="text-center" width="9%">
													Jatuh Tempo
												</th>
												<th class="text-center" width="15%">
													Biaya Lain
												</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$y = 0;
												$total_biaya_tambahan = 0;
												foreach ($permintaan_biaya_bayar as $minta_biaya) {
													$data_bank = $this->bank_m->get_by(array('id' => $minta_biaya['bank_id']), true);										
													$biaya_tambahan = $this->biaya_m->get_by(array('id' => $minta_biaya['biaya_tambahan_id']), true);

													$label_biaya_tambahan = '-';
													$jml_biaya_tmbahan = 0;
													if(count($biaya_tambahan) != 0){
														$label_biaya_tambahan = $biaya_tambahan->nama;
														$jml_biaya_tmbahan = $minta_biaya['jumlah_biaya'];
													}

													$total_biaya_tambahan = $total_biaya_tambahan + $jml_biaya_tmbahan;

													$data_bon = $this->permintaan_biaya_bon_m->get_by(array('id' => $minta_biaya['permintaan_biaya_bon_id']), true);
													$biaya = $this->biaya_m->get_by(array('id' => $data_bon->biaya_id), true);


													$attrs_identitas_bank_name = array(
														'id'          => 'bank_name_biaya_bon_'.$y,
														'name'        => 'biaya_bon['.$y.'][bank_nama]',
														'class'       => 'form-control',
														'placeholder' => translate('Bank', $this->session->userdata('language')),
														'value'		  => $minta_biaya['nama_bank']
													);

													$attrs_identitas_bank_nomor = array(
														'id'    => 'bank_nomor_biaya_bon_'.$y,
														'name'  => 'biaya_bon['.$y.'][bank_no_rek]',
														'class' => 'form-control',
														'data-index' => $y,
														'placeholder' => translate('No. Rekening', $this->session->userdata('language')),
														'value'		  => $minta_biaya['nomor_tipe']
													);

													$attrs_identitas_bank_no_giro = array(
														'id'    => 'bank_no_giro_biaya_bon_'.$y,
														'name'  => 'biaya_bon['.$y.'][bank_no_giro]',
														'class' => 'form-control',
														'data-index' => $y,
														'placeholder' => translate('No. Giro', $this->session->userdata('language')),
														'value'		  => $minta_biaya['nomor_tipe']

													);

													$attrs_identitas_bank_no_cek = array(
														'id'    => 'bank_no_cek_biaya_bon_'.$y,
														'name'  => 'biaya_bon['.$y.'][bank_no_cek]',
														'class' => 'form-control',
														'data-index' => $y,
														'placeholder' => translate('No. Cek', $this->session->userdata('language')),
														'value'		  => $minta_biaya['nomor_tipe']

													);

													$attrs_identitas_bank_penerima_cek = array(
														'id'    => 'bank_penerima_cek_biaya_bon_'.$y,
														'name'  => 'biaya_bon['.$y.'][bank_penerima_cek]',
														'class' => 'form-control',
														'placeholder' => translate('Penerima', $this->session->userdata('language')),
														'value'		  => $minta_biaya['penerima']

													);

													$keterangan = explode("\n", $data_bon->keterangan);

													$jenis_bayar = '';
													$hidden_cek = '';
													$hidden_giro = '';
													$hidden_transfer = '';
													
													if($minta_biaya['pembayaran_tipe'] == 1){
														$jenis_bayar = 'Cek';
														$hidden_cek = '';
														$hidden_giro = 'class="hidden"';
														$hidden_transfer = 'class="hidden"';
													}if($minta_biaya['pembayaran_tipe'] == 2){
														$jenis_bayar = 'Giro';
														$hidden_cek = 'class="hidden"';
														$hidden_giro = '';
														$hidden_transfer = 'class="hidden"';
													}if($minta_biaya['pembayaran_tipe'] == 3){
														$jenis_bayar = 'Transfer';
														$hidden_cek = 'class="hidden"';
														$hidden_giro = 'class="hidden"';
														$hidden_transfer = '';
													}
											?>
											<tr id="item_row_<?=$y?>">
												<td style="vertical-align: top !important;"><input type="hidden" id="id_biaya_bon_<?=$y?>" name="biaya_bon[<?=$y?>][biaya_id]" value="<?=$data_bon->biaya_id?>"><?=$biaya->nama?></td>
												<td style="vertical-align: top !important;"><a class="fancybox-button" title="<?=$data_bon->url?>" href="<?=config_item('site_img_bon').$data_bon->permintaan_biaya_id.'/'.$data_bon->url?>" data-rel="fancybox-button"><img src="<?=config_item('site_img_bon').$data_bon->permintaan_biaya_id.'/'.$data_bon->url?>" alt="Smiley face" class="img-responsive" ></a></td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya[<?=$y?>][no_bon]" id="biaya_no_bon_<?=$y?>" value="<?=$data_bon->no_bon?>"><?=$data_bon->no_bon?></td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya[<?=$y?>][tanggal]" id="biaya_tanggal_<?=$y?>" value="<?=$data_bon->tgl_bon?>"><?=date('d M Y', strtotime($data_bon->tgl_bon))?></td>
												<td style="vertical-align: top !important;"><input type="hidden" id="id_biaya_bon_<?=$y?>" name="biaya_bon[<?=$y?>][total_biaya]" value="<?=$data_bon->total_bon?>"><?=formatrupiah($data_bon->total_bon)?></td>
												<td style="vertical-align: top !important;"><input type="hidden" id="id_biaya_bon_<?=$y?>" name="biaya_bon[<?=$y?>][keterangan]" value="<?=$data_bon->keterangan?>"><?=implode("</br>", $keterangan)?></td>
												<td style="vertical-align: top !important;"><input type="hidden" data-index="<?=$y?>" id="cek_biaya_bon_<?=$y?>" name="biaya_bon[<?=$y?>][tipe]" value="<?=$minta_biaya['pembayaran_tipe']?>"><?=$jenis_bayar?></td>
												
												<td style="vertical-align: top !important;">
													<div id="pilih_trf" <?=$hidden_transfer?>>
														<label class="col-md-12"><?=translate("Bank", $this->session->userdata("language"))?> : </label>
														<label class="col-md-12"><?=$minta_biaya['nama_bank']?> </label>
														<label class="col-md-12"><?=translate("No. Rekening", $this->session->userdata("language"))?> :</label>
														<label class="col-md-12"><?=$minta_biaya['nomor_tipe']?> </label>
													</div>
													<div id="pilih_giro" <?=$hidden_giro?>>
														<div class="form-group">
															<label class="col-md-12"><?=translate("Bank", $this->session->userdata("language"))?> : </label>
															<label class="col-md-12"><?=$minta_biaya['nama_bank']?> </label>
															<label class="col-md-12"><?=translate("No. Giro", $this->session->userdata("language"))?> :</label>
															<label class="col-md-12"><?=$minta_biaya['nomor_tipe']?> </label>
														</div>
													</div>
													<div id="pilih_cek" <?=$hidden_cek?>>
														<div class="form-group">
															<label class="col-md-12"><?=translate("No. Cek", $this->session->userdata("language"))?> : </label>
															<label class="col-md-12"><?=$minta_biaya['nomor_tipe']?> </label>
															<label class="col-md-12"><?=translate("Penerima", $this->session->userdata("language"))?> :</label>
															<label class="col-md-12"><?=$minta_biaya['penerima']?> </label>
														</div>
													</div>
												</td>
												<td style="vertical-align: top !important;"><input type="hidden" id="bank_id_biaya_bon_<?=$y?>" name="biaya_bon[<?=$y?>][bank_id]" value="<?=$minta_biaya['bank_id']?>"><?=$data_bank->nob.' a/n '.$data_bank->acc_name.'-'.$data_bank->acc_number?></td>
												<td style="vertical-align: top !important;"><input type="hidden" id="tanggal_biaya_bon_<?=$y?>" name="biaya_bon[<?=$y?>][tanggal]" value="<?=date('Y-m-d', strtotime($minta_biaya['tanggal']))?>"><?=date('d M Y', strtotime($minta_biaya['tanggal']))?></td>
												<td style="vertical-align: top !important;"><?=date('d M Y', strtotime($minta_biaya['jatuh_tempo']))?></td>
												<td style="vertical-align: top !important;" style="width:150px"><input type="hidden" id="jenis_biaya_<?=$y?>" name="biaya_bon[<?=$y?>][biaya_tambah_id]" value="<?=$minta_biaya['biaya_tambahan_id']?>"><input type="hidden" id="jenis_biaya_nama_<?=$y?>" name="biaya_bon[<?=$y?>][biaya_tambah_nama]" value="<?=$label_biaya_tambahan?>"><input type="hidden" id="jenis_biaya_jumlah_<?=$y?>" name="biaya_bon[<?=$y?>][nominal]" value="<?=$minta_biaya['jumlah_biaya']?>"><div class="form-group">
								                        <label class="col-md-12"><?=translate("Jenis Biaya", $this->session->userdata("language"))?> :</label>
								                        <label class="col-md-12"><?=$label_biaya_tambahan?></label>
								                        
								                    </div>
								                    <div class="form-group">
								                        <label class="col-md-12"><?=translate("Jumlah Biaya", $this->session->userdata("language"))?> :</label>
								                        <label class="col-md-12"><?=formatrupiah($jml_biaya_tmbahan)?></label>
								                    </div>
								                </td>
											</tr>
											<?php
													$y++;
												}
											?>
										</tbody>
										<tfoot>
											<?php
												foreach ($permintaan_biaya_bayar as $minta_biaya) {
													$data_bank = $this->bank_m->get_by(array('id' => $minta_biaya['bank_id']), true);
													$data_bon = $this->permintaan_biaya_bon_m->get_by(array('id' => $minta_biaya['permintaan_biaya_bon_id']), true);
													$jenis_bayar = "";
													if($minta_biaya['pembayaran_tipe'] == 1){
														$jenis_bayar = 'Cek';
														
													}if($minta_biaya['pembayaran_tipe'] == 2){
														$jenis_bayar = 'Giro';
														$hidden_transfer = 'class="hidden"';
													}if($minta_biaya['pembayaran_tipe'] == 3){
														$jenis_bayar = 'Transfer';
													}
											?>
											<tr>
												<th colspan="9" class="text-right" id="th_jenis_bayar_0"><?=$jenis_bayar?></th>
												<th class="text-right" id="th_bank_bayar_0"><?=$data_bank->nob?></th>
												<th class="text-right" id="th_no_cek_bayar_0"><?=$minta_biaya['nomor_tipe']?></th>
												<th class="text-right" id="th_total_cek_bayar_0"><?=formatrupiah($data_bon->total_bon)?></th>
											</tr>
											<?php
												}
											?>
											<tr>
												<th colspan="10" class="text-right">Total Kasbon</th>
												<th colspan="2" class="text-right"><?=formatrupiah($form_data['nominal_setujui'])?></th>
											</tr>
											<tr>
												<th colspan="10" class="text-right">Total Biaya Tambahan</th>
												<th colspan="2" class="text-right" id="biaya_tambahan"><?=formatrupiah($total_biaya_tambahan)?></th>
											</tr>
											<tr>
												<th colspan="10" class="text-right"><input type="hidden" class="form-control" id="total_bayar" name="total_bayar" value="<?=$form_data['nominal']?>">Total Pembayaran</th>
												<th colspan="2" class="text-right" id="th_total_bayar"><?=formatrupiah($form_data['nominal_setujui'])?></th>

											</tr>
											<tr>
												<th colspan="12" class="text-left" id="th_terbilang">Terbilang : # <?=terbilang($form_data['nominal_setujui'])?> #</th>
											</tr>
										</tfoot>
	
									</table>
								</div>
								<?php
									}

									if($form_data['tipe'] == 2 && $form_data['nominal'] < 1000000){
										$form_upload_bon = '';
										$x = 0;
										if(count($form_data_detail) != 0){
											foreach ($form_data_detail as $key => $bon) {
												$biaya = $this->biaya_m->get_by(array('id' => $bon['biaya_id']), true);
												$form_upload_bon .= '<tr>
												<td style="vertical-align: top !important;">'.$biaya->nama.'</td>
												<td><a class="fancybox-button" title="'.$bon['url'].'" href="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" alt="Smiley face" class="img-responsive" ></a></td>
												<td style="vertical-align: top !important;">'.$bon['no_bon'].'</td>
												<td style="vertical-align: top !important;"><input type="hidden" id="tanggal_biaya_bon_'.$x.'" name="biaya_bon['.$x.'][tanggal]" value="'.date('Y-m-d', strtotime($bon['tgl_bon'])).'">'.date('d M Y', strtotime($bon['tgl_bon'])).'</td>
												<td style="vertical-align: top !important;"><input type="hidden" id="total_biaya_bon_'.$x.'" name="biaya_bon['.$x.'][total_biaya]" value="'.$bon['total_bon'].'">'.formatrupiah($bon['total_bon']).'</td>
												<td style="vertical-align: top !important;"><input type="hidden" id="total_biaya_bon_'.$x.'" name="biaya_bon['.$x.'][keterangan]" value="'.$bon['keterangan'].'">'.$bon['keterangan'].'</td>
												</tr>';

												$x++;
											}	
										}
										
										?>

										<table class="table table-bordered table-hover">
											<thead>
											<tr role="row" class="heading">
												<th class="text-center" width="8%">
											 		Biaya
												</th>
												<th class="text-center" width="8%">
											 		Image
												</th>
												<th class="text-center" width="10%">
													 No. Bon
												</th>
												<th class="text-center" width="8%">
													 Tgl. Bon
												</th>
												<th class="text-center" width="10%">
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
									</div>
									               
										<?php
									}
								?>

								<div class="form-group">
									<label class="col-md-12" >
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



