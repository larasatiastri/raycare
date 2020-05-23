<?php
	$form_attr = array(
	    "id"            => "form_add_pembelian", 
	    "name"          => "form_add_pembelian", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."pembelian/pembelian_obat/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	if($data_item != '')
	{
		$i = 0;
		$total = 0;
		$item_row_template_db = '';
		$hidden = ''; 
		foreach ($data_item as $key => $detail) 
		{
			$btn_search        = '<a class="btn btn-primary search-item" data-original-title="Search Item" data-status-row="item_row_add" title="'.translate('Pilih Item', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a>';

			$btn_del_db    = '<div class="text-center"><button class="btn red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

			$btn_jumlah_db  = '<a class="btn blue-chambray add-jumlah" href="'.base_url().'pembelian/pembelian_obat/add_jumlah/item_row_{0}" data-original-title="Tambah Jumlah" title="'.translate('Tambah Jumlah', $this->session->userdata('language')).'" data-toggle="modal" data-target="#popup_modal_jumlah" ><i class="fa fa-truck"></i></a>';

			$item = $this->item_m->get_by(array('id' => $detail), true);
			$item = object_to_array($item);

			$data_satuan = $this->item_satuan_m->get_by(array('item_id' => $detail));

			$data_stok = $this->inventory_m->get_data_stok($detail,$data_item_satuan[$key]);
			$stock = (count($data_stok) != 0)?$data_stok[0]['stock']:'0';

			$supplier_item = $this->supplier_item_m->get_by(array('supplier_id' => $data_supplier[0]['id'], 'item_id' => $detail, 'item_satuan_id' => $data_item_satuan[$key]), true);

			$supplier_harga = $this->supplier_harga_item_m->get_harga($supplier_item->id)->result_array();

			$syarat = '-';
			if(count($supplier_item) != 0)
			{
				if($supplier_item->minimum_order != ''){
					$syarat = $supplier_item->minimum_order.'/'.$supplier_item->kelipatan_order;
				}
			}
			
			$attrs_item_id_db = array ( 
			    'id'       => 'items_item_id_'.$i,
			    'type'     => 'hidden',
			    'name'     => 'items['.$i.'][item_id]',
			    'class'    => 'form-control',
			    'readonly' => 'readonly',
			    'value' => $detail,
			);

			$attrs_item_kode_db = array (
			    'id'          => 'items_kode_'.$i,
			    'name'        => 'items['.$i.'][item_kode]',
			    'class'       => 'form-control',
			    'readonly'    => 'readonly',
			    'value' => $item['kode'],
			);

			$attrs_item_nama_db = array(
			    'id'          => 'items_nama_'.$i,
			    'name'        => 'items['.$i.'][item_nama]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			    'value' => $item['nama'],
			);

			$attrs_item_zat_aktif_db = array(
			    'id'          => 'items_zat_aktif_'.$i,
			    'name'        => 'items['.$i.'][item_zat_aktif]',
			    'class'       => 'form-control',
			    'value' => $item['zat_aktif'],
			);

			$attrs_item_bentuk_kekuatan_db = array(
			    'id'          => 'items_bentuk_kekuatan_'.$i,
			    'name'        => 'items['.$i.'][item_bentuk_kekuatan]',
			    'class'       => 'form-control',
			    'value' => $item['bentuk_kekuatan'],
			);

			$attrs_item_syarat_db = array(
			    'id'          => 'items_syarat_'.$i,
			    'name'        => 'items['.$i.'][item_syarat]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			    'value' => $detail,
			);

			$attrs_item_harga_db = array(
			    'id'          => 'items_harga_'.$i,
			    'name'        => 'items['.$i.'][item_harga]',
			    'class'       => 'text-right form-control',
			    'value' => $supplier_harga[0]['harga'],
			);

			if($data_supplier[0]['is_harga_flexible'] == 0) {
				$attrs_item_harga_db['readonly'] = 'readonly';
			}else if($data_supplier[0]['is_harga_flexible'] == 1) {
				unset($attrs_item_harga_db['readonly']);
			}

			$attrs_item_diskon_db = array(
			    'id'          => 'items_diskon_'.$i,
			    'type'		=> 'number',
			    'name'        => 'items['.$i.'][item_diskon]',
			    'class'       => 'text-right form-control',
			    'value' => 0,
			);

			$attrs_stok_db = array(
			    'id'    => 'items_stok_'.$i,
			    'name'  => 'items['.$i.'][stok]', 
			    'type'  => 'hidden',
			    'min'   => 0,
			    'class' => 'form-control text-right',
			    'style' => 'width:80px;',
			    'value' => 1,
			    'value' => $stock,
			);

			$attrs_jumlah_db = array(
			    'id'    => 'items_jumlah_'.$i,
			    'name'  => 'items['.$i.'][jumlah]', 
			    'type'  => 'number',
			    'min'   => 0,
			    'class' => 'form-control text-right',
			    'value' => $data_jumlah[$key],
			);

			$attrs_item_total_db = array(
			    'id'          => 'items_total_'.$i,
			    'name'        => 'items['.$i.'][item_sub_total]',
			    'class'       => 'form-control hidden sub_total',
			    'readonly'    => 'readonly',
			    'value' => $data_jumlah[$key] *$supplier_harga[0]['harga'],

			);

			$attrs_item_is_active_db = array(
			    'id'          => 'items_is_active_'.$i,
			    'name'        => 'items['.$i.'][is_active]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			    'value' => 1,

			);

			$satuan_option = array(
				'' => 'Pilih..'
			);

			foreach ($data_satuan as $satuan) {
				$satuan_option[$satuan->id] = $satuan->nama;
			}

			$item_cols_db = array(// style="width:156px;
				'item_kode'          => '<label class="control-label hidden" name="items['.$i.'][item_kode]">'.$item['kode'].'</label><div class="input-group">'.form_input($attrs_item_kode_db).form_input($attrs_item_id_db).'<span class="input-group-btn">'.$btn_search.'</span></div>',
				'item_name'          => '<label class="control-label" name="items['.$i.'][item_nama]">'.$item['nama'].'</label>'.form_input($attrs_item_nama_db).'<div id="tabel_simpan_data_'.$i.'" class="hidden"></div><div id="detail_kirim" class="hidden">',
				'item_zat_aktif'          => form_input($attrs_item_zat_aktif_db),
				'item_bentuk_kekuatan'          => form_input($attrs_item_bentuk_kekuatan_db),
				'item_satuan'        => form_dropdown('items['.$i.'][satuan_id]', $satuan_option,$data_item_satuan[$key], 'id="items_satuan_'.$i.'" data-row="'.$i.'" class="form-control satuan"'),
				'item_harga'         => '<div class="text-right"><label class="control-label hidden" name="items['.$i.'][item_harga]" id="items_hargaEl_'.$i.'"></label></div><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;IDR&nbsp;</span>'.form_input($attrs_item_harga_db).'</div>',
				'item_jumlah'        => form_input($attrs_jumlah_db),
				'item_diskon'        => form_input($attrs_item_diskon_db),

				'item_total'         => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_sub_total]">'.formatrupiah(1*$supplier_harga[0]['harga']).'</label></div>'.form_input($attrs_item_total_db),
				'action'             => form_input($attrs_item_is_active_db).$btn_del_db,
			);

			$item_row_template_db .=  '<tr id="item_row_'.$i.'" class="table_item_beli"><td>' . implode('</td><td>', $item_cols_db) . '</td></tr>';

			$total = $total + (1*$supplier_harga[0]['harga']);
			$i++;
		}
	} 
	else
	{
		$i = 0;
		$item_row_template_db = '';
	}

	$btn_search        = '<a class="btn btn-primary search-item" data-original-title="Search Item" data-status-row="item_row_add" title="'.translate('Pilih Item', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a>';
	$btn_jumlah        = '<a class="btn blue-chambray add-jumlah" href="'.base_url().'pembelian/pembelian_obat/add_jumlah/item_row_{0}" data-original-title="Tambah Jumlah" title="'.translate('Tambah Jumlah', $this->session->userdata('language')).'" data-toggle="modal" data-target="#popup_modal_jumlah"  disabled><i class="fa fa-truck"></i></a>';
	$btn_search_jumlah = '<a class="btn btn-primary search-jumlah" id="search_jumlah_permintaan_{0}" data-original-title="Jumlah" title="'.translate('Search Permintaan', $this->session->userdata('language')).'"><i class="fa fa-chain"></i></a>';
	$btn_del           = '<div class="text-center"><button class="btn red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

	$attrs_item_id  = array ( 
	    'id'       => 'items_item_id_{0}',
	    'type'     => 'hidden',
	    'name'     => 'items[{0}][item_id]',
	    'class'    => 'form-control',
	    'readonly' => 'readonly'
	);

	$attrs_item_kode = array (
	    'id'          => 'items_kode_{0}',
	    'name'        => 'items[{0}][item_kode]',
	    'class'       => 'form-control',
	    'readonly'    => 'readonly',
	    'required'    => 'required',
	);

	$attrs_item_nama = array(
	    'id'          => 'items_nama_{0}',
	    'name'        => 'items[{0}][item_nama]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);

	$attrs_item_zat_aktif = array(
	    'id'          => 'items_zat_aktif_{0}',
	    'name'        => 'items[{0}][item_zat_aktif]',
	    'class'       => 'form-control',
	);

	$attrs_item_bentuk_kekuatan = array(
	    'id'          => 'items_bentuk_kekuatan_{0}',
	    'name'        => 'items[{0}][item_bentuk_kekuatan]',
	    'class'       => 'form-control',
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
	    'min'   	  => 0,
	    'value'		  => 0,
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
	    'value'	=> '0',
	    'readonly'	=> 'readonly',
	    'required'	=> 'required',
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

	$attrs_per_item_total = array(
	    'id'          => 'items_per_total_{0}',
	    'name'        => 'items[{0}][per_item_sub_total]',
	    'class'       => 'form-control hidden sub_total_item',
	    'readonly'    => 'readonly',
	    'value'		  => 0
	);

	$attrs_tanggal_kirim_item = array(
	    'id'          => 'items_tanggal_kirim_{0}',
	    'name'        => 'items[{0}][item_tanggal_kirim]',
	    'class'       => 'form-control date-picker'
	);

	$satuan_option = array(
		'' => 'Pilih..'
	);

	$item_cols = array(// style="width:156px;
		'item_kode'          => '<label class="control-label hidden" name="items[{0}][item_kode]"></label><div class="input-group" style="min-width:90px;">'.form_input($attrs_item_kode).form_input($attrs_item_id).'<span class="input-group-btn">'.$btn_search.'</span></div>',
		'item_name'          => '<label class="control-label" name="items[{0}][item_nama]"></label><div style="min-width:200px;">'.form_input($attrs_item_nama).'<div id="tabel_simpan_data_{0}" class="hidden"></div></div><div id="detail_kirim" class="hidden"></div>',
		'item_zat_aktif'          => '<div style="min-width:150px;">'.form_input($attrs_item_zat_aktif).'</div>',
		'item_bentuk_kekuatan'          => '<div style="min-width:150px;">'.form_input($attrs_item_bentuk_kekuatan).'</div>',
		'item_satuan'        => '<div style="min-width:100px;">'.form_dropdown('items[{0}][satuan]', $satuan_option, "", "id=\"items_satuan_{0}\" data-row=\"{0}\" class=\"form-control satuan\"").form_input($attrs_satuan_nama).form_input($attrs_satuan_id).'</div>',
		'item_harga'         => '<div class="text-right" style="min-width:150px;"><label class="control-label hidden" name="items[{0}][item_harga]" id="items_hargaEl_{0}"></label></div><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;IDR&nbsp;</span>'.form_input($attrs_item_harga).form_input($attrs_item_harga_lama).'</div>',
		'item_jumlah'        => '<div style="min-width:100px;"><div class="input-group">'.form_input($attrs_jumlah).form_input($attrs_jumlah_min).'<span class="input-group-btn">'.$btn_jumlah.'</span></div></div>',
		'item_diskon'        => '<div style="min-width:100px;">'.form_input($attrs_item_diskon).'</div>',
		
		'item_total'         => '<div class="text-right" style="min-width:120px;"><label class="control-label" name="items[{0}][item_sub_total]"></label></div>'.form_input($attrs_item_total).form_input($attrs_per_item_total),
		'action'             => $btn_del,
	);

	$item_row_template =  '<tr id="item_row_{0}" class="table_item_beli"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
	
	$btn_del_penawaran = '<div class="text-center"><button class="btn red-intense del-this-penawaran" title="Hapus Penawaran"><i class="fa fa-times"></i></button></div>';

	$item_cols_penawaran = array(// style="width:156px;
		'penawaran_nomor'  => '<div style="width:120px;"><input id="penawaran_nomor_{0}" name="penawaran[{0}][nomor]" class="form-control"></div>',
		'penawaran_upload' => '<div class="input-group" style="width:180px;">
									<input id="penawaran_url_{0}" name="penawaran[{0}][url]" class="form-control" readonly>
									<span class="input-group-btn" id="upload_{0}">
	                                <span class="btn default btn-file">
	                                    <span class="fileinput-new">'.translate('Pilih File', $this->session->userdata('language')).'</span>       
	                                    <input type="file" name="upl" id="pdf_file_{0}" data-url="'.base_url().'upload_new/upload_pdf" multiple />
	                                </span>
	                                </span>
	                            </div>',
		'action'           => $btn_del_penawaran,
	);

	$item_row_template_penawaran =  '<tr id="item_row_penawaran_{0}" ><td>' . implode('</td><td>', $item_cols_penawaran) . '</td></tr>';

?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption font-blue-sharp bold uppercase"><?=translate("Pembelian", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<?php $msg = translate("Apakah anda yakin akan membuat PO ini?",$this->session->userdata("language"));
			      $msg_draft= translate("Apakah anda yakin akan menyimpan PO ini ke Draft?", $this->session->userdata("language"));
			?>
			<a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-primary btn-circle" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Buat PO", $this->session->userdata("language"))?></a>
			<input type="hidden" id="save_draft" name="save_draft">
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan PO", $this->session->userdata("language"))?></button>
	        <a id="confirm_save_draft" class="btn btn-primary btn-circle hidden" href="#" data-confirm="<?=$msg_draft?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan ke Draft", $this->session->userdata("language"))?></a>
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
					<div class="portlet">
						<div class="portlet-body form">
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
										<input type="hidden" id="jumlah_item" name="jumlah_item" value="<?=$i?>">
											<div class="form-group">
												<label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<div class="radio-list">
														<label class="radio-inline">
														<input type="radio" name="tipe_supplier" id="tipe_supplier_1" value="1" checked> <?=translate("Dalam Negeri", $this->session->userdata("language"))?> </label>
														<label class="radio-inline">
														<input type="radio" name="tipe_supplier" id="tipe_supplier_2" value="2"> <?=translate("Luar Negeri", $this->session->userdata("language"))?> </label>
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
																"value"  	=> (count($data_supplier) != 0)?$data_supplier[0]['nama']:'',
																"required"  => "required"
															);

															$id_supplier = array(
																"id"          => "id_supplier",
																"name"        => "id_supplier",
																"autofocus"   => true,
																"class"       => "form-control hidden",
																"value"  		=> (count($data_supplier) != 0)?$data_supplier[0]['id']:'',
																"placeholder" => translate("Pasien", $this->session->userdata("language"))
															);
															echo form_input($nama_supplier).form_input($id_supplier);

															$is_harga_flexible = array(
																"id"          => "is_harga_flexible",
																"name"        => "is_harga_flexible",
																"class"       => "form-control hidden",
																"value"  		=> (count($data_supplier) != 0)?$data_supplier[0]['is_harga_flexible']:0,
															);
															echo form_input($is_harga_flexible);

															$is_pkp = array(
																"id"          => "is_pkp",
																"name"        => "is_pkp",
																"class"       => "form-control hidden",
																"value"  		=> (count($data_supplier) != 0)?$data_supplier[0]['is_pkp']:0,
															);
															echo form_input($is_pkp);
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
															"value"  		=> (count($data_supplier) != 0)?$data_supplier[0]['orang_yang_bersangkutan']. '('.$data_supplier[0]['no_telp'].')':'',
															// "style"			=> "background-color: transparent;border: 0px solid;",
															"readonly"		=> "readonly"
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
															"value"  		=> (count($data_supplier) != 0)?$data_supplier[0]['alamat'].' '.$data_supplier[0]['nama_kelurahan'].', '.$data_supplier[0]['nama_kelurahan'].', '.$data_supplier[0]['nama_kecamatan'].', '.$data_supplier[0]['nama_kabupatenkota'].', '.$data_supplier[0]['nama_propinsi']:'',
															// "style"			=> "background-color: transparent;border: 0px solid; resize: none;",
															"readonly"		=> "readonly"
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
															"value"			=> (count($email_supplier) != 0)?$email_supplier['email']:'',
															// "style"			=> "background-color: transparent;border: 0px solid;",
															"readonly"		=> "readonly"
														);
														echo form_input($email_supplier);
													?>
												</div>
											</div>
											
										</div>
									</div>
									<div class="tab-pane" id="tab_penerima">
										<div class="form-body">
											<div class="form-group hidden">
												<label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<div class="radio-list">
														<label class="radio-inline">
														<input type="radio" name="tipe_penerima" id="tipe_penerima_1" value="1" checked> <?=translate("Internal", $this->session->userdata("language"))?> </label>
														<label class="radio-inline">
														<input type="radio" name="tipe_penerima" id="tipe_penerima_2" value="2"> <?=translate("Eksternal", $this->session->userdata("language"))?> </label>
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
																"readonly"  => "readonly",
																"required"  => "required"
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
															// "style"			=> "background-color: transparent;border: 0px solid;",
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
															// "style"			=> "background-color: transparent;border: 0px solid; resize: none;",
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
															// "style"			=> "background-color: transparent;border: 0px solid;",
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
														<input type="text" class="form-control" id="tanggal_pesan" name="tanggal_pesan" value="<?=date('d M Y')?>" required readonly >
														<span class="input-group-btn">
															<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Pengiriman Satu Tanggal", $this->session->userdata("language"))?> :</label>
												
												<div class="col-md-12">
													<div class="radio-list">
														<label class="radio-inline">
														<input type="radio" name="is_single" id="optionskirimya" value="1" checked="checked"> Ya </label>
														<label class="radio-inline">
														<input type="radio" name="is_single" id="optionskirimtdk" value="0"> Tidak </label>
														
													</div>
												</div>
											</div>
											<div class="form-group" id="tgl_kirim">
												<label class="col-md-12"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<div class="input-group date">
														<input type="text" class="form-control" id="tanggal_kirim" name="tanggal_kirim" value="<?=date('d M Y')?>" readonly >
														<span class="input-group-btn">
															<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group" hidden>
												<label class="col-md-12"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<div class="input-group date">
														<input type="text" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="<?=date('d M Y' , strtotime("+3 months"))?>" readonly >
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
														<input type="text" class="form-control" id="tanggal_garansi" name="tanggal_garansi" readonly value="<?=date('d M Y')?>">
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

														foreach ($tipe_pembayaran as $tipe_bayar) {
															$pembayaran_option[$tipe_bayar['id']] = $tipe_bayar['nama'].' '.$tipe_bayar['lama_tempo'];
														}

														echo form_dropdown("tipe_pembayaran", $pembayaran_option, "", 'id="tipe_pembayaran" name="tipe_pembayaran" class="form-control" required="required" ');
													 ?>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
												<div class="col-md-12">
													<?php
														$keterangan = array(
															"id"			=> "keterangan",
															"name"			=> "keterangan",
															"autofocus"		=> true,
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
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption"><?=translate("Penawaran", $this->session->userdata("language"))?></div>
							<div class="actions">
								<a class="btn btn-icon-only btn-default btn-circle add-upload">
									<i class="fa fa-plus"></i>
								</a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable">
								<span id="tpl_penawaran_row" class="hidden"><?=htmlentities($item_row_template_penawaran)?></span>
								<table class="table table-striped table-bordered table-hover" id="table_penawaran">
									<thead>
										<tr>
											<th class="text-center" width="8%"><?=translate("No. Penawaran", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="45%"><?=translate("Upload", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>								
							</div>

						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Detail Pembelian", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="portlet-body">
						<div class="table-scrollable">
							<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
							<table class="table table-striped table-bordered table-hover" id="table_detail_pembelian">
								<thead>
									<tr>
										<th class="text-center" width="10%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="10%"><?=translate("Nama", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="30%"><?=translate("Zat Aktif", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="20%"><?=translate("Bentuk", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="8%"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="5%"><?=translate("Harga", $this->session->userdata("language"))?> </th>
										<th class="text-center jumlah" width="10%"><?=translate("Jml Pesan", $this->session->userdata("language"))?> </th>
										<th class="text-center"><?=translate("Disc(%)", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="15%"><?=translate("Sub Total", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
									</tr>
								</thead>
								<tbody>
									<?php
                                    	if(count($item_cols_db) != 0)
                                    	{
                                    		echo $item_row_template_db;
                                    	}
                                    
									?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="8" class="text-right bold">Total</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input class="form-control text-right" readonly value="0" id="total" name="total">
											</div>
											<input class="form-control text-right hidden" readonly value="0" id="total_hidden" name="total_hidden">
										</td>
									</tr>
									<tr>
										<td colspan="7" class="text-right bold">Diskon</td>
										<td>
											<div class="input-group col-md-12">
												<input class="form-control text-right" type="number" value="0" min="0" max="100" id="diskon" name="diskon">
												<span class="input-group-addon">
													&nbsp;%&nbsp;
												</span>
											</div>
										</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input class="form-control text-right" id="diskon_nominal" name="diskon_nominal" value="0">
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="8" class="text-right bold">Total Setelah Diskon</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input class="form-control text-right" readonly value="0" id="total_after_disc" name="total_after_disc">
											</div>
											<input class="form-control text-right hidden" readonly value="0" id="total_after_disc_hidden" name="total_after_disc_hidden">
										</td>
									</tr>
									<tr>
										<td colspan="7" class="text-right bold">PPN</td>
										<td>
											<div class="input-group col-md-12">
												<input class="form-control text-right" type="number" value="0" min="0" max="100" id="pph" name="pph">
												<span class="input-group-addon">
													&nbsp;%&nbsp;
												</span>
											</div>
										</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input class="form-control text-right" id="pph_nominal" name="pph_nominal" value="0">
											</div>
										</td>
									</tr>

									<tr>
										<td colspan="8" class="text-right bold">Total Setelah PPN</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input class="form-control text-right" readonly value="0" id="total_after_tax" name="total_after_tax">
											</div>
											<input class="form-control text-right hidden" readonly value="0" id="total_after_tax_hidden" name="total_after_tax_hidden">
										</td>
									</tr>
									
									<tr>
										<td colspan="8" class="text-right bold">Pembulatan</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input type="text" class="form-control text-right" value="0" id="pembulatan" name="pembulatan">
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="8" class="text-right bold">Grand Total PO</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input type="hidden" class="form-control text-right" value="0" id="grand_total_hidden" name="grand_total_hidden">
												<input class="form-control text-right" readonly value="0" id="grand_total" name="grand_total">
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="7" class="text-right bold">DP</td>
										<td>
											<div class="input-group col-md-12">
												<input class="form-control text-right" type="number" value="0" min="0" max="100" id="dp" name="dp">
												<span class="input-group-addon">
													&nbsp;%&nbsp;
												</span>
											</div>
										</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input class="form-control text-right" id="dp_nominal" name="dp_nominal">
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="8" class="text-right bold">Sisa Bayar</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input type="hidden" class="form-control text-right" value="0" id="sisa_bayar_hidden" name="sisa_bayar_hidden">
												<input class="form-control text-right" readonly value="0" id="sisa_bayar" name="sisa_bayar">
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="7" class="text-right bold">Biaya Tambahan<div id="biaya_tambahan" class="hidden"></div></td>
										<td>
											<a class="btn blue-chambray add-biaya" title="<?=translate('Tambah Biaya', $this->session->userdata('language'))?>" href="<?=base_url()?>pembelian/pembelian_obat/add_biaya" data-toggle="modal" data-target="#modal_biaya">
												<i class="fa fa-pencil"></i>
											</a>
										</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input class="form-control text-right" readonly value="<?=formattanparupiah(0)?>" id="biaya_tambahan_show" name="biaya_tambahan_show">
											</div>
												<input class="form-control text-right hidden" value="0" id="biaya_tambahan" name="biaya_tambahan">
										</td>
									</tr>
									<tr>
										<td colspan="8" class="text-right bold">Grand Total Setelah Biaya</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input type="hidden" class="form-control text-right" value="0" id="grand_total_biaya_hidden" name="grand_total_biaya_hidden">
												<input class="form-control text-right" readonly value="0" id="grand_total_biaya" name="grand_total_biaya">
											</div>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
						</div>
					</div>					
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Pembayaran Kredit", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable">
								<!-- <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span> -->
								<table class="table table-striped table-bordered table-hover" id="table_detail_pembelian">
									<thead>
										<tr>
											<th class="text-center" colspan="2" width="150px;"><?=translate("Tenor", $this->session->userdata("language"))?> </th>
											<th width="150px;" class="text-center"><?=translate("Jenis Bayar", $this->session->userdata("language"))?> </th>
											<th width="1%" class="text-center" width="10%"><?=translate("Kelipatan", $this->session->userdata("language"))?> </th>
											<th colspan="2" class="text-center" width="9%"><?=translate("Bunga", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="5%"><?=translate("1x Setoran", $this->session->userdata("language"))?> </th>
											<th class="text-center" width="10%"><?=translate("Grand Total", $this->session->userdata("language"))?> </th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td rowspan="1" colspan="1" width="4%">
												<div style="min-width:70px;"><input type="number" class="form-control" name="lama_tenor" id="lama_tenor" min="0" value="0"></input></div>
											</td>
											<td rowspan="1" colspan="1" width="6%">
												<div style="min-width:70px;">
													<select class="form-control" name="jenis_tenor" id="jenis_tenor">
														<option value="0">Pilih..</option>
														<option value="1">Hari</option>
														<option value="2">Bulan</option>
														<option value="3">Tahun</option>
													</select>													
												</div>

											</td>
											<td rowspan="1" colspan="1">
												<div style="min-width:100px;">
													<select class="form-control" name="jenis_bayar" id="jenis_bayar">
														<option value="0">Pilih..</option>
														<option value="1">Harian</option>
														<option value="2">Bulanan</option>
														<option value="3">Tahunan</option>
													</select>													
												</div>

											</td>
										<td rowspan="1" colspan="1">
											<div class="input-group right" style="min-width:110px;">
												<input id="kelipatan" name="kelipatan" class="form-control" type="text" value="">
												<span class="input-group-addon">
													&nbsp;Kali&nbsp;
												</span>
											</div>
										</td>
										<td rowspan="1" colspan="1" width="3%">
											<div class="input-group right" style="min-width:95px;">
												<input id="bunga_persen" name="bunga_persen" class="form-control" type="text" value="">
												<span class="input-group-addon">
													&nbsp;%&nbsp;
												</span>
											</div>
										</td>
										<td rowspan="1" colspan="1">
											<div class="input-group right" style="min-width:150px;">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input id="bunga_nominal" name="bunga_nominal" class="form-control" type="text" value="">
											</div>
										</td>
										<td rowspan="1" colspan="1">
											<div class="input-group right" style="min-width:150px;">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input id="setoran" name="setoran"  class="form-control" type="text" value="">
											</div>
										</td>
										<td rowspan="1" colspan="1">
											<div class="input-group right" style="width:160px;">
												<span class="input-group-addon">
													&nbsp;IDR&nbsp;
												</span>
												<input id="total_bayar" name="total_bayar" readonly class="form-control" type="text" value="">
											</div>
										</td>
										</tr>
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

<div id="popover_item_content" class="row" style="display:none;">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_supplier">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kontak Person', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Rating', $this->session->userdata('language'))?></div></th>
                    <th width="1%"><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_penerima_content_cabang" class="row" style="display:none;">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_cabang">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Cabang', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kontak Person', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th width="1%"><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_penerima_content_customer" class="row" style="display:none;">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_customer">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kontak Person', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th width="1%"><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_item_content_pembelian" class="row" style="display:none;">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_item_search">
            <thead>
                <tr>
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

<div id="popover_jumlah_pesan" class="row" style="display:none;">
    <div class="col-md-12">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Search Permintaan", $this->session->userdata("language"))?></span>
				</div>
				<div class="actions">
		            <a data-target="#ajax_notes1" data-toggle="modal" class="btn btn-primary btn-circle" id="tambah-link">
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
		                    <th class="hidden"><div class="text-center"><?=translate('Id Detail', $this->session->userdata('language'))?></div></th>
		                    <th width="1%"><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
		                </tr>
		            </thead>
		            <tbody>

		            </tbody>
		        </table>
		    </div>
		</div>
    </div>
</div>
<div class="modal fade bs-modal-lg" id="modal_biaya" role="basic" aria-hidden="true">
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

<div class="modal fade" id="popup_modal_jumlah" role="basic" aria-hidden="true">
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
