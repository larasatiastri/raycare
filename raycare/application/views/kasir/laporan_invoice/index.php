<?php
	$form_attr = array(
	    "id"            => "form_laporan_invoice", 
	    "name"          => "form_laporan_invoice", 
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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Laporan Invoice", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="row">
	<div class="col-md-3">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Form Input", $this->session->userdata("language"))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<label class="col-md-12"><?=translate("Periode Tindakan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
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
					<div class="form-group">
						<label class="col-md-12"><?=translate("Penjamin", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
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
					<div class="form-group">
						<label class="col-md-12"><?=translate("Shift", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
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
					<div class="form-group">
						<label class="col-md-12"><?=translate("Resepsionis", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
						<div class="col-md-12">
							<?php
								$get_resepsionis= $this->user_m->get_by(array('user_level_id' => 19));
									// die_dump($get_kasir);
								$resepsionis_option = array(
									'0'			=> translate('Semua', $this->session->userdata('language')).'..',
								);

								$get_resepsionis = object_to_array($get_resepsionis);

								foreach ($get_resepsionis as $data) {
									$resepsionis_option[$data['id']] = $data['nama'];
								}

								echo form_dropdown('resepsionis', $resepsionis_option, '','id="resepsionis" class="form-control" ');
							?>
						</div>
					</div>
					<br>
					<div class="form-group">
						<div class="col-md-12">
							<a id="cari" class="btn btn-primary col-md-12" href="#"><i class="fa fa-search"></i> <?=translate("Cari", $this->session->userdata("language"))?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Laporan Invoice", $this->session->userdata("language"))?>
					</div>
				</div>
				<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover" id="table_laporan_invoice">
						<thead>
							<tr>
								<th class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Waktu", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("No. Invoice", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Resepsionis", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Penjamin", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("No. RM", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Pasien", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Tarif INA CBG", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Tarif RS", $this->session->userdata("language"))?></th>
							</tr>
						</thead>

						<tbody>
							
						</tbody>
							<tfoot>
				                <tr>
				                	<td class="text-right" colspan="7"><b><?=translate('Total', $this->session->userdata('language'))?> :</b></td>
					                <td class="text-right" >
					            	<b id="total_invoice_ina"></b>
					                </td>
					                <td class="text-right" >
					            	<b id="total_invoice"></b>
					                </td>
				              	</tr>
	                        </tfoot>
					</table>

				</div>
			</div>
			
		</div>
		
	</div>
</div>
<?=form_close()?>

