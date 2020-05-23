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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pengeluaran Kas Eksternal", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-default btn-circle" href="<?=base_url()?>keuangan/pengeluaran_kas_eksternal/add">
				<i class="fa fa-plus"></i> 
				<?=translate('Tambah', $this->session->userdata('language'))?>
			</a>
		</div>
	</div>
	
	<div class="portlet-body">

		<table class="table table-striped table-bordered table-hover" id="table_pengeluaran_kas_eksternal">
			<thead>
			<tr>
				<th class="text-center" width="10%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Dikeluarkan Oleh", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="10%"><?=translate("Rupiah", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Keperluan", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
			</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
	</div>
</div>