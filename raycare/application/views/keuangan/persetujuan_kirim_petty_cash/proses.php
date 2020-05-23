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


	echo form_open(base_url()."keuangan/persetujuan_kirim_petty_cash/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$confirm_save       = translate('Anda yakin akan menyetujui permintaan biaya ini ?',$this->session->userdata('language'));
	$confirm_reject     = translate('Anda yaking akan menolak permintaan biaya ini ?',$this->session->userdata('language'));
	$submit_text        = translate('Setujui', $this->session->userdata('language'));
	$reset_text         = translate('Tolak', $this->session->userdata('language'));
	$back_text          = translate('Kembali', $this->session->userdata('language'));

	$user_minta = $this->user_m->get($form_data['created_by']);

    $user_level_id = $user_minta->user_level_id;
    $user_level_minta = $this->user_level_m->get($user_level_id);

    $jml_setor = 0;
    $bank_id = 0;
    $bukti_setor = '';
    $titip_setoran_id = 0;

    if($data_setoran != ''){
    	$jml_setor = $data_setoran['rupiah'];
    	$bank_id = $data_setoran['bank_id'];
    	$bukti_setor = $data_setoran['url_bukti_setor'];
    }

    if($form_data['titip_setoran_id'] != NULL && $form_data['titip_setoran_id'] != ''){
    	$titip_setoran_id = $form_data['titip_setoran_id'];
    }
   
?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-share-alt font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Persetujuan Kirim Petty Cash", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=$back_text?>
	        </a>
	        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
	        <button type="button" id="reject" class="btn btn-primary hidden" ><?=$reset_text?></button>
	        <a id="confirm_reject" class="btn btn-circle red-intense" href="<?=base_url()?>keuangan/persetujuan_kirim_petty_cash/reject_proses/<?=$form_data_persetujuan['persetujuan_permintaan_setoran_keuangan_id']?>/<?=$form_data_persetujuan['setoran_keuangan_kasir_id']?>/<?=$form_data_persetujuan['order']?>" data-toggle="modal" data-target="#popup_modal_proses">
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
								<label><?=date('d-M-Y', strtotime($form_data['tanggal']))?></label>
							</div>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Diminta Oleh', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?=$user_minta->nama.' ('.$user_level_minta->nama.')'?></label>
							</div>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Nominal', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?=formatrupiah($form_data['total_setor'])?></label>
							</div>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?='#'.terbilang($form_data['total_setor']).' Rupiah#'?></label>
							</div>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Subjek', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?=$form_data['subjek']?></label>
							</div>
						</div>	
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Total Biaya', $this->session->userdata('language'))?> :</label>
							</div>
							<label class="col-md-12" id="jumlah_bon"></label>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
							</div>
							<label class="col-md-12" id="terbilang_biaya"></label>
						</div>		
						<div class="form-group">
							<label class="col-md-12"><?=translate("Jumlah Setor", $this->session->userdata("language"))?> :</label>		
							<label class="col-md-12"><?=formatrupiah($jml_setor)?></label>	
							<input type="hidden" name="rupiah" id="rupiah" value="<?=$jml_setor?>" class="form-control">
						</div>	
						<div class="form-group">
							<label class="col-md-12"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12" id="terbilang"><?='#'.terbilang($jml_setor).' Rupiah#'?></label>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Setor Ke Bank", $this->session->userdata("language"))?> :</label>		
								<?php
									$nob = '-';
									if($bank_id != 0){
										$banks = $this->bank_m->get_by(array('id' => $data_setoran['bank_id']), true);
										$nob = $banks->nob.' a/n '.$banks->acc_name.' - '.$banks->acc_number;
									}
								?>
							<label class="col-md-12"><?=$nob?></label>	
							<input type="hidden" name="bank_id" id="bank_id" value="<?=$bank_id?>" class="form-control">

						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Bukti Setor", $this->session->userdata("language"))?> <span>:</span></label>
							<div class="col-md-12">
								<input type="hidden" name="url_bukti_setor" id="url_bukti_setor" value="<?=$bukti_setor?>">
								<div id="upload">
									<ul class="ul-img">
									<li class="working">
										<div class="thumbnail">
											<a class="fancybox-button" title="<?=$bukti_setor?>" href="<?=base_url().'assets/mb/pages/keuangan/titip_terima_setoran/images/'.$pk_value.'/'.$bukti_setor?>" data-rel="fancybox-button"><img src="<?=base_url().'assets/mb/pages/keuangan/titip_terima_setoran/images/'.$pk_value.'/'.$bukti_setor?>" alt="Smiley face" class="img-thumbnail" ></a>
										</div>
									</li>
									</ul>

								</div>
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
						    	<input type="hidden" class="form-control" id="setoran_keuangan_kasir_id" name="setoran_keuangan_kasir_id" value="<?=$form_data['id']?>"></input>
						    	<input type="hidden" class="form-control" id="titip_setoran_id" name="titip_setoran_id" value="<?=$titip_setoran_id?>"></input>
						    	<input type="hidden" class="form-control" id="persetujuan_setoran_keuangan_kasir_id" name="persetujuan_setoran_keuangan_kasir_id" value="<?=$form_data_persetujuan['persetujuan_permintaan_setoran_keuangan_id']?>"></input>
						    	<input type="hidden" class="form-control" id="order" name="order" value="<?=$order?>"></input>
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
													"value"		=> $form_data['total_setor']
												);
												echo form_input($nominal_setujui);
											?>
										
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-12" >
										<?=translate("Terbilang", $this->session->userdata("language"))?> :
									</label>
									<label class="col-md-12" id="terbilang_setujui" >
										<?='#'.terbilang($form_data['total_setor']).' Rupiah#'?>
									</label>
									
								</div>
								
                            </div>
						</div>
					</div>
				</div>
				<div class="col-md-9">
		    		<div class="portlet light bordered">
		    			<div class="portlet-title">
		    				<div class="caption">
								<span class="caption-subject"><?=translate("Daftar Biaya", $this->session->userdata("language"))?></span>
							</div>
		    			</div>
		    			<div class="portlet-body">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_detail_setoran_biaya">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="15%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="20%"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="15%"><?=translate("Rupiah", $this->session->userdata("language"))?> </th>
										<th class="text-center"><?=translate("Keperluan", $this->session->userdata("language"))?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                    <!-- <?//=$item_row?> -->
                                </tbody>
                                <tfoot>
                                	
                                </tfoot>
                            </table>
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
<div class="modal fade bs-modal-lg" id="popup_modal" role="basic" aria-hidden="true">
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



