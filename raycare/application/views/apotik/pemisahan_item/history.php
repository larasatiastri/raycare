<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption font-blue-sharp bold uppercase"><?=translate("History", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1);"><i class="fa fa-reload"></i><?=translate("Kembali", $this->session->userdata("language"))?></a>
		</div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_history_pecah">
		<thead>
		<tr>
			<th class="text-center hidden"><?=translate("ID", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="12%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="10%"><?=translate("Dibuat oleh", $this->session->userdata("language"))?> </th>
			<th class="text-center" ><?=translate("Subjek", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="12%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="15%"><?=translate("Nama", $this->session->userdata("language"))?> </th>
			<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
		</tr>
		</thead>
		<tbody>
			
		</tbody>
		</table>
	</div>
</div>