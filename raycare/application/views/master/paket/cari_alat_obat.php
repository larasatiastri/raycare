<div id="popover_item_content" class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body form">
                <form class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-4"><?=translate("Status :", $this->session->userdata("language"))?></label>
                            <div class="col-md-4">
                                <select class="bs-select form-control" id="select_so_history">
                                    <option value="" ><?=translate("All", $this->session->userdata("language"))?></option>
                                    <option value="1" ><?=translate("Alat Kesehatan", $this->session->userdata("language"))?></option>
                                    <option value="2" ><?=translate("Obat - obatan", $this->session->userdata("language"))?></option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_item_search">
            <thead>
                <tr role="row" class="heading">
                    <th><div class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Unit", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Harga", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Tipe Item", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 

