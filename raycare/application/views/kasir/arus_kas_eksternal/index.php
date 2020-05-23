<?php
	
	$form_attr = array(
	    "id"            => "form_index", 
	    "name"          => "form_index", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    echo form_open("", $form_attr);

?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-exchange font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Arus Kas Eksternal", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="row">
        <div class="col-md-3">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Filter", $this->session->userdata("language"))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<label class="col-md-12"><?=translate("Periode", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
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
						<div class="form-group">
							<label class="col-md-12"><?=translate("Kasir", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
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

									echo form_dropdown('kasir_id', $resepsionis_option, '','id="kasir_id" class="form-control" ');
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Daftar Arus Kas Eksternal", $this->session->userdata("language"))?>
					</div>
				</div>
				<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover" id="table_arus_kas_eksternal">
						<thead>
						<tr>
							<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="10%"><?=translate("Tanggal", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Kasir", $this->session->userdata("language"))?> </th>
							<th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
							<th class="text-center" style="width: 120px;"><?=translate("Debit", $this->session->userdata("language"))?> </th>
							<th class="text-center" style="width: 120px;"><?=translate("Kredit", $this->session->userdata("language"))?> </th>
							<th class="text-center" style="width: 120px;"><?=translate("Saldo", $this->session->userdata("language"))?> </th>
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
<?=form_close()?>