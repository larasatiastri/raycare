<?php
    $this->cabang_m->set_columns(array('id','nama'));
    $categories = $this->cabang_m->get_by(array('tipe' => 1));
    // die_dump($categories);
    $categories_options = array(
    
    '' => translate('Pilih Cabang', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_options[$categories->id] = $categories->nama;
    }


    /////////////////////////////////////////////////////////////////////////////////

    $this->item_satuan_m->set_columns(array('id','nama'));
    $categories = $this->item_satuan_m->get();
    // die_dump($categories);
    $categories_satuan = array(
    
    '' => translate('Pilih Satuan', $this->session->userdata('language')) . '..',
    );

    // foreach ($categories as $categories) {
    //     $categories_satuan[$categories->id] = $categories->nama;
    // }

    //////////////////////////////////////////////////////////////////////////////
    
   	$form_attr = array(
		"id"			=> "form_edittemplate", 
		"name"			=> "form_edittemplate", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "edit"
	);


	echo form_open(base_url()."master/paket/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));


?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Paket", $this->session->userdata("language"))?></span>
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
					

					<div class="form-group hidden">
                        <label class="control-label col-md-3"><?=translate("ID :", $this->session->userdata("language"))?></label>
                        <div class="col-md-2">
                              <?php
								$id = array(
									"name"			=> "id",
									"id"			=> "id",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"readonly"		=> "readonly",
									"value"			=> $form_data['id'],
								);
								echo form_input($id);
							?>
                        </div>
                    </div>

					<div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Cabang :", $this->session->userdata("language"))?></label>
                        <div class="col-md-2">
                              <?php
                                 echo form_dropdown('tipe_transaksi',  $categories_options, $form_data['cabang_id'], "id=\"tipe_transaksi\" class=\"form-control bs-select\"")
                                ?>
                        </div>
                    </div>

                    <div class="form-group hidden">
						<div class="col-md-1">
							<input class="form-control" value="" id="poliklinik_id" name="poliklinik_id">
						</div>
					</div> 
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Poliklinik :", $this->session->userdata("language"))?></label>
                        <div class="col-md-1 hidden	">
							<input class="form-control" value="" id="poliklinik_id" name="poliklinik_id">
						</div>
                        <div class="col-md-3">
                            <?php

							    
							    $categories_poliklinik = array();
        						
        						$data_poliklinik = $this->poliklinik_paket_m->get_by(array('paket_id' => $pk_value));
			                    $array_poliklinik = array();
                            	foreach ($data_poliklinik_paket as $data) 
                            	{
			                       	$array_poliklinik[$data['id']] = $data['poliklinik_id'];
                            	}
                            	// die_dump($array_poliklinik);

                            echo form_dropdown('poli[]', $categories_poliklinik, '', 'id = "poli" class ="form-control" multiple="multiple" required');

                            ?>

                            
                        </div>

                        <div class="set_data">
                            	
                            </div>
                    </div>

                    <div class="form-group">
						<label class="control-label col-md-3"><?=translate("Kode :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<?php
								$kode = array(
									"name"			=> "kode",
									"id"			=> "kode",
									"class"			=> "form-control", 
									"placeholder"	=> translate("Kode", $this->session->userdata("language")), 
									"readonly"		=> "readonly",
									"value"			=> $form_data['kode'],
								);
								echo form_input($kode);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Nama :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<?php
								$nama_paket = array(
									"name"			=> "nama_paket",
									"id"			=> "nama_paket",
									"class"			=> "form-control", 
									"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
									"value"			=> $form_data['nama'],
									"required"		=> "required"
								);
								echo form_input($nama_paket);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>
						<div class="col-md-4">
							
							<?php
								$keterangan = array(
									"name"			=> "keterangan",
									"id"			=> "keterangan",
									"class"			=> "form-control",
									"rows"			=> 6, 
									"value"			=> $form_data['keterangan'],
									"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
								);
								echo form_textarea($keterangan);
							?>
							<!-- <textarea rows="6" class="form-control" id="description" name="description"></textarea> -->
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Create By", $this->session->userdata("language"))?> :</label>
						<div class="col-md-3">
							<?php 
								$user_create = $this->user_m->get($form_data['created_by']);
							?>
							<label class="control-label"> <?=$user_create->nama?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Create Date", $this->session->userdata("language"))?> :</label>
						<div class="col-md-3">
							<label class="control-label"><?=date('d F Y H:i:s', strtotime($form_data['created_date']))?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Modified By", $this->session->userdata("language"))?> :</label>
						<div class="col-md-3">
							<?php 
								if ($form_data['modified_by']) 
								{
									$user_modified = $this->user_m->get($form_data['modified_by']);
									$user_modified = object_to_array($user_modified);
								}
								else {
									$user_modified['nama'] = $form_data['modified_by'];
								}
							?>
							<label class="control-label"><?=$user_modified['nama']?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Modified Date", $this->session->userdata("language"))?> :</label>
						<div class="col-md-3">
							<?php 
								if ($form_data['modified_date']) 
								{
									$modified_date = date('d F Y H:i:s', strtotime($form_data['modified_date']));
								} else {
									$modified_date = $form_data['modified_date'];
								}
							?>
							<label class="control-label"><?=$modified_date?></label>
							<input type="hidden" name="modified_date" value="<?=$form_data['modified_date']?>">
							<a target="_blank" id="open_new_tab" class="btn btn-sm btn-primary hidden" href="<?=base_url()?>master/paket/edit/<?=$pk_value?>" ><?=translate("Open", $this->session->userdata("language"))?></a>
						</div>
					</div>


				    <?php

				    	//btn_search   = '<div class="text-center"><button title="Search Account" class="btn btn-xs btn-success search-account"><i class="fa fa-search"></i></button></div>';
						$btn_search          = '<div class="text-center"><button type="button" title="" class="btn btn-xs btn-primary search-account"><i class="fa fa-search"></i></button></div>';
						$btn_search_tindakan = '<div class="text-center"><button type="button" title="" class="btn btn-xs btn-primary search-account-tindakan"><i class="fa fa-search"></i></button></div>';
						$btn_plus            = '<div class="text-center"><button title="Add Row" class="btn btn-xs btn-success add_row"><i class="fa fa-plus"></i></button></div>';
						$btn_del             = '<div class="text-center"><button class="btn btn-xs red-intense del-this" title="Delete Paket Item"><i class="fa fa-times"></i></button></div>';
						$btn_del_plus        = '<div class="text-center"><button class="btn btn-xs red-intense del-this-plus" title="Delete Paket Item"><i class="fa fa-times"></i></button></div>';

						$attrs_account_id = array(
						    'id'          => 'account_id_{0}',
						    'name'        => 'account[{0}][account_id]',
						    'class'       => 'form-control input-xs hidden',
						    'readonly' => 'readonly',
						);

						$attrs_account_item_id = array(
						    'id'          => 'account_item_id_{0}',
						    'name'        => 'account[{0}][item_id]',
						    'class'       => 'form-control input-xs hidden',
						    'readonly' => 'readonly',
						);

						$attrs_is_delete = array (
						    'id'       => 'account_is_delete_{0}',
						    'name'     => 'account[{0}][is_delete]',
						    'class'    => 'form-control input-sm hidden',
						    'readonly' => 'readonly',
						);

						$attrs_account_code = array(
						    'id'          => 'account_code_{0}',
						    'name'        => 'account[{0}][kode]',
						    'class'       => 'form-control input-xs hidden',
						    'width'		  => '100%',
						    'disabled'    => 'disabled',
						);

						$attrs_account_name = array(
						    'id'          => 'account_name_{0}',
						    'name'        => 'account[{0}][nama]',
						    'class'       => 'form-control input-xs hidden',
						    // 'readonly'    => 'readonly',
						    // 'style'       => 'width:180px;',
						);

						$attrs_account_jumlah = array(
						    'id'          => 'account_jumlah_{0}',
						    'name'        => 'account[{0}][jumlah]',
						    'class'       => 'form-control text-center input-xs',
						    'value'		  => 1,
						    'type'		  => 'number',
						    'min'		  => 0
						    // 'readonly'    => 'readonly',
						    // 'style'       => 'width:180px;',
						);


						$attrs_account_satuan = array(
						    'id'          => 'account_satuan_{0}',
						    'name'        => 'account[{0}][satuan]',
						    'class'       => 'form-dropdown input-xs',
						    'value'		  => '<?=php?>',
						    // 'readonly'    => 'readonly',
						    // 'style'       => 'width:180px;',
						);

						$attrs_account_names = array(
						    'id'          => 'account_names_{0}',
						    'name'        => 'account[{0}][names]',
						    'class'       => 'form-control input-xs',
						);

						$attrs_account_sub_total = array(
						    'id'          => 'account_sub_total_{0}',
						    'name'        => 'account[{0}][sub_total]',
						    'class'       => 'form-control text-center input-xs hidden',
						    'readonly'    => 'readonly',
						    // 'value'       => 0,
						    // 'style'       => 'width:180px;',
						);

						$attrs_account_harga = array(
						    'id'          => 'account_harga_{0}',
						    'name'        => 'account[{0}][harga]',
						    'class'       => 'form-control text-center input-xs hidden',
						    'readonly'    => 'readonly',
						    // 'value'       => 0,
						    // 'style'       => 'width:180px;',
						);


					
						foreach ($data_paket_item as $key => $data) {
							// die_dump($data);
							$harga = $data['harga'];

						}


						// item row column
						$item_cols = array(// style="width:156px;
							'account_code' 	 	=> form_input($attrs_account_item_id).form_input($attrs_account_id).form_input($attrs_is_delete).'<label class="control-label col-md-8" id="account_kode_{0}" name="account[{0}][kode]"></label>',
							'btn_search'   	 	=> $btn_search,
							'account_name' 	 	=> form_input($attrs_account_name).'<label class="control-label" id="account_nama_{0}" name="account[{0}][nama]"></label>',
							'account_dijual' 	=> '<div class="text-center"><input type="checkbox" id="account_dijual_{0}" name="account[{0}][dijual]" value="1"></div>',
							'account_jumlah' 	=> form_input($attrs_account_jumlah),
							'account_satuan' 	=> form_dropdown('account[{0}][satuan]',  $categories_satuan, "", "id='account_satuan_{0}' class='form-control '"),
							'account_harga' 	=> form_input($attrs_account_harga).'<label class="control-label col-md-12"  value="" id="account_harga_{0}" name="account[{0}][harga]"></label>',
							'account_sub_total' => form_input($attrs_account_sub_total).'<label class="control-label col-md-12 subtotal" id="account_sub_total_{0}" name="account[{0}][sub_total]"></label>',
							'action'       		=> $btn_del,
						);

						// ==================== TINDAKAN =======================
						$attrs_account_id_tindakan = array(
						    'id'          => 'account_tindakan_id_{0}',
						    'name'        => 'tindakan[{0}][tindakan_id]',
						    'class'       => 'form-control input-xs hidden',
						    'width'		  => '10%',
						    'readonly'    => 'readonly',
						);

						$attrs_account_id_paket_tindakan = array(
						    'id'          => 'account_paket_tindakan_id_{0}',
						    'name'        => 'tindakan[{0}][paket_tindakan_id]',
						    'class'       => 'form-control input-xs hidden',
						    'width'		  => '10%',
						    'readonly'    => 'readonly',
						);

						$attrs_is_delete_tindakan = array (
						    'id'       => 'tindakan_is_delete_{0}',
						    'name'     => 'tindakan[{0}][is_delete]',
						    'class'    => 'form-control input-sm hidden',
						    'readonly' => 'readonly',
						);


						$attrs_account_kode = array(
						    'id'          => 'account_kode_tindakan_{0}',
						    'name'        => 'tindakan[{0}][kode_tindakan]',
						    'class'       => 'form-control input-xs hidden',
						    'width'		  => '10%',
						    'readonly'    => 'readonly',
						);

						$attrs_account_nama = array(
						    'id'          => 'account_nama_tindakan_{0}',
						    'name'        => 'tindakan[{0}][nama_tindakan]',
						    'class'       => 'form-control input-xs hidden',
						    'readonly'    => 'readonly',
						    // 'style'       => 'width:180px;',
						);


						$attrs_account_jumlah_tindakan = array(
						    'id'          => 'account_jumlah_tindakan_{0}',
						    'name'        => 'tindakan[{0}][jumlah_tindakan]',
						    'class'       => 'form-control text-center input-xs',
						    'value'       => 1,
						    'min'		  => 0,
						    'type'		  => 'number'

						    // 'style'       => 'width:180px;',
						);

						$attrs_account_harga_tindakan = array(
						    'id'          => 'account_harga_tindakan_{0}',
						    'name'        => 'tindakan[{0}][harga_tindakan]',
						    'class'       => 'form-control text-center input-xs hidden',
						    'readonly'    => 'readonly',
						    // 'value'       => 0,
						    // 'style'       => 'width:180px;',
						);

						$attrs_account_sub_total_tindakan = array(
						    'id'          => 'account_sub_total_tindakan_{0}',
						    'name'        => 'tindakan[{0}][sub_total_tindakan]',
						    'class'       => 'form-control text-center input-xs hidden',
						    'readonly'    => 'readonly',
						    // 'value'       => 0,
						    // 'style'       => 'width:180px;',
						);
						

						$item_cols_acc = array(// style="width:156px;
							'account_code' 		=> form_input($attrs_account_id_paket_tindakan).form_input($attrs_account_kode).form_input($attrs_account_id_tindakan).form_input($attrs_is_delete).'<label class="control-label col-md-8 text-center" id="tindakan_kode_tindakan_{0}" name="tindakan[{0}][kode_tindakan]"> </label>',
							'btn_search'   		=> $btn_search_tindakan,
							'account_name' 		=> form_input($attrs_account_nama).'<label class="control-label" id="tindakan_nama_tindakan_{0}" name="tindakan[{0}][nama_tindakan]"></label>',
							'account_jumlah' 	=> form_input($attrs_account_jumlah_tindakan),
							'account_harga'	   	=> form_input($attrs_account_harga_tindakan).'<label class="control-label col-md-12" id="tindakan_harga_tindakan_{0}" name="tindakan[{0}][harga_tindakan]"></label>',
							'account_sub_total' => form_input($attrs_account_sub_total_tindakan).'<label class="control-label col-md-12 subtotal_tindakan" id="tindakan_sub_total_{0}" name="tindakan[{0}][sub_total_tindakan]"></label>',
							'action'       		=> $btn_del_plus,
						);

						// gabungkan $item_cols jadi string table row
						$item_row_template =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
						$item_row_template_acc =  '<tr id="item_row_plus_{0}" class="row_plus"><td>' . implode('</td><td>', $item_cols_acc) . '</td></tr>';
						
						



						// ================================== THIS BEGIN DATA FORM DATABASE ==================================

						$i = 0;
						$dummy_rows = array();
						foreach ($data_paket_item as $key => $data) {
						
						
						$btn_search_a = '<div class="text-center"><button type="button" disabled title="" class="btn btn-sm btn-primary search-account"><i class="fa fa-search"></i></button></div>';
						$btn_del_db   = '<div class="text-center"><button class="btn btn-sm red-intense del-db" title="Delete Paket Item"><i class="fa fa-times"></i></button></div>';
						
							if($data['is_sale'] == 1) {

								$check = 'checked';

							} elseif ($data['is_sale'] == 0) {

								$check = '';

							}

							if($data['paket_id'] != ''){

								$account_info = $this->item_m->get_by(array('id' => $data['item_id']));
								$item = object_to_array($account_info);
								// $paket_item = $this->item_m->get($item['id']);

								// die(dump($item));
								// die(dump($this->db->last_query()));

								foreach ($account_info as $paket_info)
								{
									$nama = $paket_info->nama;
									$kode = $paket_info->kode;
									// $jumlah = $paket_info->jumlah;

								}

							}

							// die_dump($data_paket_item);
            				$satuan = $this->item_satuan_m->get_by(array('item_id' => $data['item_id']));
            				$satuan_option = array(
            					''	=> 'Pilih Satuan...'
            				);

            				foreach ($satuan as $data_satuan) {
            					$satuan_option[$data_satuan->id] = $data_satuan->nama;
            				}
            				// die_dump($satuan_option);

							$attrs_account_item_id['value'] = $data['item_id'];
							$attrs_account_id['value']      = $data['id'];
							$attrs_account_code['value']    = $kode;
							$attrs_account_name['value']    = $nama;
							$attrs_account_jumlah['value']  = $data['jumlah'];
							$attrs_account_harga['value']   = $data['harga'];

							$harga = $data['harga'];
							// die_dump($attrs_account_id);

							$item_cols = array(// style="width:156px;
								'account_code' 		=> form_input($attrs_account_item_id).form_input($attrs_account_id).form_input($attrs_is_delete).form_input($attrs_account_code).'<label class="control-label col-md-8" value="'.$kode.'" id="account_kode_{0}" name="account[{0}][kode]">'.$kode.'</label>',
								'btn_search'   		=> $btn_search_a,
								'account_name' 		=> form_input($attrs_account_name).'<label class="control-label" value="'.$nama.'" id="account_nama_{0}" name="account[{0}][nama]">'.$nama.'</label>',
								'account_dijual' 	=> '<div class="text-center"><input type="checkbox" id="account_dijual_{0}" name="account[{0}][dijual]" value="1"'.$check.'></div>',
								'account_jumlah' 	=> form_input($attrs_account_jumlah),
								'account_satuan' 	=> form_dropdown('account[{0}][satuan]',  $satuan_option, $data['item_satuan_id'], "id='account_satuan_{0}' class='form-control bs-select-satuan'"),
								'account_harga' 	=> form_input($attrs_account_harga).'<label class="control-label col-md-12" value="'.$harga.'" id="account_harga_{0}" name="account[{0}][harga]">Rp. '.number_format($harga).',-</label>',
								'account_sub_total' => form_input($attrs_account_sub_total).'<label class="control-label col-md-12 subtotal" id="account_sub_total_{0}" name="account[{0}][sub_total]"></label>',
								'action'       		=> $btn_del_db,
							);
						    $item_dummy =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
						    $dummy_rows[] = str_replace('{0}', "{$key}", $item_dummy );
						
						   $i++;

						}
						echo '<input type="hidden" id="count_obat" name="count_obat" value="'.$i.'">';



						$i=0;
						$dummy_rows_tindakan = array();
					    foreach ($data_paket_tindakan as $key => $data_tindakan) {
				    			

				    			$btn_search_b = '<div class="text-center"><button type="button" disabled title="" class="btn btn-sm btn-primary search-account"><i class="fa fa-search"></i></button></div>';
								$btn_del_db_b = '<div class="text-center"><button class="btn btn-sm red-intense del-this-plus-db" title="Delete Paket Item"><i class="fa fa-times"></i></button></div>';
						    	
						    	if($data_tindakan['paket_id'] != ''){

									$account_info = $this->tindakan_m->get_by(array('id' => $data_tindakan['tindakan_id']));
									$item = object_to_array($account_info);
									// $paket_item = $this->item_m->get($item['id']);

									// die(dump($item));
									// die(dump($this->db->last_query()));

									foreach ($account_info as $paket_info)
									{
										$nama = $paket_info->nama;
										$kode = $paket_info->kode;
										// $jumlah = $paket_info->jumlah;

									}

								}

							$attrs_account_id_tindakan['value']       =$data_tindakan['tindakan_id'];
							$attrs_account_id_paket_tindakan['value'] =$data_tindakan['id'];
							$attrs_account_kode['value']              =$kode;
							$attrs_account_nama['value']              =$nama;
							$attrs_account_jumlah_tindakan['value']   =$data_tindakan['jumlah'];
							$attrs_account_harga_tindakan['value']    =$data_tindakan['harga'];
							
							$harga                                    = $data_tindakan['harga'];
							// die_dump($harga);

							$item_cols_acc = array(// style="width:156px;
							'account_code' 		=> form_input($attrs_account_id_paket_tindakan).form_input($attrs_account_kode).form_input($attrs_account_id_tindakan).form_input($attrs_is_delete_tindakan).'<label class="control-label col-md-8 text-center" id="tindakan_kode_tindakan_{0}" name="tindakan[{0}][kode_tindakan]">'.$kode.'</label>',
							'btn_search'   		=> $btn_search_b,
							'account_name' 		=> form_input($attrs_account_nama).'<label class="control-label" id="tindakan_nama_tindakan_{0}" name="tindakan[{0}][nama_tindakan]">'.$nama.'</label>',
							'account_jumlah' 	=> form_input($attrs_account_jumlah_tindakan),
							'account_harga'	   	=> form_input($attrs_account_harga_tindakan).'<label class="control-label col-md-12" id="tindakan_harga_tindakan_{0}" name="tindakan[{0}][harga_tindakan]">Rp. '.number_format($harga).',-</label>',
							'account_sub_total' => form_input($attrs_account_sub_total_tindakan).'<label class="control-label subtotal_tindakan col-md-12" id="tindakan_sub_total_{0}" name="tindakan[{0}][sub_total_tindakan]"></label>',
							'action'       		=> $btn_del_db_b,
								
							);
						    $item_dummy_tindakan =  '<tr id="item_row_{0}" class="row_plus"><td>' . implode('</td><td>', $item_cols_acc) . '</td></tr>';

						    $dummy_rows_tindakan[] = str_replace('{0}', "{$key}", $item_dummy_tindakan );
						
						   $i++;	
						    
					    }

						echo '<input type="hidden" id="count_tindakan" name="count_tindakan" value="'.$i.'">';


						 
				    ?>
				    <div class="row">
	                    <div class="col-md-12">
	                        <div class="portlet">
	                            <div class="portlet-title">
	                            	<div class="caption">
										<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
										<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Obat & Alat Kesehatan", $this->session->userdata("language"))?></span>
	                            	</div>
	                            </div>
	                            <div class="portlet-body">
	                                <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
	                                <!-- <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span> -->
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_account">
	                                        <thead>
	                                            <tr role="row" class="heading">
	                                                <th colspan="2" width="20%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
	                                                <th width="15%"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Dijual", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Jumlah", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Sub Total", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Actions", $this->session->userdata('language'))?></div></th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                          			<?php foreach ($dummy_rows as $row):?>
											                       	<?=$row?>
											                    <?php endforeach;?>
	                                            <!-- <?//=$item_row?> -->
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
										<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
										<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan", $this->session->userdata("language"))?></span>
	                            	</div>
	                            </div>
	                            <div class="portlet-body">
	                                <!-- <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span> -->
	                                <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span>
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_account_tindakan">
	                                        <thead>
	                                            <tr role="row" class="heading">
	                                                <th colspan="2" width="20%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
	                                                <th width="15%"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Jumlah", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Sub Total", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                          	<?php foreach ($dummy_rows_tindakan as $row):?>
											                       	<?=$row?>
											                    <?php endforeach;?>
	                                            <!-- <?//=$item_row?> -->
	                                        </tbody>
	                                    </table>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
				</div>

				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-9"><?=translate('Total Obat & Alat Kesehatan', $this->session->userdata('language'))?> :</label>
							<div class="col-md-2">
								<div class="input-group">
									<span class="input-group-addon">
										&nbsp;Rp&nbsp;
									</span>
									<input class="form-control col-md-2 text-right" readonly id="total_bayar" name="total_bayar">
									<input class="form-control col-md-2 hidden"  id="total_bayar_hidden" name="total_bayar_hidden">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-9"><?=translate('Total Tindakan', $this->session->userdata('language'))?> :</label>
							<div class="col-md-2">
								<div class="input-group">
									<span class="input-group-addon">
											&nbsp;Rp&nbsp;
									</span>
									<input class="form-control col-md-2 text-right tot_tindakan" readonly id="total_tindakan" name="total_tindakan">
									<input class="form-control col-md-2 hidden"  id="total_tindakan_hidden" name="total_tindakan_hidden">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-9"><?=translate('Biaya Tambahan', $this->session->userdata('language'))?> :</label>
							<div class="col-md-2">
								<div class="input-group">
									<span class="input-group-addon">
											&nbsp;Rp&nbsp;
									</span>
									<input class="form-control col-md-2 text-right" required id="biaya_tambahan" name="biaya_tambahan" value="<?=$form_data['biaya_tambahan']?>"></input>
									<!-- <input class="form-control col-md-2 " id="biaya_tambahan_hidden" name="biaya_tambahan_hidden" value="<?=$form_data['biaya_tambahan']?>"></input> -->
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-9"><?=translate('Total Keseluruhan', $this->session->userdata('language'))?> :</label>
								<div class="col-md-2">
									<div class="input-group">
										<span class="input-group-addon">
												&nbsp;Rp&nbsp;
										</span>
										<input class="form-control col-md-2 text-right" readonly id="total_keseluruhan" name="total_keseluruhan" value="<?=number_format($form_data['harga_total']).',-'?>"></input>
										<input class="form-control col-md-2 hidden"  id="total_keseluruhan_hidden" name="total_keseluruhan_hidden" value="<?=$form_data['harga_total']?>"></input>
									</div>
								</div>
						</div>
					</div>
				</div>

				<?php
					$confirm_save       = translate('Apa anda yakin akan mengubah paket ini ?',$this->session->userdata('language'));
					$submit_text        = translate('Simpan', $this->session->userdata('language'));
					$reset_text         = translate('Reset', $this->session->userdata('language'));
					$back_text          = translate('Kembali', $this->session->userdata('language'));
				?>
				<div class="form-actions fluid">    
				    <div class="col-md-offset-1 col-md-9">
				        
				        <a class="btn default" href="javascript:history.go(-1)"><?=$back_text?></a>
				        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
				        <button type="submit" id="save" class="btn green-haze hidden" ><?=$submit_text?></button>
				        <a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=$submit_text?></a>
				        
				    </div>          
				</div>
			</div>
		</div>

	</div>			
</div>									
</div>
							
<?=form_close();?>
<?php $this->load->view('master/paket/cari_alat_obat.php'); ?> 
<?php $this->load->view('master/paket/cari_titipan.php'); ?> 
