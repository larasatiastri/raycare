<?php
	$form_attr = array(
	    "id"            => "form_set_user_level", 
	    "name"          => "form_set_user_level", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."pengaturan/fitur_user_level/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Set User Level", $this->session->userdata("language"))?></span>
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
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Nama Halaman", $this->session->userdata("language"))?>:</label>
					<div class="col-md-3">
						<label class="control-label"><?=$page?></label>
						<input type="hidden" name="page" id="page" value="<?=$page?>">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Nama Fitur", $this->session->userdata("language"))?>:</label>
					<div class="col-md-3">
						<label class="control-label"><?=$button?></label>
						<input type="hidden" name="button" id="button" value="<?=$button?>">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Batasan User Level", $this->session->userdata("language"))?> :</label>
					<div class="col-md-3">
						<select id="user_level_id" name="user_level_id[]" class="multi-select"  multiple="multiple">
							<?php
								$this->user_level_m->set_columns(array('id', 'nama'));
								$where = array(
								    'is_active'       => 1
								    );
								$user_levels = $this->user_level_m->get_by($where);

								$fitur_user_levels = $this->fitur_tombol_user_level_m->get_by(array('page' => $page, 'button' => $button));

								foreach ($user_levels as $user_level) 
								{
								    $found = false;
								    $selected = "";
									foreach ($fitur_user_levels as $fitur_user_level) 
									{
										if($user_level->id == $fitur_user_level->user_level_id)
										{
											$found = true;
										}
									}
									if($found == true)
									{
										$selected = "selected=\"selected\"";
									}
									echo "<option value=\"".$user_level->id."\" ".$selected.">".$user_level->nama."</option>";
								}
							?>
						</select>
					</div>
				</div>

			</div>
			<?php $msg = translate("Apakah anda yakin akan mengatur fitur user level ini?",$this->session->userdata("language"));?>
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



