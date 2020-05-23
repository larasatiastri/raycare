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


?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<!-- <i class="fa fa-cogs font-blue-sharp"></i> -->
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
                            <select class="form-control" id="tipe" name="tipe" required>
								<option value="">Pilih Tipe...</option>
								<option value="1">Obat</option>
								<option value="2">Tindakan</option>
							</select>
                            
                        </div>
                    </div>

                    <div class="form-group hidden">
						<label class="control-label col-md-3"><?=translate("Paket_ID :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<?php
								$nama_paket = array(
									"name"			=> "paket_id",
									"id"			=> "paket_id",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
									"value"			=> $form_data['id'],
								);
								echo form_input($nama_paket);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Nama Batch :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<?php
								$nama_paket = array(
									"name"			=> "nama_paket_batch",
									"id"			=> "nama_paket_batch",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($nama_paket);
							?>
						</div>
					</div>

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
						                    <th class="table-checkbox"><div class="text-center"><input type="checkbox" class="group-checkable text-center" data-set="#table_paket_item .checkboxes"/></div></th>
                                            <th scope="col"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
                                            <th scope="col"><div class="text-center"><?=translate("Jumlah", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("Jumlah Digunakan", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("Satuan Digunakan", $this->session->userdata('language'))?></div></th>
						                </tr>
									</thead>
									<tbody>
									
									</tbody>
								</table>
							</div>
						</div>
					</div>

                    <div class="portlet">
                        <div class="portlet-title">
                        	<div class="caption">
								<!-- <i class="fa fa-cogs font-blue-sharp"></i> -->
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan", $this->session->userdata("language"))?></span>
                        	</div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-responsive">
                                <table class="table table-condensed table-striped table-bordered table-hover" id="table_paket_tindakan">
                                    <thead>
                                        <tr role="row" class="heading">
						                    <th class="table-checkbox"><div class="text-center"><input type="checkbox" class="group-checkable text-center" data-set="#table_paket_tindakan .checkboxes"/></div></th>
                                            <th scope="col"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
                                            <th scope="col"><div class="text-center"><?=translate("Jumlah", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("Jumlah Digunakan", $this->session->userdata('language'))?></div></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

				<?php
					$confirm_save       = translate('Apakah anda yakin akan menambah paket batch ini?',$this->session->userdata('language'));
					$submit_text        = translate('Simpan', $this->session->userdata('language'));
					$back_text          = translate('Kembali', $this->session->userdata('language'));
				?>
				<div class="form-actions fluid">    
				    <div class="col-md-offset-2 col-md-9">
				        
				        <a class="btn default" href="javascript:history.go(-1)"><?=$back_text?></a>
				        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
				        <a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=$submit_text?></a>
				        
				    </div>          
				</div>
			</div>
		</div>

	</div>			
</div>									
</div>
