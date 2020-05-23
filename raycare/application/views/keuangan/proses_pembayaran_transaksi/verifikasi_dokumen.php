<?php
	$form_attr = array(
	    "id"            => "form_proses_po", 
	    "name"          => "form_proses_po", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "verifikasi",
        "pembelian_id" => $pk_value,
        "no_pembelian" => $form_data[0]['no_pembelian']
    );

    echo form_open(base_url()."keuangan/proses_pembayaran_transaksi/save", $form_attr, $hidden);
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
				'item_jumlah_setujui'        => '<label class="control-label inline-button-table" name="items['.$i.'][jumlah_setujui]">'.$detail['jumlah_disetujui'].' '.$satuan_item->nama.' </label>'.form_input($attrs_jumlah_setuju_db).form_input($attrs_item_satuan_db).form_input($attrs_item_is_active_db),
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
	$msg_draft= translate("Apakah anda yakin akan menyimpan PO ini ke Draft?", $this->session->userdata("language"));

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

	$data_bank = $this->bank_m->get_by(array('is_active' => 1));

	$bank_option = array();

	foreach ($data_bank as $bank) 
	{
		$bank_option[$bank->id] = $bank->nob.' A/C No.'. $bank->acc_number;
	}

	$biaya_option = array(
		''	=> translate('Pilih', $this->session->userdata('language')).'...'
	);

	$biaya = $this->biaya_m->get_by(array('is_active' => 1));

	foreach ($biaya as $row) {
		$biaya_option[$row->id] = $row->nama;
	}

	$colspan = 4;

	$form_option_payment = '<div class="form-group">
					    	<div class="col-md-12">
					    			<select class="form-control payment_type" name="payment_type" id="payment_type]">
									  <option value="1">Cek</option>
									  <option value="2">Giro</option>
									  <option value="3">Transfer</option>
									</select>
					    		
					     	</div>
					    </div>
					    <div class="form-group">
							<div class="col-md-12">
								<div class="input-group">
									<span class="input-group-addon">Rp.
									</span>
									<input type="number" min="0" class="form-control text-right col-md-2 payment_cek_ID_0 payment_cek" id="nominal" name="nominal" value="0">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">';
	$form_option_payment .=	 form_dropdown('bank_id', $bank_option, '','id="bank_id" class="form-control"');		
	$form_option_payment .=	'</div>
						</div>
						
						<div id="section_1" class="hidden">
							<div class="form-group">
								<div class="col-md-12">
									<input type="text"class="form-control text-right col-md-2 payment_cek_ID_0 payment_cek" id="bank_no_cek" name="bank_no_cek" placeholder="Nomor Cek">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<input type="text"class="form-control text-right col-md-2 payment_penerimacek_ID_0 payment_cek" id="bank_penerima_cek" name="bank_penerima_cek" placeholder="Penerima">
								</div>
							</div>
							
					    </div>
					    <div id="section_2" class="hidden">
							<div class="form-group">
								<div class="col-md-12">
									<div class="input-group"><input type="text" name="bank_supp_name" value="" id="bank_name_paymen_0" class="form-control" placeholder="Bank"><span class="input-group-btn"><input type="hidden" name="bank_supp_id" value="5" id="bank_id_paymen_0" class="form-control"><a class="btn btn-primary search-bank" data-status-row="item_row_add" title="" data-original-title="Pilih Bank"><i class="fa fa-search"></i></a></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<input type="text" class="form-control text-right col-md-2 payment_giro_ID_0 payment_giro" id="bank_no_giro" name="bank_no_giro" placeholder="Nomor Giro">
								</div>
							</div>
					    </div>
					    <div id="section_3" class="hidden">
					    	<div class="form-group">
								<div class="col-md-12">
									<div class="input-group"><input type="text" name="bank_supp_name" value="" id="bank_name_paymen_0" class="form-control" placeholder="Bank"><span class="input-group-btn"><input type="hidden" name="bank_supp_id" value="5" id="bank_id_paymen_0" class="form-control"><a class="btn btn-primary search-bank" data-status-row="item_row_add" title="" data-original-title="Pilih Bank"><i class="fa fa-search"></i></a></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<input type="text" class="form-control text-right col-md-2 payment_tt_ID_0 payment_tt" id="bank_supp_nomor" name="bank_supp_nomor" placeholder="No. Rekening">
								</div>
							</div>
					    </div>

					    <div class="form-group">
							<label class="col-md-12">No. Rekening Koran :</label>
							<div class="col-md-12">
								<input type="text" class="form-control" id="nomor_rk" name="nomor_rk">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12">No. Voucher :</label>
							<div class="col-md-12">
								<input type="text" class="form-control" id="nomor_voucher" name="nomor_voucher">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12">Tanggal Buat :</label>
							<div class="col-md-12">
								<div class="input-group date">
									<input type="text" class="form-control" id="tanggal_payment_0" name="tanggal" value="'.date('d M Y').'" readonly>
									<span class="input-group-btn">
										<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12">Jatuh Tempo :</label>
							<div class="col-md-12">
								<div class="input-group date">
									<input type="text" class="form-control" id="jatuh_tempo_payment_0" name="jatuh_tempo" value="'.date('d M Y').'" readonly>
									<span class="input-group-btn">
										<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12">Biaya Tambahan :</label>
							<div class="col-md-12">';
	$form_option_payment .=	 form_dropdown('biaya_id', $biaya_option, '','id="biaya_id" class="form-control"');
	$form_option_payment .=	'</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<input type="number" min="0" class="form-control text-right col-md-2 jml_biaya_ID_0 jml_biaya" id="nominal_biaya" name="nominal_biaya" placeholder="Nominal Biaya">
							</div>
						</div>';
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption font-blue-sharp bold uppercase"><?=$form_data[0]['no_pembelian']?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<input type="hidden" id="jml_baris" name="jml_baris" value="<?=$i?>">
			<a id="confirm_reject" class="btn btn-circle red-intense" href="<?=base_url()?>keuangan/proses_pembayaran_transaksi/tolak_dokumen/<?=$pk_value?>" data-toggle="modal" data-target="#popup_modal_proses">
	        	<i class="fa fa-times"></i>
	        	<!-- <i class="fa fa-floppy-o"></i> -->
	        	Tolak
	        </a>
			<a id="confirm_save" class="btn btn-primary btn-circle" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Setujui", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
	        <a id="confirm_save_draft" class="btn btn-primary btn-circle hidden" href="#" data-confirm="<?=$msg_draft?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan ke Draft", $this->session->userdata("language"))?></a>

	    </div>
	</div>
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
												$tad = $total - $diskon;
												$pph = ($form_data[0]['pph']/100)*$tad;

												$grand_total = ($tad + $pph) ;
												$grand_total_pph = ($tad + $pph) - $form_data[0]['pph_23_nominal'] ;
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
													<td class="text-right"><?=formatrupiah($pph)?></td>
												</tr>
												<tr>
													<td colspan="6" class="text-right bold">Total Setelah PPN</td>
													<td class="text-right bold" id="grand_tot"><?=formatrupiah($grand_total)?></td>
												</tr>
												<tr>
													<td colspan="5" class="text-right bold">PPH 23(%)</td>
													<td class="text-right"><?=$form_data[0]['pph_23']?> %</td>
													<td class="text-right"><?=formatrupiah($form_data[0]['pph_23_nominal'])?></td>
												</tr>
												<tr>
													<td colspan="6" class="text-right bold">Grand Total PO</td>
													<td class="text-right bold" id="grand_tot"><?=formatrupiah($grand_total_pph)?></td>
												</tr>
												<tr>
													<td colspan="5" class="text-right bold">DP(%)</td>
													<td class="text-right"><?=$form_data[0]['dp']?> %</td>
													<td class="text-right"><?=formatrupiah(($form_data[0]['dp']/100)*$grand_total_pph)?></td>
												</tr>
												<tr class="hidden">
													<td colspan="6" class="text-right bold">Sisa Bayar</td>
													<td class="text-right bold"><?=formatrupiah($form_data[0]['sisa_bayar'])?></td>
												</tr>
												<tr>
													<td colspan="6" class="text-right bold">Biaya Tambahan</td>
													
													<?php
														$biaya_tambahan = ($form_data[0]['biaya_tambahan'] == 0)?formatrupiah($form_data[0]['biaya_tambahan']):'<a href="'.base_url().'keuangan/proses_pembayaran_transaksi/view_biaya/'.$pk_value.'" data-target="#modal_biaya" data-toggle="modal" id="biaya_tambahan_po">'.formatrupiah($form_data[0]['biaya_tambahan']).'</a>';
													?>
													<td class="text-right" id="biaya_tambahan_po"><?=$biaya_tambahan?></a></td>
												</tr>
												<tr>
													<td colspan="6" class="text-right bold">Grand Total Setelah Biaya</td>
													<td class="text-right bold" id="grand_tot_biaya"><?=formatrupiah($grand_total_pph + $form_data[0]['biaya_tambahan'])?></td>
												</tr>
												<tr>
													<td colspan="5" class="text-right bold">TOTAL INVOICE</td>
													<td colspan="2" class="text-right bold" id="label_total_invoice"><?=formatrupiah($total_invoice)?></td>
												</tr>
											</tfoot>
										</table>

										<input class="form-control hidden" readonly value="<?=$total?>" id="tot_hidden" name="tot_hidden">
										<input class="form-control hidden" id="ppn_hidden" name="ppn_hidden" value="<?=$form_data[0]['pph']?>">
										<input class="form-control hidden" id="pph_23_persen_hidden" name="pph_23_persen_hidden" value="<?=$form_data[0]['pph_23']?>">
										<input class="form-control hidden" id="pph_23_nominal_hidden" name="pph_23_nominal_hidden" value="<?=$form_data[0]['pph_23_nominal']?>">
										<input class="form-control text-right hidden" id="disk_hidden" name="disk_hidden" value="<?=($form_data[0]['diskon'] / 100) * $total?>">
										
										<input class="form-control hidden" id="biaya_tambah_hidden" name="biaya_tambah_hidden" value="<?=$form_data[0]['biaya_tambahan']?>">

										<input class="form-control hidden" id="grand_tot_hidden" name="grand_tot_hidden" value="<?=$grand_total_pph?>">
										<input class="form-control hidden" id="grand_tot_biaya_hidden" name="grand_tot_biaya_hidden" value="<?=$grand_total_pph + $form_data[0]['biaya_tambahan']?>">
										<input class="form-control hidden" id="depe" name="depe" value="<?=$form_data[0]['dp']?>">
										<input class="form-control hidden" id="sisa_nya" name="sisa_nya" value="<?=$grand_total_pph-$form_data[0]['dp']?>">
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
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject"><?=translate("Daftar Invoice", $this->session->userdata("language"))?></span>
						</div>
					</div>
					<div class="portlet-body">
					    <div class="form-body">
					    	<div class="table-scrollable">
								<?php
									$form_upload_bon = '';
									$i = 0;
									
									if(count($data_invoice) != 0){
										foreach ($data_invoice as $key => $bon) {

											$attrs_identitas_bank_id = array(
												'id'    => 'bank_id_invoice_'.$i,
												'name'  => 'invoice['.$i.'][bank_supp_id]',
												'class' => 'form-control',
												'type'	=> 'hidden'
											);

											$attrs_identitas_bank_name = array(
												'id'          => 'bank_name_invoice_'.$i,
												'name'        => 'invoice['.$i.'][bank_supp_name]',
												'class'       => 'form-control',
												'placeholder' => translate('Bank', $this->session->userdata('language'))
											);

											$attrs_identitas_bank_nomor = array(
												'id'    => 'bank_nomor_invoice_'.$i,
												'name'  => 'invoice['.$i.'][bank_supp_nomor]',
												'class' => 'form-control',
												'data-index' => $i,
												'placeholder' => translate('No. Rekening', $this->session->userdata('language'))
											);

											$attrs_identitas_bank_no_giro = array(
												'id'    => 'bank_no_giro_invoice_'.$i,
												'name'  => 'invoice['.$i.'][bank_no_giro]',
												'class' => 'form-control',
												'data-index' => $i,
												'placeholder' => translate('No. Giro', $this->session->userdata('language'))
											);

											$attrs_identitas_bank_no_cek = array(
												'id'    => 'bank_no_cek_invoice_'.$i,
												'name'  => 'invoice['.$i.'][bank_no_cek]',
												'class' => 'form-control',
												'data-index' => $i,
												'placeholder' => translate('No. Cek', $this->session->userdata('language'))
											);

											$attrs_identitas_bank_penerima_cek = array(
												'id'    => 'bank_penerima_cek_invoice_'.$i,
												'name'  => 'invoice['.$i.'][bank_penerima_cek]',
												'class' => 'form-control',
												'placeholder' => translate('Penerima', $this->session->userdata('language'))
											);

											$form_upload_bon .= '<tr id="item_row_'.$i.'">
											<td class="text-center"><a class="fancybox-button fa fa-file" title="'.$bon['url'].'" href="'.config_item('site_img_bayar').$data_bayar['id'].'/'.$bon['url'].'" data-rel="fancybox-button"></a></td>
											<td style="vertical-align: top !important;"><input type="hidden" id="id_invoice_'.$i.'" name="invoice['.$i.'][id]" value="'.$bon['id'].'"><input type="hidden" id="id_po_invoice_'.$i.'" name="invoice['.$i.'][id_po]" value="'.$bon['pembelian_id'].'">'.$bon['no_invoice'].'</td>
											<td style="vertical-align: top !important;">'.date('d M Y', strtotime($bon['tgl_invoice'])).'</td>
											
											<td class="text-right" style="vertical-align: top !important;"><input type="hidden" name="invoice['.$i.'][total_invoice]" id="total_invoice_'.$i.'" value="'.$bon['total_invoice'].'">'.formatrupiah($bon['total_invoice']).'</td>';

											if($form_data[0]['is_pkp'] == 1){
												$form_upload_bon .= '<td><a class="fancybox-button" title="'.$bon['url_faktur_pajak'].'" href="'.config_item('site_img_bayar').$data_bayar['id'].'/'.$bon['url_faktur_pajak'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bayar').$data_bayar['id'].'/'.$bon['url_faktur_pajak'].'" alt="Smiley face" class="img-responsive" ></a></td>';
											}
											
											$form_upload_bon .= '<td style="vertical-align: top !important;">'.$bon['keterangan'].'</td>
											</tr>';

											$i++;
										}	
									}
									
									?>
											<!-- <td><a class="fancybox-button" title="'.$bon['url'].'" href="'.config_item('site_img_bayar').$data_bayar['id'].'/'.$bon['url'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bayar').$data_bayar['id'].'/'.$bon['url'].'" alt="Smiley face" class="img-responsive" ></a></td> -->

								<table class="table table-bordered table-hover" id="table_invoice">
									<thead>
									<tr role="row" class="heading">
										<th class="text-center" width="1%">
									 		Inv
										</th>
										<th class="text-center" width="8%">
											No. Invoice
										</th>
										<th class="text-center" width="8%">
											Tgl. Invoice
										</th>
										<th class="text-center" width="10%">
											Total Invoice
										</th>
										<?php 
											if($form_data[0]['is_pkp'] == 1){
												$colspan = 5;
											?>
										<th class="text-center" width="8%">
											Faktur Pajak
										</th>
											<?php
											}
										?>
										<th class="text-center" width="25%">
											Keterangan
										</th>
									</tr>
									</thead>
									<tbody>
										<?=$form_upload_bon?>
									</tbody>
									<tfoot>
										
										<tr>
											<th colspan="<?=$colspan?>" class="text-right">Total Penagihan Invoice</th>
											<th colspan="1" class="text-right"><?=formatrupiah($total_invoice)?></th>
										</tr>
										<tr>
											<th colspan="<?=$colspan?>" class="text-right">Total Biaya Tambahan</th>
											<th colspan="1" class="text-right" id="biaya_tambahan"></th>
										</tr>
										<tr>
											<th colspan="<?=$colspan?>" class="text-right"><input type="hidden" class="form-control" id="total_bayar" name="total_bayar" value="<?=$total_invoice?>">Total Pembayaran</th>
											<th colspan="1" class="text-right" id="th_total_bayar"><?=formatrupiah($total_invoice)?></th>

										</tr>
										<tr>
											<th colspan="14" class="text-left" >Terbilang : # <?=terbilang($total_invoice)?> Rupiah #</th>
										</tr>
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

