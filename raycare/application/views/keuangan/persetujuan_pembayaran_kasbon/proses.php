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


	echo form_open(base_url()."keuangan/persetujuan_pembayaran_kasbon/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$confirm_save       = translate('Anda yakin akan menyetujui pengajuan pembayaran kasbon ini ?',$this->session->userdata('language'));
	$confirm_reject     = translate('Anda yakin akan menolak permintaan biaya ini ?',$this->session->userdata('language'));
	$submit_text        = translate('Setujui', $this->session->userdata('language'));
	$reset_text         = translate('Tolak', $this->session->userdata('language'));
	$back_text          = translate('Kembali', $this->session->userdata('language'));

	$user_minta = $this->user_m->get($form_data['created_by']);

    $user_level_id = $user_minta->user_level_id;
    $user_level_minta = $this->user_level_m->get($user_level_id);

    $jml_setor = 0;
    $bank_id = $form_data['bank_id'];
    $pengajuan_pembayaran_kasbon_id = 0;
   
?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-share-alt font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Persetujuan Pembayaran Biaya", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=$back_text?>
	        </a>
	        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
	        <button type="button" id="reject" class="btn btn-primary hidden" ><?=$reset_text?></button>
	        <a id="confirm_reject" class="btn btn-circle red-intense" href="<?=base_url()?>keuangan/persetujuan_pembayaran_kasbon/reject_proses/<?=$form_data_persetujuan['id']?>/<?=$form_data_persetujuan['pengajuan_pembayaran_kasbon_id']?>/<?=$form_data_persetujuan['order']?>" data-toggle="modal" data-target="#popup_modal_proses">
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
								<label><?=formatrupiah($form_data['nominal'])?></label>
							</div>
						</div>
						<div class="form-group hidden">								
							<div class="col-md-12">
								<label><?=translate('Nominal', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<input class="form-control" type="hidden" name="nominal_setujui" id="nominal_setujui" value="<?=$form_data['nominal']?>"></input>
							</div>
						</div>
						<div class="form-group">								
							<div class="col-md-12">
								<label><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?='#'.terbilang($form_data['nominal']).' Rupiah#'?></label>
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
							<label class="col-md-12"><?=translate('No Cek', $this->session->userdata('language'))?> :</label>
							<label class="col-md-12" id="terbilang"><?=$form_data['no_cek']?></label>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Cek dari Bank", $this->session->userdata("language"))?> :</label>		
								<?php
									$nob = '-';
									if($bank_id != 0){
										$banks = $this->bank_m->get_by(array('id' => $form_data['bank_id']), true);
										$nob = $banks->nob.' a/n '.$banks->acc_name.' - '.$banks->acc_number;
									}
								?>
							<label class="col-md-12"><?=$nob?></label>	
							<input type="hidden" name="bank_id" id="bank_id" value="<?=$bank_id?>" class="form-control">

						</div>
											
					</div>
				</div>
				<div class="col-md-9">
					<input type="hidden" class="form-control" id="pengajuan_pembayaran_kasbon_id" name="pengajuan_pembayaran_kasbon_id" value="<?=$form_data['id']?>"></input>
			    	<input type="hidden" class="form-control" id="persetujuan_pembayaran_kasbon_id" name="persetujuan_pembayaran_kasbon_id" value="<?=$form_data_persetujuan['id']?>"></input>
			    	<input type="hidden" class="form-control" id="order" name="order" value="<?=$order?>"></input>
                                
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Daftar Biaya", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="form-body">
						    <div class="portlet-body">
								<table class="table table-condensed table-striped table-bordered table-hover" id="tabel_kasbon">
	                                <thead>
	                                    <tr>
	                                        <th class="text-center" width="15%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="20%"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="20%"><?=translate("Tipe", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="15%"><?=translate("Rupiah", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("Keperluan", $this->session->userdata("language"))?> </th>
	                                    </tr>
	                                </thead>
	                                <tbody>	
	                                	<?php
	                                		$i = 0;
	                                		foreach ($form_data_detail as $detail) {
	                                			$tipe = '';
									            if($detail['tipe'] == 1){
									                $tipe = 'Kasbon';
									            }if($detail['tipe'] == 2){
									                $tipe = 'Rembes';
									            }
									            $nominal = formatrupiah($detail['nominal_setujui']);
									            if($detail['tipe'] == 2){
									                $nominal = '<a class="detail-nominal" href="'.base_url().'keuangan/persetujuan_pembayaran_kasbon/modal_detail/'.$detail['permintaan_biaya_id'].'" data-target="#modal_view" data-toggle="modal">'.formatrupiah($detail['nominal_setujui']).'</a>';
									            }
	                                			?>
	                                		<tr>
	                                			<td class="text-center"><input type="hidden" id="permintaan_biaya_id" name="kasbon[<?=$i?>][permintaan_biaya_id]" value="<?=$detail['permintaan_biaya_id']?>" ><?=date('d M Y', strtotime($detail['tanggal']))?></td>
	                                			<td class="text-left"><?=$detail['diminta_oleh']?></td>
	                                			<td class="text-left"><?=$tipe?></td>
	                                			<td class="text-right"><?=$nominal?></td>
	                                			<td class="text-left"><?=$detail['keperluan']?></td>
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



