<?php

	$form_attr = array(
		"id"			=> "form_add_terima_uang", 
		"name"			=> "form_add_terima_uang", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."kasir/titip_terima_uang/save_terima_uang", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');


?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Terima Uang", $this->session->userdata("language"))?></span>
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
						<label class="control-label col-md-4"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-2">
							<div class="input-group date" id="tanggal" >
								<input type="text" class="form-control" id="tanggal" name="tanggal"  readonly >
								<span class="input-group-btn">
									<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
								<!-- value="<?=date('d-M-Y')?>" -->
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Diterima Dari", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-2">
								<input class="form-control input-sm" id="nomer_{0}" name="nama_ref_user" value="<?=$flash_form_data["nama_ref_user"]?>" placeholder="Diterima Oleh" required>
								<input class="form-control input-sm hidden" id="nomer_{0}" name="id_ref_pasien" value="<?=$flash_form_data["id_ref_pasien"]?>" placeholder="ID Referensi Pasien">
								<input class="form-control input-sm hidden" id="nomer_{0}" name="tipe_user" value="<?=$flash_form_data["tipe_user"]?>" placeholder="Tipe User">
								<input class="form-control input-sm hidden" id="nomer_{0}" name="kasir_titip_uang_id" value="<?=$flash_form_data["kasir_titip_uang_id"]?>" placeholder="Kasir Titip Uang ID">
							</div>
							<span class="input-group-btn" style="left:-15px;">
								<a class="btn btn-xs btn-primary pilih-user" style="height:20px;" title="<?=translate('Pilih User', $this->session->userdata('language'))?>">
									<i class="fa fa-search"></i>
								</a>
							</span>
						</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Rupiah :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<?php
								$rupiah = array(
									"name"			=> "rupiah",
									"id"			=> "rupiah",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"placeholder"	=> translate("Rupiah", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($rupiah);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Subjek :", $this->session->userdata("language"))?></label>
						<div class="col-md-2">
							<?php
								$subjek = array(
									"name"			=> "subjek",
									"id"			=> "subjek",
									"autofocus"		=> true,
									"class"			=> "form-control", 
									"placeholder"	=> translate("Subjek", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($subjek);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>
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
				    //	$btn_search   = '<div class="text-center"><button title="Search Account" class="btn btn-sm btn-success search-account"><i class="fa fa-search"></i></button></div>';
				    	$btn_search = '<div class="text-center"><button type="button" title="" class="btn btn-sm btn-success search-account"><i class="fa fa-search"></i></button></div>';
				    	$btn_plus   = '<div class="text-center"><button title="Add Row" class="btn btn-sm btn-success add_row"><i class="fa fa-plus"></i></button></div>';
						$btn_del           = '<div class="text-center"><button class="btn btn-sm red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';
						$btn_del_plus           = '<div class="text-center"><button class="btn btn-sm red del-this-plus" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

						$attrs_account_id = array(
						    'id'          => 'account_id_{0}',
						    'name'        => 'account[{0}][account_id]',
						    'class'       => 'form-control input-sm hidden',
						);
						$attrs_account_type = array(
						    'id'          => 'account_type_{0}',
						    'name'        => 'account[{0}][account_type]',
						    'class'       => 'form-control input-sm hidden',
						);

						$attrs_account_code = array(
						    'id'          => 'account_code_{0}',
						    'name'        => 'account[{0}][code]',
						    'class'       => 'form-control input-sm',
						    'width'		  => '100%',
						    'readonly'    => 'readonly',
						);

						$attrs_account_name = array(
						    'id'          => 'account_name_{0}',
						    'name'        => 'account[{0}][name]',
						    'class'       => 'form-control input-sm',
						    'readonly'    => 'readonly',
						    // 'style'       => 'width:180px;',
						);

						$attrs_account_names = array(
						    'id'          => 'account_names_{0}',
						    'name'        => 'account[{0}][names]',
						    'class'       => 'form-control input-sm',
						);

						$attrs_account_rupiah = array(
						    'id'          => 'account_rupiah_{0}',
						    'name'        => 'account[{0}][rupiah]',
						    'class'       => 'form-control input-sm',
						);

						$attrs_account_keterangan = array(
						    'id'          => 'account_keterangan_{0}',
						    'name'        => 'account[{0}][keterangan]',
						    'class'       => 'form-control input-sm',
						);
						

						// item row column
						$item_cols = array(// style="width:156px;
							'keterangan' 	=> form_input($attrs_account_keterangan).form_input($attrs_account_id),
							'rupiah'	   	=> form_input($attrs_account_rupiah),
							'tanggal'	   	=> '<div class="input-group date" id="tanggal_kasir_biaya">
													<input type="text" class="form-control" id="account_tanggal_kasir_biaya_{0}" name="account[{0}][tanggal_kasir_biaya]" readonly >
													<span class="input-group-btn">
														<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
													</span>
												</div>',
							'action'       	=> $btn_del,
						);

						$item_cols_acc = array(// style="width:156px;
							'account_code' => form_input($attrs_account_code).form_input($attrs_account_id),
							'account_name' => form_input($attrs_account_name).form_input($attrs_account_type),
							'btn_search'   => $btn_search,
							'debit'	   		=> form_input($attrs_account_rupiah),
							// '<div class="text-center"><input type="radio"  id="account_debitkredit_{0}" name="account[{0}][debitkredit]" value="D"></div>',
							'credit'	   => '<div class="text-center"><input type="radio"  id="account_debitkredit_{0}" name="account[{0}][debitkredit]" value="C"></div>',
							'action'       => $btn_del_plus,
						);

						// gabungkan $item_cols jadi string table row
						$item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
						$item_row_template_acc =  '<tr id="item_row_plus_{0}" class="row_plus"><td>' . implode('</td><td>', $item_cols_acc) . '</td></tr>';
						
				    ?>
				    <div class="row">
	                    <div class="col-md-12">
	                        <div class="portlet">
	                            <div class="portlet-title">
	                            	<div class="caption">
					                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Biaya", $this->session->userdata("language"))?></span>
					                </div>
	                            </div>
	                            <div class="actions" style="float: right;">
				                    <a data-toggle="modal" id="add_biaya" class="btn btn-primary">
				                        <i class="fa fa-plus"></i>
				                        <span class="hidden-480">
				                             <?=translate("Tambah", $this->session->userdata("language"))?>
				                        </span>
				                    </a>
				                </div>
	                            <div class="portlet-body">
	                                <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
	                                <!-- <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span> -->
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_account">
	                                        <thead>
	                                            <tr role="row" class="heading">
	                                                <th width="45%"><div class="text-center"><?=translate("Keterangan", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Rupiah", $this->session->userdata('language'))?></div></th>
	                                                <th width="15%"><div class="text-center"><?=translate("Tanggal", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
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
				<?php
					$confirm_save       = translate('Apa kamu yakin ingin menambahkan terima uang ini ?',$this->session->userdata('language'));
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
<?php $this->load->view('reservasi/titip_terima_uang/pilih_user.php'); ?> 