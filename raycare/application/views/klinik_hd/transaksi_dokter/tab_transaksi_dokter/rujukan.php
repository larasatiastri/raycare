 
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<?=translate("Daftar Rujukan", $this->session->userdata("language"))?>
		</div>
		<div class="actions">
			 <a   class="btn btn-circle btn-icon-only btn-default" id="refrsh">
                <i class="fa fa-refresh"></i>
                
            </a>
            <a href="<?=config_item('url_core')?>reservasi/rujukan" class="btn btn-circle btn-icon-only btn-default" target="_blank">
                <i class="fa fa-plus"></i>
            </a>
        </div>
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="table_rujukan">
			<thead>
				<tr role="row">
					<th class="text-center"><?=translate("Poliklinik Asal", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Poliklinik Tujuan", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Tanggal Dirujuk", $this->session->userdata("language"))?> </th>
					<th class="text-center"><?=translate("Tanggal Rujukan", $this->session->userdata("language"))?> </th>		 	 
				</tr>
			</thead>
			<tbody>
			 
			</tbody>
		</table>
	</div>
</div>

 
 