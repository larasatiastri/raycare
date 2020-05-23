<?php
    $this->cabang_m->set_columns(array('id','nama'));
    $categories = $this->cabang_m->get();
    // die_dump($categories);
    $categories_options = array(
    
    '' => translate('Pilih Cabang', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_options[$categories->id] = $categories->nama;
    }

    $this->item_satuan_m->set_columns(array('id','nama'));
    $categories = $this->item_satuan_m->get();
    // die_dump($categories);
    $categories_satuan = array(
    
    '' => translate('Pilih Satuan', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_satuan[$categories->id] = $categories->nama;
    }


	$form_attr = array(
		"id"			=> "form_addtemplate", 
		"name"			=> "form_addtemplate", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);

	// die_dump($form_data);

	echo form_open(base_url()."master/paket/save_batch", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	if ($paket_batch['tipe'] == 1){

		$tipe = 'Obat';
	} else {

		$tipe = 'Tindakan';
	}

?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Buat Batch Paket", $this->session->userdata("language"))?></span>
		</div>
		
	</div>
	<div class="portlet-body form">
		<div class="portlet-body form">	
			<div class="form-wizard">
				<div class="form-body">
					<div class="alert alert-danger display-hide">
				        <button class="close" data-close="alert"></button>
				        <?=$form_alert_danger?>
				    </div>
				    <div class="alert alert-success display-hide">
				        <button class="close" data-close="alert"></button>
				        <?=$form_alert_success?>
				    </div>

					<div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Tipe :", $this->session->userdata("language"))?></label>
                        <div class="col-md-2">
							<label class="control-label col-md-0"><?=$tipe?></label>		
	                        </div>
                    </div>

                    <div class="form-group hidden">
						<label class="control-label col-md-3"><?=translate("Paket_batch_ID :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<?php
								$nama_paket = array(
									"name"			=> "paket_batch_id",
									"id"			=> "paket_batch_id",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"value"			=> $paket_batch['id'],
								);
								echo form_input($nama_paket);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Nama Batch :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<label class="control-label col-md-0"><?=$paket_batch['nama']?></label>		
						</div>
					</div>

				   
	                <div class="row">
	                    <div class="col-md-12">
			                <div class="portlet" style="margin-top: 20px;">
				                <div class="portlet-title">
									<div class="caption">
										<!-- <i class="fa fa-cogs font-blue-sharp"></i> -->
										<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Obat & Alat Kesehatan", $this->session->userdata("language"))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="form-body">
										<table class="table table-striped table-bordered table-hover" id="table_paket_item">
											<thead>
												<tr role="row" class="heading">
		                                            <th scope="col"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
		                                            <th scope="col"><div class="text-center"><?=translate("Jumlah Di Gunakan", $this->session->userdata('language'))?></div></th>
		                                            <th scope="col"><div class="text-center"><?=translate("Satuan Digunakan", $this->session->userdata('language'))?></div></th>
								                </tr>
											</thead>
											<tbody>
											
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

	                <div class="row">
	                    <div class="col-md-12">
	                        <div class="portlet">
	                            <div class="portlet-title">
	                            	<div class="caption">
										<!-- <i class="fa fa-cogs font-blue-sharp"></i> -->
										<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan", $this->session->userdata("language"))?></span>
	                            	</div>
	                            </div>
	                            <div class="portlet-body">
	                                <!-- <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span> -->
	                                <!-- <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span> -->
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_paket_tindakan">
	                                        <thead>
	                                            <tr role="row" class="heading">
		                                            <th scope="col"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
		                                            <th scope="col"><div class="text-center"><?=translate("Jumlah Di Gunakan", $this->session->userdata('language'))?></div></th>
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

				<?php
					$back_text          = translate('Kembali', $this->session->userdata('language'));
				?>
				<div class="form-actions fluid">    
				    <div class="col-md-offset-1 col-md-9">
				        
				        <a class="btn default" href="javascript:history.go(-1)"><?=$back_text?></a>
				        
				    </div>          
				</div>
			</div>
		</div>

	</div>			
</div>									
</div>
