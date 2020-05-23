<?php

$btn_del           = '<div class="text-center"><a class="btn btn-sm red-intense del-this" title="Delete Dokumen Pelengkap"><i class="fa fa-times"></i></a></div>';
$btn_del_db        = '<div class="text-center"><a class="btn btn-sm red-intense del-item-db" title="Delete Dokumen Pelengkap"><i class="fa fa-times"></i></a></div>';

$attrs_pelengkap_id = array (
    'id'            => 'pelengkap_id{0}',
    'name'          => 'pelengkap[{0}][id]',
    'type'          => 'hidden',
    'class'         => 'form-control',
    'placeholder'   => 'Pelengkap ID',
    'style'         => 'width : 100px;'
);

$attrs_pelengkap_subjek	= array (
    'id'            => 'pelengkap_subjek{0}',
    'name'          => 'pelengkap[{0}][subjek]',
    'type'          => 'text',
    'class'         => 'form-control',
    'placeholder'   => 'Subjek',
    'style'         => 'width : 100px;'
);

$attrs_pelengkap_no_dokumen = array (
    'id'            => 'pelengkap_no_dokumen{0}',
    'name'          => 'pelengkap[{0}][no_dokumen]',
    'type'          => 'text',
    'class'         => 'form-control',
    'placeholder'   => 'No Dokumen',
    'style'         => 'width : 100px;'
);

$attrs_pelengkap_file = array (
    'id'            => 'pelengkap_file{0}',
    'name'          => 'pelengkap[{0}][file]',
    'type'          => 'file',
    'style'         => 'width : 85px;'
);

$attrs_pelengkap_is_delete = array (
    'id'            => 'pelengkap_is_delete{0}',
    'name'          => 'pelengkap[{0}][is_delete]',
    'type'          => 'hidden',
    'class'         => 'form-control',
    'placeholder'   => 'Is Delete',
    'style'         => 'width : 100px;'
);

$jenis_file_option = array(
	'' => 'Pilih..',
	'1' => 'Gambar',
	'2' => 'PDF'
);

// $records = $form_data_persetujuan->result_array();
// die_dump($records);

// item row column
if ($flag == "add") 
{
    $hidden_check = "";
	$item_cols = array(// style="width:156px;
    'pelengkap_subjek'     => form_input($attrs_pelengkap_subjek),
    'pelengkap_no_dokumen'     => form_input($attrs_pelengkap_no_dokumen),
	'pelengkap_jenis_file'     => form_dropdown('pelengkap[{0}][jenis_file]', $jenis_file_option, '', "id=\"pelengkap_jenis_file_{0}\" class=\"form-control\" style=\"width:80px;\" "),
    'pelengkap_file'             => form_input($attrs_pelengkap_file),
    'pelengkap_waktu_kadaluarsa'             => '<div class="input-group date" id="tanggal_kadaluarsa">
                                                    <input type="text" class="form-control" id="pelengkap_tanggal_kadaluarsa_{0}" name="pelengkap[{0}][tanggal_kadaluarsa]" readonly >
                                                    <span class="input-group-btn">
                                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                 </div>',
    'action'             => $btn_del,
	);
	// gabungkan $item_cols jadi string table row
	$pelengkap_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$dummy_rows = array();
}
elseif ($flag == "edit")
{
    $hidden_check = "";
    $item_cols = array(// style="width:156px;
    'pelengkap_subjek'                  => form_input($attrs_pelengkap_id).form_input($attrs_pelengkap_subjek).form_input($attrs_pelengkap_is_delete),
    'pelengkap_no_dokumen'              => form_input($attrs_pelengkap_no_dokumen),
    'pelengkap_jenis_file'              => form_dropdown('pelengkap[{0}][jenis_file]', $jenis_file_option, '', "id=\"pelengkap_jenis_file_{0}\" class=\"form-control\" style=\"width:80px;\" "),
    'pelengkap_file'                    => form_input($attrs_pelengkap_file),
    'pelengkap_waktu_kadaluarsa'        => '<div class="input-group date" id="tanggal_kadaluarsa">
                                                <input type="text" class="form-control" id="pelengkap_tanggal_kadaluarsa_{0}" name="pelengkap[{0}][tanggal_kadaluarsa]" readonly >
                                                <span class="input-group-btn">
                                                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>',
    'action'                            => $btn_del,
    );
    // gabungkan $item_cols jadi string table row
    $pelengkap_row_template =  '<tr id="item_row_{0}" class="row_add"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

    $dummy_rows = array();


    $get_pasien_dokumen = $this->pasien_dokumen_m->get_by(array('pasien_id' => $form_data['id'], 'tipe' => '1'));
    $records = object_to_array($get_pasien_dokumen);
    
    foreach ($records as $key=>$data) 
    {
        $attrs_pelengkap_id['value'] = $data['id'];
        $attrs_pelengkap_subjek['value'] = $data['subjek'];
        $attrs_pelengkap_no_dokumen['value'] = $data['no_dokumen'];
        $jenis_file = $data['jenis'];
        $tanggal_kadaluarsa = $data['tanggal_kadaluarsa'];

            
            $item_cols = array(// style="width:156px;
            'pelengkap_subjek'               => form_input($attrs_pelengkap_id).form_input($attrs_pelengkap_subjek).form_input($attrs_pelengkap_is_delete),
            'pelengkap_no_dokumen'           => form_input($attrs_pelengkap_no_dokumen),
            'pelengkap_jenis_file'           => form_dropdown('pelengkap[{0}][jenis_file]', $jenis_file_option, $jenis_file, "id=\"pelengkap_jenis_file_{0}\" class=\"form-control\" style=\"width:80px;\" "),
            'pelengkap_file'                 => form_input($attrs_pelengkap_file),
            'pelengkap_waktu_kadaluarsa'     => '<div class="input-group date" id="tanggal_kadaluarsa">
                                                    <input type="text" class="form-control" id="pelengkap_tanggal_kadaluarsa_{0}" name="pelengkap[{0}][tanggal_kadaluarsa]" readonly value="'.$tanggal_kadaluarsa.'" >
                                                    <span class="input-group-btn">
                                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>',
            'action'             => $btn_del_db,
            );
            // gabungkan $item_cols jadi string table row
            $pelengkap_row_edit_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
            
            $dummy_rows[] = str_replace('{0}', "{$key}", $pelengkap_row_edit_template );
        
    }
}
else
{
    $get_pasien_dokumen = $this->pasien_dokumen_m->get_by(array('pasien_id' => $form_data['id'], 'tipe' => '1'));
    $records = object_to_array($get_pasien_dokumen);
    //die_dump($records);
    $hidden_check = "hidden";
    foreach ($records as $key=>$data) 
    {
        $attrs_pelengkap_id['value'] = $data['id'];
        $attrs_pelengkap_subjek['value'] = $data['subjek'];
        $attrs_pelengkap_no_dokumen['value'] = $data['no_dokumen'];
        $jenis_file = $data['jenis'];
        $url = $data['url_file'];
        $tanggal_kadaluarsa = $data['tanggal_kadaluarsa'];

        if($data['jenis'] == '1')
        {
            $jenis = 'Gambar';
        }
        elseif ($data['jenis'] == '2') {
            $jenis = 'PDF';
        }
        else{
            $jenis = '';
        }    
            $item_cols = array(// style="width:156px;
            'pelengkap_subjek'               => '<div class="text-center"><label for="subjek" class="control-label text-center">'.$data['subjek'].'</label>',
            'pelengkap_no_dokumen'           => '<label for="no_dokumen" class="control-label">'.$data['no_dokumen'].'</label>',
            'pelengkap_jenis_file'           => '<div class="text-center"><label for="jenis" class="control-label text-center">'.$jenis.'</label>',
            'pelengkap_file'                 => '<div class="text-center"><label for="url" class="control-label">'.$url.'</label></div>',
            'pelengkap_waktu_kadaluarsa'     => '<div class="text-center"><label for="tanggal_kadaluarsa" class="control-label text-center">'.date('d M Y', strtotime($data['tanggal_kadaluarsa'])).'</label>',
            );
            // gabungkan $item_cols jadi string table row
            $pelengkap_row_edit_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
            
            $dummy_rows[] = str_replace('{0}', "{$key}", $pelengkap_row_edit_template );
        
    }
}

?>

<div class="portlet light">
	<div class="portlet-title" <?=$hidden_check?>>
		<div class="actions">
            <a data-toggle="modal" id="tambah_row_pelengkap" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<span id="tpl_pelengkap_row" class="hidden"><?=htmlentities($pelengkap_row_template)?></span>
		<table class="table table-striped table-bordered table-hover" id="table_pelengkap">
			<thead>

				<tr class="heading">
					<th class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("No.Dokumen", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Jenis File", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Pilih File", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?></th>
					<th class="text-center" <?=$hidden_check?>><?=translate("Aksi", $this->session->userdata("language"))?></th>
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

