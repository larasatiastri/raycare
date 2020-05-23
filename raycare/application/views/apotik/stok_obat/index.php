<?php
    $form_attr = array(
        "id"            => "form_stok_obat", 
        "name"          => "form_stok_obat", 
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
    
    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');


?>
<div class="portlet light">
    <div class="portlet-title"> 
        <div class="caption">
            <i class="fa fa-sort-amount-desc font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Stok Obat, Vitamin dan Alkes", $this->session->userdata("language"))?></span>
        </div>
        
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            
                <table class="table table-striped table-bordered table-hover" id="table_stok_obat">
                <thead>
                <tr>
                    <th class="text-center" width="12%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Stok", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Expire Date", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?> </th>
                </tr>
                
                </thead>
                <tbody>
                 
                </tbody>
                </table>
            </div>
        </div>
    </div>  
</div>
<?=form_close()?>
