<?php

	$form_attr = array(
		"id"			=> "form_proses_titip_setoran", 
		"name"			=> "form_proses_titip_setoran", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "proses",
		"id"		=> $pk_value
	);


	echo form_open(base_url()."keuangan/proses_terima_setoran/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$confirm_save       = translate('Anda yakin akan menerima setoran ini?',$this->session->userdata('language'));
	$submit_text        = translate('Terima', $this->session->userdata('language'));
	$reset_text         = translate('Reset', $this->session->userdata('language'));
	$back_text          = translate('Kembali', $this->session->userdata('language'));


?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Terima Setoran", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=$back_text?></a>
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
		    	<div class="col-md-3">
		    		<div class="portlet light bordered">
		    			<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
							</div>
		    			</div>
						<div class="portlet-body">
							<div class="form-group">
								<label class="col-md-12"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
								<label class="col-md-12"><?=date('d M Y', strtotime($data_setoran['tanggal']))?></label>
								
								
							</div>
							<div class="form-group hidden">
									<label class="col-md-12"><?=translate("Diterima Oleh", $this->session->userdata("language"))?> :</label>
									<div class="col-md-12">
										<div class="input-group">
											<input class="form-control input-sm" id="nomer_{0}" name="nama_ref_user" value="<?=$flash_form_data["nama_ref_user"]?>" placeholder="Diterima Oleh" >
											<input class="form-control input-sm hidden" id="nomer_{0}" name="id_ref_pasien" value="<?=$flash_form_data["id_ref_pasien"]?>" placeholder="ID Referensi Pasien">
											<input class="form-control input-sm hidden" id="nomer_{0}" name="tipe_user" value="<?=$flash_form_data["tipe_user"]?>" placeholder="Tipe User">
											<input class="form-control input-sm hidden" id="nomer_{0}" name="kasir_titip_uang_id" value="<?=$flash_form_data["kasir_titip_uang_id"]?>" placeholder="Kasir Titip Uang ID">
											<span class="input-group-btn">
												<a class="btn btn-primary pilih-user" title="<?=translate('Pilih User', $this->session->userdata('language'))?>">
													<i class="fa fa-search"></i>
												</a>
											</span>
										</div>
										
									</div>
								</div>
							<div class="form-group hidden">
								<label class="col-md-12"><?=translate("Jumlah Biaya", $this->session->userdata("language"))?> :</label>		
								<label class="col-md-12"><?=$data_setoran['rupiah_bon']?></label>		
								
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Jumlah Biaya", $this->session->userdata("language"))?> :</label>		
								<label class="col-md-12"><?=formatrupiah($data_setoran['rupiah_bon'])?></label>		

							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Sisa Saldo", $this->session->userdata("language"))?> :</label>		
								<label class="col-md-12"><?=formatrupiah($data_setoran['rupiah'])?></label>		

							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
								<label class="col-md-12" id="terbilang"><?='#'.terbilang($data_setoran['rupiah']).' Rupiah#'?></label>
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Setor Ke Bank", $this->session->userdata("language"))?> :</label>		
									<?php
										$nob = '-';
										if($data_setoran['bank_id'] != 0){
											$banks = $this->bank_m->get_by(array('id' => $data_setoran['bank_id']), true);
											$nob = $banks->nob;
										}
									?>
								<label class="col-md-12"><?=$nob?></label>			

							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Bukti Setor", $this->session->userdata("language"))?> <span>:</span></label>
								<div class="col-md-12">
									<input type="hidden" name="url_bukti_setor" id="url_bukti_setor" value="<?=$data_setoran['url_bukti_setor']?>">
									<div id="upload">
										<ul class="ul-img">
										<li class="working">
											<div class="thumbnail">
												<a class="fancybox-button" title="<?=$data_setoran['url_bukti_setor']?>" href="<?=config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/titip_terima_setoran/images/'.$pk_value.'/'.$data_setoran['url_bukti_setor']?>" data-rel="fancybox-button"><img src="<?=config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/titip_terima_setoran/images/'.$pk_value.'/'.$data_setoran['url_bukti_setor']?>" alt="Smiley face" class="img-thumbnail" ></a>
											</div>
										</li>
										</ul>

									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-12"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>
								<label class="col-md-12"><?=$data_setoran['subjek']?></label>
								
							</div>

							<div class="form-group">
								<label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
								<label class="col-md-12"><?=($data_setoran['keterangan'] != '')?$data_setoran['keterangan']:'-'?></label>
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
                                        <th class="text-center" width="15%"><?=translate("Nomor", $this->session->userdata("language"))?> </th>
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