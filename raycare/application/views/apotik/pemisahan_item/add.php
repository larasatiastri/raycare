<?php

	$form_attr = array(
	    "id"            => "form_pemisahan_item", 
	    "name"          => "form_pemisahan_item", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
		    echo form_open(base_url()."apotik/pemisahan_item/save", $form_attr);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	
	$btn_info           = '<div class="text-center"><a data-info="{0}" class="btn btn-sx btn-primary info"><i class="fa fa-info"></i></a></div>';

	$attrs_item_sebelum_jumlah  = array( 
	    'id'       => 'items_sebelum_jumlah_{0}',
	    'name'     => 'items[{0}][item_sebelum_jumlah]',
	    'class'    => 'form-control hidden',
	    'readonly' => 'readonly'
	);

	$attrs_item_sebelum_satuan = array(
	    'id'          => 'items_sebelum_satuan_{0}',
	    'name'        => 'items[{0}][item_sebelum_satuan]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);

	$attrs_item_sesudah_jumlah = array(
	    'id'          => 'items_sesudah_jumlah_{0}',
	    'name'        => 'items[{0}][item_sesudah_jumlah]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);

	$attrs_item_sesudah_satuan = array(
	    'id'          => 'items_sesudah_satuan_{0}',
	    'name'        => 'items[{0}][item_sesudah_satuan]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);

	$attrs_jumlah_item = array(
	    'id'          => 'items_jumlah_item_{0}',
	    'name'        => 'items[{0}][item_jumlah_item]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);

	$attrs_stok_awal_sebelum = array(
	    'id'          => 'items_sesudah_satuan_{0}',
	    'name'        => 'items[{0}][stok_awal_sebelum]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);

	$attrs_stok_akhir_sebelum = array(
	    'id'          => 'items_jumlah_item_{0}',
	    'name'        => 'items[{0}][stok_akhir_sebelum]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);

	$attrs_stok_awal_sesudah = array(
	    'id'          => 'items_sesudah_satuan_{0}',
	    'name'        => 'items[{0}][stok_awal_sesudah]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);

	$attrs_stok_akhir_sesudah = array(
	    'id'          => 'items_jumlah_item_{0}',
	    'name'        => 'items[{0}][stok_akhir_sesudah]',
	    'class'       => 'form-control hidden',
	    'readonly'    => 'readonly',
	);


	$item_cols = array(// style="width:156px;
		'no'				=> '<div class="text-center"><label class="control-label text-center" id="nomer" name="nomer"></label></div>',
		'jumlah_sebelum'   => form_input($attrs_item_sebelum_jumlah).form_input($attrs_stok_awal_sebelum).form_input($attrs_stok_akhir_sebelum).'<div class="text-center"><label class="control-label" id="jumlah_sebelum_lblname{0}" name="items[{0}][lblnameJumlah]"></label></div>'.form_input($attrs_jumlah_item).'<div id="simpan_identitas" class="hidden"></div>',
		'satuan_sebelum'   => form_input($attrs_item_sebelum_satuan).'<div class="text-center"><label class="control-label" id="satuan_sebelum_lblname{0}" name="items[{0}][lblnameSatuan]"></label></div>',
		'konversi' 			=> '<div class="text-center"><label class="control-label" id="konversi" name="konversi">konversi</label></div>',
		'jumlah_sesudah'   => form_input($attrs_item_sesudah_jumlah).form_input($attrs_stok_awal_sesudah).form_input($attrs_stok_akhir_sesudah).'<div class="text-center"><label class="control-label" id="jumlah_sesudah_lblname{0}" name="items[{0}][lblnameJumlahSudah]"></label></div>',
		'satuan_sesudah'   => form_input($attrs_item_sesudah_satuan).'<div class="text-center"><label class="control-label" id="satuan_sesudah_lblname{0}" name="items[{0}][lblnameSatuanSudah]"></label></div>',
	);
	// gabungkan $item_cols jadi string table row
	$item_row_template_konversi =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$dummy_rows = array();

?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-dropbox font-blue-sharp"></i>
			<span class="caption font-blue-sharp bold uppercase"><?=translate("Pemisahan Item", $this->session->userdata("language"))?></span>
		</div>
		<?php $msg= translate("Apakah anda yakin akan menyimpan Pemisahan Item ini?", $this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
	        <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="form-body">
				<div class="alert alert-danger display-hide">
			        <button class="close" data-close="alert"></button>
			        <?=$form_alert_danger?>
			    </div>
			    <div class="alert alert-success display-hide">
			        <button class="close" data-close="alert"></button>
			        <?=$form_alert_success?>
			    </div>
			    <div class="col-md-6">
			    	<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Gudang", $this->session->userdata("language"))?> :</label>
						<div class="col-md-5">
							<?php
								$nama_gudang = array(
									"id"			=> "nama_gudang",
									"name"			=> "nama_gudang",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"readonly"		=> "readonly",
									"value"			=> $form_data_gudang['informasi']
								);

								$id_gudang = array(
									"id"			=> "id_gudang",
									"name"			=> "id_gudang",
									"autofocus"			=> true,
									"class"			=> "form-control hidden",
									"placeholder"	=> translate("Pasien", $this->session->userdata("language")),
									"value"			=> $form_data_gudang['id']
								);
								echo form_input($nama_gudang);
								echo form_input($id_gudang);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Kode", $this->session->userdata("language"))?> :</label>
						<div class="col-md-5">
							<?php
							// die_dump($form_data);
								$kode_item = array(
									"id"			=> "kode_item",
									"name"			=> "kode_item",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"readonly"		=> "readonly",
									"value"			=> $form_data['kode']	
								);

								$id_item = array(
									"id"			=> "id_item",
									"name"			=> "id_item",
									"autofocus"			=> true,
									"class"			=> "form-control hidden", 
									"readonly"		=> "readonly",
									"value"			=> $form_data['id']	
								);

								echo form_input($kode_item);
								echo form_input($id_item);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
						<div class="col-md-5">
							<?php
								$nama_item = array(
									"id"			=> "nama_item",
									"name"			=> "nama_item",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"readonly"		=> "readonly",
									"value"			=> $form_data['nama']
								);

								echo form_input($nama_item);
							?>
						</div>
					</div>
			    </div>
			    <div class="col-md-6">
			    	<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Tanggal", $this->session->userdata("language"))?> :<span class="required">*</span></label>
						<div class="col-md-5">
							<div class="input-group date" id="tanggal_pecah">
								<?php
									$tanggal = array(
										"id"        => "tanggal_pecah",
										"name"      => "tanggal_pecah",
										"autofocus" => true,
										"type"		=> "text",
										"readonly"	=> "readonly",
										"required"  => "required",
										"class"     => "form-control date-picker",
										"value"		=> date('d M Y')
									);

									echo form_input($tanggal);
								?>
								<span class="input-group-btn">
									<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
								</span>
							</div>
							
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Subjek", $this->session->userdata("language"))?> :<span class="required">*</span></label>
						<div class="col-md-5">
							<?php
								$subjek = array(
									"id"        => "subjek",
									"name"      => "subjek",
									"autofocus" => true,
									"required"  => "required",
									"class"     => "form-control"
								);

								echo form_input($subjek);
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
						<div class="col-md-5">
							<?php
								$keterangan = array(
									"id"          => "keterangan",
									"name"        => "keterangan",
									"autofocus"   => true,
									"rows"        => 5,
									"class"       => "form-control",
									"placeholder" => translate("Keterangan", $this->session->userdata("language"))
								);
								echo form_textarea($keterangan);
							?>
						</div>
					</div>
			    </div>
				
				
				
			</div>
		</div>

		
		<div class="row">
			<div class="portlet">
				
				<div class="col-md-4">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<?=translate("Rumus Konversi", $this->session->userdata("language"))?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable" style="margin:0px !important;">
								<table class="table table-striped table-bordered table-hover" id="table_daftar_item">
									<thead>
									<tr>
										<th class="text-center hidden"><?=translate("ID", $this->session->userdata("language"))?> </th>
										<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
										<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
										<th class="text-center"><?=translate("Operasional", $this->session->userdata("language"))?> </th>
										<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
										<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
									</tr>
									</thead>
									<tbody>
										<?php
										$item_satuan_info = $this->list_inventory_m->get_data($form_data['id'])->result_array();
										$info = object_to_array($item_satuan_info);
										// die_dump($info);

										$item_count = $this->list_inventory_m->get_by(array('item_id' => $form_data['id'], 'parent_id !=' => is_null(1)));
										$count = count($item_count);

										$i = 0;
										$parent_id = '';
										$konvert_satuan = '';
										$satuan_nama = '';
										foreach ($info as $satuan_info) {
											if($i == 0)
											{
												$item_satuan_konvert = $this->list_inventory_m->get_by(array('parent_id' => $satuan_info['id']));
												$konvert_satuan = object_to_array($item_satuan_konvert);

												$satuan_nama = $satuan_info['nama'];

											}
											else
											{
												$item_satuan_konvert = $this->list_inventory_m->get_by(array('parent_id' => $parent_id));
												$konvert_satuan = object_to_array($item_satuan_konvert);

												$nama = $this->list_inventory_m->get_by(array('id' => $parent_id));
												$satuan_nama_id = object_to_array($nama);
												$satuan_nama = $satuan_nama_id[0]['nama'];

											}
											
											$parent_id = $konvert_satuan[0]['id'];

											if($konvert_satuan)
											{
												if($i != $count)
												{
													echo 	'<tr>
																<td class="text-center">1</td>
																<td class="text-center">'.$satuan_nama.'</td>
																<td class="text-center">=</td>
																<td class="text-center">'.$konvert_satuan[0]['jumlah'].'</td>
																<td class="text-center">'.$konvert_satuan[0]['nama'].'</td>
															</tr>';
												}
												$i++;
											}
										}
												
										?>
									</tbody>
								</table>
							</div>
	
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<?=translate("Stok Awal", $this->session->userdata("language"))?>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover" id="table_info_item">
								<thead>
								<tr>
									<th class="text-center hidden"><?=translate("ID", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
								</tr>
								</thead>
								<tbody>
									<?php
										// $item_satuan_info = $this->list_inventory_m->get_data($form_data['id'])->result_array();
										$inventory_info = $this->item_satuan_m->get_data_stok_akhir($form_data['id'])->result_array();
											// die_dump($inventory_info);
										$i = 0;
										$par_id = '';
										$kon_satuan = '';
										$nama_satuan = '';
										foreach ($inventory_info as $item_info) {
											if($i == 0)
											{
												$item_satuan_konvert = $this->list_inventory_m->get_by(array('parent_id' => null, 'item_id' => $form_data['id']));
												$kon_satuan = object_to_array($item_satuan_konvert);
												// die_dump($this->db->last_query());
												$nama_satuan = $kon_satuan[0]['nama'];

											}
											else
											{
												$item_satuan_konvert = $this->list_inventory_m->get_by(array('parent_id' => $par_id));
												$kon_satuan = object_to_array($item_satuan_konvert);

												$nama = $this->list_inventory_m->get_by(array('id' => $kon_satuan[0]['id']));
												$nama_satuan_id = object_to_array($nama);
												$nama_satuan = $nama_satuan_id[0]['nama'];
												// die_dump($kon_satuan[0]['id']);

											}
											
											$par_id = $kon_satuan[0]['id'];
											$info = $this->list_inventory_m->get_stok_awal($par_id, $form_data['id'], $form_data_gudang['id'])->result_array();

											if($info)
											{
												echo '<tr>
														<td class="text-center"><input type="text" class="stok_awal" id="stok_awal_'.$par_id.'" value="'.$info[0]['jumlah_item'].'" name="stok[0][item_stok_awal_'.$par_id.']" readonly="readonly" style="background-color: transparent;border: 0px solid; text-align: center;">
														<input type="hidden" class="stok_awal" id="'.$par_id.'" value="'.$item_info['id'].'"></td>
														<td class="text-center">'.$nama_satuan.'</td>
													</tr>';
											}
											else
											{
												echo '<tr>
														<td class="text-center"><input type="text" class="stok_awal" id="stok_awal_'.$par_id.'" value="0" name="stok[0][item_stok_awal_'.$par_id.']"" readonly="readonly" style="background-color: transparent;border: 0px solid; text-align: center;">
														<input type="hidden" class="stok_awal" id="'.$par_id.'" value="'.$par_id.'"></td>
														<td class="text-center">'.$nama_satuan.'</td>
													</tr>';
											}
										$i++;
										}		
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<?=translate("Stok Akhir", $this->session->userdata("language"))?>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover" id="table_history">
								<thead>
								<tr>
									<th class="text-center hidden"><?=translate("ID", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
								</tr>
								</thead>
								<tbody>
									<?php
										$inventory_info = $this->item_satuan_m->get_data_stok_akhir($form_data['id'])->result_array();
											// die_dump($inventory_info);
										$i = 0;
										$par_id = '';
										$kon_satuan = '';
										$nama_satuan = '';
										foreach ($inventory_info as $item_info) {
											if($i == 0)
											{
												$item_satuan_konvert = $this->list_inventory_m->get_by(array('parent_id' => null, 'item_id' => $form_data['id']));
												$kon_satuan = object_to_array($item_satuan_konvert);
												// die_dump($this->db->last_query());
												$nama_satuan = $kon_satuan[0]['nama'];

											}
											else
											{
												$item_satuan_konvert = $this->list_inventory_m->get_by(array('parent_id' => $par_id));
												$kon_satuan = object_to_array($item_satuan_konvert);

												$nama = $this->list_inventory_m->get_by(array('id' => $kon_satuan[0]['id']));
												$nama_satuan_id = object_to_array($nama);
												$nama_satuan = $nama_satuan_id[0]['nama'];
												// die_dump($kon_satuan[0]['id']);

											}
											
											$par_id = $kon_satuan[0]['id'];
											$info = $this->list_inventory_m->get_stok_awal($par_id, $form_data['id'], $form_data_gudang['id'])->result_array();

											if($info)
											{
												echo 	'<tr>
														<td class="text-center"><input type="text" id="stok_'.$par_id.'" value="'.$info[0]['jumlah_item'].'" name="stok[0][item_stok_akhir_'.$par_id.']"" readonly="readonly" style="background-color: transparent;border: 0px solid; text-align: center;">
														<input type="hidden" class="stok_akhir" id="'.$par_id.'" value="'.$par_id.'"></td>
														<td class="text-center">'.$nama_satuan.'</td>
													</tr>';
											}
											else
											{
												echo 	'<tr>
														<td class="text-center"><input type="text" id="stok_'.$par_id.'" value="0" name="stok[0][item_stok_akhir_'.$par_id.']" readonly="readonly" style="background-color: transparent;border: 0px solid; text-align: center;">
														<input type="hidden" class="stok_akhir" id="'.$par_id.'" value="'.$par_id.'"></td>
														<td class="text-center">'.$nama_satuan.'</td>
													</tr>';
											}
										$i++;
										}
									?>
								</tbody>
							</table>
						</div>
					</div>					
				</div>
			</div>

		</div>
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<?=translate("Proses Konversi", $this->session->userdata("language"))?>
				</div>
				<div class="actions">
		            <a href="<?=base_url()?>apotik/pemisahan_item/tambah_pisah_item/<?=$form_data['id']?>/<?=$form_data_gudang['id']?>/1" data-target="#ajax_notes1" data-toggle="modal" id="tambah_konversi" class="btn btn-circle btn-icon-only btn-default">
		                <i class="fa fa-plus"></i>
		            </a>
		            <a title="tambah_row" id="addRow" class="btn btn-primary hidden">
		                <i class="fa fa-plus"></i>
		            </a>
		            <input type="hidden" id="itemRow" name="itemRow">
		        </div>

			</div>
			<div class="portlet-body">
				<span id="tpl_item_row_konversi" class="hidden"><?=htmlentities($item_row_template_konversi)?></span>
				<div class="table-scrollable" style="margin:0px !important;">
					<table class="table table-striped table-bordered table-hover" id="table_konversi">
						<thead>
						<tr>
							<th class="text-center hidden"><?=translate("ID", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("No.", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
						</tr>
						</thead>
						<tbody><!-- 
							<?php foreach ($dummy_rows as $row):?>
			                    <?=$row?>
			                <?php endforeach;?> -->
						</tbody>
					</table>
				</div>
				
			</div>

</div>
			<?php $msg= translate("Apakah anda yakin akan menyimpan Pemisahan Item ini?", $this->session->userdata("language"));?>
			<div class="form-actions right">	
				<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
		        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		        <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
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
