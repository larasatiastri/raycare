<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Kelompok", $this->session->userdata("language"))?></span>
		</div>
		 
	</div>
	<div class="portlet-body form">
		<div class="">
			<div class="form-group">
				<div class="col-md-12">

					 <div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="table_kelompok_penjamin">
							<thead>
							<tr role="row" class="heading">
								<th class="text-center"><?=translate("Nama Kelompok", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("URL", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("URL", $this->session->userdata("language"))?> </th>
								<th class="text-center" width="200"><input type="checkbox" id="checkall" name="checkall" class="make-switch"> <?=translate("Semua", $this->session->userdata("language"))?> </th>
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
