<?php
	$form_attr = array(
	    "id"            => "form_grafik_hd", 
	    "name"          => "form_grafik_hd", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-bar-chart-o"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Grafik Pelayanan HD", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a id="cetak_csv" class="btn btn-primary" target="_blank" href="<?=base_url()?>laporan/grafik_hd_bulan/cetak_csv">
				<i class="fa fa-file-excel-o"></i>
				<?=translate("Download CSV", $this->session->userdata("language"))?>
			</a>
			
		</div>
	</div>
	<div class="portlet-body form">


				<div class="portlet solid blue-madison">
					<div class="portlet-title">
						<div class="caption">
							<?=translate("Filter", $this->session->userdata("language"))?>
						</div>
					</div>
					<div class="portlet-body">
					<div class="row">
						<div class="col-md-6">

								<div class="form-group">
									<label class="col-md-12"><?=translate("Periode Tindakan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
									<div class="col-md-12">
										<div class="input-group date">
											<input type="text" class="form-control" id="month_year" name="month_year" required  value="<?=date('M Y')?>" readonly >
											<span class="input-group-btn">
												<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
								</div>


						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-12"><?=translate("Penjamin", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<?php
										$penjamin_option = array(
											'0'			=> translate('Semua', $this->session->userdata('language')).'..',
											'2'			=> translate('BPJS', $this->session->userdata('language')),
											'1'			=> translate('Swasta', $this->session->userdata('language')),
										);

										$default = '0';
										if($this->session->userdata('level_id') == 37){
											$default = '2';
										}
										echo form_dropdown('penjamin', $penjamin_option, $default,'id="penjamin" class="form-control" ');
									?>
								</div>
							</div>
						</div>
					</div>


					</div>
				</div>


				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject uppercase">
						      <?=translate("Grafik", $this->session->userdata("language"))?>
						    </span>
							<span class="caption-helper" id="label_bulan"></span>
						</div>
						<div class="actions">
							<a class="btn default" id="refresh">
								<i class="fa fa-undo"></i>
								<?=translate("Refresh", $this->session->userdata("language"))?>
							</a>
						</div>
					</div>
					<div class="portlet-body">
					<style>
.container-fix {
  overflow-y: auto;
  height: 300px;
}
/*th {
  height: 0;
  line-height: 0;
  padding-top: 0;
  padding-bottom: 0;
  color: transparent;
  border: none;
  white-space: nowrap;
}
th{
  position: fixed;
  background: transparent;
  color: #fff;
  padding: 9px 25px;
  top: 50;
  margin-left: -25px;
  line-height: normal;
  border-left: 1px solid #800;
}
*/

					</style>
						<div class="row">
							<div class="col-md-6">
								<a id="label_pasien_last"><?=translate("Total Pasien", $this->session->userdata("language"))?> :</a>
								<div id="chart_3" class="chart">
                        		</div>
							</div>
							<div class="col-md-6">
								<a id="label_pasien_now"><?=translate("Total Pasien", $this->session->userdata("language"))?> :</a>
								<div id="chart_2" class="chart">
                       			</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="portlet box blue-sharp">
									<div class="portlet-title" style="margin-bottom: 0px !important;">
										<div class="caption">
											<span class="caption-subject uppercase">
										      <?=translate("List", $this->session->userdata("language"))?>
										    </span>
											<span class="caption-helper" id="label_list_bulan" style="color:#fff;">...</span>
										</div>
									</div>
									<div class="portlet-body">
									<div class="" style="height:681px;">
										<table class="table table-striped table-hover" id="table_tindakan_bulanan">
											<thead>
												<tr>
													<th class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?> </th>
													<th class="text-center"><?=translate("Hari", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("Tindakan", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("P", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("S", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("S", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("M", $this->session->userdata("language"))?></th>
												</tr>
											</thead>

											<tbody>
											</tbody>
											<tfoot>
												<th class="text-center" colspan="2">TOTAL</th>
												<th class="text-left" id="total_tindakan"></th>
												<th class="text-center" id="tindakan_pagi"></th>
												<th class="text-center" id="tindakan_siang"></th>
												<th class="text-center" id="tindakan_sore"></th>
												<th class="text-center" id="tindakan_mlm"></th>
											</tfoot>
										</table>
									</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="portlet box green-haze">
									<div class="portlet-title" style="margin-bottom: 0px !important;">
										<div class="caption">
											<span class="caption-subject uppercase">
										      <?=translate("Pasien Baru", $this->session->userdata("language"))?>
										    </span>
											<span class="caption-helper" id="label_list_baru" style="color:#fff;">...</span>
										</div>
									</div>
									<div class="portlet-body">
									<div class="container-fix">
										<table class="table table-striped table-hover" id="table_pasien_baru">
											<thead>
												<tr>
													<th class="text-center"><?=translate("No", $this->session->userdata("language"))?> </th>
													<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("Asal Klinik/Puskesmas", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("RS Rujukan", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("Marketing", $this->session->userdata("language"))?></th>
												</tr>
											</thead>

											<tbody>
											</tbody>
										</table>
									</div>
									</div>
								</div>
								<div class="portlet box yellow">
									<div class="portlet-title" style="margin-bottom: 0px !important;">
										<div class="caption">
											<span class="caption-subject uppercase" style="color:#000;">
										      <?=translate("Pasien Traveling", $this->session->userdata("language"))?>
										    </span>
											<span class="caption-helper" id="label_list_traveling" style="color:#000;">...</span>
										</div>
									</div>
									<div class="portlet-body">
										<div class="container-fix">
												<table class="table table-striped table-hover" id="table_pasien_traveling">
													<thead>
														<tr>
															<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
															<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
															<th class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></th>
															<th class="text-center"><?=translate("Akhir Durasi", $this->session->userdata("language"))?></th>
															<th class="text-center"><?=translate("RS Tujuan", $this->session->userdata("language"))?></th>
															<th class="text-center"><?=translate("Alasan", $this->session->userdata("language"))?></th>
															<th class="text-center"><?=translate("Status", $this->session->userdata("language"))?></th>
															<th class="text-center"><?=translate("Dokter", $this->session->userdata("language"))?></th>
														</tr>
													</thead>

													<tbody>
													</tbody>
												</table>
												</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="portlet box grey-gallery">
									<div class="portlet-title" style="margin-bottom: 0px !important;">
										<div class="caption">
											<span class="caption-subject uppercase" style="color:#fff;">
										      <?=translate("Pasien Meninggal", $this->session->userdata("language"))?>
										    </span>
											<span class="caption-helper" id="label_list_meninggal" style="color:#fff;">...</span>
										</div>
									</div>
									<div class="portlet-body">
									<div class="container-fix">
										<table class="table table-striped table-hover" id="table_pasien_meninggal">
											<thead>
												<tr>
													<th class="text-center"><?=translate("No", $this->session->userdata("language"))?> </th>
													<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("Tempat", $this->session->userdata("language"))?></th>
												</tr>
											</thead>

											<tbody>
											</tbody>
										</table>
									</div>
									</div>
								</div>
								<div class="portlet box red">
									<div class="portlet-title" style="margin-bottom: 0px !important;">
										<div class="caption">
											<span class="caption-subject uppercase">
										      <?=translate("Pasien Pindah", $this->session->userdata("language"))?>
										    </span>
											<span class="caption-helper" id="label_list_pindah" style="color:#fff;">...</span>
										</div>
									</div>
									<div class="portlet-body">
									<div class="container-fix">
										<table class="table table-striped table-hover" id="table_pasien_pindah">
											<thead>
												<tr>
													<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
													<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("RS Tujuan", $this->session->userdata("language"))?></th>
													<th class="text-center"><?=translate("Alasan", $this->session->userdata("language"))?></th>
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
</div>
<?=form_close()?>
<div class="modal fade bs-modal-lg" id="modal_laporan" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>