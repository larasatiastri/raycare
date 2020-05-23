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
			<i class="icon-share-alt font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Persetujuan Permintaan Biaya", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=$back_text?>
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
								<label><?=translate('Keperluan', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label><?=$form_data['keperluan']?></label>
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
						    	<input type="hidden" class="form-control" id="persetujuan_permintaan_biaya_id" name="persetujuan_permintaan_biaya_id" value="<?=$pk_value?>"></input>
						    	<input type="hidden" class="form-control" id="order" name="order" value="<?=$order?>"></input>
                                <div class="form-group">
									<label class="col-md-12">
										<?=translate("Nominal", $this->session->userdata("language"))?> :
									</label>		
									
										
									<label class="col-md-12"><a  data-toggle="modal" data-target="#modal_view" href="<?=base_url()?>keuangan/persetujuan_permintaan_biaya/modal_view_history/<?=$form_data['id']?>"><?=formatrupiah($form_data['nominal_setujui'])?></a></label>
									
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-12" >
										<?=translate("Terbilang", $this->session->userdata("language"))?> :
									</label>
									<label class="col-md-12" id="terbilang_setujui" >
										<?='#'.terbilang($form_data['nominal_setujui']).' Rupiah#'?>
									</label>
									
								</div>
								<div class="form-group">
									<label class="col-md-12" >
										<?=translate("Keterangan", $this->session->userdata("language"))?> :
									</label>
									<label class="col-md-12"><?=$form_data_persetujuan['keterangan']?></label>
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



