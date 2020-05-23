<?php
	$form_attr = array(
		"id"			=> "form_edit_stock_opname", 
		"name"			=> "form_edit_stock_opname", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);
	$hidden = array(
		"command"	=> "edit"
	);
	$id = array(
		'id'    => 'id',
    	'type'  => 'hidden',
    	'value' => $pk_value
	);
	echo form_open(base_url()."apotik/stock_opname/save", $form_attr,$hidden,$id);
					
	$date = date_create($form_data['tanggal_mulai']);
	if ($form_data['tanggal_mulai'] != NULL) {
		$start_date = date_format($date, 'd F Y h:i A');
	} else{
		$start_date = '';
	}

	$form_config = array(
    	'default_control_label_class' => 'col-md-4 control-label',
    	'default_form_control_class'  => 'col-md-8',
    	'default_form_class'          => 'form-horizontal col-md-12',
    );

$this->form_builder->init($form_config);

$btn_search = '<div class="text-center"><button type="button" title="'.translate('Cari Item', $this->session->userdata('language')).'" class="btn btn-sm btn-success search-item"><i class="fa fa-search"></i></button></div>';
$btn_del    = '<div class="text-center"><button type="button" class="btn btn-sm red-intense del-this" title="'.translate('Hapus Item', $this->session->userdata('language')).'"><i class="fa fa-times"></i></button></div>';


$attrs_is_delete = array (
    'id'       => 'is_deleted{0}',
    'name'     => 'items[{0}][is_deleted]',
    'class'    => 'form-control input-sm hidden',
    // 'style'    => 'width:80px;',
    'readonly' => 'readonly',
    // 'value' => 'BLSG01',
);

$attrs_sto_id = array (
    'id'       => 'sto_id_{0}',
    'name'     => 'items[{0}][id]',
    'class'    => 'form-control input-sm hidden',
    // 'style'    => 'width:80px;',
    'readonly' => 'readonly',
    // 'value' => 'BLSG01',
);

$attrs_item_id = array (
    'id'       => 'items_id_{0}',
    'name'     => 'items[{0}][item_id]',
    'class'    => 'form-control input-sm hidden',
    // 'style'    => 'width:80px;',
    'readonly' => 'readonly',
    // 'value' => 'BLSG01',
);

$attrs_item_code = array (
    'id'       => 'items_code_{0}',
    'name'     => 'items[{0}][code]',
    'class'    => 'form-control input-sm',
    // 'style'    => 'width:80px;',
    'readonly' => 'readonly',
    // 'value' => 'BLSG01',
);

$attrs_item_name = array(
    'id'       => 'items_name_{0}',
    'name'     => 'items[{0}][name]',
    'class'    => 'form-control input-sm ',
    'readonly' => 'readonly',
    'style'       => 'width:700px;',
    // 'value' => 'Selongsong Gas HND ASTREA',
);
$attrs_item_qty = array(
    'id'       => 'items_name_{0}',
    'name'     => 'items[{0}][system_qty]',
    'class'    => 'form-control input-sm hidden',
    'readonly' => 'readonly',
    // 'value' => 'Selongsong Gas HND ASTREA',
);
$attrs_item_satuan = array(
    'id'       => 'items_name_{0}',
    'name'     => 'items[{0}][item_satuan_id]',
    'class'    => 'form-control input-sm hidden',
    'readonly' => 'readonly',
     
    // 'value' => 'Selongsong Gas HND ASTREA',
);

$attrs_item_satuan_text = array(
    'id'       => 'items_name_{0}',
    'name'     => 'items[{0}][item_satuan_text]',
    'class'    => 'form-control input-sm ',
    'readonly' => 'readonly',
   
    // 'value' => 'Selongsong Gas HND ASTREA',
);

$item_cols = array(
    'item_code'   => form_input($attrs_is_delete).form_input($attrs_sto_id).form_input($attrs_item_id).form_input($attrs_item_code).form_input($attrs_item_qty).form_input($attrs_item_satuan),
    'item_search' => $btn_search,
    'item_name'   => form_input($attrs_item_name),
    'item_satuan'  => form_input($attrs_item_satuan_text),
    'action'      => $btn_del,
);
$item_row_template =  '<tr id="item_row_{0}" class="table-edit-stock-opname"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

$dummy_data = array();

$records = $form_data_item->result_array();

// die_dump($records);
$btn_search = '<div class="text-center"><button type="button" disabled title="'.translate('Cari Item', $this->session->userdata('language')).'" class="btn btn-sm btn-success search-item"><i class="fa fa-search"></i></button></div>';
$btn_del    = '<div class="text-center"><button type="button" data-toggle="tooltip" data-placement="top" data-original-title="The last tip!" class="btn btn-sm red-intense del-item-db" title="'.translate('Hapus3 Item', $this->session->userdata('language')).'"><i class="fa fa-times"></i></button></div>';

foreach ($records as $key=>$data) {
	// die_dump($data['id']);
    $attrs_sto_id['value'] = $data['id'];
    $attrs_item_id['value'] = $data['item_id'];
    $attrs_item_code['value'] = $data['kode'];
    $attrs_item_name['value'] = $data['nama'];
    $attrs_item_satuan['value'] = $data['item_satuan_id'];
    $attrs_item_satuan_text['value'] = $data['nama_satuan'];
    $attrs_item_qty['value']=$data['jumlah_sistem'];
    
    $item_cols = array(
        'item_code'   => form_input($attrs_is_delete).form_input($attrs_sto_id).form_input($attrs_item_id).form_input($attrs_item_code).form_input($attrs_item_satuan).form_input($attrs_item_qty),
        'item_search' => $btn_search,
        'item_name'   => form_input($attrs_item_name),
        'item_satuan'  => form_input($attrs_item_satuan_text),
        'action'      => $btn_del,
    );

    $item_dummy =  '<tr id="item_row_{0}" class="table-edit-stock-opname"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
    $dummy_rows[] = str_replace('{0}', "{$key}", $item_dummy );
}

?>	
<style type="text/css">
	.form-group{
		margin-bottom: 10px;
	}
</style>
<div class="portlet light">
    <div class="portlet-body form">
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Information", $this->session->userdata("language"))?></span>
			</div> <input type="hidden" id="pk" name="pk" value="<?=$warehouse_id?>"><input type="hidden" id="pk2" name="pk2" value="<?=$warehouse_people_id?>">
			<div class="tools">
				<a href="javascript:;" class="collapse">
				</a>	
			</div>
		</div>
		<div class="portlet-body form">	
			<div class="form-wizard">
				<div class="form-body">
					<div class="form-group hidden">
						<label class="control-label col-md-3">ID</label>
						<div class="col-md-1">
							<input class="form-control" id="id" name="id" value="<?=$pk_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Nomor Stok Opname", $this->session->userdata("language"))?> :</label>
						<div class="col-md-4">
							<label class="control-label"><?=$form_data['no_stok_opname']?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Stok Opname oleh", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-4">
							<label class="control-label"><?=$form_data_people['nama']?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Tanggal Mulai", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-4">
							<label class="control-label"><?=$start_date?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">
							<?=translate("Gudang", $this->session->userdata("language"))?> :
						</label>
						<div class="col-md-3">
							<label class="control-label"><?=$form_data_warehouse['nama']?></label>
							<input class="input-sm hidden" id="warehouse_id" value="<?=$warehouse_id?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">
							<?=translate("Created By", $this->session->userdata("language"))?> :
						</label>
						<div class="col-md-3">
							<label class="control-label"><?=$form_data2[0]['nama1']?></label>
							<input class="input-sm hidden" id="createdby" value="<?=$warehouse_id?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">
							<?=translate("Created Date", $this->session->userdata("language"))?> :
						</label>
						<div class="col-md-3">
							<label class="control-label"><?if($form_data2[0]['created_date']!='-'){?><?=date('d M Y H:i:s',strtotime($form_data2[0]['created_date']))?><?}else{?><?=$form_data2[0]['created_date']?><?}?></label>
							<input class="input-sm hidden" id="createddate" value="<?=$warehouse_id?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">
							<?=translate("Modified By", $this->session->userdata("language"))?> :
						</label>
						<div class="col-md-3">
							<label class="control-label"><?=$form_data2[0]['nama2']?></label>
							<input class="input-sm hidden" id="modifiedby" value="<?=$warehouse_id?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">
							<?=translate("Modified Date", $this->session->userdata("language"))?> :
						</label>
						<div class="col-md-3">
							<label class="control-label"><?if($form_data2[0]['modified_date']!='-'){?><?=date('d M Y H:i:s',strtotime($form_data2[0]['modified_date']))?><?}else{?><?=$form_data2[0]['modified_date']?><?}?></label>
							<input class="input-sm hidden" id="modifieddate" value="<?=$form_data2[0]['modified_date']?>">
						</div>
					</div>


					<div class="row">
					    <div class="col-md-12">
					        <div class="portlet" id="item_temporary" style="display:none">
					            <div class="portlet-title">
					                <div class="caption">
					                    <i class=""></i><?=translate("New Stock Opname Item", $this->session->userdata("language"))?>
					                </div>

					               
					            </div>

					            <div class="portlet-body">
					            	<div class="form-body">
						                <table class="table table-striped table-bordered table-hover" >
						                    <thead>
						                        <tr role="row" class="heading">
						                            <th scope="col" ><div class="text-center"><?=translate("Item Code", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" ><div class="text-center"><?=translate("Item Name", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" ><div class="text-center"><?=translate("Warehouse", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" ><div class="text-center"><?=translate("Action", $this->session->userdata("language"))?></div></th>
						                            
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

					<div class="row">
					    <div class="col-md-12">
					            <div class="form-group"></div>
					        <div class="portlet">
					            <div class="portlet-title">
					                <div class="caption">
					                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Item Stok Opname", $this->session->userdata("language"))?></span>
					                </div>

					                <div class="actions"> <!-- <a href="#" class="btn default btn-sm">
					                                                <i class="fa fa-pencil icon-black"></i> Edit </a> -->
					                    
					                </div>
					                
					            </div>

					            <div class="portlet-body">
					            	<div class="form-body">
                    					<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
						                <table class="table table-condensed table-striped table-bordered table-hove" id="table_edit_stock_opname">
						                    <thead>
						                        <tr role="row" class="heading">
						                            <th scope="col" colspan="2" ><div class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" ><div class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" ><div class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></div></th>
						                            <th scope="col" width="10%"><div class="text-center"><?=translate("Action", $this->session->userdata("language"))?></div></th>
						                            
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
	            $confirm_save       = translate('Are you sure you want to edit this stock opname?',$this->session->userdata('language'));
	            ?>
	            <div class="form-actions fluid">	
					<div class="col-md-12 text-right">
				        <a class="btn default" href="javascript:history.go(-1)"><?=translate('Back', $this->session->userdata('language'))?></a>
						<a id="confirm_save" class="btn green-haze" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=translate('Commit', $this->session->userdata('language'))?></a>
				        <button type="submit" id="save" class="btn default hidden" >Save</button>
					</div>			
				</div>
			</div>			
		</div>		
	</div>

<div id="popover_item_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_item_search">
            <thead>
                <tr role="row" class="heading">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Satuan', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
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
<?=form_close();?>



