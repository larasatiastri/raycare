<?php
	$form_attr = array(
		"id"			=> "form_proses_terima_setoran", 
		"name"			=> "form_proses_terima_setoran", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
	);
	$hidden = array(
		"command"			=> "add_deposit", 
	);
	echo form_open(base_url()."keuangan/titip_terima_setoran/save", $form_attr, $hidden);

?>


<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
          <i class="fa fa-money font-blue-sharp"></i>
          <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penerimaan Setoran", $this->session->userdata("language"))?></span>
        </div>
        
    </div>
    <div class="portlet-body">
        <table class="table table-striped table-hover" id="table_titip_setoran">
            <thead>
                <tr>
                    <th width="3%"><div class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Diberikan Oleh", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Diterima Oleh", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></div></th>
                    <th><div class="text-center"><?=translate("Jumlah Biaya", $this->session->userdata("language"))?></div></th>                                 
                    <th><div class="text-center"><?=translate("Sisa Saldo", $this->session->userdata("language"))?></div></th>                                 
                    <th width="2%"><div class="text-center"><?=translate("Status", $this->session->userdata("language"))?></div></th>                             
                    <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>                                 
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>                            
    </div>
</div>



<?=form_close();?>
<div class="modal fade bs-modal-lg" id="popup_modal" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-md" style="width: 400px !important;">
       <div class="modal-content">
       </div>
   </div>
</div>