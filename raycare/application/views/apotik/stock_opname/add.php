<?php
	$form_attr = array(
		"id"			=> "form_add_stock_opname", 
		"name"			=> "form_add_stock_opname", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);
	$hidden = array(
		"command"	=> "add"
	);
    $id = array(
        'id'    => 'id',
        'type'  => 'hidden',
        'value' => $pk_value
    );
	echo form_open(base_url()."apotik/stock_opname/save", $form_attr,$hidden, $id);

    // die_dump($id);

$form_config = array(
    'default_control_label_class' => 'col-md-4 control-label',
    'default_form_control_class'  => 'col-md-8',
    'default_form_class'          => 'form-horizontal col-md-12',
    );

$this->form_builder->init($form_config);

$btn_search = '<div class="text-center"><button type="button" title="'.translate("Cari Item", $this->session->userdata('language')).'" class="btn btn-sm btn-success search-item"><i class="fa fa-search"></i></button></div>';
$btn_del    = '<div class="text-center"><button type="button" class="btn btn-sm red-intense del-this" title="'.translate("Hapus Item", $this->session->userdata('language')).'"><i class="fa fa-times"></i></button></div>';

$attrs_item_id = array (
    'id'       => 'items_id_{0}',
    'name'     => 'items[{0}][item_id]',
    'class'    => 'form-control input-sm hidden ',
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
    'name'     => 'items[{0}][satuan_text]',
    'class'    => 'form-control input-sm',
    'readonly' => 'readonly',
    // 'value' => 'Selongsong Gas HND ASTREA',
);
$item_cols = array(
    'item_code'   => form_input($attrs_item_id).form_input($attrs_item_code).form_input($attrs_item_qty).form_input($attrs_item_satuan),
    'item_search' => $btn_search,
    'item_name'   => form_input($attrs_item_name),
    'item_satuan' => form_input($attrs_item_satuan_text),
    'action'      => $btn_del,
);
$item_row_template =  '<tr id="item_row_{0}" class="table-stock-opname"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

?>	
<div class="portlet light">
    <div class="portlet-body form">
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("stok opname", $this->session->userdata("language"))?></span>
			</div>
            <input type="hidden" id="pk" name="pk" value="<?=$pk_value?>">
			<div class="tools">
				<a href="javascript:;" class="collapse">
				</a>	
			</div>
		</div>
		<div class="portlet-body form">	
			<div class="form-wizard">
				<div class="form-body">
                    <div class="form-group"></div>
					<div class="form-group hidden">
                        <label class="control-label col-md-3">ID</label>
                        <div class="col-md-1">
                            <input class="form-control" id="warehouse_id" name="warehouse_id" value="<?=$pk_value?>"> 
                        </div>
                    </div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Stok Opname untuk", $this->session->userdata("language"))?> :</label>
						<div class="col-md-3">
                            <?php
                                $warehouse_option = array(
                                    '' => ''
                                );
                                $result = $this->warehouse_people_m->get_by(array('gudang_id' => $pk_value,'is_active'=>1));
                                foreach($result as $row)
                                {
                                    $warehouse_option[$row->id] = $row->nama;
                                }
                                echo form_dropdown("warehouse_people_id", $warehouse_option, "", "id=\"warehouse\" class=\"form-control select2me\" required data-placeholder=\"".translate('Choose People', $this->session->userdata('language'))."\" ");
                            ?>
                         
                        </div>
				        <!-- // <input type="hidden" name="user_id" id="user_id" readonly="readonly" value="<?=isset($user_idvalue)?$user_id_value:""?>"/>    -->
					</div>	
                    <div class="form-group">
                        <label class="control-label col-md-3">
                            <?=translate('Gudang', $this->session->userdata('language'))?> :
                        </label>
                        <div class="col-md-3">
                            <label class="control-label">
                                <?php           
                                    $form_data = $this->warehouse_m->get($pk_value);
                                    $data = object_to_array($form_data);
                                    echo $data['nama'];
                                ?>
                            </label>
                        </div>
                    </div>				
				</div>
			</div>			
		</div>		
	</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light" id="item_temporary">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("detail stok opname", $this->session->userdata("language"))?></span>
                </div>

                <div class="actions"> 
                	<a class="btn btn-primary choose_template" id="choose_template">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
                             <?=translate("Pilih dari template", $this->session->userdata("language"))?>
                        </span>
                    </a>
                </div>
            </div>

            <div class="portlet-body form">
            	<div class="form-body">
                    <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
					<div class="table-scrollable">
	                <table class="table table-striped table-bordered table-hover" id="table_add_stock_opname">
	                    <thead>
	                        <tr role="row" class="heading">
	                            <th scope="col" colspan="2"><div class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></div></th>
	                            <th scope="col" ><div class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></div></th>
                                 <th scope="col" ><div class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></div></th>
	                            <th scope="col" width="10%"><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>
	                            
	                        </tr>
	                    </thead>
	                    <tbody>

	                    </tbody>
	                </table>
	                </div>

	                <div class="form-group">
						<div class="col-md-6">
				            <input type="checkbox" class="form-control" name="save_template" id="save_template" value="1"/><label class="control-label" ><?=translate("Save As Template", $this->session->userdata("language"))?></label>
				        </div>
					</div>
					<div class="form-group" id="template_name" style="display:none">							
						<label class="control-label col-md-3"><?=translate("Template name :", $this->session->userdata("language"))?></label>
						<div class="col-md-4">
				           <input type="text" class="form-control" name="template_name" id="template_name" />
				        </div>
					</div>	
	            </div>
	            <?php 
	            	$confirm_save       = translate('Apakah anda yakin ingin menambah stock opname ini?',$this->session->userdata('language'));
		        ?>

		        <div class="form-actions fluid">	
				    <div class="col-md-12 text-left">
					    <a class="btn default" href="javascript:history.go(-1)"><?=translate('Back', $this->session->userdata('language'))?></a>
                        <a id="confirm_save" class="btn btn-primary" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=translate('Submit', $this->session->userdata('language'))?></a>
					    <button type="submit" id="save" class="btn default hidden" >Save</button>
					</div>			
				</div>	
            </div>
        </div>
    </div>
</div>

<!-- INVENTORY -->
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
<!--TEMPLATE -->      
<?=$this->load->view('apotik/stock_opname/search_template_jadi')?>
<?=form_close();?>