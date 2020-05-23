<?php
	$form_attr = array(
	    "id"            => "form_rekap_pengeluaran_obat_alkes", 
	    "name"          => "form_rekap_pengeluaran_obat_alkes", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
   
    echo form_open("", $form_attr);
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-file font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Pengeluaran Obat & Alkes", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="row">
	<div class="col-md-12">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Form Pencarian", $this->session->userdata("language"))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="row">
						<div class="col-md-3">
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-12 bold"><?=translate("Periode Tindakan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
									<div class="col-md-12">
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
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Penjamin", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
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
						<div class="col-md-3">
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Shift", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
								<div class="col-md-12">
									<?php
										$shift_option = array(
											'0'			=> translate('Semua', $this->session->userdata('language')).'..',
											'1'			=> translate('Shift 1', $this->session->userdata('language')),
											'2'			=> translate('Shift 2', $this->session->userdata('language')),
											'3'			=> translate('Shift 3', $this->session->userdata('language')),
											'4'			=> translate('Shift 4', $this->session->userdata('language')),
										);
										echo form_dropdown('shift', $shift_option, '','id="shift" class="form-control" ');
									?>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="col-md-12 bold" style="color:#fff;"><?=translate(".", $this->session->userdata("language"))?></label>
								<div class="col-md-12">
									<a id="cari" class="btn btn-primary col-md-12" href="#"><i class="fa fa-search"></i> <?=translate("Cari", $this->session->userdata("language"))?></a>
								</div>
							</div>
						</div>
					</div>
					
					
					
					
					<br>
					
				</div>
			</div>
		</div>

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
		<div class="col-md-12">
			<div class="portlet box green-haze">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Pengeluaran Obat Alkes", $this->session->userdata("language"))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="container-fix">
						<table class="table table-striped table-bordered table-hover" id="table_rekap_pengeluaran_obat_alkes">
							<thead>
								<tr>
									<th class="text-center" width="1%"><?=translate("No.", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("No. Pengeluaran", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Penjamin", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("No. RM", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
									<th class="text-right"><?=translate("Nominal", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Rincian", $this->session->userdata("language"))?></th>
								</tr>
							</thead>

							<tbody>
								
							</tbody>
								
						</table>
					</div>
					

				</div>
			</div>
			
		</div>
		<div class="col-md-6">
			<div class="portlet box blue">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Rekapan Pengeluaran Obat & Alkes", $this->session->userdata("language"))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="container-fix">
						<table class="table table-striped table-bordered table-hover" id="table_obat_alkes">
							<thead>
								<tr>
									<th class="text-center" width="1%"><?=translate("No.", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Nama Item", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Satuan", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Total", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Diskon", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("SubTotal", $this->session->userdata("language"))?></th>
								</tr>
							</thead>

							<tbody>
								
							</tbody>
								
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="portlet box green">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Daftar Pasien", $this->session->userdata("language"))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="container-fix">
						<table class="table table-striped table-bordered table-hover" id="table_pasien">
							<thead>
								<tr>
									<th class="text-center" width="1%"><?=translate("No.", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Tgl", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Penjamin", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("No. RM", $this->session->userdata("language"))?></th>
									<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
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

