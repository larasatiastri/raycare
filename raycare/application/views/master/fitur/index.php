<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-credit-card font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Fitur", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">

            <a href="<?=base_url()?>master/fitur/add" class="btn btn-circle btn-default"> <i class="fa fa-plus"></i> <span class="hidden-480"> <?=translate("Tambah", $this->session->userdata("language"))?>'</span> </a>

        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_fitur">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Path", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
