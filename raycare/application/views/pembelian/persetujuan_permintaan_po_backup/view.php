<?php
    //////////////////////////////////////////////////////////////////////////////////////

    $this->item_satuan_m->set_columns(array('id','nama'));
    $categories = $this->item_satuan_m->get();
    // die_dump($categories);
    $categories_satuan = array(
    
    '' => translate('Pilih Satuan', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_satuan[$categories->id] = $categories->nama;
    }

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_view_permintaan_po", 
		"name"			=> "form_view_permintaan_po", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "view"
	);


	echo form_open(base_url()."pembelian/persetujuan_permintaan_po/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	// die_dump($data_order);


?>	

		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-search font-blue-sharp"></i>
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("View Persetujuan Permintaan Barang", $this->session->userdata("language"))?></span>
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
				</div>
				<div class="form-wizard">
					<div class="row">
						<div class="col-md-3">
							<div class="portlet box blue-sharp">
								<div class="portlet-title" style="margin-bottom: 0px !important;">
									<div class="caption">
										<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="form-group hidden">
										<label class="control-label col-md-4"><?=translate("Kode", $this->session->userdata("language"))?> :</label>
										<div class="col-md-4">
											<?php
												$pk_value = array(
													"id"			=> "pk_value",
													"name"			=> "pk_value",
													"class"			=> "form-control", 
													"placeholder"	=> translate("pk_value", $this->session->userdata("language")), 
													"value"			=> $pk_value,
													"help"			=> $flash_form_data['pk_value'],
												);
												echo form_input($pk_value);
											?>
										</div>
									</div>
									<div class="form-group hidden">
										<label class="control-label col-md-4"><?=translate("tipe", $this->session->userdata("language"))?> :</label>
										<div class="col-md-4">
											<?php
												$tipe = array(
													"id"			=> "tipe",
													"name"			=> "tipe",
													"class"			=> "form-control", 
													"placeholder"	=> translate("tipe", $this->session->userdata("language")), 
													"value"			=> $form_data[0]['tipe_permintaan'],
													"help"			=> $flash_form_data['tipe'],
												);
												echo form_input($tipe);
											?>
										</div>
									</div>
									<div class="form-group hidden">
										<label class="control-label col-md-12"><?=translate("user level id", $this->session->userdata("language"))?> :</label>
										<div class="col-md-4">
											<?php
												$user_level_id = array(
													"id"			=> "user_level_id",
													"name"			=> "user_level_id",
													"class"			=> "form-control", 
													"placeholder"	=> translate("user_level_id", $this->session->userdata("language")), 
													"value"			=> $id_user_level,
													// "help"			=> $flash_form_data['user_level_id'],
												);
												echo form_input($user_level_id);
											?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-12 bold"><?=translate("Tanggal :", $this->session->userdata("language"))?></label>		
												<div class="col-md-12">
													<label class="control-label"><?=date('d M Y', strtotime($data_order[0]['tanggal']))?></label>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-12 bold"><?=translate("Cabang :", $this->session->userdata("language"))?></label>		
												<div class="col-md-12">
													<label class="control-label"><?=$data_cabang[0]['nama']?></label>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-12 bold"><?=translate("Dibuat Oleh :", $this->session->userdata("language"))?></label>		
												<div class="col-md-12">
													<?php $user_create = $this->user_m->get($data_order[0]['created_by'])?>
													<?php  $user_level_id = $this->user_level_m->get_by(array('id', $data_order[0]['created_by']))?>

													<label class="control-label"><?=$user_create->nama?></label>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-12 bold"><?=translate("Ditujukan Ke :", $this->session->userdata("language"))?></label>		
												<div class="col-md-12">
													<?php $user_create = $this->user_m->get($data_order[0]['user_id'])?>
													<label class="control-label"><?=$user_create->nama?></label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Subjek :", $this->session->userdata("language"))?></label>		
										<div class="col-md-12">
											<label class="control-label"><?=$data_order[0]['subjek']?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>
										<div class="col-md-12">
											<label class="control-label" style="text-align: left;"><?=$data_order[0]['keterangan']?></label>
										</div>
									</div>
								</div>
			                    
							</div>						
						</div>

						
						<div class="col-md-9 hidden" id="section_terdaftar">
							<div class="portlet box blue-sharp">
								<div class="portlet-title" style="margin-bottom: 0px !important;">
									<div class="caption">
										<span class="caption-subject"><?=translate("Order Deskripsi", $this->session->userdata("language"))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-hover" id="table_item_terdaftar">
	                                        <thead>
	                                            <tr>
	                                                <th width="10%"><div class="text-center"><?=translate("id", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Jumlah Pesan", $this->session->userdata('language'))?></div></th>
	                                                <th width="2%"><div class="text-center"><?=translate("Jumlah Setujui", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
	                                                <th width="2%"><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
	                                                <th width="2%"><div class="text-center"><?=translate("Supplier", $this->session->userdata('language'))?></div></th>
													<th width="1%" ><div class="text-center"><?=translate("Tolak", $this->session->userdata('language'))?></th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                          
	                                            <!-- <?//=$item_row?> -->
	                                        </tbody>
	                                    </table>
	                                </div>
								</div>
							</div>
							<div class="portlet light bordered hidden">
								<div class="portlet-title">
									<div class="caption">
										<span class="caption-subject"><?=translate("Item Box Paket", $this->session->userdata("language"))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="table-responsive">
	                                    <table class="table table-striped table-hover" id="table_item_box_paket">
	                                        <thead>
	                                            <tr>
	                                                <th width="10%"><div class="text-center"><?=translate("id", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Jumlah Pesan", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Jumlah Setujui", $this->session->userdata('language'))?></div></th>
													<th width="10%" ><div class="text-center"><?=translate("Tolak", $this->session->userdata('language'))?></div></th>
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

						<div class="col-md-9 hidden" id="section_tidak_terdaftar">
							<div class="portlet light bordered">
								<div class="portlet-title">
									<div class="caption">
										<span class="caption-subject"><?=translate("Permintaan Item Yang Tidak Terdaftar", $this->session->userdata("language"))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-hover" id="table_item_tidak_terdaftar">
	                                        <thead>
	                                            <tr>
	                                                <th ><div class="text-center"><?=translate("id", $this->session->userdata('language'))?></div></th>
	                                                <th ><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Jumlah Pesan", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Jumlah Setujui", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Supplier", $this->session->userdata('language'))?></div></th>
	                                                <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
													<th width="1%">
														<div class="text-center"><?=translate("Tolak", $this->session->userdata('language'))?>
															
														</div>
													</th>
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
				</div>
				<?php
					$back_text = translate('Kembali', $this->session->userdata('language'));
				?>
				<div class="form-actions right">    
			        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
			        	<i class="fa fa-chevron-left"></i>
			        	<?=$back_text?>
			        </a>
				</div>
			</div>

		</div>

<?=form_close();?>

<div id="popover_item_content" class="row" style="display:none;">
    <div class="col-md-12">
		<div class="portlet">
			<div class="portlet-body">
		        <table class="table table-striped table-hover" id="table_pilih_item_user">
		            <thead>
		                <tr>
		                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
		                    <th width="15%" style="width: 100px;"><div class="text-center"><?=translate('User Level.', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Order', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Status', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal Baca', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Dibaca Oleh', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal Persetujuan', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Disetujui Oleh', $this->session->userdata('language'))?></div></th>
		                    <th widht="5%"><div class="text-center"><?=translate('Jumlah Persetujuan', $this->session->userdata('language'))?></div></th>
		                </tr>
		            </thead>
		            <tbody>
		            </tbody>
		        </table>
		    </div>
		</div>
    </div>
</div>

<div id="popover_item_content_box" class="row" style="display:none;">
    <div class="col-md-12">
		<div class="portlet">
			<div class="portlet-body">
		        <table class="table table-striped table-hover" id="table_pilih_item_user_box">
		            <thead>
		                <tr>
		                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
		                    <th width="15%" style="width: 100px;"><div class="text-center"><?=translate('User Level.', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Order', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Status', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal Baca', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Dibaca Oleh', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal Persetujuan', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Disetujui Oleh', $this->session->userdata('language'))?></div></th>
		                    <th widht="5%"><div class="text-center"><?=translate('Jumlah Persetujuan', $this->session->userdata('language'))?></div></th>
		                </tr>
		            </thead>
		            <tbody>
		            </tbody>
		        </table>
		    </div>
		</div>
    </div>
</div>

<div id="popover_item_content_tidak_terdaftar" class="row" style="display:none;">
    <div class="col-md-12">
		<div class="portlet">
			<div class="portlet-body">
		        <table class="table table-striped table-hover" id="table_pilih_item_tidak_terdaftar_user">
		            <thead>
		                <tr>
		                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
		                    <th width="15%" style="width: 100px;"><div class="text-center"><?=translate('User Level.', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Order', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Status', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal Baca', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Dibaca Oleh', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal Persetujuan', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Disetujui Oleh', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Jumlah Persetujuan', $this->session->userdata('language'))?></div></th>
		                </tr>
		            </thead>
		            <tbody>
		            </tbody>
		        </table>
		    </div>
		</div>
    </div>
</div>


<div class="modal fade bs-modal-sm" id="popup_modal" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-sm">
       <div class="modal-content">

       </div>
   </div>
</div>

<div class="modal fade bs-modal-sm" id="popup_modal_file" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-sm">
       <div class="modal-content">
       
       </div>
   </div>
</div>