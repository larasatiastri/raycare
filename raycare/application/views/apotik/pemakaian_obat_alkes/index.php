<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-user font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pemakaian Obat & Alkes untuk Tindakan", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>apotik/pemakaian_obat_alkes" class="btn btn-circle btn-default">
                <i class="fa fa-chevron-left"></i>
                <span class="hidden-480">
                     <?=translate("Kembali", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_pemakaian_obat_alkes">
			<thead>
				<tr>
					<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Tindakan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Shift", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Obat & Alkes", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Apoteker", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Penerima", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
