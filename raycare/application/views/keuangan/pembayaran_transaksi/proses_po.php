<?php
	$form_attr = array(
	    "id"            => "form_proses_po", 
	    "name"          => "form_proses_po", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "proses_po",
        "pembelian_id" => $pk_value
    );

    echo form_open(base_url()."keuangan/pembayaran_transaksi/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$btn_search        = '<div class="text-center"><a class="btn btn-primary search-item" data-original-title="Search Item" data-status-row="item_row_add" title="'.translate('Pilih Item', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a></div>';
	$btn_search_jumlah = '';
	
	if($form_data_detail != '')
	{
		$i = 0;
		$item_row_template_db = '';
		$total = 0;
		$diskon = 0;
		$pph = 0;
		$grand_total = 0;
		foreach ($form_data_detail as $detail) 
		{
			$btn_del_db = '<div class="text-center"><button class="btn btn-sm red-intense del-this-db" data-confirm="'.translate('Anda yakin akan menghapus item ini?', $this->session->userdata('language')).'" data-index="'.$i.'" data-id="'.$detail['id'].'" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

			$data_satuan = $this->item_satuan_m->get_by(array('item_id' => $detail['item_id']));

			$style = '';

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
			    'class'       => 'form-control hidden',
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
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			    'value' => $detail['harga_beli'],
			);

			$attrs_item_diskon_db = array(
			    'id'          => 'items_diskon_'.$i,
			    'type'		=> 'hidden',
			    'name'        => 'items['.$i.'][item_diskon]',
			    'class'       => 'form-control',
			    'value' => $detail['diskon'],
			    'max'	=> 100,
			    'min'	=> 0,
			);

			$attrs_stok_db = array(
			    'id'    => 'items_stok_'.$i,
			    'name'  => 'items['.$i.'][stok]', 
			    'type'  => 'number',
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
			    'max'   => $detail['jumlah_disetujui'],
			    'class' => 'form-control text-right',
			    'value' => $detail['jumlah_pesan'],
			);
			$attrs_jumlah_setuju_db = array(
			    'id'    => 'items_jumlah_'.$i,
			    'name'  => 'items['.$i.'][jumlah_setujui]', 
			    'type'  => 'hidden',
			    'min'   => 0,
			    'class' => 'form-control text-right',
			    'value' => $detail['jumlah_disetujui'],
			);

			$attrs_item_total_db = array(
			    'id'          => 'items_total_'.$i,
			    'name'        => 'items['.$i.'][item_total]',
			    'class'       => 'form-control sub_total hidden',
			    'readonly'    => 'readonly',
			    'value' => ($detail['jumlah_disetujui']*$detail['harga_beli']) - (($detail['diskon']/100)*$detail['harga_beli']),

			);

			$attrs_item_is_active_db = array(
			    'id'          => 'items_is_active_'.$i,
			    'name'        => 'items['.$i.'][is_active]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			    'value' => 1,

			);

			$attrs_item_satuan_db = array(
			    'id'          => 'items_satuan_'.$i,
			    'name'        => 'items['.$i.'][item_satuan]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			    'value' => $detail['item_satuan_id'],
			);

			$satuan_option = array(
				'' => 'Pilih..'
			);

			foreach ($data_satuan as $satuan) {
				$satuan_option[$satuan->id] = $satuan->nama;
			}

			$total = $total + (($detail['harga_beli'] - (($detail['diskon']/100)*$detail['harga_beli'])) * $detail['jumlah_pesan']);

			$satuan_item = $this->item_satuan_m->get($detail['item_satuan_id']);

			$item_cols_db = array(// style="width:156px;
				'item_kode'          => '<label class="control-label text-left" name="items['.$i.'][item_kode]">'.$detail['kode'].'</label>'.form_input($attrs_item_id_db).form_input($attrs_item_kode_db),
				'item_name'          => '<label class="control-label" name="items['.$i.'][item_nama]">'.$detail['nama'].'</label>'.form_input($attrs_item_nama_db).form_input($attrs_id_db),
				'item_satuan'        => '<label class="control-label" name="items['.$i.'][item_satuan]">'.$satuan_item->nama.'</label>'.form_input($attrs_item_satuan_db).form_input($attrs_item_is_active_db),
				'item_harga'         => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_harga]">'.formatrupiah($detail['harga_beli']).'</label></div>'.form_input($attrs_item_harga_db),
				'item_diskon'        => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_diskon]">'.$detail['diskon'].' %</label></div>'.form_input($attrs_item_diskon_db),
				'item_jumlah_setujui'        => '<label class="control-label" name="items['.$i.'][jumlah_setujui]">'.$detail['jumlah_disetujui'].' </label>'.form_input($attrs_jumlah_setuju_db),
				'item_tgl_kirim'     => '<div class="text-center"><label class="control-label" name="items['.$i.'][tgl_kirim]">'.date('d M Y', strtotime($detail['tanggal_kirim'])).' </label></div>',
				'item_total'         => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_sub_total]">'.formatrupiah(($detail['harga_beli'] - (($detail['diskon']/100)*$detail['harga_beli'])) * $detail['jumlah_pesan']).'</label>'.form_input($attrs_item_total_db).'</div>',
			);

			$item_row_template_db .=  '<tr id="item_row_'.$i.'" class="table_item_beli" '.$style.'><td>' . implode('</td><td>', $item_cols_db) . '</td></tr>';

			$i++;
		}
	} 
	else
	{
		$i = 0;
		$item_row_template_db = '';
		$total = 0;
		$diskon = 0;
		$pph = 0;
		$grand_total = 0;
	}


	$btn_del           = '<div class="text-center"><button class="btn btn-sm red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

	$attrs_id_db = array ( 
	    'id'       => 'items_id_{0}',
	    'type'     => 'hidden',
	    'name'     => 'items[{0}][id]',
	    'class'    => 'form-control',
	    'readonly' => 'readonly',
	    'value' => '',
	);

	$attrs_item_id  = array ( 
	    'id'       => 'items_item_id_{0}',
	    'type'     => 'hidden',
	    'name'     => 'items[{0}][item_id]',
	    'class'    => 'form-control',
	    'readonly' => 'readonly',
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
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);

	$attrs_stok = array(
	    'id'    => 'items_stok_{0}',
	    'name'  => 'items[{0}][stok]', 
	    'type'  => 'number',
	    'min'   => 0,
	    'class' => 'form-control text-right',
	    'style' => 'width:80px;',
	    'value' => 1,
	);

	$attrs_jumlah = array(
	    'id'    => 'items_jumlah_{0}',
	    'name'  => 'items[{0}][jumlah]', 
	    'type'  => 'number',
	    'min'   => 0,
	    'class' => 'form-control text-right'
	);

	$attrs_item_total = array(
	    'id'          => 'items_total_{0}',
	    'name'        => 'items[{0}][item_total]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
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
		'item_kode'          => '<label class="control-label" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;"></label>'.form_input($attrs_item_id).form_input($attrs_item_kode),
		'item_name'          => '<label class="control-label" name="items[{0}][item_nama]"></label>'.form_input($attrs_item_nama).form_input($attrs_id_db),
		'item_satuan'        => form_dropdown('items[{0}][satuan]', $satuan_option, "", "id=\"items_satuan_{0}\" class=\"form-control\""),
		'item_harga'         => '<div class="text-right"><label class="control-label" name="items[{0}][item_harga]"></label></div>'.form_input($attrs_item_harga),
		'item_diskon'        => '<label class="control-label" name="items[{0}][item_diskon]"></label>'.form_input($attrs_item_diskon),
		'item_jumlah'        => form_input($attrs_jumlah),
		'item_total'         => '<label class="control-label" name="items[{0}][item_total]"></label>'.form_input($attrs_item_total),
	);

	$item_row_template =  '<tr id="item_row_{0}" class="table_item_beli"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$msg = translate("Apakah anda yakin akan memproses PO yang telah disetujui ini?",$this->session->userdata("language"));
	$msg_draft= translate("Apakah and yakin akan menyimpan PO ini ke Draft?", $this->session->userdata("language"));

	if($form_data[0]['tipe_customer'] == 1)
	{
		$data_customer = $this->penerima_cabang_m->get($form_data[0]['customer_id']);
		$customer_alamat = $this->cabang_alamat_m->get_alamat_lengkap($form_data[0]['customer_id']);
		$customer_email = $this->cabang_sosmed_m->get_by(array('cabang_id' => $form_data[0]['customer_id'], 'tipe' => 1, 'is_active' => 1), true);
	}

	$data_penawaran = $this->pembelian_penawaran_m->get_by(array('pembelian_id' => $pk_value, 'is_active' => 1));

	$supplier_email = $this->supplier_email_m->get_by(array('supplier_id' => $form_data[0]['id'], 'is_active' => 1, 'is_primary' => 1), true);

	$email_supp = (count($supplier_email) != 0)?$supplier_email->email:'-';

	$data_supplier = $this->supplier_m->get_by(array('id' => $form_data[0]['supplier_id']), true);
	// die(dump($data_supplier));
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption font-blue-sharp bold uppercase"><?=$form_data[0]['no_pembelian']?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<input type="hidden" id="jml_baris" name="jml_baris" value="<?=$i?>">
			<a id="confirm_save" class="btn btn-primary btn-circle" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Proses", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
	        <a id="confirm_save_draft" class="btn btn-primary btn-circle hidden" href="#" data-confirm="<?=$msg_draft?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan ke Draft", $this->session->userdata("language"))?></a>

	    </div>
	</div>
	<?php
		if($pembayaran_status['keterangan_tolak'] != NULL && $pembayaran_status['keterangan_tolak'] != ''){
	?>
	<div class="note note-danger note-bordered">
		<p>
			NOTE : <?=$pembayaran_status['keterangan_tolak']?>
		</p>
	</div>
	<?php
		}
	?>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-7">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject"><?=translate("Informasi PO", $this->session->userdata("language"))?></span>
						</div>
					</div>
					<div class="portlet-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-12"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=date('d M Y', strtotime($form_data[0]['tanggal_pesan']))?></label>
										
									</div>
									<div class="form-group">
										<label class="col-md-12"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=date('d M Y', strtotime($form_data[0]['tanggal_kadaluarsa']))?></label>
									</div>
										
									<div class="form-group">
										<label class="col-md-12"><?=translate("Garansi", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=($form_data[0]['tanggal_kirim'] != '1970-01-01')?date('d M Y', strtotime($form_data[0]['tanggal_kirim'])):'-'?></label>
										

									</div>
									<?php
										$lama_tempo = ($tipe_bayar[0]['lama_tempo'] != '')?$tipe_bayar[0]['lama_tempo'].' Hari':'';
									?>
									<div class="form-group">
										<label class="col-md-12"><?=translate('Tipe Pembayaran', $this->session->userdata('language'))?> :</label>
										<label class="col-md-12 bold"><?=$tipe_bayar[0]['nama'].' '.$lama_tempo?></label>
										
									</div>
									<div class="form-group">
										<label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=($form_data[0]['keterangan'] != '')?$form_data[0]['keterangan']:'-'?></label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
										<?php
											$tipe = 'Dalam Negeri';
											if($form_data[0]['tipe_supplier'] == 2)
											{
												$tipe = 'Luar Negeri';
											}
										?>
										<label class="col-md-12 bold"><?=$tipe?></label>
										
									</div>
									<div class="form-group">
										<label class="col-md-12"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=$form_data[0]['nama'].' ['.$form_data[0]['kode'].']'?> </label>
										
									</div>
									
									<div class="form-group">
										<label class="col-md-12"><?=translate("Kontak", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=$form_data[0]['no_telp']?> </label>
									</div>
									<div class="form-group">
										<label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=$form_data[0]['alamat'].', '.$form_data[0]['rt_rw'].', '.$form_data[0]['kelurahan'].', '.$form_data[0]['kecamatan'].', '.$form_data[0]['kota'].', '.$form_data[0]['propinsi'].', Indonesia'?> </label>
									</div>
									<div class="form-group">
										<label class="col-md-12"><?=translate("Email", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=$email_supp?></label>
										
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
										<?php
											$tipe_customer = 'Internal';
											if($form_data[0]['tipe_customer'] == 2)
											{
												$tipe_customer = 'Eksternal';
											}
										?>
										<label class="col-md-12 bold"><?=$tipe_customer?></label>
									</div>
									<div class="form-group">
										<label class="col-md-12"><?=translate("Ditujukan ke", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=$data_customer->nama?></label>

									</div>
									<div class="form-group">
										<label class="col-md-12"><?=translate("Kontak", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=$data_customer->penanggung_jawab?></label>
									</div>
									<div class="form-group">
										<label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=$customer_alamat[0]['alamat'].', '.$customer_alamat[0]['nama_kelurahan'].', '.$customer_alamat[0]['kecamatan'].','.$customer_alamat[0]['kabkot'].', '.$customer_alamat[0]['propinsi']?></label>
									
									</div>
									<div class="form-group">
										<label class="col-md-12"><?=translate("Email", $this->session->userdata("language"))?> :</label>
										<label class="col-md-12 bold"><?=$customer_email->url?></label>
										
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="portlet">
									<div class="portlet-title">
										<div class="caption">
											<span class="caption-subject"><?=translate("Detail Item", $this->session->userdata("language"))?></span>
										</div>
									</div>
									<div class="portlet-body">
										<div class="table-scrollable">
										<table class="table table-striped table-bordered table-hover" id="table_detail_pembelian">
											<thead>
												<tr>
													<th class="text-center" width="5%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
													<th class="text-center" ><?=translate("Nama", $this->session->userdata("language"))?> </th>
													<th class="text-center" width="5%"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
													<th class="text-center"style="width : 120px !important;"><?=translate("Harga", $this->session->userdata("language"))?> </th>
													<th class="text-center" width="3%"><?=translate("Disc", $this->session->userdata("language"))?> </th>
													<th class="text-center" width="5%"><?=translate("Jml", $this->session->userdata("language"))?> </th>
													<th class="text-center" width="9%"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?> </th>
													<th class="text-center" width="10%"><?=translate("Sub Total", $this->session->userdata("language"))?> </th>
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
											<?php
												$diskon = ($form_data[0]['diskon']/100)*$total;
												$tad = $total - $diskon;
												$pph = ($form_data[0]['pph']/100)*$tad;
												$grand_total = (($total - $diskon) + $pph);
												$grand_total_pph = (($total - $diskon) + $pph) - $form_data[0]['pph_23_nominal'];
											?>
											<tfoot>
												<tr>
													<td colspan="7" class="text-right bold">Total</td>
													<td><div class="text-right bold" id="total"><?=formatrupiah($total)?></div></td>
												</tr>
												<tr>
													<td colspan="6" class="text-right bold">Diskon(%)</td>
													<td class="text-right"><?=$form_data[0]['diskon']?> %</td>
													<td class="text-right"><?=formatrupiah(($form_data[0]['diskon']/100)*$total)?></td>
												</tr>
												<tr>
													<td colspan="6" class="text-right bold">PPN(%)</td>
													<td class="text-right"><?=$form_data[0]['pph']?> %</td>
													<td class="text-right"><?=formatrupiah($pph)?></td>
												</tr>
												<tr>
													<td colspan="7" class="text-right bold">Total Setelah PPN</td>
													<td class="text-right bold" id="grand_tot"><?=formatrupiah($grand_total)?></td>
												</tr>
												<tr>
													<td colspan="6" class="text-right bold">PPH 23(%)</td>
													<td class="text-right"><?=$form_data[0]['pph_23']?> %</td>
													<td class="text-right"><?=formatrupiah($form_data[0]['pph_23_nominal'])?></td>
												</tr>
												<tr>
													<td colspan="7" class="text-right bold">Grand Total PO</td>
													<td class="text-right bold" id="grand_tot"><?=formatrupiah($grand_total_pph)?></td>
												</tr>

												<tr>
													<td colspan="6" class="text-right bold">DP(%)</td>
													<td class="text-right"><?=$form_data[0]['dp']?> %</td>
													<td class="text-right"><?=formatrupiah(($form_data[0]['dp']/100)*$tad)?></td>
												</tr>
												<tr class="hidden">
													<td colspan="7" class="text-right bold">Sisa Bayar</td>
													<td class="text-right bold"><?=formatrupiah($form_data[0]['sisa_bayar'])?></td>
												</tr>
												<tr>
													<td colspan="7" class="text-right bold">Biaya Tambahan</td>
													
													<td class="text-right" id="biaya_tambahan_po"><?=formatrupiah($form_data[0]['biaya_tambahan'])?></td>
												</tr>
												<tr>
													<td colspan="7" class="text-right bold">Grand Total Setelah Biaya</td>
													<td class="text-right bold" id="grand_tot_biaya"><?=formatrupiah($grand_total_pph + $form_data[0]['biaya_tambahan'])?></td>
												</tr>
												<tr>
													<td colspan="6" class="text-right bold">TOTAL INVOICE</td>
													<td colspan="2" class="text-right bold" id="label_total_invoice">Rp. 0,-</td>
												</tr>
											</tfoot>
										</table>

										<input class="form-control hidden" readonly value="<?=$total?>" id="tot_hidden" name="tot_hidden">
										<input class="form-control hidden" id="ppn_hidden" name="ppn_hidden" value="<?=$form_data[0]['pph']?>">
										<input class="form-control text-right hidden" id="disk_hidden" name="disk_hidden" value="<?=($form_data[0]['diskon'] / 100) * $total?>">
										
										<input class="form-control hidden" id="biaya_tambah_hidden" name="biaya_tambah_hidden" value="<?=$form_data[0]['biaya_tambahan']?>">

										<input class="form-control hidden" id="grand_tot_hidden" name="grand_tot_hidden" value="<?=$grand_total_pph?>">
										<input class="form-control hidden" id="grand_tot_biaya_hidden" name="grand_tot_biaya_hidden" value="<?=$grand_total + $form_data[0]['biaya_tambahan']?>">
										<input class="form-control hidden" id="depe" name="depe" value="<?=$form_data[0]['dp']?>">
										<input class="form-control hidden" id="sisa_nya" name="sisa_nya" value="<?=$grand_total-$form_data[0]['dp']?>">
										<input class="form-control hidden" id="total_invoice" name="total_invoice" value="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>	
		<div class="col-md-5">
			<div class="portlet light bordered" id="section-bon">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject"><?=translate("Form Input", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<label class="col-md-12"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<div class="input-group date">
									<input type="text" class="form-control" id="tanggal" name="tanggal" value="<?=date('d M Y')?>" readonly >
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
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
					<div class="row">
						<div class="col-md-12">
							<div class="portlet" id="section-biaya">
								<div class="portlet-title">
									<div class="caption">
										<span class="caption-subject"><?=translate("Biaya", $this->session->userdata("language"))?></span>
									</div>
									<div class="actions">
										<a class="btn btn-icon-only btn-default btn-circle add-biaya">
											<i class="fa fa-plus"></i>
										</a>
									</div>
								</div>
								<div class="portlet-body">
									<?php
									$biaya_option = array(
										''	=> translate('Pilih', $this->session->userdata('language')).'...'
									);

									$biaya = $this->biaya_m->get_by(array('is_active' => 1));

									foreach ($biaya as $row) {
										$biaya_option[$row->id] = $row->nama;
									}

									$form_biaya = '
					                    <div class="form-group hidden">
					                        <label class="control-label col-md-4">'.translate("ID", $this->session->userdata("language")).' :</label>
					                        <div class="col-md-8">
					                            <input class="form-control" id="id_biaya{0}" name="biaya[{0}][id]">
					                        </div>
					                    </div>
					                    <div class="form-group hidden">
					                        <label class="control-label col-md-4">'.translate("Active", $this->session->userdata("language")).' :</label>
					                        <div class="col-md-8">
					                            <input class="form-control" id="is_active_biaya{0}" name="biaya[{0}][is_active]">
					                        </div>
					                    </div>
					                    <div class="form-group">
					                        <label class="col-md-12">'.translate("Jenis Biaya", $this->session->userdata("language")).' :</label>
					                        <div class="col-md-12">
					                            <div class="input-group">';
					                $form_biaya .= form_dropdown('biaya[{0}][biaya_id]', $biaya_option, '','id="jenis_biaya_{0}" class="form-control"');             
					                $form_biaya .= '<span class="input-group-btn">
					                                    <a class="btn red-intense del-this-biaya" id="btn_delete_biaya_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
					                                </span>
					                            </div>
					                        </div>
					                    </div>
					                    <div class="form-group">
					                        <label class="col-md-12">'.translate("Jumlah Biaya", $this->session->userdata("language")).' :</label>
					                        <div class="col-md-12">
					                                <div class="input-group">
					                                    <span class="input-group-addon">
					                                        Rp.
					                                    </span>
					                                    <input class="form-control jumlah"  id="jumlah_biaya_{0}" name="biaya[{0}][nominal]" placeholder="Jumlah Biaya">
					                                </div>
					                                <span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
					                        </div>
					                    </div>';
									?>
									<input type="hidden" id="tpl-form-biaya" value="<?=htmlentities($form_biaya)?>">
									<div class="form-body">
										<ul class="list-unstyled" id="biayaList">
										</ul>
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
										<span class="caption-subject"><?=translate("Upload Proforma Invoice", $this->session->userdata("language"))?></span>
									</div>
									<div class="actions">
										<a class="btn btn-icon-only btn-default btn-circle add-upload">
											<i class="fa fa-plus"></i>
										</a>
									</div>
								</div>
								<div class="portlet-body">
									<?php
									$form_upload_bon = '
										<div class="form-group hidden">
											<label class="col-md-12">'.translate("ID", $this->session->userdata("language")).' :</label>
											<div class="col-md-12">
												<input class="form-control" id="id_bon{0}" name="bon[{0}][id]">
											</div>
										</div>
										<div class="form-group hidden">
											<label class="col-md-12">'.translate("Active", $this->session->userdata("language")).' :</label>
											<div class="col-md-12">
												<input class="form-control" id="is_active_bon{0}" name="bon[{0}][is_active]">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-12">'.translate("No. Proforma Invoice", $this->session->userdata("language")).' :</label>
											<div class="col-md-12">
												<div class="input-group">
													<input class="form-control" id="no_bon_{0}" name="bon[{0}][no_bon]" placeholder="No. Invoice" required>
													<span class="input-group-btn">
														<a class="btn red-intense del-this" id="btn_delete_upload_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
													</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-12">'.translate("Total Proforma Invoice", $this->session->userdata("language")).' :</label>
											<div class="col-md-12">
													<div class="input-group">
														<span class="input-group-addon">
															Rp.
														</span>
														<input class="form-control" required id="total_bon_{0}" name="bon[{0}][total_bon]" placeholder="Total Invoice">
													</div>
													<span class="help-block">Jangan menggunakan titik(.) atau koma(,)</span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-12">'.translate("Tgl. Proforma Invoice", $this->session->userdata("language")).' :<span class="required">*</span></label>
											<div class="col-md-12">
												<div class="input-group date">
													<input type="text" class="form-control" id="bon_tanggal_{0}" name="bon[{0}][tanggal]" placeholder="Tanggal" value="'.date('d M Y').'"readonly >
													<span class="input-group-btn">
														<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
													</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-12">'.translate("Upload Proforma Invoice", $this->session->userdata("language")).' :<span class="required">*</span></label>
											<div class="col-md-12">
												<input type="hidden" required name="bon[{0}][url]" id="bon_url_{0}" required>
												<div id="upload_{0}">
													<span class="btn default btn-file">
														<span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>		
														<input type="file" class="upl_invoice" name="upl" id="upl_{0}" data-url="'.base_url().'upload/upload_photo" multiple />
													</span>

												<ul class="ul-img">
												</ul>

												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-12">'.translate("Keterangan", $this->session->userdata("language")).' :</label>
											<div class="col-md-12">
												<textarea class="form-control" id="keterangan_{0}" name="bon[{0}][keterangan]" cols="8" rows="6" ></textarea>
											</div>
										</div>
										';

									?>

									<input type="hidden" id="tpl-form-upload" value="<?=htmlentities($form_upload_bon)?>">
									<div class="form-body" >
										<ul class="list-unstyled" id="invoiceList">
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>	
<?=form_close()?>

