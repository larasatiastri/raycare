<div class="portlet light" style="margin-top : 20px;">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Resep", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_resep">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="15%"><?=translate("Dokter", $this->session->userdata("language"))?></th>
					<th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center" width="15"><?=translate("Nama", $this->session->userdata("language"))?></th>
					<th class="text-center" width="5%"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
					<th class="text-center" width="5%"><?=translate("komposisi", $this->session->userdata("language"))?></th>
					<th class="text-center" width="44%"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>

<div class="portlet light" style="margin-top : 20px;">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Resep Manual", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_resep_manual">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center" width="15%"><?=translate("Dokter", $this->session->userdata("language"))?></th>
					<th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center" width="69%"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
<div id="popover_komposisi_content" class="row">
	<div class="portlet">
		<div class="portlet-body">
			<div class="col-md-12">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#komposisi_item" data-toggle="tab">
						<?=translate('Komposisi Item', $this->session->userdata('language'))?> </a>
					</li>
					<li>
						<a href="#komposisi_manual" data-toggle="tab">
						<?=translate('Komposisi Manual', $this->session->userdata('language'))?> </a>
					</li>
				</ul>
			</div>
			<div class="tab-content">
				<div class="tab-pane active" id="komposisi_item">
					<div class="col-md-12">
						<div class="portlet-body">
					        <table class="table table-condensed table-striped table-bordered table-hover" id="table_komposisi_item">
					            <thead>
					                <tr role="row">
					                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
					                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
					                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
					                    <th><div class="text-center" width="1%"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
					                </tr>
					            </thead>
					            <tbody>
					            </tbody>
					        </table>
					    </div>
				    </div>
				</div>
				<div class="tab-pane" id="komposisi_manual">
					<div class="col-md-12">
						<div class="portlet-body">
					        <table class="table table-condensed table-striped table-bordered table-hover" id="table_komposisi_manual">
					            <thead>
					                <tr role="row">
					                    <th class="text-center"><?=translate('ID', $this->session->userdata('language'))?></th>
					                    <th class="text-center" width="1%"><?=translate('No', $this->session->userdata('language'))?></th>
					                    <th class="text-center" width="90%"><?=translate('Keterangan', $this->session->userdata('language'))?></th>
					                </tr>
					            </thead>
					            <tbody>
					            </tbody>
					        </table>
					    </div>
				    </div>
				</div>
			</div>
		</div>
	</div>
    

</div>