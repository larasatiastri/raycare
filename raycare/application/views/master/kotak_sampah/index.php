<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
			<span class="caption-subject font-green-sharp bold uppercase"><?=translate("Kotak Sampah", $this->session->userdata("language"))?></span>
		</div>
		<!-- <div class="actions">
            <a href="<?=base_url()?>master/cabang_/add" class="btn green">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div> -->
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_kotak_sampah">
		<thead>
			<tr class="heading">
				<th class="text-center" width="300"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="500"><?=translate("Tipe Data", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("User", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
			</tr>
		</thead>
		<tbody>
		
		</tbody>
		</table>
	</div>
</div>