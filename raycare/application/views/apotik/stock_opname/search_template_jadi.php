<div id="popover_template" class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    <i class=""></i><?=translate("Search Template", $this->session->userdata("language"))?>
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="table_template">
                    <thead>
                        <tr role="row" class="heading">                
                            <th scope="col" ><div class="text-center"><?=translate("Template", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Orang", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>
                        </tr>
                    </thead>
                    <tbody>                      
                                                       
                    </tbody>
                </table>
            </div>
        </div>

        <div class="portlet" id="data-detail">
            <div class="portlet-title">
                <div class="caption">
                    <i class=""></i><?=translate("Data Detail Template", $this->session->userdata("language"))?>
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="table_detail">
                    <thead>
                        <tr role="row" class="heading">                
                            <th scope="col" ><div class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></div></th>
                        </tr>
                    </thead>
                   
                    <tbody>
                    </tbody>
                
                </table>
            </div>

        </div>
    </div>
</div>