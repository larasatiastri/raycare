<?php
	
	$form_attr = array(
	    "id"            => "form_history", 
	    "name"          => "form_history", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );

?>

<div class="portlet light">
	<div class="portlet">
		<div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Penerimaan Barang", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
				<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
			        	<i class="fa fa-chevron-left"></i>
						<?=translate('Kembali', $this->session->userdata('language'))?>
			        </a>
			</div>
		</div>
		
		<div class="portlet-body">
	
			<table class="table table-striped table-bordered table-hover" id="table_history_penerimaan_barang">
				<thead>
				<tr class="heading">
					<th class="text-center" style="width: 1%;"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("No. PMB", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("No. Surat Jalan", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Supplier", $this->session->userdata("language"))?> </th>
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
