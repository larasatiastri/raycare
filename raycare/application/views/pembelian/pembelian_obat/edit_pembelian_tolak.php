<?php
	$form_attr = array(
	    "id"            => "form_add_pembelian", 
	    "name"          => "form_add_pembelian", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "edit",
        "pembelian_id" => $pk_value
    );

    echo form_open(base_url()."pembelian/pembelian_obat/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$btn_search        = '<a class="btn btn-primary search-item" data-original-title="Search Item" data-status-row="item_row_add" title="'.translate('Pilih Item', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a>';
	$btn_search_jumlah = '';
	
	if($form_data_detail != '')
	{
		$btn_search_db        = '<a class="btn btn-primary search-item disabled" data-original-title="Search Item" data-status-row="item_row_add" title="'.translate('Pilih Item', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a>';

		$i = 0;
		$total = 0;
		$item_row_template_db = '';

		// die(dump($form_data_detail));
		foreach ($form_data_detail as $detail) 
		{
			$btn_del_db = '';
			$color = '';
			$hidden = '';
			$label_satuan = '';
			$label_harga = '';
			$label_diskon = '';
			$label_jumlah = '';
			$label_tgl_kirim = '';

			$attrs_item_is_active_db = array(
			    'id'          => 'items_is_active_'.$i,
			    'name'        => 'items['.$i.'][is_active]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			    'value' => 1,

			);

			$item_satuan     = $this->item_satuan_m->get($detail['item_satuan_id']);
			if($detail['status'] == 3)
			{
				$btn_del_db = '<div class="text-center"><button class="btn red-intense del-this-db" data-confirm="'.translate('Anda yakin akan menghapus item ini?', $this->session->userdata('language')).'" data-index="'.$i.'" data-id="'.$detail['id'].'" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';
				$attrs_item_is_active_db['value'] = 0;

				$color           = 'style="color:red;"';
				$hidden          = 'hidden';
				

				$label_satuan    = '<label name="items[{0}][satuan]">'.$item_satuan->nama.'</label>';
				$label_harga     = '<div class="text-right"><label name="items[{0}][harga_beli]">'.formatrupiah($detail['harga_beli']).'</label></div>';
				$label_diskon    = '<div class="text-right"><label name="items[{0}][diskon]">'.$detail['diskon'].' %</label></div>';
				$label_jumlah    = '<div class="text-center"><label name="items[{0}][jumlah]">'.$detail['jumlah_disetujui'].'</label></div>';
				$label_tgl_kirim = '<div class="text-center"><label name="items[{0}][tgl_kirim]">'.date('d M Y', strtotime($detail['tanggal_kirim'])).'</label></div>';
			}

			$data_detail = $this->pembelian_detail_m->get_detail_persetujuan($detail['id']);

			$info = '<a title="'.translate('Info', $this->session->userdata('language')).'" data-item="'.htmlentities(json_encode($data_detail)).'" class="item-list" data-id="'.$detail['id'].'" data-satuan="'.$item_satuan->nama.'" name="info" '.$color.'><u>'.$detail['nama'].'</u></a>';

			$data_satuan = $this->item_satuan_m->get_by(array('item_id' => $detail['item_id']));

			$attrs_id_db = array ( 
			    'id'       => 'items_id_'.$i,
			    'type'     => 'hidden',
			    'name'     => 'items['.$i.'][id]',
			    'class'    => 'form-control',
			    'readonly' => 'readonly',
			    'value' => $detail['id'],
			);

			$attrs_item_id_db = array ( 
			    'id'       => 'items_item_id_'.$i,
			    'type'     => 'hidden',
			    'name'     => 'items['.$i.'][item_id]',
			    'class'    => 'form-control',
			    'readonly' => 'readonly',
			    'value' => $detail['item_id'],
			);

			$attrs_item_kode_db = array (
			    'id'          => 'items_kode_'.$i,
			    'name'        => 'items['.$i.'][item_kode]',
			    'class'       => 'form-control',
			    'readonly'    => 'readonly',
			    'value' => $detail['kode'],
			);

			$attrs_item_nama_db = array(
			    'id'          => 'items_nama_'.$i,
			    'name'        => 'items['.$i.'][item_nama]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			    'value' => $detail['nama'],
			);

			$attrs_item_syarat_db = array(
			    'id'          => 'items_syarat_'.$i,
			    'name'        => 'items['.$i.'][item_syarat]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			    'value' => $detail['item_id'],
			);

			$attrs_item_harga_db = array(
			    'id'          => 'items_harga_'.$i,
			    'name'        => 'items['.$i.'][item_harga]',
			    'class'       => 'text-right form-control '.$hidden,
			    'value' => $detail['harga_beli'],
			);

			$attrs_item_diskon_db = array(
			    'id'          => 'items_diskon_'.$i,
			    'type'		=> 'number',
			    'name'        => 'items['.$i.'][item_diskon]',
			    'class'       => 'text-right form-control '.$hidden,
			    'value' => $detail['diskon'],
			);

			$attrs_stok_db = array(
			    'id'    => 'items_stok_'.$i,
			    'name'  => 'items['.$i.'][stok]', 
			    'type'  => 'hidden',
			    'min'   => 0,
			    'class' => 'form-control text-right',
			    'style' => 'width:80px;',
			    'value' => 1,
			    'value' => $detail['item_id'],
			);

			$attrs_jumlah_db = array(
			    'id'    => 'items_jumlah_'.$i,
			    'name'  => 'items['.$i.'][jumlah]', 
			    'type'  => 'number',
			    'min'   => 0,
			    'class' => 'form-control text-right '.$hidden,
			    'value' => $detail['jumlah_disetujui'],
			);

			$attrs_item_total_db = array(
			    'id'          => 'items_total_'.$i,
			    'name'        => 'items['.$i.'][item_sub_total]',
			    'class'       => 'form-control hidden sub_total',
			    'readonly'    => 'readonly',
			    'value' => $detail['jumlah_disetujui']*$detail['harga_beli'],

			);

			

			

			$satuan_option = array(
				'' => 'Pilih..'
			);

			foreach ($data_satuan as $satuan) {
				$satuan_option[$satuan->id] = $satuan->nama;
			}

			$item_cols_db = array(// style="width:156px;
				'item_kode'          => '<label class="control-label hidden" name="items['.$i.'][item_kode]">'.$detail['kode'].'</label><div class="input-group">'.form_input($attrs_item_kode_db).form_input($attrs_item_id_db).form_input($attrs_id_db).'<span class="input-group-btn">'.$btn_search_db.'</span></div>',
				'item_name'          => $info.'<label class="control-label hidden" name="items['.$i.'][item_nama]">'.$detail['nama'].'</label>'.form_input($attrs_item_nama_db).'<div id="tabel_simpan_data_'.$i.'" class="hidden"></div>',
				'item_syarat'        => '<div class="text-center"><label class="control-label" id="item_syarat_'.$i.'" name="items['.$i.'][item_syarat]"></label></div>'.form_input($attrs_item_syarat_db),
				'item_satuan'        => form_dropdown('items['.$i.'][satuan_id]', $satuan_option,$detail['item_satuan_id'], 'id="items_satuan_'.$i.'" data-row="'.$i.'" class="form-control satuan '.$hidden.'"').$label_satuan,
				'item_stok'          => '<div class="text-center"><label class="control-label" name="items['.$i.'][stok]"></label></div>'.form_input($attrs_stok_db),
				'item_harga'         => '<div class="text-right '.$hidden.'"><label class="control-label hidden" name="items['.$i.'][item_harga]" id="items_hargaEl_'.$i.'"></label></div><div class="input-group col-md-12 '.$hidden.'"><span class="input-group-addon">&nbsp;Rp&nbsp;</span>'.form_input($attrs_item_harga_db).'</div>'.$label_harga,
				'item_diskon'        => form_input($attrs_item_diskon_db).$label_diskon,
				'item_jumlah'        => form_input($attrs_jumlah_db).$label_jumlah,
				'item_tanggal_kirim' => '<div class="input-group date '.$hidden.'">
											<input type="text" class="form-control" id="items_tanggal_kirim_'.$i.'" name="items['.$i.'][item_tanggal_kirim]" readonly value="'.date('d M Y', strtotime($detail['tanggal_kirim'])).'">
											<span class="input-group-btn">
												<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
											</span>
										</div>'.$label_tgl_kirim,
				'item_total'         => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_sub_total]">'.formatrupiah($detail['jumlah_pesan']*$detail['harga_beli']).'</label></div>'.form_input($attrs_item_total_db),
				'action'             => form_input($attrs_item_is_active_db).$btn_del_db,
			);

			$item_row_template_db .=  '<tr id="item_row_'.$i.'" class="table_item_beli" '.$color.'><td>' . implode('</td><td>', $item_cols_db) . '</td></tr>';

			$total = $total + ($detail['jumlah_pesan']*$detail['harga_beli']);
			$i++;
		}
	} 
	else
	{
		$i = 0;
		$item_row_template_db = '';
	}

	$btn_search        = '<a class="btn btn-primary search-item" data-original-title="Search Item" data-status-row="item_row_add" title="'.translate('Pilih Item', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a>';
	$btn_search_jumlah = '<a class="btn btn-primary search-jumlah" id="search_jumlah_permintaan_{0}" data-original-title="Jumlah" title="'.translate('Search Permintaan', $this->session->userdata('language')).'"><i class="fa fa-chain"></i></a>';
	$btn_del           = '<div class="text-center"><button class="btn red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

	$attrs_id = array ( 
	    'id'       => 'items_id_{0}',
	    'type'     => 'hidden',
	    'name'     => 'items[{0}][id]',
	    'class'    => 'form-control',
	    'readonly' => 'readonly',
	);

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
	    'id'          => 'items_tanggal_kirim_{0}',
	    'name'        => 'items[{0}][item_tanggal_kirim]',
	    'class'       => 'form-control date-picker'
	);

	$attrs_item_is_active = array(
	    'id'          => 'items_is_active_{0}',
	    'name'        => 'items[{0}][is_active]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	    'value' => 1,
	);

	$satuan_option = array(
		'' => 'Pilih..'
	);

	$item_cols = array(// style="width:156px;
		'item_kode'          => '<label class="control-label hidden" name="items[{0}][item_kode]"></label>'.form_input($attrs_id).form_input($attrs_item_id).'<div class="input-group">'.form_input($attrs_item_kode).'<span class="input-group-btn">'.$btn_search.'</span></div>',
		'item_name'          => '<label class="control-label" name="items[{0}][item_nama]"></label>'.form_input($attrs_item_nama).form_input($attrs_id_db).'<div id="tabel_simpan_data_{0}" class="hidden"></div>',
		'item_syarat'        => '<div class="text-center"><label class="control-label" id="item_syarat_{0}" name="items[{0}][item_syarat]"></label></div>'.form_input($attrs_item_syarat),
		'item_satuan'        => form_dropdown('items[{0}][satuan_id]', $satuan_option, "", "id=\"items_satuan_{0}\" data-row=\"{0}\" class=\"form-control satuan\"").form_input($attrs_satuan_nama),
		'item_stok'          => '<div class="text-center"><label class="control-label" name="items[{0}][stok]"></label></div>'.form_input($attrs_stok),
		'item_harga'         => '<div class="text-right"><label class="control-label hidden" name="items[{0}][item_harga]" id="items_hargaEl_{0}"></label></div><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span>'.form_input($attrs_item_harga_lama).form_input($attrs_item_harga).'</div>',
		'item_diskon'        => form_input($attrs_item_diskon),
		'item_jumlah'        => form_input($attrs_jumlah).form_input($attrs_jumlah_min),
		'item_tanggal_kirim' => '<div class="input-group date">
											<input type="text" class="form-control" id="items_tanggal_kirim_{0}" name="items[{0}][item_tanggal_kirim]" readonly value="'.date('d M Y').'">
											<span class="input-group-btn">
												<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
											</span>
										</div>',
		'item_total'         => '<div class="text-right"><label class="control-label" name="items[{0}][item_sub_total]"></label></div>'.form_input($attrs_item_total),
		'action'             => form_input($attrs_item_is_active).$btn_del,
	);

	$item_row_template =  '<tr id="item_row_{0}" class="table_item_beli"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$msg = translate("Apakah anda yakin akan mengubah PO ini?",$this->session->userdata("language"));
	$msg_draft= translate("Apakah and yakin akan menyimpan PO ini ke Draft?", $this->session->userdata("language"));

	if($form_data[0]['tipe_customer'] == 1)
	{
		$data_customer = $this->penerima_cabang_m->get($form_data[0]['customer_id']);
		$customer_alamat[0] = $this->cabang_alamat_m->get_alamat_lengkap($form_data[0]['customer_id']);
		$customer_email = $this->cabang_sosmed_m->get_by(array('cabang_id' => $form_data[0]['customer_id'], 'tipe' => 1, 'is_active' => 1), true);
	}

	$data_penawaran = $this->pembelian_penawaran_m->get_by(array('pembelian_id' => $pk_value, 'is_active' => 1));
	if($data_penawaran)
	{
		$data_penawaran = object_to_array($data_penawaran);
		$x = 0;
		$item_row_template_penawaran_db = '';
		foreach ($data_penawaran as $penawaran)
		{
			$btn_del_penawaran_db = '<div class="text-center"><button class="btn red-intense del-this-penawaran-db" data-index="'.$x.'" data-confirm="'.translate('Anda yakin akan menghapus penawaran ini?', $this->session->userdata('language')).'" data-id="'.$penawaran['id'].'" title="Hapus Penawaran"><i class="fa fa-times"></i></button></div>';

			$item_cols_penawaran_db = array(// style="width:156px;
				'penawaran_nomor'  => '<input type="hidden" id="penawaran_id_'.$x.'" name="penawaran['.$x.'][id]" class="form-control" value="'.$penawaran['id'].'"><input id="penawaran_nomor_'.$x.'" name="penawaran['.$x.'][nomor]" class="form-control" value="'.$penawaran['nomor_penawaran'].'">',
				'penawaran_upload' => '<div class="input-group">
											<input id="penawaran_url_'.$x.'" name="penawaran['.$x.'][url]" class="form-control" value="'.$penawaran['url'].'" readonly>
											<span class="input-group-btn" id="upload_'.$x.'">
			                                <span class="btn default btn-file">
			                                    <span class="fileinput-new">'.translate('Pilih File', $this->session->userdata('language')).'</span>       
			                                    <input type="file" name="upl" id="pdf_file_'.$x.'" data-url="'.base_url().'upload/upload_pdf" multiple />
			                                </span>
			                                </span>
			                            </div>',
				'action'           => '<input type="hidden" id="penawaran_is_active_'.$x.'" name="penawaran['.$x.'][is_active]" value="1" class="form-control">'.$btn_del_penawaran_db,
			);

			$item_row_template_penawaran_db .=  '<tr id="item_row_penawaran_'.$x.'" ><td>' . implode('</td><td>', $item_cols_penawaran_db) . '</td></tr>';

			$x++;
		}
	}
	else
	{
		$x = 0;
	}

	$btn_del_penawaran = '<div class="text-center"><button class="btn red-intense del-this-penawaran" title="Hapus Penawaran"><i class="fa fa-times"></i></button></div>';

	$item_cols_penawaran = array(// style="width:156px;
		'penawaran_nomor'  => '<input type="hidden" id="penawaran_id_{0}" name="penawaran[{0}][id]" class="form-control"><input id="penawaran_nomor_{0}" name="penawaran[{0}][nomor]" class="form-control">',
		'penawaran_upload' => '<div class="input-group">
									<input id="penawaran_url_{0}" name="penawaran[{0}][url]" class="form-control" readonly>
									<span class="input-group-btn" id="upload_{0}">
	                                <span class="btn default btn-file">
	                                    <span class="fileinput-new">'.translate('Pilih File', $this->session->userdata('language')).'</span>       
	                                    <input type="file" name="upl" id="pdf_file_{0}" data-url="'.base_url().'upload/upload_pdf" multiple />
	                                </span>
	                                </span>
	                            </div>',
		'action'           => '<input type="hidden" id="penawaran_is_active_{0}" name="penawaran[{0}][is_active]" value="1" class="form-control">'.$btn_del_penawaran,
	);

	$item_row_template_penawaran =  '<tr id="item_row_penawaran_{0}" ><td>' . implode('</td><td>', $item_cols_penawaran) . '</td></tr>';
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil font-blue-sharp"></i>
			<span class="caption font-blue-sharp bold uppercase"><?=translate("Pembelian", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-primary btn-circle" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
			<input type="hidden" id="save_draft" name="save_draft">
			<input type="hidden" id="jml_baris" name="jml_baris" value="<?=$i?>">
			<input type="hidden" id="jml_penawaran" name="jml_penawaran" value="<?=$x?>">
			<input type="hidden" id="tipe_bayar" name="tipe_bayar" value="<?=$form_data[0]['tipe_pembayaran']?>">
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
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
															"value"     => $form_data[0]['nama'],
														);

														$id_supplier = array(
															"id"        => "id_supplier",
															"name"      => "id_supplier",
															"autofocus" => true,
															"class"     => "form-control hidden",
															"value"     => $form_data[0]['id'],
														);
														echo form_input($nama_supplier);
														echo form_input($id_supplier);
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
															"readonly"		=> "readonly",
															"value"			=> $form_data[0]['no_telp']
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
															"readonly"		=> "readonly",
															"value"			=> $form_data[0]['alamat'].', '.$form_data[0]['rt_rw'].', '.$form_data[0]['kelurahan'].', '.$form_data[0]['kecamatan'].', '.$form_data[0]['kota'].', '.$form_data[0]['propinsi'].', '.$form_data[0]['negara'],
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
											<div class="form-group">
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
																"id"			=> "nama_penerima",
																"name"			=> "nama_penerima",
																"autofocus"			=> true,
																"class"			=> "form-control",
																"readonly"		=> "readonly",
																'value'			=> $data_customer->nama
															);

															$id_penerima = array(
																"id"			=> "id_penerima",
																"name"			=> "id_penerima",
																"autofocus"			=> true,
																"class"			=> "form-control hidden",
																'value'			=> $data_customer->id
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
															echo form_input($tipe_penerima);
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
															"readonly"		=> "readonly",
															'value'			=> $data_customer->penanggung_jawab
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
															"readonly"		=> "readonly",
															'value'			=> $customer_alamat[0]['alamat']
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
															"readonly"		=> "readonly",
															'value'			=> $customer_email->url
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
														<input type="text" class="form-control" id="tanggal_pesan" name="tanggal_pesan" readonly value="<?=date('d M Y', strtotime($form_data[0]['tanggal_pesan']))?>">
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
														<input type="text" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" readonly value="<?=date('d M Y', strtotime($form_data[0]['tanggal_kadaluarsa']))?>">
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
														<input type="text" class="form-control" id="tanggal_garansi" name="tanggal_garansi" readonly value="<?=($form_data[0]['tanggal_garansi'] != '1970-01-01')?date('d M Y', strtotime($form_data[0]['tanggal_garansi'])):''?>">
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

														foreach ($supplier_tipe_bayar as $tipe_bayar) 
														{
															$tempo = ($tipe_bayar['lama_tempo'] != '')?$tipe_bayar['lama_tempo'].' hari':'';
															$pembayaran_option[$tipe_bayar['id']] = $tipe_bayar['nama'].' '.$tempo;
														}

														echo form_dropdown("tipe_pembayaran", $pembayaran_option, $form_data[0]['tipe_pembayaran'], " id='tipe_pembayaran' name='tipe_pembayaran' class='form-control' ");
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
															"autofocus"			=> true,
															"rows"			=> 6,
															"class"			=> "form-control",
															"style"			=> "resize: none;",
															"placeholder"	=> translate("Keterangan", $this->session->userdata("language")),
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
								 <?php
								 	if(count($data_penawaran))
									{
										echo $item_row_template_penawaran_db;
									}
								 ?>
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
				<div class="col-md-9">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Detail Item", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="portlet-body">
							<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
							<table class="table table-striped table-bordered table-hover" id="table_detail_pembelian">
								<thead>
									<tr>
										<th class="text-center" style="width: 150px !important;"><?=translate("Kode", $this->session->userdata("language"))?> </th>
										<th class="text-center"style="width : 300px !important;"><?=translate("Nama", $this->session->userdata("language"))?> </th>
										<th class="text-center"style="width : 50px !important;"><?=translate("Syarat Order", $this->session->userdata("language"))?> </th>
										<th class="text-center"style="width : 120px !important;"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
										<th class="text-center"style="width : 80px !important;"><?=translate("Stok", $this->session->userdata("language"))?> </th>
										<th class="text-center"style="width : 165px !important;"><?=translate("Harga", $this->session->userdata("language"))?> </th>
										<th class="text-center"><?=translate("Diskon", $this->session->userdata("language"))?> </th>
										<th class="text-center" style="width : 80px !important;"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
										<th class="text-center" style="width : 150px !important;"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?> </th>
										<th class="text-center"style="width : 150px !important;"><?=translate("Sub Total", $this->session->userdata("language"))?> </th>
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
										<td colspan="9" class="text-right bold">Total</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;Rp&nbsp;
												</span>
												<input class="form-control text-right" readonly value="<?=$total?>" id="total" name="total">
											</div>
											<input class="form-control text-right hidden" readonly value="<?=$total?>" id="total_hidden" name="total_hidden">
										</td>
									</tr>
									<tr>
										<td colspan="8" class="text-right bold">Diskon</td>
										<td>
											<div class="input-group col-md-12">
												<input class="form-control text-right" value="0" min="0" max="100" id="diskon" name="diskon">
												<span class="input-group-addon">
													&nbsp;%&nbsp;
												</span>
											</div>
										</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;Rp&nbsp;
												</span>
												<input class="form-control text-right" id="diskon_nominal" name="diskon_nominal" value="0">
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="8" class="text-right bold">PPN</td>
										<td>
											<div class="input-group col-md-12">
												<input class="form-control text-right" value="0" min="0" max="100" id="pph" name="pph">
												<span class="input-group-addon">
													&nbsp;%&nbsp;
												</span>
											</div>
										</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;Rp&nbsp;
												</span>
												<input class="form-control text-right" id="pph_nominal" name="pph_nominal" value="0">
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
									<tr>
										<td colspan="8" class="text-right bold">DP</td>
										<td>
											<div class="input-group col-md-12">
												<input class="form-control text-right" value="0" min="0" max="100" id="dp" name="dp">
												<span class="input-group-addon">
													&nbsp;%&nbsp;
												</span>
											</div>
										</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;Rp&nbsp;
												</span>
												<input class="form-control text-right" id="dp_nominal" name="dp_nominal">
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="9" class="text-right bold">Sisa Bayar</td>
										<td colspan="2">
											<div class="input-group col-md-12">
												<span class="input-group-addon">
													&nbsp;Rp&nbsp;
												</span>
												<input type="hidden" class="form-control text-right" readonly value="0" id="sisa_bayar_hidden" name="sisa_bayar_hidden">
												<input class="form-control text-right" readonly value="0" id="sisa_bayar" name="sisa_bayar">
											</div>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Pembayaran Kredit", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="portlet-body">
							<!-- <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span> -->
							<table class="table table-striped table-bordered table-hover" id="table_detail_pembelian">
								<thead>
									<tr>
										<th class="text-center" colspan="2" width="10%"><?=translate("Tenor", $this->session->userdata("language"))?> </th>
										<th width="8%" class="text-center"><?=translate("Jenis Bayar", $this->session->userdata("language"))?> </th>
										<th width="1%" class="text-center" width="10%"><?=translate("Kelipatan", $this->session->userdata("language"))?> </th>
										<th colspan="2" class="text-center" width="9%"><?=translate("Bunga", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="5%"><?=translate("1x Setoran", $this->session->userdata("language"))?> </th>
										<th class="text-center" width="10%"><?=translate("Grand Total", $this->session->userdata("language"))?> </th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td rowspan="1" colspan="1" width="4%">
											<input type="number" class="form-control" name="lama_tenor" id="lama_tenor" min="0" value="0"></input>
										</td>
										<td rowspan="1" colspan="1" width="6%">
											<select class="form-control" name="jenis_tenor" id="jenis_tenor">
												<option value="0">Pilih..</option>
												<option value="1">Hari</option>
												<option value="2">Bulan</option>
												<option value="3">Tahun</option>
											</select>
										</td>
										<td rowspan="1" colspan="1">
											<select class="form-control" name="jenis_bayar" id="jenis_bayar">
												<option value="0">Pilih..</option>
												<option value="1">Harian</option>
												<option value="2">Bulanan</option>
												<option value="3">Tahunan</option>
											</select>
										</td>
									<td rowspan="1" colspan="1">
										<div class="input-group right">
											<input id="kelipatan" name="kelipatan" class="form-control" type="text" value="">
											<span class="input-group-addon">
												&nbsp;Kali&nbsp;
											</span>
										</div>
									</td>
									<td rowspan="1" colspan="1" width="3%">
										<div class="input-group right">
											<input id="bunga_persen" name="bunga_persen" class="form-control" type="text" value="">
											<span class="input-group-addon">
												&nbsp;%&nbsp;
											</span>
										</div>
									</td>
									<td rowspan="1" colspan="1">
										<div class="input-group right">
											<span class="input-group-addon">
												&nbsp;Rp&nbsp;
											</span>
											<input id="bunga_nominal" name="bunga_nominal" class="form-control" type="text" value="">
										</div>
									</td>
									<td rowspan="1" colspan="1">
										<div class="input-group right">
											<span class="input-group-addon">
												&nbsp;Rp&nbsp;
											</span>
											<input id="setoran" name="setoran"  class="form-control" type="text" value="">
										</div>
									</td>
									<td rowspan="1" colspan="1">
										<div class="input-group right">
											<span class="input-group-addon">
												&nbsp;Rp&nbsp;
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
		
		<?=form_close()?>
	</div>
</div>	

<div id="popover_item_content" style="display:none;" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_supplier">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kontak Person', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Email', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Rating', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_penerima_content_cabang" style="display:none;" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover"  id="table_pilih_cabang">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Cabang', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kontak Person', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_penerima_content_customer" style="display:none;" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover"  id="table_pilih_customer">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kontak Person', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_item_content_pembelian" style="display:none;" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover"  id="table_item_search">
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
        		<table class="table table-condensed table-striped table-bordered table-hover"  id="table_search_permintaan">
		            <thead>
		                <tr>
		                    <th class="hidden"><div class="text-center"><?=translate('Id', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('User (User Level)', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Subjek', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Keterangan', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
		                    <th class="hidden"><div class="text-center"><?=translate('Id Detail', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
		                </tr>
		            </thead>
		            <tbody>

		            </tbody>
		        </table>
		    </div>
		</div>
    </div>
</div>
