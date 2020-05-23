<?php
    $this->jurnal_template_trans_tipe_m->set_columns(array('id','nama'));
    $categories = $this->jurnal_template_trans_tipe_m->get();
    // die_dump($categories);
    $categories_options = array(
    
    '' => translate('Pilih Tipe', $this->session->userdata('language')) . '..',
    );

    foreach ($categories as $categories) {
        $categories_options[$categories->id] = $categories->nama;
    }

    //////////////////////////////////////////////////////////////////////////

    $this->load->model('akunting/jurnal_template_trans_tipe_m');

	$type = $this->jurnal_template_trans_tipe_m->get_by(array('id' => $journal_data['tipe_transaksi']));
	$type = object_to_array($type);
	// die_dump($type);
	$subjek='';
	foreach ($type as $type) 
	{
		$subjek = $type['subjek'];
		$nama_tipe = $type['nama'];
	}

	if ($subjek != null) 
	{
		$checked = $subjek;
	} else {

		$checked = 'Tidak Ada';
	}

	// die_dump($subjek);
    //////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_view_template", 
		"name"			=> "form_view_template", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$this->load->helper('form');
        $this->load->library('form_builder');
	$flash_form_data  = $this->session->flashdata('form_data');

	$hidden = array(
		"command"	=> "add"
	);

	//////////////////////////////////////////////////////////////////////////

	$this->load->model('master/user_m');

	$type = $this->user_m->get_by(array('id' => $journal_data['created_by']));
	$type = object_to_array($type);
	// die_dump($type);
	$nama='';
	foreach ($type as $type) 
	{
		$nama = $type['nama'];
	}

						
	echo form_open(base_url()."akunting/jurnal_template/save", $form_attr,$hidden);
?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs font-green-sharp"></i>
			<span class="caption-subject font-green-sharp bold uppercase"><?=translate("Jurnal Template", $this->session->userdata("language"))?></span>
		</div>
		
	</div>
	<div class="portlet-body form">
		<div class="portlet-body form">	
			<div class="form-wizard">
				<div class="form-body">
					
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Nama Template :", $this->session->userdata("language"))?></label>		
						
							<div class="col-md-4">
								<label ><?=$journal_data['nama_template']?></label>
							</div>
					</div>
					
					<div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Transaksi Tipe :", $this->session->userdata("language"))?></label>
                        <div class="col-md-4">
                        	<?php
                        		$this->load->model('jurnal_template_trans_tipe_m');
                        		$type = $this->jurnal_template_trans_tipe_m->get_by(array('id' => $journal_data['tipe_transaksi']));
                        		$type = object_to_array($type);
                        		$name = '';
                        		foreach ($type as $type) {
                        			$name = $type['nama'];
                        		}
                        	?>
                        	<input type="hidden" name="tipe_transaksi" id="tipe_transaksi" value="<?=$journal_data['tipe_transaksi']?>">
                        	<label ><?=$name?></label>
                        </div>
                    </div>

					<div class="section-1">
						<div class="form-group">
							<label class="control-label col-md-3" id="type_t" name="type_t">Journal to <?=$nama_tipe?> account payable :</label>
							<div class="col-md-9">
								<label><?=$checked?></label>
								<!-- <input type="checkbox" class="make-switch" id="acc-payable" name="acc-payable" data-size="normal"  data-yes-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="No"> -->
							</div>
						</div>
					</div>
						
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Created By :", $this->session->userdata("language"))?></label>
						<div class="col-md-4">
							<label><?=$nama?></label>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>
						<div class="col-md-4">
							<textarea rows="6" readonly class="form-control" id="description" name="description" ><?=$journal_data['keterangan'];?></textarea>
						</div>
					</div>
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

					
					
						// // item row column
						$item_cols = array(// style="width:156px;
							'account_code' => form_input($attrs_account_code).form_input($attrs_account_id),
							'account_name' => form_input($attrs_account_name),
							'debt_cred'	   => '<div class="text-center"><input type="checkbox" class="make-switch" id="account_debitkredit_{0}" name="account[{0}][debitkredit]" data-size="normal"  data-yes-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="Credit"></div>',
							'action'       => $btn_del,
						);

						// $item_cols_acc = array(// style="width:156px;
						// 	'account_code' => form_input($attrs_account_code),form_input($attrs_account_id),
						// 	'account_name' => form_input($attrs_account_names),
						// 	'btn_search'   => $btn_search,
						// 	'debt_cred'	   => '<div class="text-center"><input type="checkbox" class="make-switch" id="account_debitkredit_{0}" name="account[{0}][debitkredit]" data-size="normal"  data-yes-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="Credit"></div>',
						// 	'variables'    => form_input($attrs_variables),
						// 	'action'       => $btn_del_plus,
						// );

						// // gabungkan $item_cols jadi string table row
						// $item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
						// $item_row_template_acc =  '<tr id="item_row_plus_{0}" class="row_plus"><td>' . implode('</td><td>', $item_cols_acc) . '</td></tr>';
						

						// ///////////////////////////////---------------------------------------------------------------
						$item_row_template =  '';
						
						$check ='';
						foreach($journal_details_data as $row){
						
										
								$dummy_data = array(
									array($row['akun_id'],$row['akun_id'],'<div class="text-center"><input type="checkbox" class="make-switch" id="account_debitkredit_{0}" name="account[{0}][debitkredit]" data-size="normal" value=1 data-yes-color="success" data-off-color="danger" data-on-text="Debit" data-off-text="Credit" checked></div>')
							);

						}

						foreach ($journal_details_data as $key=>$data) {

							if($data['debit_credit'] == 'D'){
									$check = 'checked';
							}else $check = '';	


							$account_info = $this->akun_m->get_by(array('id' => $data['akun_id']));
							// $akun_id = object_to_array($account_info);
							// die_dump($account_info);
							// die_dump($this->db->last_query());


							
							foreach($account_info as $account_info)
							{
								
								$name = $account_info->nama;
								$no_account = $account_info->no_akun;
								
							}

						    $attrs_account_code['value']=$no_account;
						    $attrs_account_name['value'] = $name;


							$item_cols = array(// style="width:156px;
								'account_code' => form_input($attrs_account_code),
								'account_name' => form_input($attrs_account_name),
								'' => '',
								'debt_cred'	   => '<div class="text-center"><input type="checkbox" class="make-switch" id="account_debitkredit_{0}" name="account[{0}][debitkredit]" data-size="normal" value=1 data-yes-color="success" data-off-color="danger" data-on-text="Debit" data-off-text="Credit" '. $check .' readonly></div>',
							);

						    $item_dummy =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
						    $dummy_rows[] = str_replace('{0}', "{$key}", $item_dummy );
						}
				    ?>
				    <div class="row">
	                    <div class="col-md-12">
	                        <div class="portlet light">
	                            <div class="portlet-title"></div>
	                            <div class="portlet-body">
	                                <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
	                                <div class="table-responsive">
	                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_account">
	                                        <thead>
	                                            <tr role="row" class="heading">
	                                                <th colspan="3" width="50%"><div class="text-center"><?=translate("Account", $this->session->userdata('language'))?></div></th>
	                                                <th><div class="text-center"><?=translate("Debit / Credit", $this->session->userdata('language'))?></div></th>
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
					$back_text          = translate('Back', $this->session->userdata('language'));
				?>
				<div class="form-actions fluid">    
				    <div class="col-md-offset-1 col-md-9">
				        
				        <a class="btn default" href="javascript:history.go(-1)"><?=$back_text?></a>
				        
				    </div>          
				</div>

			</div>
		</div>

<?=form_close();?>
