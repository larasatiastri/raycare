<?php

    //////////////////////////////////////////////////////////////////////////////////////

    $this->item_satuan_m->set_columns(array('id','nama'));
    $categories = $this->item_satuan_m->get();
    // die_dump($categories);
    $categories_satuan = array(
    
    '' => translate('Pilih Satuan', $this->session->userdata('language')) . '..',
    );

    $categories_supplier = array(
    
    '' => translate('Pilih Supplier', $this->session->userdata('language')) . '..',
    );


    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_add_permintaan_po", 
		"name"			=> "form_add_permintaan_po", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."pembelian/permintaan_po/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');


?>	
<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
				<i class="icon-plus font-blue-sharp"></i>
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Permintaan Barang & Jasa", $this->session->userdata("language"))?></span>
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
							<div class="portlet box blue-sharp">
								<div class="portlet-title" style="margin-bottom: 0px !important;">
									<div class="caption">
										<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<div class="col-md-12">
													<div class="input-group date">
														<input type="text" class="form-control" id="tanggal" name="tanggal" value="<?=date('d M Y')?>" required readonly >
														<span class="input-group-btn">
															<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
														</span>
													</div>
													
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<div class="col-md-12">
														<?php
															$user = $this->user_m->get($this->session->userdata('user_id'));
															// die_dump($this->db->last_query());
														?>
														<input class="form-control" id="nomer_{0}" readonly name="nama_ref_user" value="<?=$user->nama?>" placeholder="<?=translate('Diminta Oleh', $this->session->userdata('language'))?>" required>
														<input class="form-control input-sm hidden" id="nomer_{0}" name="id_ref_user" value="<?=$user->id?>" placeholder="ID Referensi User">
														<input class="form-control input-sm hidden" id="nomer_{0}" name="user_level_id" value="<?=$user->user_level_id?>" placeholder="Tipe User">
														<input class="form-control input-sm hidden" id="nomer_{0}" name="cabang_id" value="<?=$user->cabang_id?>" placeholder="Kasir Titip Uang ID">
													<div class="input-group hidden">
														<span class="input-group-btn">
															<a class="btn btn-primary pilih-user" title="<?=translate('Diminta Oleh', $this->session->userdata('language'))?>">
																<i class="fa fa-search"></i>
															</a>
														</span>
														
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
						              	<div class="col-md-12">
			              					<div class="btn-group btn-group-justified">
												<a id="item_terdaftar" class="btn btn-primary">
													<?=translate("Terdaftar", $this->session->userdata("language"))?>
												</a>
												<a id="item_tak_terdaftar" class="btn btn-default">
													<?=translate("Tidak Terdaftar", $this->session->userdata("language"))?>
												</a>
											</div>
						              	</div>
						              	<div class="col-md-12">
							                  <input class="hidden"  id="tipe_item" name="tipe_item" value="1">
						              	</div>
					              	</div>
					              	<div class="form-group">
										<!-- <label class="control-label col-md-3"><?=translate("Subjek :", $this->session->userdata("language"))?></label>		 -->
										<div class="col-md-12">
											<?php
												$subjek = array(
													"name"        => "subjek",
													"id"          => "subjek",
													"autofocus"   => true,
													"class"       => "form-control", 
													"placeholder" => translate("Subjek", $this->session->userdata("language")), 
													"required"    => "required"
												);
												echo form_input($subjek);
											?>
										</div>
									</div>
									<div class="form-group">
										<!-- <label class="control-label col-md-3"><?=translate("Keterangan :", $this->session->userdata("language"))?></label> -->
										<div class="col-md-12">
											
											<?php
												$keterangan = array(
													"name"			=> "keterangan",
													"id"			=> "keterangan",
													"class"			=> "form-control",
													"rows"			=> 10, 
													"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
												);
												echo form_textarea($keterangan);
											?>
											<!-- <textarea rows="6" class="form-control" id="description" name="description"></textarea> -->
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-9" id="section_terdaftar">
							<div class="portlet box blue-sharp">
								<div class="portlet-title" style="margin-bottom: 0px !important;">
									<div class="caption">
										<span class="caption-subject"><?=translate("Pilih Item", $this->session->userdata("language"))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="form-body">
									<?php
								    //	$btn_search   = '<div class="text-center"><button title="Search Account" class="btn btn-success search-account"><i class="fa fa-search"></i></button></div>';
								    	$btn_search 		= '<span class="input-group-btn"><button type="button" class="btn btn-primary search-account"><i class="fa fa-search"></i></button></span>';
								    	$btn_search_supplier = '<span class="input-group-btn"><button type="button" class="btn btn-primary search-supplier" disabled><i class="fa fa-search"></i></button></span>';
								    	$btn_plus   		= '<div class="text-center"><button title="Add Row" class="btn btn-success add_row"><i class="fa fa-plus"></i></button></div>';
										$btn_del           	= '<div class="text-center"><button class="btn red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
										$btn_del_plus       = '<div class="text-center"><button class="btn red del-this-plus" title="Delete"><i class="fa fa-times"></i></button></div>';
										$btn_unggah_gbr		= '<div class="text-center"><button type="button" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'pembelian/permintaan_po/unggah_gambar/item_row_plus_{0}/{0}" class="btn blue-chambray unggah-gambar name="tindakan[{0}][gambar]" title="Unggah Gambar"><i class="fa fa-image"></i></button></div>'; 
										$btn_unggah_file	= '<div class="text-center"><button type="button" data-toggle="modal" data-target="#popup_modal_file" href="'.base_url().'pembelian/permintaan_po/unggah_file/item_row_plus_{0}/{0}" class="btn gray-default unggah-file" name="tindakan[{0}][file]" title="Unggah File"><i class="fa fa-file"></i></button></div>'; 
				// '.$row['id'].'
				// <a title="'.translate('Unggah Gambar', $this->session->userdata('language')).'" href="'.base_url().'pembelian/permintaan_po/unggah_gambar/" data-toggle="modal" data-target="#popup_modal" class="btn green-haze"><i class="fa fa-image"></i></a>			
										$attrs_account_id = array(
										    'id'          => 'account_id_{0}',
										    'name'        => 'account[{0}][account_id]',
										    'class'       => 'form-control hidden',
										    'required'	  => 'required'
										);

										$attrs_item_sub_kat_id = array(
										    'id'          => 'item_sub_kat_id_{0}',
										    'name'        => 'account[{0}][item_sub_kat_id]',
										    'class'       => 'form-control hidden',
										    'required'	  => 'required'
										);

										$attrs_account_type = array(
										    'id'          => 'account_type_{0}',
										    'name'        => 'account[{0}][account_type]',
										    'class'       => 'form-control hidden',
										);

										$attrs_account_code = array(
										    'id'          => 'account_kode_{0}',
										    'name'        => 'account[{0}][kode]',
										    'class'       => 'form-control',
										    'width'		  => '50%',
										);


										$attrs_account_name = array(
										    'id'          => 'account_name_{0}',
										    'name'        => 'account[{0}][nama]',
										    'class'       => 'form-control',
										    'readonly'    => 'readonly',
										    // 'style'       => 'width:180px;',
										);

										$attrs_account_supp_id = array(
										    'id'          => 'account_supp_id_{0}',
										    'name'        => 'account[{0}][supp_id]',
										    'class'       => 'form-control hidden',
										);
										$attrs_account_supp_tipe = array(
										    'id'          => 'account_supp_tipe_{0}',
										    'name'        => 'account[{0}][supp_tipe]',
										    'class'       => 'form-control hidden',
										);

										$attrs_account_supp_name = array(
										    'id'          => 'account_supp_name_{0}',
										    'name'        => 'account[{0}][supp_nama]',
										    'class'       => 'form-control hidden',
										    'readonly'    => 'readonly',
										    // 'style'       => 'width:180px;',
										);

										$attrs_account_jumlah = array(
										    'id'          => 'account_jumlah_{0}',
										    'name'        => 'account[{0}][jumlah]',
										    'class'       => 'form-control text-center',
										    'value'		  => 1,
										    'min'		  => 0,
										    'type'		  => 'number'
										    // 'readonly'    => 'readonly',
										    // 'style'       => 'width:180px;',
										);

										$attrs_account_nama_box = array(
										    'id'          => 'account_nama_box_{0}',
										    'name'        => 'account[{0}][nama_box]',
										    'class'       => 'form-control',
										    'readonly'    => 'readonly',
										    // 'style'       => 'width:180px;',
										);

										$attrs_account_id_box = array(
										    'id'          => 'account_id_box_{0}',
										    'name'        => 'account[{0}][id_box]',
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


										$attrs_account_satuan = array(
										    'id'          => 'account_satuan_{0}',
										    'name'        => 'account[{0}][satuan]',
										    'class'       => 'form-dropdown',
										    'value'		  => '',
										    // 'readonly'    => 'readonly',
										    // 'style'       => 'width:180px;',
										);

										$attrs_account_sub_total = array(
										    'id'          => 'account_sub_total_{0}',
										    'name'        => 'account[{0}][sub_total]',
										    'class'       => 'form-control text-center hidden',
										    'readonly'    => 'readonly',
										    // 'value'       => 0,
										    // 'style'       => 'width:180px;',
										);

										$attrs_account_harga = array(
										    'id'          => 'account_harga_{0}',
										    'name'        => 'account[{0}][harga]',
										    'class'       => 'form-control',
										    
										);


										// item row column
										$item_cols = array(// style="width:156px;
											'account_code' 	 	=> '<div class="input-group" style="width:200px;">'.form_input($attrs_account_code).form_input($attrs_account_id).form_input($attrs_item_sub_kat_id).$btn_search.'</div>',
											'account_name' 	 	=> '<div style="width:300px;">'.form_input($attrs_account_name).form_input($attrs_account_id_box).'</div>',
											'account_jumlah' 	=> '<div style="width:80px;">'.form_input($attrs_account_jumlah).form_input($attrs_account_jumlah_box).'</div>',
											'account_satuan' 	=> '<div style="width:150px;">'.form_dropdown('account[{0}][satuan]',  $categories_satuan, "", "id='account_satuan_{0}' class='form-control bs-select-satuan' disabled").'</div>',
											'account_harga' 	=> '<div style="width:150px;">'.form_input($attrs_account_harga).form_input($attrs_account_supp_name).form_input($attrs_account_supp_tipe).form_input($attrs_account_supp_id).'</div>',
											//'account_supplier' 	=> '<div class="input-group">'.form_input($attrs_account_supp_name).form_input($attrs_account_supp_tipe).form_input($attrs_account_supp_id).$btn_search_supplier.'</div>',
											'action'       		=> $btn_del.'<div id="account_simpan_item_box_{0}" name="account[{0}][simpan_item_box]" ></div>',
										);

										/////////////////////////////////////////
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
										);

										$attrs_account_nama = array(
										    'id'          => 'account_nama_tindakan_{0}',
										    'name'        => 'tindakan[{0}][nama_tindakan]',
										    'class'       => 'form-control',
										    // 'style'       => 'width:180px;',
										);


										$attrs_account_jumlah_tindakan = array(
										    'id'          => 'account_jumlah_tindakan_{0}',
										    'name'        => 'tindakan[{0}][jumlah_tindakan]',
										    'class'       => 'form-control text-center',
										    'value'       => 1,
										    'min'		  => 0,
										    'type'		  => 'number'

										    // 'style'       => 'width:180px;',
										);

										$attrs_account_satuan_tindakan = array(
										    'id'          => 'account_satuan_tindakan_{0}',
										    'name'        => 'tindakan[{0}][satuan_tindakan]',
										    'class'       => 'form-control',


										    // 'style'       => 'width:180px;',
										);

										$attrs_account_harga_ref = array(
										    'id'          => 'account_harga_ref_{0}',
										    'name'        => 'tindakan[{0}][harga_ref]',
										    'class'       => 'form-control',
										);

										$attrs_account_supplier_name = array(
										    'id'          => 'account_supplier_name_{0}',
										    'name'        => 'tindakan[{0}][supplier_name]',
										    'class'       => 'form-control',
										);

										$attrs_account_upload_file = array(
										    'id'          => 'account_upload_file_{0}',
										    'name'        => 'tindakan[{0}][upload_file]',
										    'class'       => 'form-control text-center hidden',
										    'readonly'    => 'readonly',
										    // 'value'       => 0,
										    // 'style'       => 'width:180px;',
										);

										$attrs_account_url_file = array(
										    'id'          => 'account_url_file_{0}',
										    'name'        => 'tindakan[{0}][url_file]',
										    'class'       => 'form-control text-center hidden',
										    'readonly'    => 'readonly',
										    // 'value'       => 0,
										    // 'style'       => 'width:180px;',
										);

										$attrs_account_url_gambar = array(
										    'id'          => 'account_url_gambar_{0}',
										    'name'        => 'tindakan[{0}][url_gambar]',
										    'class'       => 'form-control text-center hidden',
										    'readonly'    => 'readonly',
										    // 'value'       => 0,
										    // 'style'       => 'width:180px;',
										);
										
										$jenis_option = array(
											'1'	=> 'Barang',
											'2'	=> 'Jasa',
										);
										$item_cols_acc = array(// style="width:156px;
											'account_name'          => '<div style="width:300px;">'.form_input($attrs_account_nama).'<div class="hidden" id="url_pdf_{0}">pdf goes here</div></div>',
											'account_jumlah'        => '<div style="width:80px;">'.form_input($attrs_account_jumlah_tindakan).'<div class="hidden" id="url_gambar_{0}">pdf goes here</div></div>',
											'account_satuan'        => '<div style="width:150px;">'.form_input($attrs_account_satuan_tindakan).form_input($attrs_account_upload_file).'</div>',
											'account_jenis' => '<div style="width=100px;">'.form_dropdown('tindakan[{0}][jenis]',$jenis_option,'1','id="account_jenis_{0}" class="form-control" style="width:100px;"').'</div>',
											'account_harga_ref'     => '<div style="width:150px;">'.form_input($attrs_account_harga_ref).'</div>',
											'account_supplier_name' => '<div style="width:150px;">'.form_input($attrs_account_supplier_name).'</div>',
											
											
											'action'       		=> '<div class="inline-button-table text-center"> 
				                                                    	<input class="form-control text-center hidden" id="account_url_file_{0}" name="tindakan[{0}][url_file]" ></input>
				                                                    	<input class="form-control text-center hidden" id="account_url_gambar_{0}" name="tindakan[{0}][url_gambar]" ></input>
				                                                    	<input class="form-control text-center hidden" id="account_upload_file_{0}" name="tindakan[{0}][upload_file]" ></input>
					                                                    <button type="button" data-toggle="modal" data-target="#popup_modal_file" href="'.base_url().'pembelian/permintaan_po/unggah_file/item_row_plus_{0}/{0}" class="btn gray-default unggah-file" name="tindakan[{0}][file]" title="Unggah File"><i class="fa fa-file"></i></button>
					                                                    <button type="button" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'pembelian/permintaan_po/unggah_gambar/item_row_plus_{0}/{0}" class="btn blue-chambray unggah-gambar" name="tindakan[{0}][gambar]" title="Unggah Gambar"><i class="fa fa-image"></i></button>
																		<button class="btn red del-this-plus" title="Delete"><i class="fa fa-times"></i></button>
				                                                    </div>',

										);

										// gabungkan $item_cols jadi string table row
										$item_row_template =  '<tr id="item_row_{0}" class="row_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
										$item_row_template_acc =  '<tr id="item_row_plus_{0}" class="row_plus"><td>' . implode('</td><td>', $item_cols_acc) . '</td></tr>';
										
								    ?>
								    
								    	<div class="table-scrollable">
								    		<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
		                                <!-- <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span> -->
		                                    <table class="table table-striped table-hover" id="table_add_account">
		                                        <thead>
		                                            <tr>
		                                                <th style="width:200px;"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
		                                                <th style="width:80px;"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
		                                                <th style="width:150px;"><div class="text-center"><?=translate("Jumlah", $this->session->userdata('language'))?></div></th>
		                                                <th style="width:150px;"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
		                                                <th style="width:200px;"><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
		                                                <th style="width:200px;" class="hidden"><div class="text-center"><?=translate("Keterangan", $this->session->userdata('language'))?></div></th>
		                                                <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
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
						<!-- end of pilih item -->

						<div class="col-md-9 hidden" id="section_tidak_terdaftar">
							<div class="portlet box blue-sharp">
								<div class="portlet-title" style="margin-bottom: 0px !important;">
									<div class="caption">
										<span class="caption-subject"><?=translate("Input Item", $this->session->userdata("language"))?></span>
									</div>
									<div class="actions" style="float: right;">
					                    <a data-toggle="modal" id="add_biaya" class="btn btn-circle btn-primary">
					                        <i class="fa fa-plus"></i>
					                    </a>
					                </div>
								</div>
								<div class="portlet-body">
									<div class="form-body">
										<div class="table-scrollable">
		                                	<!-- <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span> -->
		                                	<span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span>
		                                    <table class="table table-striped table-hover" id="table_add_account_titipan">
		                                        <thead>
		                                            <tr>
		                                                <th width="25%"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
		                                                <th width="5%"><div class="text-center"><?=translate("Jumlah", $this->session->userdata('language'))?></div></th>
		                                                <th width="10%"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
		                                                <th width="10%"><div class="text-center"><?=translate("Jenis", $this->session->userdata('language'))?></div></th>
		                                                <th width="7%"><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
		                                                <th width="10%"><div class="text-center"><?=translate("Supplier", $this->session->userdata('language'))?></div></th>
		                                                <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
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
						$confirm_save       = translate('Apakah anda Yakin Akan Tambah Permintaan Barang Ini ?',$this->session->userdata('language'));
						$submit_text        = translate('Simpan', $this->session->userdata('language'));
						$reset_text         = translate('Reset', $this->session->userdata('language'));
						$back_text          = translate('Kembali', $this->session->userdata('language'));
					?>
					<div class="form-actions right">    
				        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
				        	<i class="fa fa-chevron-left"></i>
				        	<?=$back_text?>
				        </a>
				        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
				        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
				        <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal">
				        	<i class="fa fa-check"></i>
				        	<?=$submit_text?>
				        </a>
					</div>
				</div>
			</div>

</div>

<?=form_close();?>
<?php $this->load->view('pembelian/permintaan_po/pilih_item.php'); ?> 
<?php $this->load->view('pembelian/permintaan_po/pilih_user.php'); ?>
<?php $this->load->view('pembelian/permintaan_po/pilih_supplier.php'); ?>

<div class="modal fade bs-modal-sm" id="popup_modal" role="basic" aria-hidden="true" style="">
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

<div class="modal fade bs-modal-sm" id="popup_modal_file" role="basic" aria-hidden="true" style="">
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