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
    
    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');

    $option_cabang = array(
        '0'  => '---'.translate('Pilih Cabang', $this->session->userdata('language')).'---'
    );
    $result_cabang = $this->cabang_m->get_by(array('tipe !=' => 0, 'is_active' => 1));
    foreach($result_cabang as $row)
    {
        $option_cabang[$row->id] = $row->nama;
    }

    $option_gudang = array(
    );
    $result_gudang = $this->gudang_m->get_by(array( 'is_active' => 1));
    foreach($result_gudang as $row)
    {
        $option_gudang[$row->id] = $row->nama;
    }

    $option_kategori = array(
        '0'  => '---'.translate('Semua Kategori', $this->session->userdata('language')).'---'
    );
    $result_kategori = $this->item_kategori_m->get_by(array('is_active' => 1));
    foreach($result_kategori as $row)
    {
        $option_kategori[$row->id] = $row->nama;
    }

    $option_sub_kategori = array(
        '0'  => '---'.translate('Semua Sub Kategori', $this->session->userdata('language')).'---'
    );

    $option_pilihan = array(
        '1' => 'Stok Tersedia', 
        '2' => 'Stok Kosong', 
    );

?>
<div class="portlet light">
    <div class="portlet-title"> 
        <div class="caption">
            <i class="fa fa-sort-amount-desc font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Buffer Stock Raycare Kalideres", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions hidden">
             
            <button type="button" id="btnso" class="btn btn-default btn-circle" data-toggle="modal" href="<?=base_url()?>penjualan/buffer_stock/modal_so" data-target="#ajax_so">
                <i class="fa fa-plus"></i>
                <?=translate("Penjualan", $this->session->userdata("language"))?>
            </button>
            <button type="button" id="btnpo" class="btn btn-default btn-circle" data-toggle="modal" href="<?=base_url()?>penjualan/buffer_stock/modal_po" data-target="#ajax_po">
                <i class="fa fa-plus"></i>
                <?=translate("Pembelian", $this->session->userdata("language"))?>
            </button>
            
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
                <div class="col-md-12">
                    <div class="portlet box blue-madison">
                        <div class="portlet-title" style="margin-bottom: 0px !important;">
                            <div class="caption">
                                <!-- <i class="fa fa-cogs font-green-sharp"></i> -->
                                <span class="caption-subject"><?=translate("Filter", $this->session->userdata("language"))?></span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-2 hidden">
                                    <div class="form-group hidden">
                                        <div class="col-md-12">
                                            <?php
                                                echo form_dropdown('cabang', $option_cabang, $this->session->userdata('cabang_id'),'id="cabang" class="form-control" required="required"');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <?php
                                                echo form_dropdown('gudang', $option_gudang, '','id="gudang" class="form-control" required="required"');
                                            ?>  
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <?php
                                                echo form_dropdown('kategori', $option_kategori, '','id="kategori" class="form-control" required="required"');
                                            ?>  
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                           <?php
                                                echo form_dropdown('sub_kategori', $option_sub_kategori, '','id="sub_kategori" class="form-control" required="required"');
                                            ?>   
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                           <?php
                                                echo form_dropdown('pilihan', $option_pilihan, '','id="pilihan" class="form-control" required="required"');
                                            ?>   
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <a id="cariinventory" class="btn btn-primary col-md-12">
                                                <i class="fa fa-search"></i>
                                                <?=translate("Cari", $this->session->userdata("language"))?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <a  id="printinventory" target="_blank" href="<?=base_url()?>apotik/buffer_stock/cetak_stok/WH-05-2016-001/0/0/1" class="btn btn-primary col-md-12">
                                                <i class="fa fa-print"></i>
                                                <?=translate("Cetak", $this->session->userdata("language"))?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="portlet box blue-sharp">
                        <div class="portlet-title" style="margin-bottom: 0px !important;">
                            <div class="caption">
                                <span class="caption-subject"><?=translate("Daftar Stok", $this->session->userdata("language"))?></span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-hover" id="table_buffer_stok">
                            <thead>
                            <tr>
                                <th class="text-center" width="12%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
                                <th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
                                <th class="text-center"><?=translate("Buffer", $this->session->userdata("language"))?> </th> 
                                <th class="text-center"><?=translate("Stok", $this->session->userdata("language"))?> </th>
                                <th class="text-center" ><?=translate("Satuan", $this->session->userdata("language"))?> </th>
                                <th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
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