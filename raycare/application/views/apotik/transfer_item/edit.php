<?php

    //////////////////////////////////////////////////////////////////////////////////////
   
    $get_gudang = $this->gudang_m->get_by(array('is_active' => 1));
        // die_dump(object_to_array($get_gudang));

    $gudang = object_to_array($get_gudang);

    $gudang_option = array(
        '' => translate('Pilih Gudang', $this->session->userdata('language'))
    );
    foreach ($gudang as $data) {
        $gudang_option[$data['id']] = $data['nama'];
    }

    $this->item_satuan_m->set_columns(array('id','nama'));
    $categories = $this->item_satuan_m->get();
    // die_dump($categories);
    $categories_satuan = array(
    
    '' => translate('Pilih Satuan', $this->session->userdata('language')) . '..',
    );

    //////////////////////////////////////////////////////////////////////////////////////

    $form_attr = array(
        "id"            => "form_edit_kirim_item", 
        "name"          => "form_edit_kirim_item", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );

    $hidden = array(
        "command"   => "update"
    );


    echo form_open(base_url()."gudang/transfer_item/kirim_item", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');

    // die_dump($data_order);


?>  
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Permintaan", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions">
            <?php

                $back_text      = translate('Kembali', $this->session->userdata('language'));
                $confirm_save   = translate('Apa Kamu Yakin Akan Ubah Transfer Item Ini ?',$this->session->userdata('language'));
                $submit_text    = translate('Submit', $this->session->userdata('language'));
                $reset_text     = translate('Reset', $this->session->userdata('language'));
            ?>
                
            <a class="btn btn-circle default" href="javascript:history.go(-1)">
                <i class="fa fa-chevron-left"></i>
                <?=$back_text?>
            </a>
            <button type="submit" id="save" class="btn btn-primary hidden" >
                <?=$submit_text?>
            </button> 
            <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal">
                <i class="fa fa-check"></i>
                <?=$submit_text?>
            </a> 

        </div>
    </div>
    <div class="portlet-body form">
        <div class="portlet-body form"> 
            <div class="form-wizard">
                <div class="row">
                <div class="form-body">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <?=$form_alert_danger?>
                    </div>
                    <div class="alert alert-success display-hide">
                        <button class="close" data-close="alert"></button>
                        <?=$form_alert_success?>
                    </div>

                        <div class="form-group hidden">
                            <label class="control-label col-md-4"><?=translate("pk value", $this->session->userdata("language"))?> :</label>
                            <div class="col-xs-3">
                                <?php
                                    $pk_value = array(
                                        "id"            => "pk_value",
                                        "name"          => "pk_value",
                                        "value"         => $pk_value,
                                        "class"         => "form-control",
                                    );
                                    echo form_input($pk_value);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4"><?=translate("Transfer Dari :", $this->session->userdata("language"))?></label>     
                            <div class="col-xs-2">
                                <?php
                                    echo form_dropdown('gudang_dari', $gudang_option, $form_data['dari_gudang_id'], "id=\"gudang_dari\" class=\"form-control\""); 
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4"><?=translate("Transfer Ke :", $this->session->userdata("language"))?></label>     
                            <div class="col-xs-2">
                                <?php
                                    echo form_dropdown('gudang_ke', $gudang_option, $form_data['ke_gudang_id'], "id=\"gudang_ke\" class=\"form-control\""); 
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4"><?=translate("Tanggal Transfer", $this->session->userdata("language"))?> :</label>
                            <div class="col-xs-2">
                                <?php
                                    $tanggal = array(
                                        "id"            => "tanggal_transfer",
                                        "name"          => "tanggal_transfer",
                                        "autofocus"         => true,
                                        "value"         => date('d-M-Y', strtotime($form_data['tanggal'])),
                                        "class"         => "form-control date-picker",
                                        "placeholder"   => translate("Tanggal Transfer", $this->session->userdata("language"))
                                    );
                                    echo form_input($tanggal);
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4"><?=translate("No. Surat Jalan", $this->session->userdata("language"))?> :</label>
                            <div class="col-xs-3">
                                <?php
                                    $no_surat_jalan = array(
                                        "id"            => "no_surat_jalan",
                                        "name"          => "no_surat_jalan",
                                        "autofocus"         => true,
                                        "value"         => $form_data['no_surat_jalan'],
                                        "class"         => "form-control",
                                        "placeholder"   => translate("No. Surat Jalan", $this->session->userdata("language"))
                                    );
                                    echo form_input($no_surat_jalan);
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>     
                            <div class="col-xs-4">
                                <textarea id="nomer_{0}" name="keterangan" class="form-control" rows="3" placeholder="Keterangan"><?=$form_data['keterangan']?></textarea>
                                <!-- <label class="control-label"><?=$form_data['keterangan']?></label> -->
                            </div>
                        </div>
                 
                </div>
                </div>
                </div>
            </div>

                
                    <?php
                    //  $btn_search   = '<div class="text-center"><button title="Search Account" class="btn btn-xs btn-success search-account"><i class="fa fa-search"></i></button></div>';
                        $btn_search         = '<div class="text-center"><button type="button" title="" class="btn btn-xs btn-primary search-account"><i class="fa fa-search"></i></button></div>';
                        $btn_plus           = '<div class="text-center"><button title="Add Row" class="btn btn-xs btn-success add_row"><i class="fa fa-plus"></i></button></div>';
                        $btn_del            = '<div class="text-center"><button class="btn btn-xs red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
                        $btn_del_plus       = '<div class="text-center"><button class="btn btn-xs red del-this-plus" title="Delete"><i class="fa fa-times"></i></button></div>';
                        $btn_stock         = '<div class="text-center"><button type="button" title="Semua Stock" class="btn btn-xs btn-primary stock"><i class="fa fa-check"></i> '.translate("Semua", $this->session->userdata("language")).'</button></div>';
                        $btn_permintaan         = '<div class="text-center"><button type="button" title="Semua Permintaan" class="btn btn-xs btn-primary permintaan"><i class="fa fa-check"></i> '.translate("Semua Permintaan", $this->session->userdata("language")).'</button></div>';
                        $btn_del_db        = '<div class="text-center"><a class="btn btn-sm red-intense del-item-db" title="Delete Item"><i class="fa fa-times"></i></a></div>';
// '.$row['id'].'
// <a title="'.translate('Unggah Gambar', $this->session->userdata('language')).'" href="'.base_url().'pembelian/permintaan_po/unggah_gambar/" data-toggle="modal" data-target="#popup_modal" class="btn btn-xs green-haze"><i class="fa fa-image"></i></a>         
                        $attrs_transfer_item_id = array(
                            'id'          => 'items_transfer_item_id_{0}',
                            'name'        => 'items[{0}][transfer_item_id]',
                            'class'       => 'form-control input-xs hidden',
                        );

                        $attrs_item_id = array(
                            'id'          => 'items_item_id_{0}',
                            'name'        => 'items[{0}][item_id]',
                            'class'       => 'form-control input-xs hidden',
                        );
                        
                        $attrs_item_kode = array(
                            'id'          => 'items_item_kode_{0}',
                            'name'        => 'items[{0}][item_kode]',
                            'class'       => 'form-control text-center input-xs hidden',
                            // 'readonly'    => 'readonly',
                            // 'style'       => 'width:180px;',
                        );

                        $attrs_item_nama = array(
                            'id'          => 'items_item_nama_{0}',
                            'name'        => 'items[{0}][item_nama]',
                            'class'       => 'form-control text-center input-xs hidden',
                            // 'readonly'    => 'readonly',
                            // 'type'         => 'number'
                            // 'style'       => 'width:180px;',
                        );

                        $attrs_item_jumlah_kirim = array(
                            'id'          => 'items_jumlah_kirim_{0}',
                            'name'        => 'items[{0}][jumlah_kirim]',
                            'class'       => 'form-control text-center input-xs',
                            // 'readonly'    => 'readonly',
                            'type'        => 'number',
                            'value'       =>  0,
                            // 'style'       => 'width:180px;',
                        );


                        $attrs_item_satuan = array(
                            'id'          => 'items_satuan_{0}',
                            'name'        => 'items[{0}][satuan]',
                            'class'       => 'form-dropdown input-xs',
                            'value'       => '<?=php?>',
                            // 'readonly'    => 'readonly',
                            // 'style'       => 'width:180px;',
                        );

                        $attrs_item_satuan_persetujuan = array(
                            'id'          => 'items_satuan_persetujuanl_{0}',
                            'name'        => 'items[{0}][satuan_persetujuanl]',
                            'class'       => 'form-control text-center input-xs hidden',
                            'readonly'    => 'readonly',
                            // 'value'       => 0,
                            // 'style'       => 'width:180px;',
                        );

                        $attrs_item_delete    = array (
                            'id'          => 'items_item_delete{0}',
                            'name'        => 'items[{0}][delete]',
                            'type'        => 'hidden',
                            'class'       => 'form-control',
                            'readonly'    => 'readonly',
                        );


                            $records = $form_item_detail->result_array();
                            // $records = $this->transfer_item_m->get_transfer_item($form_data['id'])->result_array();
                            // die(dump($records));
                            // die_dump($this->db->last_query());
                            // item row column
                            $item_cols = array(// style="width:156px;
                                'item_kode'             => form_input($attrs_item_id).form_input($attrs_item_kode).
                                                            '<div class="input-group text-right" style="width: 100%;">
                                                            <label class="control-label" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;"></label>
                                                            <span class="input-group-btn"><button type="button" title="" class="btn btn-xs btn-primary search-account"><i class="fa fa-search"></i></button></span>
                                                            </div>',
                                'item_nama'             => form_input($attrs_item_nama).'<label cass="control-label" name="items[{0}][item_nama]" style="text-align : left !important; width : 150px !important;"></label>',
                                'item_satuan'           => form_dropdown('items[{0}][satuan]',  $categories_satuan, "", "id='satuan' class='form-control bs-select-satuan'"),
                                'item_stok'             => '<div class="text-center">
                                                            <input class="form-Control text-center hidden" name="items[{0}][stock]">
                                                            <label class="control-label" name="items[{0}][stock]"></label>
                                                            </div>',
                                'item_jumlah_kirim'         => '<div class="input-group text-right" style="width: 100%;">
                                                                <input class="form-control text-center" id="items_jumlah_kirim_{0}" name="items[{0}][jumlah_kirim]"></input>
                                                                <span class="input-group-btn"><button type="button" title="Semua Stock" class="btn btn-xs btn-primary stock"><i class="fa fa-check"></i> '.translate("Semua", $this->session->userdata("language")).'</button></span>
                                                                </div>',
                                'aksi'                  => $btn_del,
                            );

                            $item_row_template =  '<tr id="item_row_{0}" class="row_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
                            $items_rows = array();


                            if($records)
                            {

                                $i = 0;
                                foreach ($records as $key => $data) 
                                {
                                    # code...
                                    
                                    $attrs_transfer_item_id['value'] = $data['transfer_item_id'];
                                    $attrs_item_kode['value'] = $data['item_kode'];
                                    $attrs_item_nama['value'] = $data['item_nama'];
                                    $attrs_item_id['value'] = $data['item_id'];
                                    // $attrs_item_satuan_id['value'] = $data['item_satuan_id'];
                                    $attrs_item_jumlah_kirim['value'] = $data['jumlah'];

                                    $satuan = $this->item_satuan_m->get_by(array('item_id' => $data['item_id']));
                                    $satuan_option = array(
                                        ''  => 'Pilih Satuan...'
                                    );

                                    foreach ($satuan as $data_satuan) {
                                        $satuan_option[$data_satuan->id] = $data_satuan->nama;
                                    }
                                    // die_dump($satuan_option);


                                    $item_cols = array(// style="width:156px;
                                        'item_kode'             => form_input($attrs_item_id).form_input($attrs_item_kode).form_input($attrs_item_delete).
                                                                    '<div class="input-group text-right" style="width: 100%;">
                                                                    <label class="control-label" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;">'.$data['item_kode'].'</label>
                                                                    <span class="input-group-btn"><button type="button" title="" class="btn btn-xs btn-primary search-account"><i class="fa fa-search"></i></button></span>
                                                                    </div>',
                                        'item_nama'             => form_input($attrs_item_nama).'<label cass="control-label" name="items[{0}][item_nama]" style="text-align : left !important; width : 150px !important;">'.$data['item_nama'].'</label>',
                                        'item_satuan'           => form_dropdown('items[{0}][satuan]',  $satuan_option, $data['item_satuan_id'], "id='satuan' class='form-control bs-select-satuan'"),
                                        'item_stok'             => '<div class="text-center">
                                                                    <input class="form-control text-center hidden" id="items_stock_{0}" name="items[{0}][stock]" value="'.$data['stock'].'"></input>
                                                                    <label class="control-label" name="items[{0}][stock]">'.$data['stock'].'</label>
                                                                    </div>',
                                        'item_jumlah_kirim'         => '<div class="input-group text-right" style="width: 100%;">
                                                                        <input class="form-control text-center" id="items_jumlah_kirim_{0}" name="items[{0}][jumlah_kirim]" value="'.$data['jumlah'].'"></input>
                                                                        <span class="input-group-btn"><button type="button" title="Semua Stock" class="btn btn-xs btn-primary stock"><i class="fa fa-check"></i> '.translate("Semua", $this->session->userdata("language")).'</button></span>
                                                                        </div>',
                                        'aksi'                  => $btn_del_db,
                                    );

                                    $item_row_template_edit =  '<tr id="item_row_{0}" class="row_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
                                    $items_rows[] = str_replace('{0}', "{$key}", $item_row_template_edit );
                                    $i++;
                                }
                            }
                            
                    ?>
                    <input class="text-center hidden" id="counter_edit" value="<?=$i?>">

                <div class="row">
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    
                </div>
                <div class="row">
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    
                </div>
                <div class="row">
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    
                </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Item Yang Akan Masuk", $this->session->userdata("language"))?></span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
                                    <!-- <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span> -->
                                    <div class="table-responsive">
                                        <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_item_terdaftar">
                                            <thead>
                                                <tr role="row" class="heading">
                                                    <!-- <th width="10%"><div class="text-center"><?=translate("id", $this->session->userdata('language'))?></div></th> -->
                                                    <th width="5%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
                                                    <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
                                                    <th width="10%"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
                                                    <th width="15%"><div class="text-center"><?=translate("Stock", $this->session->userdata('language'))?></div></th>
                                                    <th width="10%"><div class="text-center"><?=translate("Jumlah Kirim", $this->session->userdata('language'))?></div></th>
                                                    <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($items_rows as $row):?>
                                                    <?=$row?>
                                                <?php endforeach;?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            <div class="row">
                &nbsp;
                &nbsp;
                &nbsp;
                
            </div>
    
                <?php
                    $confirm_save       = translate('Apa Kamu Yakin Akan Ubah Transfer Item Ini ?',$this->session->userdata('language'));
                    $submit_text        = translate('Simpan', $this->session->userdata('language'));
                    $reset_text         = translate('Reset', $this->session->userdata('language'));
                    $back_text          = translate('Kembali', $this->session->userdata('language'));
                ?>
                <div class="form-actions fluid">    
                    <div class="col-md-offset-1 col-md-9">
                        
                        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
                        <!-- <button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button> -->
                        <!-- <a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$confirm_save?>" data-toggle="modal"><?=$submit_text?></a> -->
                        
                    </div>          
                </div>
            </div>
</div>

                            
<?=form_close();?>

<?php $this->load->view('gudang/transfer_item/pilih_item.php'); ?> 
