<?php

$btn_del           = '<div class="text-center"><a class="btn btn-sm red-intense del-this" title="Delete Persetujuan Pembelian"><i class="fa fa-times"></i></a></div>';
$btn_del_db        = '<div class="text-center"><a class="btn btn-sm red-intense del-item-db" title="Delete Persetujuan Pembelian"><i class="fa fa-times"></i></a></div>';

$attrs_user_level_id	= array (
    'id'          => 'user_level_id_item{0}',
    'name'        => 'user_level_persetujuan_item[{0}][id]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);
$attrs_user_level_name = array (
    'id'          => 'user_level_name_item{0}',
    'name'        => 'user_level_persetujuan_item[{0}][name]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);
$attrs_user_level_persetujuan_id	= array (
    'id'          => 'user_level_persetujuan_item_persetujuan_id{0}',
    'name'        => 'user_level_persetujuan_item[{0}][persetujuan_id]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);
$attrs_user_level_action	= array (
    'id'          => 'user_level_persetujuan_item_action{0}',
    'name'        => 'user_level_persetujuan_item[{0}][action]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
    'value'		  => 'add',
);
$attrs_user_level_delete    = array (
    'id'          => 'user_level_persetujuan_item_delete{0}',
    'name'        => 'user_level_persetujuan_item[{0}][delete]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);

$order_option = array(
	'1' => '1',
	'2' => '2',
	'3' => '3'
);

// die_dump($records);

// item row column
if ($flag == "add") {
    $item_cols = array(// style="width:156px;
        'user_level_name'  => form_input($attrs_user_level_name).form_input($attrs_user_level_id).'<label class="control-label" id="user_level_lblname_item{0}" name="user_level[{0}][lblname]"></label>',
        'user_level_order' => form_dropdown('user_level_persetujuan_item[{0}][order]', $order_option, '', "id=\"user_level_persetujuan_item_order_{0}\" class=\"form-control\""),
        'action'           => $btn_del,
    );
    // gabungkan $item_cols jadi string table row
    $item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

    $dummy_rows = array();
}else
{
    $records = $form_data_persetujuan_item->result_array();
    $item_cols = array(// style="width:156px;
	'user_level_name'     => form_input($attrs_user_level_name).'<label class="control-label" id="user_level_lblname_item{0}" name="user_level[{0}][lblname]"></label>'.form_input($attrs_user_level_id).form_input($attrs_user_level_persetujuan_id).form_input($attrs_user_level_action).form_input($attrs_user_level_delete),
	'user_level_order'     => form_dropdown('user_level_persetujuan_item[{0}][order]', $order_option, '', "id=\"user_level_persetujuan_item_order_{0}\" class=\"form-control\""),
	'action'             => $btn_del,
	);
	// gabungkan $item_cols jadi string table row
	$item_row_template =  '<tr id="item_row_{0}" class="add_row"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$dummy_rows = array();

    if($records)
    {
    	foreach ($records as $key=>$data) 
    	{
    		$attrs_user_level_id['value'] = $data['user_level_menyetujui_id'];
    		$attrs_user_level_persetujuan_id['value'] = $data['id'];
    		$attrs_user_level_action['value'] = 'edit';
    		$order_user = $data['level_order'];

    		$user_level_name = $this->user_level_m->get_by(array('id' => $data['user_level_menyetujui_id']));

    		foreach ($user_level_name as $name)
    		{
    	    	
    	    	$attrs_user_level_name['value'] = $name->nama;

    	    	$item_cols = array(// style="width:156px;
    		    'user_level_name'     => form_input($attrs_user_level_name).'<label class="control-label" id="user_level_lblname_item{0}" name="user_level[{0}][lblname]">'.$name->nama.'</label>'.form_input($attrs_user_level_id).form_input($attrs_user_level_persetujuan_id).form_input($attrs_user_level_action).form_input($attrs_user_level_delete),
    		    'user_level_order'     => form_dropdown('user_level_persetujuan_item[{0}][order]', $order_option, $order_user, "id=\"user_level_persetujuan_item_order_{0}\" class=\"form-control\""),
    		    'action'             => $btn_del_db,
    			);
    			// gabungkan $item_cols jadi string table row
    			$item_dummy =  '<tr id="item_row_edit_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
    		    $dummy_rows[] = str_replace('{0}', "{$key}", $item_dummy );

    		}
    	}        
    }
    else
    {
        $item_cols = array(// style="width:156px;
            'user_level_name'  => form_input($attrs_user_level_name).form_input($attrs_user_level_id).'<label class="control-label" id="user_level_lblname_item{0}" name="user_level[{0}][lblname]"></label>',
            'user_level_order' => form_dropdown('user_level_persetujuan_item[{0}][order]', $order_option, '', "id=\"user_level_persetujuan_item_order_{0}\" class=\"form-control\""),
            'action'           => $btn_del,
        );
        // gabungkan $item_cols jadi string table row
        $item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

        $dummy_rows = array();
    }
}

?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<?=translate("Persetujuan Item", $this->session->userdata("language"))?>
		</div>
		<div class="actions">
            <a data-toggle="modal" data-target="#modal_user_level_item" title="<?=translate('Tambah', $this->session->userdata('language'))?>" href="<?=base_url()?>master/user_level/search_user_level_item" class="btn btn-circle btn-icon-only btn-default">
                <i class="fa fa-plus"></i>
            </a>
            <a class="btn btn-circle btn-icon-only btn-default hidden" id="addRowItem">
                <i class="fa fa-plus"></i>
            </a>
        </div>
	</div>
	<div class="portlet-body">
        <input type="hidden" id="numRowItem">
		<span id="tpl_item_row_item" class="hidden"><?=htmlentities($item_row_template)?></span>
		<table class="table table-striped table-bordered table-hover" id="table_persetujuan_item">
			<thead>
				<tr>
					<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
					<th class="text-center" width="10%"><?=translate("Order", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
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

<div class="modal fade" id="modal_user_level_item" role="basic" aria-hidden="true">
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