<?php

$btn_del           = '<div class="text-center hidden"><a class="btn btn-sm red-intense del-this" title="Delete Dokumen Pelengkap"><i class="fa fa-times"></i></a></div>';
$btn_del_db        = '<div class="text-center"><a class="btn btn-sm red-intense del-item-db" title="Delete Dokumen Pelengkap"><i class="fa fa-times"></i></a></div>';

$attrs_satuan_id    = array (
    'id'            => 'satuan_id_{0}',
    'name'          => 'satuan[{0}][id]',
    'type'          => 'hidden',
    'class'         => 'form-control satuan_id',
    'placeholder'   => 'Satuan Id'
);

$attrs_satuan_jumlah	= array (
    'id'            => 'satuan_jumlah_{0}',
    'name'          => 'satuan[{0}][jumlah]',
    'type'          => 'text',
    'class'         => 'form-control jumlah',
    'placeholder'   => 'Jumlah',
    'data-row'       => '{0}',
);

$attrs_satuan_satuan = array (
    'id'            => 'satuan_satuan_{0}',
    'name'          => 'satuan[{0}][satuan]',
    'type'          => 'text',
    'class'         => 'form-control satuan',
    'placeholder'   => 'Satuan',
    'data-row'       => '{0}',
);

$attrs_satuan_action_satuan = array (
    'id'            => 'satuan_action_satuan_{0}',
    'name'          => 'satuan[{0}][action_satuan]',
    'type'          => 'hidden',
    'class'         => 'form-control action_satuan',
    'placeholder'   => 'Action',
);

$attrs_satuan_action_jumlah = array (
    'id'            => 'satuan_action_jumlah_{0}',
    'name'          => 'satuan[{0}][action_jumlah]',
    'type'          => 'hidden',
    'class'         => 'form-control action_jumlah',
    'placeholder'   => 'Action',

);

$attrs_satuan_row = array (
    'id'            => 'satuan_row_{0}',
    'name'          => 'satuan[{0}][row]',
    'type'          => 'hidden',
    'class'         => 'form-control current_row',
    'placeholder'   => 'Action',
    'value'         => '{0}'
);

$attrs_satuan_jumlah_2	= array (
    'id'            => 'satuan_jumlah_2_{0}',
    'name'          => 'satuan2[{0}][jumlah]',
    'type'          => 'text',
    'class'         => 'form-control jumlah2',
    'placeholder'   => 'Jumlah'
);

$attrs_satuan_satuan_2 = array (
    'id'            => 'satuan_satuan_2_{0}',
    'name'          => 'satuan2[{0}][satuan]',
    'type'          => 'text',
    'class'         => 'form-control',
    'placeholder'   => 'Satuan'
);

// $records = $form_data_persetujuan->result_array();
// die_dump($records);

// item row column
if ($flag == "add") 
{   
    $parent_id = '';
    $hidden_check = "";
    $primary = "";
	$item_cols = array(// style="width:156px;
	'satuan_jumlah'   => form_input($attrs_satuan_jumlah).form_input($attrs_satuan_action_jumlah),
	'satuan_satuan'   => form_input($attrs_satuan_satuan).form_input($attrs_satuan_id).form_input($attrs_satuan_action_satuan),
	'operator'        => '<div class="text-center"><label id="operator"></label></div>',
	'satuan_jumlah_2' => '<div class="text-center"><label id="jumlah_{0}" class="jumlah"></label></div>',
	'satuan_satuan_2' => '<div class="text-center"><label id="satuan_{0}" class="satuan"></label></div>',
    // 'is_primary'      => '<div class="text-center"><input type="radio" name="satuan2[{0}][is_primary]"></div>'
	'is_primary'      => '<div class="text-center"><input type="radio" name="is_primary" id="is_primary" data-id="{0}" class="is_primary"></div>'
	);
	// gabungkan $item_cols jadi string table row
	$satuan_row_template =  '<tr id="item_row_{0}" class="{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$dummy_rows = array();
}
elseif ($flag == "edit")
{
    $item_cols = array(// style="width:156px;
    'satuan_jumlah'   => form_input($attrs_satuan_jumlah).form_input($attrs_satuan_action_jumlah),
    'satuan_satuan'   => form_input($attrs_satuan_satuan).form_input($attrs_satuan_id).form_input($attrs_satuan_action_satuan),
    'operator'        => '<div class="text-center"><label id="operator"></label></div>',
    'satuan_jumlah_2' => '<div class="text-center"><label id="jumlah_{0}" class="jumlah"></label></div>',
    'satuan_satuan_2' => '<div class="text-center"><label id="satuan_{0}" class="satuan"></label></div>',
    // 'is_primary'      => '<div class="text-center"><input type="radio" name="satuan2[{0}][is_primary]"></div>'
    'is_primary'      => '<div class="text-center"><input type="radio" name="is_primary" id="is_primary" data-id="{0}" class="is_primary"></div>'
    );
    // gabungkan $item_cols jadi string table row
    $satuan_row_template =  '<tr id="item_row_{0}" class="add_{2}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

    $dummy_rows = array();
    
    $get_item_satuan = $this->item_satuan_m->get_by(array('item_id' => $form_data['id']));
    $records = object_to_array($get_item_satuan);

    $get_primary = $this->item_satuan_m->get_by(array('is_primary' => '1', 'item_id' => $form_data['id']));
    $records_primary = object_to_array($get_primary);

    $primary = $records_primary[0]['id'];
    // die_dump($primary);
    $parent_id = '';
    foreach ($records as $key=>$data) 
    {
        $attrs_satuan_jumlah['value'] = $data['jumlah'];
        $attrs_satuan_satuan['value'] = $data['nama'];
        $attrs_satuan_id['value'] = $data['id'];
        $attrs_satuan_action_jumlah['value'] = 'added_jumlah';
        $attrs_satuan_action_satuan['value'] = 'added_satuan';
            $operator = '';
            $parent = '';

            if ($data['parent_id'] == null) {
                $operator = '';
                $parent = $data['nama'];
                $parent_id = $data['id'];
                // $primary = $data['is_primary'];
            }else{
                $operator = '=';
                $parent_id = $data['id'];

                $get_parent = $this->item_satuan_m->get_by(array('id' => $data['parent_id'] ));
                $data_parent = object_to_array($get_parent);
                $parent = $data_parent[0]['nama'];
                // $primary = $data['is_primary'];
            }

            $check='';
            if ($data['is_primary'] == '1') {
                $check="checked";
            }else{
                $check="";
            }
            $item_cols = array(// style="width:156px;
                'satuan_jumlah'   => form_input($attrs_satuan_jumlah).form_input($attrs_satuan_action_jumlah),
                'satuan_satuan'   => form_input($attrs_satuan_satuan).form_input($attrs_satuan_id).form_input($attrs_satuan_action_satuan),
                'operator'        => '<div class="text-center"><label id="operator">'.$operator.'</label></div>',
                'satuan_jumlah_2' => '<div class="text-center"><label id="jumlah_{0}" class="jumlah">1</label></div>',
                'satuan_satuan_2' => '<div class="text-center"><label id="satuan_{0}" class="satuan">'.$parent.'</label></div>',
                // 'is_primary'      => '<div class="text-center"><input type="radio" name="satuan2[{0}][is_primary]"></div>'
                'is_primary'      => '<div class="text-center"><input type="radio" '.$check.' name="is_primary" id="is_primary" data-id="'.$data['id'].'" class="is_primary form-control" style="left: 20px;"></div>'
            );
            // gabungkan $item_cols jadi string table row
            $satuan_row_edt_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
            
            $dummy_rows[] = str_replace('{0}', "{$key}", $satuan_row_edt_template );
        
    }
}
else
{
    // $get_pasien_dokumen = $this->pasien_dokumen_m->get_by(array('pasien_id' => $form_data['id'], 'tipe' => '1'));
    // $records = object_to_array($get_pasien_dokumen);
    // //die_dump($records);
    // $hidden_check = "hidden";
    // foreach ($records as $key=>$data) 
    // {
    //     $attrs_pelengkap_id['value'] = $data['id'];
    //     $attrs_pelengkap_subjek['value'] = $data['subjek'];
    //     $attrs_pelengkap_no_dokumen['value'] = $data['no_dokumen'];
    //     $jenis_file = $data['jenis'];
    //     $url = $data['url_file'];
    //     $tanggal_kadaluarsa = $data['tanggal_kadaluarsa'];

    //     if($data['jenis'] == '1')
    //     {
    //         $jenis = 'Gambar';
    //     }
    //     elseif ($data['jenis'] == '2') {
    //         $jenis = 'PDF';
    //     }
    //     else{
    //         $jenis = '';
    //     }    
    //         $item_cols = array(// style="width:156px;
    //         'pelengkap_subjek'               => '<div class="text-center"><label for="subjek" class="control-label text-center">'.$data['subjek'].'</label>',
    //         'pelengkap_no_dokumen'           => '<label for="no_dokumen" class="control-label">'.$data['no_dokumen'].'</label>',
    //         'pelengkap_jenis_file'           => '<div class="text-center"><label for="jenis" class="control-label text-center">'.$jenis.'</label>',
    //         'pelengkap_file'                 => '<div class="text-center"><label for="url" class="control-label">'.$url.'</label></div>',
    //         'pelengkap_waktu_kadaluarsa'     => '<div class="text-center"><label for="tanggal_kadaluarsa" class="control-label text-center">'.date('d M Y', strtotime($data['tanggal_kadaluarsa'])).'</label>',
    //         );
    //         // gabungkan $item_cols jadi string table row
    //         $pelengkap_row_edit_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
            
    //         $dummy_rows[] = str_replace('{0}', "{$key}", $pelengkap_row_edit_template );
        
    // }
}

?>

<div class="portlet light">
	<div class="portlet-title">
        <div class="caption">
            <?=translate("Setup Satuan", $this->session->userdata("language"))?>
        </div>
		<div class="actions">
            <a id="tambah_row_satuan" class="btn btn-icon-only btn-circle btn-primary">
                <i class="fa fa-plus"></i>
            </a>

            <a id="delete_row_satuan" class="btn btn-icon-only btn-circle red-intense">
                <i class="fa fa-times"></i>
            </a>

            <input type="hidden" id="tambah_row" name="tambah_row" value="save_item">
            <input type="hidden" id="item_id" name="item_id" placeholder="item_id" value="<?=$pk_value?>">
            <input type="hidden" id="parent_id" name="parent_id" placeholder="parent_id" value="<?=$parent_id?>">
            <input type="hidden" id="satuan_primary" name="satuan_primary" placeholder="satuan_primary" value="<?=$primary?>">
        </div>
	</div><!-- akhir dari portlet-title -->
	<div class="portlet-body">
		<span id="tpl_satuan_row" class="hidden"><?=htmlentities($satuan_row_template)?></span>
        <div class="table-scrollable">
    		<table class="table table-striped table-bordered table-hover" id="table_satuan">
    			<thead>
    				<tr>
    					<th class="text-center" width="20%"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
    					<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></th>
    					<th class="text-center" width="1%"><?=translate("Operator", $this->session->userdata("language"))?></th>
                        <th class="text-center" width="20%"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                        <th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                        <th class="text-center" width="1%"><?=translate("Primary", $this->session->userdata("language"))?></th>
    				</tr>
    			</thead>

    			<tbody>
    				<?php foreach ($dummy_rows as $row):?>
                        <?=$row?>
                    <?php endforeach;?>
    			</tbody>
    		</table>  
        </div><!-- akhir dari table-scrollable -->
	</div><!-- akhir dari portlet-body -->
</div><!-- akhir dari portlet -->

