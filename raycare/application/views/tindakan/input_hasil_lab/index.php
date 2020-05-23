<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject"><?=translate("Hasil Lab Pasien", $this->session->userdata("language"))?></span>
        </div>  
        <div class="actions">
            <a class="btn btn-circle btn-primary" href="<?=base_url()?>tindakan/input_hasil_lab/add"><i class="fa fa-plus"></i>  <?=translate("Tambah", $this->session->userdata("language"))?></a>
        </div>                      
    </div>
    <div class="portlet-body"> 
        <table class="table table-striped table-bordered table-hover" id="table_hasil_lab">
            <thead>
                <tr>
                    <th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="10%"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="15%"><?=translate("No. Hasil Lab", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="15%"><?=translate("Lab. Klinik", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="20%"><?=translate("Pasien", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="20%"><?=translate("Umur", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="20%"><?=translate("Dokter", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
                </tr>
            </thead>
                    
            <tbody>
                
            </tbody>
           
        </table>
       
        
    </div>
</div>