<?php

$btn_del           = '<div class="text-center"><a class="btn btn-sm red-intense del-this" title="Delete Dokumen Rekam Medis"><i class="fa fa-times"></i></a></div>';
$btn_del_db        = '<div class="text-center"><a class="btn btn-sm red-intense del-item-db" title="Delete Dokumen Rekam Medis"><i class="fa fa-times"></i></a></div>';

$attrs_rekam_medis_id = array (
    'id'            => 'rekam_medis_id{0}',
    'name'          => 'rekam_medis[{0}][id]',
    'type'          => 'hidden',
    'class'         => 'form-control',
    'placeholder'   => 'Rekam Medis ID',
    'style'         => 'width : 100px;'
);

$attrs_rekam_medis_subjek	= array (
    'id'            => 'rekam_medis_subjek{0}',
    'name'          => 'rekam_medis[{0}][subjek]',
    'type'          => 'text',
    'class'         => 'form-control',
    'placeholder'   => 'Subjek',
    'style'         => 'width : 100px;'
);

$attrs_rekam_medis_no_dokumen = array (
    'id'            => 'rekam_medis_no_dokumen{0}',
    'name'          => 'rekam_medis[{0}][no_dokumen]',
    'type'          => 'text',
    'class'         => 'form-control',
    'placeholder'   => 'No Dokumen',
    'style'         => 'width : 100px;'
);

$attrs_rekam_medis_file = array (
    'id'            => 'rekam_medis_file{0}',
    'name'          => 'rekam_medis[{0}][file]',
    'type'          => 'file',
    'style'         => 'width : 85px;'
);

$attrs_rekam_medis_is_delete = array (
    'id'            => 'rekam_medis_is_delete{0}',
    'name'          => 'rekam_medis[{0}][is_delete]',
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
    'rekam_medis_subjek'     => form_input($attrs_rekam_medis_subjek),
    'rekam_medis_no_dokumen'     => form_input($attrs_rekam_medis_no_dokumen),
	'rekam_medis_jenis_file'     => form_dropdown('rekam_medis[{0}][jenis_file]', $jenis_file_option, '', "id=\"rekam_medis_jenis_file_{0}\" class=\"form-control\" style=\"width:80px;\" "),
    'rekam_medis_file'             => form_input($attrs_rekam_medis_file),
    'rekam_medis_waktu_kadaluarsa'             => '<div class="input-group date" id="tanggal_kadaluarsa">
                                                    <input type="text" class="form-control" id="rekam_medis_tanggal_kadaluarsa_{0}" name="rekam_medis[{0}][tanggal_kadaluarsa]" readonly >
                                                    <span class="input-group-btn">
                                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                 </div>',
    'action'             => $btn_del,
	);
	// gabungkan $item_cols jadi string table row
	$rekam_medis_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

	$dummy_rows_rekam_medis = array();
}elseif ($flag == "edit")
{
    $hidden_check = "";
    $item_cols = array(// style="width:156px;
    'rekam_medis_subjek'     => form_input($attrs_rekam_medis_id).form_input($attrs_rekam_medis_subjek).form_input($attrs_rekam_medis_is_delete),
    'rekam_medis_no_dokumen'     => form_input($attrs_rekam_medis_no_dokumen),
    'rekam_medis_jenis_file'     => form_dropdown('rekam_medis[{0}][jenis_file]', $jenis_file_option, '', "id=\"rekam_medis_jenis_file_{0}\" class=\"form-control\" style=\"width:80px;\" "),
    'rekam_medis_file'             => form_input($attrs_rekam_medis_file),
    'rekam_medis_waktu_kadaluarsa'             => '<div class="input-group date" id="tanggal_kadaluarsa">
                                                    <input type="text" class="form-control" id="rekam_medis_tanggal_kadaluarsa_{0}" name="rekam_medis[{0}][tanggal_kadaluarsa]" readonly >
                                                    <span class="input-group-btn">
                                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                 </div>',
    'action'             => $btn_del,
    );
    // gabungkan $item_cols jadi string table row
    $rekam_medis_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

    $dummy_rows_rekam_medis = array();

    $get_pasien_dokumen = $this->pasien_dokumen_m->get_by(array('pasien_id' => $form_data['id'], 'tipe' => '2'));
    $records = object_to_array($get_pasien_dokumen);
    
    foreach ($records as $key=>$data) 
    {
        $attrs_rekam_medis_id['value'] = $data['id'];
        $attrs_rekam_medis_subjek['value'] = $data['subjek'];
        $attrs_rekam_medis_no_dokumen['value'] = $data['no_dokumen'];
        $jenis_file = $data['jenis'];
        $tanggal_kadaluarsa = $data['tanggal_kadaluarsa'];

            
            $item_cols = array(// style="width:156px;
            'rekam_medis_subjek'               => form_input($attrs_rekam_medis_id).form_input($attrs_rekam_medis_subjek).form_input($attrs_rekam_medis_is_delete),
            'rekam_medis_no_dokumen'           => form_input($attrs_rekam_medis_no_dokumen),
            'rekam_medis_jenis_file'           => form_dropdown('rekam_medis[{0}][jenis_file]', $jenis_file_option, $jenis_file, "id=\"rekam_medis_jenis_file_{0}\" class=\"form-control\" style=\"width:80px;\" "),
            'rekam_medis_file'                 => form_input($attrs_rekam_medis_file),
            'rekam_medis_waktu_kadaluarsa'     => '<div class="input-group date" id="tanggal_kadaluarsa">
                                                    <input type="text" class="form-control" id="rekam_medis_tanggal_kadaluarsa_{0}" name="rekam_medis[{0}][tanggal_kadaluarsa]" readonly value="'.$tanggal_kadaluarsa.'" >
                                                    <span class="input-group-btn">
                                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>',
            'action'             => $btn_del_db,
            );
            // gabungkan $item_cols jadi string table row
            $rekam_medis_row_edit_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
            
            $dummy_rows_rekam_medis[] = str_replace('{0}', "{$key}", $rekam_medis_row_edit_template );
        
    }
}else{
    $hidden_check = "hidden";
    $get_pasien_dokumen_rekam_medis = $this->pasien_dokumen_m->get_by(array('pasien_id' => $form_data['id'], 'tipe' => '2'));
    $records_rekam_medis = object_to_array($get_pasien_dokumen_rekam_medis);
    //die_dump($records2);
    foreach ($records_rekam_medis as $key=>$data) 
    {
        if($data['jenis'] == '1')
        {
            $jenis = 'Gambar';
        }
        elseif ($data['jenis'] == '2') 
        {
            $jenis = 'PDF';
        }
        else
        {
            $jenis = '';
        }

        $item_cols_rekam_medis = array(// style="width:156px;
        'rekam_medis_subjek'               => '<div class="text-center"><label for="subjek" class="control-label">'.$data['subjek'].'</label></div>',
        'rekam_medis_no_dokumen'           => '<label for="no_dokumen" class="control-label">'.$data['no_dokumen'].'</label>',
        'rekam_medis_jenis_file'           => '<div class="text-center"><label for="jenis" class="control-label">'.$jenis.'</label></div>',
        'rekam_medis_file'                 => '<div class="text-center"><label for="url" class="control-label">'.$url.'</label></div>',
        'rekam_medis_waktu_kadaluarsa'     => '<div class="text-center"><label for="tanggal_kadaluarsa" class="control-label">'.date('d M Y', strtotime($data['tanggal_kadaluarsa'])).'</label></div>',
        );
        // gabungkan $item_cols jadi string table row
        $rekam_medis_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols_rekam_medis) . '</td></tr>';
        
        $dummy_rows_rekam_medis[] = str_replace('{0}', "{$key}", $rekam_medis_row_template );
        
    }
}

?>

<div class="portlet light">
	<div class="portlet-title" <?=$hidden_check?>>
		<div class="actions">
            <a data-toggle="modal" id="tambah_row_rekam_medis" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<span id="tpl_rekam_medis_row" class="hidden"><?=htmlentities($rekam_medis_row_template)?></span>
		<table class="table table-striped table-bordered table-hover" id="table_rekam_medis">
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
				<?php foreach ($dummy_rows_rekam_medis as $row):?>
                    <?=$row?>
                <?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>

