<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<!-- <i class="fa fa-cogs font-blue-sharp"></i> -->
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Data Batch", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a href="<?=base_url()?>master/paket" class="btn default">
                <i class="fa"></i>
                <span class="hidden-480">
                     <?=translate("Kembali", $this->session->userdata("language"))?>
                </span>
            </a>
            <a href="<?=base_url()?>master/paket/add_batch/<?=$pk?>" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>

	 <div class="form-group hidden">
		<label class="control-label col-md-3"><?=translate("Paket_ID :", $this->session->userdata("language"))?></label>		
		<div class="col-md-2">
			<?php
				$paket_id = array(
					"name"			=> "paket_id",
					"id"			=> "paket_id",
					"autofocus"		=> true,
					"class"			=> "form-control", 
					"placeholder"	=> translate("Paket_id", $this->session->userdata("language")), 
					"value"			=> $pk,
				);
				echo form_input($paket_id);
			?>
		</div>
	</div>

	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_batch">
		<thead>
		<tr class="heading">
			<th class="text-center"><?=translate("Tipe", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Nama Batch", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Order", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		</tr>
		</thead>
		<tbody>
		
		</tbody>
		</table>
	</div>
</div>