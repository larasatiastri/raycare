<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Kirim Petty Cash", $this->session->userdata("language"))?></span>
        </div>
        <div class="actions">
              <a href="<?=base_url()?>keuangan/kirim_petty_cash/add" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#popup_modal_add" class="btn btn-circle btn-default">
                  <i class="fa fa-plus"></i>
                  <span>
                       <?=translate("Tambah", $this->session->userdata("language"))?>
                  </span>
              </a>
          </div>
        
    </div>
    <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover" id="table_kirim_petty_cash">
            <thead>
                <tr>
                    <th width="3%"><div class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Diberikan Oleh", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Diterima Oleh", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?></div></th>                                 
                    <th width="2%"><div class="text-center"><?=translate("Status", $this->session->userdata("language"))?></div></th>                             
                    <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>                                 
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>                            
    </div>
</div>

<div class="modal fade bs-modal-lg" id="popup_modal_add" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">
       </div>
   </div>
</div>
<div class="modal fade bs-modal-lg" id="popup_modal_edit" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">
       </div>
   </div>
</div>
<div class="modal fade bs-modal-lg" id="popup_modal_view" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">
       </div>
   </div>
</div>