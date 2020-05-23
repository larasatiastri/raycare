<?php
    $this->cabang_m->set_columns(array('id','nama'));
    $categories = $this->cabang_m->get();
    // die_dump($categories);
    $categories_options = array(
    
    '' => translate('Pilih Cabang', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_options[$categories->id] = $categories->nama;
    }

    $this->item_satuan_m->set_columns(array('id','nama'));
    $categories = $this->item_satuan_m->get();
    // die_dump($categories);
    $categories_satuan = array(
    
    '' => translate('Pilih Satuan', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_satuan[$categories->id] = $categories->nama;
    }


	$form_attr = array(
		"id"			=> "form_viewtemplate", 
		"name"			=> "form_viewtemplate", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."master/paket/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$back_text = translate('Kembali', $this->session->userdata('language'));


?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Lihat Paket", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn default" href="javascript:history.go(-1)"><i class="fa fa-arrow-left"></i> <?=$back_text?></a>
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
                        		$this->load->model('cabang_m');
                        		$type = $this->cabang_m->get_by(array('id' => $paket_data['cabang_id']));
                        		$type = object_to_array($type);
                        		// die_dump($type);
                        		$name = '';
                        		foreach ($type as $type) {
                        			$name = $type['nama'];
                        		}
                        	?>
                        	<label class="control-label" ><?=$name?></label>
                        </div>
                    </div>

                    <div class="form-group">
                    	<label class="control-label col-md-3"><?=translate('Poliklinik', $this->session->userdata('language'))?> :</label>
                    	<div class="col-md-4">
                    		<label class="control-label">
                    		<?php 
                    			$poliklinik_id = $this->poliklinik_paket_m->get_by(array('paket_id' => $pk_value));

                    			echo '<ul style="list-style-type: none; text-align: left; padding: 0px; margin:0px;">'; 
                    			foreach ($poliklinik_id as $row)
                    			{
                    				$poliklinik = $this->poliklinik_m->get($row->poliklinik_id);
                    				echo '<li>'.$poliklinik->nama.'</li>';
                    			}
                    			echo '</ul>';

                    	 	?>
                    		
                    		</label>
                    	</div>
                    </div>		

                    <div class="form-group">
						<label class="control-label col-md-3"><?=translate("Kode :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<label class='control-label'><?=$paket_data['kode']?></label>
                        	<input type="hidden" name="paket_id" value="<?=$paket_data['id']?>" id="paket_id" class="form-control" required="required" autofocus="1" readonly>

						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Nama :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<label class="control-label"><?=$paket_data['nama']?></label>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>
						<div class="col-md-3">
							<label class="control-label" style="text-align: left;"><?=$paket_data['keterangan']?></label>
							<!-- <textarea rows="6" class="form-control" id="description" name="description"></textarea> -->
						</div>
					</div>
					<div class="form-group"></div>


				    <?php
				    //	$btn_search   = '<div class="text-center"><button title="Search Account" class="btn btn-xs btn-success search-account"><i class="fa fa-search"></i></button></div>';
				    	$btn_search 		= '<div class="text-center"><button type="button" title="" class="btn btn-xs btn-success search-account"><i class="fa fa-search"></i></button></div>';
				    	$btn_search_titipan = '<div class="text-center"><button type="button" title="" class="btn btn-xs btn-success search-account-titipan"><i class="fa fa-search"></i></button></div>';
				    	$btn_plus   		= '<div class="text-center"><button title="Add Row" class="btn btn-xs btn-success add_row"><i class="fa fa-plus"></i></button></div>';
						$btn_del           	= '<div class="text-center"><button class="btn btn-xs red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';
						$btn_del_plus       = '<div class="text-center"><button class="btn btn-xs red del-this-plus" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

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
						    'type'		  => 'number'
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
						    'class'       => 'form-control text-center input-xs hidden',
						    'readonly'    => 'readonly',
						    // 'value'       => 0,
						    // 'style'       => 'width:180px;',
						);

						$attrs_account_harga = array(
						    'id'          => 'account_harga_{0}',
						    'name'        => 'account[{0}][harga]',
						    'class'       => 'form-control text-center input-xs hidden',
						    'readonly'    => 'readonly',
						    // 'value'       => 0,
						    // 'style'       => 'width:180px;',
						);


						// item row column
						$item_cols = array(// style="width:156px;
							'account_code' 	 	=> '<label class="control-label" id="account_kode_{0}" name="account[{0}][kode]"></label>'.form_input($attrs_account_id),
							'btn_search'   	 	=> $btn_search,
							'account_name' 	 	=> form_input($attrs_account_name).'<label class="control-label" id="account_nama_{0}" name="account[{0}][nama]"></label>'.form_input($attrs_account_type),
							'account_dijual' 	=> '<div class="text-center"><input type="checkbox" id="account_dijual_{0}" name="account[{0}][dijual]" value="1"></div>',
							'account_jumlah' 	=> form_input($attrs_account_jumlah).form_input($attrs_account_type),
							'account_satuan' 	=> form_dropdown('tipe_satuan',  $categories_satuan, "", "id='tipe_satuan' class='form-control bs-select-satuan'"),
							'account_harga' 	=> form_input($attrs_account_harga).'<label class="control-label"  id="account_harga_{0}" name="account[{0}][harga]"></label>'.form_input($attrs_account_type),
							'account_sub_total' => form_input($attrs_account_sub_total).'<label class="control-label subtotal" id="account_sub_total_{0}" name="account[{0}][sub_total]"></label>'.form_input($attrs_account_type),
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
						    'type'		  => 'number'

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
							'account_code' 		=> form_input($attrs_account_id_titipan).'<label class="control-label col-md-4 text-center" id="tindakan_kode_titipan_{0}" name="tindakan[{0}][kode_titipan]"> </label>',
							// form_input($attrs_account_kode),
							'btn_search'   		=> $btn_search_titipan,
							'account_name' 		=> '<label class="control-label" id="tindakan_nama_titipan_{0}" name="tindakan[{0}][nama_titipan]"></label>',
							// form_input($attrs_account_nama),
							'account_jumlah' 	=> form_input($attrs_account_jumlah_tindakan).form_input($attrs_account_type),
							'account_harga'	   	=> form_input($attrs_account_harga_tindakan).'<label class="control-label" id="tindakan_harga_titipan_{0}" name="tindakan[{0}][harga_titipan]"></label>',
							// form_input($attrs_account_harga_tindakan),
							'account_sub_total' => form_input($attrs_account_sub_total_tindakan).'<label class="control-label subtotal_titipan" id="tindakan_sub_total_{0}" name="tindakan[{0}][sub_total_titipan]"></label>',
							// form_input($attrs_account_sub_total_tindakan),
							'action'       		=> $btn_del_plus,
						);

						// gabungkan $item_cols jadi string table row
						// $item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
						// $item_row_template_acc =  '<tr id="item_row_plus_{0}" class="row_plus"><td>' . implode('</td><td>', $item_cols_acc) . '</td></tr>';
						
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
	                                <!-- <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span> -->
	                                <!-- <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span> -->
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_account">
	                                        <thead>
	                                            <tr role="row" class="heading">
	                                                <th><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Dijual", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Jumlah", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Sub Total", $this->session->userdata('language'))?></div></th>
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
	                                <!-- <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span> -->
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_account_titipan">
	                                        <thead>
	                                            <tr role="row" class="heading">
	                                                <th><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Jumlah", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Sub Total", $this->session->userdata('language'))?></div></th>
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
								<label class="control-label" name="sub_total" id="sub_total"></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-9">Total Tindakan :</label>
							<div class="col-md-2">
								<label class="control-label" name="sub_total_tindakan" id="sub_total_tindakan"></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-9">Biaya Tambahan :</label>
							<div class="col-md-2">
								<label class="control-label"><?='Rp. '.number_format($paket_data['biaya_tambahan'], 0, '', '.').',-'?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-9">Total Keseluruhan :</label>
							<div class="col-md-2">
								<label class="control-label"><?='Rp. '.number_format($paket_data['harga_total'], 0, '', '.').',-'?></label>
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
				<div class="form-actions ">    
				   <!--  <div class="col-md-offset-1 col-md-9">
				        <a class="btn default" href="javascript:history.go(-1)"><?=$back_text?></a>
				    </div>        -->   
				</div>
			</div>
		</div>

	</div>			
</div>									
</div>
							
