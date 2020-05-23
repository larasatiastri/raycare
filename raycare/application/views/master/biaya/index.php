<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-credit-card font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("biaya", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">

            <a href="<?=base_url()?>master/biaya/add" class="btn btn-circle btn-default"> <i class="fa fa-plus"></i> <span class="hidden-480"> <?=translate("Tambah", $this->session->userdata("language"))?>'</span> </a>

        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_biaya">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Nama Biaya", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Kategori Biaya", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tipe Biaya", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
