<div class="portlet light">
<div class="portlet-title">
	<div class="caption">
		<i class="fa fa-history font-blue-sharp"></i>
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Histori Tindakan HD Pasien", $this->session->userdata("language"))?></span>
	</div>
	<div class="actions">
		<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
		<i class="fa fa-chevron-left"></i>
			<?=translate("Kembali", $this->session->userdata("language"))?>
		</a>
	</div>
	 
</div>
<div class="portlet-body">
	 
	<table class="table table-condensed table-striped table-bordered table-hover" id="table_cabang3">
		<thead>
			<tr role="row">
				<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No.Tindakan", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No. Pasien", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Nama Pasien", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Nama Dokter", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("BB pre HD", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("BB post HD", $this->session->userdata("language"))?> </th>
		 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		 	 
			</tr>
		</thead>
		<tbody>
			 
		</tbody>
	</table>
</div>
</div>

<div class="portlet light">
<div class="portlet-title">
	<div class="caption">
		<i class="fa fa-history font-blue-sharp"></i>
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Histori Tindakan Umum Pasien", $this->session->userdata("language"))?></span>
	</div>
	<div class="actions">
		<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
		<i class="fa fa-chevron-left"></i>
			<?=translate("Kembali", $this->session->userdata("language"))?>
		</a>
	</div>
	 
</div>
<div class="portlet-body">
	 
	<table class="table table-condensed table-striped table-bordered table-hover" id="table_tindakan_umum">
		<thead>
			<tr role="row">
				<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No.Tindakan", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("No. Pasien", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Nama Pasien", $this->session->userdata("language"))?> </th>
				<th class="text-center"><?=translate("Nama Dokter", $this->session->userdata("language"))?> </th>
		 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		 	 
			</tr>
		</thead>
		<tbody>
			 
		</tbody>
	</table>
</div>
</div>