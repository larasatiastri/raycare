<?php
	$form_attr = array(
		"id"			=> "form_titip_terima_setoran", 
		"name"			=> "form_titip_terima_setoran", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
	);
	$hidden = array(
		"command"			=> "add_deposit", 
	);
	echo form_open(base_url()."keuangan/titip_terima_setoran/save", $form_attr, $hidden);

?>

<div class="tabbable-custom nav-justified">
    <ul class="nav nav-tabs nav-justified">
        <li class="active"><a href="#titip_setoran" data-toggle="tab"><?=translate("Pengajuan Saldo", $this->session->userdata("language"))?> </a></a></li>
        <li><a href="#terima_setoran" data-toggle="tab"><?=translate("Penerimaan Saldo", $this->session->userdata("language"))?> </a></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="titip_setoran">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pengajuan Saldo", $this->session->userdata("language"))?></span>
                    </div>
                    <div class="actions">
                        <a href="<?=base_url()?>keuangan/titip_terima_setoran/add" class="btn btn-circle btn-default">
                            <i class="fa fa-plus"></i>
                            <span>
                                 <?=translate("Tambah", $this->session->userdata("language"))?>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table_titip_setoran">
                        <thead>
                            <tr>
                                <th width="3%"><div class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></div></th>
                                <th><div class="text-center"><?=translate("Diberikan Oleh", $this->session->userdata("language"))?></div></th>
                                <th><div class="text-center"><?=translate("Diterima Oleh", $this->session->userdata("language"))?></div></th>
                                <th><div class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?></div></th>
                                <th><div class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></div></th>
                                <th><div class="text-center"><?=translate("Jumlah Biaya", $this->session->userdata("language"))?></div></th>                                 
                                <th><div class="text-center"><?=translate("Sisa Saldo", $this->session->userdata("language"))?></div></th>                                 
                                <th width="1%"><div class="text-center"><?=translate("Status", $this->session->userdata("language"))?></div></th>                                 
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>                            
                </div>
            </div>
        </div>
        <div class="tab-pane" id="terima_setoran">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penerimaan Saldo", $this->session->userdata("language"))?></span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table_terima_setoran">
                        <thead>
                            <tr>
                                <th width="1%"><div class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></div></th>
                                <th ><div class="text-center"><?=translate("Diberikan Oleh", $this->session->userdata("language"))?></div></th>
                                <th ><div class="text-center"><?=translate("Diterima Oleh", $this->session->userdata("language"))?></div></th>
                                <th ><div class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?></div></th>
                                <th ><div class="text-center"><?=translate("Rupiah", $this->session->userdata("language"))?></div></th>                                 
                                <th width="2%"><div class="text-center"><?=translate("Status", $this->session->userdata("language"))?></div></th>                                   
                                <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>                                   
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>                            
                </div>
            </div>
        </div>
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
<div class="modal fade bs-modal-sm" id="popup_modal_verif" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-sm">
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