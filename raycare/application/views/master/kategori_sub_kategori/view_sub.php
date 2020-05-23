<?php 
	$form_attr = array(
		"id"            => "form_tambah_sub", 
		"name"          => "form_tambah_sub", 
		"autocomplete"  => "off", 
		"class"         => "form-horizontal",
		"role"			=> "form"
	);
	$hidden = array(
		"command"   => "edit_sub_kategori",
		"id"		=> $pk
	);
	echo form_open(base_url()."master/kategori_sub_kategori/save", $form_attr, $hidden);

	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$attr = array(
		"class"			=> "control-label", 
	);

	$btn_del    = '<div class="text-center"><button type="button" class="btn btn-sm red del-item" title="Hapus"><i class="fa fa-times"></i></button></div>';
	$attr_text = array(
		"id" 		=> "spesifikasi[{0}][text]",
		"name" 		=> "spesifikasi[{0}][text]",
		"disabled"	=> "disabled",
		"class" 	=> "form-control text"
	);

	$attr_value = array(
		"id" 		=> "spesifikasi[{0}][value]",
		"disabled"	=> "disabled",
		"name" 		=> "spesifikasi[{0}][value]",
		"class" 	=> "form-control value"
	);

	$item_cols = array(
		'text' 		=> form_input($attr_text),
		'value'  	=> form_input($attr_value),
		// 'action'    => $btn_del,
	);
	
	// gabungkan $item_cols jadi string table row
	$item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$form_spesifikasi	 = '<div class="form-group">
								<label class="control-label col-md-2">'.translate('Judul', $this->session->userdata('language')).' :</label>
								<div class="col-md-4">
									<div class="input-group">
										<input class="form-control" readonly id="spesifikasi[{0}][judul]" name="spesifikasi[{0}][judul]">
										<span class="input-group-btn">
											<a class="btn red-intense del-this" title="Hapus"><i class="fa fa-times"></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
					     		<label for="spesifikasi[{0}][spesifikasi_type]" class="col-md-2 control-label">'.translate('Tipe', $this->session->userdata('language')).' :</label>
						    	<div class="col-md-3">
						    		<select class="form-control" disabled name="spesifikasi[{0}][spesifikasi_type]" id="spesifikasi[{0}][spesifikasi_type]">
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
										<input class="form-control" disabled name="spesifikasi[{0}][max_text]" id="spesifikasi[{0}][max_text]">
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
												
											</div>
											<div class="portlet-body">
												<table class="table table-striped table-hover table-bordered" id="table_item_{0}" name="spesifikasi[{0}][table_item]">
													<thead>
														<tr class="heading">
															<th class="text-center">'.translate('Text', $this->session->userdata('language')).'</th>
															<th class="text-center">'.translate('Value', $this->session->userdata('language')).'</th>
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
				<label class="control-label col-md-3"><?=translate('Kategori', $this->session->userdata('language'))?> :</label>
				<div class="col-md-2">
					<input class="form-control hidden" id="kategori_sub_id" name="kategori_sub_id" value="<?=$pk?>">
					<?php 	
						$kategori = $this->kategori_m->get_by(array('id' => $form_data['item_kategori_id']));
						
						echo form_label($kategori[0]->nama, '', $attr);
					?>
				</div>
			</div>		
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate('Kode', $this->session->userdata('language'))?> :</label>
				<div class="col-md-2">
					<?php 
						echo form_label($form_data['kode'], '', $attr);
					?>
				</div>
			</div>	
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate('Nama', $this->session->userdata('language'))?> :</label>
				<div class="col-md-2">
					<?php 
						echo form_label($form_data['nama'], '', $attr);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
				<div class="col-md-3">
					<?php 
						$attr['style'] = "text-align: left;";
						echo form_label($form_data['keterangan'], '', $attr);
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

					$attrs_delete = array (
					    'id'          => 'is_delete{0}',
					    'name'        => 'user_level[{0}][is_delete]',
					    'type'        => 'hidden',
					    'class'       => 'form-control',
					    'readonly'    => 'readonly',
					);
					$attrs_id	= array (
					    'id'          => 'id{0}',
					    'name'        => 'user_level[{0}][id]',
					    'type'        => 'hidden',
					    'class'       => 'form-control',
					    'readonly'    => 'readonly',
					);
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

					$item_cols = array(
						'user_level_name'   => form_input($attrs_delete).form_input($attrs_id).form_input($attrs_user_level_id).form_input($attrs_user_level_name).'<label class="control-label" id="user_level_lblname{0}" name="user_level[{0}][lblname]"></label>',
						'user_level_order'  => form_dropdown('user_level[{0}][order]', $order_option, '', "id=\"user_level_order_{0}\" class=\"form-control\""),
						'user_level_lewati' => '<div class="text-center"><input type="checkbox" name="user_level[{0}][lewati]" id="user_level[{0}][lewati]"></div>',
						'user_level_req'    => '<div class="text-center"><input type="checkbox" name="user_level[{0}][req]" id="user_level[{0}][req]"></div>',
					);
					// gabungkan $item_cols jadi string table row
					// $item_row_template_persetujuan =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

					$dummy_rows = array();
					// die_dump($form_pembelian);
					$i = 0;
					foreach ($form_pembelian as $key=>$data) 
					{
						$attrs_id['value']              = $data['id'];
						$attrs_user_level_id['value']   = $data['user_level_id'];
						$attrs_user_level_name['value'] = $data['nama'];
						$order_user                     = $data['level_order'];

						($data['lewati'] == 1) ? $check1 = "Ya" : $check1 = "Tidak";
						($data['req'] == 1) ? $check2 = "Ya" : $check2 = "Tidak";

					    $item_cols = array(
							'user_level_name'   => form_input($attrs_delete).form_input($attrs_id).form_input($attrs_user_level_id).form_input($attrs_user_level_name).'<label class="control-label" id="user_level_lblname{0}" name="user_level[{0}][lblname]">'.$data['nama'].'</label>',
							'user_level_order'  => '<div class="text-center">'.$order_user.'</div>',
							'user_level_lewati' => '<div class="text-center">'.$check1.'</div>',
							'user_level_req'    => '<div class="text-center">'.$check2.'</div>',
						);
						// gabungkan $item_cols jadi string table row
						$item_dummy =  '<tr id="item_row_edit_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
						$dummy_rows[] = str_replace('{0}', "{$key}", $item_dummy );
						$i++;
					}

				?>

				<div class="tab-pane " id="pembelian">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Persetujuan Pembelian", $this->session->userdata("language"))?></span>
							</div>
							
						</div>
						<div class="portlet-body">
							<input class="form-control hidden" name="index" value="<?=$i?>">
							<!-- <span id="tpl_item_row_persetujuan" class="hidden"><?=htmlentities($item_row_template_persetujuan)?></span> -->
							<table class="table table-striped table-bordered table-hover" id="table_persetujuan">
								<thead>
									<tr class="heading">
										<th class="text-center"><?=translate("User Level", $this->session->userdata("language"))?></th>
										<th class="text-center" width="20%"><?=translate("Order", $this->session->userdata("language"))?></th>
										<th class="text-center" width="10%"><?=translate("Lewati", $this->session->userdata("language"))?></th>
										<th class="text-center" width="10%"><?=translate("Req", $this->session->userdata("language"))?></th>
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
		 	$msg = translate("Apakah anda yakin akan mengubah sub kategori ini?",$this->session->userdata("language"));
		?>
		<div class="form-actions">
			<div class="col-md-offset-2 col-md-9">
				<a class="btn default" href="<?=base_url()?>master/kategori_sub_kategori"><?=translate("Kembali", $this->session->userdata("language"))?></a>
				<!-- <a id="confirm_save" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a> -->
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