<?php

    $form_attr = array(
        "id"            => "form_tanda_terima_faktur", 
        "name"          => "form_tanda_terima_faktur", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );

    $hidden = array(
        "command"   => "verifikasi",
        "id"        => $pk_value
    );      

    echo form_open(base_url()."keuangan/tanda_terima_faktur_online/save", $form_attr, $hidden); 

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
    $total_invoice = 0;
    foreach ($form_data_detail as $detail_invoice) {
        $total_invoice = $total_invoice + $detail_invoice['nominal'];
        $item_cols_berkas = array(
            'tanggal' => '<div class="text-left inline-button-table">'.date('d M Y', strtotime($detail_invoice['tanggal'])).'</div>',
            'berkas_nomor' => '<div class="text-left">'.$detail_invoice['no_berkas'].'</div>',
            'iamges' => '<div class="text-left"><div id="upload">
                            <ul class="ul-img">
                                <li class="working">
                                    <div class="thumbnail">
                                        <a class="fancybox-button" title="'.$detail_invoice['url_berkas'].'" href="'.config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.$form_data['id'].'/'.$detail_invoice['url_berkas'].'" data-rel="fancybox-button"><img src="'.config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.$form_data['id'].'/'.$detail_invoice['url_berkas'].'" alt="Smiley face" class="img-thumbnail"></a>
                                    </div>
                                </li>
                            </ul>
                        </div></div>'
        );
        if($data_supplier->is_pkp == 1){
            $item_cols_berkas['no_faktur_pjk'] = '<div class="text-left">'.$detail_invoice['no_faktur_pajak'].'</div>';
            $item_cols_berkas['faktur_pjk'] = '<div class="text-left"><div id="upload">
                            <ul class="ul-img">
                                <li class="working">
                                    <div class="thumbnail">
                                        <a class="fancybox-button" title="'.$detail_invoice['url_faktur_pajak'].'" href="'.config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.$form_data['id'].'/'.$detail_invoice['url_faktur_pajak'].'" data-rel="fancybox-button"><img src="'.config_item('base_dir').'cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/'.$form_data['id'].'/'.$detail_invoice['url_faktur_pajak'].'" alt="Smiley face" class="img-thumbnail"></a>
                                    </div>
                                </li>
                            </ul>
                        </div></div>';
        }
            
        $item_cols_berkas['keterangan'] = '<div class="text-left">'.$detail_invoice['keterangan'].'</div>';
        $item_cols_berkas['nilai']      = '<div class="text-right">'.formatrupiah($detail_invoice['nominal']).'</div>';
        

        $item_row_template_berkas .=  '<tr id="item_row_berkas_{0}" style="vertical-align:top;" ><td >' . implode('</td><td>', $item_cols_berkas) . '</td></tr>';
        # code...
    }

    if($form_data_detail_po != '')
    {
        $i = 0;
        $item_row_template_db = '';
        $total = 0;
        $diskon = 0;
        $pph = 0;
        $grand_total = 0;
        foreach ($form_data_detail_po as $detail) 
        {
            $btn_del_db = '<div class="text-center"><button class="btn btn-sm red-intense del-this-db" data-confirm="'.translate('Anda yakin akan menghapus item ini?', $this->session->userdata('language')).'" data-index="'.$i.'" data-id="'.$detail['id'].'" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

            $data_satuan = $this->item_satuan_m->get_by(array('item_id' => $detail['item_id']));

            $style = '';

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
                'class'       => 'form-control hidden',
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
                'class'       => 'form-control hidden',
                'readonly'    => 'readonly',
                'value' => $detail['harga_beli'],
            );

            $attrs_item_diskon_db = array(
                'id'          => 'items_diskon_'.$i,
                'type'      => 'hidden',
                'name'        => 'items['.$i.'][item_diskon]',
                'class'       => 'form-control',
                'value' => $detail['diskon'],
                
            );

            $attrs_stok_db = array(
                'id'    => 'items_stok_'.$i,
                'name'  => 'items['.$i.'][stok]', 
                'type'  => 'number',
                'min'   => 0,
                'class' => 'form-control text-right',
                'style' => 'width:80px;',
                'value' => 1,
                'value' => $detail['item_id'],
            );

            $attrs_jumlah_db = array(
                'id'    => 'items_jumlah_'.$i,
                'name'  => 'items['.$i.'][jumlah]', 
                'type'  => 'number',
                'min'   => 0,
                'max'   => $detail['jumlah_disetujui'],
                'class' => 'form-control text-right',
                'value' => $detail['jumlah_pesan'],
            );
            $attrs_jumlah_setuju_db = array(
                'id'    => 'items_jumlah_'.$i,
                'name'  => 'items['.$i.'][jumlah_setujui]', 
                'type'  => 'hidden',
                'min'   => 0,
                'class' => 'form-control text-right',
                'value' => $detail['jumlah_disetujui'],
            );

            $attrs_item_total_db = array(
                'id'          => 'items_total_'.$i,
                'name'        => 'items['.$i.'][item_total]',
                'class'       => 'form-control sub_total hidden',
                'readonly'    => 'readonly',
                'value' => ($detail['jumlah_disetujui']*$detail['harga_beli']) - (($detail['diskon']/100)*$detail['harga_beli']),

            );

            $attrs_item_is_active_db = array(
                'id'          => 'items_is_active_'.$i,
                'name'        => 'items['.$i.'][is_active]',
                'class'       => 'form-control hidden',
                'readonly'    => 'readonly',
                'value' => 1,

            );

            $attrs_item_satuan_db = array(
                'id'          => 'items_satuan_'.$i,
                'name'        => 'items['.$i.'][item_satuan]',
                'class'       => 'form-control hidden',
                'readonly'    => 'readonly',
                'value' => $detail['item_satuan_id'],
            );

            $satuan_option = array(
                '' => 'Pilih..'
            );

            foreach ($data_satuan as $satuan) {
                $satuan_option[$satuan->id] = $satuan->nama;
            }

            $total = $total + (($detail['harga_beli'] - (($detail['diskon']/100)*$detail['harga_beli'])) * $detail['jumlah_pesan']);

            $satuan_item = $this->item_satuan_m->get($detail['item_satuan_id']);

            $item_cols_db = array(// style="width:156px;
                'item_kode'          => '<label class="control-label text-left" name="items['.$i.'][item_kode]">'.$detail['kode'].'</label>'.form_input($attrs_item_id_db).form_input($attrs_item_kode_db),
                'item_name'          => '<label class="control-label" name="items['.$i.'][item_nama]">'.$detail['nama'].'</label>'.form_input($attrs_item_nama_db).form_input($attrs_id_db),
                'item_tgl_kirim'     => '<div class="text-center"><label class="control-label" name="items['.$i.'][tgl_kirim]">'.date('d M Y', strtotime($detail['tanggal_kirim'])).' </label></div>',
                'item_harga'         => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_harga]">'.formatrupiah($detail['harga_beli']).'</label></div>'.form_input($attrs_item_harga_db),
                'item_diskon'        => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_diskon]">'.$detail['diskon'].' %</label></div>'.form_input($attrs_item_diskon_db),
                'item_jumlah_setujui'        => '<label class="control-label inline-button-table" name="items['.$i.'][jumlah_setujui]">'.$detail['jumlah_disetujui'].' '.$satuan_item->nama.' </label>'.form_input($attrs_jumlah_setuju_db).form_input($attrs_item_satuan_db).form_input($attrs_item_is_active_db),
                'item_total'         => '<div class="text-right"><label class="control-label" name="items['.$i.'][item_sub_total]">'.formatrupiah(($detail['harga_beli'] - (($detail['diskon']/100)*$detail['harga_beli'])) * $detail['jumlah_pesan']).'</label>'.form_input($attrs_item_total_db).'</div>',
            );

            $item_row_template_db .=  '<tr id="item_row_'.$i.'" class="table_item_beli" '.$style.'><td>' . implode('</td><td>', $item_cols_db) . '</td></tr>';

            $i++;
        }
    } 
    else
    {
        $i = 0;
        $item_row_template_db = '';
        $total = 0;
        $diskon = 0;
        $pph = 0;
        $grand_total = 0;
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
                $confirm_save   = translate('Anda yakin akan menyetujui proses Tukar Faktur Ini ?',$this->session->userdata('language'));
                $submit_text    = translate('Verifikasi', $this->session->userdata('language'));
                $reset_text     = translate('Reset', $this->session->userdata('language'));
                $tolak_text     = translate('Tolak', $this->session->userdata('language'));
            ?>
                
            
            <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
                <i class="fa fa-chevron-left"></i>
            <?=$back_text?>
            </a>
            <a class="btn btn-circle btn-primary" id="confirm_save" data-confirm="<?=$confirm_save?>">
                <i class="fa fa-check"></i>
            <?=$submit_text?>
            </a>
            <a class="btn btn-circle btn-danger" id="btn_tolak" href="<?=base_url()?>keuangan/tanda_terima_faktur_online/reject_proses/<?=$pk_value?>" data-toggle="modal" data-target="#popup_modal_proses">
                <i class="fa fa-times"></i>
            <?=$tolak_text?>
            </a>
            <button type="submit" id="save" class="btn btn-primary hidden" >
            <?=$submit_text?>
            </button> 

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
                            <label class="col-md-12 bold"><?=translate("Tanggal Jatuh Tempo", $this->session->userdata("language"))?> :</label>              
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
                            <span class="caption-subject"><?=translate("Informasi PO", $this->session->userdata("language"))?></span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Tanggal Pesan", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=date('d M Y', strtotime($form_data_po[0]['tanggal_pesan']))?></label>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=date('d M Y', strtotime($form_data_po[0]['tanggal_kadaluarsa']))?></label>
                                    </div>
                                        
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Garansi", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=($form_data_po[0]['tanggal_kirim'] != '1970-01-01')?date('d M Y', strtotime($form_data_po[0]['tanggal_kirim'])):'-'?></label>
                                        

                                    </div>
                                    <?php
                                        $lama_tempo = ($tipe_bayar[0]['lama_tempo'] != '')?$tipe_bayar[0]['lama_tempo'].' Hari':'';
                                    ?>
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate('Tipe Pembayaran', $this->session->userdata('language'))?> :</label>
                                        <label class="col-md-12 bold"><?=$tipe_bayar[0]['nama'].' '.$lama_tempo?></label>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=($form_data_po[0]['keterangan'] != '')?$form_data_po[0]['keterangan']:'-'?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
                                        <?php
                                            $tipe = 'Dalam Negeri';
                                            if($form_data_po[0]['tipe_supplier'] == 2)
                                            {
                                                $tipe = 'Luar Negeri';
                                            }
                                        ?>
                                        <label class="col-md-12 bold"><?=$tipe?></label>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Supplier", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=$form_data_po[0]['nama'].' ['.$form_data_po[0]['kode'].']'?> </label>
                                        <input class="form-control" type="hidden" id="supplier_id" name="supplier_id" value="<?=$form_data_po[0]['id']?>"></input>
                                        
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Kontak", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=$form_data_po[0]['no_telp']?> </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=$form_data_po[0]['alamat'].', '.$form_data_po[0]['rt_rw'].', '.$form_data_po[0]['kelurahan'].', '.$form_data_po[0]['kecamatan'].', '.$form_data_po[0]['kota'].', '.$form_data_po[0]['propinsi'].', Indonesia'?> </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Email", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=$email_supp?></label>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
                                        <?php
                                            $tipe_customer = 'Internal';
                                            if($form_data_po[0]['tipe_customer'] == 2)
                                            {
                                                $tipe_customer = 'Eksternal';
                                            }
                                        ?>
                                        <label class="col-md-12 bold"><?=$tipe_customer?></label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Ditujukan ke", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=$data_customer->nama?></label>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Kontak", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=$data_customer->penanggung_jawab?></label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=$customer_alamat[0]['alamat'].', '.$customer_alamat[0]['nama_kelurahan'].', '.$customer_alamat[0]['kecamatan'].','.$customer_alamat[0]['kabkot'].', '.$customer_alamat[0]['propinsi']?></label>
                                    
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12"><?=translate("Email", $this->session->userdata("language"))?> :</label>
                                        <label class="col-md-12 bold"><?=$customer_email->url?></label>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <span class="caption-subject"><?=translate("Detail Item", $this->session->userdata("language"))?></span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-hover" id="table_detail_pembelian">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="5%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
                                                    <th class="text-center" ><?=translate("Nama", $this->session->userdata("language"))?> </th>
                                                    <th class="text-center" width="9%"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?> </th>
                                                    <th class="text-center"style="width : 120px !important;"><?=translate("Harga", $this->session->userdata("language"))?> </th>
                                                    <th class="text-center" width="3%"><?=translate("Disc", $this->session->userdata("language"))?> </th>
                                                    <th class="text-center" width="5%"><?=translate("Jml", $this->session->userdata("language"))?> </th>
                                                    <th class="text-center" width="10%"><?=translate("Sub Total", $this->session->userdata("language"))?> </th>
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
                                            <?php
                                                $diskon = ($form_data_po[0]['diskon']/100)*$total;
                                                $tad = $total - $diskon;
                                                $pph = ($form_data_po[0]['pph']/100)*$tad;

                                                $grand_total = ($tad + $pph) ;
                                            ?>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6" class="text-right bold">Total</td>
                                                    <td><div class="text-right bold" id="total"><?=formatrupiah($total)?></div></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right bold">Diskon(%)</td>
                                                    <td class="text-right"><?=$form_data_po[0]['diskon']?> %</td>
                                                    <td class="text-right"><?=formatrupiah(($form_data_po[0]['diskon']/100)*$total)?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right bold">PPN(%)</td>
                                                    <td class="text-right"><?=$form_data_po[0]['pph']?> %</td>
                                                    <td class="text-right"><?=formatrupiah($pph)?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-right bold">Grand Total PO</td>
                                                    <td class="text-right bold" id="grand_tot"><?=formatrupiah($grand_total)?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right bold">DP(%)</td>
                                                    <td class="text-right"><?=$form_data_po[0]['dp']?> %</td>
                                                    <td class="text-right"><?=formatrupiah(($form_data_po[0]['dp']/100)*$total)?></td>
                                                </tr>
                                                <tr class="hidden">
                                                    <td colspan="6" class="text-right bold">Sisa Bayar</td>
                                                    <td class="text-right bold"><?=formatrupiah($form_data_po[0]['sisa_bayar'])?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-right bold">Biaya Tambahan</td>
                                                    
                                                    <?php
                                                        $biaya_tambahan = ($form_data_po[0]['biaya_tambahan'] == 0)?formatrupiah($form_data_po[0]['biaya_tambahan']):'<a href="'.base_url().'keuangan/proses_pembayaran_transaksi/view_biaya/'.$pk_value.'" data-target="#modal_biaya" data-toggle="modal" id="biaya_tambahan_po">'.formatrupiah($form_data_po[0]['biaya_tambahan']).'</a>';
                                                    ?>
                                                    <td class="text-right" id="biaya_tambahan_po"><?=$biaya_tambahan?></a></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-right bold">Grand Total Setelah Biaya</td>
                                                    <td class="text-right bold" id="grand_tot_biaya"><?=formatrupiah($grand_total + $form_data_po[0]['biaya_tambahan'])?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right bold">TOTAL INVOICE</td>
                                                    <td colspan="2" class="text-right bold" id="label_total_invoice"><?=formatrupiah($total_invoice)?></td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <input class="form-control hidden" readonly value="<?=$total?>" id="tot_hidden" name="tot_hidden">
                                        <input class="form-control hidden" id="ppn_hidden" name="ppn_hidden" value="<?=$form_data_po[0]['pph']?>">
                                        <input class="form-control text-right hidden" id="disk_hidden" name="disk_hidden" value="<?=($form_data_po[0]['diskon'] / 100) * $total?>">
                                        
                                        <input class="form-control hidden" id="biaya_tambah_hidden" name="biaya_tambah_hidden" value="<?=$form_data_po[0]['biaya_tambahan']?>">

                                        <input class="form-control hidden" id="grand_tot_hidden" name="grand_tot_hidden" value="<?=$grand_total?>">
                                        <input class="form-control hidden" id="grand_tot_biaya_hidden" name="grand_tot_biaya_hidden" value="<?=$grand_total + $form_data_po[0]['biaya_tambahan']?>">
                                        <input class="form-control hidden" id="depe" name="depe" value="<?=$form_data_po[0]['dp']?>">
                                        <input class="form-control hidden" id="sisa_nya" name="sisa_nya" value="<?=$grand_total-$form_data_po[0]['dp']?>">
                                        <input class="form-control hidden" id="total_invoice" name="total_invoice" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12">
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
                                        <?=formatrupiah($total_invoice)?>
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

<div class="modal fade bs-modal-lg" id="popup_modal_proses" role="basic" aria-hidden="true">
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