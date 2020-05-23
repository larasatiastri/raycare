<?php
    $form_attr = array(
        "id"            => "form_add_hasil_lab", 
        "name"          => "form_add_hasil_lab", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        "role"          => "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."tindakan/input_hasil_lab/save", $form_attr, $hidden);
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
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Input Hasil Lab', $this->session->userdata('language'))?></span>
        </div>
        <?php $msg = translate("Apakah anda yakin akan menyimpan data hasil lab ini?",$this->session->userdata("language"));?>
        <div class="actions">   
            <a class="btn btn-circle btn-default" href="<?=base_url()?>tindakan/input_hasil_lab"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
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
                            <label class="col-md-12"><?=translate('Tanggal Pemeriksaan', $this->session->userdata('language'))?> :</label>
                            <div class="col-md-12">
                                <div class="input-group date">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" value="<?=date('d M Y')?>" readonly >
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                                           
                        <div class="form-group">
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
                                    <input class="form-control" id="nama_ref_pasien" name="nama_ref_pasien" value="" readonly required placeholder="<?=translate("Nama Pasien", $this->session->userdata("language"))?>">
                                    <input class="form-control hidden" id="tanggal_lahir" name="tanggal_lahir" value="">
                            </div>  
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Usia", $this->session->userdata("language"))?> : </label>
                            <div class="col-md-12">
                                <input class="form-control" id="usia" name="usia" value="" readonly placeholder="<?=translate("Usia Pasien", $this->session->userdata("language"))?>">
                                <input class="form-control hidden" id="umur" name="umur" value="">
                                <input class="form-control hidden" id="kategori_usia_id" name="kategori_usia_id" value="">
                            </div>  
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                                <textarea class="form-control" id="alamat_pasien" name="alamat_pasien" value="" readonly cols="6" rows="4"></textarea>
                            </div>  
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Lab. Klinik", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                            <?php
                                $data_lab_klinik = $this->laboratorium_klinik_m->get_by(array('is_active' => 1));

                                $lab_option = array();

                                foreach ($data_lab_klinik as $lab_klinik) {
                                    $lab_option[$lab_klinik->id] = $lab_klinik->nama;
                                }

                                echo form_dropdown('laboratorium_klinik_id',$lab_option,'','id="laboratorium_klinik_id" class="form-control"');
                            ?>
                            </div>  
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?=translate("No. Lab", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                                <input class="form-control" id="no_hasil_lab" name="no_hasil_lab" value="" required placeholder="<?=translate("No. Lab", $this->session->userdata("language"))?>">
                            </div>  
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Dokter", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                                <input class="form-control" id="nama_dokter" name="nama_dokter" value="" required placeholder="<?=translate("Nama Dokter", $this->session->userdata("language"))?>">
                            </div>  
                        </div>
                    </div>
                </div><!-- end of <div class="portlet-body"> -->    
            </div>
        </div><!-- end of <div class="col-md-4"> -->
            
        <div class="col-md-7">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject"><?=translate("Hasil Pemeriksaan Lab", $this->session->userdata("language"))?></span>
                    </div>                      
                </div>
                <div class="portlet-body"> 
                    
                    <?php
                        $option_satuan = array();
                        $btn_search         = '<span class="input-group-btn"><button type="button" title="'.translate('Pilih Item', $this->session->userdata('language')).'" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></span>';
                        $btn_del            = '<div class="text-center"><button class="btn red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
                        
                        $btn_add_identitas     = '<span class="input-group-btn"><button type="button" data-toggle="modal" data-target="#popup_modal_jumlah_keluar" href="'.base_url().'tindakan/input_hasil_lab/add_identitas/item_row_{0}" class="btn blue-chambray add-identitas" name="item[{0}][identitas]" title="Tambah Jumlah"><i class="fa fa-info"></i></button></span>'; 
   
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

                        $attrs_item_hasil = array(
                            'id'          => 'item_hasil_{0}',
                            'name'        => 'item[{0}][hasil]',
                            'class'       => 'form-control',
                        );

                        $attrs_item_id_detail = array(
                            'id'       => 'item_id_detail_{0}',
                            'name'     => 'item[{0}][id_detail]',
                            'class'    => 'form-control',
                            'type'     => 'hidden'
                        ); 

                        $attrs_item_nilai_normal_bawah = array(
                            'id'       => 'item_nilai_normal_bawah_{0}',
                            'name'     => 'item[{0}][nilai_normal_bawah]',
                            'class'    => 'form-control',
                            'type'     => 'hidden'
                        );

                        $attrs_item_nilai_normal_atas = array(
                            'id'       => 'item_nilai_normal_atas_{0}',
                            'name'     => 'item[{0}][nilai_normal_atas]',
                            'class'    => 'form-control',
                            'type'     => 'hidden'
                        );

                        $attrs_item_satuan = array(
                            'id'       => 'item_satuan_{0}',
                            'name'     => 'item[{0}][satuan]',
                            'class'    => 'form-control',
                            'type'     => 'hidden',
                        );
                        $attrs_item_keterangan = array(
                            'id'       => 'item_keterangan_{0}',
                            'name'     => 'item[{0}][keterangan]',
                            'class'    => 'form-control',
                        );


                        // item row column
                        $item_cols = array(// style="width:156px;
                            'item_code'   => '<div class="input-group">'.form_input($attrs_item_code).form_input($attrs_item_id).$btn_search.'</div>',
                            'item_hasil' => form_input($attrs_item_hasil),
                            'item_nilai_normal' => form_input($attrs_item_nilai_normal_bawah).form_input($attrs_item_nilai_normal_atas).form_input($attrs_item_id_detail).'<div id="item_nilai_normal" class="text-left"></div>',
                            'item_satuan'   => form_input($attrs_item_satuan).'<div id="item_satuan" class="text-left"></div>',
                            'item_keterangan'   => form_input($attrs_item_keterangan),
                            'action'      => $btn_del,
                        );

                        $item_row_template =  '<tr id="item_row_{0}" class="row_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

                    ?>
                   
                    <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
                    
                    <table class="table table-bordered" id="tabel_tambah_item">
                        <thead>
                            <tr>
                                <th class="text-center" width="30%"><?=translate("Pemeriksaan", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("Hasil", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="20%"><?=translate("Nilai Normal", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="5%"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="25%"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="1%" ><?=translate("Aksi", $this->session->userdata("language"))?></th>
                            </tr>
                        </thead>
                                
                        <tbody>
                            
                        </tbody>
                        
                    </table>

                </div>
            </div>
            
        </div><!-- end of <div class="col-md-8"> -->

    <div class="col-md-2">
        <div class="portlet light bordered" id="section-file">
            <div class="portlet-title" style="margin-bottom: 0px !important;">
                <div class="caption">
                    <span class="caption-subject"><?=translate("Upload Hasil Lab", $this->session->userdata("language"))?></span>
                </div>
                <div class="actions">
                    <a class="btn btn-icon-only btn-default btn-circle add-upload">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
            
            
            <div class="portlet-body">
                <?php
                $form_upload_hasil_lab = '
                    <div class="form-group">
                        <label class="col-md-12 bold">'.translate("Nama File", $this->session->userdata("language")).' :</label>
                        <div class="col-md-12">
                            <div class="input-group">
                                <input class="form-control" id="url_hasil_lab{0}" name="hasil_lab[{0}][url]" placeholder="Nama File" readonly>
                                <span class="input-group-btn">
                                    <a class="btn red-intense del-this" id="btn_delete_upload_{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12 bold">'.translate("Upload File", $this->session->userdata("language")).' :<span class="required">*</span></label>
                        <div class="col-md-12">
                            <div id="upload_{0}">
                                <span class="btn default btn-file">
                                    <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>        
                                    <input type="file" class="upl_invoice" name="upl" id="upl_{0}" data-url="'.base_url().'upload_new/upload_photo" multiple />
                                </span>

                            <ul class="ul-img">
                            </ul>

                            </div>
                        </div>
                    </div>
                    
                    ';
                ?>

                <input type="hidden" id="tpl-form-upload" value="<?=htmlentities($form_upload_hasil_lab)?>">
                <div class="form-body" >
                    <ul class="list-unstyled" id="hasilLabList">
                    </ul>
                </div>
            </div>
        </div>
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
                    <th><div class="text-center"><?=translate('Tipe', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kategori', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Satuan', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 



