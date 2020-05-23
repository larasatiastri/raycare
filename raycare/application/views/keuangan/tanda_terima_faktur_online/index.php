<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-file font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tanda Terima Faktur Online", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions">
            <a class="btn btn-primary btn-circle" id="btn_grab">
                <i class="fa fa-recycle"></i> 
                <?=translate('Grab Data', $this->session->userdata('language'))?>
            </a>
        </div>
    </div>
   
    <div class="portlet-body">
        <table class="table table-striped table-hover" id="table_tanda_terima_faktur_online">
           <thead>
                <tr>
                    <th class="text-center" width="5%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="8%"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="15%"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
                    <th class="text-center" ><?=translate("Ref", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="10%"><?=translate("Nominal", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="10%"><?=translate("Posisi", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="5%"><?=translate("Waktu Akhir", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
</div>
