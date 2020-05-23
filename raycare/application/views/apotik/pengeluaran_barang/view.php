<?php
    $form_attr = array(
        "id"            => "form_pengeluaran_barang", 
        "name"          => "form_pengeluaran_barang", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        "role"          => "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."apotik/pengeluaran_barang/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
    
    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');
?>

<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pengeluaran Barang", $this->session->userdata("language"))?></span>
        </div>

        <div class="actions">
            <?php $msg = translate("Apakah anda yakin akan membuat pengeluaran barang ini?",$this->session->userdata("language"));?>
            <a class="btn btn-default btn-circle" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
            <a id="confirm_save" class="btn btn-sm btn-primary btn-circle hidden" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
            <a id="confirm_save_draft" class="btn btn-sm btn-primary btn-circle hidden" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan Draft", $this->session->userdata("language"))?></a>
            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
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
                            <label class="col-md-12"><?=translate("Tanggal Keluar", $this->session->userdata("language"))?>:</label>
                            
                            <label class="col-md-12"><?=date('d F Y', strtotime($form_data['tanggal']))?></label>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Diterima Oleh", $this->session->userdata("language"))?> :</label>
                            <label class="col-md-12"><?=$form_data['user_penerima']?></label>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> <span>:</span></label>
                            <label class="col-md-12"><?=$form_data['keterangan']?></span></label>
                            
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
                        
                        <table class="table table-bordered" id="table_pengeluaran_item">
                            <thead>
                                <tr>
                                    <th class="text-center" width="15%"><?=translate("Kode", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="25%"><?=translate("Nama", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="25%"><?=translate("BN", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="25%"><?=translate("ED", $this->session->userdata("language"))?></th>
                                    <th class="text-left" width="10%"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                                </tr>
                            </thead>
                                    
                            <tbody>
                                <?php
                                    if(count($form_data_detail)){
                                        foreach ($form_data_detail as $data_detail) {
                                            ?>
                                            <tr>
                                                <td><?=$data_detail['kode']?></td>
                                                <td><?=$data_detail['nama']?></td>
                                                <td><?=$data_detail['bn_sn_lot']?></td>
                                                <td><?=$data_detail['expire_date']?></td>
                                                <td><?=$data_detail['jumlah'].' '.$data_detail['nama_satuan']?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>  
<?=form_close()?>


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
