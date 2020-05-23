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
	echo form_open(base_url()."reservasi/titip_terima_setoran/save", $form_attr, $hidden);

    $td_filter = '<tr role="row" class="filter"> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"></div></td> 
                    <td><div class="text-center"> 
                        <select name="order_status" id="status" class="form-control form-filter input-sm order_status"> 
                            <option value="0">'.translate("Semua", $this->session->userdata("language")).'</option> 
                            <option value="1">'.translate("Belum Diterima", $this->session->userdata("language")).'</option> 
                            <option value="2">'.translate("Sudah Diterima", $this->session->userdata("language")).'</option> 
                        </select> </div> 
                    </td> 
                </tr>';

?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light">
			<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Titip Setoran", $this->session->userdata("language"))?></span>
                </div>
                <div class="actions">
                	&nbsp;
                </div>
                <div class="actions">
                    <a href="<?=base_url()?>reservasi/titip_terima_setoran/add" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
                             <?=translate("Tambah", $this->session->userdata("language"))?>
                        </span>
                    </a>
                </div>
            </div>

            
            <div class="portlet-body">
            <div class="form-group"></div>
            <div class="caption">
            	<label class="control-label"><h4></h4></label>
            </div>
                <div id="thead-filter-template" class="hidden"><?=htmlentities($td_filter)?></div>
            	<table class="table table-striped table-bordered table-hover" id="table_titip_setoran">
					<thead>
						<tr role="row" class="heading">
                            <th scope="col" width="8%"><div class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Diberikan Oleh", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Diterima Oleh", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Rupiah", $this->session->userdata("language"))?></div></th>                                 
                            <th scope="col" ><div class="text-center"><?=translate("Status", $this->session->userdata("language"))?></div></th>		                            
                        </tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>		            		
            </div>
		</div>

		<div class="portlet light">
			<div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Terima Setoran", $this->session->userdata("language"))?></span>
                </div>
            </div>
            <div class="portlet-body">
            <div class="form-group"></div>
            <div class="caption">
            	<label class="control-label"><h4></h4></label>
            </div>
            	<table class="table table-striped table-bordered table-hover" id="table_terima_setoran">
					<thead>
						<tr role="row" class="heading">
                            <th scope="col" width="8%"><div class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Diberikan Oleh", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Diterima Oleh", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></div></th>
                            <th scope="col" ><div class="text-center"><?=translate("Rupiah", $this->session->userdata("language"))?></div></th>                                 
                            <th scope="col" ><div class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></div></th>		                            
                        </tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>		            		
            </div>
		</div>
	</div>
</div>

<?=form_close();?>
<?php $this->load->view('reservasi/titip_terima_setoran/info.php'); ?> 
<div class="modal fade bs-modal-lg" id="popup_modal" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
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