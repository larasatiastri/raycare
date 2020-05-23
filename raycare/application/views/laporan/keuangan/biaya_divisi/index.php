<?php
	$form_attr = array(
	    "id"            => "form_grafik_biaya", 
	    "name"          => "form_grafik_biaya", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);

    $divisi = $this->divisi_m->get_by(array('is_active' => 1));
    $divisi = object_to_array($divisi);

    $divisi_option = array();

    foreach ($divisi as $row_divisi) {
    	$divisi_option[$row_divisi['id']] = $row_divisi['kode'].' | '.$row_divisi['nama'];
    }
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Grafik Laporan Biaya Per Divisi", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions hidden">
			<a id="cetak_csv" class="btn btn-circle btn-primary" target="_blank" href="<?=base_url()?>laporan/keuangan/biaya/cetak_csv">
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
							<label class="col-md-12"><?=translate("Periode", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
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
							<label class="col-md-12"><?=translate("Divisi", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<?php
									echo form_dropdown('divisi_id',$divisi_option,'','id="divisi_id" class="form-control"');

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
				
			</div>
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-6">
						<div id="chart_3" class="chart">
                		</div>
					</div>
					<div class="col-md-6">
						<div id="chart_2" class="chart">
               			</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject uppercase">
								      <?=translate("Biaya Per Tanggal", $this->session->userdata("language"))?>
								    </span>
									<span class="caption-helper" id="label_list_bulan">...</span>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-striped table-bordered table-hover" id="table_biaya_tanggal">
									<thead>
										<tr>
											<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("User", $this->session->userdata("language"))?></th>
											<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
										</tr>
									</thead>

									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject uppercase">
								      <?=translate("Biaya Per Kategori", $this->session->userdata("language"))?>
								    </span>
									<span class="caption-helper" id="label_list_kategori">...</span>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-striped table-bordered table-hover" id="table_biaya_kategori">
									<thead>
										<tr>
											<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("Biaya", $this->session->userdata("language"))?></th>
											<th class="text-center"><?=translate("User", $this->session->userdata("language"))?></th>
											<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
										</tr>
									</thead>

									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject uppercase">
								      <?=translate("Biaya Per User", $this->session->userdata("language"))?>
								    </span>
									<span class="caption-helper" id="label_list_user">...</span>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-striped table-bordered table-hover" id="table_biaya_user">
									<thead>
										<tr>
											<th class="text-center" width="1%"><?=translate("No", $this->session->userdata("language"))?> </th>
											<th class="text-center"><?=translate("User", $this->session->userdata("language"))?></th>
											<th class="text-center"><?=translate("Rupiah", $this->session->userdata("language"))?></th>
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
<?=form_close()?>
