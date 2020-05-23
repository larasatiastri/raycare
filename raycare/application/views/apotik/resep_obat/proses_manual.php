<?php

    $form_attr = array(
        "id"            => "form_proses_manual", 
        "name"          => "form_proses_manual", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );

    $hidden = array(
        "command"   => "proses_manual"
    );


    echo form_open(base_url()."apotik/resep_obat/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');

    $pasien = $this->pasien_m->get_by(array('id' => $form_data['pasien_id']), true);
    $pasien = object_to_array($pasien); 

    $dokter = $this->user_m->get_by(array('id' => $form_data['dokter_id']), true);
    $dokter = object_to_array($dokter);

    $msg_save = translate('Anda yakin untuk memproses resep ini?', $this->session->userdata('language'));
    $cabang = $this->cabang_m->get($form_data['cabang_id']);

?>  
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-plus font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses Resep Obat", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions">
            <a class="btn btn-default btn-circle" href="<?=base_url()?>apotik/resep_obat"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
            <a class="btn btn-primary btn-circle hidden" id="confirm_save_draf" data-confirm="<?=$msg_save?>"><i class="fa fa-file-text-o"></i> <?=translate("Simpan ke Draf", $this->session->userdata("language"))?></a>
            <a class="btn btn-primary btn-circle" id="confirm_save" data-confirm="<?=$msg_save?>"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
            <button class="btn hidden" id="save"></button>
        </div>

    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <input type="hidden" class="form-control" value="<?=$form_data['cabang_id']?>" id="cabang_id" name="cabang_id">
            <input type="hidden" class="form-control" value="<?=$pk_value?>" id="tindakan_resep_obat_id" name="tindakan_resep_obat_id">
            <input type="hidden" class="form-control" value="<?=$pasien['id']?>" id="pasien_id" name="pasien_id">
            <div class="row">
                <div class="col-md-3">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <?=translate("Informasi", $this->session->userdata("language"))?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Cabang", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <label class=""><b> <?=$cabang->nama?></b> </label>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <div class="form-group">
                                <label class="col-md-12"><?=translate("No. Resep", $this->session->userdata("language"))?> :</label>
                                <div class="col-md-12">
                                    <label class=""><b> <?=$form_data['nomor_resep']?></b> </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
                                <div class="col-md-12">
                                    <label class=""><b> <?=$pasien['nama'].' ['.$data_bed['kode'].']'?></b> </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
                                <div class="col-md-12">
                                    <label class=""><b> <?=$dokter['nama']?></b> </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12"><?=translate("Tanggal Resep", $this->session->userdata("language"))?> :</label>
                                <div class="col-md-12">
                                    <label class=""><b> <?=date('d M Y', strtotime($form_data['created_date']))?></b> </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9"> 
                <?php
                    //  $btn_search   = '<div class="text-center"><button title="Search Account" class="btn btn-xs btn-success search-account"><i class="fa fa-search"></i></button></div>';
                        $btn_search         = '<div class="text-center"><button type="button" title="" class="btn  btn-primary search-item"><i class="fa fa-search"></i></button></div>';
                        $btn_del            = '<div class="text-center"><button class="btn red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
                       

                        $attrs_item_kode = array(
                            'id'          => 'items_item_kode_{0}',
                            'name'        => 'items[{0}][item_kode]',
                            'class'       => 'form-control text-center hidden',
                            'readonly'    => 'readonly'
                        );
                        
                        $attrs_item_jumlah = array(
                            'id'          => 'items_jumlah_{0}',
                            'name'        => 'items[{0}][jumlah]',
                            'class'       => 'form-control text-center',
                            'value'       =>  0,
                            'type'        => 'number'
                        );

                        $attrs_item_jumlah_kirim = array(
                            'id'          => 'items_jumlah_kirim_{0}',
                            'name'        => 'items[{0}][jumlah_kirim]',
                            'class'       => 'form-control text-center',
                            'type'        => 'number',
                            'value'       =>  0,
                        );

                        $attrs_item_satuan_id = array(
                            'id'          => 'items_id_satuan_{0}',
                            'name'        => 'items[{0}][id_satuan]',
                            'class'       => 'form-control hidden'
                        );

                        $attrs_item_gudang_id = array(
                            'id'          => 'items_gudang_id_{0}',
                            'name'        => 'items[{0}][gudang_id]',
                            'class'       => 'form-control hidden'
                        );

                        $attrs_item_bn = array(
                            'id'          => 'items_bn_{0}',
                            'name'        => 'items[{0}][bn]',
                            'class'       => 'form-control hidden'
                        );

                        $attrs_item_ed = array(
                            'id'          => 'items_ed_{0}',
                            'name'        => 'items[{0}][ed]',
                            'class'       => 'form-control hidden'
                        );

                        $attrs_item_dosis = array(
                            'id'          => 'items_dosis_{0}',
                            'name'        => 'items[{0}][dosis]',
                            'class'       => 'form-control'
                        );

                        $resep_option = array(
                            ''  => translate('Pilih', $this->session->userdata('language'))
                        );

                        foreach($form_data_item as $data_item){

                            $resep_option[$data_item['id']] = $data_item['keterangan'];
                        }

                        $item_cols = array(
                            'item_kode'         => '<div class="input-group text-right" style="width: 100%;">
                                                    <input class="form-control text-center" id="items_item_kode_{0}" name="items[{0}][item_kode]" readonly></input>
                                                    <input class="form-control text-center hidden" id="items_item_id_{0}" name="items[{0}][item_id]"></input>
                                                    <span class="input-group-btn"><button type="button" title="" class="btn  btn-primary search-item"><i class="fa fa-search"></i></button>
                                                    </div>',
                            'item_nama'         => '<label class="control-label" name="items[{0}][item_nama]"></label>',
                            'item_satuan'       => form_input($attrs_item_satuan_id).'<label class="control-label" name="items[{0}][item_satuan]"></label>',
                            'item_bn'           => form_input($attrs_item_bn).'<div class="text-left"><label class="control-label" name="items[{0}][item_bn]"></label></div>',
                            'item_ed'           => form_input($attrs_item_ed).'<div class="text-left"><label class="control-label" name="items[{0}][item_ed]"></label></div>',
                            'gudang'         => form_input($attrs_item_gudang_id).'<label class="control-label" name="items[{0}][gudang_nama]"></label>',
                            'item_jumlah_kirim' => form_input($attrs_item_jumlah_kirim),
                            'item_dosis'        => form_input($attrs_item_dosis),
                            'item_resep'        => form_dropdown('items[{0}][resep_id]', $resep_option, '', 'id="items_resep_{0}" class="form-control select-resep"'),
                            'aksi'              => $btn_del.'<div id="simpan_identitas" hidden ></div>',
                        );

                        $item_row_template =  '<tr id="item_row_{0}" class="row_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

                        
                    ?>
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <?=translate("Detail Item", $this->session->userdata("language"))?>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-condensed table-striped table-bordered table-hover">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="100%"><div class="text-center"><?=translate("Keterangan", $this->session->userdata('language'))?></div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($form_data_item as $data_item) :?>
                                        <tr>
                                            <td><?=$data_item['keterangan']?></td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                            <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
                            <div class="table-responsive">
                                <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_item">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="10%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
                                            <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("BN", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("ED", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("Gudang", $this->session->userdata('language'))?></div></th>
                                            <th width="5%" ><div class="text-center"><?=translate("Jml Kirim", $this->session->userdata('language'))?></div></th>
                                            <th width="5%" ><div class="text-center"><?=translate("Dosis", $this->session->userdata('language'))?></div></th>
                                            <th width="20%" ><div class="text-center"><?=translate("Resep", $this->session->userdata('language'))?></div></th>
                                            <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
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
        
</div>

                            
<?=form_close();?>

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

<?php $this->load->view('apotik/transfer_item/pilih_item.php'); ?> 
