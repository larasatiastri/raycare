<?php

    //////////////////////////////////////////////////////////////////////////////////////
   
    $form_attr = array(
        "id"            => "form_proses", 
        "name"          => "form_proses", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );

    $hidden = array(
        "command"   => "submit"
    );


    echo form_open(base_url()."gudang/transfer_item/proses_item", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');

    // die_dump($data_order);


?>  
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-check font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Transfer Item", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions">
            <?php

                $back_text      = translate('Kembali', $this->session->userdata('language'));
                $confirm_save   = translate('Apa Kamu Yakin Akan Kirim Proses Request Item Ini ?',$this->session->userdata('language'));
                $confirm_reject   = translate('Apa Kamu Yakin Akan Reject Proses Request Item Ini ?',$this->session->userdata('language'));
                $submit_text    = translate('Simpan', $this->session->userdata('language'));
                $reset_text     = translate('Reset', $this->session->userdata('language'));
                $reject_text  = translate('Reject', $this->session->userdata('language'));
            ?>
                
            <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
                <i class="fa fa-chevron-left"></i>
                <?=$back_text?>
            </a>
            <button type="button" id="reject_proses" class="btn btn-primary hidden" >
                <?=$reject_text?>
            </button>
            <a id="confirm_reject_proses" class="btn btn-circle red" href="<?=base_url()?>gudang/transfer_item/reject_proses/<?=$form_data['id']?>" data-confirm="<?=$confirm_reject?>" data-toggle="modal" data-target="#popup_modal_proses">
                <i class="fa fa-times"></i>
                <?=$reject_text?>
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
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <?=$form_alert_danger?>
        </div>
        <div class="alert alert-success display-hide">
            <button class="close" data-close="alert"></button>
            <?=$form_alert_success?>
        </div>
        <div class="portlet-body form"> 
            <div class="form-wizard">
                <div class="row">
                    <div class="col-md-3">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <?=translate("Informasi", $this->session->userdata("language"))?>
                                </div>
                            </div>
                            <div class="form-body">
                                <div class="form-group hidden">
                                    <label class="col-md-12"><?=translate("Kode", $this->session->userdata("language"))?> :</label>
                                    <div class="col-md-12">
                                        <?php
                                            $pk_value = array(
                                                "id"            => "pk_value",
                                                "name"          => "pk_value",
                                                "class"         => "form-control", 
                                                "placeholder"   => translate("pk_value", $this->session->userdata("language")), 
                                                "value"         => $pk_value,
                                                "help"          => $flash_form_data['pk_value'],
                                            );
                                            echo form_input($pk_value);
                                        ?>
                                    </div>
                                </div>
                               
                                <div class="form-group">
                                    <label class="col-md-12"><?=translate("Transfer Dari :", $this->session->userdata("language"))?></label>     
                                    <div class="col-md-12">
                                        <?php $dari_gudang_id = $this->gudang_m->get($form_data['dari_gudang_id'])?>
                                        <label class="control-label"><?=$dari_gudang_id->nama?></label>
                                    </div>
                                    <div class="col-xs-2">
                                        <?php
                                            $dari_gudang_id = array(
                                                "id"            => "dari_gudang_id",
                                                "name"          => "dari_gudang_id",
                                                "class"         => "form-control hidden", 
                                                "placeholder"   => translate("dari_gudang_id", $this->session->userdata("language")), 
                                                "value"         => $form_data['dari_gudang_id'],
                                            );
                                            echo form_input($dari_gudang_id);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12"><?=translate("Transfer Ke :", $this->session->userdata("language"))?></label>     
                                    <div class="col-md-12">
                                        <?php $ke_gudang_id = $this->gudang_m->get($form_data['ke_gudang_id'])?>
                                        <label class="control-label"><?=$ke_gudang_id->nama?></label>
                                        
                                    </div>
                                    <div class="col-md-12">
                                        <?php
                                            $ke_gudang_id = array(
                                                "id"            => "ke_gudang_id",
                                                "name"          => "ke_gudang_id",
                                                "class"         => "form-control hidden", 
                                                "placeholder"   => translate("ke_gudang_id", $this->session->userdata("language")), 
                                                "value"         => $form_data['ke_gudang_id'],
                                            );
                                            echo form_input($ke_gudang_id);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12"><?=translate("Tanggal Permintaan :", $this->session->userdata("language"))?></label>     
                                    <div class="col-md-12">
                                        <label class="control-label "><?=date('d M Y H:i:s', strtotime($form_data['tanggal']))?></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12"><?=translate("Tanggal Transfer", $this->session->userdata("language"))?> :</label>
                                    <div class="col-md-12">
                                        <?php
                                            $tanggal = array(
                                                "id"            => "tanggal_transfer",
                                                "name"          => "tanggal_transfer",
                                                "autofocus"         => true,
                                                "class"         => "form-control date-picker",
                                                "placeholder"   => translate("Tanggal Transfer", $this->session->userdata("language"))
                                            );
                                            echo form_input($tanggal);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12"><?=translate("No. Surat Jalan", $this->session->userdata("language"))?> :</label>
                                    <div class="col-md-12">
                                        <?php
                                            $no_surat_jalan = array(
                                                "id"            => "no_surat_jalan",
                                                "name"          => "no_surat_jalan",
                                                "autofocus"         => true,
                                                "class"         => "form-control",
                                                "placeholder"   => translate("No. Surat Jalan", $this->session->userdata("language"))
                                            );
                                            echo form_input($no_surat_jalan);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>     
                                    <div class="col-md-12">
                                        <textarea id="nomer_{0}" name="keterangan" class="form-control" rows="8" placeholder="Keterangan"></textarea>
                                        <!-- <label class="control-label"><?=$form_data['keterangan']?></label> -->
                                    </div>
                                </div>
                             
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <?=translate("Detail Item", $this->session->userdata("language"))?>
                                </div>
                            </div>
                            <?php
                            //  $btn_search   = '<div class="text-center"><button title="Search Account" class="btn btn-xs btn-success search-account"><i class="fa fa-search"></i></button></div>';
                                $btn_search         = '<div class="text-center"><button type="button" title="" class="btn  btn-primary search-account"><i class="fa fa-search"></i></button></div>';
                                $btn_search_titipan = '<div class="text-center"><button type="button" title="" class="btn  btn-success search-account-titipan"><i class="fa fa-search"></i></button></div>';
                                $btn_plus           = '<div class="text-center"><button title="Add Row" class="btn  btn-success add_row"><i class="fa fa-plus"></i></button></div>';
                                $btn_del            = '<div class="text-center"><button class="btn  red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
                                $btn_del_plus       = '<div class="text-center"><button class="btn  red del-this-plus" title="Delete"><i class="fa fa-times"></i></button></div>';
                                $btn_stock         = '<button type="button" data-row="{0}" name="items[{0}][stock]" title="Semua stock" class="btn  green-haze stock"><i class="fa fa-check"></i> '.translate("Semua Stock", $this->session->userdata("language")).'</button>';
                                $btn_permintaan         = '<button type="button" data-row="{0}" name="items[{0}][permintaan] title="Semua permintaan" class="btn  green-haze permintaan"><i class="fa fa-check"></i> '.translate("Semua Permintaan", $this->session->userdata("language")).'</button>';
        // '.$row['id'].'
        // <a title="'.translate('Unggah Gambar', $this->session->userdata('language')).'" href="'.base_url().'pembelian/permintaan_po/unggah_gambar/" data-toggle="modal" data-target="#popup_modal" class="btn btn-xs green-haze"><i class="fa fa-image"></i></a>         
                                $attrs_item_id = array(
                                    'id'          => 'items_item_id_{0}',
                                    'name'        => 'items[{0}][item_id]',
                                    'class'       => 'form-control input-xs hidden',
                                );

                                $attrs_pmb_id = array(
                                    'id'          => 'items_pmb_id_{0}',
                                    'name'        => 'items[{0}][pmb_id]',
                                    'class'       => 'form-control input-xs hidden',
                                );

                                $attrs_gudang_id = array(
                                    'id'          => 'items_gudang_id_{0}',
                                    'name'        => 'items[{0}][gudang_id]',
                                    'class'       => 'form-control input-xs hidden',
                                );

                                $attrs_harga_beli = array(
                                    'id'          => 'items_harga_beli_{0}',
                                    'name'        => 'items[{0}][harga_beli]',
                                    'class'       => 'form-control input-xs hidden',
                                );

                                $attrs_item_satuan_id = array(
                                    'id'          => 'items_item_satuan_id_{0}',
                                    'name'        => 'items[{0}][item_satuan_id]',
                                    'class'       => 'form-control input-xs hidden',
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

                                $attrs_item_stock = array(
                                    'id'          => 'items_stock_{0}',
                                    'name'        => 'items[{0}][stock]',
                                    'class'       => 'form-input input-xs hidden',
                                    // 'readonly'    => 'readonly',
                                    // 'style'       => 'width:180px;',
                                );

                                $attrs_item_permintaan = array(
                                    'id'          => 'items_permintaan_{0}',
                                    'name'        => 'items[{0}][permintaan]',
                                    'class'       => 'form-input input-xs hidden',
                                    // 'readonly'    => 'readonly',
                                    // 'style'       => 'width:180px;',
                                );

                                // $records = $this->transfer_item_m->get_data_item_terdaftar($form_data[0]['order_permintaan_pembelian_id'], $form_data[0]['user_level_id'])->result_array();
                                $records = $this->request_item_m->get_request_item($form_data['id'])->result_array();
                                // $records = object_to_array($records);
                                // die_dump($this->db->last_query());
                                // die_dump($records);
                                
                                $i = 0;
                                foreach ($records as $key=>$data) {

                                    // die_dump($data);
                                    // $attrs_persetujuan_permintaan_pembelian_id['value'] = $data['persetujuan_permintaan_pembelian_id'];
                                    // $attrs_order_permintaan_pembelian_detail_id['value'] = $data['order_permintaan_pembelian_detail_id'];
                                    $attrs_item_id['value'] = $data['item_id'];
                                    $attrs_item_satuan_id['value'] = $data['item_satuan_id'];
                                    $attrs_item_kode['value'] = $data['item_kode'];
                                    $attrs_item_nama['value'] = $data['item_nama'];
                                    $attrs_item_stock['value'] = $data['stock'];
                                    $attrs_item_permintaan['value'] = $data['jumlah_permintaan'];
                                    $attrs_pmb_id['value'] = $data['pmb_id'];
                                    $attrs_gudang_id['value'] = $data['gudang_id'];
                                    $attrs_harga_beli['value'] = $data['harga_beli'];
                                    // $attrs_item_jumlah_item['value'] = $data['jumlah'];
                                    // $attrs_item_jumlah['value'] = $data['jumlah_setujui'];
                                    // $attrs_item_satuan['value'] = $data['nama_satuan_order'];
                                    // $attrs_item_satuan_persetujuan['value'] = $data['satuan_id'];

                                    // $satuan = $this->persetujuan_permintaan_pembelian_m->get_satuan($data['item_id'])->result_array();
                                    // // die_dump($this->db->last_query());
                                    // // die_dump($satuan);
                                    // $satuan_option = array();

                                    // foreach ($satuan as $sub_satuan) {

                                    //     // die_dump($sub_satuan);
                                    //     $satuan_option[$sub_satuan['id']] = $sub_satuan['nama'];

                                    // }

                                    if($data['is_identitas'] == 1)
                                    {
                                        // item row column
                                        $item_cols = array(// style="width:156px;
                                            'item_kode'              => form_input($attrs_item_id).form_input($attrs_item_satuan_id).'<label class="control-label" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;">'.$data['item_kode'].'</label>',
                                            'item_nama'              => form_input($attrs_harga_beli).form_input($attrs_gudang_id).form_input($attrs_pmb_id).'<label cass="control-label" name="items[{0}][item_nama]" style="text-align : left !important; width : 150px !important;">'.$data['item_nama'].'</label>',
                                            'item_stok'              => form_input($attrs_item_stock).'<div class="text-center"><label class="control-label" name="items[{0}][stock]">'.$data['stock'].'</label></div>',
                                            'item_jumlah_permintaan' => form_input($attrs_item_permintaan).'<div class="text-center"><label class="control-label" name="items[{0}][jumlah_permintaan]">'.$data['jumlah_permintaan'].' '.$data['item_satuan'].'</label></div>',
                                            'item_jumlah_kirim'      => '<div class="input-group text-center" style="width: 100%;">
                                                                        <label cass="control-label" id="items_jumlah_kirim_{0}" name="items[{0}][jumlah_kirim]" style="text-align : center !important; width : 150px !important;"></label>
                                                                        <span class="input-group-btn"><a class="btn btn-primary identitas hidden" id="info_identitas_{0}" name="info" data-toggle="modal" data-target="#popup_modal_identitas" href="'.base_url().'gudang/transfer_item/modal_identitas_proses_permintaan/'.$data['item_id'].'/'.$data['item_satuan_id'].'/item_row_{0}" ><i class="fa fa-info"></i></a>
                                                                        <a class="btn btn-primary check-identitas" data-row-check="{0}" data-confirm="'.translate("Apakah anda ingin mengganti identitas sebelumnya ?", $this->session->userdata("language")).'"><i class="fa fa-info"></i></a>
                                                                        </div>',
                                            'semua_stok'             => '<div class="inline-button-table">'.$btn_stock.'<div id="simpan_identitas" hidden ></div>'.$btn_permintaan.'</div>',
                                        );
                                    
                                    } else {

                                        // item row column
                                        $item_cols = array(// style="width:156px;
                                            'item_kode'              => form_input($attrs_item_id).form_input($attrs_item_satuan_id).'<label class="control-label" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;">'.$data['item_kode'].'</label>',
                                            'item_nama'              => form_input($attrs_harga_beli).form_input($attrs_gudang_id).form_input($attrs_pmb_id).'<label cass="control-label" name="items[{0}][item_nama]" style="text-align : left !important; width : 150px !important;">'.$data['item_nama'].'</label>',
                                            'item_stok'              => form_input($attrs_item_stock).'<div class="text-center"><label class="control-label" name="items[{0}][stock]">'.$data['stock'].'</label></div>',
                                            'item_jumlah_permintaan' => form_input($attrs_item_permintaan).'<div class="text-center"><label class="control-label" name="items[{0}][jumlah_permintaan]">'.$data['jumlah_permintaan'].' '.$data['item_satuan'].'</label></div>',
                                            'item_jumlah_kirim'      => form_input($attrs_item_jumlah_kirim).'<div class="text-center"></div>',
                                            'semua_stok'             => '<div class="inline-button-table">'.$btn_stock.$btn_permintaan.'</div>',
                                        );

                                    }

                                    $item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
                                    $items_rows[] = str_replace('{0}', "{$key}", $item_row_template );

                                $i++;                           
                                }


                                
                                
                            ?>

           
                            <div class="portlet-body">
                                <!-- <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span> -->
                                <!-- <span id="tpl_item_acc_row" class="hidden"><?=htmlentities($item_row_template_acc)?></span> -->
                                <div class="table-responsive">
                                    <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_item_terdaftar">
                                        <thead>
                                            <tr>
                                                <!-- <th width="10%"><div class="text-center"><?=translate("id", $this->session->userdata('language'))?></div></th> -->
                                                <th width="10%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
                                                <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
                                                <th width="10%"><div class="text-center"><?=translate("Stok", $this->session->userdata('language'))?></div></th>
                                                <th width="15%"><div class="text-center"><?=translate("Jumlah Permintaan", $this->session->userdata('language'))?></div></th>
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
            </div>
        </div>           
    </div>
</div>

                            
<?=form_close();?>


<div class="modal fade bs-modal-lg" id="popup_modal_proses" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-md" style="width: 480px !important;">
       <div class="modal-content">

       </div>
   </div>
</div>

<div class="modal fade bs-modal-lg" id="popup_modal_identitas" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" style="width:1060px;" >
       <div class="modal-content">

       </div>
   </div>
</div>