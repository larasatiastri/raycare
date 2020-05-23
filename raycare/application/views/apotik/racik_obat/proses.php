<div class="portlet light">
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_proses_racik_obat", 
			    "name"          => "form_proses_racik_obat", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "proses"
		    );

		    echo form_open(base_url()."apotik/racik_obat/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');

		?>

		<div class="form-body">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-chemistry font-blue-sharp"></i>
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Racik Obat", $this->session->userdata("language"))?></span>
						<span class="caption-helper"><?php echo '<label class="control-label ">'.date('d M Y').'</label>'; ?></span>
					</div>
					<div class="actions">

			            <?php $msg = translate("Apakah anda yakin akan membuat racik obat ini?",$this->session->userdata("language"));?>
						
						<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
							<i class="fa fa-mail-reply"></i>
							<?=translate("Kembali", $this->session->userdata("language"))?>
						</a>
						<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
							<i class="fa fa-floppy-o"></i>
							<?=translate("Simpan", $this->session->userdata("language"))?>
						</a>
				        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
							
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
						
						<div class="form-group hidden">
							<label class="control-label col-md-2"><?=translate("Resep Obat Racikan Id", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php
									$resep_obat_racikan_id = array(
										"id"			=> "resep_obat_racikan_id",
										"name"			=> "resep_obat_racikan_id",
										"autofocus"			=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Resep Obat Racikan Id", $this->session->userdata("language")), 
										"value"			=> $form_data['id'],
										"help"			=> $flash_form_data['resep_obat_racikan_id'],
									);
									echo form_input($resep_obat_racikan_id);
								?>
								<label id="resep_obat_racikan_id" class="control-label"><?=$form_data['id']?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php
									$pembuat = array(
										"id"			=> "pembuat",
										"name"			=> "pembuat",
										"autofocus"			=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Pembuat", $this->session->userdata("language")), 
										"value"			=> $form_data['nama_dokter'],
										"help"			=> $flash_form_data['pembuat'],
									);
									echo form_input($pembuat);

        							$user_id = $this->session->userdata('user_id');
        							$get_pembuat = $this->user_m->get($user_id);
        							$data_pembuat = object_to_array($get_pembuat);
									// die_dump($data_pembuat);
								?>
								<label id="pembuat" class="control-label"><?=$data_pembuat['nama']?></label>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> :</label>
                        
	                        <div class="col-md-2">
	                            <div class="input-group date" id="tanggal">
	                                <input type="text" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" >
	                                <span class="input-group-btn">
	                                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
	                                </span>
	                            </div>
	                        </div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Jumlah Produksi", $this->session->userdata("language"))?>:<span class="required">*</span></label>
							<div class="col-md-2">
								<?php
									$jumlah_produksi = array(
										"id"          => "jumlah_produksi",
										"name"        => "jumlah_produksi",
										"type"        => "number",
										"min"         => 1,
										"autofocus"   => true,
										"class"       => "form-control text-right required", 
										"placeholder" => translate("Jumlah Produksi", $this->session->userdata("language")), 
										"value"       => "1",
										"help"        => $flash_form_data['jumlah_produksi'],
									);
									echo form_input($jumlah_produksi);
								?>	
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php
									$dokter = array(
										"id"			=> "dokter",
										"name"			=> "dokter",
										"autofocus"			=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Dokter", $this->session->userdata("language")), 
										"value"			=> $form_data['nama_dokter'],
										"help"			=> $flash_form_data['dokter'],
									);
									echo form_input($dokter);
								?>
								<label id="dokter" class="control-label"><?=$form_data['nama_dokter']?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Pasien", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php

									// die_dump($form_data);
									$pasien_id = array(
										"id"			=> "pasien_id",
										"name"			=> "pasien_id",
										"autofocus"			=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Pasien Id", $this->session->userdata("language")), 
										"value"			=> $form_data['pasien_id'],
										"help"			=> $flash_form_data['pasien'],
									);
									echo form_input($pasien_id);
								?>

								<?php
									$pasien = array(
										"id"			=> "pasien",
										"name"			=> "pasien",
										"autofocus"			=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Pasien", $this->session->userdata("language")), 
										"value"			=> $form_data['nama_pasien'],
										"help"			=> $flash_form_data['pasien'],
									);
									echo form_input($pasien);
								?>
								<label id="pasien" class="control-label"><?=$form_data['nama_pasien']?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php
									$nama_resep = array(
										"id"			=> "nama_resep",
										"name"			=> "nama_resep",
										"autofocus"			=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
										"value"			=> $form_data['nama_resep'],
										"help"			=> $flash_form_data['nama_resep'],
									);
									echo form_input($nama_resep);
								?>
								<label id="nama_resep" class="control-label"><?=$form_data['nama_resep']?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Jumlah", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php
									$jumlah = array(
										"id"			=> "jumlah",
										"name"			=> "jumlah",
										"autofocus"			=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
										"value"			=> $form_data['jumlah'].' '.$form_data['nama_satuan'],
										"help"			=> $flash_form_data['jumlah'],
									);
									echo form_input($jumlah);
								?>

								<?php
									$satuan_id = array(
										"id"			=> "satuan_id",
										"name"			=> "satuan_id",
										"autofocus"			=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
										"value"			=> $form_data['satuan_id'],
										"help"			=> $flash_form_data['satuan_id'],
									);
									echo form_input($satuan_id);
								?>
								<label id="jumlah" class="control-label"><?=$form_data['jumlah']?>&nbsp;<?=$form_data['nama_satuan']?></label>
								<input type="hidden" class="form-control" name="satuan_produksi" value="<?=$form_data['nama_satuan']?>" placeholder="<?=translate("Satuan", $this->session->userdata("language"))?>">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Dosis", $this->session->userdata("language"))?> :</label>
							<div class="col-md-2">
								<?php
									$dosis = array(
										"id"			=> "dosis",
										"name"			=> "dosis",
										"autofocus"			=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Dosis", $this->session->userdata("language")), 
										"value"			=> $form_data['dosis'],
										"help"			=> $flash_form_data['dosis'],
									);
									echo form_input($dosis);
								?>
								<label id="dosis" class="control-label"><?=$form_data['dosis']?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
							<div class="col-md-3">
								<?php
									$keterangan = array(
										"id"			=> "keterangan",
										"name"			=> "keterangan",
										"autofocus"			=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
										"value"			=> $form_data['keterangan'],
										"help"			=> $flash_form_data['keterangan'],
									);
									echo form_textarea($keterangan);
								?>
								<label id="keterangan" class="control-label" style="text-align:left !important;"><?=$form_data['keterangan']?></label>
							</div>
						</div>

						
						
						
						
					</div>
				</div>	
			</div>
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Komposisi Manual', $this->session->userdata('language'))?></span>
					</div>
				</div>
				<div class="portlet-body">

					<table class="table table-striped table-bordered table-hover" id="table_komposisi_manual">
						<thead>

							<tr>
								<th class="text-center" style="width : 10% !important;"><?=translate("ID", $this->session->userdata("language"))?></th>
								<th class="text-center" style="width : 10% !important;"><?=translate("No", $this->session->userdata("language"))?></th>
								<th class="text-center" style="width : 90% !important;"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
							</tr>
						</thead>

						<tbody>
						</tbody>
					</table>
				</div>


			</div>

			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Komposisi Racikan', $this->session->userdata('language'))?></span>
					</div>
				</div>
				<div class="portlet-body">

				<?php 
					$btn_search        = '<div class="text-center"><button title="" class="btn btn-sm btn-primary search-item" data-original-title="Search Item" data-status-row="item_row_add"><i class="fa fa-search"></i></button></div>';
					$btn_search_result = '<div class="text-center"><button title="" class="btn btn-sm btn-success search-item-result" data-original-title="Search Item"><i class="fa fa-search"></i></button></div>';
					$btn_del           = '<div class="text-center"><button class="btn btn-sm red-intense del-this" title="Delete Item"><i class="fa fa-times"></i></button></div>';

					$attrs_item_id  = array ( 
					    'id'       => 'items_item_id_{0}',
					    'type'     => 'hidden',
					    'name'     => 'items[{0}][item_id]',
					    'class'    => 'form-control',
					    // 'hidden'   => 'hidden',
					    // 'style'    => 'width:80px;',
					    'readonly' => 'readonly',
					    // 'value' => 'BLSG01',
					);

					$attrs_inventory_id  = array ( 
					    'id'       => 'items_inventory_id_{0}',
					    'type'     => 'hidden',
					    'name'     => 'items[{0}][inventory_id]',
					    'class'    => 'form-control',
					    // 'hidden'   => 'hidden',
					    // 'style'    => 'width:80px;',
					    'readonly' => 'readonly',
					    // 'value' => 'BLSG01',
					);

					$attrs_gudang_id  = array ( 
					    'id'       => 'items_gudang_id_{0}',
					    'type'     => 'hidden',
					    'name'     => 'items[{0}][gudang_id]',
					    'class'    => 'form-control',
					    // 'hidden'   => 'hidden',
					    // 'style'    => 'width:80px;',
					    'readonly' => 'readonly',
					    // 'value' => 'BLSG01',
					);

					$attrs_pmb_id  = array ( 
					    'id'       => 'items_pmb_id_id_{0}',
					    'type'     => 'hidden',
					    'name'     => 'items[{0}][pmb_id]',
					    'class'    => 'form-control',
					    // 'hidden'   => 'hidden',
					    // 'style'    => 'width:80px;',
					    'readonly' => 'readonly',
					    // 'value' => 'BLSG01',
					);

					$attrs_satuan_id  = array ( 
					    'id'       => 'items_satuan_id_{0}',
					    'type'     => 'hidden',
					    'name'     => 'items[{0}][item_satuan_id]',
					    'class'    => 'form-control',
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

					$attrs_item_harga = array(
					    'id'          => 'items_harga_{0}',
					    'name'        => 'items[{0}][item_harga]',
					    'class'       => 'form-control hidden',
					    'readonly'    => 'readonly',
					);

					$attrs_item_sub_harga = array(
					    'id'          => 'items_sub_harga_{0}',
					    'name'        => 'items[{0}][item_sub_harga]',
					    'class'       => 'form-control hidden',
					    'readonly'    => 'readonly',
					);

					$attrs_jumlah_dokter = array(
					    'id'    => 'items_jumlah_dokter_{0}',
					    'name'  => 'items[{0}][item_jumlah_dokter]', 
					    'type'  => 'number',
					    'min'   => 0,
					    'value' => 0,
					    'class' => 'form-control text-right jumlah_dokter hidden',
					);

					$attrs_jumlah_dokter_awal = array(
					    'id'    => 'items_jumlah_dokter_awal_{0}',
					    'name'  => 'items[{0}][item_jumlah_dokter_awal]', 
					    'type'  => 'number',
					    'min'   => 0,
					    'value' => 1,
					    'data-id' => '{0}',
					    'class' => 'form-control text-right jumlah_dokter_awal hidden',
					);

					$attrs_jumlah = array(
					    'id'    => 'items_jumlah_{0}',
					    'name'  => 'items[{0}][item_jumlah]', 
					    'type'  => 'number',
					    'min'   => 0,
					    'value' => 0,
					    'class' => 'form-control text-right hidden',
					);

					$item_cols = array(// style="width:156px;
						'item_kode'   => '<label class="control-label" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;"></label>'.form_input($attrs_item_id).form_input($attrs_item_kode).form_input($attrs_satuan_id),
						'item_search' => $btn_search.form_input($attrs_inventory_id).form_input($attrs_gudang_id).form_input($attrs_pmb_id),
						'item_name'   => '<label class="control-label" name="items[{0}][item_nama]"></label>'.form_input($attrs_item_nama).'<div id="simpan_identitas" class="hidden"></div>',
						'item_jumlah_dokter' => '<div class="input-group text-center" style="width: 100%;">
											<label class="control-label jumlah_dokter" id="items_jumlah_dokter_{0}" name="items[{0}][item_jumlah_dokter]" style="display: table-cell; width: 100%; text-align: center;"></label>
										  </div>'.form_input($attrs_jumlah_dokter).form_input($attrs_jumlah_dokter_awal),
						'item_jumlah_farmasi' => '<div class="input-group text-center">
											<label class="control-label" name="items[{0}][item_jumlah]" style="display: table-cell; width: 100%; text-align: center;"></label>
											<a class="btn btn-primary identitas hidden" id="info_identitas_{0}" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'apotik/racik_obat/modal_identitas/0/0/item_row_{0}" ><i class="fa fa-info"></i></a>
											<a class="btn btn-primary check-identitas" data-row-check="{0}" data-confirm="'.translate("Apakah anda ingin mengganti identitas sebelumnya ?", $this->session->userdata("language")).'"><i class="fa fa-info"></i></a>
										  </div>'.form_input($attrs_jumlah),
						'item_satuan' => '<div class="text-center">
											<label class="control-label" name="items[{0}][item_satuan]"></label>
										  </div>',
						'item_harga'  => '<div class="input-group text-right">
											<span class="input-group-addon">
										    	<b>Rp.</b>
										    </span>
											<label class="control-label form-control" id="items_harga_{0}" name="items[{0}][item_harga]"></label>
										  </div>'.form_input($attrs_item_harga),
						'item_sub_harga'  => '<div class="input-group text-right">
												<span class="input-group-addon">
											    	<b>Rp.</b>
											    </span>
												<label class="form-control control-label" id="items_sub_harga_{0}" name="items[{0}][item_sub_harga]"></label>
										      </div>'.form_input($attrs_item_sub_harga),
						'action'      => $btn_del,
					);

					$item_row_template =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

					$get_komposisi_racikan = $this->resep_obat_racikan_m->get_komposisi_racikan($form_data['id']);
				    $records = $get_komposisi_racikan->result_array();
				    
				    // die_dump($records);
				    $i=0;
				    $harga;
				    $total = 0;
				    $tanggal = '';
					$diAmbil;

				    if(empty($records)){
					    $items_rows = array();
					}else{
					    foreach ($records as $key=>$data) 
					    {

					    	$tanggal = $data['tanggal'];

					    	$harga_terbaru = $this->resep_obat_racikan_m->get_harga_terbaru($data['item_satuan_id'])->result_array();
							// die_dump($data);
							if ($tanggal == $harga_terbaru[0]['tanggal']) {
								$attrs_item_id['value']    = $data['item_id'];
								$attrs_item_kode['value']    = $data['item_kode'];
								$attrs_satuan_id['value']  = $data['item_satuan_id'];
								$attrs_item_nama['value']  = $data['item_nama'];
								$attrs_item_harga['value'] = $data['harga'];
								$attrs_jumlah_dokter_awal['value']	   = $data['jumlah'];

								$harga = intval($data['harga']);
								$jumlah = intval($data['jumlah']);

								$sub_harga = $jumlah * $harga;
								$total = $total + $sub_harga;
								$attrs_item_sub_harga['value']	   = $sub_harga;


								$btn_search        = '<div class="text-center"><button title="" class="btn btn-sm btn-primary search-item-db" data-original-title="Cari Item" data-status-row="item_row_add"><i class="fa fa-search"></i></button></div>';
								$btn_del           = '<div class="text-center"><button class="btn btn-sm red-intense del-this-db" title="Delete Item"><i class="fa fa-times"></i></button></div>';
						        
						        $item_cols = array(// style="width:156px;
									'item_kode'   => '<label class="control-label" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;">'.$data['item_kode'].'</label>'.form_input($attrs_item_id).form_input($attrs_item_kode).form_input($attrs_satuan_id),
									'item_search' => $btn_search.form_input($attrs_inventory_id).form_input($attrs_gudang_id).form_input($attrs_pmb_id),
									'item_name'   => '<label class="control-label" name="items[{0}][item_nama]">'.$data['item_nama'].'</label>'.form_input($attrs_item_nama).'<div id="simpan_identitas" class="hidden"></div>',
									'item_jumlah_dokter' => '<div class="input-group text-center" style="width: 100%;">
														<label class="control-label jumlah_dokter" id="items_jumlah_dokter_{0}" name="items[{0}][item_jumlah_dokter]" style="display: table-cell; width: 100%; text-align: center;">
													    '.$data['jumlah'].'
													    </label>
													  </div>'.form_input($attrs_jumlah_dokter).form_input($attrs_jumlah_dokter_awal),
									'item_jumlah_farmasi' => '<div class="input-group text-center">
														<label class="control-label" name="items[{0}][item_jumlah]" style="display: table-cell; width: 100%; text-align: center;">0</label>
														<a class="btn btn-primary identitas hidden" id="info_identitas_{0}" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'apotik/racik_obat/modal_identitas/'.$data['item_id'].'/'.$data['item_satuan_id'].'/item_row_{0}" ><i class="fa fa-info"></i></a>
														<a class="btn btn-primary check-identitas" data-row-check="{0}" data-confirm="'.translate("Apakah anda ingin mengganti identitas sebelumnya ?", $this->session->userdata("language")).'"><i class="fa fa-info"></i></a>
													  </div>'.form_input($attrs_jumlah),

									'item_satuan' => '<div class="text-center"><label class="control-label" name="items[{0}][item_satuan]">'.$data['nama_satuan'].'</label></div>',
									'item_harga'  => '<div class="input-group text-right">
														<span class="input-group-addon">
													    	<b>Rp.</b>
													    </span>
													  <label class="control-label form-control" id="items_harga_{0}" name="items[{0}][item_harga]">'.number_format($data['harga'],0,'','.').',-</label>
													  </div>'.form_input($attrs_item_harga),
									'item_sub_harga'  => '<div class="input-group text-right">
															<span class="input-group-addon">
														    	<b>Rp.</b>
														    </span>
															<label class="control-label form-control" id="items_sub_harga_{0}" name="items[{0}][item_sub_harga]">'.number_format($sub_harga,0,'','.').',-</label>
														  </div>'.form_input($attrs_item_sub_harga),
									'action'      => $btn_del,
								);
					            // gabungkan $item_cols jadi string table row
					            $item_row_edit_template =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
					            
					            $items_rows[] = str_replace('{0}', "{$key}", $item_row_edit_template );
							}
							
					    $i++;
					    }
					}


				?>
				<div id="form_tambahan">
					
				</div>
					<input type="hidden" id="counter" value="<?=$i?>">
					<span id="tpl_komposisi_racikan" class="hidden"><?=htmlentities($item_row_template)?></span>
					<table class="table table-striped table-bordered table-hover" id="table_komposisi_racikan">
						<thead>
							<tr>
								<th class="text-center" colspan="2" style="width : 15% !important;"><?=translate("Kode", $this->session->userdata("language"))?></th>
								<th class="text-center" style="width : 20% !important;"><?=translate("Nama", $this->session->userdata("language"))?></th>
			                    <th class="text-center" style="width : 10% !important;"><?=translate("Jumlah Dokter", $this->session->userdata("language"))?></th>
			                    <th class="text-center" style="width : 10% !important;"><?=translate("Jumlah Farmasi", $this->session->userdata("language"))?></th>
			                    <th class="text-center" style="width : 10% !important;"><?=translate("Satuan", $this->session->userdata("language"))?></th>
								<th class="text-center" style="width : 15% !important;"><?=translate("Harga", $this->session->userdata("language"))?></th>
								<th class="text-center" style="width : 15% !important;"><?=translate("Sub Total", $this->session->userdata("language"))?></th>
								<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($items_rows as $row):?>
			                    <?=$row?>
			                <?php endforeach;?>
						</tbody>
						<tfoot>
							<tr>
								<td class="text-right" colspan="7"><b><?=translate("Total", $this->session->userdata("language"))?> : </b></td>
								<td class="text-right" colspan="2">
									<div class="input-group">
										<span class="input-group-addon">
									    	<b>Rp.</b>
									    </span>

									    <label id="sub_total" class="control-label form-control"><?=number_format($total,0,'','.')?>,-</label>
										<input type="hidden" id="sub_total" name="sub_total" value="<?=$total?>">
									</div>
								</td>
							</tr>
							<tr>
								<td class="text-right" colspan="7"><b><?=translate("Biaya Tambahan", $this->session->userdata("language"))?> : </b></td>
								<td colspan="2">
									<div class="input-group">
										<span class="input-group-addon">
									    	<b>Rp.</b>
									    </span>
										<input type="number" min="0" class="form-control text-right" value="0" id="biaya_tambahan" name="biaya_tambahan">
									</div>
								</td>
							</tr>
							<tr>
								<td class="text-right" colspan="7"><b><?=translate("Harga Jual", $this->session->userdata("language"))?> : </b></td>
								<td class="text-right" colspan="2">
									<div class="input-group">
										<span class="input-group-addon">
									    	<b>Rp.</b>
									    </span>
									<label id="harga_jual" class="control-label"><?=number_format($total,0,'','.')?>,-</label>
									<input type="hidden" id="harga_jual" name="harga_jual" value="<?=$total?>">
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

<div id="popover_item_content" class="row">
	<div class="form-group">
		<!-- <label class="control-label col-md-3"><?=translate('Gudang', $this->session->userdata('language'))?></label> -->
		<div class="col-md-3" style="float: right; padding-bottom: 10px;">
			<?php 

				$get_gudang = $this->gudang_m->get_by(array('is_active' => 1));
					// die_dump(object_to_array($get_gudang));

				$gudang = object_to_array($get_gudang);

				$gudang_option = array(
					'' => translate('Semua Gudang', $this->session->userdata('language'))
				);
				foreach ($gudang as $data) {
					$gudang_option[$data['id']] = $data['nama'];
				}

				echo form_dropdown('gudang', $gudang_option, "", "id=\"gudang\" class=\"form-control\""); 
			?>
		</div>
	</div>
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_item_search">
            <thead>
                <tr role="row" class="heading">
                    <th><div class="text-center"><?=translate('Id', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Stok', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 
<?=form_close()?>

<div class="modal fade bs-modal-lg" id="popup_modal" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" style="width:1060px;" >
       <div class="modal-content">

       </div>
   </div>
</div>

<div class="modal fade bs-modal-lg" id="popup_modal_file" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-md" style="width: 800px !important;">
       <div class="modal-content">
       
       </div>
   </div>
</div>