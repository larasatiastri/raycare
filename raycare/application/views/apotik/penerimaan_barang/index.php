<?php
	
	$form_attr = array(
	    "id"            => "form_index", 
	    "name"          => "form_index", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );

?>

<div class="portlet light">
	<div class="portlet">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Daftar Penerimaan Barang", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
				<a class="btn btn-default btn-circle" href="<?=base_url()?>apotik/penerimaan_barang/history">
					<i class="fa fa-history"></i> 
					<?=translate('History', $this->session->userdata('language'))?>
				</a>
			</div>
		</div>
		
		<div class="portlet-body">
	
			<table class="table table-striped table-bordered table-hover" id="table_penerimaan_barang">
				<thead>
				<tr class="heading">
					<th class="text-center"><?=translate("No. Surat Jalan", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Tanggal Kirim", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("Status", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
				</tr>
				</thead>
				<tbody>
				
				</tbody>
				<tfoot>
					
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?=form_close();?>

<div class="modal fade bs-modal-lg" id="popup_modal" role="basic" aria-hidden="true" style="margin-top:20px; margin-bottom:20px;">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-md" style="width: 480px !important;">
       <div class="modal-content">

       </div>
   </div>
</div>