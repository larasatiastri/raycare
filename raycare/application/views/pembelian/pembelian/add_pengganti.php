<?php
    $form_attr = array(
        "id"            => "form_add_pembelian", 
        "name"          => "form_add_pembelian", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        "role"          => "form"
    );
    
    $hidden = array(
        "command"   => "add_ganti",
        "pembelian_id" => $pk_value
    );

    echo form_open(base_url()."pembelian/pembelian/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $btn_search        = '<a class="btn btn-primary search-item" data-original-title="Search Item" data-status-row="item_row_add" title="'.translate('Pilih Item', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a>';
    $btn_search_jumlah = '';
    
    $persen_pph = 0;
    $persen_ppn = ($data_supplier[0]['is_pkp'] == 1)?10:0;

    if($data_supplier[0]['is_pkp'] == 1 && $data_supplier[0]['is_pph'] == 1){
        $persen_pph = 2;
    }elseif($data_supplier[0]['is_pkp'] == 0 && $data_supplier[0]['is_pph'] == 1){
        $persen_pph = 4;
    }elseif($data_supplier[0]['is_pkp'] == NULL && $data_supplier[0]['is_pph'] == 1){
        $persen_pph = 4;
    }else{
        $persen_pph = 0;
    }

    if($form_data_detail != '')
    {
        $i = 0;
        $total = 0;
        $item_row_template_db = '';
        $hidden = ''; 
        if($form_data[0]['is_single_kirim'] == 1) $hidden = 'hidden';
        $total_pph_23 = 0;

        foreach ($form_data_detail as $detail) 
        {
            $btn_del_db = '<div class="text-center"><button class="btn red-intense del-this-db" data-confirm="'.translate('Anda yakin akan menghapus item ini?', $this->session->userdata('language')).'" data-index="'.$i.'" data-id="'.$detail['id'].'" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

            $btn_jumlah_db        = '<a class="btn blue-chambray add-jumlah '.$hidden.'" href="'.base_url().'pembelian/pembelian/edit_jumlah_edit/item_row_'.$i.'/'.$detail['id'].'" data-original-title="Tambah Jumlah" title="'.translate('Tambah Jumlah', $this->session->userdata('language')).'" data-toggle="modal" data-target="#popup_modal_jumlah" ><i class="fa fa-truck"></i></a>';

            $data_satuan = $this->item_satuan_m->get_by(array('item_id' => $detail['item_id']));

            $data_stok = $this->inventory_m->get_data_stok($detail['item_id'],$detail['item_satuan_id']);
            $stock = (count($data_stok) != 0)?$data_stok[0]['stock']:'0';

            $supplier_item = $this->supplier_item_m->get_by(array('supplier_id' => $form_data[0]['id'], 'item_id' => $detail['item_id'], 'item_satuan_id' => $detail['item_satuan_id']), true);


            $syarat = '-';
            if(count($supplier_item) != 0)
            {
                if($supplier_item->minimum_order != ''){
                    $syarat = $supplier_item->minimum_order.'/'.$supplier_item->kelipatan_order;
                }
            }
            $attrs_id_db = array ( 
                'id'       => 'items_id_'.$i,
                'type'     => 'hidden',
                'name'     => 'items['.$i.'][id]',
                'class'    => 'form-control',
                'readonly' => 'readonly',
                'value' => $detail['id'],
            );

            $attrs_item_id_db = array ( 
                'id'       => 'items_item_id_'.$i,
                'type'     => 'hidden',
                'name'     => 'items['.$i.'][item_id]',
                'class'    => 'form-control',
                'readonly' => 'readonly',
                'value' => $detail['item_id'],
            );

            $attrs_item_kode_db = array (
                'id'          => 'items_kode_'.$i,
                'name'        => 'items['.$i.'][item_kode]',
                'class'       => 'form-control',
                'readonly'    => 'readonly',
                'value' => $detail['kode'],
            );

            $attrs_item_nama_db = array(
                'id'          => 'items_nama_'.$i,
                'name'        => 'items['.$i.'][item_nama]',
                'class'       => 'form-control hidden',
                'readonly'    => 'readonly',
                'value' => $detail['nama'],
            );

            $attrs_item_syarat_db = array(
                'id'          => 'items_syarat_'.$i,
                'name'        => 'items['.$i.'][item_syarat]',
                'class'       => 'form-control hidden',
                'readonly'    => 'readonly',
                'value' => $detail['item_id'],
            );

            $attrs_item_harga_db = array(
                'id'          => 'items_harga_'.$i,
                'name'        => 'items['.$i.'][item_harga]',
                'class'       => 'text-right form-control',
                'value' => $detail['harga_beli'],
            );

            // if($form_data[0]['is_harga_flexible'] == 0) {
            //     $attrs_item_harga_db['readonly'] = 'readonly';
            // }else if($form_data[0]['is_harga_flexible'] == 1) {
            //     unset($attrs_item_harga_db['readonly']);
            // }

            $attrs_item_diskon_db = array(
                'id'          => 'items_diskon_'.$i,
                'type'      => 'number',
                'name'        => 'items['.$i.'][item_diskon]',
                'class'       => 'text-right form-control',
                'value' => $detail['diskon'],
            );

            $attrs_stok_db = array(
                'id'    => 'items_stok_'.$i,
                'name'  => 'items['.$i.'][stok]', 
                'type'  => 'hidden',
                'min'   => 0,
                'class' => 'form-control text-right',
                'style' => 'width:80px;',
                'value' => 1,
                'value' => $stock,
            );

            $attrs_jumlah_db = array(
                'id'    => 'items_jumlah_'.$i,
                'name'  => 'items['.$i.'][jumlah]', 
                'type'  => 'number',
                'min'   => 0,
                'class' => 'form-control text-right',
                'value' => $detail['jumlah_pesan'],
                'max' => $detail['jumlah_pesan'],
            );

            $attrs_item_total_db = array(
                'id'          => 'items_total_'.$i,
                'name'        => 'items['.$i.'][item_sub_total]',
                'class'       => 'form-control hidden sub_total',
                'readonly'    => 'readonly',
                'value' => $detail['jumlah_pesan']*$detail['harga_beli'],

            );

            $attrs_item_is_active_db = array(
                'id'          => 'items_is_active_'.$i,
                'name'        => 'items['.$i.'][is_active]',
                'class'       => 'form-control hidden',
                'readonly'    => 'readonly',
                'value' => 1,

            );

            $attrs_item_is_pph_db = array(
                'id'          => 'items_is_pph_'.$i,
                'name'        => 'items['.$i.'][is_pph]',
                'class'       => 'form-control hidden is_pph',
                'readonly'    => 'readonly',
                'value' => $supplier_item->is_pph,

            );

            $satuan_option = array(
                '' => 'Pilih..'
            );

            foreach ($data_satuan as $satuan) {
                $satuan_option[$satuan->id] = $satuan->nama;
            }

            if($supplier_item->is_pph == 1){
                $total_pph_23 = $total_pph_23 +  (($persen_pph/100)*($detail['jumlah_pesan']*$detail['harga_beli']));
            }

            $item_cols_db = array(// style="width:156px;
                'item_kode'          => '<label class="control-label hidden" name="items['.$i.'][item_kode]">'.$detail['kode'].'</label>'.form_input($attrs_item_kode_db).form_input($attrs_item_id_db).form_input($attrs_id_db),
                'item_name'          => '<label class="control-label" name="items['.$i.'][item_nama]">'.$detail['nama'].'</label>'.form_input($attrs_item_nama_db).'<div id="tabel_simpan_data_'.$i.'" class="hidden"></div><div id="detail_kirim" class="hidden">',
                'item_satuan'        => form_dropdown('items['.$i.'][satuan_id]', $satuan_option,$detail['item_satuan_id'], 'id="items_satuan_'.$i.'" data-row="'.$i.'" class="form-control satuan"'),
                'item_harga'         => '<div class="text-right"><label class="control-label hidden" name="items['.$i.'][item_harga]" id="items_hargaEl_'.$i.'"></label></div><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span>'.form_input($attrs_item_harga_db).'</div>',
                'item_diskon'        => form_input($attrs_item_diskon_db),
                'item_jumlah'        => '<div class="input-group">'.form_input($attrs_jumlah_db).'<span class="input-group-btn">'.$btn_jumlah_db.'</span></div>',

                'item_total'         => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_sub_total]">'.formatrupiah($detail['jumlah_pesan']*$detail['harga_beli']).'</label></div>'.form_input($attrs_item_total_db),
                'action'             => form_input($attrs_item_is_active_db).form_input($attrs_item_is_pph_db).$btn_del_db,
            );

            $item_row_template_db .=  '<tr id="item_row_'.$i.'" class="table_item_beli"><td>' . implode('</td><td>', $item_cols_db) . '</td></tr>';

            $total = $total + ($detail['jumlah_pesan']*$detail['harga_beli']);
            $i++;
        }
    } 
    else
    {
        $i = 0;
        $item_row_template_db = '';
    }

    $btn_search        = '<a class="btn btn-primary search-item" data-original-title="Search Item" data-status-row="item_row_add" title="'.translate('Pilih Item', $this->session->userdata('language')).'"><i class="fa fa-search"></i></a>';
    $btn_jumlah        = '<a class="btn blue-chambray add-jumlah" href="'.base_url().'pembelian/pembelian/add_jumlah/item_row_{0}" data-original-title="Tambah Jumlah" title="'.translate('Tambah Jumlah', $this->session->userdata('language')).'" data-toggle="modal" data-target="#popup_modal_jumlah"  disabled><i class="fa fa-truck"></i></a>';
    $btn_search_jumlah = '<a class="btn btn-primary search-jumlah" id="search_jumlah_permintaan_{0}" data-original-title="Jumlah" title="'.translate('Search Permintaan', $this->session->userdata('language')).'"><i class="fa fa-chain"></i></a>';
    $btn_del           = '<div class="text-center"><button class="btn red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

    $attrs_id = array ( 
        'id'       => 'items_id_{0}',
        'type'     => 'hidden',
        'name'     => 'items[{0}][id]',
        'class'    => 'form-control',
        'readonly' => 'readonly',
    );

    $attrs_item_id  = array ( 
        'id'       => 'items_item_id_{0}',
        'type'     => 'hidden',
        'name'     => 'items[{0}][item_id]',
        'class'    => 'form-control',
        'readonly' => 'readonly'
    );

    $attrs_item_kode = array (
        'id'          => 'items_kode_{0}',
        'name'        => 'items[{0}][item_kode]',
        'class'       => 'form-control',
        'readonly'    => 'readonly',
    );

    $attrs_item_nama = array(
        'id'          => 'items_nama_{0}',
        'name'        => 'items[{0}][item_nama]',
        'class'       => 'form-control hidden',
        'readonly'    => 'readonly',
    );

    $attrs_item_syarat = array(
        'id'          => 'items_syarat_{0}',
        'name'        => 'items[{0}][item_syarat]',
        'class'       => 'form-control hidden',
        'readonly'    => 'readonly',
    );

    $attrs_item_harga = array(
        'id'          => 'items_harga_{0}',
        'name'        => 'items[{0}][item_harga]',
        'class'       => 'form-control text-right',
        'value'       => 0,
    );

    $attrs_item_harga_lama = array(
        'id'          => 'items_harga_lama_{0}',
        'name'        => 'items[{0}][item_harga_lama]',
        'class'       => 'form-control text-right hidden',
        'value'       => 0,
    );

    $attrs_item_diskon = array(
        'id'          => 'items_diskon_{0}',
        'name'        => 'items[{0}][item_diskon]',
        'class'       => 'form-control text-right',
        'type'        => 'number',
        'min'         => 0,
        'value'       => 0,
    );

    $attrs_stok = array(
        'id'    => 'items_stok_{0}',
        'name'  => 'items[{0}][stok]', 
        'type'  => 'number',
        'min'   => 0,
        'class' => 'form-control text-right hidden',
        'style' => 'width:80px;',
        'value' => 1,
    );

    $attrs_jumlah = array(
        'id'    => 'items_jumlah_{0}',
        'name'  => 'items[{0}][jumlah]',
        'min'   => 0,
        'class' => 'form-control text-right',
        'type'  => 'number',
        'value' => '0'
        // 'style' => 'width:80px;'
    );

    $attrs_jumlah_min = array(
        'id'    => 'items_jumlah_min_{0}',
        'name'  => 'items[{0}][jumlah_min]',
        'min'   => 0,
        'class' => 'form-control text-right hidden',
        'type'  => 'number',
        'value' => '0'
        // 'style' => 'width:80px;'
    );

    $attrs_satuan_nama = array(
        'id'    => 'items_satuan_nama_{0}',
        'name'  => 'items[{0}][satuan_nama]',
        'min'   => 0,
        'class' => 'form-control hidden'
        // 'style' => 'width:80px;'
    );

    $attrs_satuan_id = array(
        'id'    => 'items_satuan_id_{0}',
        'name'  => 'items[{0}][satuan_id]',
        'min'   => 0,
        'class' => 'form-control hidden'
        // 'style' => 'width:80px;'
    );

    $attrs_item_total = array(
        'id'          => 'items_total_{0}',
        'name'        => 'items[{0}][item_sub_total]',
        'class'       => 'form-control hidden sub_total',
        'readonly'    => 'readonly',
        'value'       => 0
    );

    $attrs_tanggal_kirim_item = array(
        'id'          => 'items_tanggal_kirim_{0}',
        'name'        => 'items[{0}][item_tanggal_kirim]',
        'class'       => 'form-control date-picker'
    );

    $attrs_item_is_active = array(
        'id'          => 'items_is_active_{0}',
        'name'        => 'items[{0}][is_active]',
        'class'       => 'form-control hidden',
        'readonly'    => 'readonly',
        'value' => 1,
    );

    $satuan_option = array(
        '' => 'Pilih..'
    );

    $item_cols = array(// style="width:156px;
        'item_kode'          => '<label class="control-label hidden" name="items[{0}][item_kode]"></label>'.form_input($attrs_id).form_input($attrs_item_id).form_input($attrs_item_kode),
        'item_name'          => '<label class="control-label" name="items[{0}][item_nama]"></label>'.form_input($attrs_item_nama).form_input($attrs_id_db).'<div id="tabel_simpan_data_{0}" class="hidden"></div><div id="detail_kirim" class="hidden">',
        'item_satuan'        => form_dropdown('items[{0}][satuan_id]', $satuan_option, "", "id=\"items_satuan_{0}\" data-row=\"{0}\" class=\"form-control satuan\"").form_input($attrs_satuan_nama),
        'item_harga'         => '<div class="text-right"><label class="control-label hidden" name="items[{0}][item_harga]" id="items_hargaEl_{0}"></label></div><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span>'.form_input($attrs_item_harga_lama).form_input($attrs_item_harga).'</div>',
        'item_diskon'        => form_input($attrs_item_diskon),
        'item_jumlah'        => '<div class="input-group">'.form_input($attrs_jumlah).form_input($attrs_jumlah_min).'<span class="input-group-btn">'.$btn_jumlah.'</span></div>',

        'item_total'         => '<div class="text-right"><label class="control-label" name="items[{0}][item_sub_total]"></label></div>'.form_input($attrs_item_total),
        'action'             => form_input($attrs_item_is_active).$btn_del,
    );

    $item_row_template =  '<tr id="item_row_{0}" class="table_item_beli"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

    $msg = translate("Apakah anda yakin akan mengubah PO ini?",$this->session->userdata("language"));
    $msg_draft= translate("Apakah and yakin akan menyimpan PO ini ke Draft?", $this->session->userdata("language"));

    if($form_data[0]['tipe_customer'] == 1)
    {
        $data_customer = $this->penerima_cabang_m->get($form_data[0]['customer_id']);
        $customer_alamat[0] = $this->cabang_alamat_m->get_alamat_lengkap($form_data[0]['customer_id']);
        $customer_email = $this->cabang_sosmed_m->get_by(array('cabang_id' => $form_data[0]['customer_id'], 'tipe' => 1, 'is_active' => 1), true);
    }

    $data_penawaran = $this->pembelian_penawaran_m->get_by(array('pembelian_id' => $pk_value, 'is_active' => 1));
    if($data_penawaran)
    {
        $data_penawaran = object_to_array($data_penawaran);
        $x = 0;
        $item_row_template_penawaran_db = '';
        foreach ($data_penawaran as $penawaran)
        {
            $btn_del_penawaran_db = '<div class="text-center"><button class="btn red-intense del-this-penawaran-db" data-index="'.$x.'" data-confirm="'.translate('Anda yakin akan menghapus penawaran ini?', $this->session->userdata('language')).'" data-id="'.$penawaran['id'].'" title="Hapus Penawaran"><i class="fa fa-times"></i></button></div>';

            $item_cols_penawaran_db = array(// style="width:156px;
                'penawaran_nomor'  => '<input type="hidden" id="penawaran_id_'.$x.'" name="penawaran['.$x.'][id]" class="form-control" value="'.$penawaran['id'].'"><input id="penawaran_nomor_'.$x.'" name="penawaran['.$x.'][nomor]" class="form-control" value="'.$penawaran['nomor_penawaran'].'">',
                'penawaran_upload' => '<div class="input-group">
                                            <input id="penawaran_url_'.$x.'" name="penawaran['.$x.'][url]" class="form-control" value="'.$penawaran['url'].'" readonly>
                                            <span class="input-group-btn" id="upload_'.$x.'">
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new">'.translate('Pilih File', $this->session->userdata('language')).'</span>       
                                                <input type="file" name="upl" id="pdf_file_'.$x.'" data-url="'.base_url().'upload/upload_pdf" multiple />
                                            </span>
                                            </span>
                                        </div>',
                'action'           => '<input type="hidden" id="penawaran_is_active_'.$x.'" name="penawaran['.$x.'][is_active]" value="1" class="form-control">'.$btn_del_penawaran_db,
            );

            $item_row_template_penawaran_db .=  '<tr id="item_row_penawaran_'.$x.'" ><td>' . implode('</td><td>', $item_cols_penawaran_db) . '</td></tr>';

            $x++;
        }
    }
    else
    {
        $x = 0;
    }

    $btn_del_penawaran = '<div class="text-center"><button class="btn red-intense del-this-penawaran" title="Hapus Penawaran"><i class="fa fa-times"></i></button></div>';

    $item_cols_penawaran = array(// style="width:156px;
        'penawaran_nomor'  => '<input type="hidden" id="penawaran_id_{0}" name="penawaran[{0}][id]" class="form-control"><input id="penawaran_nomor_{0}" name="penawaran[{0}][nomor]" class="form-control">',
        'penawaran_upload' => '<div class="input-group">
                                    <input id="penawaran_url_{0}" name="penawaran[{0}][url]" class="form-control" readonly>
                                    <span class="input-group-btn" id="upload_{0}">
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new">'.translate('Pilih File', $this->session->userdata('language')).'</span>       
                                        <input type="file" name="upl" id="pdf_file_{0}" data-url="'.base_url().'upload/upload_pdf" multiple />
                                    </span>
                                    </span>
                                </div>',
        'action'           => '<input type="hidden" id="penawaran_is_active_{0}" name="penawaran[{0}][is_active]" value="1" class="form-control">'.$btn_del_penawaran,
    );

    $item_row_template_penawaran =  '<tr id="item_row_penawaran_{0}" ><td>' . implode('</td><td>', $item_cols_penawaran) . '</td></tr>';

    $supplier_email = $this->supplier_email_m->get_by(array('supplier_id' => $form_data[0]['id'], 'is_active' => 1, 'is_primary' => 1), true);

    $email_supp = (count($supplier_email) != 0)?$supplier_email->email:'-';

?>
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-pencil font-blue-sharp"></i>
            <span class="caption font-blue-sharp bold uppercase"><?=translate("Buat PO Pengganti", $this->session->userdata("language")).' '.$form_data[0]['no_pembelian']?></span>
        </div>
        <div class="actions">
            <a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
            <a id="confirm_save" class="btn btn-primary btn-circle" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
            <input type="hidden" id="save_draft" name="save_draft">
            <input type="hidden" id="jml_baris" name="jml_baris" value="<?=$i?>">
            <input type="hidden" id="jml_penawaran" name="jml_penawaran" value="<?=$x?>">
            <input type="hidden" id="tipe_bayar" name="tipe_bayar" value="<?=$form_data[0]['tipe_pembayaran']?>">
            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
            <a id="confirm_save_draft" class="btn btn-primary btn-circle hidden" href="#" data-confirm="<?=$msg_draft?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan ke Draft", $this->session->userdata("language"))?></a>
        </div>
    </div>
    <div class="note note-danger note-bordered">
        <p>
             NOTE: Pembuatan PO Pengganti ini hanya dapat digunakan untuk merubah harga dan jumlah pesan lebih kecil dari PO sebelumnya
        </p>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <?=$form_alert_danger?>
            </div>
            <div class="alert alert-success display-hide">
                <button class="close" data-close="alert"></button>
                <?=$form_alert_success?>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="portlet">
                        <div class="portlet-body form">
                            <div class="tabbable-custom nav-justified">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active">
                                        <a href="#tab_supplier" data-toggle="tab">
                                        Supplier </a>
                                    </li>
                                    <li>
                                        <a href="#tab_penerima" data-toggle="tab">
                                        Penerima </a>
                                    </li>
                                    <li>
                                        <a href="#tab_info_pembelian" data-toggle="tab">
                                        Info Pembelian </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_supplier">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <div class="radio-list">
                                                        <label class="radio-inline">
                                                        <input type="radio" name="tipe_supplier" id="tipe_supplier_1" value="1" checked> <?=translate("Dalam Negeri", $this->session->userdata("language"))?> </label>
                                                        <label class="radio-inline">
                                                        <input type="radio" name="tipe_supplier" id="tipe_supplier_2" value="2"> <?=translate("Luar Negeri", $this->session->userdata("language"))?> </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <?php
                                                        $nama_supplier = array(
                                                            "id"        => "nama_supplier",
                                                            "name"      => "nama_supplier",
                                                            "autofocus" => true,
                                                            "class"     => "form-control", 
                                                            "readonly"  => "readonly",
                                                            "value"     => $form_data[0]['nama'],
                                                        );

                                                        $id_supplier = array(
                                                            "id"        => "id_supplier",
                                                            "name"      => "id_supplier",
                                                            "autofocus" => true,
                                                            "class"     => "form-control hidden",
                                                            "value"     => $form_data[0]['id'],
                                                        );
                                                        echo form_input($nama_supplier);
                                                        echo form_input($id_supplier);

                                                        $is_harga_flexible = array(
                                                            "id"          => "is_harga_flexible",
                                                            "name"        => "is_harga_flexible",
                                                            "class"       => "form-control hidden",
                                                            "value"       => $form_data[0]['is_harga_flexible']
                                                        );
                                                        echo form_input($is_harga_flexible);

                                                        $is_pkp = array(
                                                            "id"          => "is_pkp",
                                                            "name"        => "is_pkp",
                                                            "class"       => "form-control hidden",
                                                            "value"         => (count($form_data) != 0)?$form_data[0]['is_pkp']:0,
                                                        );
                                                        echo form_input($is_pkp);
                                                    ?>
                                                        
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Kontak", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <?php
                                                        $kontak_supplier = array(
                                                            "id"            => "kontak_supplier",
                                                            "name"          => "kontak_supplier",
                                                            "class"         => "form-control",
                                                            "placeholder"   => translate("Kontak", $this->session->userdata("language")),
                                                            "readonly"      => "readonly",
                                                            "value"         => $form_data[0]['no_telp']
                                                        );
                                                        echo form_input($kontak_supplier);
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <?php
                                                        $alamat_supplier = array(
                                                            "id"            => "alamat_supplier",
                                                            "name"          => "alamat_supplier",
                                                            "rows"          => 3,
                                                            "autofocus"     => true,
                                                            "class"         => "form-control",
                                                            "placeholder"   => translate("Alamat", $this->session->userdata("language")),
                                                            "readonly"      => "readonly",
                                                            "value"         => $form_data[0]['alamat'].', '.$form_data[0]['rt_rw'].', '.$form_data[0]['kelurahan'].', '.$form_data[0]['kecamatan'].', '.$form_data[0]['kota'].', '.$form_data[0]['propinsi'].', '.$form_data[0]['negara'],
                                                        );
                                                        echo form_textarea($alamat_supplier);
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Email", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <?php
                                                        $email_supplier = array(
                                                            "id"            => "email_supplier",
                                                            "name"          => "email_supplier",
                                                            "class"         => "form-control",
                                                            "placeholder"   => translate("Email", $this->session->userdata("language")),
                                                            "readonly"      => "readonly",
                                                            "value"         => $email_supp
                                                        );
                                                        echo form_input($email_supplier);
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_penerima">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <div class="radio-list">
                                                        <label class="radio-inline">
                                                        <input type="radio" name="tipe_penerima" id="tipe_penerima_1" value="1" checked> <?=translate("Internal", $this->session->userdata("language"))?> </label>
                                                        <label class="radio-inline">
                                                        <input type="radio" name="tipe_penerima" id="tipe_penerima_2" value="2"> <?=translate("Eksternal", $this->session->userdata("language"))?> </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Ditujukan ke", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                        <?php
                                                            $nama_penerima = array(
                                                                "id"            => "nama_penerima",
                                                                "name"          => "nama_penerima",
                                                                    "class"         => "form-control",
                                                                "readonly"      => "readonly",
                                                                'value'         => $data_customer->nama
                                                            );

                                                            $id_penerima = array(
                                                                "id"            => "id_penerima",
                                                                "name"          => "id_penerima",
                                                                    "class"         => "form-control hidden",
                                                                'value'         => $data_customer->id
                                                            );
                                                            $tipe_penerima = array(
                                                                "id"            => "tipe",
                                                                "name"          => "tipe",
                                                                    "class"         => "form-control hidden",
                                                                "value"         => "1"
                                                            );
                                                            echo form_input($nama_penerima);
                                                            echo form_input($id_penerima);
                                                            echo form_input($tipe_penerima);
                                                        ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Kontak", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <?php
                                                        $kontak_penerima = array(
                                                            "id"            => "kontak_penerima",
                                                            "name"          => "kontak_penerima",
                                                            "class"         => "form-control",
                                                            "placeholder"   => translate("Kontak", $this->session->userdata("language")),
                                                            "readonly"      => "readonly",
                                                            'value'         => $data_customer->penanggung_jawab
                                                        );
                                                        echo form_input($kontak_penerima);
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <?php
                                                        $alamat_penerima = array(
                                                            "id"            => "alamat_penerima",
                                                            "name"          => "alamat_penerima",
                                                            "rows"          => 3,
                                                            "class"         => "form-control",
                                                            "placeholder"   => translate("Alamat", $this->session->userdata("language")),
                                                            "readonly"      => "readonly",
                                                            'value'         => $customer_alamat[0]['alamat']
                                                        );
                                                        echo form_textarea($alamat_penerima);
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Email", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <?php
                                                        $email_penerima = array(
                                                            "id"            => "email_penerima",
                                                            "name"          => "email_penerima",
                                                            "class"         => "form-control",
                                                            "placeholder"   => translate("Email", $this->session->userdata("language")),
                                                            "readonly"      => "readonly",
                                                            'value'         => $customer_email->url
                                                        );
                                                        echo form_input($email_penerima);
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_info_pembelian">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control" id="tanggal_pesan" name="tanggal_pesan" readonly value="<?=date('d M Y', strtotime($form_data[0]['tanggal_pesan']))?>">
                                                        <span class="input-group-btn">
                                                            <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Pengiriman Satu Tanggal", $this->session->userdata("language"))?> :</label>
                                                <?php 
                                                    $check_ya = '';
                                                    $check_tdk = '';
                                                    $hidden = '';

                                                    if($form_data[0]['is_single_kirim'] == 1){
                                                        $check_ya = 'checked';
                                                        $check_tdk = '';
                                                        $hidden = '';
                                                    }if($form_data[0]['is_single_kirim'] == 0){
                                                        $check_ya = '';
                                                        $check_tdk = 'checked';
                                                        $hidden = 'hidden';
                                                    }
                                                ?>
                                                
                                                <div class="col-md-12">
                                                    <div class="radio-list">
                                                        <label class="radio-inline">
                                                        <input type="radio" name="is_single" id="optionskirimya" <?=$check_ya?> value="1"> Ya </label>
                                                        <label class="radio-inline">
                                                        <input type="radio" name="is_single" id="optionskirimtdk" <?=$check_tdk?> value="0"> Tidak </label>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group <?=$hidden?>" id="tgl_kirim">
                                                <label class="col-md-12"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control" id="tanggal_kirim" name="tanggal_kirim" value="<?=date('d M Y', strtotime($form_data[0]['tanggal_kirim']))?>" readonly >
                                                        <span class="input-group-btn">
                                                            <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" hidden>
                                                <label class="col-md-12"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" readonly value="<?=date('d M Y', strtotime($form_data[0]['tanggal_kadaluarsa']))?>">
                                                        <span class="input-group-btn">
                                                            <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                                
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Garansi", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <div class="input-group date">
                                                        <input type="text" class="form-control" id="tanggal_garansi" name="tanggal_garansi" readonly value="<?=($form_data[0]['tanggal_garansi'] != '1970-01-01')?date('d M Y', strtotime($form_data[0]['tanggal_garansi'])):''?>">
                                                        <span class="input-group-btn">
                                                            <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate('Tipe Pembayaran', $this->session->userdata('language'))?> :</label>
                                                <div class="col-md-12">
                                                    <?php 
                                                        $pembayaran_option = array(
                                                            ''  => 'Pilih Pembayaran',
                                                        );

                                                        foreach ($supplier_tipe_bayar as $tipe_bayar) 
                                                        {
                                                            $tempo = ($tipe_bayar['lama_tempo'] != '')?$tipe_bayar['lama_tempo'].' hari':'';
                                                            $pembayaran_option[$tipe_bayar['id']] = $tipe_bayar['nama'].' '.$tempo;
                                                        }

                                                        echo form_dropdown("tipe_pembayaran", $pembayaran_option, $form_data[0]['tipe_pembayaran'], " id='tipe_pembayaran' name='tipe_pembayaran' class='form-control' ");
                                                     ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
                                                <div class="col-md-12">
                                                    <?php
                                                        $keterangan = array(
                                                            "id"            => "keterangan",
                                                            "name"          => "keterangan",
                                                            "rows"          => 6,
                                                            "class"         => "form-control",
                                                            "style"         => "resize: none;",
                                                            "placeholder"   => translate("Keterangan", $this->session->userdata("language")),
                                                            "value"         => 'Pengganti '.$form_data[0]['no_pembelian']
                                                        );
                                                        echo form_textarea($keterangan);
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption"><?=translate("Penawaran", $this->session->userdata("language"))?></div>
                            <div class="actions">
                                <a class="btn btn-icon-only btn-default btn-circle add-upload">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <span id="tpl_penawaran_row" class="hidden"><?=htmlentities($item_row_template_penawaran)?></span>
                            <table class="table table-striped table-bordered table-hover" id="table_penawaran">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="8%"><?=translate("No. Penawaran", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" width="45%"><?=translate("Upload", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                 <?php
                                    if(count($data_penawaran))
                                    {
                                        echo $item_row_template_penawaran_db;
                                    }
                                 ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <span class="caption-subject"><?=translate("Detail Pengiriman", $this->session->userdata("language"))?></span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="form-body">
                            <?php 
                                $data_pengiriman = $this->pembelian_detail_tanggal_kirim_m->get_tanggal_kirim($pk_value);

                                $idx = 1;
                                foreach ($data_pengiriman as $key => $data_kirim){
                            ?>
                                
                                    <table class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th colspan="4"><?=date('d M Y', strtotime($data_kirim['tanggal_kirim']))?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                    $data_kirim_detail = $this->pembelian_detail_tanggal_kirim_m->get_tanggal_kirim_detail($pk_value, $data_kirim['tanggal_kirim']);

                                    $idy = a;
                                    foreach ($data_kirim_detail as $key => $kirim_detail) {
                                ?>
                                    <tr>
                                    <td><?=$kirim_detail['kode_item']?></td>
                                    <td><?=$kirim_detail['nama_item']?></td>
                                    <td><?=$kirim_detail['jumlah_kirim']?></td>
                                    <td><?=$kirim_detail['nama_satuan']?></td>
                                    </tr>
                                <?php
                                    $idy++;

                                    }   
                                ?>      
                                    </tbody>
                                    </table>
                        <?php
                                    $idx++;
                                }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-9">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <span class="caption-subject"><?=translate("Detail Item", $this->session->userdata("language"))?></span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover" id="table_detail_pembelian">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 150px !important;"><?=translate("Kode", $this->session->userdata("language"))?> </th>
                                        <th class="text-center"style="width : 350px !important;"><?=translate("Nama", $this->session->userdata("language"))?> </th>
                                        <th class="text-center"style="width : 120px !important;"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
                                        <th class="text-center"style="width : 125px !important;"><?=translate("Harga", $this->session->userdata("language"))?> </th>
                                        <th class="text-center"><?=translate("Diskon", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" style="width : 100px !important;"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
                                        <th class="text-center"style="width : 140px !important;"><?=translate("Sub Total", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(count($item_cols_db) != 0)
                                        {
                                            echo $item_row_template_db;
                                        }
                                    
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-right bold">Total</td>
                                        <td colspan="2">
                                            <div class="input-group col-md-12">
                                                <span class="input-group-addon">
                                                    &nbsp;Rp&nbsp;
                                                </span>
                                                <input class="form-control text-right" readonly value="<?=$total?>" id="total" name="total">
                                            </div>
                                            <input class="form-control text-right hidden" readonly value="<?=$total?>" id="total_hidden" name="total_hidden">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right bold">Diskon</td>
                                        <td>
                                            <div class="input-group col-md-12">
                                                <input class="form-control text-right" min="0" max="100" id="diskon" name="diskon" value="<?=$form_data[0]['diskon']?>">
                                                <span class="input-group-addon">
                                                    &nbsp;%&nbsp;
                                                </span>
                                            </div>
                                        </td>
                                        <td colspan="2">
                                            <div class="input-group col-md-12">
                                                <span class="input-group-addon">
                                                    &nbsp;Rp&nbsp;
                                                </span>
                                                <input class="form-control text-right" id="diskon_nominal" name="diskon_nominal" value="<?=($form_data[0]['diskon'] / 100) * $total?>">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right bold">PPN</td>
                                        <td>
                                            <div class="input-group col-md-12">
                                                <input class="form-control text-right" min="0" max="100" id="pph" name="pph" value="<?=$form_data[0]['pph']?>">
                                                <span class="input-group-addon">
                                                    &nbsp;%&nbsp;
                                                </span>
                                            </div>
                                        </td>
                                        <td colspan="2">
                                            <div class="input-group col-md-12">
                                                <span class="input-group-addon">
                                                    &nbsp;Rp&nbsp;
                                                </span>
                                                <input class="form-control text-right" id="pph_nominal" name="pph_nominal" value="<?=($form_data[0]['pph']/100)* $total?>">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right bold">Total Setelah PPN</td>
                                        <td colspan="2">
                                            <div class="input-group col-md-12">
                                                <span class="input-group-addon">
                                                    &nbsp;IDR&nbsp;
                                                </span>
                                                <input class="form-control text-right" readonly value="0" id="total_after_tax" name="total_after_tax">
                                            </div>
                                            <input class="form-control text-right hidden" readonly value="0" id="total_after_tax_hidden" name="total_after_tax_hidden">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right bold">PPH 23</td>
                                        <td style="background-color:#f9f9f9">
                                            <div class="input-group col-md-12">
                                                <input class="form-control text-right" type="number" value="<?=$persen_pph?>" min="0" max="100" id="pph23" name="pph23" readonly>
                                                <span class="input-group-addon">
                                                    &nbsp;%&nbsp;
                                                </span>
                                            </div>
                                        </td>
                                        <td colspan="2">
                                            <div class="input-group col-md-12">
                                                <span class="input-group-addon">
                                                    &nbsp;IDR&nbsp;
                                                </span>
                                                <input class="form-control text-right" readonly value="<?=$total_pph_23?>" id="pph_23_nominal" name="pph_23_nominal">
                                            </div>
                                            <input class="form-control text-right hidden" readonly value="<?=$total_pph_23?>" id="pph_23_nominal_hidden" name="pph_23_nominal_hidden">
                                        </td>
                                    </tr>
                                    
                                    <?php
                                        $pembulatan = ($form_data[0]['pembulatan'] != 0)?$form_data[0]['pembulatan']:0;
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-right bold">Pembulatan</td>
                                        <td colspan="2">
                                            <div class="input-group col-md-12">
                                                <span class="input-group-addon">
                                                    &nbsp;Rp&nbsp;
                                                </span>
                                                <input type="text" class="form-control text-right" value="<?=$pembulatan?>" id="pembulatan" name="pembulatan">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right bold">Grand Total PO</td>
                                        <td colspan="2">
                                            <div class="input-group col-md-12">
                                                <span class="input-group-addon">
                                                    &nbsp;Rp&nbsp;
                                                </span>
                                                <input type="hidden" class="form-control text-right" value="<?=$form_data[0]['grand_total_po']?>" id="grand_total_hidden" name="grand_total_hidden">
                                                <input class="form-control text-right" readonly value="<?=$form_data[0]['grand_total_po']?>" id="grand_total" name="grand_total">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right bold">DP</td>
                                        <td>
                                            <div class="input-group col-md-12">
                                                <input class="form-control text-right" value="<?=$form_data[0]['dp']?>" min="0" max="100" id="dp" name="dp">
                                                <span class="input-group-addon">
                                                    &nbsp;%&nbsp;
                                                </span>
                                            </div>
                                        </td>
                                        <td colspan="2">
                                            <div class="input-group col-md-12">
                                                <span class="input-group-addon">
                                                    &nbsp;Rp&nbsp;
                                                </span>
                                                <input class="form-control text-right" id="dp_nominal" name="dp_nominal" value="<?=($form_data[0]['dp']/100) * $form_data[0]['grand_total_po']?>">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right bold">Sisa Bayar</td>
                                        <td colspan="2">
                                            <div class="input-group col-md-12">
                                                <span class="input-group-addon">
                                                    &nbsp;Rp&nbsp;
                                                </span>
                                                <input type="hidden" class="form-control text-right" readonly value="0" id="sisa_bayar_hidden" name="sisa_bayar_hidden">
                                                <input class="form-control text-right" readonly value="0" id="sisa_bayar" name="sisa_bayar">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right bold">Biaya Tambahan<div class="hidden" id="biaya_tambahan"></div></td>
                                        <td >
                                            <a class="btn blue-chambray add-biaya" title="<?=translate('Tambah Biaya', $this->session->userdata('language'))?>" href="<?=base_url()?>pembelian/pembelian/edit_biaya/<?=$pk_value?>" data-toggle="modal" data-target="#modal_biaya">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                        <td colspan="2">
                                            <div class="input-group col-md-12">
                                                <span class="input-group-addon">
                                                    &nbsp;Rp&nbsp;
                                                </span>
                                                <input class="form-control text-right" id="biaya_tambahan" name="biaya_tambahan" value="<?=$form_data[0]['biaya_tambahan']?>">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right bold">Grand Total Setelah Biaya</td>
                                        <td colspan="2">
                                            <div class="input-group col-md-12">
                                                <span class="input-group-addon">
                                                    &nbsp;Rp&nbsp;
                                                </span>
                                                <input type="hidden" class="form-control text-right" value="<?=$form_data[0]['grand_total']?>" id="grand_total_biaya_hidden" name="grand_total_biaya_hidden">
                                                <input class="form-control text-right" readonly value="<?=formattanparupiah($form_data[0]['grand_total'])?>" id="grand_total_biaya" name="grand_total_biaya">
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?=form_close()?>
    </div>
</div>  

<div id="popover_item_content" style="display:none;" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_supplier">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kontak Person', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Rating', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_penerima_content_cabang" style="display:none;" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover"  id="table_pilih_cabang">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Cabang', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kontak Person', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_penerima_content_customer" style="display:none;" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover"  id="table_pilih_customer">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kontak Person', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_item_content_pembelian" style="display:none;" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover"  id="table_item_search">
            <thead>
                <tr>
                    <th><div class="text-center"><?=translate('Id', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Stok', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Belum Diterima', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Satuan', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Min', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Max', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Harga', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="popover_jumlah_pesan" class="row" style="display:none;">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Search Permintaan", $this->session->userdata("language"))?></span>
                </div>
                <div class="actions">
                    <a data-target="#ajax_notes1" data-toggle="modal" class="btn btn-primary btn-circle" id="tambah-link">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
                             <?=translate("Tambah Order", $this->session->userdata("language"))?>
                        </span>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-condensed table-striped table-bordered table-hover"  id="table_search_permintaan">
                    <thead>
                        <tr>
                            <th class="hidden"><div class="text-center"><?=translate('Id', $this->session->userdata('language'))?></div></th>
                            <th><div class="text-center"><?=translate('Tanggal', $this->session->userdata('language'))?></div></th>
                            <th><div class="text-center"><?=translate('User (User Level)', $this->session->userdata('language'))?></div></th>
                            <th><div class="text-center"><?=translate('Subjek', $this->session->userdata('language'))?></div></th>
                            <th><div class="text-center"><?=translate('Keterangan', $this->session->userdata('language'))?></div></th>
                            <th><div class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
                            <th class="hidden"><div class="text-center"><?=translate('Id Detail', $this->session->userdata('language'))?></div></th>
                            <th><div class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bs-modal-lg" id="modal_biaya" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>
<div class="modal fade bs-modal-lg" id="popup_modal_jumlah" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>
