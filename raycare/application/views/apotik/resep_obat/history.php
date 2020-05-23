<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Resep Obat", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>apotik/resep_obat" class="btn btn-default btn-circle">
                <i class="fa fa-chevron-left"></i>
                <span class="hidden-480">
                     <?=translate("Kembali", $this->session->userdata("language"))?>
                </span>
            </a>
	           
    </div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-4">
				<table class="table table-striped table-bordered table-hover" id="table_box_paket_history">
					<thead>
						<tr class="heading">
							<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("No. Permintaan", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Bed", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?></th>
							<th class="text-center" style="width : 100px !important;"><?=translate("Kode Box", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Diproses Oleh", $this->session->userdata("language"))?></th>
						</tr>	
					</thead>

					<tbody>
					</tbody>
				</table>
			</div>
			<div class="col-md-8">
				<table class="table table-striped table-bordered table-hover" id="table_resep_obat_history">
					<thead>
						<tr class="heading">
							<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("No. Resep", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?></th>
							<th class="text-center" style="width : 100px !important;"><?=translate("Item", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Diproses Oleh", $this->session->userdata("language"))?></th>
							<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></th>
						</tr>	
					</thead>

					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
