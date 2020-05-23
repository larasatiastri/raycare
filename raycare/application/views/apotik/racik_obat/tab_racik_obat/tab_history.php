<div class="portlet light" style="margin-top : 20px;">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Resep", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_resep_history">
			<thead>
				<tr>
					<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
					<th class="text-center" style="width : 150px !important;"><?=translate("No.Batch", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 150px !important;"><?=translate("Nama", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 150px !important;"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 150px !important;"><?=translate("Dibuat Oleh", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 250px !important;"><?=translate("Dokter", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 250px !important;"><?=translate("Pasien", $this->session->userdata("language"))?></th>
					<th class="text-center" style="width : 200px !important;"><?=translate("Jumlah Produksi", $this->session->userdata("language"))?></th>
					<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?></th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
</div>
<div id="popover_komposisi_history_content" class="row">
	<div class="portlet" style="padding-left : 10px; padding-right : 10px;">
		<!-- <div class="portlet-title">
			<div class="caption">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Komposisi", $this->session->userdata("language"))?></span>
			</div>
		</div> -->
		<div class="portlet-body">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#komposisi_item_history" data-toggle="tab">
					<?=translate('Komposisi Item', $this->session->userdata('language'))?> </a>
				</li>
				<li>
					<a href="#komposisi_manual_history" data-toggle="tab">
					<?=translate('Komposisi Manual', $this->session->userdata('language'))?> </a>
				</li>

			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="komposisi_item_history">
					<div class="col-md-12">
				    	<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Komposisi Item", $this->session->userdata("language"))?></span>
								</div>
							</div>
							<div class="portlet-body">
						        <table class="table table-condensed table-striped table-bordered table-hover" id="table_komposisi_item_history">
						            <thead>
						                <tr role="row">
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
				<div class="tab-pane" id="komposisi_manual_history">
					<div class="col-md-12">
				    	<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Komposisi Manual", $this->session->userdata("language"))?></span>
								</div>
							</div>
							<div class="portlet-body">
						        <table class="table table-condensed table-striped table-bordered table-hover" id="table_komposisi_manual_history">
						            <thead>
						                <tr role="row">
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