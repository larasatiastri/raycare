<div class="portlet light" style="margin-top : 20px;">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Resep", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_resep">
			<thead>
				<tr class="heading">
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center" style="width : 150px !important;"><?=translate("Dokter", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 150px !important;"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 150px !important;"><?=translate("Nama", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 150px !important;"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 150px !important;"><?=translate("komposisi", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 400px !important;"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></th>
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
				<tr class="heading">
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Dokter", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
					<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
<div id="popover_komposisi_content" class="row">
	<div class="portlet" style="padding-left : 10px; padding-right : 10px;">
		<!-- <div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Komposisi", $this->session->userdata("language"))?></span>
			</div>
		</div> -->
		<div class="portlet-body">
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
			<div class="tab-content">
				<div class="tab-pane active" id="komposisi_item">
					<div class="col-md-12">
				    	<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Komposisi Item", $this->session->userdata("language"))?></span>
								</div>
							</div>
							<div class="portlet-body">
						        <table class="table table-condensed table-striped table-bordered table-hover" id="table_komposisi_item">
						            <thead>
						                <tr role="row" class="heading">
						                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
						                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
						                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
						                    <th><div class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
						                </tr>
						            </thead>
						            <tbody>
						            </tbody>
						        </table>
						    </div>
						</div>
				    </div>
				</div>
				<div class="tab-pane" id="komposisi_manual">
					<div class="col-md-12">
				    	<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Komposisi Manual", $this->session->userdata("language"))?></span>
								</div>
							</div>
							<div class="portlet-body">
						        <table class="table table-condensed table-striped table-bordered table-hover" id="table_komposisi_manual">
						            <thead>
						                <tr role="row" class="heading">
						                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
						                    <th style="width : 150px !important;"><div class="text-center"><?=translate('No', $this->session->userdata('language'))?></div></th>
						                    <th style="width : 500px !important;"><div class="text-center"><?=translate('Keterangan', $this->session->userdata('language'))?></div></th>
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
    

</div>