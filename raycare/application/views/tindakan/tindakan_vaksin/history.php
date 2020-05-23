<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject"><?=translate("History Vaksin Pasien", $this->session->userdata("language"))?></span>
        </div>  
        <div class="actions">
            <a class="btn btn-circle btn-default" href="<?=base_url()?>tindakan/tindakan_vaksin"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
        </div>                      
    </div>
    <div class="portlet-body"> 
        <table class="table table-bordered" id="tabel_history_vaksin">
            <thead>
                <tr>
                    <th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="20%"><?=translate("Pasien", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="15%"><?=translate("Vaksin", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="10%"><?=translate("Tgl", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="20%"><?=translate("Dokter", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="20%"><?=translate("Perawat", $this->session->userdata("language"))?></th>
                    <th class="text-center" width="14%"><?=translate("Cabang", $this->session->userdata("language"))?></th>
                </tr>
            </thead>
                    
            <tbody>
                
            </tbody>
           
        </table>
       
        
    </div>
</div>