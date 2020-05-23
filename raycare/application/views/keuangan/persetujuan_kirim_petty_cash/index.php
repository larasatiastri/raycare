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
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Persetujuan Pengisian Petty Cash", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions hidden">
				<a class="btn btn-primary btn-circle" href="<?=base_url()?>keuangan/persetujuan_kirim_petty_cash/history">
					<i class="fa fa-undo"></i> 
					<?=translate('History', $this->session->userdata('language'))?>
				</a>
			</div>
		</div>
		
		<div class="portlet-body">
			<table class="table table-striped table-bordered table-hover" id="table_persetujuan_kirim_petty_cash">
				<thead>
				<tr class="heading">
					<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="10%"><?=translate("Nominal", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Subjek", $this->session->userdata("language"))?> </th>
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