<div id="popover_item_content_tindakan" class="row">
    <div class="portlet-body form">
            <div class="form-body">
                <ul class="nav nav-tabs">
                    <li  class="active">
                        <a href="#obat" data-toggle="tab">
                            <?=translate('Obat', $this->session->userdata('language'))?> </a>
                    </li>
                    <li>
                        <a href="#racikan_obat" data-toggle="tab">
                            <?=translate('Racikan Obat', $this->session->userdata('language'))?> </a>
                    </li>
                </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="obat" >
                    <?php include('tab_data_resep/obat.php') ?>
                </div>
                <div class="tab-pane" id="racikan_obat">
                    <?php include('tab_data_resep/racikan_obat.php') ?>
                </div>
            </div>
        </div>
    </div>
    
</div> 

