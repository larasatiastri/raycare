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

    echo form_open(base_url()."pembelian/pembelian_alkes/save", $form_attr, $hidden);
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
			    'name'        => 'items['.$i.'][item_diskon]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			    'value' => $detail['diskon'],
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
			    'class' => 'form-control text-right',
			    'value' => $detail['jumlah_pesan'],
			);

			$attrs_item_total_db = array(
			    'id'          => 'items_total_'.$i,
			    'name'        => 'items['.$i.'][item_total]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			    'value' => $detail['jumlah_disetujui']*$detail['harga_beli'],

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

			$total = $total + (($detail['harga_beli'] - (($detail['diskon']/100)*$detail['harga_beli'])) * $detail['jumlah_disetujui']);

			$satuan_item = $this->item_satuan_m->get($detail['item_satuan_id']);

			$jml_diterima = '';
			$diterima_detail = $this->pmb_po_detail_m->get_data_diterima($detail['id']);
			// die(dump($this->db->last_query()));
            if(count($diterima_detail)){
            	
                $jml_diterima = '';
                foreach ($diterima_detail as $diterima) {
                   $jml_diterima .= $diterima['jumlah'].' '.$diterima['nama_satuan'].', '; 

                }

            }
            $jml_diterima = rtrim($jml_diterima,', ');


            if($detail['jumlah_belum_diterima'] == 0){
            	$style = 'success';
            }

			$item_cols_db = array(// style="width:156px;
				'item_kode'          => '<label class="control-label" name="items['.$i.'][item_kode]" style="text-align : left !important; width : 150px !important;">'.$detail['kode'].'</label>'.form_input($attrs_item_id_db).form_input($attrs_item_kode_db),
				'item_name'          => '<label class="control-label" name="items['.$i.'][item_nama]">'.$detail['nama'].'</label>'.form_input($attrs_item_nama_db).form_input($attrs_id_db),
				
				'item_harga'         => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_harga]">'.formatrupiah($detail['harga_beli']).'</label></div>'.form_input($attrs_item_harga_db),
				'item_diskon'        => '<label class="control-label" name="items['.$i.'][item_diskon]">'.$detail['diskon'].' %</label>'.form_input($attrs_item_diskon_db),
				'item_jumlah'        => '<label class="control-label" name="items['.$i.'][jumlah_pesan]">'.$detail['jumlah_disetujui'].' </label>',
				'item_satuan'        => '<label class="control-label" name="items['.$i.'][item_satuan]">'.$satuan_item->nama.'</label>',
				'item_jumlah_terima'        => '<label class="control-label" name="items['.$i.'][jumlah_terima]"><a class="jumlah_terima" data-toggle="modal" data-target="#popup_modal_jumlah_terima" href="'.base_url().'pembelian/pembelian_alkes/modal_jumlah_terima/'.$detail['id'].'/'.$detail['item_id'].'/'.$detail['item_satuan_id'].'">'.$jml_diterima.'</a> </label>',
				'item_total'         => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_total]">'.formatrupiah(($detail['harga_beli'] - (($detail['diskon']/100)*$detail['harga_beli'])) * $detail['jumlah_disetujui']).'</label>'.form_input($attrs_item_total_db).'</div>',
			);

			$item_row_template_db .=  '<tr id="item_row_'.$i.'" class="table_item_beli '.$style.'" ><td>' . implode('</td><td>', $item_cols_db) . '</td></tr>';

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

	$msg = translate("Apakah anda yakin akan membuat PO ini?",$this->session->userdata("language"));
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
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-search font-blue-sharp"></i>
			<span class="caption font-blue-sharp bold uppercase"><?=translate("View Pembelian", $this->session->userdata("language"))?> <?=$form_data[0]['no_pembelian']?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<input type="hidden" id="jml_baris" name="jml_baris" value="<?=$i?>">
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
												<label class="col-md-12 bold"><?=$email_supplier?> </label>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab_penerima">
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
												<label class="col-md-12 bold"><?=$customer_alamat[0]['alamat'].', '.$customer_alamat[0]['nama_kelurahan'].', '.$customer_alamat[0]['kecamatan'].','.$customer_alamat[0]['kabkot'].', '.$customer_alamat[0]['propinsi']?> </label>
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Email", $this->session->userdata("language"))?> :</label>
												<label class="col-md-12 bold"><?=$customer_email->url?> </label>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab_info_pembelian">
										<div class="form-body">
											<div class="form-group">
												<label class="col-md-12"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> :</label>
												<label class="col-md-12 bold"><?=date('d M Y', strtotime($form_data[0]['tanggal_pesan']))?></label>
												
											</div>
											<div class="form-group">
												<label class="col-md-12"><?=translate("Pengiriman Satu Tanggal", $this->session->userdata("language"))?> :</label>
												<?php 
													$check_ya = '';
													$hidden = '';

													if($form_data[0]['is_single_kirim'] == 1){
														$check_ya = 'Ya';
														$hidden = '';
													}if($form_data[0]['is_single_kirim'] == 0){
														$check_ya = 'Tidak';
														$hidden = 'hidden';
													}
												?>
												<label class="col-md-12 bold"><?=$check_ya?></label>
											</div>
											<div class="form-group <?=$hidden?>" id="tgl_kirim">
												<label class="col-md-12"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?> :</label>
												<label class="col-md-12 bold"><?=date('d M Y', strtotime($form_data[0]['tanggal_kirim']))?></label>
												
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
								</div>
							</div>
							
						</div>
					</div>
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-file-pdf-o"></i>
								<span class="caption-subject"><?=translate("Penawaran", $this->session->userdata("language"))?></span>
							</div>						
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
										if(count($data_penawaran))
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
										}else{
											?>
											<tr>
												<td>Tidak ada penawaran untuk pembelian ini</td>
											</tr>
											<?php
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
							<i class="fa fa-cubes"></i>
								<span class="caption-subject"><?=translate("Detail Item", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="portlet-body">
							<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
							<div class="table-scrollable">
								
							
							<table class="table table-striped table-bordered table-hover" id="table_detail_pembelian">
								<thead>
									<tr>
										<th class="text-center" style="width : 20px !important;"><?=translate("Kode", $this->session->userdata("language"))?> </th>
										<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
										
										<th class="text-center"style="width : 120px !important;"><?=translate("Harga Sistem", $this->session->userdata("language"))?> </th>
										<th class="text-center"><?=translate("Diskon", $this->session->userdata("language"))?> </th>
										<th class="text-center" style="width : 150px !important;"><?=translate("Jumlah Pesan", $this->session->userdata("language"))?> </th>
										<th class="text-center"style="width : 150px !important;"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
										<th class="text-center" style="width : 150px !important;"><?=translate("Jumlah Diterima", $this->session->userdata("language"))?> </th>
										<th class="text-center"style="width : 250px !important;"><?=translate("Sub Total", $this->session->userdata("language"))?> </th>
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
									$biaya_tambahan = $form_data[0]['biaya_tambahan'];
									$grand_total = ($total - $diskon);
									$pph = ($form_data[0]['pph']/100) * $grand_total;
									$grand_total_after_tax = ($total - $diskon) + $pph;
									$grand_total_non_biaya = ($total - $diskon) + $pph +$biaya_tambahan;
								?>
								<tfoot>
									<tr>
										<td colspan="7" class="text-right bold">Total</td>
										<td><div class="text-right bold"><?=formatrupiah($total)?></div></td>
									</tr>
									<tr>
										<td colspan="6" class="text-right bold">Diskon(%)</td>
										<td class="text-right"><?=$form_data[0]['diskon']?> %</td>
										<td class="text-right"><?=formatrupiah(($form_data[0]['diskon']/100)*$total)?></td>
									</tr>
									<tr>
										<td colspan="7" class="text-right bold">Total Setelah Diskon</td>
										<td><div class="text-right bold"><?=formatrupiah($grand_total)?></div></td>
									</tr>
									<tr>
										<td colspan="6" class="text-right bold">PPN(%)</td>
										<td class="text-right"><?=$form_data[0]['pph']?> %</td>
										<td class="text-right"><?=formatrupiah(($form_data[0]['pph']/100)*$grand_total)?></td>
									</tr>
									<tr>
										<td colspan="7" class="text-right bold">Total Setelah PPN</td>
										<td><div class="text-right bold"><?=formatrupiah($grand_total_after_tax)?></div></td>
									</tr>
									<tr>
										<td colspan="7" class="text-right bold">Grand Total</td>
										<td class="text-right bold"><?=formatrupiah($grand_total_non_biaya)?></td>
									</tr>
									<tr>
										<td colspan="6" class="text-right bold">DP(%)</td>
										<td class="text-right"><?=$form_data[0]['dp']?> %</td>
										<td class="text-right"><?=formatrupiah(($form_data[0]['dp']/100)*$grand_total_non_biaya)?></td>
									</tr>
									<tr>
										<td colspan="6" class="text-right bold">Biaya Tambahan</td>
										<td class="text-left"><?=($form_data[0]['ket_tambahan'] == '')?'-':$form_data[0]['ket_tambahan']?></td>
										<td class="text-right"><?=formatrupiah($form_data[0]['biaya_tambahan'])?></td>
									</tr>
									<tr>
										<td colspan="7" class="text-right bold">Grand Total Setelah Biaya</td>
										<td class="text-right bold"><?=formatrupiah($grand_total_non_biaya)?></td>
									</tr>
								</tfoot>
							</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-truck"></i>
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
								
									<table class="table table-striped table-bordered table-hover" >
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
		</div>
		
		<?=form_close()?>
	</div>
</div>

<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_terima" role="basic" aria-hidden="true">
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

