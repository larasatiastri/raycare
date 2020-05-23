<?php 
	$form_attr = array(
		"id"            => "form_tambah_sub", 
		"name"          => "form_tambah_sub", 
		"autocomplete"  => "off", 
		"class"         => "form-horizontal",
		"role"			=> "form"
	);
	$hidden = array(
		"command"   => "add_sub_kategori"
	);
	echo form_open(base_url()."master/kategori_sub_kategori/save", $form_attr, $hidden);

	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));


	$btn_del    = '<div class="text-center"><button type="button" class="btn btn-sm red del-item" title="Hapus"><i class="fa fa-times"></i></button></div>';
	$attr_text = array(
		"id" 		=> "detail_text_{0}",
		"name" 		=> "detail[{0}][text]",
		"class" 	=> "form-control text"
	);

	$attr_value = array(
		"id" 		=> "detail_value_{0}",
		"name" 		=> "detail[{0}][value]",
		"class" 	=> "form-control value"
	);

	$item_cols = array(
		'text' 		=> form_input($attr_text),
		'value'  	=> form_input($attr_value),
		'action'    => $btn_del,
	);
	
	// gabungkan $item_cols jadi string table row
	$item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$form_spesifikasi	 = '<div class="form-group">
								<label class="control-label col-md-2">'.translate('Judul', $this->session->userdata('language')).' :</label>
								<div class="col-md-4">
									<div class="input-group">
										<input class="form-control" id="spesifikasi[_ID_0][judul]" name="spesifikasi[_ID_0][judul]">
										<span class="input-group-btn">
											<a class="btn red-intense del-this" title="Hapus"><i class="fa fa-times"></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
					     		<label for="spesifikasi[_ID_0][spesifikasi_type]" class="col-md-2 control-label">'.translate('Tipe', $this->session->userdata('language')).' :</label>
						    	<div class="col-md-3">
						    		<select class="form-control" name="spesifikasi[_ID_0][spesifikasi_type]" id="spesifikasi[_ID_0][spesifikasi_type]">
										<option value="0">-- Pilih Tipe --</option>
										<option value="1">Text</option>
										<option value="2">Textarea</option>
										<option value="3">Number</option>
										<option value="4">Dropdown</option>
										<option value="5">Radio Button</option>
										<option value="6">Checkbox</option>
										<option value="7">Multi Select</option>
									</select>
						     	</div>
						    </div>
						    <div id="section_1" hidden>
								<div class="form-group">
									<label class="control-label col-md-2"></label>
									<div class="col-md-1">
										<input class="form-control" name="spesifikasi[_ID_0][max_text]" id="spesifikasi[_ID_0][max_text]">
									</div>
									<label class="control-label">Maksimal Karakter</label>
								</div>
							</div>
							<div id="section_4" hidden>
								<div class="form-group">
									<label class="control-label col-md-2"></label>
									<div class="col-md-5">
										<div class="portlet">
											<div class="portlet-title">
												<div class="caption">
													<span class="">List</span>
												</div>
												<div class="actions">
													<a class="btn btn-xs btn-primary" name="spesifikasi[_ID_0][add_item]" id="spesifikasi[_ID_0][add_item]"><i class="fa fa-plus"></i></a>
												</div>
											</div>
											<div class="portlet-body">
								 				<input type="hidden" name="spesifikasi[_ID_0][count]">
												<table class="table table-striped table-hover table-bordered" id="spesifikasi[_ID_0][table_item]" name="spesifikasi[_ID_0][table_item]">
													<thead>
														<tr class="heading">
															<th class="text-center">'.translate('Text', $this->session->userdata('language')).'</th>
															<th class="text-center">'.translate('Value', $this->session->userdata('language')).'</th>
															<th class="text-center">'.translate('Aksi', $this->session->userdata('language')).'</th>
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
						    ';
												

	
?>
							
<div class="portlet light">
							
	<!-- SUB KATEGORI -->
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Sub Kategori', $this->session->userdata('language'))?></span> 
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

			<div class="form-group" style="margin-top: 10px;">
				<label class="control-label col-md-3"><?=translate('Kategori', $this->session->userdata('language'))?><span class="required">*</span> :</label>
				<div class="col-md-2">
					<?php 	
						$kategori_options = array(
							''	=> '-- Pilih Kategori --'
						);

						$kategori = $this->kategori_m->get_by(array('is_active' => 1));
						foreach ($kategori as $row)
						{
						    $kategori_options[$row->id] = $row->nama;
						}

						echo form_dropdown("item_kategori_id", $kategori_options, "", "id=\"item_kategori_id\" class=\"form-control\" required ");
					?>
				</div>
			</div>		
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate('Kode', $this->session->userdata('language'))?><span class="required">*</span> :</label>
				<div class="col-md-2">
					<?php 

						$attr_kode = array(
							"id"			=> "kode",
							"name"			=> "kode",
							"maxlength"		=> 6,
							"class"			=> "form-control", 
							"placeholder"	=> translate("Kode", $this->session->userdata("language")), 
							"required"		=> "required"
						);
						echo form_input($attr_kode);
					?>
				</div>
			</div>	
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate('Nama', $this->session->userdata('language'))?><span class="required">*</span> :</label>
				<div class="col-md-2">
					<?php 

						$attr_nama = array(
							"id"			=> "nama",
							"name"			=> "nama",
							"class"			=> "form-control", 
							"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
							"required"		=> "required"
						);
						echo form_input($attr_nama);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
				<div class="col-md-3">
					<?php 

						$attr_keterangan = array(
							"id"			=> "keterangan",
							"name"			=> "keterangan",
							"class"			=> "form-control", 
							"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
							"rows"			=> 5
						);
						echo form_textarea($attr_keterangan);
					?>
				</div>
			</div>
		</div>
	</div>
	
	<!-- DETAIL SUB KATEGORI -->
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Detail Sub Kategori', $this->session->userdata('language'))?></span> 
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#spesifikasi" data-toggle="tab">
					<?=translate('Spesifikasi', $this->session->userdata('language'))?> </a>
				</li>
				<li>
					<a href="#pembelian" data-toggle="tab">
					<?=translate('Pembelian', $this->session->userdata('language'))?> </a>
				</li>
			</ul>
			<div class="tab-content">
				
				<!-- SPESIFIKASI -->
				<div class="tab-pane active" id="spesifikasi">
					<div class="portlet light" id="section-spesifikasi">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Spesifikasi", $this->session->userdata("language"))?></span>
							</div>
							<div class="actions">
								<a class="btn btn-primary tambah-spesifikasi"><i class="fa fa-plus"></i> <?=translate('Tambah', $this->session->userdata('language'))?></a>
							</div>
						</div>
						<div class="portlet-body">
							<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
							<input type="hidden" id="tpl-form-spesifikasi" value="<?=htmlentities($form_spesifikasi)?>">
							<div class="form-body">
								<ul class="list-unstyled">
					            </ul>
							</div>
						</div>
					</div>
				</div>

				<!-- PEMBELIAN -->
				<?php 

					$btn_del           = '<div class="text-center"><a class="btn btn-sm red-intense del-this" title="Delete Persetujuan Pembelian"><i class="fa fa-times"></i></a></div>';

					$attrs_user_level_id	= array (
					    'id'          => 'user_level_id{0}',
					    'name'        => 'user_level[{0}][user_level_id]',
					    'type'        => 'hidden',
					    'class'       => 'form-control',
					    'readonly'    => 'readonly',
					);
					$attrs_user_level_name = array (
					    'id'          => 'user_level_name{0}',
					    'name'        => 'user_level[{0}][name]',
					    'type'        => 'hidden',
					    'class'       => 'form-control',
					    'readonly'    => 'readonly',
					);
					
					$order_option = array(
						'1' => '1',
						'2' => '2',
						'3' => '3'
					);

					$item_cols = array(// style="width:156px;
						'user_level_name'   => form_input($attrs_user_level_id).form_input($attrs_user_level_name).'<label class="control-label" id="user_level_lblname{0}" name="user_level[{0}][lblname]"></label>',
						'user_level_order'  => form_dropdown('user_level[{0}][order]', $order_option, '', "id=\"user_level_order_{0}\" class=\"form-control\""),
						'user_level_lewati' => '<div class="text-center"><input type="checkbox" name="user_level[{0}][lewati]" id="user_level[{0}][lewati]"></div>',
						'user_level_req'    => '<div class="text-center"><input type="checkbox" name="user_level[{0}][req]" id="user_level[{0}][req]"></div>',
						'action'            => $btn_del,
					);
					// gabungkan $item_cols jadi string table row
					$item_row_template_persetujuan =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

					$dummy_rows = array();


				?>

				<div class="tab-pane " id="pembelian">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Persetujuan Pembelian", $this->session->userdata("language"))?></span>
							</div>
							<div class="actions">
					            <a data-toggle="modal" href="#popup_modal" class="btn btn-primary">
					                <i class="fa fa-plus"></i>
					                <span class="hidden-480">
					                     <?=translate("Tambah", $this->session->userdata("language"))?>
					                </span>
					            </a>
					        </div>
						</div>
						<div class="portlet-body">
							<span id="tpl_item_row_persetujuan" class="hidden"><?=htmlentities($item_row_template_persetujuan)?></span>
							<table class="table table-striped table-bordered table-hover" id="table_persetujuan">
								<thead>
									<tr class="heading">
										<th class="text-center"><?=translate("User Level", $this->session->userdata("language"))?></th>
										<th class="text-center" width="20%"><?=translate("Order", $this->session->userdata("language"))?></th>
										<th class="text-center" width="10%"><?=translate("Lewati", $this->session->userdata("language"))?></th>
										<th class="text-center" width="10%"><?=translate("Req", $this->session->userdata("language"))?></th>
										<th class="text-center" width="10%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
									</tr>
								</thead>

								<tbody>
									<?php foreach ($dummy_rows as $row):?>
					                    <?=$row?>
					                <?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<?php 
		 	$msg = translate("Apakah anda yakin akan membuat sub kategori ini?",$this->session->userdata("language"));
		?>
		<div class="form-actions">
			<div class="col-md-offset-2 col-md-9">
				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
				<a id="confirm_save" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
	            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			</div>
		</div>	
	</div>
</div>

<!-- MODAL -->
<div id="popup_modal" class="modal fade" role="dialog" aria-hidden="true" style="display: none;">
    <form action="#" class="form-horizontal">
        <div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Pilih User Level", $this->session->userdata("language"))?></h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="table_user_level">
                        <thead>
                            <tr role="row" class="heading">
                                <th scope="col" ><div class="text-center"><?=translate("ID", $this->session->userdata("language"))?></div></th>
                                <th scope="col" ><div class="text-center"><?=translate("User Level", $this->session->userdata("language"))?></div></th>
                                <th scope="col" ><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary hidden" id="btnOK">OK</button>
                    <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
                </div>
            </div>
        </div>
    </form>
</div>