<div id="popover_pasien_content" class="row">
    <div class="portlet-body form">
            <div class="form-body">
                <ul class="nav nav-tabs">
                    <li  class="active">
                        <a href="#user" data-toggle="tab">
                            <?=translate('User', $this->session->userdata('language'))?> </a>
                    </li>
                    <li>
                        <a href="#gudang_orang" data-toggle="tab">
                            <?=translate('Gudang Orang', $this->session->userdata('language'))?> </a>
                    </li>
                </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="user" >
                    <?php include('tab_data_user/user.php') ?>
                </div>
                <div class="tab-pane" id="gudang_orang">
                    <?php include('tab_data_user/gudang_orang.php') ?>
                </div>
            </div>
        </div>
    </div>  
</div> 