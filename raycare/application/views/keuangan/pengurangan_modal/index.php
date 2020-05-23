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
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-money font-blue-sharp"></i> 
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pengurangan Modal", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			
			<a class="btn btn-default btn-circle" href="<?=base_url()?>keuangan/pengurangan_modal/add">
				<i class="fa fa-plus"></i> 
				<?=translate('Tambah', $this->session->userdata('language'))?>
			</a>
		</div>
	</div>
	
	<div class="portlet-body">

		<table class="table table-striped table-bordered table-hover" id="table_pengurangan_modal">
			<thead>
			<tr>
				<th class="text-center" width="10%"><?=translate("No.Permintaan", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="5%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Nominal", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Keperluan", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
			</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
	</div>
</div>