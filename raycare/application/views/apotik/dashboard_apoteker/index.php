<?php
    $form_attr = array(
        "id"            => "form_buffer_stok", 
        "name"          => "form_buffer_stok", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        "role"          => "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."master/item/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
    
    
?>
<div class="portlet light">
    <div class="portlet-title"> 
        <div class="caption">
            <i class="fa fa-sort-amount-desc font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Dashboard Apoteker", $this->session->userdata("language"))?></span>
        </div>
       
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <?=$form_alert_danger?>
            </div>
            <div class="alert alert-success display-hide">
                <button class="close" data-close="alert"></button>
                <?=$form_alert_success?>
            </div>

            <div class="row">
               

                <div class="col-md-6">
                    <div class="portlet box blue-sharp">
                        <div class="portlet-title" style="margin-bottom: 0px !important;">
                            <div class="caption">
                                <span class="caption-subject"><?=translate("Daftar Stok Expired", $this->session->userdata("language"))?></span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-hover" id="table_expired">
                            <thead>
                            <tr>
                                <th class="text-center" width="12%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
                                <th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
                                <th class="text-center" width="3%"><?=translate("Jml", $this->session->userdata("language"))?> </th>
                                <th class="text-center" width="3%"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
                                <th class="text-center" width="8%"><?=translate("BN", $this->session->userdata("language"))?> </th>
                                <th class="text-center" width="15%"><?=translate("ED", $this->session->userdata("language"))?> </th>
                                <th class="text-center" width="25%"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
                            </tr>
                            
                            </thead>
                            <tbody>
                            <?php
                                foreach ($data_expired as $expired) :
                            ?>
                                <tr>
                                    <td><?=$expired['kode_item']?></td>
                                    <td><?=$expired['nama_item']?></td>
                                    <td><?=$expired['jumlah']?></td>
                                    <td><?=$expired['nama_satuan']?></td>
                                    <td><?=$expired['bn_sn_lot']?></td>
                                    <td><div class="text-center inline-button"><?=date('d M Y', strtotime($expired['expire_date']))?></div></td>
                                    <td><div class="text-left inline-button"><?=$expired['nama_supplier']?></div></td>
                                </tr>
                            <?php
                                endforeach;
                            ?>
                            </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="portlet box blue-sharp">
                        <div class="portlet-title" style="margin-bottom: 0px !important;">
                            <div class="caption">
                                <span class="caption-subject"><?=translate("Daftar Stok Buffer", $this->session->userdata("language"))?></span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-hover" id="table_buffer">
                            <thead>
                            <tr>
                                <th class="text-center" width="12%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
                                <th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
                                <th class="text-center" width="3%"><?=translate("Jml", $this->session->userdata("language"))?> </th>
                                <th class="text-center" ><?=translate("Satuan", $this->session->userdata("language"))?> </th>
                                <th class="text-center" width="8%"><?=translate("BN", $this->session->userdata("language"))?> </th>
                                <th class="text-center" width="15%"><div class="text-center inline-button"><?=translate("ED", $this->session->userdata("language"))?> </div></th>
                            </tr>
                            
                            </thead>
                            <tbody>
                            <?php
                                foreach ($data_buffer as $buffer) :
                            ?>
                                <tr>
                                    <td><?=$buffer['kode_item']?></td>
                                    <td><?=$buffer['nama_item']?></td>
                                    <td><?=$buffer['jumlah']?></td>
                                    <td><?=$buffer['nama_satuan']?></td>
                                    <td><?=$buffer['bn_sn_lot']?></td>
                                    <td><?=date('d M Y', strtotime($buffer['expire_date']))?></td>
                                </tr>
                            <?php
                                endforeach;
                            ?>
                            </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="portlet box blue-sharp">
                        <div class="portlet-title" style="margin-bottom: 0px !important;">
                            <div class="caption">
                                <span class="caption-subject"><?=translate("Daftar PO Outstanding", $this->session->userdata("language"))?></span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-hover" id="table_pembelian_proses">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" width="1%"><?=translate("No.PO", $this->session->userdata("language"))?> </th>
                                        <th class="text-center"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" width="10%"><?=translate("Tgl.Pesan", $this->session->userdata("language"))?> </th>
                                        <th class="text-center" width="10%"><?=translate("Tgl.Kadaluarsa", $this->session->userdata("language"))?> </th>
                                        <th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
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
</div>
<?=form_close()?>
<div  class="modal fade"  id="ajax_po" role="basic" aria-hidden="true">
        <div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
        
            <div class="modal-dialog">
                <div class="modal-content">
                
            
 
                </div>
                 
            </div>
</div>

<div  class="modal fade"  id="ajax_so" role="basic" aria-hidden="true">
        <div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
        
            <div class="modal-dialog">
                <div class="modal-content">
                
            
 
                </div>
                 
            </div>
</div>