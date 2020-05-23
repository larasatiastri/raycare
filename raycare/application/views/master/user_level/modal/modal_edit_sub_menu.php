<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Sub Menu', $this->session->userdata('language'))?></span>
	</div>
</div>
<div class="modal-body">
<div class="portlet light">
	<div class="portlet-body form">
		<div class="form-body">
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Menu Utama", $this->session->userdata("language"))?> :<span class="required">*</span></label>
				<div class="col-md-6">
					<?php

						$menu_utama = get_user_level_menu($url_api,$user_level_id);


						$option_menu_utama = array(
							'0'	=> translate('Utama', $this->session->userdata('language'))
						);

						foreach ($menu_utama as $menu_utama) 
						{
							$option_menu_utama[$menu_utama->id] = $menu_utama->nama;
						}

						echo form_dropdown('edit_parent_id', $option_menu_utama, $parent_id, 'id="edit_parent_id" class="form-control" disabled');

					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?> :<span class="required">*</span></label>
				<div class="col-md-8">
					<?php
						$nama = array(
							"id"          => "sub_menu[0][nama]",
							"name"        => "sub_menu[0][nama]",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("Nama", $this->session->userdata("language")), 
							"required"    => "required",
							"value"       => $nama

						);
						echo form_input($nama);
					?>
				</div>
			</div>		
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Base URL", $this->session->userdata("language"))?> :</label>
				<div class="col-md-9">
					<?php
						$base_url = array(
							"id"          => "sub_menu[0][base_url]",
							"name"        => "sub_menu[0][base_url]",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("Base URL", $this->session->userdata("language")), 
							"value"       => rtrim($base_url, "/")

						);
						echo form_input($base_url);
					?>
				</div>
			</div>
			<div class="form-group hidden">
				<label class="control-label col-md-3"><?=translate("Cabang ID", $this->session->userdata("language"))?> :</label>
				<div class="col-md-9">
					<?php
						$cabang = array(
							"id"          => "sub_menu[0][cabang_id]",
							"name"        => "sub_menu[0][cabang_id]",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("Cabang ID", $this->session->userdata("language")), 
							"value"       => $cabang_id

						);
						echo form_input($cabang);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("URL", $this->session->userdata("language"))?> :</label>
				<div class="col-md-8">
					<?php
						$url = array(
							"id"          => "sub_menu[0][url]",
							"name"        => "sub_menu[0][url]",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("URL", $this->session->userdata("language")), 
							"value"       => $url

						);
						echo form_input($url);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Icon Class", $this->session->userdata("language"))?> :</label>
				<div class="col-md-8">
					<?php
						$icon_class = array(
							"id"          => "sub_menu[0][icon_class]",
							"name"        => "sub_menu[0][icon_class]",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("Icon Class", $this->session->userdata("language")), 
							"value"       => $icon_class
						);
						echo form_input($icon_class);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Unik ID", $this->session->userdata("language"))?> :</label>
				<div class="col-md-8">
					<?php
						$unik_id = array(
							"id"          => "sub_menu[0][unik_id]",
							"name"        => "sub_menu[0][unik_id]",
							"autofocus"   => true,
							"class"       => "form-control",
							"style"       => "background-color: transparent;border: 0px solid;", 
							"readonly"    => "readonly", 
							"placeholder" => translate("Unik ID", $this->session->userdata("language")), 
							"value"       => $unik_id
						);
						echo form_input($unik_id);
					?>
				</div>
			</div>
			<div class="form-group hidden">
				<label class="control-label col-md-3"><?=translate("ID", $this->session->userdata("language"))?> :</label>
				<div class="col-md-8">
					<?php
						$id = array(
							"id"          => "sub_menu[0][id]",
							"name"        => "sub_menu[0][id]",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("ID", $this->session->userdata("language")), 
							"value"       => $id
						);
						echo form_input($id);
					?>
				</div>
			</div>
			<div class="form-group hidden">
				<label class="control-label col-md-3"><?=translate("Parent ID", $this->session->userdata("language"))?> :</label>
				<div class="col-md-8">
					<?php
						$id = array(
							"id"          => "parent_id",
							"name"        => "parent_id",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("Parent ID", $this->session->userdata("language")), 
							"value"       => $parent_id
						);
						echo form_input($id);
					?>
				</div>
			</div>
			<div class="form-group hidden">
				<label class="control-label col-md-3"><?=translate("User Level ID", $this->session->userdata("language"))?> :</label>
				<div class="col-md-8">
					<?php
						$id = array(
							"id"          => "sub_menu[0][user_level_id]",
							"name"        => "sub_menu[0][user_level_id]",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("ID", $this->session->userdata("language")), 
							"value"       => $user_level_id
						);
						echo form_input($id);
					?>
				</div>
			</div>
		</div>
		<?php 
			$msg = translate('Apakah anda yakin mengubah sub menu ini?', $this->session->userdata("language"));
			$proses = translate('Sedang Diproses', $this->session->userdata("language")).'...';
		 ?>
		
	</div>
</div>
</div>
<div class="modal-footer">
	<a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
	<a id="confirm_save_sub" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-proses="<?=$proses?>" onClick="javascript:save();"><?=translate("Simpan", $this->session->userdata("language"))?></a>
    <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
</div>

<script type="text/javascript">
$(document).ready(function(){
	baseAppUrl = mb.baseUrl() + 'master/user_level/';
});
function save() 
{
	$form = $('#form_menu_user_level');

	if(! $form.valid()) return;

	var i = 0;
    var msg = $('a#confirm_save_sub').data('confirm');
    var proses = $('a#confirm_save_sub').data('proses');

    bootbox.confirm(msg, function(result) {
        Metronic.blockUI({boxed: true, message: proses});
        if (result==true) {
            i = parseInt(i) + 1;
            $('a#confirm_save_sub', $form).attr('disabled','disabled');
            if(i === 1)
            {
                $.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'save_sub_menu',
                    data     : $form.serialize(),
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                       if(results.success === true)
                       {
                            mb.showMessage('success',results.msg,'Sukses');
                            $('a#confirm_save_sub', $form).removeAttr('disabled');
                            $('a#refresh').click();
                            $('a#close').click();
                       }
                       else
                       {
                            mb.showMessage('error',results.msg,'Gagal');
                       }
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });               
            }
        }
    });
}

</script>