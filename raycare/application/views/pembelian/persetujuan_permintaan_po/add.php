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
		"id"			=> "form_add_persetujuan_permintaan_po", 
		"name"			=> "form_add_persetujuan_permintaan_po", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "proses"
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
			<i class="fa fa-check font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Persetujuan Permintaan Barang", $this->session->userdata("language"))?></span>
		</div>
		<?php
			$back_text          = translate('Kembali', $this->session->userdata('language'));
			$confirm_save       = translate('Apa Kamu Yakin Akan Proses Persetujuam Permintaan Barang Ini ?',$this->session->userdata('language'));
			$submit_text        = translate('Simpan', $this->session->userdata('language'));
		?>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=$back_text?>
	        </a>
	        <button type="submit" id="save" class="btn btn-primary hidden" >
	        	<?=$submit_text?>
	        </button>
	        <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal">
        		<i class="fa fa-check"></i>
	        	<?=$submit_text?>
	        </a>
		        
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
							<div class="portlet light bordered">
								<div class="portlet-title">
									<div class="caption">
										<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
									</div>
								</div>

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
									<label class="control-label col-md-4"><?=translate("order_setuju", $this->session->userdata("language"))?> :</label>
									<div class="col-md-4">
										<?php
											$order_setuju = array(
												"id"			=> "order_setuju",
												"name"			=> "order_setuju",
												"class"			=> "form-control", 
												"value"			=> $form_data[0]['order'],
											);
											echo form_input($order_setuju);
										?>
									</div>
								</div>
								<div class="form-group hidden">
									<label class="control-label col-md-4"><?=translate("cabang_id", $this->session->userdata("language"))?> :</label>
									<div class="col-md-4">
										<?php
											$cabang_id = array(
												"id"			=> "cabang_id",
												"name"			=> "cabang_id",
												"class"			=> "form-control", 
												"value"			=> $form_data[0]['cabang_id'],
											);
											echo form_input($cabang_id);
										?>
									</div>
								</div>
								<div class="form-group hidden">
									<label class="control-label col-md-4"><?=translate("user level id", $this->session->userdata("language"))?> :</label>
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

								<div class="form-group hidden">
									<label class="control-label col-md-4"><?=translate("Order", $this->session->userdata("language"))?> :</label>
									<div class="col-md-4">
										<?php
											$order = array(
												"id"			=> "order",
												"name"			=> "order",
												"class"			=> "form-control", 
												"placeholder"	=> translate("order", $this->session->userdata("language")), 
												"value"			=> $form_data[0]['order'],
												// "help"			=> $flash_form_data['order'],
											);
											echo form_input($order);
										?>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Tanggal :", $this->session->userdata("language"))?></label>		
									<div class="col-md-8">
										<label class="control-label"><?=date('d M Y', strtotime($data_order[0]['tanggal']))?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Dibuat Oleh :", $this->session->userdata("language"))?></label>		
									<div class="col-md-8">
										<?php $user_create = $this->user_m->get($data_order[0]['created_by'])?>
										<?php  $user_level_id = $this->user_level_m->get_nama($data_order[0]['created_by'])->result_array()?>
				
										<label class="control-label"><?=$user_create->nama?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Ditujukan Ke :", $this->session->userdata("language"))?></label>		
									<div class="col-md-8">
										<?php $user_create = $this->user_m->get($data_order[0]['user_id'])?>
										<label class="control-label"><?=$user_create->nama?></label>
									</div>
								</div>
			                    <div class="form-group">
									<label class="control-label col-md-4"><?=translate("Subjek :", $this->session->userdata("language"))?></label>		
									<div class="col-md-8">
										<label class="control-label"><?=$data_order[0]['subjek']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>
									<div class="col-md-8">
										<label class="control-label" style="text-align: left;"><?=$data_order[0]['keterangan']?></label>
									</div>
								</div>
							</div>
						</div>
						
						<?php
					    //	$btn_search   = '<div class="text-center"><button title="Search Account" class="btn btn-xs btn-success search-account"><i class="fa fa-search"></i></button></div>';
					    	$btn_search 		= '<div class="text-center"><button type="button" title="" class="btn btn-xs btn-primary search-account"><i class="fa fa-search"></i></button></div>';
					    	$btn_search_titipan = '<div class="text-center"><button type="button" title="" class="btn btn-xs btn-success search-account-titipan"><i class="fa fa-search"></i></button></div>';
					    	$btn_plus   		= '<div class="text-center"><button title="Add Row" class="btn btn-xs btn-success add_row"><i class="fa fa-plus"></i></button></div>';
							$btn_del           	= '<div class="text-center"><button class="btn btn-xs red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
							$btn_del_plus       = '<div class="text-center"><button class="btn btn-xs red del-this-plus" title="Delete"><i class="fa fa-times"></i></button></div>';
							$btn_unggah_gbr		= '<div class="text-center"><button type="button" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'pembelian/permintaan_po/unggah_gambar/item_row_plus_{0}/{0}" class="btn btn-xs blue-chambray unggah-gambar name="tindakan[{0}][gambar]" title="Unggah Gambar"><i class="fa fa-image"></i></button></div>'; 
							$btn_unggah_file	= '<div class="text-center"><button type="button" data-toggle="modal" data-target="#popup_modal_file" href="'.base_url().'pembelian/permintaan_po/unggah_file/item_row_plus_{0}/{0}" class="btn btn-xs gray-default unggah-file" name="tindakan[{0}][file]" title="Unggah File"><i class="fa fa-file"></i></button></div>'; 
	// '.$row['id'].'
	// <a title="'.translate('Unggah Gambar', $this->session->userdata('language')).'" href="'.base_url().'pembelian/permintaan_po/unggah_gambar/" data-toggle="modal" data-target="#popup_modal" class="btn btn-xs green-haze"><i class="fa fa-image"></i></a>			
							$attrs_persetujuan_permintaan_barang_id = array(
							    'id'          => 'items_id_{0}',
							    'name'        => 'items[{0}][persetujuan_permintaan_barang_id]',
							    'class'       => 'form-control hidden',
							);

							$attrs_order_permintaan_barang_id = array(
							    'id'          => 'items_order_permintaan_barang_id_{0}',
							    'name'        => 'items[{0}][order_permintaan_barang_id]',
							    'class'       => 'form-control hidden',
							);

							$attrs_order_permintaan_barang_detail_id = array(
							    'id'          => 'items_order_permintaan_barang_detail_id_{0}',
							    'name'        => 'items[{0}][order_permintaan_barang_detail_id]',
							    'class'       => 'form-control hidden',
							);

							$attrs_item_id = array(
							    'id'          => 'items_id_{0}',
							    'name'        => 'items[{0}][item_id]',
							    'class'       => 'form-control hidden',
							);

							$attrs_item_satuan_id = array(
							    'id'          => 'items_satuan_id_{0}',
							    'name'        => 'items[{0}][item_satuan_id]',
							    'class'       => 'form-control hidden',
							);

							$attrs_item_sub_kategori_id = array(
							    'id'          => 'items_sub_kategori_id_{0}',
							    'name'        => 'items[{0}][item_sub_kategori_id]',
							    'class'       => 'form-control hidden',
							);
							
							$attrs_item_jumlah = array(
							    'id'          => 'items_jumlah_{0}',
							    'name'        => 'items[{0}][jumlah]',
							    'class'       => 'form-control',
							    'value'		  =>  0,
							    'type'		  => 'number'
							    // 'readonly'    => 'readonly',
							    // 'style'       => 'width:180px;',
							);


							$attrs_item_satuan = array(
							    'id'          => 'items_satuan_{0}',
							    'name'        => 'items[{0}][satuan]',
							    'class'       => 'form-dropdown',
							    'value'		  => '<?=php?>',
							    // 'readonly'    => 'readonly',
							    // 'style'       => 'width:180px;',
							);

							$attrs_item_satuan_persetujuan = array(
							    'id'          => 'items_satuan_persetujuan_{0}',
							    'name'        => 'items[{0}][satuan_persetujuan]',
							    'class'       => 'form-control hidden',
							    'readonly'    => 'readonly',
							    // 'value'       => 0,
							    // 'style'       => 'width:180px;',
							);

							$attrs_item_tipe_permintaan= array(
							    'id'          => 'items_tipe_permintaan_{0}',
							    'name'        => 'items[{0}][tipe_permintaan]',
							    'class'       => 'form-control hidden',
							    'readonly'    => 'readonly',
							    // 'value'       => 0,
							    // 'style'       => 'width:180px;',
							);

							////////////////////////////////////////////////////////////////

							$attrs_account_id_box = array(
							    'id'          => 'account_id_box_{0}',
							    'name'        => 'box[{0}][id_box]',
							    'class'       => 'form-control hidden',
							    'readonly'    => 'readonly',
							    // 'style'       => 'width:180px;',
							);

							$attrs_account_item_id_box = array(
							    'id'          => 'account_item_id_box_{0}',
							    'name'        => 'account[{0}][item_id_box]',
							    'class'       => 'form-control',
							    'readonly'    => 'readonly',
							    // 'style'       => 'width:180px;',
							);

							$attrs_account_nama_box = array(
							    'id'          => 'account_nama_box_{0}',
							    'name'        => 'account[{0}][nama_box]',
							    'class'       => 'form-control',
							    'readonly'    => 'readonly',
							    // 'style'       => 'width:180px;',
							);

							$attrs_account_jumlah_box = array(
							    'id'          => 'account_jumlah_box_{0}',
							    'name'        => 'account[{0}][jumlah_box]',
							    'class'       => 'form-control hidden',
							    'readonly'    => 'readonly',
							    // 'style'       => 'width:180px;',
							);

							$attrs_account_item_satuan_id_box = array(
							    'id'          => 'account_item_satuan_id_box_{0}',
							    'name'        => 'account[{0}][item_satuan_id_box]',
							    'class'       => 'form-control',
							    'readonly'    => 'readonly',
							    // 'style'       => 'width:180px;',
							);

							$attrs_box_persetujuan_permintaan_barang_id = array(
							    'id'          => 'box_persetujuan_permintaan_barang_id{0}',
							    'name'        => 'box[{0}][persetujuan_permintaan_barang_id]',
							    'class'       => 'form-control hidden',
							);

							$attrs_box_order_permintaan_barang_id = array(
							    'id'          => 'box_order_permintaan_barang_id{0}',
							    'name'        => 'box[{0}][order_permintaan_barang_id]',
							    'class'       => 'form-control hidden',
							);



							$records = $this->persetujuan_permintaan_barang_m->get_data_item_terdaftar($form_data[0]['order_permintaan_barang_id'], $form_data[0]['user_level_id'])->result_array();
							
							// die(dump($records));
							$i = 0;
							if($records)
							{
								foreach ($records as $key=>$data) {

									$checked = '';
									$disabled = '';
									$readonly = '';
									if($data['status'] == 4)
									{
										$checked = 'checked="checked"';
										$disabled = 'disabled="disabled"';
										$readonly = 'readonly="readonly"';
										$attrs_item_satuan['readonly'] = 'readonly';
										$input_check = '<div class="text-center"><input class="checkboxes hidden" name="items[{0}][checkbox]" id="checkbox_'.$i.'" type="checkbox" '.$checked.' '.$readonly.'><input class="checkboxes" name="itemss[{0}][checkbox]" id="checkbox_'.$i.'" type="checkbox" '.$checked.' '.$disabled.'></div>';
									}
									else
									{
										unset($attrs_item_satuan['readonly']);
										$input_check = '<div class="text-center"><input class="checkboxes" name="items[{0}][checkbox]" id="checkbox_'.$i.'" type="checkbox" '.$checked.' '.$readonly.'></div>';
									}

									// die_dump($data);
									$attrs_persetujuan_permintaan_barang_id['value'] = $data['persetujuan_permintaan_barang_id'];
									$attrs_order_permintaan_barang_id['value'] = $data['order_permintaan_barang_id'];
									$attrs_order_permintaan_barang_detail_id['value'] = $data['order_permintaan_barang_detail_id'];
									$attrs_item_id['value'] = $data['item_id'];
									$attrs_item_satuan_id['value'] = $data['item_satuan_id'];
									$attrs_item_sub_kategori_id['value'] = $data['item_sub_kategori'];
									$attrs_item_kode['value'] = $data['kode'];
									$attrs_item_nama['value'] = $data['nama'];
									$attrs_item_jumlah['value'] = $data['jumlah_setujui'];
									$attrs_item_satuan['value'] = $data['nama_satuan_order'];
									$attrs_item_satuan_persetujuan['value'] = $data['satuan_id'];
									$attrs_item_tipe_permintaan['value'] = $data['tipe_permintaan'];

									$satuan = $this->persetujuan_permintaan_barang_m->get_satuan($data['item_id'])->result_array();
									// die_dump($this->db->last_query());
									// die_dump($satuan);
									$satuan_option = array();

									foreach ($satuan as $sub_satuan) {

										// die_dump($sub_satuan);
										$satuan_option[$sub_satuan['id']] = $sub_satuan['nama'];

									}


									// item row column
									$item_cols = array(// style="width:156px;
										'item_kode' 	 	  => form_input($attrs_persetujuan_permintaan_barang_id).form_input($attrs_order_permintaan_barang_id).form_input($attrs_order_permintaan_barang_detail_id).form_input($attrs_item_id).form_input($attrs_item_satuan_id).form_input($attrs_item_sub_kategori_id).'<label class="control-label" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;">'.$data['kode'].'</label>',
										'item_nama' 	 	  => form_input($attrs_item_tipe_permintaan).'<label cass="control-label" name="items[{0}][item_nama]" style="text-align : left !important; width : 150px !important;">'.$data['nama'].'</label>',
										'item_jumlah' 		  => '<div class="text-center"><label class="control-label" name="items[{0}][jumlah_item]">'.$data['jumlah'].' '.$data['nama_satuan_order'].'</label></div>',
										'item_jumlah_setujui' => '<div class="input-group text-right" style="width: 120px;"> 
	                                                            	<input class="form-control" type="number" id="items_jumlah_{0}" name="items[{0}][jumlah]" min="0" value="'.$data['jumlah_setujui'].'" '.$readonly.'></input>
	                                                            		<span class="input-group-btn">
	                                                            			<button type="button" name="items[{0}][info_item_terdaftar_user]" title="Semua Stock" class="btn btn-primary pilih-item-terdaftar-user" data-op-detail-id="'.$data['order_permintaan_barang_detail_id'].'" data-id="'.$data['order_permintaan_barang_id'].'" data-item-id="'.$data['item_id'].'" data-level-id="'.$id_user_level.'"><i class="fa fa-info"></i>
	                                                            			</button>
	                                                            		</span>
	                                                              </div>',
										'item_satuan' 		  => '<div style="width:120px;">'.form_dropdown('items[{0}][satuan]', $satuan_option, $data['satuan_setujui'], 'id="items_satuan_{0}" class="form-control" data-row="{0}" '.$readonly.'').'</div>',
										'item_harga' 		  => '<div class="text-right"><label class="control-label" name="items[{0}][item_harga]" '.$readonly.'>'.formatrupiah($data['harga_ref']).'</label></div>',
										'item_supplier' 		  => '<div class="text-left"><label class="control-label" name="items[{0}][item_supplier]">'.$data['nama_supp'].' ['.$data['kode_supp'].']</label></div>',
										'item_check'       	  => $input_check,
									);

									$item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>
															<tr><td colspan="1"> Keterangan </td>
																<td colspan="7"> <input class="form-control" id="items_keterangan_{0}" name="items[{0}][keterangan]" '.$readonly.' value="'.$data['keterangan'].'"></input>
															</tr>';
					    			$items_rows[] = str_replace('{0}', "{$key}", $item_row_template );

								$i++;							
								}

							} else {


								// item row column
								$item_cols = array(// style="width:156px;
									'item_kode' 	 	  => form_input($attrs_persetujuan_permintaan_barang_id).'<label class="control-label" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;"></label>',
									'item_nama' 	 	  => '<label cass="control-label" name="items[{0}][item_nama]" style="text-align : left !important; width : 150px !important;"></label>',
									'item_jumlah' 		  => '<div class="text-center"><label class="control-label" name="items[{0}][jumlah_item]"></label></div>',
									'item_jumlah_setujui' => '<div class="input-group text-right" style="width: 100%;"> 
                                                            	<input class="form-control" type="number" id="items_jumlah_{0}" name="items[{0}][jumlah]" min="1" value="1"></input>
                                                            		<span class="input-group-btn">
                                                            			<button type="button" name="items[{0}][info_item_terdaftar_user]" title="Semua Stock" class="btn btn-primary pilih-item-terdaftar-user"><i class="fa fa-info"></i>
                                                            			</button>
                                                            		</span>
                                                              </div>',
									// 'item_satuan' 		  => form_dropdown('items[{0}][satuan]', "id=\"items_satuan_{0}\" class=\"form-control\" data-row=\"{0}\""),
									'item_satuan' 		  => '<input class="form-control" type="number" id="items_jumlah_{0}" name="items[{0}][jumlah]" min="1" value="1"></input>',
									'item_check'       	  => '<div class="text-center"><input class="checkboxes" name="items[{0}][checkbox]" id="checkbox_'.$i.'" type="checkbox"></div>',
								);

								$item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
								$items_rows = array();

							}
							///////////////////////////////////////////////////////////////////////////////

							$records_box_paket = $this->persetujuan_permintaan_barang_m->get_data_item_box_paket($form_data[0]['order_permintaan_barang_id'], $form_data[0]['user_level_id'])->result_array();
							// die_dump($this->db->last_query());	
							// die_dump($records_box_paket);

                			// die_dump($item);

							$z = 0;
							if($records_box_paket)
							{

								foreach ($records_box_paket as $key=>$data_box) {

                					$item = $this->item_m->get_item_order_permintaan_barang_detail_box($data_box['order_permintaan_barang_id'], $data_box['box_paket_id'])->result_array();
									// die_dump($data_box);
									$attrs_box_persetujuan_permintaan_barang_id['value'] = $data_box['persetujuan_permintaan_barang_id'];
									$attrs_box_order_permintaan_barang_id['value']       = $data_box['order_permintaan_barang_id'];
									$attrs_account_nama_box['value']                     = $data_box['nama'];
									$attrs_account_id_box['value']                       = $data_box['box_paket_id'];
									$attrs_item_jumlah['value']                          = $data_box['jumlah_setujui'];
									$attrs_item_satuan['value']                          = $data_box['nama_satuan_order'];

									// item box paket row column
									$item_cols_box = array(

										'item_box_nama' 			=> form_input($attrs_box_persetujuan_permintaan_barang_id).form_input($attrs_account_id_box).form_input($attrs_box_order_permintaan_barang_id).
																		'<span class="input-group-btn">
																			<label cass="control-label" name="box[{0}][nama_box]" style="text-align : left !important; width : 700px !important;">'.$data_box['nama_box'].'</label>
																		 	<button type="button" name="box[{0}][item_box]" title="Item Box" class="btn btn-primary pilih-item-box" data-item="'.htmlentities(json_encode($item)).'" data-id="'.$data_box['order_permintaan_barang_id'].'" data-level-id="'.$data_box['user_level_id'].'"><i class="fa fa-info"></i>
                                                            			 	</button>
                                                            			 </span>',
										'item_box_jumlah_pesan' 	=> '<div class="text-center"><label class="control-label" name="box[{0}][jumlah_box]">'.$data_box['jumlah_pesan'].' box</label></div>',
										'item_box_jumlah_setujui'	=> '<div class="input-group text-right" style="width: 120px;"> 
			                                                            	<input class="form-control" type="number" id="box_jumlah_setujui_{0}" name="box[{0}][jumlah_setujui]" value="1" min="1" max="'.$data_box['jumlah_pesan'].'" ></input>
			                                                            		<span class="input-group-btn">
			                                                            			<button type="button"  name="box[{0}][info_item_box_disetujui]" title="Semua Stock" class="btn btn-primary pilih-item-box-disetujui" data-op-detail-id="'.$data_box['order_permintaan_barang_detail_id'].'" data-id="'.$data_box['order_permintaan_barang_id'].'" data-level-id="'.$data_box['user_level_id'].'"><i class="fa fa-info"></i>
			                                                            			</button>
			                                                            		</span>
			                                                              </div>',
										'item_box_check'			=> '<div class="text-center"><input class="checkboxes" name="box[{0}][checkbox]" id="checkbox_'.$z.'" type="checkbox"></div>',								

									);

									$item_row_template_box =  '<tr id="item_row_box_{0}"><td>' . implode('</td><td>', $item_cols_box) . '</td></tr>
																<tr><td colspan="1"> Keterangan </td>
																	<td colspan="3"> <input class="form-control" id="box_keterangan_{0}" name="box[{0}][keterangan]"></input>
																</tr>';
					    			$items_rows_box[] = str_replace('{0}', "{$key}", $item_row_template_box );
									

								$z++;
								}

							} else {

								// item box paket row column
								$item_cols_box = array(

									'item_box_nama' 			=> '<label cass="control-label" name="box[{0}][nama_box]" style="text-align : left !important; width : 150px !important;"></label>',
									'item_box_jumlah_pesan' 	=> '<div class="text-center"><label class="control-label" name="box[{0}][jumlah_box]"></label></div>',
									'item_box_jumlah_setujui'	=> '<div class="input-group text-right" style="width: 120px;"> 
		                                                            	<input class="form-control" id="box_jumlah_setujui_{0}" name="box[{0}][jumlah_setujui]" value="0"></input>
		                                                            		<span class="input-group-btn">
		                                                            			<button type="button" name="box[{0}][info_item_box]" title="Semua Stock" class="btn btn-primary pilih-item-box-user" data-id="0" data-level-id="0"><i class="fa fa-info"></i>
		                                                            			</button>
		                                                            		</span>
		                                                              </div>',
									'item_box_check'			=> '<div class="text-center"><input class="checkboxes" name="box[{0}][checkbox]" id="checkbox_'.$z.'" type="checkbox"></div>',								

								);

								$item_row_template_box =  '<tr id="item_row_box_{0}"><td>' . implode('</td><td>', $item_cols_box) . '</td></tr>';
								$items_rows_box = array();
							}


							//////////////////////////////////////////////////////////////////////////////
							$attrs_account_id_titipan = array(
							    'id'          => 'account_tindakan_id_{0}',
							    'name'        => 'tindakan[{0}][tindakan_id]',
							    'class'       => 'form-control hidden',
							    'width'		  => '10%',
							    'readonly'    => 'readonly',
							);


							$attrs_account_kode = array(
							    'id'          => 'account_kode_titipan_{0}',
							    'name'        => 'tindakan[{0}][kode_titipan]',
							    'class'       => 'form-control',
							    'width'		  => '10%',
							    'readonly'    => 'readonly',
							);

							$attrs_account_nama = array(
							    'id'          => 'account_nama_tindakan_{0}',
							    'name'        => 'tindakan[{0}][nama_tindakan]',
							    'class'       => 'form-control',
							    // 'style'       => 'width:180px;',
							);


							$attrs_item2_jumlah = array(
							    'id'          => 'item2_jumlah_{0}',
							    'name'        => 'item2[{0}][jumlah_item]',
							    'class'       => 'form-control',
							    'value'       => 0,
							    'type'		  => 'number'

							    // 'style'       => 'width:180px;',
							);

							$attrs_item2_satuan = array(
							    'id'          => 'item2_satuan_{0}',
							    'name'        => 'item2[{0}][satuan]',
							    'class'       => 'form-control',

							    // 'style'       => 'width:180px;',
							);

							$attrs_account_upload_file = array(
							    'id'          => 'account_upload_file_{0}',
							    'name'        => 'item2[{0}][upload_file]',
							    'class'       => 'form-control hidden',
							    'readonly'    => 'readonly',
							    // 'value'       => 0,
							    // 'style'       => 'width:180px;',
							);

							$attrs_account_url_file = array(
							    'id'          => 'account_url_file_{0}',
							    'name'        => 'item2[{0}][url_file]',
							    'class'       => 'form-control hidden',
							    'readonly'    => 'readonly',
							    // 'value'       => 0,
							    // 'style'       => 'width:180px;',
							);

							$attrs_account_url_gambar = array(
							    'id'          => 'account_url_gambar_{0}',
							    'name'        => 'item2[{0}][url_gambar]',
							    'class'       => 'form-control hidden',
							    'readonly'    => 'readonly',
							    // 'value'       => 0,
							    // 'style'       => 'width:180px;',
							);

							$attrs_item2_persetujuan_permintaan_barang_id = array(
							    'id'          => 'account_persetujuan_permintaan_barang_id_{0}',
							    'name'        => 'item2[{0}][persetujuan_permintaan_barang_id]',
							    'class'       => 'form-control hidden',
							);

							$attrs_item2_nama = array(
							    'id'          => 'account_nama_item_{0}',
							    'name'        => 'item2[{0}][nama_item]',
							    'class'       => 'form-control hidden',
							);

							$attrs_item2_data_order = array(
							    'id'          => 'account_data_order_{0}',
							    'name'        => 'item2[{0}][data_order]',
							    'class'       => 'form-control hidden',
							);

							$attrs_item2_order_permintaan_barang_id = array(
							    'id'          => 'account_order_permintaan_barang_id_{0}',
							    'name'        => 'item2[{0}][order_permintaan_barang_id]',
							    'class'       => 'form-control hidden',
							);

							$attrs_item2_order_permintaan_barang_detail_id = array(
							    'id'          => 'account_order_permintaan_barang_detail_id_{0}',
							    'name'        => 'item2[{0}][order_permintaan_barang_detail_id]',
							    'class'       => 'form-control hidden',
							);

							$data_item_tidak_terdaftar = $this->persetujuan_permintaan_barang_m->get_data_item_tidak_terdaftar($form_data[0]['order_permintaan_barang_id'], $form_data[0]['user_level_id'])->result_array();
							// die_dump($this->db->last_query());		
							// die_dump($data_item_tidak_terdaftar);

							$z = 0;
							foreach ($data_item_tidak_terdaftar as $key => $data) {

								$checked = '';
								$disabled = '';
								$readonly = '';
								if($data['status'] == 4)
								{
									$checked = 'checked="checked"';
									$disabled = 'disabled="disabled"';
									$readonly = 'readonly="readonly"';
									$attrs_item2_satuan['readonly'] = 'readonly';
									$input_check = '<div class="text-center"><input class="checkboxes hidden" name="item2[{0}][checkbox]" id="checkbox_'.$z.'" type="checkbox" '.$checked.' '.$readonly.'><input class="checkboxes" name="itemss[{0}][checkbox]" id="checkbox_'.$z.'" type="checkbox" '.$checked.' '.$disabled.'></div>';
								}
								else
								{
									unset($attrs_item2_satuan['readonly']);
									$input_check = '<div class="text-center"><input class="checkboxes" name="item2[{0}][checkbox]" id="checkbox_'.$z.'" type="checkbox" '.$checked.' '.$readonly.'></div>';
								}

								$attrs_item2_persetujuan_permintaan_barang_id['value'] = $data['persetujuan_permintaan_barang_id'];
								$attrs_item2_order_permintaan_barang_id['value'] = $data['order_permintaan_barang_id'];
								$attrs_item2_order_permintaan_barang_detail_id['value'] = $data['order_permintaan_barang_detail_id'];
								$attrs_item2_nama['value'] = $data['nama_item'];
								$attrs_item2_jumlah['value'] = $data['jumlah_setujui'];
								$attrs_item2_satuan['value'] = $data['satuan_item'];
								$attrs_item2_data_order['value'] = $data['data_order'];
									$attrs_item2_satuan['readonly'] = 'readonly';
								

								$link_pdf = '';
								$link_img = '';
								$data_pdf = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $data['order_permintaan_barang_detail_id'], 'tipe' => 1 ), true);
								$data_img = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $data['order_permintaan_barang_detail_id'], 'tipe' => 2 ), true);

								if(count($data_pdf))
								{
									$link_pdf = '<a target="_blank" href="'.base_url().'assets/mb/pages/pembelian/permintaan_po/doc/'.$data['nama_item'].'/'.$data_pdf->url.'" class="btn grey-cascade unggah-file" name="item2[{0}][file]" title="Unggah File"><i class="fa fa-file"></i></a>';
								}
								if(count($data_img))
								{
									$link_img = '<button type="button" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'pembelian/persetujuan_permintaan_po/lihat_gambar/'.$data['order_permintaan_barang_detail_id'].'" class="btn blue-chambray unggah-gambar name="item2[{0}][gambar]" title="Unggah Gambar"><i class="fa fa-image"></i>
																	</button>';
								}
								$jml_setujui = ($data['jumlah_setujui'] == '')?$data['jumlah_item']:$data['jumlah_setujui'];
								$harga_ref = ($data['harga_ref'] == '')?0:$data['harga_ref'];

								$item_cols_acc = array(// style="width:156px;
									'item2_name' 			=> form_input($attrs_item2_persetujuan_permintaan_barang_id).form_input($attrs_item2_order_permintaan_barang_id).form_input($attrs_item2_order_permintaan_barang_detail_id).'<label cass="control-label" name="item2[{0}][item_nama]" style="text-align : left !important; width : 150px !important;">'.$data['nama_item'].'</label>',
									'item2_jumlah' 			=> form_input($attrs_item2_nama).form_input($attrs_item2_data_order).'<div class="text-center"><label class="control-label" name="item2[{0}][jumlah_item]">'.$data['jumlah_item'].' '.$data['satuan_item'].'</label></div>',
									'item2_jumlah_setujui'	=> '<div class="input-group text-right" style="width: 120px;"> 
                                                            	<input class="form-control" type="number" id="item2_jumlah_{0}" name="item2[{0}][jumlah]" min="0"  value="'.$jml_setujui.'" '.$readonly.'></input>
                                                            		<span class="input-group-btn">
                                                            			<button type="button" name="item2[{0}][info_item_tidak_terdaftar_user]" title="Semua Stock" class="btn btn-primary pilih-item-tidak-terdaftar-user" data-op-detail-id="'.$data['order_permintaan_barang_detail_id'].'" data-id="'.$data['order_permintaan_barang_id'].'" data-level-id="'.$data['user_level_id'].'"><i class="fa fa-info"></i>
                                                            			</button>
                                                            		</span>	
                                                              </div>',
									'item2_satuan' 			=> '<div style="width:120px;">'.form_input($attrs_item2_satuan).'</div>',
									'item_harga' 		  	=> '<div class="text-right"><label class="control-label" name="items[{0}][item_harga]">'.formatrupiah($harga_ref).'</label></div>',
									'item_supplier' 		=> '<div class="text-left"><label class="control-label" name="items[{0}][item_supplier]">'.$data['nama_supp'].'</label></div>',
									'aksi'   				=> $link_pdf.$link_img,
									'item_check'    		=> $input_check,
								);
								

								// gabungkan $item_cols jadi string table row
								$item_row_template_acc =  '<tr id="item_row_plus_{0}" class="row_plus">
																<td>' . implode('</td>
																<td>', $item_cols_acc) . '</td>
															</tr>
															<tr><td colspan="1"> Keterangan </td>
																<td colspan="7"> <input class="form-control" id="items_keterangan_{0}" name="item2[{0}][keterangan]" value="'.$data['keterangan'].'" '.$readonly.'></input>
															</tr>';
				   				$items_rows_2[] = str_replace('{0}', "{$key}", $item_row_template_acc );
							}
							
					    ?>

					    <div class="col-md-9 hidden" id="section_terdaftar">
							<div class="portlet light bordered">
								<div class="portlet-title">
									<div class="caption">
										<span class="caption-subject"><?=translate("Permintaan Item Yang Terdaftar", $this->session->userdata("language"))?></span>
									</div>
								</div>
								<div class="portlet-body table-scrollable">
									<!-- <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span> -->
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_item_terdaftar">
	                                        <thead>
	                                            <tr>
	                                                <!-- <th width="10%"><div class="text-center"><?=translate("id", $this->session->userdata('language'))?></div></th> -->
	                                                <th width="10%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Jumlah Pesan", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Jumlah Setujui", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Harga Ref", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Supplier", $this->session->userdata('language'))?></div></th>
													<th width="1%" class="table-checkbox"><div class="text-center"><?=translate("Tolak", $this->session->userdata('language'))?>
														<input type="checkbox" class="group-checkable text-center" data-set="#table_add_item_terdaftar .checkboxes"/>
													</div></th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
		                                        <?php foreach ($items_rows as $row):?>
						                        	<?=$row?>
						                    	<?php endforeach;?>
	                                        </tbody>
	                                    </table>
	                                </div>
								</div>
							</div>
							
							<?php
								if($records_box_paket)
								{
							?>
								<div class="portlet light bordered">
									<div class="portlet-title">
										<div class="caption">
											<span class="caption-subject"><?=translate("Item Box Paket", $this->session->userdata("language"))?></span>
										</div>
									</div>
									<div class="portlet-body">
										<div class="table-responsive table-scrollable">
		                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_item_box_paket">
		                                        <thead>
		                                            <tr>
		                                                <!-- <th width="10%"><div class="text-center"><?=translate("id", $this->session->userdata('language'))?></div></th> -->
		                                                <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
		                                                <th width="10%"><div class="text-center"><?=translate("Jumlah Pesan", $this->session->userdata('language'))?></div></th>
		                                                <th width="10%"><div class="text-center"><?=translate("Jumlah Setujui", $this->session->userdata('language'))?></div></th>
														<th width="1%" class="table-checkbox"><div class="text-center"><?=translate("Tolak", $this->session->userdata('language'))?>
															<input type="checkbox" class="group-checkable text-center" data-set="#table_item_box_paket .checkboxes"/></div>
														</th>
		                                            </tr>
		                                        </thead>
		                                        <tbody>
		                                        	<?php foreach ($items_rows_box as $row):?>
							                        	<?=$row?>
							                    	<?php endforeach;?>
		                                            <!-- <?//=$item_row?> -->
		                                        </tbody>
		                                    </table>
		                                </div>
									</div>
								</div>
								
							<?php
								}
							?>
						</div>
					
						<div class="col-md-9 hidden" id="section_tidak_terdaftar">
							<div class="portlet light bordered">
								<div class="portlet-title">
									<div class="caption">
										<span class="caption-subject"><?=translate("Permintaan Item Yang Tidak Terdaftar", $this->session->userdata("language"))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="table-responsive  table-scrollable">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_item_tidak_terdaftar">
	                                        <thead>
	                                            <tr>
	                                                <!-- <th ><div class="text-center"><?=translate("id", $this->session->userdata('language'))?></div></th> -->
	                                                <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Jumlah Pesan", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Jumlah Setujui", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Harga Ref", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Supplier", $this->session->userdata('language'))?></div></th>
	                                                <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
													<th width="1%" class="table-checkbox">
														<div class="text-center"><?=translate("Tolak", $this->session->userdata('language'))?>
															<input type="checkbox" class="group-checkable text-center" data-set="#table_add_item_tidak_terdaftar .checkboxes"/>
														</div>
													</th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                            <?php foreach ($items_rows_2 as $row):?>
						                        	<?=$row?>
						                    	<?php endforeach;?>
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

<div id="popover_item_content" class="row">
    <div class="col-md-12">
		<div class="portlet">
			<div class="portlet-body">
		        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item_user">
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

<div id="popover_item_content_box" class="row">
    <div class="col-md-12">
		<div class="portlet">
			<div class="portlet-body table-scrollable">
		        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item_user_box">
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

<div id="popover_item_content_tidak_terdaftar">
    <div class="col-md-12">
		<div class="portlet">
			<div class="portlet-body table-scrollable">
		        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item_tidak_terdaftar_user">
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