<div class="portlet light">
	<div class="portlet-title">
        <div class="caption">
            <span><?=translate("Item Berdasarkan Detail", $this->session->userdata("language"))?></span>
        </div>
	</div>
	<div class="portlet-body">
        <input class="form-control" id="item_id_detail">
        <table class="table table-striped table-bordered table-hover" id="table_item_detail">
            <thead>
                <tr>
                    <th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="1%">
                        <div class="text-center">
                            <input type="checkbox" class="group-checkable text-center" data-set="#table_item_detail .checkboxes"/>
                        </div>
                    </th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
	</div>
</div>

