<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Surat Data Kelayakan Anggota', $this->session->userdata('language'))?></span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Kode Cabang", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-5">
				<label class="control-label"><?=$form_data['ref_kode_cabang']?></label>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Kode RS Rujukan", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-5">
				<label class="control-label"><?=$form_data['ref_kode_rs_rujukan']?></label>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Tanggal Rujukan", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-5">
				<label class="control-label"><?=date('d F Y', strtotime($form_data['ref_tanggal_rujukan']))?></label>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Nomer Rujukan", $this->session->userdata("language"))?> :</label>
			
			<div class="col-md-5">
				<label class="control-label"><?=$form_data['ref_nomor_rujukan']?></label>
			</div>
		</div>
	</div>
</div>