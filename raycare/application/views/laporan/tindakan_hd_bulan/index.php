<?php
	$form_attr = array(
	    "id"            => "form_laporan_hd", 
	    "name"          => "form_laporan_hd", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Laporan Tindakan HD Per Bulan", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a id="cetak_pdf" class="btn btn-circle btn-primary" target="_blank" href="<?=base_url()?>laporan/tindakan_hd_bulan/cetak_pdf">
				<i class="fa fa-file-excel-o"></i>
				<?=translate("Download CSV", $this->session->userdata("language"))?>
			</a>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="row">
			<div class="col-md-12">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<?=translate("Form Input", $this->session->userdata("language"))?></span>
						</div>
					</div>
					<div class="portlet-body">
						<div class="form-body">
							<div class="form-group">
								<label class="col-md-2"><?=translate("Periode Tindakan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-6">
									<div id="reportrange" class="btn default">
										<i class="fa fa-calendar"></i>
										&nbsp; <span>
										</span>
										<b class="fa fa-angle-down"></b>
									</div>
									<input type="hidden" class="form-control" id="tgl_awal" name="tgl_awal"></input>
									<input type="hidden" class="form-control" id="tgl_akhir" name="tgl_akhir"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2"><?=translate("Penjamin", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-6">
									<?php
										$penjamin_option = array(
											'0'			=> translate('Semua', $this->session->userdata('language')).'..',
											'2'			=> translate('BPJS', $this->session->userdata('language')),
											'1'			=> translate('Swasta', $this->session->userdata('language')),
										);
										echo form_dropdown('penjamin', $penjamin_option, '','id="penjamin" class="form-control" ');
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="actions">
							<a class="btn btn-circle btn-default" id="refresh">
								<i class="fa fa-undo"></i>
								<?=translate("Refresh", $this->session->userdata("language"))?>
							</a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover" id="table_tindakan_bulanan">
							<thead>
								<tr>
									<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="1%"><?=translate("Tgl", $this->session->userdata("language"))?> </th>
									<th class="text-center" width="2%"><?=translate("RM", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Nama", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Waktu", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Pelayanan", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Assesment", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Med. Diagnose", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("QB", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("QD", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("UFG", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Heparin", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Dose", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("First", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Maint", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Mesin", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Jenis Dialyzer", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Dialyzer", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Blood Access", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Problem", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Komplikasi", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Pengirim", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Peny. Bawaan", $this->session->userdata("language"))?></th>
									<th class="text-center" width="1%"><?=translate("Peny. Penyebab", $this->session->userdata("language"))?></th>
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
<?=form_close()?>
