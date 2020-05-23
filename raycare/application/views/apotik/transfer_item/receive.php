<?php

    //////////////////////////////////////////////////////////////////////////////////////
   
    $form_attr = array(
        "id"            => "form_receive_transfer_item", 
        "name"          => "form_receive_transfer_item", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );

    $hidden = array(
        "command"   => "receive"
    );


    echo form_open(base_url()."apotik/transfer_item/receive_terima_item", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');

    $user = $this->user_m->get($form_data['created_by']);

    $items_rows = $this->transfer_item_detail_m->get_data($pk_value)->result_array();
    $items_rows = object_to_array($items_rows);


?>  
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Penerimaan", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions">
            <?php

                $back_text    = translate('Kembali', $this->session->userdata('language'));
                $confirm_save = translate('Anda yakin akan menerima item ini ?',$this->session->userdata('language'));
                $submit_text  = translate('Receive', $this->session->userdata('language'));
                $reject_text  = translate('Reject', $this->session->userdata('language'));

            ?>
                
            <a class="btn btn-circle default" href="javascript:history.go(-1)">
                <i class="fa fa-chevron-left"></i>
                <?=$back_text?>
            </a>
            <button type="button" id="reject" class="btn btn-primary hidden" >
                <?=$reject_text?>
            </button>
            <a id="confirm_reject" class="btn btn-circle red" href="<?=base_url()?>apotik/transfer_item/reject_terima/<?=$pk_value?>" data-confirm="<?=$confirm_save?>" data-toggle="modal" data-target="#popup_modal_receive">
                <i class="fa fa-times"></i>
                <?=$reject_text?>
            </a>
            <button type="submit" id="save_receive" class="btn btn-primary hidden" >
                <?=$submit_text?>
            </button>
            <a id="confirm_save_receive" class="btn btn-circle btn-primary" data-confirm="<?=$confirm_save?>" data-toggle="modal">
                <i class="fa fa-check"></i>
                <?=$submit_text?>
            </a>
        
        </div>
    </div>
    <div class="portlet-body form">
        <div class="portlet-body form"> 
            <div class="form-wizard">
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

                        <div class="form-group hidden">
                            <label class="col-md-12"><?=translate("Kode", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-4">
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
                            <label class="col-md-12"><?=translate("No. Transfer :", $this->session->userdata("language"))?></label>     
                            <div class="col-md-12">
                                <label><?=$form_data['no_transfer']?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("No. Surat Jalan :", $this->session->userdata("language"))?></label>     
                            <div class="col-md-12">
                                <label><?=$form_data['no_surat_jalan']?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Transfer Dari :", $this->session->userdata("language"))?></label>     
                            <div class="col-md-12">
                                <?php $dari_gudang_id = $this->gudang_m->get_by(array('id' => $form_data['dari_gudang_id']), true)?>
                                <label><?=$dari_gudang_id->nama?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Transfer Ke :", $this->session->userdata("language"))?></label>  
                            <div class="col-md-12">
                                <?php $ke_gudang_id = $this->gudang_m->get_by(array('id' => $form_data['ke_gudang_id']), true)?>
                                <label><?=$ke_gudang_id->nama?></label>
                            </div>
                            <input id="cabang_id" name="cabang_id" value="<?=$ke_gudang_id->cabang_klinik?>" type="hidden"></input>   
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Tanggal Transfer :", $this->session->userdata("language"))?></label>     
                            <div class="col-md-12">
                                <label><?=date('d M Y', strtotime($form_data['tanggal']))?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("No. Surat Jalan :", $this->session->userdata("language"))?></label>     
                            <div class="col-md-12">
                                <label><?=$form_data['no_surat_jalan']?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>     
                            <div class="col-md-12">
                                <label><?=($form_data['keterangan'] != '' && $form_data['keterangan'] != NULL)?$form_data['keterangan']:'-'?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Dibuat Oleh :", $this->session->userdata("language"))?></label>     
                            <div class="col-md-12">
                                <label><?=$user->nama?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <span><?=translate("Item Dikirim", $this->session->userdata("language"))?></span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-responsive">
                                <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_item_terdaftar">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="10%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
                                            <th><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("Batch Number", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("Expire Date", $this->session->userdata('language'))?></div></th>
                                            <th width="10%"><div class="text-center"><?=translate("Jumlah", $this->session->userdata('language'))?></div></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <?php 
                                            $i = 0;
                                            foreach ($items_rows as $row):?>
                                            <tr>
                                                <td><div class="text-left"><input type="hidden" id="kode_<?=$i?>" name="items[<?=$i?>][kode]" value="<?=$row['item_kode']?>"><input type="hidden" id="item_id_<?=$i?>" name="items[<?=$i?>][item_id]" value="<?=$row['item_id']?>"><?=$row['item_kode']?></div></td>
                                                <td><div class="text-left"><input type="hidden" id="nama_<?=$i?>" name="items[<?=$i?>][nama]" value="<?=$row['item_nama']?>"><input type="hidden" id="id_<?=$i?>" name="items[<?=$i?>][id]" value="<?=$row['id']?>"><?=$row['item_nama']?></div></td>
                                                <td><div class="text-left"><input type="hidden" id="bn_sn_lot_<?=$i?>" name="items[<?=$i?>][bn_sn_lot]" value="<?=$row['bn_sn_lot']?>"><?=$row['bn_sn_lot']?></div></td>
                                                <td><div class="text-center"><input type="hidden" id="expire_date_<?=$i?>" name="items[<?=$i?>][expire_date]" value="<?=$row['expire_date']?>"><?=date('d M Y', strtotime($row['expire_date']))?></div></td>
                                                <td><div class="text-left"><input type="hidden" id="jumlah_kirim_<?=$i?>" name="items[<?=$i?>][jumlah_kirim]" value="<?=$row['jumlah_kirim']?>"><input type="hidden" id="item_satuan_id_<?=$i?>" name="items[<?=$i?>][item_satuan_id]" value="<?=$row['item_satuan_id']?>"><?=$row['jumlah_kirim'].' '.$row['nama_satuan']?></div></td>
                                            </tr>
                                        <?php 
                                            $i++;
                                        endforeach;?>
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

                            
<?=form_close();?>

<div class="modal fade bs-modal-lg" id="popup_modal_receive" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
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