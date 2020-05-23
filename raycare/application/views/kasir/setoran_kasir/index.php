<?php
	$form_attr = array(
	    "id"            => "form_laporan_invoice", 
	    "name"          => "form_laporan_invoice", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-file font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Setoran Kasir", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>kasir/setoran_kasir/add" class="btn green">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-hover" id="table_setoran_kasir">
			<thead>
				<tr>
					<th class="text-center"><?=translate("Tanggal Setor", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Shift", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Jenis", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Bank", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Kasir", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Nomor Bukti Setor", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Rupiah", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
				
			</tbody>
		</table>
	</div>
</div>
<?=form_close()?>

