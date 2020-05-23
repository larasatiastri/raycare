<?php
    $form_attr = array(
        "id"            => "form_pemakaian_obat_alkes", 
        "name"          => "form_pemakaian_obat_alkes", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        "role"          => "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."apotik/pemakaian_obat_alkes/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
    
    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');
?>

<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pemakaian Obat dan Alkes", $this->session->userdata("language"))?></span>
        </div>

        <div class="actions">
            <?php $msg = translate("Apakah anda yakin akan menyimpan data Pemakaian Obat dan Alkes ini?",$this->session->userdata("language"));?>
            <a class="btn btn-default btn-circle" href="<?=base_url()?>apotik/pemakaian_obat_alkes/history"><i class="fa fa-history"></i> <?=translate("History", $this->session->userdata("language"))?></a>
            <a data-toggle="modal" data-target="#modal_verif" href="<?=base_url()?>apotik/pemakaian_obat_alkes/verifikasi" class="btn btn-sm btn-primary btn-circle"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
            <a id="confirm_save_draft" class="btn btn-sm btn-primary btn-circle hidden" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan Draft", $this->session->userdata("language"))?></a>
           
        </div>
    </div>
    <div class="portlet-body form">
        <div class="row">
            <div class="col-md-3">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
                        </div>                      
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Tanggal Keluar", $this->session->userdata("language"))?><span class="required" style="color:red;"> *</span>:</label>
                            
                            <div class="col-md-12">
                                <div class="input-group date" id="tanggal">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" readonly required value="<?=date('d F Y')?>">
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Jenis Tindakan", $this->session->userdata("language"))?> <span class="required" style="color:red;"> *</span>:</label>
                            <div class="col-md-12">
                            <?php
                            	$jenis_option = array(
                                    '3' => 'Semua',
                            		'1'	=> 'Tindakan HD',
                            		'2'	=> 'Tindakan Transfusi'
                            	);

                            	echo form_dropdown('jenis_tindakan',$jenis_option,3,'id="jenis_tindakan" class="form-control"');
                            ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> <span>:</span></label>
                            <div class="col-md-12">
                                <textarea name="keterangan" id="" cols="40" class="form-control" rows="5" placeholder="<?=translate("Keterangan", $this->session->userdata("language"))?>"></textarea>
                            </div>
                        </div>
                        <div class="form-group" hidden>
                            <label class="col-md-12"><?=translate("User Penerima", $this->session->userdata("language"))?> <span>:</span></label>
                            <div class="col-md-12">
                                <input name="user_penerima_id" id="user_penerima_id" class="form-control" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject"><?=translate("Daftar Item", $this->session->userdata("language"))?></span>
                        </div>                      
                    </div>
                    <div class="portlet-body"> 
                        <?php
                            $option_satuan = array();
                            $btn_search         = '<span class="input-group-btn"><button type="button" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></span>';
                            $btn_del            = '<div class="text-center"><button class="btn red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
                            
                            $btn_add_identitas     = '<span class="input-group-btn"><button type="button" data-toggle="modal" data-target="#popup_modal_jumlah_keluar" href="'.base_url().'apotik/pemakaian_obat_alkes/add_identitas/item_row_{0}" class="btn blue-chambray add-identitas" name="item[{0}][identitas]" title="Tambah Jumlah"><i class="fa fa-info"></i></button></span>'; 
       
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
                                'name'        => 'item[{0}][nama]',
                                'class'       => 'form-control',
                                'readonly'    => 'readonly',
                            );

                            $attrs_item_jumlah = array(
                                'id'       => 'item_jumlah_{0}',
                                'name'     => 'item[{0}][jumlah]',
                                'class'    => 'form-control',
                                'value'    => 0,
                                'min'      => 0,
                                'type'     => 'number',
                                'readonly' => 'readonly',
                            );

                            $attrs_item_stok = array(
                                'id'       => 'item_stok_{0}',
                                'name'     => 'item[{0}][stok]',
                                'class'    => 'form-control',
                                'type'     => 'hidden',
                            );



                            // item row column
                            $item_cols = array(// style="width:156px;
                                'item_code'   => '<div class="input-group">'.form_input($attrs_item_code).form_input($attrs_item_id).$btn_search.'</div>',
                                'item_name'   => form_input($attrs_item_name).'<div id="identitas_row" class="hidden"></div>',
                                'item_satuan' => form_dropdown('item[{0}][satuan_id]',  $option_satuan, "", "id='item_satuan_id_{0}' class='form-control bs-select-satuan'"),
                                'item_stok'   => form_input($attrs_item_stok).'<div id="item_stok" class="text-center"></div>',
                                'item_jumlah' => '<div class="input-group">'.form_input($attrs_item_jumlah).$btn_add_identitas.'</div>',
                                'action'      => $btn_del,
                            );

                            $item_row_template =  '<tr id="item_row_{0}" class="row_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

                        ?>
                       
                        <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
                        
                        <table class="table table-bordered" id="table_pengeluaran_item">
                            <thead>
                                <tr>
                                    <th class="text-center" width="15%"><?=translate("Kode", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="25%"><?=translate("Nama", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="15%"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="15%"><?=translate("Stok", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="10%"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="1%" ><?=translate("Aksi", $this->session->userdata("language"))?></th>
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
<?=form_close()?>

<div id="popover_item_content">
    <table class="table table-condensed table-striped table-bordered table-hover" id="table_item_search">
        <thead>
            <tr>
                <th widht="20%"><div class="text-center">Kode</div></th>
                <th widht="79%"><div class="text-center">Nama</div></th>
                <th widht="1%"><div class="text-center">Aksi</div></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
            

<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_keluar" role="basic" aria-hidden="true">
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

<div class="modal fade bs-modal-lg" id="modal_verif" role="basic" aria-hidden="true">
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
