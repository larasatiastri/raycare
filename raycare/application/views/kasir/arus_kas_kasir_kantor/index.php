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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Arus kas kasir", $this->session->userdata("language"))?></span>
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
							<label class="col-md-12"><?=translate("Periode Kas", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
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
			</div>
		</div>
		<div class="col-md-12">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate("Arus Kas Kasir", $this->session->userdata("language"))?>
					</div>
				</div>
				<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover" id="table_arus_kas_kasir">
						<thead>
						<tr>
							<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="10%"><?=translate("Tgl", $this->session->userdata("language"))?> </th>
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