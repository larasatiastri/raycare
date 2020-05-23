<?
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
    $msg = translate("Apakah anda yakin akan menyimpan harga ini?",$this->session->userdata("language"));

    // $hidden = array(
    //     "command"   => "add"
    // );

    // echo form_open(base_url()."reservasi/titip_terima_setoran/save");

?>

 
    <form action="<?=base_url()?>reservasi/titip_terima_setoran/verifikasi" method="post" id="form_harga" class="form-horizontal">
        
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("Verifikasi", $this->session->userdata("language"))?></h4>
                </div>
                <div class="modal-body">
            	 <div class="alert alert-danger display-hide">
			        <button class="close" data-close="alert"></button>
			        <?=$form_alert_danger?>
        		</div>
        		<div class="alert alert-success display-hide">
			        <button class="close" data-close="alert"></button>
			        <?=$form_alert_success?>
                </div>
                <div class="form-group hidden" style="margin-top:20px; margin-bottom:20px;">
                        <label class="control-label col-md-5"><?=translate("command", $this->session->userdata("language"))?> :</label>
                        
                        <div class="col-md-6">
                            <?php
                                $command = array(
                                    "name"          => "command",
                                    "id"            => "command",
                                    "autofocus"     => true,
                                    "class"         => "form-control", 
                                    "placeholder"   => translate("command", $this->session->userdata("language")), 
                                    "required"      => "required",
                                    "value"         => "add",
                                );
                                echo form_input($command);
                            ?>
                        </div>
                    </div>

                    <div class="form-group hidden" style="margin-top:20px; margin-bottom:20px;">
                        <label class="control-label col-md-5"><?=translate("id_terima_setoran", $this->session->userdata("language"))?> :</label>
                        
                        <div class="col-md-6">
                            <?php
                                $id_terima_setoran = array(
                                    "name"          => "id_terima_setoran",
                                    "id"            => "id_terima_setoran",
                                    "autofocus"     => true,
                                    "class"         => "form-control", 
                                    "placeholder"   => translate("id_terima_setoran", $this->session->userdata("language")), 
                                    "required"      => "required",
                                    "value"         => $id,
                                );
                                echo form_input($id_terima_setoran);
                            ?>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:20px; margin-bottom:20px;">
                        <label class="control-label col-md-4"><?=translate("Username", $this->session->userdata("language"))?> :</label>

                        <div class="col-md-6">
							<?php
								$username = array(
									"name"			=> "username",
									"id"			=> "username",
									"autofocus"		=> true,
									"class"			=> "form-control", 
									"placeholder"	=> translate("Username", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($username);
							?>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px; margin-bottom:20px;">
                        <label class="control-label col-md-4"><?=translate("Password", $this->session->userdata("language"))?> :</label>

                        <div class="col-md-6">
							<?php
								$Password = array(
									"name"			=> "password",
									"id"			=> "password",
                                    "class"         => "form-control", 
									"type"			=> "password", 
									"placeholder"	=> translate("Password", $this->session->userdata("language")), 
									"required"		=> "required"
								);
								echo form_input($Password);
							?>
						</div>
                    </div>
                    
                </div>
                <?php
					$confirm_save       = translate('Apa kamu yakin ingin menambahkan terima uang ini ?',$this->session->userdata('language'));
					$submit_text        = translate('Simpan', $this->session->userdata('language'));
					$reset_text         = translate('Reset', $this->session->userdata('language'));
					$back_text          = translate('Kembali', $this->session->userdata('language'));
				?>
                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnOK">OK</button>

                </div>

    </form>
