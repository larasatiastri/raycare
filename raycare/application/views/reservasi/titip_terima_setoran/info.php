<div id="popover_pasien_content" class="row" style="display:none">
    <div class="portlet-body form">
            <div class="form-body">
                <ul class="nav nav-tabs">
                    <li  class="active">
                        <a href="#kasir_biaya" data-toggle="tab">
                            <?=translate('Biaya', $this->session->userdata('language'))?> </a>
                    </li>
                    <li>
                        <a href="#pembayaran_pasien" data-toggle="tab">
                            <?=translate('Faktur', $this->session->userdata('language'))?> </a>
                    </li>
                </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="kasir_biaya" >
                    <?php include('tab_data_biaya/kasir_biaya.php') ?>
                </div>
                <div class="tab-pane" id="pembayaran_pasien">
                    <?php include('tab_data_biaya/pembayaran_pasien.php') ?>
                </div>
            </div>
        </div>
    </div>  
</div>