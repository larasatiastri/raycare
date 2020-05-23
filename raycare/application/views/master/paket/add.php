<?php
    $this->cabang_m->set_columns(array('id','nama'));
    $categories = $this->cabang_m->get_by(array('tipe' => 1));
    // die_dump($categories);
    $categories_options = array(
    
    '' => translate('Pilih Cabang', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_options[$categories->id] = $categories->nama;
    }

    //////////////////////////////////////////////////////////////////////////////////////

    $this->item_satuan_m->set_columns(array('id','nama'));
    $categories = $this->item_satuan_m->get();
    // die_dump($categories);
    $categories_satuan = array(
    
    '' => translate('Pilih Satuan', $this->session->userdata('language')) . '..',
    );

    // foreach ($categories as $categories) {
    //     $categories_satuan[$categories->id] = $categories->nama;
    // }

    //////////////////////////////////////////////////////////////////////////////////////

    $this->poliklinik_m->set_columns(array('id','nama', 'kode'));
    $where = array(

    	'is_active' => 1

    	);

    $categories = $this->poliklinik_m->get();
    // die_dump($categories);		
    $categories_poliklinik = array();

    // foreach ($categories as $categories) {
    //     $categories_poliklinik[$categories->id] = $categories->nama;
    // }


	$form_attr = array(
		"id"			=> "form_addtemplate", 
		"name"			=> "form_addtemplate", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."master/paket/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));


?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<!-- <i class="fa fa-cogs font-blue-sharp"></i> -->
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Paket", $this->session->userdata("language"))?></span>
		</div>
		
	</div>
	<div class="portlet-body form">
		<div class="portlet-body form">	
			<div class="form-wizard">
				<div class="form-body">
					<div class="alert alert-danger display-hide">
				        <button class="close" data-close="alert"></button>
				        <?=$form_alert_danger?>
				    </div>
				    <div class="alert alert-success display-hide">
				        <button class="close" data-close="alert"></button>
				        <?=$form_alert_success?>
				    </div>
					
					

					<div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Cabang :", $this->session->userdata("language"))?></label>
                        <div class="col-md-2">
                              <?php
                                 echo form_dropdown('tipe_transaksi',  $categories_options, ' ', "id=\"tipe_transaksi\" class=\"form-control bs-select\"")
                                ?>
                        </div>
                    </div>

					<div class="form-group hidden">
						<div class="col-md-1">
							<input class="form-control" value="" id="poliklinik_id" name="poliklinik_id">
						</div>
					</div> 
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Poliklinik :", $this->session->userdata("language"))?></label>
                        <div class="col-md-1 hidden	">
							<input class="form-control" value="" id="poliklinik_id" name="poliklinik_id">
						</div>
                        <div class="col-md-3">
                            <?php

                            echo form_dropdown('poli[]', $categories_poliklinik,'', 'id = "poli" class ="form-control" multiple= "multiple" required');

                            ?>
                        </div>
                    </div>

                    <div class="form-group">
						<label class="control-label col-md-3"><?=translate("Kode :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<?php
								$kode = array(
									"name"			=> "kode",
									"id"			=> "kode",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"placeholder"	=> translate("Kode", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($kode);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Nama :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<?php
								$nama_paket = array(
									"name"			=> "nama_paket",
									"id"			=> "nama_paket",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($nama_paket);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>
						<div class="col-md-4">
							
							<?php
								$keterangan = array(
									"name"			=> "keterangan",
									"id"			=> "keterangan",
									"class"			=> "form-control",
									"rows"			=> 6, 
									"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
								);
								echo form_textarea($keterangan);
							?>
							<!-- <textarea rows="6" class="form-control" id="description" name="description"></textarea> -->
						</div>
					</div>

				    <?php
				    //	$btn_search   = '<div class="text-center"><button title="Search Account" class="btn btn-xs btn-success search-account"><i class="fa fa-search"></i></button></div>';
				    	$btn_search 		= '<div class="text-center"><button type="button" title="" class="btn btn-xs btn-primary search-account"><i class="fa fa-search"></i></button></div>';
				    	$btn_search_titipan = '<div class="text-center"><button type="button" title="" class="btn btn-xs btn-primary search-account-titipan"><i class="fa fa-search"></i></button></div>';
				    	$btn_plus   		= '<div class="text-center"><button title="Add Row" class="btn btn-xs btn-success add_row"><i class="fa fa-plus"></i></button></div>';
						$btn_del           	= '<div class="text-center"><button class="btn btn-xs red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';
						$btn_del_plus       = '<div class="text-center"><button class="btn btn-xs red-intense del-this-plus" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

						$attrs_account_id = array(
						    'id'          => 'account_id_{0}',
						    'name'        => 'account[{0}][account_id]',
						    'class'       => 'form-control input-xs hidden',
						);
						$attrs_account_type = array(
						    'id'          => 'account_type_{0}',
						    'name'        => 'account[{0}][account_type]',
						    'class'       => 'form-control input-xs hidden',
						);

						$attrs_account_code = array(
						    'id'          => 'account_code_{0}',
						    'name'        => 'account[{0}][kode]',
						    'class'       => 'form-control input-xs hidden',
						    'width'		  => '100%',
						    'readonly'    => 'readonly',
						);

						$attrs_account_name = array(
						    'id'          => 'account_name_{0}',
						    'name'        => 'account[{0}][nama]',
						    'class'       => 'form-control input-xs hidden',
						    // 'readonly'    => 'readonly',
						    // 'style'       => 'width:180px;',
						);

						$attrs_account_jumlah = array(
						    'id'          => 'account_jumlah_{0}',
						    'name'        => 'account[{0}][jumlah]',
						    'class'       => 'form-control text-center input-xs',
						    'value'		  => 1,
						    'type'		  => 'number',
						    'min'		  => 0,
						    // 'readonly'    => 'readonly',
						    // 'style'       => 'width:180px;',
						);


						$attrs_account_satuan = array(
						    'id'          => 'account_satuan_{0}',
						    'name'        => 'account[{0}][satuan]',
						    'class'       => 'form-dropdown input-xs',
						    'value'		  => '<?=php?>',
						    // 'readonly'    => 'readonly',
						    // 'style'       => 'width:180px;',
						);

						$attrs_account_names = array(
						    'id'          => 'account_names_{0}',
						    'name'        => 'account[{0}][names]',
						    'class'       => 'form-control input-xs',
						);

						$attrs_account_sub_total = array(
						    'id'          => 'account_sub_total_{0}',
						    'name'        => 'account[{0}][sub_total]',
						    'class'       => 'form-control text-right col-md-12 input-xs hidden',
						    'readonly'    => 'readonly',
						    // 'value'       => 0,
						    // 'style'       => 'width:180px;',
						);

						$attrs_account_harga = array(
						    'id'          => 'account_harga_{0}',
						    'name'        => 'account[{0}][harga]',
						    'class'       => 'form-control col-md-12 text-right input-xs hidden',
						    'readonly'    => 'readonly',
						    // 'value'       => 0,
						    // 'style'       => 'width:180px;',
						);


						// item row column
						$item_cols = array(// style="width:156px;
							'account_code' 	 	=> '<label class="control-label col-md-8" id="account_kode_{0}" name="account[{0}][kode]"></label>'.form_input($attrs_account_id),
							'btn_search'   	 	=> $btn_search,
							'account_name' 	 	=> form_input($attrs_account_name).'<label class="control-label" id="account_nama_{0}" name="account[{0}][nama]"></label>'.form_input($attrs_account_type),
							'account_dijual' 	=> '<div class="text-center"><input type="checkbox" id="account_dijual_{0}" name="account[{0}][dijual]" value="1"></div>',
							'account_jumlah' 	=> form_input($attrs_account_jumlah).form_input($attrs_account_type),
							'account_satuan' 	=> form_dropdown('account[{0}][satuan]',  $categories_satuan, "", "id='account_satuan_{0}' class='form-control bs-select-satuan'"),
							'account_harga' 	=> form_input($attrs_account_harga).'<label class="control-label col-md-12 text-right"  id="account_harga_{0}" name="account[{0}][harga]"></label>'.form_input($attrs_account_type),
							'account_sub_total' => form_input($attrs_account_sub_total).'<label class="control-label col-md-12 text-right subtotal" id="account_sub_total_{0}" name="account[{0}][sub_total]"></label>'.form_input($attrs_account_type),
							'action'       		=> $btn_del,
						);

						/////////////////////////////////////////
						$attrs_account_id_titipan = array(
						    'id'          => 'account_tindakan_id_{0}',
						    'name'        => 'tindakan[{0}][tindakan_id]',
						    'class'       => 'form-control input-xs hidden',
						    'width'		  => '10%',
						    'readonly'    => 'readonly',
						);


						$attrs_account_kode = array(
						    'id'          => 'account_kode_titipan_{0}',
						    'name'        => 'tindakan[{0}][kode_titipan]',
						    'class'       => 'form-control input-xs',
						    'width'		  => '10%',
						    'readonly'    => 'readonly',
						);

						$attrs_account_nama = array(
						    'id'          => 'account_nama_titipan_{0}',
						    'name'        => 'tindakan[{0}][nama_titipan]',
						    'class'       => 'form-control input-xs',
						    'readonly'    => 'readonly',
						    // 'style'       => 'width:180px;',
						);


						$attrs_account_jumlah_tindakan = array(
						    'id'          => 'account_jumlah_tindakan_{0}',
						    'name'        => 'tindakan[{0}][jumlah_tindakan]',
						    'class'       => 'form-control text-center input-xs',
						    'value'       => 1,
						    'type'		  => 'number',
						    'min'		  => 0,
						    // 'style'       => 'width:180px;',
						);

						$attrs_account_harga_tindakan = array(
						    'id'          => 'account_harga_tindakan_{0}',
						    'name'        => 'tindakan[{0}][harga_tindakan]',
						    'class'       => 'form-control text-center input-xs hidden',
						    'readonly'    => 'readonly',
						    // 'value'       => 0,
						    // 'style'       => 'width:180px;',
						);

						$attrs_account_sub_total_tindakan = array(
						    'id'          => 'account_sub_total_tindakan_{0}',
						    'name'        => 'tindakan[{0}][sub_total_tindakan]',
						    'class'       => 'form-control text-center input-xs hidden',
						    'readonly'    => 'readonly',
						    // 'value'       => 0,
						    // 'style'       => 'width:180px;',
						);
						

						$item_cols_acc = array(// style="width:156px;
							'account_code' 		=> form_input($attrs_account_id_titipan).'<label class="control-label col-md-8 text-center" id="tindakan_kode_titipan_{0}" name="tindakan[{0}][kode_titipan]" ></label>',
							'btn_search'   		=> $btn_search_titipan,
							'account_name' 		=> '<label class="control-label" id="tindakan_nama_titipan_{0}" name="tindakan[{0}][nama_titipan]"></label>',
							'account_jumlah' 	=> form_input($attrs_account_jumlah_tindakan).form_input($attrs_account_type),
							'account_harga'	   	=> form_input($attrs_account_harga_tindakan).'<label class="control-label col-md-12 text-right" id="tindakan_harga_titipan_{0}" name="tindakan[{0}][harga_titipan]"></label>',
							'account_sub_total' => form_input($attrs_account_sub_total_tindakan).'<label class="control-label col-md-12 text-right subtotal_titipan" id="tindakan_sub_total_{0}" name="tindakan[{0}][sub_total_titipan]"></label>',
							'action'       		=> $btn_del_plus,
						);

						// gabungkan $item_cols jadi string table row
						$item_row_template =  '<tr id="item_row_{0}" class="table_item"><td>'  . implode('</td><td>', $item_cols) . '</td></tr>';
						$item_row_template_acc =  '<tr id="item_row_plus_{0}" class="row_plus"><td>' . implode('</td><td>', $item_cols_acc) . '</td></tr>';
						
				    ?>
				    <div class="row">
	                    <div class="col-md-12">
	                        <div class="portlet">
	                            <div class="portlet-title">
	                            	<div class="caption">
										<!-- <i class="fa fa-cogs font-blue-sharp"></i> -->
										<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Obat & Alat Kesehatan", $this->session->userdata("language"))?></span>
	                            	</div>
	                            </div>
	                            <div class="portlet-body">
	                                <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
	                                <!-- <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span> -->
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_account">
	                                        <thead>
	                                            <tr role="row" class="heading">
	                                                <th colspan="2" width="20%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
	                                                <th width="15%"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Dijual", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Jumlah", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Sub Total", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Actions", $this->session->userdata('language'))?></div></th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                          
	                                            <!-- <?//=$item_row?> -->
	                                        </tbody>
	                                    </table>
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
										<!-- <i class="fa fa-cogs font-blue-sharp"></i> -->
										<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan", $this->session->userdata("language"))?></span>
	                            	</div>
	                            </div>
	                            <div class="portlet-body">
	                                <!-- <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span> -->
	                                <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span>
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_account_titipan">
	                                        <thead>
	                                            <tr role="row" class="heading">
	                                                <th colspan="2" width="20%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
	                                                <th width="15%"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Jumlah", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Sub Total", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                          
	                                            <!-- <?//=$item_row?> -->
	                                        </tbody>
	                                    </table>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
				</div>

				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-9">Total Obat & Alat Kesehatan :</label>
							<div class="col-md-2">
								<div class="input-group">
									<span class="input-group-addon">
										&nbsp;Rp&nbsp;
									</span>
									<input class="form-control col-md-2 text-right" readonly id="total_bayar" name="total_bayar">
								</div>
								<input class="form-control col-md-2 hidden"  id="total_bayar_hidden" name="total_bayar_hidden">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-9">Total Tindakan :</label>
							<div class="col-md-2">
								<div class="input-group">
									<span class="input-group-addon">
										&nbsp;Rp&nbsp;
									</span>
									<input class="form-control col-md-2 text-right tot_tindakan" readonly id="total_tindakan" name="total_tindakan">
								</div>
								<input class="form-control col-md-2 hidden"  id="total_tindakan_hidden" name="total_tindakan_hidden">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-9">Biaya Tambahan :</label>
							<div class="col-md-2">
								<div class="input-group">
									<span class="input-group-addon">
										&nbsp;Rp&nbsp;
									</span>
									<input class="form-control col-md-2 text-right" id="biaya_tambahan" name="biaya_tambahan">
								</div>
								<input class="form-control col-md-2 hidden"  id="biaya_tambahan_hidden" name="biaya_tambahan_hidden">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-9">Total Keseluruhan :</label>
								<div class="col-md-2">
									<div class="input-group">
										<span class="input-group-addon">
											&nbsp;Rp&nbsp;
										</span>
										<input class="form-control col-md-2 text-right" readonly id="total_keseluruhan" name="total_keseluruhan">
									</div>
									<input class="form-control col-md-2 hidden"  id="total_keseluruhan_hidden" name="total_keseluruhan_hidden">
								</div>
						</div>
					</div>
				</div>

				<?php
					$confirm_save       = translate('Apa Kamu Yakin Akan Tambah Paket Ini ?',$this->session->userdata('language'));
					$submit_text        = translate('Simpan', $this->session->userdata('language'));
					$reset_text         = translate('Reset', $this->session->userdata('language'));
					$back_text          = translate('Kembali', $this->session->userdata('language'));
				?>
				<div class="form-actions fluid">    
				    <div class="col-md-offset-1 col-md-9">
				        
				        <a class="btn default" href="javascript:history.go(-1)"><?=$back_text?></a>
				        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
				        <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>
				        <a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=$submit_text?></a>
				        
				    </div>          
				</div>
			</div>
		</div>

	</div>			
</div>									
</div>
							
<?=form_close();?>
<?php $this->load->view('master/paket/cari_alat_obat.php'); ?> 
<?php $this->load->view('master/paket/cari_titipan'); ?> 
