<form id="satu" name="satu" class="horizontal" autocomplete="off">
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Kelompok Penjamin", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
	            <a href="<?=base_url()?>master/kelompok_penjamin/add" class="btn btn-primary">
	                <i class="fa fa-plus"></i>
	                <span class="hidden-480">
	                     <?=translate("Tambah", $this->session->userdata("language"))?>
	                </span>
	            </a>
	        </div>
		</div>
		<div class="portlet-body">
			<table class="table table-striped table-bordered table-hover" id="table_kelompok_penjamin">
				<thead>
					<tr role="row" class="heading">
						<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
						<th class="text-center"><?=translate("URL", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="200"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</form>
 