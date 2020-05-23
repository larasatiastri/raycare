
<div class="portlet light">
    <div class="portlet-title"> 
        <div class="caption">
            <i class="fa fa-sort-amount-desc font-blue-sharp"></i>
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Stok Box Paket", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions">   
            <a class="btn btn-circle btn-info" href="<?=base_url()?>apotik/box_paket/history"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
            
        </div>
        
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            
                <table class="table table-striped table-bordered table-hover" id="table_stok_box_paket_history">
                <thead>
                <tr>
                    <th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Kode Paket", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Nama Box Paket", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Isi Box", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Tgl Buat", $this->session->userdata("language"))?> </th>
                    <th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
                    <th class="text-center"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> </th>
                </tr>
                
                </thead>
                <tbody>
                 
                </tbody>
                </table>
            </div>
        </div>
    </div>  
</div>