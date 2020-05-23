<?php

    //////////////////////////////////////////////////////////////////////////////////////
   
    $get_gudang = $this->gudang_m->get_by(array('is_active' => 1 ));
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
        "id"            => "form_add_kirim_item", 
        "name"          => "form_add_kirim_item", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );

    $hidden = array(
        "command"   => "kirim"
    );


    echo form_open(base_url()."apotik/transfer_item/kirim_item", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');

    // die_dump($data_order);


?>  
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
        <i class="icon-plus font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Transfer Item", $this->session->userdata("language"))?></span>
        </div>

    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="portlet light bordered">
                        <div class="alert alert-danger display-hide">
                            <button class="close" data-close="alert"></button>
                            <?=$form_alert_danger?>
                        </div>
                        <div class="alert alert-success display-hide">
                            <button class="close" data-close="alert"></button>
                            <?=$form_alert_success?>
                        </div>
                        <div class="portlet-title">
                            <div class="caption">
                                <span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-2">
                            </div>
                            <div class="col-xs-2">
                                <?php
                                    $pk_value = array(
                                        "id"            => "pk_value",
                                        "name"          => "pk_value",
                                        "class"         => "form-control hidden", 
                                        "placeholder"   => translate("pk_value", $this->session->userdata("language")), 
                                        "value"         => $data_gudang_kirim[0]['id'],
                                    );
                                    echo form_input($pk_value);
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Transfer Dari :", $this->session->userdata("language"))?></label>     
                            <div class="col-md-12">
                            
                                
                                <label ><b><?=$data_gudang_kirim[0]['nama']?></b></label>
                            </div>  
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Transfer Ke :", $this->session->userdata("language"))?></label>     
                            <div class="col-md-12">
                                <?php
                                    echo form_dropdown('gudang_ke', $gudang_option, "", "id=\"gudang_ke\" class=\"form-control\" required"); 
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Tanggal Transfer", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <div class="input-group date">
                                    <input type="text" class="form-control" id="tanggal_transfer" name="tanggal_transfer" placeholder="Tanggal" value="<?=date('d M Y')?>"readonly >
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>     
                            <div class="col-md-12">
                                <textarea id="keterangan" name="keterangan" class="form-control" rows="6" placeholder="Keterangan"></textarea>
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

                        $attrs_item_is_identitas = array(
                            'id'          => 'items_is_identitas_{0}',
                            'name'        => 'items[{0}][is_identitas]',
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

                        if($flag == 1)
                        {

                            $item_cols = array(
                            'item_kode'         => '<div class="input-group text-right" style="width: 100%;">
                                                    <input class="form-control text-center" id="items_item_kode_{0}" name="items[{0}][item_kode]" readonly></input>
                                                    <input class="form-control text-center hidden" id="items_item_id_{0}" name="items[{0}][item_id]"></input>
                                                    <span class="input-group-btn"><button type="button" title="" class="btn  btn-primary search-item"><i class="fa fa-search"></i></button>
                                                    </div>',
                            'item_nama'         => '<label class="control-label" name="items[{0}][item_nama]"></label>',
                            'item_satuan'       => form_input($attrs_item_satuan_id).form_input($attrs_item_is_identitas).'<label class="control-label" name="items[{0}][item_satuan]"></label>',
                            'item_bn'           => form_input($attrs_item_bn).'<div class="text-left"><label class="control-label" name="items[{0}][item_bn]"></label></div>',
                            'item_ed'           => form_input($attrs_item_ed).'<div class="text-left"><label class="control-label" name="items[{0}][item_ed]"></label></div>',
                            'item_jumlah_kirim' => form_input($attrs_item_jumlah_kirim),
                            'aksi'              => $btn_del.'<div id="simpan_identitas" hidden ></div>',
                            );

                            $item_row_template =  '<tr id="item_row_{0}" class="row_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

                        }
                        
                    ?>
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <?=translate("Detail Item", $this->session->userdata("language"))?>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
                            <div class="table-responsive">
                                <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_transfer_item">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="18%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
                                            <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("BN", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("ED", $this->session->userdata('language'))?></div></th>
                                            <th width="5%" ><div class="text-center"><?=translate("Jml Kirim", $this->session->userdata('language'))?></div></th>
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
        <div class="form-actions right">
                <?php

                    $back_text      = translate('Kembali', $this->session->userdata('language'));
                    $confirm_save   = translate('Anda yakin akan melakukan transfer item ini?',$this->session->userdata('language'));
                    $submit_text    = translate('Simpan', $this->session->userdata('language'));
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
