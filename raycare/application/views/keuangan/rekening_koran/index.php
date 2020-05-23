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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("BUKU BANK", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
            <a href="<?=base_url()?>keuangan/rekening_koran/add" class="btn btn-circle btn-default">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <?=translate("Tambah", $this->session->userdata("language"))?>
                </span>
            </a>
        </div>
	</div>

			<div class="portlet light bordered">
				<div class="portlet-body">
					<div class="form-group">
						<label class="col-md-3"><?=translate("Periode", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
						<div class="col-md-4">
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
						<label class="col-md-3"><?=translate("Bank", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
						<div class="col-md-4">
							<?php
								$banks = $this->bank_m->get_by(array('is_active' => 1));

								$bank_option = array();

								foreach ($banks as $bank) {
									$bank_option[$bank->id] = $bank->nob.' a/n '.$bank->acc_name.' - '.$bank->acc_number;
								}

								echo form_dropdown('bank_id', $bank_option, '', 'id="bank_id" class="form-control"');
							?>
						</div>
					</div>
					<table class="table table-striped table-bordered table-advance table-hover" id="table_arus_kas_bank">
						<thead>
						<tr>
							<th class="text-left" width="1%"><i class="fa fa-clock-o"></i> <?=translate("Tgl", $this->session->userdata("language"))?> </th>
							<th class="text-left"> <?=translate("Nomor", $this->session->userdata("language"))?> </th>
							<th class="text-left"> <?=translate("No BG / CEK", $this->session->userdata("language"))?> </th>
							<th class="text-left"><i class="fa fa-question"></i> <?=translate("Keterangan", $this->session->userdata("language"))?> </th>
							<th class="text-left" style="width: 120px;"><i class="fa fa-sort-amount-desc"></i> <?=translate("Debit", $this->session->userdata("language"))?> </th>
							<th class="text-left" style="width: 120px;"><i class="fa fa-sort-amount-asc"></i> <?=translate("Kredit", $this->session->userdata("language"))?> </th>
							<th class="text-left" style="width: 120px;"><i class="fa fa-bookmark"></i> <?=translate("Saldo", $this->session->userdata("language"))?> </th>
							<th class="text-left"><i class="fa fa-user"></i> <?=translate("User", $this->session->userdata("language"))?> </th>
							<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
						</tr>
						</thead>
						<tbody>
						
						</tbody>
						
					</table>
				</div>
			</div>





</div>
<?=form_close()?>