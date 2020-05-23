<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs font-green-sharp"></i>
			<span class="caption-subject font-green-sharp bold uppercase"><?=translate("Data Cabang", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>master/cabang_/add" class="btn green">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_cabang">
		<thead>
		<tr class="heading">
			<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Kode Cabang", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Nama Cabang", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Alamat", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Telepon 1", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Telepon 2", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Fax", $this->session->userdata("language"))?> </th>
			<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		</tr>
		</thead>
		<tbody>
		
		</tbody>
		</table>
	</div>
</div>