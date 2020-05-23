<?php
    $form_attr = array(
        "id"            => "form_add_tindakan_vaksin", 
        "name"          => "form_add_tindakan_vaksin", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        "role"          => "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."tindakan/tindakan_vaksin/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
    
    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');
?>

<div class="form-body">
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-plus font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Tindakan Vaksin', $this->session->userdata('language'))?></span>
        </div>
        <?php $msg = translate("Apakah anda yakin akan membuat penjualan obat ini?",$this->session->userdata("language"));?>
        <div class="actions">   
            <a class="btn btn-circle btn-default" href="<?=base_url()?>tindakan/tindakan_vaksin/history"><i class="fa fa-undo"></i>  <?=translate("History", $this->session->userdata("language"))?></a>
            <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <?=translate('Informasi', $this->session->userdata('language'))?>
                    </div>
                </div>
                <div class="portlet-body form">
                <div class="form-body" id="section-diagnosis">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="btn-group btn-group-justified">
                                <a id="btn_terdaftar" class="btn btn-primary">
                                    <?=translate("Terdaftar", $this->session->userdata("language"))?>
                                </a>
                                <a id="btn_tidak_terdaftar" class="btn btn-default">
                                    <?=translate("Tidak Terdaftar", $this->session->userdata("language"))?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <input class="form-control hidden" id="tipe_pasien" name="tipe_pasien" value="" >
                        
                        <div class="form-group pasien_terdaftar">
                            <label class="col-md-12"><?=translate("No. Rekam Medis", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input class="form-control" id="no_rekmed" name="no_rekmed" value="" placeholder="<?=translate("No. Rekam Medis", $this->session->userdata("language"))?>" required>
                                    <span class="input-group-btn">
                                        <a class="btn grey-cascade pilih-pasien" title="<?=translate('Pilih Pasien', $this->session->userdata('language'))?>">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </span>
                                </div>
                                <input class="form-control hidden" id="id_ref_pasien" name="id_ref_pasien" value="" required placeholder="<?=translate("ID Referensi Pasien", $this->session->userdata("language"))?>">
                            </div>  
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Pasien", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                                    <input class="form-control" id="nama_ref_pasien" name="nama_ref_pasien" value="" readonly  required placeholder="<?=translate("Nama Pasien", $this->session->userdata("language"))?>">
                            </div>  
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                                <textarea class="form-control" id="alamat_pasien" name="alamat_pasien" value="" readonly cols="6" rows="4"></textarea>
                            </div>  
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate('Tanggal', $this->session->userdata('language'))?> :</label>
                            <div class="col-md-12">
                                <div class="input-group date">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?=date('d M Y')?>" readonly >
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group hidden">
                            <label class="col-md-12"><?=translate("Shift", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
                            <div class="col-md-12">
                                <?php
                                    $shift_tindakan = 1;
                                    if(date('H:i:s') > '03:00:01' &&  date('H:i:s') <= '12:00:00'){
                                        $shift_tindakan = 1;
                                    }if(date('H:i:s') > '12:00:01' &&  date('H:i:s') <= '18:30:00'){
                                        $shift_tindakan = 2;
                                    }if(date('H:i:s') > '18:30:01' &&  date('H:i:s') <= '23:59:59'){
                                        $shift_tindakan = 3;
                                    }if(date('H:i:s') > '00:00:01' &&  date('H:i:s') <= '03:00:00'){
                                        $shift_tindakan = 3;
                                    }
                                    $jenis_option = array(
                                        ''          => translate('Pilih', $this->session->userdata('language')).'..',
                                        '1'         => translate('Shift 1', $this->session->userdata('language')),
                                        '2'         => translate('Shift 2', $this->session->userdata('language')),
                                        '3'         => translate('Shift 3', $this->session->userdata('language')),
                                        '4'         => translate('Shift 4', $this->session->userdata('language')),
                                    );
                                    echo form_dropdown('shift', $jenis_option, $shift_tindakan,'id="shift" class="form-control" required ');
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Jenis Vaksin", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                                <?php
                                    $vaksin = $this->master_vaksin_m->get_by(array('is_active' => 1));

                                    $options_vaksin = array(
                                        '' => translate('Pilih', $this->session->userdata('language')).'...'
                                    );

                                    foreach ($vaksin as $vks) {
                                        $options_vaksin[$vks->id] = $vks->nama;
                                    }

                                    echo form_dropdown('master_vaksin_id', $options_vaksin, '', 'id="master_vaksin_id" class="form-control select2" required');

                                ?>
                            </div>  
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Dokter", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                                <?php
                                    $dokter = $this->user_m->get_by(array('user_level_id' => 10, 'is_active' => 1));

                                    $options_dokter = array(
                                        '' => translate('Pilih', $this->session->userdata('language')).'...'
                                    );

                                    foreach ($dokter as $dkt) {
                                        $options_dokter[$dkt->id] = $dkt->nama;
                                    }

                                    echo form_dropdown('dokter_id', $options_dokter, '', 'id="dokter_id" class="form-control select2" required');

                                ?>
                            </div>  
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Perawat", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                                <?php
                                    $perawat = $this->user_m->get_by('user_level_id in (9,18) and is_active = 1');

                                    $options_perawat = array(
                                        '' => translate('Pilih', $this->session->userdata('language')).'...'
                                    );

                                    foreach ($perawat as $perawat) {
                                        $options_perawat[$perawat->id] = $perawat->nama;
                                    }

                                    echo form_dropdown('perawat_id', $options_perawat, '', 'id="perawat_id" class="form-control select2" required');

                                ?>
                            </div>  
                        </div>
                
                        
                        </div>
                    </div><!-- end of <div class="portlet-body"> -->    
            </div>
        </div><!-- end of <div class="col-md-4"> -->
            <div class="col-md-9">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject"><?=translate("History Vaksin Pasien", $this->session->userdata("language"))?></span>
                        </div>                      
                    </div>
                    <div class="portlet-body"> 
                        <table class="table table-bordered" id="tabel_history_vaksin">
                            <thead>
                                <tr>
                                    <th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="25%"><?=translate("Nama", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="10%"><?=translate("Tgl", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="25%"><?=translate("Dokter", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="25%"><?=translate("Perawat", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="14%"><?=translate("Cabang", $this->session->userdata("language"))?></th>
                                </tr>
                            </thead>
                                    
                            <tbody>
                                
                            </tbody>
                           
                        </table>
                       
                        
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject"><?=translate("Daftar Item Digunakan", $this->session->userdata("language"))?></span>
                        </div>                      
                    </div>
                    <div class="portlet-body"> 
                        
                        <?php
                            $option_satuan = array();
                            $btn_search         = '<span class="input-group-btn"><button type="button" title="'.translate('Pilih Item', $this->session->userdata('language')).'" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></span>';
                            $btn_del            = '<div class="text-center"><button class="btn red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
                            
                            $btn_add_identitas     = '<span class="input-group-btn"><button type="button" data-toggle="modal" data-target="#popup_modal_jumlah_keluar" href="'.base_url().'tindakan/tindakan_vaksin/add_identitas/item_row_{0}" class="btn blue-chambray add-identitas" name="item[{0}][identitas]" title="Tambah Jumlah"><i class="fa fa-info"></i></button></span>'; 
       
                            $attrs_item_id = array(
                                'id'          => 'item_id_{0}',
                                'name'        => 'item[{0}][item_id]',
                                'class'       => 'form-control hidden',
                            );

                            $attrs_item_code = array(
                                'id'          => 'item_kode_{0}',
                                'name'        => 'item[{0}][kode]',
                                'class'       => 'form-control',
                                'width'       => '50%',
                                'readonly' => 'readonly',
                            );

                            $attrs_item_name = array(
                                'id'          => 'item_name_{0}',
                                'name'        => 'item[{0}][name]',
                                'class'       => 'form-control',
                                'readonly'    => 'readonly',
                            );

                            $attrs_item_jumlah = array(
                                'id'       => 'item_qty_{0}',
                                'name'     => 'item[{0}][qty]',
                                'class'    => 'form-control',
                                'value'    => 1,
                                'min'      => 0,
                                'type'     => 'number',
                            );

                            $attrs_item_harga = array(
                                'id'       => 'item_harga_{0}',
                                'name'     => 'item[{0}][harga]',
                                'class'    => 'form-control',
                                'type'     => 'hidden',
                                 'value'    => 0,
                            );
                            $attrs_item_sub_total = array(
                                'id'       => 'item_sub_total_{0}',
                                'name'     => 'item[{0}][sub_total]',
                                'class'    => 'form-control',
                                'type'     => 'hidden',
                                'value'    => 0,
                            );



                            // item row column
                            $item_cols = array(// style="width:156px;
                                'item_code'   => '<div class="input-group">'.form_input($attrs_item_code).form_input($attrs_item_id).$btn_search.'</div>',
                                'item_name'   => form_input($attrs_item_name).'<div id="identitas_row" class="hidden"></div>',
                                'item_satuan' => form_dropdown('item[{0}][satuan_id]',  $option_satuan, "", "id='item_satuan_id_{0}' class='form-control'"),
                                'item_jumlah' => form_input($attrs_item_jumlah),
                                'item_harga'   => form_input($attrs_item_harga).'<div id="item_harga" class="text-right">Rp.0,-</div>',
                                'item_sub_total'   => form_input($attrs_item_sub_total).'<div id="item_sub_total" class="text-right">Rp.0,-</div>',
                                'action'      => $btn_del,
                            );

                            $item_row_template =  '<tr id="item_row_{0}" class="row_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

                        ?>
                       
                        <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
                        
                        <table class="table table-bordered" id="tabel_tambah_item">
                            <thead>
                                <tr>
                                    <th class="text-center" width="15%"><?=translate("Kode", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="25%"><?=translate("Nama", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="15%"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="10%"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="15%"><?=translate("Harga", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="15%"><?=translate("Sub Total", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="1%" ><?=translate("Aksi", $this->session->userdata("language"))?></th>
                                </tr>
                            </thead>
                                    
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <input name="grand_total" id="grand_total_hidden" type="hidden" class="form-control"></input>
                                    <th colspan="5" class="text-right">Grand Total</th>
                                    <th colspan="2" class="text-right" id="grand_total">Rp. 0,-</th>
                                </tr>
                            </tfoot>
                        </table>

                        <br/>
    
                        <div class="form-group">
                            <label class="control-label col-md-3"><?=translate("Vaksin Berlanjut", $this->session->userdata("language"))?> :</label>
                            
                            <div class="col-md-9">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                    <input type="radio" name="is_lanjut" id="optionspkpya" value="1" required> Ya </label>
                                    <label class="radio-inline">
                                    <input type="radio" name="is_lanjut" id="optionspkptdk" value="0" required> Tidak </label>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-group hidden" id="div_lanjut">
                            <label class="control-label col-md-3"><?=translate("Tanggal Selanjutnya", $this->session->userdata("language"))?> :</label>
                            
                            <div class="col-md-6">
                                <div class="input-group date">
                                    <input type="text" class="form-control" id="tanggal_lanjut" name="tanggal_lanjut" placeholder="Tanggal" value="<?=date('d M Y')?>" readonly >
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div><!-- end of <div class="col-md-8"> -->
        </div>
    </div>
</div>


<?=form_close()?>

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No. RM', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tempat, Tanggal Lahir', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 

<div id="popover_item_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" widht="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>      
    </div>
</div> 





