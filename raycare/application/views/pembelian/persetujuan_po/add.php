<?php
	$form_attr = array(
	    "id"            => "form_add_persetujuan", 
	    "name"          => "form_add_persetujuan", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );

    echo form_open(base_url()."pembelian/persetujuan_po/save", $form_attr);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	// die_dump($form_data_supplier);

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
	    'class'       => 'form-control hidden',
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
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);

	$attrs_item_diskon = array(
	    'id'          => 'items_diskon_{0}',
	    'name'        => 'items[{0}][item_diskon]',
	    'class'       => 'form-control text-right hidden',
	    'type'		  => 'number',
	);

	$attrs_stok = array(
	    'id'    => 'items_stok_{0}',
	    'name'  => 'items[{0}][stok]',
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

	$attrs_jumlah_tolak = array(
	    'id'    => 'items_jumlah_tolak_{0}',
	    'name'  => 'items[{0}][jumlah_tolak]',
	    'min'   => 0,
	    'class' => 'form-control text-right',
	    'type'	=> 'number',
	    'readonly'	=> 'readonly',
	    'value'	=> '0'
	    // 'style' => 'width:80px;'
	);

	$attrs_jumlah_order = array(
	    'id'    => 'items_jumlah_order_{0}',
	    'name'  => 'items[{0}][jumlah_order]',
	    'class' => 'form-control text-right hidden',
	    'readonly'    => 'readonly',
	    // 'style' => 'width:80px;'
	);

	$attrs_satuan_nama = array(
	    'id'    => 'items_satuan_nama_{0}',
	    'name'  => 'items[{0}][satuan_nama]',
	    'min'   => 0,
	    'class' => 'form-control hidden'
	    // 'style' => 'width:80px;'
	);

	$attrs_item_total = array(
	    'id'          => 'items_total_{0}',
	    'name'        => 'items[{0}][item_sub_total]',
	    'class'       => 'form-control hidden sub_total',
	    'readonly'    => 'readonly',
	);

	$attrs_item_check = array(
	    'id'          => 'items_check_{0}',
	    'name'        => 'items[{0}][check]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);

	$attrs_item_keterangan = array(
	    'id'          => 'items_keterangan_{0}',
	    'name'        => 'items[{0}][keterangan]',
	    'class'       => 'form-control',
	);

	$satuan_option = array(
		'' => 'Pilih..'
	);

	$records = $this->persetujuan_po_m->get_data_item($pk_value, $form_data_supplier[0]['id'],$order)->result_array();

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
	$i = 0;
	foreach ($records as $key=>$data) {
	    // die_dump($data);
	    $input_check = '<input type="checkbox" id="items_check_{0}" name="items[{0}][checkbox]">';
	    $harga = $this->supplier_harga_item_m->get_harga_edit($data['id_satuan'])->result_array();
	    $stok = $this->inventory_m->get_stok($data['id'],$data['id_satuan']);
	    if(count($stok) == 0)
		{
			$data_stok = 0;
		}
		else
		{
			$data_stok = $stok[0]['stok'];
		}

		$max_min = ($data['max_order'] != NULL && $data['min_order'] != NULL)?$data['max_order'].'/'.$data['min_order']:'-';

		$attrs_item_id['value']      = $data['id'];
		$attrs_detail_id['value']    = $data['id_detail'];
		$attrs_item_kode['value']    = $data['kode'];
		$attrs_item_nama['value']    = $data['nama'];
		$attrs_item_syarat['value']  = $max_min;
		$attrs_jumlah_order['value'] = $data['jumlah_pesan'];
		$attrs_jumlah['value']       = ($data['jumlah_persetujuan'] === NULL)?$data['jumlah_pesan']: $data['jumlah_persetujuan'];
		$attrs_jumlah_tolak['value'] = ($data['jumlah_persetujuan'] === NULL)?0: ($data['jumlah_pesan']-$data['jumlah_persetujuan']);
		$attrs_item_diskon['value']  = $data['diskon'];
		$attrs_item_harga['value']   = $data['harga_beli'];
		$attrs_stok['value']         = $data_stok;
		$attrs_satuan_nama['value']  = $data['id_satuan'];
		$attrs_item_keterangan['value']  = $data['keterangan'];

		$checked  = '';
		$color  = '';
		$readonly = '';
		$disabled = '';
		$satuan_option = array(
			'' => $data['satuan']
		);
		   
		$jumlah_setuju = form_input($attrs_jumlah);
		$jumlah_tolak = form_input($attrs_jumlah_tolak);
		$satuan_item = form_dropdown('items[{0}][satuan]', $satuan_option, "", 'id="items_satuan_{0}" class="form-control" disabled '.$color);


		if($data['status_setuju'] == 4 )
		{
			$readonly = 'readonly="readonly"';
			$checked = 'checked';
			$color = 'style="color: red; "';
			$disabled = 'disabled="disabled"';

			$attrs_item_keterangan['readonly']  = 'readonly';
			$attrs_jumlah['readonly']  = 'readonly';
			$attrs_jumlah['style']  = 'color: red;';
			$attrs_satuan_nama['style']  = 'color: red;';

			$input_check = '<input type="checkbox" id="items_check_{0}" name="items[{0}][checkbox]" value="on" checked>';

			$jumlah_setuju = form_input($attrs_jumlah);
			$jumlah_tolak = form_input($attrs_jumlah_tolak);
			$satuan_item = form_dropdown('items[{0}][satuan]', $satuan_option, "", 'id="items_satuan_{0}" class="form-control" disabled '.$color);
		}
		else
		{
			unset($attrs_item_keterangan['readonly']);
			unset($attrs_jumlah['readonly']);
			unset($attrs_jumlah['style']);

			$jumlah_setuju = form_input($attrs_jumlah);
			$jumlah_tolak = form_input($attrs_jumlah_tolak);
		}

		// echo $data['user_level_id'];
		// if($data['user_level_id'] != $this->session->userdata('level_id') )
		// {
		// 	$item_satuan = $this->item_satuan_m->get($data['id_satuan']);

		// 	$readonly = 'readonly="readonly"';
		// 	$checked = '';
		// 	$color = 'style="color: red; "';
		// 	$disabled = 'disabled="disabled"';

		// 	$attrs_item_keterangan['value']  = translate('Item tidak perlu melewati persetujuan anda', $this->session->userdata('language'));
		// 	$attrs_item_keterangan['readonly']  = 'readonly';
		// 	$attrs_satuan_nama['style']  = 'color: red;';

		// 	$jumlah_setuju = ($data['jumlah_persetujuan'] === NULL)?'<label name="items[{0}][jumlah]">'.$data['jumlah_pesan'].'</label>': '<label name="items[{0}][jumlah]">'.$data['jumlah_persetujuan'].'</label>';
		// 	$satuan_item = '<label name="items[{0}][satuan]">'.$item_satuan->nama.'</label>';
			
		// }

		

	    // die_dump($data['id_satuan']);
	 
	$item_cols = array(// style="width:156px;
		'item_tolak'         => $input_check,
		'item_kode'         => '<label class="control-label" name="items[{0}][item_kode]" style="text-align : left !important;">'.$data['kode'].'</label>'.form_input($attrs_item_id).form_input($attrs_item_kode).form_input($attrs_detail_id),
		'item_name'         => '<label class="control-label" name="items[{0}][item_nama]">'.$attrs_item_nama['value'] = $data['nama'].'</label>'.form_input($attrs_item_nama).'<div id="simpan_data_{0}" class=""></div>',
		'item_syarat'       => '<div class="text-center"><label class="control-label" name="items[{0}][item_syarat]">'.$max_min.'</label></div>'.form_input($attrs_item_syarat),
		'item_stok'         => '<div class="text-center"><label class="control-label" name="items[{0}][stok]">'.$data_stok.'</label></div>'.form_input($attrs_stok),
		'item_jumlah_order' => '<div class="text-center"><label class="control-label" name="items[{0}][jumlah_order]">'. $data['jumlah_pesan'].'</label></div>'.form_input($attrs_jumlah_order),
		'item_jumlah'       => '<div class="text-center">'.$jumlah_setuju.'</div>',
		'item_jumlah_tolak'       => '<div class="text-center">'.$jumlah_tolak.'</div>',
		'item_satuan'       => $satuan_item.form_input($attrs_satuan_nama),
		'item_harga'        => '<div class="text-right"><label class="control-label" name="items[{0}][item_harga]">Rp. '.number_format($data['harga_beli'], 0, ' ', '.').',-</label></div>'.form_input($attrs_item_harga),
		'item_diskon'       => '<div class="text-right"><label class="control-label" name="items[{0}][item_diskon]">'.$data['diskon'].'%</label></div>'.form_input($attrs_item_diskon),
		'item_total'        => '<div class="text-right"><label class="control-label" name="items[{0}][item_sub_total]"></label></div>'.form_input($attrs_item_total),
	);

    $items =  '<tr id="item_row_{0}" class="table_item" '.$color.'><td>' . implode('</td><td>', $item_cols) . '</td></tr>
    			<tr><td colspan="3" '.$color.'>Keterangan</td><td colspan="9">'.form_input($attrs_item_keterangan).'</td></tr>';
    $items_rows[] = str_replace('{0}', "{$key}", $items );

	$i++;
	}

	$user_created = $this->user_m->get_by(array('id' => $form_data['created_by']), true);

	if($form_data['tipe_customer'] == 1)
	{
		$data_customer = $this->penerima_cabang_m->get($form_data['customer_id']);
		$customer_alamat = $this->cabang_alamat_m->get_alamat_lengkap($form_data['customer_id']);
		$customer_email = $this->cabang_sosmed_m->get_by(array('cabang_id' => $form_data['customer_id'], 'tipe' => 1, 'is_active' => 1), true);
	}
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-check font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Persetujuan PO", $this->session->userdata("language"))?></span>
		</div>
			</div>
	<div class="portlet-body form">
		<div class="row">
			<div class="col-md-3">
				<div class="portlet">
					<div class="portlet-body form">
						<div class="tabbable-custom nav-justified">
							<ul class="nav nav-tabs nav-justified">
								<li class="active">
									<a href="#tab_info" data-toggle="tab">
									Informasi </a>
								</li>
								<li>
									<a href="#tab_supplier" data-toggle="tab">
									Supplier </a>
								</li>
								<li>
									<a href="#tab_penerima" data-toggle="tab">
									Penerima </a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_info">
									<div class="form-group">
										<input type="hidden" id="pembelian_id" name="pembelian_id" value="<?=$pk_value?>">
										<input type="hidden" id="order_persetujuan" name="order_persetujuan" value="<?=$order?>">
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("No PO", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12" style="text-align: left;"><?=$form_data['no_pembelian']?></label>
										<input type="hidden" id="id_persetujuan" name="id_persetujuan" value="<?=$pk_value?>">
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12" style="text-align: left;"><?=date('d M Y', strtotime($form_data['tanggal_pesan']))?></label>
									</div>
									<?php
										$lama_tempo = ($tipe_bayar[0]['lama_tempo'] != '')?$tipe_bayar[0]['lama_tempo'].' Hari':'';
									?>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate('Tipe Pembayaran', $this->session->userdata('language'))?> :</label>
										<label class="col-md-12"><?=$tipe_bayar[0]['nama'].' '.$lama_tempo?></label>
										
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12" style="text-align: left;"><?=$form_data['keterangan']?></label>
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Dibuat oleh", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12" style="text-align: left;"><?=$user_created->nama?></label>
									</div>
								</div>
								<div class="tab-pane" id="tab_supplier">
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12" style="text-align: left;"><?=$form_data_supplier[0]['nama'].' ['.$form_data_supplier[0]['kode'].']'?></label>
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Contact Person", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12" style="text-align: left;"><?=$form_data_supplier[0]['orang_yang_bersangkutan']?></label>
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12" style="text-align: left;"><?=$form_data_supplier[0]['alamat']?></label>
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Telepon", $this->session->userdata("language"))?> :</label>
										<div class="col-md-12">
											<?php
												$data_telp = $this->pembelian_m->get_data_no_telp($pk_value)->result();
												echo '<ul style="list-style-type: none; text-align: left; padding: 0px; margin:0px;">'; 
					                			foreach ($data_telp as $no_telp)
					                			{
					                				echo '<li>'.$no_telp->no_telp.'</li>';
					                			}
					                			echo '</ul>';
											?>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="tab_penerima">
									<div class="form-body">
										
										<div class="form-group">
											<label class="col-md-12"><?=translate("Ditujukan ke", $this->session->userdata("language"))?> :</label>
											<label class="col-md-12 bold"><?=$data_customer->nama?></label>

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
														"style"			=> "background-color: transparent;border: 0px solid;",
														"readonly"		=> "readonly",
														'value'			=> $customer_alamat[0]['alamat'].', '.$customer_alamat[0]['nama_kelurahan'].', '.$customer_alamat[0]['kecamatan'].','.$customer_alamat[0]['kabkot'].', '.$customer_alamat[0]['propinsi']
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
														"readonly"		=> "readonly",
														'value'			=> $customer_email->url
													);
													echo form_input($email_penerima);
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
					</div>
					<div class="portlet-body table-scrollable">
						<span id="tpl_penawaran_row" class="hidden"><?=htmlentities($item_row_template_penawaran)?></span>
						<table class="table table-striped table-bordered table-hover" id="table_penawaran">
							<thead>
								<tr>
									<th class="text-center" width="100%"><?=translate("Penawaran", $this->session->userdata("language"))?> </th>	
								</tr>
							</thead>
							<tbody>
								<?php
									if($data_penawaran)
									{
										$data_penawaran = object_to_array($data_penawaran);
										foreach ($data_penawaran as $penawaran)
										{
											?>
											<tr>
												<td><a target="_blank" href="<?=base_url()?>assets/mb/pages/pembelian/pembelian/doc/penawaran/<?=$pk_value?>/<?=$penawaran['id']?>/<?=$penawaran['url']?>"><?=($penawaran['nomor_penawaran'] != '')?$penawaran['nomor_penawaran']:$penawaran['url']?></a></td>
											</tr>
											<?php
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-9">
			<div class="table-scrollable">
				<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>	
				<table class="table table-striped table-hover" id="table_detail_pembelian">
				<thead>
					<th class="text-center" width="1%"><?=translate("Tolak", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Syarat Order", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Stok", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Jumlah Order", $this->session->userdata("language"))?> </th>
					<th class="text-center" style="width: 120px !important;"><?=translate("Jumlah Setujui", $this->session->userdata("language"))?> </th>
					<th class="text-center" style="width: 60px !important;"><?=translate("Jumlah Tolak", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Harga Sistem", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Diskon", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Sub Total", $this->session->userdata("language"))?> </th>
				</thead>
				<tbody>
					<?php foreach ($items_rows as $row):?>
		                <?=$row?>
		            <?php endforeach;?>
				</tbody>
				<tfoot>
					<?php

						$data_head = $this->pembelian_m->get_by(array('id' => $pk_value));
						$data_head_detail = object_to_array($data_head);
					?>
					<tr>
						<td colspan="10" class="text-right bold">Total</td>
						<td colspan="2">
							<div class="input-group col-md-12">
								<span class="input-group-addon">
									&nbsp;Rp&nbsp;
								</span>
								<input class="form-control text-right" readonly value="" id="total" name="total">
							</div>
							<input class="form-control text-right hidden" readonly value="" id="total_hidden" name="total_hidden">
						</td>
					</tr>
					<tr>
						<td colspan="10" class="text-right bold">Diskon(%)</td>
						<td colspan="2">
							<div class="input-group col-md-12">
								<input class="form-control text-right" readonly type="number" value="<?=$data_head_detail[0]['diskon']?>" id="diskon" name="diskon">
								<span class="input-group-addon">
									&nbsp;%&nbsp;
								</span>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="10" class="text-right bold">Total Setelah Diskon</td>
						<td colspan="2">
							<div class="input-group col-md-12">
								<span class="input-group-addon">
									&nbsp;Rp&nbsp;
								</span>
								<input class="form-control text-right" readonly value="0" id="total_after_disc" name="total_after_disc">
							</div>
							<input class="form-control text-right hidden" readonly value="0" id="total_after_disc_hidden" name="total_after_disc_hidden">
						</td>
					</tr>
					<tr>
						<td colspan="10" class="text-right bold">PPN(%)</td>
						<td colspan="2">
							<div class="input-group col-md-12">
								<input class="form-control text-right" readonly type="number" value="<?=$data_head_detail[0]['pph']?>" id="pph" name="pph">
								<span class="input-group-addon">
									&nbsp;%&nbsp;
								</span>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="10" class="text-right bold">Total Setelah PPN</td>
						<td colspan="2">
							<div class="input-group col-md-12">
								<span class="input-group-addon">
									&nbsp;Rp&nbsp;
								</span>
								<input class="form-control text-right" readonly value="0" id="total_after_tax" name="total_after_tax">
							</div>
							<input class="form-control text-right hidden" readonly value="0" id="total_after_tax_hidden" name="total_after_tax_hidden">
						</td>
					</tr>
					<tr>
						<td colspan="10" class="text-right bold">PPH 23</td>
						<td colspan="2">
							<div class="input-group col-md-12">
								<span class="input-group-addon">
									&nbsp;Rp&nbsp;
								</span>
								<input class="form-control text-right" readonly value="<?=formattanparupiahstrip($data_head_detail[0]['pph_23_nominal'])?>" id="pph23" name="pph23">
								<input class="form-control" type="hidden" readonly value="<?=$data_head_detail[0]['pph_23_nominal']?>" id="pph23_nominal" name="pph23_nominal">
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="10" class="text-right bold">Biaya Tambahan</td>
						<td colspan="2">
							<div class="input-group col-md-12">
								<span class="input-group-addon">
									&nbsp;Rp&nbsp;
								</span>
								<input class="form-control text-right" readonly value="<?=formattanparupiahstrip($data_head_detail[0]['biaya_tambahan'])?>" id="biaya_tambahan" name="biaya_tambahan">
								<input class="form-control" type="hidden" readonly value="<?=$data_head_detail[0]['biaya_tambahan']?>" id="biaya_tambahan_hidden" name="biaya_tambahan_hidden">
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="10" class="text-right bold">Grand Total</td>
						<td colspan="2">
							<div class="input-group col-md-12">
								<span class="input-group-addon">
									&nbsp;Rp&nbsp;
								</span>
								<input class="form-control text-right" readonly value="" id="grand_total" name="grand_total">
							</div>
						</td>
					</tr>
				</tfoot>
				</table>
			</div>
			</div>
			<div class="col-md-9">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject"><?=translate("Detail Pengiriman", $this->session->userdata("language"))?></span>
						</div>
					</div>
					<div class="portlet-body">
						<div class="form-body">
						<?php 
							$data_pengiriman = $this->pembelian_detail_tanggal_kirim_m->get_tanggal_kirim($pk_value);

							$idx = 1;
							foreach ($data_pengiriman as $key => $data_kirim){
						?>
							
								<table class="table table-striped table-hover" >
								<thead>
									<tr>
										<th colspan="4"><?=date('d M Y', strtotime($data_kirim['tanggal_kirim']))?></th>
									</tr>
								</thead>
								<tbody>
							<?php
								$data_kirim_detail = $this->pembelian_detail_tanggal_kirim_m->get_tanggal_kirim_detail($pk_value, $data_kirim['tanggal_kirim']);

								$idy = a;
								foreach ($data_kirim_detail as $key => $kirim_detail) {
							?>
								<tr>
								<td><?=$kirim_detail['kode_item']?></td>
								<td><?=$kirim_detail['nama_item']?></td>
								<td><?=$kirim_detail['jumlah_kirim']?></td>
								<td><?=$kirim_detail['nama_satuan']?></td>
								</tr>
							<?php
								$idy++;

								}	
							?>		
								</tbody>
								</table>
					<?php
								$idx++;
							}
						?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-actions right">
			<?php $msg = translate("Apakah anda yakin akan menyimpan Persetujuan ini?", $this->session->userdata("language"));?>
			<a class="btn default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a class="btn btn-danger" href="<?=base_url()?>pembelian/persetujuan_po/tolak_po/<?=$pk_value?>" data-toggle="modal" data-target="#popup_modal_proses"><i class="fa fa-times"></i> <?=translate("Tolak PO", $this->session->userdata("language"))?></a>
	        <a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Proses", $this->session->userdata("language"))?></a>
			<button type="submit" id="save" class="btn default hidden" ><?=translate("Proses", $this->session->userdata("language"))?></button>
		</div>

	</div>	
</div>

<div class="modal fade bs-modal-lg" id="popup_modal_proses" role="basic" aria-hidden="true">
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
	
