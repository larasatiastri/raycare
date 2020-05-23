<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Permintaan Dialyzer Baru", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>apotik/permintaan_dialyzer_baru" class="btn btn-default btn-circle">
                <i class="fa fa-chevron-left"></i>
                <span class="hidden-480">
                     <?=translate("Kembali", $this->session->userdata("language"))?>
                </span>
            </a>  
    	</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_permintaan_dialyzer_baru_history">
			<thead>
				<tr class="heading">
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Cabang", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("No. Permintaan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Diminta Oleh", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Waktu Permintaan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Waktu Tunggu", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Bed", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Apoteker", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Diberikan Ke", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Dialyzer", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("BN", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("ED", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
