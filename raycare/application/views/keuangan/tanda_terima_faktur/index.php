<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-file font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tanda Terima Faktur", $this->session->userdata("language"))?></span>
        </div>
        
        <div class="actions">
            <a class="btn btn-default btn-circle hidden" href="<?=base_url()?>keuangan/tanda_terima_faktur/add">
                <i class="fa fa-plus"></i> 
                <?=translate('Tambah', $this->session->userdata('language'))?>
            </a>
        </div>
        
    </div>
    <div class="portlet-body">
        <table class="table table-striped table-hover" id="table_tanda_terima_faktur">
           <thead>
                <tr>
                    <th class="text-center" width="5%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="8%"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width=""><?=translate("Supplier", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="12%"><?=translate("Ref", $this->session->userdata("language"))?> </th>
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
