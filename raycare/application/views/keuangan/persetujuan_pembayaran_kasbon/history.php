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
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Persetujuan Pembayaran Kasbon", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
				<a class="btn btn-primary btn-circle" href="<?=base_url()?>keuangan/persetujuan_pembayaran_kasbon">
					<i class="fa fa-chevron-left"></i> 
					<?=translate('Kembali', $this->session->userdata('language'))?>
				</a>
			</div>
		</div>
		
		<div class="portlet-body">
			<table class="table table-striped table-bordered table-hover" id="table_persetujuan_pembayaran_kasbon_history">
				<thead>
				<tr class="heading">
					<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("Rupiah", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Status", $this->session->userdata("language"))?> </th>
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