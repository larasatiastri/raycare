<div id="popover_supplier_content" class="row">
    <div class="portlet-body form">
        <div class="form-body">
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption">
                        <span class="caption-subject"><?=translate("Pilih Supplier", $this->session->userdata("language"))?></span>
                    </div>
                    <ul class="nav nav-tabs">
                        <li  class="active">
                            <a href="#supplier" data-toggle="tab">
                                <?=translate('Supplier', $this->session->userdata('language'))?> </a>
                        </li>
                        <li>
                            <a href="#calon_supplier" data-toggle="tab">
                                <?=translate('Calon Supplier', $this->session->userdata('language'))?> </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="supplier" >
                        <?php include('tab_supplier/supplier.php') ?>
                    </div>
                    <div class="tab-pane" id="calon_supplier">
                        <?php include('tab_supplier/calon_supplier.php') ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>  
</div>