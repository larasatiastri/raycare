<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    <i class=""></i><?=translate("Data User", $this->session->userdata("language"))?>
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="table_user">
                    <thead>
                        <tr role="row" class="heading">
                
                            <th scope="col" ><div class="text-center"><?=translate("ID", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("User Fullname", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Branch", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Actions", $this->session->userdata("language"))?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
