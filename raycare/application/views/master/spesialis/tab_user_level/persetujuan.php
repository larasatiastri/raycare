<?php

$btn_del           = '<div class="text-center"><a class="btn btn-sm red-intense del-this" title="Delete Persetujuan Pembelian"><i class="fa fa-times"></i></a></div>';
$btn_del_db        = '<div class="text-center"><a class="btn btn-sm red-intense del-item-db" title="Delete Persetujuan Pembelian"><i class="fa fa-times"></i></a></div>';

$attrs_user_level_id	= array (
    'id'          => 'user_level_id{0}',
    'name'        => 'user_level[{0}][id]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);
$attrs_user_level_name = array (
    'id'          => 'user_level_name{0}',
    'name'        => 'user_level[{0}][name]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);
$attrs_user_level_persetujuan_id	= array (
    'id'          => 'user_level_persetujuan_id{0}',
    'name'        => 'user_level[{0}][persetujuan_id]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);
$attrs_user_level_action	= array (
    'id'          => 'user_level_action{0}',
    'name'        => 'user_level[{0}][action]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
    'value'		  => 'add',
);
$attrs_user_level_delete    = array (
    'id'          => 'user_level_delete{0}',
    'name'        => 'user_level[{0}][delete]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);

$order_option = array(
	'1' => '1',
	'2' => '2',
	'3' => '3'
);

// $records = $form_data_persetujuan->result_array();
// die_dump($records);

// item row column
if ($flag == "add") {
	$item_cols = array(// style="width:156px;
	'user_level_name'     => form_input($attrs_user_level_name).form_input($attrs_user_level_id).'<label class="control-label" id="user_level_lblname{0}" name="user_level[{0}][lblname]"></label>',
	'user_level_order'     => form_dropdown('user_level[{0}][order]', $order_option, '', "id=\"user_level_order_{0}\" class=\"form-control\""),
	'action'             => $btn_del,
	);
	// gabungkan $item_cols jadi string table row
	$item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$dummy_rows = array();
}else
{
	$item_cols = array(// style="width:156px;
	'user_level_name'     => form_input($attrs_user_level_name).'<label class="control-label" id="user_level_lblname{0}" name="user_level[{0}][lblname]"></label>'.form_input($attrs_user_level_id).form_input($attrs_user_level_persetujuan_id).form_input($attrs_user_level_action).form_input($attrs_user_level_delete),
	'user_level_order'     => form_dropdown('user_level[{0}][order]', $order_option, '', "id=\"user_level_order_{0}\" class=\"form-control\""),
	'action'             => $btn_del,
	);
	// gabungkan $item_cols jadi string table row
	$item_row_template =  '<tr id="item_row_{0}" class="add_row"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$dummy_rows = array();

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
		    'user_level_name'     => form_input($attrs_user_level_name).'<label class="control-label" id="user_level_lblname{0}" name="user_level[{0}][lblname]">'.$name->nama.'</label>'.form_input($attrs_user_level_id).form_input($attrs_user_level_persetujuan_id).form_input($attrs_user_level_action).form_input($attrs_user_level_delete),
		    'user_level_order'     => form_dropdown('user_level[{0}][order]', $order_option, $order_user, "id=\"user_level_order_{0}\" class=\"form-control\""),
		    'action'             => $btn_del_db,
			);
			// gabungkan $item_cols jadi string table row
			$item_dummy =  '<tr id="item_row_edit_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
		    $dummy_rows[] = str_replace('{0}', "{$key}", $item_dummy );

		}
	}
}

?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-green-sharp bold uppercase"><?=translate("Persetujuan Pembelian", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a data-toggle="modal" href="#popup_modal" class="btn green-haze">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
		<table class="table table-striped table-bordered table-hover" id="table_persetujuan">
			<thead>
				<tr class="heading">
					<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Order", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></th>
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

<div id="popup_modal" class="modal fade" role="dialog" aria-hidden="true" style="display: none;">
    <form action="#" class="form-horizontal">
        <div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title font-green-sharp bold uppercase"><?=translate("Pilih User Level", $this->session->userdata("language"))?></h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="table_user_level">
                        <thead>
                            <tr role="row" class="heading">
                                <th scope="col" ><div class="text-center"><?=translate("ID", $this->session->userdata("language"))?></div></th>
                                <th scope="col" ><div class="text-center"><?=translate("User Level", $this->session->userdata("language"))?></div></th>
                                <th scope="col" ><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" id="closeModal" class="btn default" data-dismiss="modal">Close</button> -->
                    <button type="button" class="btn green-haze hidden" id="btnOK">OK</button>
                    <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
                </div>
            </div>
        </div>
    </form>
</div>