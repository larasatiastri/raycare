
	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<!-- <i class="fa fa-cogs font-green-sharp"></i> -->
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("History Daftar Antrian Pasien", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
				<a class="btn btn-circle btn-default" href="<?=base_url()?>klinik_hd/antrian_tensi_bb">
					<i class="fa fa-chevron-left"></i>
					<?=translate('Kembali', $this->session->userdata('language'))?>
				</a>
				<div class="btn-group">
					<a class="btn green-haze btn-circle" href="javascript:;" data-toggle="dropdown">
					<!--<i class="fa fa-check-circle"></i>-->
					 Columns <i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu pull-right" id="combo_history">
						<li>
							<label><input type="checkbox" data-column="1">No. RM</label>
						</li>
						<li>
							<label><input type="checkbox" data-column="3">Alamat</label>
						</li>
					</ul>
				</div>
				
	        </div>
		</div>
		<div class="portlet-body">
			<table class="table table-condensed table-striped table-bordered table-hover" id="table_antrian_tensi_history">
				<thead>
					<tr role="row">
						<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="1%"><?=translate("No. RM", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="15%"><?=translate("Pasien", $this->session->userdata("language"))?> </th>
						<th class="text-center" width="30%"><?=translate("Alamat", $this->session->userdata("language"))?> </th>
						<th class="text-center"width="10%"><?=translate("BB", $this->session->userdata("language"))?> </th>
						<th class="text-center"width="20%"><?=translate("TD", $this->session->userdata("language"))?> </th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div><!-- akhir dari portlet -->