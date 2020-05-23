
<?php
	$form_attr = array(
	    "id"            => "form_penjamin_pasien", 
	    "name"          => "form_penjamin_pasien", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add_penjamin",
        "id_pasien"		=> $id_pasien,
    );

    echo form_open(base_url()."master/pasien/save_penjamin", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>

<div class="portlet light">
			
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Penjamin Pasien", $this->session->userdata("language"))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat penjamin pasien ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
	</div>

	<div class="portlet-body form">
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
				
				<div class="col-md-3 ">
					<?php
						$id_pasien_input = array(
							"id"			=> "id_pasien",
							"name"			=> "id_pasien",
							"autofocus"		=> true,
							"class"			=> "form-control", 
							"placeholder"	=> translate("ID Pasien", $this->session->userdata("language")), 
							"value"			=> $id_pasien,
							"help"			=> $flash_form_data['id_pasien'],
						);
						echo form_input($id_pasien_input);
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
				<label class="control-label col-md-2"><?=translate("Pasien", $this->session->userdata("language"))?> :</label>
				
				<div class="col-md-3">
					<?php
						$data_pasien = $this->pasien_m->get($id_pasien);
					?>
					<label class="control-label"><?=$data_pasien->nama?></label>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-2"><?=translate("Penjamin", $this->session->userdata("language"))?> :<span class="required">*</span></label>
				
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
						echo form_dropdown('penjamin', $penjamin_option, '', "id=\"penjamin\" class=\"form-control\" required=\"required\" ");
					?>
				</div>

				<div class="col-md-2" id="show_penjamin_kelompok">
					
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

			<div id="show_penjamin">
			</div>
		</div>
	</div>
			
<?=form_close()?>

</div>





