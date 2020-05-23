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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Grafik Pelayanan HD", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a id="cetak_csv" class="btn btn-circle btn-primary" target="_blank" href="<?=base_url()?>laporan/grafik_hd_bulan/cetak_csv">
				<i class="fa fa-file-excel-o"></i>
				<?=translate("Download CSV", $this->session->userdata("language"))?>
			</a>
			<a href="#" class="btn btn-circle btn-default btn-icon-only fullscreen" data-original-title="" title=""></a>
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
							<a class="btn btn-circle btn-default" id="refresh">
								<i class="fa fa-undo"></i>
								<?=translate("Refresh", $this->session->userdata("language"))?>
							</a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="row">
							<div class="col-md-6">
								<a id="label_pasien_last"><?=translate("Total Pasien", $this->session->userdata("language"))?> :</a>
								<div id="chart_2" class="chart">
                        		</div>
							</div>
							<div class="col-md-6">
								<div class="portlet-title" style="margin-bottom: 0px !important;">
									<div class="caption">
										<span class="caption-subject uppercase">
									      <?=translate("List", $this->session->userdata("language"))?>
									    </span>
										<span class="caption-helper" id="label_list_bulan" style="color:#fff;">...</span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-striped table-hover" id="table_tindakan_bulanan">
										<thead>
											<tr>
												<th class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?> </th>
												<th class="text-center"><?=translate("Hari", $this->session->userdata("language"))?></th>
												<th class="text-center"><?=translate("P", $this->session->userdata("language"))?></th>
												<th class="text-center"><?=translate("S", $this->session->userdata("language"))?></th>
												<th class="text-center"><?=translate("S", $this->session->userdata("language"))?></th>
												<th class="text-center"><?=translate("M", $this->session->userdata("language"))?></th>
												<th class="text-center"><?=translate("Tindakan", $this->session->userdata("language"))?></th>
												<th class="text-center"><?=translate("Nominal", $this->session->userdata("language"))?></th>
											</tr>
										</thead>

										<tbody>
										</tbody>
										<tfoot>
											<th class="text-center" colspan="2">TOTAL</th>
											<th class="text-center" id="tindakan_pagi"></th>
											<th class="text-center" id="tindakan_siang"></th>
											<th class="text-center" id="tindakan_sore"></th>
											<th class="text-center" id="tindakan_mlm"></th>
											<th class="text-left" id="total_tindakan"></th>
											<th class="text-left" id="total_nominal"></th>

										</tfoot>
									</table>
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