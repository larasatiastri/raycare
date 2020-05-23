
<div class="portlet light">
    <div class="portlet-title"> 
        <div class="caption">
            <i class="fa fa-sort-amount-desc font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Master Box Paket", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions">   
            <a class="btn default" href="<?=base_url()?>apotik/master_box_paket"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
            
        </div>
        
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            
                <table class="table table-striped table-bordered table-hover" id="table_master_box_paket">
                <thead>
                <tr>
                    <th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Nama Box Paket", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="1%"><?=translate("Isi Box", $this->session->userdata("language"))?> </th>
                </tr>
                
                </thead>
                <tbody>
                 
                </tbody>
                </table>
            </div>
        </div>
    </div>  
</div>
