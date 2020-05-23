<div class="portlet light">
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_claim_pasien", 
			    "name"          => "form_claim_pasien", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add_claim",
		        "id_pasien"		=> $id_pasien,
		    );

		    echo form_open(base_url()."master/pasien/save_claim", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');
		?>

		<div class="form-body">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Klaim Pasien", $this->session->userdata("language"))?></span>
					</div>
				</div>

				<div class="portlet-body form" style="margin-top:30px;">
					<div class="form-body">
						<div class="alert alert-danger display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_danger?>
					    </div>
					    <div class="alert alert-success display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_success?>
					    </div>
					    <div class="form-group hidden">
							<label class="control-label col-md-2"><?=translate("Id Pasien", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-3 hidden">
								<?php
									$id_pasien = array(
										"id"			=> "id_pasien",
										"name"			=> "id_pasien",
										"autofocus"		=> true,
										"class"			=> "form-control", 
										"placeholder"	=> translate("ID Pasien", $this->session->userdata("language")), 
										"value"			=> $id_pasien,
										"help"			=> $flash_form_data['id_pasien'],
									);
									echo form_input($id_pasien);
								?>

							</div>
							
						</div>

						<div class="form-group hidden">
							<label class="control-label col-md-2"><?=translate("Row Saat Ini", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-3 hidden">
								<?php
									$row = array(
										"id"			=> "row",
										"name"			=> "row",
										"autofocus"		=> true,
										"class"			=> "form-control", 
										"placeholder"	=> translate("Row Saat Ini", $this->session->userdata("language")), 
										"value"			=> "dokumen_0",
										"help"			=> $flash_form_data['row'],
									);
									echo form_input($row);
								?>

							</div>
							
						</div>
						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("Klaim", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-3">
								<?php
									$penjamin = $this->penjamin_m->get_by(array('is_suspended' => '0', 'id !=' => '1'));
									// die(dump($this->db->last_query()));
									$penjamin_option = array(
									    '' => translate('Pilih..', $this->session->userdata('language'))
									);

									foreach ($penjamin as $data)
									{
									    $penjamin_option[$data->id] = $data->nama;
									}
									echo form_dropdown('penjamin', $penjamin_option, '', "id=\"penjamin\" class=\"form-control\"");
								?>
							</div>

							<div class="col-md-2" id="show_claim_kelompok">
								
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2"><?=translate("No Kartu", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-3">
								<?php
									$no_kartu = array(
										"id"			=> "no_kartu",
										"name"			=> "no_kartu",
										"autofocus"		=> true,
										"class"			=> "form-control", 
										"placeholder"	=> translate("No Kartu", $this->session->userdata("language")), 
										"help"			=> $flash_form_data['no_kartu'],
									);
									echo form_input($no_kartu);
								?>

							</div>
							
						</div>

						<div id="show_claim">
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php $msg = translate("Apakah anda yakin akan membuat klaim pasien ini?",$this->session->userdata("language"));?>
		<div class="form-actions fluid">	
			<div class="col-md-offset-1 col-md-9">
				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
				<a id="confirm_save" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
		        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			</div>		
		</div>
		<?=form_close()?>
	</div>
</div>
<div id="popover_penjamin_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_data_klaim">
            <thead>
                <tr role="row" class="heading">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Isi', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tipe', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Pasien Penjamin ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 




