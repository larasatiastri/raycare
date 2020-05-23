<?php
	$form_attr = array(
		"id"			=> "form_proses", 
		"name"			=> "form_proses", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "persetujuan_biaya_besar"
	);


	echo form_open(base_url()."keuangan/proses_pembayaran_reimburse/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$confirm_save       = translate('Anda yakin akan menyetujui permintaan biaya ini ?',$this->session->userdata('language'));
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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Persetujuan Permintaan Biaya", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=$back_text?>
	        </a>
	        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
	        <button type="button" id="reject" class="btn btn-primary hidden" ><?=$reset_text?></button>
	        <a id="confirm_reject" class="btn btn-circle red-intense" href="<?=base_url()?>keuangan/proses_pembayaran_transaksi/reject_proses/<?=$form_data['id']?>/<?=$form_data['tipe']?>" data-toggle="modal" data-target="#popup_modal_proses">
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
								<input type="hidden" name="pegawai_id" id="pegawai_id" value="<?=$form_data['diminta_oleh_id']?>">
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
					</div>
				</div>
				
				<div class="col-md-9">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Persetujuan", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-body">
						    <div class="portlet-body">
						    	<input type="hidden" class="form-control" id="permintaan_biaya_id" name="permintaan_biaya_id" value="<?=$form_data['id']?>"></input>
						    	
                                <input type="hidden" class="form-control" id="tipe_dana" name="tipe_dana" value="<?=$form_data['tipe']?>"></input>
                            
								<?php
									if($form_data['tipe'] == 1 && $form_data['nominal'] < 1000000){
										?>
										<div class="form-group">
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
												
												</div>
											</div>
										</div>
										<?php
									}
									if($form_data['tipe'] == 1 && $form_data['nominal'] >= 1000000){

										$btn_search        = '<a class="btn btn-primary search-bank" data-status-row="item_row_add" title="'.translate('Pilih Bank', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a>';


										$data_bank = $this->bank_m->get_by(array('is_active' => 1));

										$bank_option = array();

										foreach ($data_bank as $bank) 
										{
											$bank_option[$bank->id] = $bank->nob.' A/C No.'. $bank->acc_number;
										}

										$biaya_option = array(
											''	=> translate('Pilih', $this->session->userdata('language')).'...'
										);

										$biaya = $this->biaya_m->get_by(array('is_active' => 1));

										foreach ($biaya as $row) {
											$biaya_option[$row->id] = $row->nama;
										}
										$biaya = $this->biaya_m->get_by(array('id' => $form_data['biaya_id']), true);

										$attrs_identitas_bank_id = array(
											'id'    => 'bank_id_biaya_bon_0',
											'name'  => 'biaya_bon[0][bank_pegawai_id]',
											'class' => 'form-control',
											'type'	=> 'hidden'
										);


										$attrs_identitas_bank_name = array(
											'id'          => 'bank_name_biaya_bon_0',
											'name'        => 'biaya_bon[0][bank_nama]',
											'class'       => 'form-control',
											'placeholder' => translate('Bank', $this->session->userdata('language'))
										);

										$attrs_identitas_bank_nomor = array(
											'id'    => 'bank_nomor_biaya_bon_0',
											'name'  => 'biaya_bon[0][bank_no_rek]',
											'class' => 'form-control',
											'data-index' => 0,
											'placeholder' => translate('No. Rekening', $this->session->userdata('language'))
										);

										$attrs_identitas_bank_no_giro = array(
											'id'    => 'bank_no_giro_biaya_bon_0',
											'name'  => 'biaya_bon[0][bank_no_giro]',
											'class' => 'form-control',
											'data-index' => 0,
											'placeholder' => translate('No. Giro', $this->session->userdata('language'))
										);

										$attrs_identitas_bank_no_cek = array(
											'id'    => 'bank_no_cek_biaya_bon_0',
											'name'  => 'biaya_bon[0][bank_no_cek]',
											'class' => 'form-control',
											'data-index' => 0,
											'placeholder' => translate('No. Cek', $this->session->userdata('language'))
										);

										$attrs_identitas_bank_penerima_cek = array(
											'id'    => 'bank_penerima_cek_biaya_bon_0',
											'name'  => 'biaya_bon[0][bank_penerima_cek]',
											'class' => 'form-control',
											'placeholder' => translate('Penerima', $this->session->userdata('language'))
										);

										$keterangan = explode("\n", $form_data['keperluan']);
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
															 Cek
														</th>
														<th class="text-center" width="1%">
															 Giro
														</th>
														<th class="text-center" width="1%">
															 TT
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
														<td style="vertical-align: top !important;"><input type="radio" data-index="0" data-text="Cek" id="cek_biaya_bon_0" name="biaya_bon[0][tipe]" value="1" checked></td>
														<td style="vertical-align: top !important;"><input type="radio" data-index="0" data-text="Giro" id="giro_biaya_bon_0" name="biaya_bon[0][tipe]" value="2" class=""></td>
														<td style="vertical-align: top !important;"><input type="radio" data-index="0" data-text="Transfer" id="trf_biaya_bon_0" name="biaya_bon[0][tipe]" value="3" class=""></td>
														<td style="vertical-align: top !important;">
															<div id="pilih_trf" class="hidden">
																<div class="form-group">
																	<div class="col-md-12">
																		<div class="input-group">
																		<?=form_input($attrs_identitas_bank_name)?>
																		<span class="input-group-btn">
																		<?=form_input($attrs_identitas_bank_id).$btn_search?>
																		</span>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-md-12"><?=form_input($attrs_identitas_bank_nomor)?></div>
																</div>
															</div>
															<div id="pilih_giro" class="hidden">
																<div class="form-group">
																	<div class="col-md-12">
																		<?=form_input($attrs_identitas_bank_name)?>
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-md-12">
																		<?=form_input($attrs_identitas_bank_no_giro)?>
																	</div>
																</div>
															</div>
															<div id="pilih_cek">
																<div class="form-group">
																	<div class="col-md-12">
																		<?=form_input($attrs_identitas_bank_no_cek)?>
																	</div>
																	<div class="col-md-12">
																		<?=form_input($attrs_identitas_bank_penerima_cek)?>
																	</div>
																</div>
															</div>
														</td>
														<td style="vertical-align: top !important;"><?=form_dropdown('biaya_bon[0][bank_id]', $bank_option, '', 'id="bank_id_biaya_bon_0" class="form-control" style="width:150px" data-index="0"')?></td>
														<td style="vertical-align: top !important;"><div class="input-group date">
																<input type="text" class="form-control" id="tanggal_biaya_bon_0" name="biaya_bon[0][tanggal]" value="<?=date('d M Y')?>" readonly  >
																<span class="input-group-btn">
																	<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
																</span>
															</div>
														</td>
														<td style="vertical-align: top !important;"><div class="input-group date">
																<input type="text" class="form-control" id="jatuh_tempo_biaya_bon_0" name="biaya_bon[0][jatuh_tempo]" value="<?=date('d M Y')?>" readonly >
																<span class="input-group-btn">
																	<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
																</span>
															</div></td>
														<td style="vertical-align: top !important;" style="width:150px"><div class="form-group">
										                        <label class="col-md-12"><?=translate("Jenis Biaya", $this->session->userdata("language"))?> :</label>
										                        <div class="col-md-12">
										                            <div class="input-group"><?=form_dropdown('biaya_bon[0][biaya_tambah_id]', $biaya_option, '','id="jenis_biaya_0" class="form-control" style="width:150px"')?>
										                            </div>
										                        </div>
										                    </div>
										                    <div class="form-group">
										                        <label class="col-md-12"><?=translate("Jumlah Biaya", $this->session->userdata("language"))?> :</label>
										                        <div class="col-md-12">
										                                <div class="input-group">
										                                    <span class="input-group-addon">
										                                        Rp.
										                                    </span>
										                                    <input class="form-control jumlah" id="jumlah_biaya_0" name="biaya_bon[0][nominal]" placeholder="Jumlah Biaya" style="width:150px">
										                                </div>
										                                <span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
										                        </div>
										                    </div>
										                </td>
														</tr>
												</tbody>
												<tfoot>
													<tr>
														<th colspan="8" class="text-right" id="th_jenis_bayar_0">Jenis Bayar 0</th>
														<th class="text-right" id="th_bank_bayar_0">Bank</th>
														<th class="text-right" id="th_no_cek_bayar_0"></th>
														<th class="text-right" id="th_total_cek_bayar_0"></th>
													</tr>
													<tr>
														<th colspan="9" class="text-right">Total Kasbon</th>
														<th colspan="2" class="text-right"><?=formatrupiah($form_data['nominal'])?></th>
													</tr>
													<tr>
														<th colspan="9" class="text-right">Total Biaya Tambahan</th>
														<th colspan="2" class="text-right" id="biaya_tambahan"></th>
													</tr>
													<tr>
														<th colspan="9" class="text-right"><input type="hidden" class="form-control" id="total_bayar" name="total_bayar" value="<?=$form_data['nominal']?>">Total Pembayaran</th>
														<th colspan="2" class="text-right" id="th_total_bayar"><?=formatrupiah($form_data['nominal'])?></th>

													</tr>
													<tr>
														<th colspan="11" class="text-left" id="th_terbilang">Terbilang : # <?=terbilang($form_data['nominal'])?> Rupiah #</th>
													</tr>
												</tfoot>
											</table> 
										</div>
										 
										
										<?php
									}
									if($form_data['tipe'] == 2 && $form_data['nominal'] > 0){
										
										$btn_search        = '<a class="btn btn-primary search-bank" data-status-row="item_row_add" title="'.translate('Pilih Bank', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a>';

										$data_bank = $this->bank_m->get_by(array('is_active' => 1));

										$bank_option = array();

										foreach ($data_bank as $bank) 
										{
											$bank_option[$bank->id] = $bank->nob.' A/C No.'. $bank->acc_number;
										}

										$biaya_option = array(
											''	=> translate('Pilih', $this->session->userdata('language')).'...'
										);

										$biaya = $this->biaya_m->get_by(array('is_active' => 1));

										foreach ($biaya as $row) {
											$biaya_option[$row->id] = $row->nama;
										}
										$biaya = $this->biaya_m->get_by(array('id' => $form_data['biaya_id']), true);

										$i = 0;
										$form_upload_bon_besar = '';
										if(count($form_data_detail) != 0){
											foreach ($form_data_detail as $key => $bon) {

												$attrs_identitas_bank_id = array(
													'id'    => 'bank_id_biaya_bon_0',
													'name'  => 'biaya_bon[0][bank_pegawai_id]',
													'class' => 'form-control',
													'type'	=> 'hidden'
												);

												$attrs_identitas_bank_name = array(
													'id'          => 'bank_name_biaya_bon_'.$i,
													'name'        => 'biaya_bon['.$i.'][bank_nama]',
													'class'       => 'form-control',
													'placeholder' => translate('Bank', $this->session->userdata('language'))
												);

												$attrs_identitas_bank_nomor = array(
													'id'    => 'bank_nomor_biaya_bon_'.$i,
													'name'  => 'biaya_bon['.$i.'][bank_no_rek]',
													'class' => 'form-control',
													'data-index' => $i,
													'placeholder' => translate('No. Rekening', $this->session->userdata('language'))
												);

												$attrs_identitas_bank_no_giro = array(
													'id'    => 'bank_no_giro_biaya_bon_'.$i,
													'name'  => 'biaya_bon['.$i.'][bank_no_giro]',
													'class' => 'form-control',
													'data-index' => $i,
													'placeholder' => translate('No. Giro', $this->session->userdata('language'))
												);

												$attrs_identitas_bank_no_cek = array(
													'id'    => 'bank_no_cek_biaya_bon_'.$i,
													'name'  => 'biaya_bon['.$i.'][bank_no_cek]',
													'class' => 'form-control',
													'data-index' => $i,
													'placeholder' => translate('No. Cek', $this->session->userdata('language'))
												);

												$attrs_identitas_bank_penerima_cek = array(
													'id'    => 'bank_penerima_cek_biaya_bon_'.$i,
													'name'  => 'biaya_bon['.$i.'][bank_penerima_cek]',
													'class' => 'form-control',
													'placeholder' => translate('Penerima', $this->session->userdata('language'))
												);

												$biaya = $this->biaya_m->get_by(array('id' => $bon['biaya_id']), true);

												$form_upload_bon_besar .= '<tr id="item_row_'.$i.'">
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][id_bon]" id="biaya_id_bon_'.$i.'" value="'.$bon['id'].'"><input type="hidden" name="biaya['.$i.'][biaya_id] id="biaya_id_'.$i.'" value="'.$bon['biaya_id'].'">'.$biaya->nama.'</td>
												<td style="vertical-align: top !important;"><a class="fancybox-button" title="'.$bon['url'].'" href="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" alt="Smiley face" class="img-responsive" ></a></td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya_bon['.$i.'][permintaan_biaya_bon_id]" id="biaya_permintaan_biaya_bon_id_'.$i.'" value="'.$bon['id'].'"><input type="hidden" name="biaya['.$i.'][no_bon]" id="biaya_no_bon_'.$i.'" value="'.$bon['no_bon'].'">'.$bon['no_bon'].'</td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][tanggal]" id="biaya_tanggal_'.$i.'" value="'.$bon['tgl_bon'].'">'.date('d M Y', strtotime($bon['tgl_bon'])).'</td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya_bon['.$i.'][total_biaya]" id="biaya_total_bon_'.$i.'" value="'.$bon['total_bon'].'">'.formatrupiah($bon['total_bon']).'</td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][keterangan]" id="biaya_keterangan_'.$i.'" value="'.$bon['keterangan'].'">'.$bon['keterangan'].'</td>
												<td style="vertical-align: top !important;"><input type="radio" data-index="'.$i.'" data-text="Cek" id="cek_biaya_bon_'.$i.'" name="biaya_bon['.$i.'][tipe]" value="1" checked></td>
												<td style="vertical-align: top !important;"><input type="radio" data-index="'.$i.'" data-text="Giro" id="giro_biaya_bon_'.$i.'" name="biaya_bon['.$i.'][tipe]" value="2" class=""></td>
												<td style="vertical-align: top !important;"><input type="radio" data-index="'.$i.'" data-text="Transfer" id="trf_biaya_bon_'.$i.'" name="biaya_bon['.$i.'][tipe]" value="3" class=""></td>
												<td style="vertical-align: top !important;">
															<div id="pilih_trf" class="hidden">
																<div class="form-group">
																	<div class="col-md-12">
																		<div class="input-group">
																		'.form_input($attrs_identitas_bank_name).'
																		<span class="input-group-btn">
																		'.form_input($attrs_identitas_bank_id).$btn_search.'
																		</span>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-md-12">'.form_input($attrs_identitas_bank_nomor).'</div>
																</div>
															</div>
															<div id="pilih_giro" class="hidden">
																<div class="form-group">
																	<div class="col-md-12">
																		'.form_input($attrs_identitas_bank_name).'
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-md-12">
																		'.form_input($attrs_identitas_bank_no_giro).'
																	</div>
																</div>
															</div>
															<div id="pilih_cek">
																<div class="form-group">
																	<div class="col-md-12">
																		'.form_input($attrs_identitas_bank_no_cek).'
																	</div>
																	<div class="col-md-12">
																		'.form_input($attrs_identitas_bank_penerima_cek).'
																	</div>
																</div>
															</div>
														</td>
														<td style="vertical-align: top !important;">'.form_dropdown('biaya_bon['.$i.'][bank_id]', $bank_option, '', 'id="bank_id_biaya_bon_'.$i.'" class="form-control" style="width:150px" data-index="'.$i.'"').'</td>
														<td style="vertical-align: top !important;"><div class="input-group date">
																<input type="text" class="form-control" id="tanggal_biaya_bon_'.$i.'" name="biaya_bon['.$i.'][tanggal]" value="'.date('d M Y').'" readonly  >
																<span class="input-group-btn">
																	<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
																</span>
															</div>
														</td>
														<td style="vertical-align: top !important;"><div class="input-group date">
																<input type="text" class="form-control" id="jatuh_tempo_biaya_bon_'.$i.'" name="biaya_bon['.$i.'][jatuh_tempo]" value="'.date('d M Y').'" readonly >
																<span class="input-group-btn">
																	<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
																</span>
															</div></td>
														<td style="vertical-align: top !important;" style="width:150px"><div class="form-group">
										                        <label class="col-md-12">'.translate("Jenis Biaya", $this->session->userdata("language")).' :</label>
										                        <div class="col-md-12">
										                            <div class="input-group">'.form_dropdown('biaya_bon['.$i.'][biaya_tambah_id]', $biaya_option, '','id="jenis_biaya_'.$i.'" class="form-control" style="width:150px"').'
										                            </div>
										                        </div>
										                    </div>
										                    <div class="form-group">
										                        <label class="col-md-12">'.translate("Jumlah Biaya", $this->session->userdata("language")).' :</label>
										                        <div class="col-md-12">
										                                <div class="input-group">
										                                    <span class="input-group-addon">
										                                        Rp.
										                                    </span>
										                                    <input class="form-control jumlah" id="jumlah_biaya_'.$i.'" name="biaya_bon['.$i.'][nominal]" placeholder="Jumlah Biaya" style="width:150px">
										                                </div>
										                                <span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
										                        </div>
										                    </div>
										                </td>
												</tr>';

												$i = $i + 1;
											}	
										}
								?>
								<div class="table-scrollable">
	                               <table class="table table-bordered table-hover" id="table_bayar">
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
													 Cek
												</th>
												<th class="text-center" width="1%">
													 Giro
												</th>
												<th class="text-center" width="1%">
													 TT
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
											<?=$form_upload_bon_besar?>
										</tbody>
										<tfoot>
										<?php
										$x = 0;
										foreach ($form_data_detail as $key => $bon) {
											?>
											<tr>
												<th colspan="11" class="text-right" id="th_jenis_bayar_<?=$x?>">Jenis Bayar <?=$x?></th>
												<th class="text-right" id="th_bank_bayar_<?=$x?>">Bank</th>
												<th class="text-right" id="th_no_cek_bayar_<?=$x?>"></th>
												<th class="text-right" id="th_total_cek_bayar_<?=$x?>"><?=formatrupiah($bon['total_bon'])?></th>
											</tr>
										<?php
										$x++;
										}
										?>
											<tr>
												<th colspan="12" class="text-right">Total Kasbon</th>
												<th colspan="2" class="text-right"><?=formatrupiah($form_data['nominal'])?></th>
											</tr>
											<tr>
												<th colspan="12" class="text-right">Total Biaya Tambahan</th>
												<th colspan="2" class="text-right" id="biaya_tambahan"></th>
											</tr>
											<tr>
												<th colspan="12" class="text-right"><input type="hidden" class="form-control" id="total_bayar" name="total_bayar" value="<?=$form_data['nominal']?>">Total Pembayaran</th>
												<th colspan="2" class="text-right" id="th_total_bayar"><?=formatrupiah($form_data['nominal'])?></th>

											</tr>
											<tr>
												<th colspan="14" class="text-left" id="th_terbilang">Terbilang : # <?=terbilang($form_data['nominal'])?> Rupiah #</th>
											</tr>
										</tfoot>
									</table>  
								</div>
								<?php
									}

									if($form_data['tipe'] == 2 && $form_data['nominal'] = 0){
										$form_upload_bon = '';
										$i = 0;
										if(count($form_data_detail) != 0){
											foreach ($form_data_detail as $key => $bon) {

												$biaya = $this->biaya_m->get_by(array('id' => $bon['biaya_id']), true);

												$form_upload_bon .= '<tr>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][id_bon]" id="biaya_id_bon_'.$i.'" value="'.$bon['id'].'"><input type="hidden" name="biaya['.$i.'][biaya_id] id="biaya_id_'.$i.'" value="'.$bon['biaya_id'].'">'.$biaya->nama.'</td>
												<td><a class="fancybox-button" title="'.$bon['url'].'" href="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" alt="Smiley face" class="img-responsive" ></a></td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][no_bon]" id="biaya_no_bon_'.$i.'" value="'.$bon['no_bon'].'">'.$bon['no_bon'].'</td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][tanggal]" id="biaya_tanggal_'.$i.'" value="'.$bon['tgl_bon'].'">'.date('d M Y', strtotime($bon['tgl_bon'])).'</td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][total_bon]" id="biaya_total_bon_'.$i.'" value="'.$bon['total_bon'].'">'.formatrupiah($bon['total_bon']).'</td>
												<td style="vertical-align: top !important;"><input type="hidden" name="biaya['.$i.'][keterangan]" id="biaya_keterangan_'.$i.'" value="'.$bon['keterangan'].'">'.$bon['keterangan'].'</td>
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
<div id="popover_bank_pegawai_content" class="row" style="display:none;">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_bank">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('Bank', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Atas Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No. Rekening', $this->session->userdata('language'))?></div></th>
                    <th width="1%"><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

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



