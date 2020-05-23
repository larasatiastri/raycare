<?php
    $this->jurnal_template_trans_tipe_m->set_columns(array('id','nama'));
    $categories = $this->jurnal_template_trans_tipe_m->get();
    $categories_options = array(
    
    '' => translate('Choose Type', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_options[$categories->id] = $categories->nama;
    }

    ///////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_edittemplate", 
		"name"			=> "form_edittemplate", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "edit",
		  'id' => $pk_value
	);
	echo form_open(base_url()."akunting/jurnal_template/save", $form_attr,$hidden);
?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs font-green-sharp"></i>
			<span><?=translate("Journal Template", $this->session->userdata("language"))?></span>
		</div>
		
	</div>
	<div class="portlet-body form">
		<div class="portlet-body form">	
			<div class="form-wizard">
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Nama Template :", $this->session->userdata("language"))?></label>		
						<div class="col-md-2">
							<?php
								$nama_template = array(
									"name"			=> "nama_template",
									"id"			=> "nama_template",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"placeholder"	=> translate("Nama Template", $this->session->userdata("language")), 
									"required"		=> "required",
									"value"			=> $form_data['nama_template'],
								);
								echo form_input($nama_template);
							?>

						</div>
					</div>
					<div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Transaction Type :", $this->session->userdata("language"))?></label>
                        <div class="col-md-2">
                              <?php
                                 echo form_dropdown('tipe_transaksi',  $categories_options, $form_data['tipe_transaksi'], "id=\"tipe_transaksi\" class=\"form-control bs-select\"")
                                ?>
                        </div>
                    </div>
                    <?php
                    $c = '';
                    	foreach ($journal_details_data as $data) {
                    		if($data['akun_id']==0){
                    			$c = 'checked';
                    		}
                    		
                    	}
                    ?>
					 <div class="section-1">
						<div class="form-group">
						<label class="control-label col-md-3" ></label>
							<div class="col-md-4">
								<div class="checkbox-list checkboxnya">
									<label class="checkbox-inline" >
									<input type="checkbox" name="acc_payable"  id="acc_payable" value="1" <?=$c?>> <label id="type_t" name="type_t"></label></label>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Create By :", $this->session->userdata("language"))?></label>
						<div class="col-md-2">
							<?php
								$created_by = array(
									"name"			=> "created_by",
									"id"			=> "created_by",
									"autofocus"		=> true,
									"class"			=> "form-control", 
									"value"			=> $this->session->userdata('nama_lengkap'),
									"readonly"		=> "readonly",
								);
								echo form_input($created_by);
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Description :", $this->session->userdata("language"))?></label>
						<div class="col-md-4">
							
							<?php
								$keterangan = array(
									"name"			=> "keterangan",
									"id"			=> "keterangan",
									"class"			=> "form-control",
									"rows"			=> 6, 
									"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
									"required"		=> "required",
									"value"			=> $form_data['keterangan'],

								);
								echo form_textarea($keterangan);
							?>

						</div>
					</div>

				
				    <?php
				    //	$btn_search   = '<div class="text-center"><button title="Search Account" class="btn btn-sm btn-success search-account"><i class="fa fa-search"></i></button></div>';
				    	$btn_search 	   = '<div class="text-center"><button type="button" title="" class="btn btn-sm btn-success search-account"><i class="fa fa-search"></i></button></div>';
				    	$btn_plus   	   = '<div class="text-center"><button title="Add Row" class="btn btn-sm btn-success add_row"><i class="fa fa-plus"></i></button></div>';
						$btn_del           = '<div class="text-center"><button class="btn btn-sm red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';
						$btn_del_plus      = '<div class="text-center"><button class="btn btn-sm red del-this-plus" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

						$attrs_is_deleted = array(
						    'id'       => 'is_deleted_{0}',
						    'name'     => 'account[{0}][is_deleted]',
						    'readonly' => 'readonly',
						    'class'       => 'form-control input-sm hidden',
						);

						$attrs_id_detail = array(
						    'id'       => 'account_id_detail_{0}',
						    'name'     => 'account[{0}][id_detail]',
						    'readonly' => 'readonly',
						    'class'    => 'form-control input-sm hidden',
						);

						$attrs_account_id = array(
						    'id'          => 'account_id_{0}',
						    'name'        => 'account[{0}][account_id]',
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

						

						// item row column
						$item_cols = array(// style="width:156px;
							'account_code' => form_input($attrs_account_code).form_input($attrs_account_id).form_input($attrs_id_detail).form_input($attrs_is_deleted),
							'account_name' => form_input($attrs_account_name),
							'btn_search'   => $btn_search,
							'debit'	   		=> '<div class="text-center"><input type="radio"  id="account_debitkredit_{0}" name="account[{0}][debitkredit]" value="D" ></div>',

							'credit'	   => '<div class="text-center"><input type="radio"  id="account_debitkredit_{0}" name="account[{0}][debitkredit]" value="C"></div>',

							// 'debt_cred'	   => '<div class="text-center"><input type="checkbox" class="make-switch" id="account_debitkredit_{0}" name="account[{0}][debitkredit]" data-size="normal"  data-yes-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="Credit"></div>',
							'action'       => $btn_del,
						);

						$item_cols_acc = array(// style="width:156px;
							'account_code' => form_input($attrs_account_code),form_input($attrs_account_id).form_input($attrs_id_detail).form_input($attrs_is_deleted),
							'account_name' => form_input($attrs_account_names),
							'btn_search'   => $btn_search,
							'debit'	   		=> '<div class="text-center"><input type="radio"  id="account_debitkredit_{0}" name="account[{0}][debitkredit]" value="D" ></div>',

							'credit'	   => '<div class="text-center"><input type="radio"  id="account_debitkredit_{0}" name="account[{0}][debitkredit]" value="C"></div>',

							// 'debt_cred'	   => '<div class="text-center"><input type="checkbox" class="make-switch" id="account_debitkredit_{0}" name="account[{0}][debitkredit]" data-size="normal"  data-yes-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="Credit"></div>',
							'action'       => $btn_del,
						);

						// gabungkan $item_cols jadi string table row
						$item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
						$item_row_template_acc =  '<tr id="item_row_plus_{0}" class="row_plus"><td>' . implode('</td><td>', $item_cols_acc) . '</td></tr>';
						///////////////////////////////////////////////////////////////////

						
						$check ='';
						foreach($journal_details_data as $row){
								$dummy_data = array(
									array($row['akun_id'],$row['akun_id'],'<div class="text-center"><input type="checkbox" class="make-switch" id="account_debitkredit_{0}" name="account[{0}][debitkredit]" data-size="normal" value=1 data-yes-color="success" data-off-color="danger" data-on-text="Debit" data-off-text="Credit" checked></div>')
							);
						}
						$i = 0;
						foreach ($journal_details_data as $key=>$data) {
				    	$btn_search_a = '<div class="text-center"><button type="button" disabled title="" class="btn btn-sm btn-success search-account"><i class="fa fa-search"></i></button></div>';

							if($data['debit_credit'] == 'D'){
									$checka = 'checked';
									$checkb = '';

							} else if($data['debit_credit'] == 'C'){
								$checkb = 'checked';
								$checka = '';
							} 

							if($data['akun_id']!=0){
								$account_info = $this->akun_m->get_by(array('id' => $data['akun_id']));
									foreach($account_info as $account_info)
									{
										$name = $account_info->nama;
										$no_account = $account_info->no_akun;
									}
							}else{
										$name = 'Akun Hutang Supplier Bersangkutan';
										$no_account = 0;
							}

								$attrs_account_id['value']=$data['akun_id'];
							    $attrs_account_code['value']=$no_account;
							    $attrs_account_name['value'] = $name;
	 							$attrs_id_detail['value'] = $data['id'];

							$item_cols = array(// style="width:156px;
								'account_code' => form_input($attrs_account_code).form_input($attrs_id_detail).form_input($attrs_account_id).form_input($attrs_is_deleted),
								'account_name' => form_input($attrs_account_name),
								'btn_search'   => $btn_search_a,
								'debit'	   		=> '<div class="text-center"><input type="radio"  id="account_debitkredit_{0}" name="account[{0}][debitkredit]" value="D" '.$checka.'></div>',
								'credit'	   => '<div class="text-center"><input type="radio"  id="account_debitkredit_{0}" name="account[{0}][debitkredit]" value="C" '.$checkb.'></div>',

								// 'debt_cred'	   => '<div class="text-center"><input type="checkbox" class="make-switch" id="account_debitkredit_{0}" name="account[{0}][debitkredit]" data-size="normal" value=1 data-yes-color="success" data-off-color="danger" data-on-text="Debit" data-off-text="Credit" '. $check .' ></div>',
								'action'       => $btn_del,
								
							);
						    $item_dummy =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
						    $dummy_rows[] = str_replace('{0}', "{$key}", $item_dummy );
						    $i++;
						}
				    ?>
				    <div class="row">
				    	<input type="hidden" name="index" value="<?=$i?>">
				    	
	                    <div class="col-md-12">
	                        <div class="portlet">
	                            <div class="portlet-title">
	                            </div>
	                            <div class="portlet-body">
	                                <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
	                                <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span>
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_account">
	                                        <thead>
	                                            <tr role="row" class="heading">
	                                                <th colspan="3" width="50%"><div class="text-center"><?=translate("Account", $this->session->userdata('language'))?></div></th>
	                                                <!-- <th><div class="text-center"><?=translate("Debit / Credit", $this->session->userdata('language'))?></div></th> -->
	                                                <th><div class="text-center"><?=translate("Debit", $this->session->userdata('language'))?></div></th>

	                                                <th><div class="text-center"><?=translate("Credit", $this->session->userdata('language'))?></div></th>

	                                                <th><div class="text-center"><?=translate("Actions", $this->session->userdata('language'))?></div></th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                          					<?php foreach ($dummy_rows as $row):?>
											                       	<?=$row?>
											                    <?php endforeach;?>
	                                        </tbody>
	                                    </table>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
				</div>
				<?php
					$confirm_save       = translate('Are you sure you want to add this template?',$this->session->userdata('language'));
					$submit_text        = translate('Save', $this->session->userdata('language'));
					$reset_text         = translate('Reset', $this->session->userdata('language'));
					$back_text          = translate('Back', $this->session->userdata('language'));
				?>
				<div class="form-actions fluid">    
				    <div class="col-md-offset-1 col-md-9">
				        
				        <a class="btn default" href="javascript:history.go(-1)"><?=$back_text?></a>
				        <button type="reset" class="btn default"><?=$reset_text?></button>
				        <button type="submit" id="save" class="btn green-haze hidden" ><?=$submit_text?></button>
				        <a id="confirm_save" class="btn green-haze" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=$submit_text?></a>
				        
				    </div>          
				</div>
			</div>
		</div>

	</div>			
</div>									
</div>
							
<?=form_close();?>
<?php $this->load->view('akunting/jurnal_template/cari_akun.php'); ?> 
	