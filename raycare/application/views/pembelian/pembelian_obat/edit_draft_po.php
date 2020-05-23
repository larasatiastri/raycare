<?php
			$form_attr = array(
			    "id"            => "form_add_pembelian", 
			    "name"          => "form_add_pembelian", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "edit"
		    );

		    $edit = 'edit';

		    echo form_open(base_url()."pembelian/pembelian_obat/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

			$btn_search        = '<div class="text-center"><a class="btn btn-xs btn-primary search-item" data-original-title="Search Item" data-status-row="item_row_add" style="height:20px;" title="'.translate('Pilih Item', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a></div>';
			$btn_search_jumlah        = '<div class="text-center"><a class="btn btn-xs btn-primary search-jumlah" data-row="{0}" data-original-title="Jumlah" style="height:20px;" title="'.translate('Search Permintaan', $this->session->userdata('language')).'"><i class="fa fa-chain"></i></a></div>';
			// $btn_search_tabel       = '<div class="text-center"><a class="btn btn-xs btn-primary hidden search-tabel" data-original-title="Jumlah" style="height:20px;" title="'.translate('Search Permintaan', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a></div>';
			// $btn_search_result = '<div class="text-center"><button title="" class="btn btn-sm btn-success search-item-result" data-original-title="Search Item"><i class="fa fa-search"></i></button></div>';
			$btn_del           = '<div class="text-center"><button class="btn btn-sm red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

			$attrs_action_items = array (
			    'id'       => 'is_action{0}',
			    'name'     => 'items[{0}][action]',
			    'class'    => 'form-control input-sm hidden',
			    // 'style'    => 'width:80px;',
			    'readonly' => 'readonly',
			    'value' => 'add',
			    'type'  => '',
			);

			$attrs_is_delete_items = array (
			    'id'       => 'is_deleted{0}',
			    'name'     => 'items[{0}][is_deleted]',
			    'class'    => 'form-control input-sm hidden',
			    // 'style'    => 'width:80px;',
			    'readonly' => 'readonly',
			    'type'  => '',
			    // 'value' => 'BLSG01',
			);

			$attrs_draft_id= array (
			    'id'          => 'items_draft_id{0}',
			    'name'        => 'items[{0}][id]',
			    'class'       => 'form-control input-sm hidden',
			    'style'       => 'width:130px;',
			    'readonly'    => 'readonly',
			    'type'  => '',
			    //'type'        => 'hidden',
			);

			$attrs_detail_id= array (
			    'id'          => 'items_detail_id{0}',
			    'name'        => 'items[{0}][id_detail]',
			    'class'       => 'form-control input-sm hidden',
			    'style'       => 'width:130px;',
			    'readonly'    => 'readonly',
			    'type'  => '',
			    //'type'        => 'hidden',
			);

			$attrs_item_id  = array ( 
			    'id'       => 'items_item_id_{0}',
			    'name'     => 'items[{0}][item_id]',
			    'class'    => 'form-control hidden',
			    // 'hidden'   => 'hidden',
			    // 'style'    => 'width:80px;',
			    'readonly' => 'readonly',
			    // 'value' => 'BLSG01',
			);

			$attrs_item_kode = array (
			    'id'          => 'items_kode_{0}',
			    'name'        => 'items[{0}][item_kode]',
			    'class'       => 'form-control',
			    'readonly'    => 'readonly',
			);

			$attrs_item_nama = array(
			    'id'          => 'items_nama_{0}',
			    'name'        => 'items[{0}][item_nama]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			);

			$attrs_item_syarat = array(
			    'id'          => 'items_syarat_{0}',
			    'name'        => 'items[{0}][item_syarat]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			);

			$attrs_item_harga = array(
			    'id'          => 'items_harga_{0}',
			    'name'        => 'items[{0}][item_harga]',
			    'class'       => 'form-control text-right',
			    'value'		  => 0,
			);

			$attrs_item_harga_lama = array(
			    'id'          => 'items_harga_lama_{0}',
			    'name'        => 'items[{0}][item_harga_lama]',
			    'class'       => 'form-control text-right hidden',
			    'value'		  => 0,
			);

			$attrs_item_diskon = array(
			    'id'          => 'items_diskon_{0}',
			    'name'        => 'items[{0}][item_diskon]',
			    'class'       => 'form-control text-right',
			    'type'		  => 'number',
			    'value'		  => 0,
			    'min'		  => 0
			);

			$attrs_stok = array(
			    'id'    => 'items_stok_{0}',
			    'name'  => 'items[{0}][stok]', 
			    'type'  => 'number',
			    'min'   => 0,
			    'class' => 'form-control text-right hidden',
			    'style' => 'width:80px;',
			    'value' => 1,
			);

			$attrs_jumlah = array(
			    'id'    => 'items_jumlah_{0}',
			    'name'  => 'items[{0}][jumlah]',
			    'min'   => 0,
			    'class' => 'form-control text-right',
			    'type'	=> 'number',
			    'value'	=> '0'
			    // 'style' => 'width:80px;'
			);

			$attrs_jumlah_min = array(
			    'id'    => 'items_jumlah_min_{0}',
			    'name'  => 'items[{0}][jumlah_min]',
			    'min'   => 0,
			    'class' => 'form-control text-right hidden',
			    'type'	=> 'number',
			    'value'	=> '0'
			    // 'style' => 'width:80px;'
			);

			$attrs_satuan_nama = array(
			    'id'    => 'items_satuan_nama_{0}',
			    'name'  => 'items[{0}][satuan_nama]',
			    'min'   => 0,
			    'class' => 'form-control hidden'
			    // 'style' => 'width:80px;'
			);

			$attrs_satuan_id = array(
			    'id'    => 'items_satuan_id_{0}',
			    'name'  => 'items[{0}][satuan_id]',
			    'min'   => 0,
			    'class' => 'form-control hidden'
			    // 'style' => 'width:80px;'
			);

			$attrs_item_total = array(
			    'id'          => 'items_total_{0}',
			    'name'        => 'items[{0}][item_sub_total]',
			    'class'       => 'form-control hidden sub_total',
			    'readonly'    => 'readonly',
			    'value'		  => 0
			);

			$attrs_tanggal_kirim_item = array(
			    'id'          => 'items_tanggal_kirim{0}',
			    'name'        => 'items[{0}][item_tanggal_kirim]',
			    'class'       => 'form-control date-picker',
			    'type'		  => 'text',
			);

			$satuan_option = array(
				'' => 'Pilih..'
			);

			$item_cols = array(// style="width:156px;
				'item_kode'          => '<label class="control-label hidden" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;"></label><div class="input-group">'.form_input($attrs_item_id).form_input($attrs_item_kode).form_input($attrs_action_items).form_input($attrs_is_delete_items).form_input($attrs_draft_id).form_input($attrs_detail_id).'<span class="input-group-btn">'.$btn_search.'</span></div>',
				'item_name'          => '<label class="control-label" name="items[{0}][item_nama]"></label>'.form_input($attrs_item_nama).'<div id="tabel_simpan_data_{0}" class="hidden"></div>',
				'item_syarat'        => '<div class="text-center"><label class="control-label" id="item_syarat_{0}" name="items[{0}][item_syarat]"></label></div>'.form_input($attrs_item_syarat),
				'item_satuan'        => form_dropdown('items[{0}][satuan]', $satuan_option, "", "id=\"items_satuan_{0}\" data-row=\"{0}\" class=\"form-control satuan\"").form_input($attrs_satuan_nama).form_input($attrs_satuan_id),
				'item_stok'          => '<div class="text-center"><label class="control-label" name="items[{0}][stok]"></label></div>'.form_input($attrs_stok),
				'item_harga'         => '<div class="text-right"><label class="control-label hidden" name="items[{0}][item_harga]" id="items_hargaEl_{0}"></label></div><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span>'.form_input($attrs_item_harga).form_input($attrs_item_harga_lama).'</div>',
				'item_diskon'        => form_input($attrs_item_diskon),
				'item_jumlah'        => '<div class="input-group">'.form_input($attrs_jumlah).form_input($attrs_jumlah_min).'<span class="input-group-btn">'.$btn_search_jumlah.'</span></div>',
				'item_tanggal_kirim' => form_input($attrs_tanggal_kirim_item),
				'item_total'         => '<div class="text-right"><label class="control-label" name="items[{0}][item_sub_total]"></label></div>'.form_input($attrs_item_total),
				'action'             => $btn_del,
			);

			$item_row_template =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

			$records = $form_data_item->result_array();

			// die_dump($records);

			$btn_search = '<div class="text-center"><button type="button" disabled title="'.translate('Search Item', $this->session->userdata('language')).'" class="btn btn-sm btn-primary search-item"><i class="fa fa-search"></i></button></div>';
			$btn_search_jumlah = '<div class="text-center"><button type="button" data-row="{0}" title="'.translate('Search Item', $this->session->userdata('language')).'" class="btn btn-sm btn-primary search-jumlah"><i class="fa fa-chain"></i></button></div>';
			$btn_del    = '<div class="text-center"><button type="button" class="btn btn-sm red-intense del-item-db" title="'.translate('Delete Item', $this->session->userdata('language')).'"><i class="fa fa-times"></i></button></div>';
			$i =0;
			$stok = 0;

			if($records){
				foreach ($records as $key=>$data) {
				    // die_dump($data);
					$get_data_item = $this->supplier_item_m->get_by(array('supplier_id' => $form_data[0]['id'], 'item_id' => $data['id'], 'item_satuan_id' => $data['id_satuan']));
					// die_dump($get_data_item);
				    $harga = $this->supplier_harga_item_m->get_harga_edit($get_data_item[0]->id)->result_array();
				    $sub_total = ($harga[0]['harga']-($harga[0]['harga']*$data['diskon']/100))*$data['jumlah_pesan'];

				    $jumlah_item_penjualan = $this->penjualan_detail_m->get_by(array('item_id' => $data['id'], 'item_satuan_id' => $data['id_satuan'], 'jumlah_konversi !=' => 0));
				    $item_penjualan = object_to_array($jumlah_item_penjualan);
				    // die_dump($item_penjualan);
				    $stok_penjualan = 0;
				    if(!empty($item_penjualan)){
				    	foreach ($item_penjualan as $jumlah_penjualan) {
					    	$stok_penjualan =	$stok_penjualan + $jumlah_penjualan['jumlah_konversi'];
					    }
				    }
				    

				    $jumlah_item_pembelian = $this->pembelian_detail_m->get_by(array('item_id' => $data['id'], 'item_satuan_id' => $data['id_satuan'], 'jumlah_belum_diterima !=' => 0));
				    $item_pembelian = object_to_array($jumlah_item_pembelian);

				    $stok_pembelian = 0;
				    if(!empty($item_pembelian)){
					    foreach ($item_pembelian as $jumlah_pembelian) {
					    	$stok_pembelian =	$stok_pembelian + $jumlah_pembelian['jumlah_belum_diterima'];
					    }
					}

				    $jumlah_item = $this->inventory_m->get_data_stok($data['id'], $data['id_satuan']);
				    // die_dump($this->db->last_query());
				    $stok = $jumlah_item[0]['stock'] - $stok_penjualan + $stok_pembelian;

					$attrs_action_items['value']    = $edit;
					$attrs_item_id['value']         = $data['id'];
					$attrs_draft_id['value']        = $data['id_draf'];
					$attrs_detail_id['value']       = $data['id_detail'];
					$attrs_item_kode['value']       = $data['kode'];
					$attrs_item_nama['value']       = $data['nama'];
					$attrs_item_syarat['value']     = $data['min_order'].'/'.$data['max_order'];
					$attrs_jumlah['value']          = $data['jumlah_pesan'];
					$attrs_item_diskon['value']     = $data['diskon'];
					$attrs_item_harga['value']      = $harga[0]['harga'];
					$attrs_item_harga_lama['value'] = $harga[0]['harga'];
					$attrs_stok['value']            = $stok;
					$attrs_satuan_id['value']     = $data['id_satuan'];
					$attrs_item_total['value']      = $sub_total;
				    // die_dump($data['id_satuan']);
				$satuan_option = array(
					'' => $data['satuan']
				);
				    
				$item_cols = array(// style="width:156px;
					'item_kode'          => '<label class="control-label hidden" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;">'.$data['kode'].'</label><div class="input-group">'.form_input($attrs_item_id).form_input($attrs_item_kode).form_input($attrs_action_items).form_input($attrs_is_delete_items).form_input($attrs_draft_id).form_input($attrs_detail_id).'<span class="input-group-btn">'.$btn_search.'</span></div>',
					'item_name'          => '<label class="control-label" name="items[{0}][item_nama]">'.$attrs_item_nama['value'] = $data['nama'].'</label>'.form_input($attrs_item_nama).'<div id="tabel_simpan_data_{0}" class=""></div>',
					'item_syarat'        => '<div class="text-center"><label class="control-label" name="items[{0}][item_syarat]">'.$data['min_order'].'/'.$data['max_order'].'</label></div>'.form_input($attrs_item_syarat),
					'item_satuan'        => form_dropdown('items[{0}][satuan]', $satuan_option, "", "id=\"items_satuan_{0}\" class=\"form-control\" disabled").form_input($attrs_satuan_nama).form_input($attrs_satuan_id),
					'item_stok'          => '<div class="text-center"><label class="control-label" name="items[{0}][stok]">'.$stok.'</label></div>'.form_input($attrs_stok),
					'item_harga'         => '<div class="text-right"><label class="control-label hidden" name="items[{0}][item_harga]" id="items_hargaEl_{0}"></label></div><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span>'.form_input($attrs_item_harga).form_input($attrs_item_harga_lama).'</div>',
					'item_diskon'        => form_input($attrs_item_diskon),
					'item_jumlah'        => '<div class="input-group">'.form_input($attrs_jumlah).form_input($attrs_jumlah_min).'<span class="input-group-btn"><button type="button" data-row="{0}" data-id="'.$data['id'].'" title="'.translate('Search Item', $this->session->userdata('language')).'" class="btn btn-sm btn-primary search-jumlah" data-satuan="'.$data['id_satuan'].'"><i class="fa fa-chain" ></i></button></span></div>',
					'item_tanggal_kirim' => form_input($attrs_tanggal_kirim_item),
					'item_total'         => '<div class="text-right"><label class="control-label" name="items[{0}][item_sub_total]">Rp. '.number_format($sub_total,0,'','.').',-</label></div>'.form_input($attrs_item_total),
					'action'             => $btn_del,
				);

				    $items =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
				    $items_rows[] = str_replace('{0}', "{$key}", $items );

				$i++;
				}
		}
			echo '<input type="hidden" id="counter" name="counter" value="'.$i.'">';
			// die_dump($form_data);

		if($form_data[0]['tipe_supplier'] == 1)
		{
			$tipe_dlm = 'checked="checked"';
			$tipe_luar = '';
		}
		else
		{
			$tipe_dlm = '';
			$tipe_luar = 'checked="checked"';
		}

		if($form_data[0]['tipe_customer'] == 1)
		{
			$tipe_int = 'checked="checked"';
			$tipe_ext = '';
			$data_penerima = $this->draft_po_m->get_data_penerima_cabang()->result_array();
		}
		else
		{
			$tipe_int = '';
			$tipe_ext = 'checked="checked"';
			$data_penerima = $this->draft_po_m->get_data_penerima_customer()->result_array();

		}
	?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption font-blue-sharp bold uppercase"><?=translate("Pembelian", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<?php $msg = translate("Apakah anda yakin akan membuat PO ini?",$this->session->userdata("language"));
				$msg_delete = translate("Apakah anda yakin akan menghapus Draft PO ini?",$this->session->userdata("language"));
			  $msg_draft= translate("Apakah and yakin akan menyimpan PO ini ke Draft?", $this->session->userdata("language"));?>
			<a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-sm btn-primary btn-circle" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-shopping-cart"></i> <?=translate("Buat PO", $this->session->userdata("language"))?></a>
			<input type="hidden" id="save_draft" name="save_draft">
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Buat PO", $this->session->userdata("language"))?></button>
	        <a id="confirm_save_draft" class="btn btn-sm btn-primary btn-circle" href="#" data-confirm="<?=$msg_draft?>" data-toggle="modal"><i class="fa fa-file-text-o"></i> <?=translate("Simpan ke Draft", $this->session->userdata("language"))?></a>
			<a id="confirm_delete" class="btn btn-sm btn-danger btn-circle" href="#" data-confirm="<?=$msg_delete?>" data-toggle="modal"><i class="fa fa-times"></i> <?=translate("Hapus Draft PO", $this->session->userdata("language"))?></a>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="row">
				<div class="col-md-3">
					<div class="portlet">
						<div class="portlet-body form">
						<div class="alert alert-danger display-hide">
							        <button class="close" data-close="alert"></button>
							        <?=$form_alert_danger?>
							    </div>
							    <div class="alert alert-success display-hide">
							        <button class="close" data-close="alert"></button>
							        <?=$form_alert_success?>
							    </div>
						<input type="hidden" id="pk_value" name="pk_value" value="<?=$pk_value?>">
							<div class="tabbable-custom nav-justified">
								<ul class="nav nav-tabs nav-justified">
									<li class="active">
										<a href="#tab_supplier" data-toggle="tab">
										Supplier </a>
									</li>
									<li>
										<a href="#tab_penerima" data-toggle="tab">
										Penerima </a>
									</li>
									<li>
										<a href="#tab_info_pembelian" data-toggle="tab">
										Info Pembelian </a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab_supplier">
										<div class="form-body">
											<div class="form-group">
												<label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<div class="radio-list">
														<label class="radio-inline">
														<input type="radio" name="tipe_supplier" id="tipe_supplier_1" value="1" <?=$tipe_dlm?>> <?=translate("Dalam Negeri", $this->session->userdata("language"))?> </label>
														<label class="radio-inline">
														<input type="radio" name="tipe_supplier" id="tipe_supplier_2" value="2" <?=$tipe_luar?>> <?=translate("Luar Negeri", $this->session->userdata("language"))?> </label>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<div class="input-group">
														<?php
															$nama_supplier = array(
																"id"        => "nama_supplier",
																"name"      => "nama_supplier",
																"autofocus" => true,
																"class"     => "form-control", 
																"readonly"  => "readonly",
																"value"		=> $form_data[0]['nama']
															);

															$id_supplier = array(
																"id"          => "id_supplier",
																"name"        => "id_supplier",
																"autofocus"   => true,
																"class"       => "form-control hidden",
																"placeholder" => translate("Pasien", $this->session->userdata("language")),
																"value"		  => $form_data[0]['id']
															);
															echo form_input($nama_supplier).form_input($id_supplier);
													?>
														<span class="input-group-btn">
															<a class="btn btn-primary pilih-supplier" title="<?=translate('Pilih Supplier', $this->session->userdata('language'))?>">
																<i class="fa fa-search"></i>
															</a>
														</span>
													</div>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-md-12"><?=translate("Kontak", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<?php
														$kontak_supplier = array(
															"id"			=> "kontak_supplier",
															"name"			=> "kontak_supplier",
															"autofocus"			=> true,
															"class"			=> "form-control",
															"placeholder"	=> translate("Kontak", $this->session->userdata("language")),
															"style"			=> "background-color: transparent;border: 0px solid;",
															"readonly"		=> "readonly",
															"value"			=> $form_data[0]['no_telp'],
														);
														echo form_input($kontak_supplier);
													?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<?php
														$alamat_supplier = array(
															"id"			=> "alamat_supplier",
															"name"			=> "alamat_supplier",
															"rows"			=> 3,
															"autofocus"		=> true,
															"class"			=> "form-control",
															"placeholder"	=> translate("Alamat", $this->session->userdata("language")),
															"style"			=> "background-color: transparent;border: 0px solid; resize: none;",
															"readonly"		=> "readonly",
															"value"			=> $form_data[0]['alamat'].', '.$form_data[0]['rt_rw']
														);
														echo form_textarea($alamat_supplier);
													?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Email", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<?php
														$email_supplier = array(
															"id"			=> "email_supplier",
															"name"			=> "email_supplier",
															"autofocus"			=> true,
															"class"			=> "form-control",
															"placeholder"	=> translate("Email", $this->session->userdata("language")),
															"style"			=> "background-color: transparent;border: 0px solid;",
															"readonly"		=> "readonly",
															"value"			=> $form_data[0]['email']
														);
														echo form_input($email_supplier);
													?>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab_penerima">
										<div class="form-body">
											<div class="form-group">
												<label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<div class="radio-list">
														<label class="radio-inline">
														<input type="radio" name="tipe_penerima" id="tipe_penerima_1" value="1" <?=$tipe_int?>> <?=translate("Internal", $this->session->userdata("language"))?> </label>
														<label class="radio-inline">
														<input type="radio" name="tipe_penerima" id="tipe_penerima_2" value="2" <?=$tipe_ext?>> <?=translate("Eksternal", $this->session->userdata("language"))?> </label>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Ditujukan ke", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<div class="input-group">
														<?php
															$nama_penerima = array(
																"id"        => "nama_penerima",
																"name"      => "nama_penerima",
																"autofocus" => true,
																"class"     => "form-control",
																"readonly"  => "readonly"
															);

															$id_penerima = array(
																"id"			=> "id_penerima",
																"name"			=> "id_penerima",
																"autofocus"			=> true,
																"class"			=> "form-control hidden",
															);
															$tipe_penerima = array(
																"id"			=> "tipe",
																"name"			=> "tipe",
																"autofocus"			=> true,
																"class"			=> "form-control hidden",
																"value"			=> "1"
															);
															echo form_input($nama_penerima).form_input($id_penerima);
														?>
														<span class="input-group-btn">
															<a class="btn btn-primary pilih-penerima-customer hidden" title="<?=translate('Pilih Penerima', $this->session->userdata('language'))?>">
																<i class="fa fa-search"></i>
															</a>
															<a class="btn btn-primary pilih-penerima-cabang" title="<?=translate('Pilih Penerima', $this->session->userdata('language'))?>">
																<i class="fa fa-search"></i>
															</a>
														</span>
														
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Kontak", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<?php
														$kontak_penerima = array(
															"id"			=> "kontak_penerima",
															"name"			=> "kontak_penerima",
															"autofocus"			=> true,
															"class"			=> "form-control",
															"placeholder"	=> translate("Kontak", $this->session->userdata("language")),
															"style"			=> "background-color: transparent;border: 0px solid;",
															"readonly"		=> "readonly"
														);
														echo form_input($kontak_penerima);
													?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<?php
														$alamat_penerima = array(
															"id"			=> "alamat_penerima",
															"name"			=> "alamat_penerima",
															"rows"			=> 3,
															"autofocus"			=> true,
															"class"			=> "form-control",
															"placeholder"	=> translate("Alamat", $this->session->userdata("language")),
															"style"			=> "background-color: transparent;border: 0px solid; resize: none;",
															"readonly"		=> "readonly"
														);
														echo form_textarea($alamat_penerima);
													?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Email", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<?php
														$email_penerima = array(
															"id"			=> "email_penerima",
															"name"			=> "email_penerima",
															"autofocus"			=> true,
															"class"			=> "form-control",
															"placeholder"	=> translate("Email", $this->session->userdata("language")),
															"style"			=> "background-color: transparent;border: 0px solid;",
															"readonly"		=> "readonly"
														);
														echo form_input($email_penerima);
													?>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab_info_pembelian">
										<div class="form-body">
											<div class="form-group">
												<label class="col-md-12"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<div class="input-group date">
														<input type="text" class="form-control" id="tanggal_pesan" name="tanggal_pesan" readonly >
														<span class="input-group-btn">
															<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<div class="input-group date">
														<input type="text" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" readonly >
														<span class="input-group-btn">
															<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Garansi", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<div class="input-group date">
														<input type="text" class="form-control" id="tanggal_garansi" name="tanggal_garansi" readonly >
														<span class="input-group-btn">
															<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate('Tipe Pembayaran', $this->session->userdata('language'))?> :</label>
												<div class="col-md-12">
													<?php 
														$pembayaran_option = array(
															''	=> 'Pilih Pembayaran',
														);

														echo form_dropdown("tipe_pembayaran", $pembayaran_option, "", " id='tipe_pembayaran' name='tipe_pembayaran' class='form-control' ");
													 ?>
												</div>
											</div>
											<div class="form-group tempo" style="display:none;">
												<label class="col-md-12"><?=translate('Tempo', $this->session->userdata('language'))?> :</label>
												<div class="col-md-12">
													<div class="input-group date tanggal">
														<input class="form-control" id="date_tempo" readonly>
														<span class="input-group-btn">
															<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<?php
														$keterangan = array(
															"id"			=> "keterangan",
															"name"			=> "keterangan",
															"autofocus"			=> true,
															"rows"			=> 6,
															"class"			=> "form-control",
															"style"			=> "resize: none;",
															"placeholder"	=> translate("Keterangan", $this->session->userdata("language"))
														);
														echo form_textarea($keterangan);
													?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penerima", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="form-body">
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											if($form_data[0]['tipe_customer'] == 1)
											{
												$tipe = "Internal";
											}
											else
											{
												$tipe = "Eksternal";
											}

											$tipe_customer = array(
													"id"			=> "tipe_customer",
													"name"			=> "tipe_customer",
													"autofocus"			=> true,
													"class"			=> "form-control", 
													"readonly"		=> "readonly",
													"style"			=> "background-color: transparent;border: 0px solid;",	
													"value"			=> $tipe,
												);

											echo form_input($tipe_customer);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Ditujukan ke", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											if($form_data[0]['tipe_customer'] == 1)
											{
												$data_penerima = $this->draft_po_m->get_data_penerima_cabang()->result_array();
												// die_dump($this->db->last_query());
											}
											else
											{
												// $data_penerima = $this->draft_po_m->get_data_penerima_customer()->result_array();
											}
											$nama_penerima = array(
												"id"			=> "nama_penerima",
												"name"			=> "nama_penerima",
												"autofocus"			=> true,
												"class"			=> "form-control",
												"style"			=> "background-color: transparent;border: 0px solid;",
												"readonly"		=> "readonly",
												"value"			=> $data_penerima[0]['nama'].' ['.$data_penerima[0]['kode'].']'
											);

											$id_penerima = array(
												"id"			=> "id_penerima",
												"name"			=> "id_penerima",
												"autofocus"			=> true,
												"class"			=> "form-control hidden",
												"value"			=> $data_penerima[0]['id']
											);
											$tipe_penerima = array(
												"id"			=> "tipe",
												"name"			=> "tipe",
												"autofocus"			=> true,
												"class"			=> "form-control hidden",
												"value"			=> "1"
											);
											echo form_input($nama_penerima);
											echo form_input($id_penerima);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kontak", $this->session->userdata("language"))?> :</label>
									<div class="col-md-5">
										<?php
											$kontak_penerima = array(
												"id"			=> "kontak_penerima",
												"name"			=> "kontak_penerima",
												"autofocus"			=> true,
												"class"			=> "form-control",
												"placeholder"	=> translate("Kontak", $this->session->userdata("language")),
												"style"			=> "background-color: transparent;border: 0px solid;",
												"readonly"		=> "readonly",
												"value"			=> $data_penerima[0]['no_telp']
											);
											echo form_input($kontak_penerima);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
									<div class="col-md-8">
										<?php
											$alamat_penerima = array(
												"id"			=> "alamat_penerima",
												"name"			=> "alamat_penerima",
												"rows"			=> 3,
												"autofocus"			=> true,
												"class"			=> "form-control",
												"placeholder"	=> translate("Alamat", $this->session->userdata("language")),
												"style"			=> "background-color: transparent;border: 0px solid; resize: none;",
												"readonly"		=> "readonly",
												"value"			=> $data_penerima[0]['alamat'].', '.$data_penerima[0]['rt_rw'],
												
											);
											echo form_textarea($alamat_penerima);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Email", $this->session->userdata("language"))?> :</label>
									<div class="col-md-8">
										<?php
											$email_penerima = array(
												"id"			=> "email_penerima",
												"name"			=> "email_penerima",
												"autofocus"			=> true,
												"class"			=> "form-control",
												"placeholder"	=> translate("Email", $this->session->userdata("language")),
												"style"			=> "background-color: transparent;border: 0px solid;",
												"readonly"		=> "readonly",
												"value"			=> $data_penerima[0]['email']
											);
											echo form_input($email_penerima);
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Info Pembelian", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="form-body">
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											$tanggal_pesan = array(
												"id"			=> "tanggal_pesan",
												"name"			=> "tanggal_pesan",
												"autofocus"			=> true,
												"class"			=> "form-control date-picker",
												"placeholder"	=> translate("Tanggal Pesan", $this->session->userdata("language")),
												"value"			=> date('m/d/Y', strtotime($form_data[0]['tanggal_pesan']))
											);
											echo form_input($tanggal_pesan);
											// die_dump($form_data);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											$tanggal_kadaluarsa = array(
												"id"			=> "tanggal_kadaluarsa",
												"name"			=> "tanggal_kadaluarsa",
												"autofocus"			=> true,
												"class"			=> "form-control date-picker",
												"placeholder"	=> translate("Tanggal Kadaluarsa", $this->session->userdata("language")),
												"value"			=> date('m/d/Y', strtotime($form_data[0]['tanggal_kadaluarsa']))

											);
											echo form_input($tanggal_kadaluarsa);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate('Tipe Pembayaran', $this->session->userdata('language'))?> :</label>
									<div class="col-md-6">
										<?php 

											$data_pembayaran = $this->supplier_tipe_pembayaran_m->get_pembayaran($form_data[0]['id'])->result();
											// die_dump($pembayaran);
											$pembayaran = object_to_array($data_pembayaran);

											$pembayaran_option = array(
												''	=> 'Pilih Pembayaran',
											);

											foreach ($pembayaran as $data_bayar) {
												if($data_bayar['lama_tempo'] == null){
													$pembayaran_option[$data_bayar['id']] = $data_bayar['nama'];
												}
												else
												{
													$pembayaran_option[$data_bayar['id']] = $data_bayar['nama'].' '.$data_bayar['lama_tempo'].' hari';
												}
											}

											echo form_dropdown("tipe_pembayaran", $pembayaran_option, $form_data[0]['tipe_pembayaran'], " id='tipe_pembayaran' name='tipe_pembayaran' class='form-control' ");
										 ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											$keterangan = array(
												"id"			=> "keterangan",
												"name"			=> "keterangan",
												"autofocus"			=> true,
												"rows"			=> 6,
												"class"			=> "form-control",
												"placeholder"	=> translate("Keterangan", $this->session->userdata("language")),
												"style"			=> "resize: none;",
												"value"			=> $form_data[0]['keterangan']
											);
											echo form_textarea($keterangan);
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="portlet">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Detail Pembelian", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body">
					<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
					<table class="table table-striped table-bordered table-hover" id="table_detail_pembelian">
						<thead>
							<tr class="heading">
								<th class="text-center"style="width: 100px !important;"><?=translate("Kode", $this->session->userdata("language"))?> </th>
								<th class="text-center"style="width : 200px !important;"><?=translate("Nama", $this->session->userdata("language"))?> </th>
								<th class="text-center"style="width : 120px !important;"><?=translate("Syarat Order", $this->session->userdata("language"))?> </th>
								<th class="text-center"style="width : 100px !important;"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
								<th class="text-center"style="width : 80px !important;"><?=translate("Stok", $this->session->userdata("language"))?> </th>
								<th class="text-center"style="width : 120px !important;"><?=translate("Harga Sistem", $this->session->userdata("language"))?> </th>
								<th class="text-center" width="10%"><?=translate("Diskon(%)", $this->session->userdata("language"))?> </th>
								<th class="text-center"style="width : 150px !important;"><?=translate("Jumlah Pesan", $this->session->userdata("language"))?> </th>
								<th class="text-center" width="10%"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?> </th>
								<th class="text-center"style="width : 250px !important;"><?=translate("Sub Total", $this->session->userdata("language"))?> </th>
								<th class="text-center"width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
							</tr>
						</thead>
						<tbody>
							<?php if($items_rows){foreach ($items_rows as $row):?>
                                <?=$row?>
                            <?php endforeach;}?>
						</tbody>
						<tfoot>
							<tr>
							<td colspan="9" class="text-right bold">Total</td>
							<td colspan="2">
								<div class="input-group col-md-12">
									<span class="input-group-addon">
										&nbsp;Rp&nbsp;
									</span>
									<input class="form-control text-right" readonly value="0" id="total" name="total">
								</div>
								<input class="form-control text-right hidden" readonly value="0" id="total_hidden" name="total_hidden">
							</td>
						</tr>
						<tr>
							<td colspan="9" class="text-right bold">Diskon(%)</td>
							<td colspan="2">
								<div class="input-group col-md-12">
									<input class="form-control text-right" type="number" value="0" id="diskon" name="diskon">
									<span class="input-group-addon">
										&nbsp;%&nbsp;
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="9" class="text-right bold">PPH(%)</td>
							<td colspan="2">
								<div class="input-group col-md-12">
									<input class="form-control text-right" type="number" value="0" id="pph" name="pph">
									<span class="input-group-addon">
										&nbsp;%&nbsp;
									</span>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="9" class="text-right bold">Biaya Tambahan</td>
							<td colspan="2">
								<div class="input-group col-md-12">
									<span class="input-group-addon">
										&nbsp;Rp&nbsp;
									</span>
									<input class="form-control text-right" value="0" id="biaya_tambahan" name="biaya_tambahan">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="9" class="text-right bold">Grand Total</td>
							<td colspan="2">
								<div class="input-group col-md-12">
									<span class="input-group-addon">
										&nbsp;Rp&nbsp;
									</span>
									<input class="form-control text-right" readonly value="0" id="grand_total" name="grand_total">
								</div>
							</td>
						</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="ajax_notes1" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="modal fade" id="ajax_notes2" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<?=form_close()?>	

<div id="popover_item_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_supplier">
            <thead>
                <tr role="row" class="heading">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kontak Person', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Email', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Rating', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_item_content_pembelian" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_item_search">
            <thead>
                <tr role="row" class="heading">
                    <th><div class="text-center"><?=translate('Id', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Stok', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Belum Diterima', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Satuan', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Min', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Max', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Harga', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_jumlah_pesan" class="row">
    <div class="col-md-12">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Search Permintaan", $this->session->userdata("language"))?></span>
				</div>
				<div class="actions">
		            <a data-target="#ajax_notes1" data-toggle="modal" class="btn btn-primary" id="tambah-link">
		                <i class="fa fa-plus"></i>
		                <span class="hidden-480">
		                     <?=translate("Tambah Order", $this->session->userdata("language"))?>
		                </span>
		            </a>
		        </div>
			</div>
			<div class="portlet-body">
        		<table class="table table-condensed table-striped table-bordered table-hover" id="table_search_permintaan">
		            <thead>
		                <tr role="row" class="heading">
		                    <th class="hidden"><div class="text-center"><?=translate('Id', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('User (User Level)', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Subjek', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Keterangan', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
		                </tr>
		            </thead>
		            <tbody>
		            	<?php

		         		  	$data_link = $this->draft_po_m->get_data_link($data['id_draf'])->result_array();
			    			// die_dump($this->db->last_query());
		            	 	foreach ($data_link as $link) {
					    	// die_dump($link);
					    		echo '<tr>
					    					<td><input class="text-center" id="tanggal" value="'.date('d M Y', strtotime($link['tanggal'])).'" readonly="readonly" style="width: 100px !important; background-color: transparent;border: 0px solid;"></td>
					    					<td><input class="" id="user" value="'.$link['user'].' ('.$link['user_level'].')" readonly="readonly" style="background-color: transparent;border: 0px solid;"></td>
					    					<td><input class="" id="subjek" value="'.$link['subjek'].'" readonly="readonly" style="background-color: transparent;border: 0px solid;"></td>
					    					<td><input class="" id="keterangan" value="'.$link['keterangan'].'" readonly="readonly" style="background-color: transparent;border: 0px solid;"></td>
					    					<td><input class="text-center" id="jumlah" value="'.$link['jumlah'].' '.$link['satuan'].'" readonly="readonly" style="width: 70px !important; background-color: transparent;border: 0px solid;"></td>
					    					<td><a class="btn-xs btn-danger"><i class="fa fa-times"></i></a></td>
					    			  </tr>';
			    			}
			    		?>
		            </tbody>
		        </table>
		    </div>
		</div>
    </div>
</div>
