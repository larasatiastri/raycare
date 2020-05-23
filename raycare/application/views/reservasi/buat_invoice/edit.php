<?php
	$form_attr = array(
	    "id"            => "form_edit_invoice", 
	    "name"          => "form_edit_invoice", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
		"command"       => "edit",
		"id"            => $pk_value
    );

    echo form_open(base_url()."reservasi/buat_invoice/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$pasien = $this->pasien_m->get($form_detail['pasien_id']);

	$check_bpjs = '';
	$check_umum = '';
	$check_bpjs_umum = '';

	if($form_detail['penjamin_id'] == 1)
	{
		$check_bpjs = '';
		$check_umum = 'checked="checked"';
		$check_bpjs_umum = '';
	}
	if($form_detail['penjamin_id'] == 2)
	{
		$check_bpjs = 'checked="checked"';
		$check_umum = '';
		$check_bpjs_umum = '';
	}
	if($form_detail['penjamin_id'] == 3)
	{
		$check_bpjs = '';
		$check_umum = '';
		$check_bpjs_umum = 'checked="checked"';
	}

	$level_id = $this->session->userdata('level_id');


	$act_trdaftar = 'btn-primary';
	$act_tdk_trdaftar = 'btn-default';
	$hidden = '';
	$hidden_tgl = 'hidden="hidden"';
	$no_member = '';
	$id_pasien = '';
	$readonly = '';

	if($level_id == 1 || $level_id == 46){
		$hidden_tgl = '';
	}
	if($form_detail['tipe_pasien'] == 1 || $form_detail['tipe_pasien'] == 0)
	{
		$act_trdaftar = 'btn-primary';
		$act_tdk_trdaftar = 'btn-default';
		$hidden = '';
		$no_member = $pasien->no_member;
		$id_pasien = $pasien->id;
		$readonly = 'readonly';
	}
	else
	{
		$act_trdaftar = 'btn-default';
		$act_tdk_trdaftar = 'btn-primary';
		$hidden = 'hidden';
	}
?>


<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Edit Invoice', $this->session->userdata('language'))?></span>
		</div>

	</div>
	<div class="note note-danger note-bordered">
		<p>
			 NOTE: Mulai tanggal 2 April 2018, Heparin tidak lagi di cantumkan kedalam invoice, Harga paket hemodialisa menjadi Rp. 1.050.000(Single) dan Rp. 850.000(Reuse) untuk pengguna BPJS.
		</p>
	</div>
	<div class="portlet-body form">
	<div class="row">
		<div class="col-md-3">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Informasi', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
				<div class="form-body" id="section-diagnosis">
					<div class="form-group">
		       	       	<div class="col-md-12">
          					<div class="btn-group btn-group-justified">
								<a id="btn_terdaftar" class="btn <?=$act_trdaftar?>">
									<?=translate("Terdaftar", $this->session->userdata("language"))?>
								</a>
								<a id="btn_tidak_terdaftar" class="btn <?=$act_tdk_trdaftar?>">
									<?=translate("Tidak Terdaftar", $this->session->userdata("language"))?>
								</a>
							</div>
		              	</div>
						<input class="form-control hidden" id="tipe_pasien" name="tipe_pasien" value="<?=$form_detail['tipe_pasien']?>" >
	              	</div>

						
						<div class="form-group" <?=$hidden_tgl?>>
						    <label class="col-md-12"><?=translate("Tanggal", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
		                	<div class="col-md-12">
		                  		<div class="input-group date" id="tanggal">
		                    	<input type="text" class="form-control" id="tanggal" name="tanggal" value="<?=date('d-M-Y', strtotime($form_detail['created_date']))?>" readonly required>
		                    		<span class="input-group-btn">
		                    			<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
		                    		</span>
		                    	</div>
		                	</div>
		              	</div>	

						<div class="form-group" <?=$hidden_tgl?>>
							<label class="col-md-12"><?=translate("No. Invoice", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
									<input class="form-control" id="no_invoice" name="no_invoice" value="<?=$form_detail['no_invoice']?>" placeholder="<?=translate("No. Invoice", $this->session->userdata("language"))?>">
							</div>	
						</div>					

						<div class="form-group pasien_terdaftar <?=$hidden?>">
							<label class="col-md-12"><?=translate("No. Rekam Medis", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-12">
								<div class="input-group">
									<input class="form-control" id="no_rekmed" name="no_rekmed" value="<?=$no_member?>" placeholder="<?=translate("No. Rekam Medis", $this->session->userdata("language"))?>" >
									<span class="input-group-btn">
										<a class="btn grey-cascade pilih-pasien" title="<?translate('Pilih Pasien', $this->session->userdata('language'))?>">
											<i class="fa fa-search"></i>
										</a>
									</span>
								</div>
								<input class="form-control hidden" id="id_ref_pasien" name="id_ref_pasien" value="<?=$id_pasien?>"  placeholder="<?=translate("ID Referensi Pasien", $this->session->userdata("language"))?>">
							</div>	
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Pasien", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
									<input class="form-control" id="nama_ref_pasien" name="nama_ref_pasien" value="<?=$form_detail['nama_pasien']?>" readonly  required placeholder="<?=translate("Nama Pasien", $this->session->userdata("language"))?>">
							</div>	
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Penanggung", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<div class="radio-list">
									<label>
									<input type="radio" name="penanggung" id="bpjs" value="1" <?=$check_bpjs?>> BPJS</label>
									<label>
									<input type="radio" name="penanggung" id="umum" value="2" <?=$check_umum?>> Umum </label>
									<label class="hidden">
									<input type="radio" name="penanggung" id="bpjs_umum" value="3" <?=$check_bpjs_umum?>> BPJS & Umum </label>
								</div>
							</div>	
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Jenis Invoice", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
							<div class="col-md-12">
								<?php
									$jenis_option = array(
										'1'			=> translate('Internal', $this->session->userdata('language')),
									);
									echo form_dropdown('jenis_invoice', $jenis_option, $form_detail['jenis_invoice'],'id="jenis_invoice" class="form-control" required ');
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Shift", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
							<div class="col-md-12">
								<?php
									$jenis_option = array(
										''			=> translate('Pilih', $this->session->userdata('language')).'..',
										'1'			=> translate('Shift 1', $this->session->userdata('language')),
										'2'			=> translate('Shift 2', $this->session->userdata('language')),
										'3'			=> translate('Shift 3', $this->session->userdata('language')),
										'4'			=> translate('Shift 4', $this->session->userdata('language')),
									);
									echo form_dropdown('tipe', $jenis_option, $form_detail['shift'],'id="tipe" class="form-control" ');
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Waktu", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
									<input class="form-control" id="waktu" name="waktu" value="<?=$form_detail['waktu_tindakan']?>" required placeholder="<?=translate("Waktu", $this->session->userdata("language"))?>">
							</div>	
						</div>
				
						
						</div>
					</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
		<div class="col-md-9">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Detail Invoice', $this->session->userdata('language'))?>
					</div>
					<div class="actions">
					<a class="btn btn-icon-only btn-default btn-circle add-item">
						<i class="fa fa-plus"></i>
					</a>
				</div>
				</div>
				<div class="portlet-body">
					<?php
						if($form_detail_item)
						{	
							$i = 0;
							$item_row_template_db = '';
							foreach ($form_detail_item as $row) 
							{
								if($row['tipe_item'] == 1)
								{
									$data_item = $this->paket_m->get($row['item_id']);
								}
								if($row['tipe_item'] == 2)
								{
									$data_item = $this->item_m->get($row['item_id']);
								}
								if($row['tipe_item'] == 3)
								{
									$data_item = $this->tindakan_m->get($row['item_id']);
								}

								$btn_search = '<span class="input-group-btn"><button type="button" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></span>';
								$btn_del_db = '<div class="text-center"><button class="btn red-intense del-this-db" data-index="'.$i.'" data-confirm="'.translate('Anda yakin akan menghapus item ini?', $this->session->userdata('language')).'" data-id="'.$row['id'].'" title="Delete"><i class="fa fa-times"></i></button></div>';

								$attrs_id_detail_db = array(
			                        'id'          => 'item_id_detail_'.$i.'',
			                        'name'        => 'item['.$i.'][id_detail]',
			                        'type'        => 'hidden',
			                        'class'       => 'form-control',
			                        'value'		  => $row['id']
			                        
			                    );

			                    $attrs_is_deleted_db = array(
			                        'id'          => 'item_is_deleted_'.$i.'',
			                        'name'        => 'item['.$i.'][is_deleted]',
			                        'type'        => 'hidden',
			                        'class'       => 'form-control',
			                        'value'		  => 0
			                    );

			                    $attrs_id_db = array(
			                        'id'          => 'item_id_'.$i.'',
			                        'name'        => 'item['.$i.'][id]',
			                        'type'        => 'hidden',
			                        'class'       => 'form-control',
			                        'value'		  => $row['item_id']
			                        
			                    );
			                    $attrs_kode_db = array(
			                        'id'          => 'item_kode_'.$i.'',
			                        'name'        => 'item['.$i.'][kode]',
			                        'class'       => 'form-control',
			                        'readonly'    => 'readonly',
			                        'value'		  => $data_item->kode
			                       
			                    );
			                    $attrs_name_db = array(
			                        'id'          => 'item_name_'.$i.'',
			                        'name'        => 'item['.$i.'][name]',
			                        'class'       => 'form-control',
			                        'readonly'    => 'readonly',
			                        'value'		  => $data_item->nama
			                        
			                    );
			                    $attrs_qty_db = array(
			                        'id'          => 'item_qty_'.$i.'',
			                        'name'        => 'item['.$i.'][qty]',
			                        'class'       => 'form-control',
			                        'value'		  => $row['qty']
			                        
			                    );
			                    $attrs_harga_db = array(
			                        'id'          => 'item_harga_'.$i.'',
			                        'name'        => 'item['.$i.'][harga]',
			                        'class'       => 'form-control',
			                        'value'		  => $row['harga'],
			                        'readonly'	=> "readonly"
			                        
			                    );
			                    $attrs_tipe_db = array(
			                        'id'          => 'item_tipe_'.$i.'',
			                        'name'        => 'item['.$i.'][tipe]',
			                        'class'       => 'form-control',
			                        'value'		  => $row['tipe']

			                    );
			                    $attrs_tipe_item_db = array(
			                        'id'          => 'item_tipe_item_'.$i.'',
			                        'name'        => 'item['.$i.'][tipe_item]',
			                        'class'       => 'form-control',
			                        'type'        => 'hidden',
			                        'value'		  => $row['tipe_item']

			                    );

			                    $attrs_sub_total_db = array(
			                        'id'          => 'item_sub_total_'.$i.'',
			                        'name'        => 'item['.$i.'][sub_total]',
			                        'class'       => 'form-control',
			                        'readonly'    => 'readonly',
			                        'value'		  => $row['qty'] * $row['harga']
			                        
			                    );

			                    $tipe_option_db = array(
			                    	'1' => '-',
			                    	'2' => 'Obat & Vitamin',
			                    	'3' => 'Penunjang Medik',
			                    );

			                    $satuan_option_db = array();

			                    $satuan = $this->item_satuan_m->get_by(array('item_id' => $row['item_id']));
			                    $satuan = object_to_array($satuan);

			                    foreach ($satuan as $row_satuan) {
			                    	$satuan_option_db[$row_satuan['id']] = $row_satuan['nama'];
			                    }
			                    $item_cols_db = array(
									'item_kode'      => '<div class="input-group">'.form_input($attrs_kode_db).form_input($attrs_id_db).form_input($attrs_id_detail_db).$btn_search.'</div>',
									'item_name'      => form_input($attrs_name_db).form_input($attrs_tipe_item_db),
									'item_qty'       => form_input($attrs_qty_db),
									'item_satuan'      => form_dropdown('item['.$i.'][satuan_id]', $satuan_option_db, $row['satuan_id'],'id="item_satuan_id_'.$i.'" class="form-control satuan_db" data-index="'.$i.'"'),
									'item_harga'     => form_input($attrs_harga_db),
									'item_tipe'      => form_dropdown('item['.$i.'][tipe]', $tipe_option_db, $row['tipe'],'id="item_tipe_'.$i.'" class="form-control" required'),
									'item_sub_total' => form_input($attrs_sub_total_db),
									'action'         => $btn_del_db.form_input($attrs_is_deleted_db)
			                    );

			                    $item_row_template_db .= '<tr id="item_row_'.$i.'"><td>' . implode('</td><td>', $item_cols_db) . '</td></tr>';
								
								$i++;
							}
						}
						else
						{
							$i = 0;
							$item_row_template_db = '';
						}

						$btn_search 		= '<span class="input-group-btn"><button type="button" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></span>';
	                    $btn_del            = '<div class="text-center"><button class="btn red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';

	                    $attrs_id_detail_db = array(
	                        'id'          => 'item_id_detail_{0}',
	                        'name'        => 'item[{0}][id_detail]',
	                        'type'        => 'hidden',
	                        'class'       => 'form-control',	                        
	                    );

	                    $attrs_is_deleted = array(
	                        'id'          => 'item_is_deleted_{0}',
	                        'name'        => 'item[{0}][is_deleted]',
	                        'type'        => 'hidden',
	                        'class'       => 'form-control',
	                        'value'		  => 0
	                    );

	                    $attrs_id = array(
	                        'id'          => 'item_id_{0}',
	                        'name'        => 'item[{0}][id]',
	                        'type'        => 'hidden',
	                        'class'       => 'form-control',
	                        
	                    );
	                    $attrs_kode = array(
	                        'id'          => 'item_kode_{0}',
	                        'name'        => 'item[{0}][kode]',
	                        'class'       => 'form-control',
	                        'readonly'    => 'readonly',
	                       
	                    );
	                    $attrs_name = array(
	                        'id'          => 'item_name_{0}',
	                        'name'        => 'item[{0}][name]',
	                        'class'       => 'form-control',
	                        'readonly'    => 'readonly',
	                        
	                    );
	                    $attrs_qty = array(
	                        'id'          => 'item_qty_{0}',
	                        'name'        => 'item[{0}][qty]',
	                        'class'       => 'form-control',
	                        
	                    );
	                    $attrs_harga = array(
	                        'id'          => 'item_harga_{0}',
	                        'name'        => 'item[{0}][harga]',
	                        'class'       => 'form-control',
	                        
	                    );
	                    $attrs_tipe = array(
	                        'id'          => 'item_tipe_{0}',
	                        'name'        => 'item[{0}][tipe]',
	                        'class'       => 'form-control',

	                    );

	                    $attrs_tipe_item = array(
	                        'id'          => 'item_tipe_item_{0}',
	                        'name'        => 'item[{0}][tipe_item]',
	                        'class'       => 'form-control',
	                        'type'        => 'hidden',

	                    );
	                    $attrs_sub_total = array(
	                        'id'          => 'item_sub_total_{0}',
	                        'name'        => 'item[{0}][sub_total]',
	                        'class'       => 'form-control',
	                        'readonly'    => 'readonly',
	                        
	                    );

	                    $tipe_option = array(
	                    	'1' => '-',
	                    	'2' => 'Obat & Vitamin',
	                    	'3' => 'Penunjang Medik',
	                    );

	                    $satuan_option = array();

	                    $item_cols = array(
							'item_kode'      => '<div class="input-group">'.form_input($attrs_kode).form_input($attrs_id).form_input($attrs_id_detail_db).$btn_search.'</div>',
							'item_name'      => form_input($attrs_name).form_input($attrs_tipe_item),
							'item_qty'       => form_input($attrs_qty),
							'item_satuan'      => form_dropdown('item[{0}][satuan_id]', $satuan_option, '','id="item_satuan_id_{0}" class="form-control" '),
							'item_harga'     => form_input($attrs_harga),
							'item_tipe'      => form_dropdown('item[{0}][tipe]', $tipe_option, '','id="item_tipe_{0}" class="form-control" required'),
							'item_sub_total' => form_input($attrs_sub_total),
							'action'         => $btn_del.form_input($attrs_is_deleted)
	                    );

	                    // gabungkan $item_cols jadi string table row
	                    $item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
	                ?>
					<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="tabel_tambah_item">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="15%"><?=translate("Kode Item", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="20%"><?=translate("Nama Item", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="5%"><?=translate("Qty", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Satuan", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Harga", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="20%"><?=translate("Tipe", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Subtotal", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata('language'))?></th>
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
                                <?php
                                	$akomodasi = ($form_detail['akomodasi'] != NULL)?$form_detail['akomodasi']:0;
                                ?>
                                	<tr>
                                		<th colspan="6"><div class="text-right">Akomodasi</div></th>
                                		<th colspan="2"><input type="number" name="akomodasi" id="item_akomodasi" value="<?=$akomodasi?>" class="form-control text-right"></th>
                                	</tr>
                                </tfoot>
                            </table>
                        </div>
                        <input type="hidden" name="jumlah_data" id="jumlah_data" value="<?=$i?>" >
					</div>
				</div>
			</div><!-- end of <div class="portlet light bordered"> -->
		</div><!-- end of <div class="col-md-8"> -->
		
	</div><!-- end of <div class="row"> -->
	<?php $msg = translate("Apakah anda yakin akan membuat invoice ini?",$this->session->userdata("language"));?>
	<div class="form-actions right">	
		<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
		<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
	</div>
</div>
</div>


<?=form_close()?>

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No. RM', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tempat, Tanggal Lahir', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 

<div id="popover_item_content" class="row">
    <div class="col-md-12">
	    <div class="portlet light bordered">

		  <div class="portlet-title tabbable-line">
			<div class="caption">
				<?=translate("Pilih Paket", $this->session->userdata("language"))?>
			</div>
		    <ul class="nav nav-tabs">
		      <li  class="active">
		          <a href="#paket" data-toggle="tab">
		              <?=translate('Paket', $this->session->userdata('language'))?> </a>
		      </li>
		      <li>
		          <a href="#item" data-toggle="tab">
		              <?=translate('Item', $this->session->userdata('language'))?> </a>
		      </li>
		      <li>
		          <a href="#tindakan" data-toggle="tab">
		              <?=translate('Tindakan', $this->session->userdata('language'))?> </a>
		      </li>
		    </ul>
		  </div>
		  <div class="portlet-body form">
		    <div class="tab-content">
		        <div class="tab-pane active" id="paket" >
		            <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_paket">
			            <thead>
			                <tr role="row">
			                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
			                </tr>
			            </thead>
			            <tbody>
			            </tbody>
			        </table>
		        </div>
		        <div class="tab-pane" id="item" >
		           	<table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item">
			            <thead>
			                <tr role="row">
			                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center" widht="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
			                </tr>
			            </thead>
			            <tbody>
			            </tbody>
			        </table>
		        </div>
		        <div class="tab-pane " id="tindakan" >
		        	<table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_tindakan">
			            <thead>
			                <tr role="row">
			                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
			                </tr>
			            </thead>
			            <tbody>
			            </tbody>
			        </table> 
		        </div>
		    </div>
		  </div>
		</div>
    </div>
</div> 




