
<div class="portlet light">
    <div class="portlet-title"> 
        <div class="caption">
            <i class="fa fa-sort-amount-desc font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Laporan Rugi Laba", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions">   
           
            <a class="btn btn-circle btn-info" href="<?=base_url()?>akunting/laporan_rugi_laba/add"><i class="fa fa-plus"></i>  <?=translate("Tambah", $this->session->userdata("language"))?></a>
            
        </div>
        
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            
                <table class="table table-striped table-bordered table-hover" id="table_labrug">
                <thead>
                <tr>
                    <th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="3%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="5%"><?=translate("Nomor", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Laba Kotor", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("LabaBersihSblmPajak", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Pajak", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("LabaBersihStlahPajak", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("User", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
                </tr>
                
                </thead>
                <tbody>
                 
                </tbody>
                </table>
            </div>
        </div>
    </div>  
</div>
