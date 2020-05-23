<?php
	$form_attr = array(
	    "id"            => "form_proses_po", 
	    "name"          => "form_proses_po", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "proses_bayar_po_ttf",
        "pembelian_id" => $pk_value,
        "ttf_id"	=> $id_ttf
    );

    echo form_open(base_url()."keuangan/pembayaran_transaksi/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$btn_search        = '<a class="btn btn-primary search-bank" data-status-row="item_row_add" title="'.translate('Pilih Bank', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a>';
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
				'item_tgl_kirim'     => '<div class="text-center"><label class="control-label" name="items['.$i.'][tgl_kirim]">'.date('d M Y', strtotime($detail['tanggal_kirim'])).' </label></div>',
				'item_harga'         => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_harga]">'.formatrupiah($detail['harga_beli']).'</label></div>'.form_input($attrs_item_harga_db),
				'item_diskon'        => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_diskon]">'.$detail['diskon'].' %</label></div>'.form_input($attrs_item_diskon_db),
				'item_jumlah_setujui'        => '<label class="control-label" name="items['.$i.'][jumlah_setujui]">'.$detail['jumlah_disetujui'].' '.$satuan_item->nama.' </label>'.form_input($attrs_jumlah_setuju_db).form_input($attrs_item_satuan_db).form_input($attrs_item_is_active_db),
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

	$total_invoice = 0;
	if(count($data_invoice) != 0){
		foreach ($data_invoice as $key => $bon) {
			$total_invoice = $total_invoice + $bon['total_invoice'];
		}
	}
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption font-blue-sharp bold uppercase"><?=$form_data[0]['no_pembelian']?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<input type="hidden" id="jml_baris" name="jml_baris" value="<?=$i?>">

	    </div>
	</div>
	<div class="note note-danger note-bordered">
		<p>
			<b> Note Keuangan: </b><?=$data_bayar_detail['keterangan']?>
		</p>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
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
										<input class="form-control" type="hidden" id="supplier_id" name="supplier_id" value="<?=$form_data[0]['id']?>"></input>
										
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
													<th class="text-center" width="9%"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?> </th>
													<th class="text-center"style="width : 120px !important;"><?=translate("Harga", $this->session->userdata("language"))?> </th>
													<th class="text-center" width="3%"><?=translate("Disc", $this->session->userdata("language"))?> </th>
													<th class="text-center" width="5%"><?=translate("Jml", $this->session->userdata("language"))?> </th>
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
												$pph = ($form_data[0]['pph']/100)*$total;
												$grand_total = ($total - $diskon) + $pph ;
											?>
											<tfoot>
												<tr>
													<td colspan="6" class="text-right bold">Total</td>
													<td><div class="text-right bold" id="total"><?=formatrupiah($total)?></div></td>
												</tr>
												<tr>
													<td colspan="5" class="text-right bold">Diskon(%)</td>
													<td class="text-right"><?=$form_data[0]['diskon']?> %</td>
													<td class="text-right"><?=formatrupiah(($form_data[0]['diskon']/100)*$total)?></td>
												</tr>
												<tr>
													<td colspan="5" class="text-right bold">PPN(%)</td>
													<td class="text-right"><?=$form_data[0]['pph']?> %</td>
													<td class="text-right"><?=formatrupiah(($form_data[0]['pph']/100)*$total)?></td>
												</tr>
												<tr>
													<td colspan="6" class="text-right bold">Grand Total PO</td>
													<td class="text-right bold" id="grand_tot"><?=formatrupiah($grand_total)?></td>
												</tr>
												<tr>
													<td colspan="5" class="text-right bold">DP(%)</td>
													<td class="text-right"><?=$form_data[0]['dp']?> %</td>
													<td class="text-right"><?=formatrupiah(($form_data[0]['dp']/100)*$total)?></td>
												</tr>
												<tr class="hidden">
													<td colspan="6" class="text-right bold">Sisa Bayar</td>
													<td class="text-right bold"><?=formatrupiah($form_data[0]['sisa_bayar'])?></td>
												</tr>
												<tr>
													<td colspan="6" class="text-right bold">Biaya Tambahan</td>
													
													<td class="text-right" id="biaya_tambahan_po"><a href="<?=base_url()?>keuangan/pembayaran_transaksi/view_biaya/<?=$pk_value?>" data-target="#modal_biaya" data-toggle="modal" id="biaya_tambahan_po"><?=formatrupiah($form_data[0]['biaya_tambahan'])?></a></td>
												</tr>
												<tr>
													<td colspan="6" class="text-right bold">Grand Total Setelah Biaya</td>
													<td class="text-right bold" id="grand_tot_biaya"><?=formatrupiah($grand_total + $form_data[0]['biaya_tambahan'])?></td>
												</tr>
												<tr>
													<td colspan="5" class="text-right bold">TOTAL INVOICE</td>
													<td colspan="2" class="text-right bold" id="label_total_invoice"><?=formatrupiah($total_invoice)?></td>
												</tr>
											</tfoot>
										</table>

										<input class="form-control hidden" readonly value="<?=$total?>" id="tot_hidden" name="tot_hidden">
										<input class="form-control hidden" id="ppn_hidden" name="ppn_hidden" value="<?=$form_data[0]['pph']?>">
										<input class="form-control text-right hidden" id="disk_hidden" name="disk_hidden" value="<?=($form_data[0]['diskon'] / 100) * $total?>">
										
										<input class="form-control hidden" id="biaya_tambah_hidden" name="biaya_tambah_hidden" value="<?=$form_data[0]['biaya_tambahan']?>">

										<input class="form-control hidden" id="grand_tot_hidden" name="grand_tot_hidden" value="<?=$grand_total?>">
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
		<div class="col-md-12">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject"><?=translate("Daftar Invoice", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body table-scrollable">
				    <div class="form-body">
						<?php
							$form_upload_bon = '';
							$i = 0;
							$total_biaya = 0;

							if(count($data_bayar_detail_po) != 0){
								foreach ($data_bayar_detail_po as $key => $bon) {

									$bank_supp = $this->supplier_bank_m->get_by(array('id' => $bon['supplier_bank_id']),true);
									$nama_supplier_bank = (count($bank_supp) == 0)?'':$bank_supp->nob.' - '.$bank_supp->acc_number.' a/n'.$bank_supp->acc_name.' Cabang: '.$bank_supp->cabang_bank;

									$data_bank = $this->bank_m->get_by(array('id' => $bon['bank_id']),true);
									$biaya = $this->biaya_m->get_by(array('id' => $bon['biaya_id']), true);
									$nama_biaya = '-';
									$jml_biaya = '-';
									if(count($biaya) != 0){
										$nama_biaya = $biaya->nama;
										$jml_biaya = formatrupiah($bon['jumlah_biaya']);
										$total_biaya = $total_biaya + $bon['jumlah_biaya'];
									}

									$tipe = '';
									$identitas = '';
									if($bon['pembayaran_tipe'] == 1){
										$tipe = 'Cek';
										$identitas = '<div class="form-group">
														<div class="col-md-12"><label>No. Cek : '.$bon['nomor_tipe'].'</label></div>
														<div class="col-md-12"><label>Penerima : '.$bon['penerima'].'</label></div>
													</div>';
									}if($bon['pembayaran_tipe'] == 2){
										$tipe = 'Giro';
										$identitas = '<div class="form-group">
														<div class="col-md-12">
															<label>Bank : '.$nama_supplier_bank.'</label>	
														</div>
														<div class="col-md-12">
															<label>No. Giro : '.$bon['nomor_tipe'].'</label>	
														</div>
													</div>';
									}if($bon['pembayaran_tipe'] == 3){
										$tipe = 'Transfer';
										$identitas = '<div class="form-group">
														<div class="col-md-12">
															<label>Bank : '.$nama_supplier_bank.'</label>	
														</div>
														<div class="col-md-12">
															<label>No. Rekening : '.$bon['nomor_tipe'].'</label>	
														</div>
													</div>';
									}

									$form_upload_bon .= '<tr id="item_row_'.$i.'">
									<td><a class="fancybox-button" title="'.$bon['url'].'" href="'.config_item('site_img_bayar').$data_bayar['id'].'/'.$bon['url'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bayar').$data_bayar['id'].'/'.$bon['url'].'" alt="Smiley face" class="img-responsive" ></a></td>
									<td style="vertical-align: top !important;"><input type="hidden" id="id_invoice_'.$i.'" name="invoice['.$i.'][id]" value="'.$bon['id'].'"><input type="hidden" id="id_bayar_invoice_'.$i.'" name="invoice['.$i.'][id_bayar]" value="'.$bon['id_pembelian_invoice'].'"><input type="hidden" id="id_po_invoice_'.$i.'" name="invoice['.$i.'][id_po]" value="'.$bon['pembelian_id'].'">'.$bon['no_invoice'].'</td>
									<td style="vertical-align: top !important;"><input type="hidden" id="no_invoice_invoice_'.$i.'" name="invoice['.$i.'][no_invoice]" value="'.$bon['no_invoice'].'">'.date('d M Y', strtotime($bon['tgl_invoice'])).'</td>
									<td style="vertical-align: top !important;">'.$tipe.'</td>
									<td style="vertical-align: top !important;">'.$identitas.'</td>
									<td style="vertical-align: top !important;"><input type="hidden" id="bank_id_invoice_'.$i.'" name="invoice['.$i.'][bank_id]" value="'.$bon['bank_id'].'">'.$data_bank->nob.' - '.$data_bank->acc_number.'</br>'.$data_bank->acc_name.'</td>
									<td style="vertical-align: top !important;">'.date('d M Y', strtotime($bon['tanggal'])).'</td>
									<td style="vertical-align: top !important;">'.date('d M Y', strtotime($bon['jatuh_tempo'])).'</td>
									<td style="vertical-align: top !important;" style="width:150px"><div class="form-group">
					                        <label class="col-md-12">'.translate("Jenis Biaya", $this->session->userdata("language")).' : '.$nama_biaya.'</label>
					                        <input type="hidden" id="jns_biaya_invoice_'.$i.'" name="invoice['.$i.'][jns_biaya]" value="'.$bon['bank_id'].'">
					                    </div>
					                    <div class="form-group">
					                        <label class="col-md-12">'.translate("Jumlah Biaya", $this->session->userdata("language")).' : '.$jml_biaya.'</label>
					                        <input type="hidden" id="jml_biaya_invoice_'.$i.'" name="invoice['.$i.'][jml_biaya]" value="'.$bon['jumlah_biaya'].'">
					                    </div>
					                </td>
									<td style="vertical-align: top !important;" class="text-right">'.formatrupiah($bon['total_invoice']).'<input type="hidden" id="total_invoice_'.$i.'" name="invoice['.$i.'][total_invoice]" value="'.$bon['total_invoice'].'"></td>
									<td style="vertical-align: top !important;">'.$bon['keterangan_inv'].'</td>
									
									</tr>';

									$i++;
								}	
							}
							
							?>
						<table class="table table-bordered table-hover" id="table_invoice">
							<thead>
							<tr role="row" class="heading">
								<th class="text-center" width="5%">
							 		Image
								</th>
								<th class="text-center" width="8%">
									No. Invoice
								</th>
								<th class="text-center" width="8%">
									Tgl. Invoice
								</th>
								<th class="text-center" width="1%">
									Jenis Bayar
								</th>
								<th class="text-center" width="20%">
									Identitas
								</th>
								<th class="text-center" width="10%">
									Bank
								</th>
								<th class="text-center" width="10%">
									Tgl Buat
								</th>
								<th class="text-center" width="9%">
									Jatuh Tempo
								</th>
								<th class="text-center" width="150px">
									Biaya
								</th>
								<th class="text-center" width="10%">
									Total Invoice
								</th>
								<th class="text-center" width="15%">
									Keterangan
								</th>
								
							</tr>
							</thead>
							<tbody>
								<?=$form_upload_bon?>
							</tbody>
							<tfoot>
								<?php
									$y = 0;
									$tr = '';
									foreach ($data_bayar_detail_tipe_po as $key => $bon) {
										$tipe = '';
										$identitas = '';
										$data_bank = $this->bank_m->get_by(array('id' => $bon['bank_id']),true);
										if($bon['pembayaran_tipe'] == 1){
											$tipe = 'Cek';
											
										}if($bon['pembayaran_tipe'] == 2){
											$tipe = 'Giro';
											
										}if($bon['pembayaran_tipe'] == 3){
											$tipe = 'Transfer';
											$identitas = '<div class="form-group">
															<div class="col-md-12">
																<label>Bank : '.$nama_supplier_bank.'</label>	
															</div>
															<div class="col-md-12">
																<label>No. Rekening : '.$bon['nomor_tipe'].'</label>	
															</div>
														</div>';
										}
									$tr .= '<tr>
												<th colspan="8" class="text-right" id="th_jenis_bayar_'.$y.'">'.$tipe.'</th>
												<th class="text-right" id="th_bank_bayar_'.$y.'">'.$data_bank->nob.' - '.$data_bank->acc_number.'</th>
												<th class="text-right" id="th_no_cek_bayar_'.$y.'">'.$bon['nomor_tipe'].'</th>
												<th class="text-right" id="th_total_cek_bayar_'.$y.'">'.formatrupiah($bon['jumlah']).'</th>
											</tr>';
								
									$y++;
									}

								echo $tr;
								?>
								<tr>
									<th colspan="9" class="text-right">Total Penagihan Invoice</th>
									<th colspan="2" class="text-right"><?=formatrupiah($total_invoice)?></th>
								</tr>
								<tr>
									<th colspan="9" class="text-right">Total Biaya Tambahan</th>
									<th colspan="2" class="text-right" id="biaya_tambahan"><?=formatrupiah($total_biaya)?></th>
								</tr>
								<tr>
									<th colspan="9" class="text-right"><input type="hidden" class="form-control" id="total_bayar" name="total_bayar" value="<?=$total_invoice?>">Total Pembayaran</th>
									<th colspan="2" class="text-right" id="th_total_bayar"><?=formatrupiah($total_invoice)?></th>

								</tr>
								<tr>
									<th colspan="11" class="text-left" id="th_terbilang">Terbilang : # <?=terbilang($total_invoice)?> #</th>
								</tr>
							</tfoot>
						</table>    
						
					</div>
                </div>
			</div>
		</div>
	</div>
</div>	
<?=form_close()?>

<div id="popover_bank_supplier_content" class="row" style="display:none;">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_bank">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('Bank', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Atas Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No. Rekening', $this->session->userdata('language'))?></div></th>
                    <th width="1%"><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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

