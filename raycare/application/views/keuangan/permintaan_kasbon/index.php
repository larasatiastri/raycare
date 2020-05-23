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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Permintaan Kasbon", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="<?=base_url()?>keuangan/permintaan_kasbon/history">
				<i class="fa fa-history"></i> 
				<?=translate('History', $this->session->userdata('language'))?>
			</a>
		</div>
	</div>
	
	<div class="portlet-body">

		<table class="table table-striped table-hover" id="table_permintaan_kasbon">
			<thead>
			<tr>
				<th class="text-center" width="10%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Jenis", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("No. Permintaan", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Rupiah", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Posisi", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Waktu Akhir", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
			</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
	</div>
</div>
