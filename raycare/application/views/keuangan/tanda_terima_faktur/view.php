<?php

    $form_attr = array(
        "id"            => "form_tanda_terima_faktur", 
        "name"          => "form_tanda_terima_faktur", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );

    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."keuangan/tanda_terima_faktur/save", $form_attr, $hidden); 

    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');


    $btn_del_berkas = '<div class="text-center"><button class="btn red-intense del-this-berkas" title="Hapus Berkas"><i class="fa fa-times"></i></button></div>';

    $data_supplier = $this->supplier_m->get($form_data['supplier_id']);
    $user = $this->user_m->get($form_data['created_by']);
    $data_pembelian = $this->pembelian_m->get_by(array('id' => $form_data['pembelian_id']), true);
    $data_pembelian = object_to_array($data_pembelian);

    $item_row_template_berkas = '';
    $total = 0;
    foreach ($form_data_detail as $detail) {
        $total = $total + $detail['nominal'];
        $item_cols_berkas = array(
            'tanggal' => '<div class="text-left inline-button-table">'.date('d M Y', strtotime($detail['tanggal'])).'</div>',
            'berkas_nomor' => '<div class="text-left">'.$detail['no_berkas'].'</div>',
            'iamges' => '<div class="text-left"><div id="upload">
                            <ul class="ul-img">
                                <li class="working">
                                    <div class="thumbnail">
                                        <a class="fancybox-button" title="'.$detail['url_berkas'].'" href="'.config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.$form_data['id'].'/'.$detail['url_berkas'].'" data-rel="fancybox-button"><img src="'.config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.$form_data['id'].'/'.$detail['url_berkas'].'" alt="Smiley face" class="img-thumbnail"></a>
                                    </div>
                                </li>
                            </ul>
                        </div></div>'
        );
        if($data_supplier->is_pkp == 1){
            $item_cols_berkas['no_faktur_pjk'] = '<div class="text-left">'.$detail['no_faktur_pajak'].'</div>';
            $item_cols_berkas['faktur_pjk'] = '<div class="text-left"><div id="upload">
                            <ul class="ul-img">
                                <li class="working">
                                    <div class="thumbnail">
                                        <a class="fancybox-button" title="'.$detail['url_faktur_pajak'].'" href="'.config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.$form_data['id'].'/'.$detail['url_faktur_pajak'].'" data-rel="fancybox-button"><img src="'.config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.$form_data['id'].'/'.$detail['url_faktur_pajak'].'" alt="Smiley face" class="img-thumbnail"></a>
                                    </div>
                                </li>
                            </ul>
                        </div></div>';
        }
            
        $item_cols_berkas['keterangan'] = '<div class="text-left">'.$detail['keterangan'].'</div>';
        $item_cols_berkas['nilai']      = '<div class="text-right">'.formatrupiah($detail['nominal']).'</div>';
        

        $item_row_template_berkas .=  '<tr id="item_row_berkas_{0}" style="vertical-align:top;" ><td >' . implode('</td><td>', $item_cols_berkas) . '</td></tr>';
        # code...
    }


    


?>  
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-search font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tanda Terima Faktur ", $this->session->userdata("language")).$form_data['no_tanda_terima_faktur']?></span>
        </div>
        <div class="actions">
            <?php

                $back_text      = translate('Kembali', $this->session->userdata('language'));
                $confirm_save   = translate('Apa Kamu Yakin Akan Membuat Tanda Terima Faktur Ini ?',$this->session->userdata('language'));
                $submit_text    = translate('Simpan', $this->session->userdata('language'));
                $reset_text     = translate('Reset', $this->session->userdata('language'));
            ?>
                
            
            <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
                <i class="fa fa-chevron-left"></i>
            <?=$back_text?>
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
    <div class="row">
        <div class="col-md-3">
            <div class="portlet box blue-sharp">
                <div class="portlet-title" style="margin-bottom: 0px !important;">
                    <div class="caption">
                        <?=translate("Informasi", $this->session->userdata("language"))?>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Tanggal Faktur", $this->session->userdata("language"))?> :</label>              
                        <label class="col-md-12"><?=date('d M Y', strtotime($form_data['tanggal']))?></label>              
                        
                    </div>

                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
                        <label class="col-md-12"><?=$data_supplier->nama?></label>
                        
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Yang Menyerahkan", $this->session->userdata("language"))?> :</label>
                        <label class="col-md-12"><?=$form_data['diserahkan_oleh']?></label>  
                    </div>

                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("No. Telepon", $this->session->userdata("language"))?> :</label>
                        <label class="col-md-12"><?=$form_data['no_telepon']?></label>  
                    </div>
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Penerima", $this->session->userdata("language"))?> :</label>
                        <label class="col-md-12"><?=$user->nama?></label>  
                    </div>
                </div>
                
                
            </div>
            <div class="dashboard-stat blue-steel">
                <div class="visual">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="number" id="harga_po">
                         <?=formatrupiah($tat)?>
                    </div>
                    <div class="desc" id="no_po">
                         <?=$data_pembelian['no_pembelian']?>
                    </div>
                </div>
                <a class="more" target="_blank" href="<?=base_url()?>pembelian/history/view/<?=$form_data['pembelian_id']?>">
                View more <i class="m-icon-swapright m-icon-white"></i>
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="portlet box blue-sharp">
                <div class="portlet-title" style="margin-bottom: 0px !important;">
                    <div class="caption">
                        <?=translate("Daftar Faktur", $this->session->userdata("language"))?>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <span id="tpl_berkas_row" class="hidden"><?=htmlentities($item_row_template_berkas)?></span>
                        <table class="table table-condensed table-striped table-hover" id="table_berkas">
                            <thead>
                                <tr>
                                    <th width="5%"><div class="text-center"><?=translate("Tanggal", $this->session->userdata('language'))?></div></th>
                                    <th width="20%"><div class="text-center"><?=translate("Berkas No", $this->session->userdata('language'))?></div></th>
                                    <th width="20%"><div class="text-center"><?=translate("Invoice", $this->session->userdata('language'))?></div></th>
                                    <?php
                                        $cols = 4;
                                        if($data_supplier->is_pkp == 1){
                                            $cols = 6;
                                    ?>
                                        <th width="20%"><div class="text-center"><?=translate("No.Faktur Pajak", $this->session->userdata('language'))?></div></th>
                                        <th width="20%"><div class="text-center"><?=translate("Faktur Pajak", $this->session->userdata('language'))?></div></th>

                                    <?
                                        }
                                    ?>
                                    <th ><div class="text-center"><?=translate("Keterangan", $this->session->userdata('language'))?></div></th>
                                    <th width="20%"><div class="text-center"><?=translate("Nilai", $this->session->userdata('language'))?></div></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    echo $item_row_template_berkas;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="<?=$cols?>" class="text-right bold">Total</td>
                                    <td colspan="1">
                                        <div class="text-right">
                                        <?=formatrupiah($total)?>
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
    </div>
</div>

                            
<?=form_close();?>

<div class="modal fade bs-modal-lg" id="modal_po" role="basic" aria-hidden="true" >
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

<div id="popover_supplier_content" class="row" style="display:none;">
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
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

