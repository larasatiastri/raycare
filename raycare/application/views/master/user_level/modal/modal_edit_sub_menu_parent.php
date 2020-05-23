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
				<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?> :<span class="required">*</span></label>
				<div class="col-md-8">
					<?php
						$nama = array(
							"id"          => "nama",
							"name"        => "nama",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("Nama", $this->session->userdata("language")), 
							"required"    => "required",
							"style"       => "background-color: transparent;border: 0px solid;", 
							"readonly"    => "readonly", 
							"value"       => $nama

						);
						echo form_input($nama);
					?>
				</div>
			</div>		
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Menu Utama Awal", $this->session->userdata("language"))?> :<span class="required">*</span></label>
				<div class="col-md-6">
					<?php
						$menu_utama = get_user_level_menu($url_api,$user_level_id);
						$option_menu_utama = array(
							'0'	=> translate('Utama', $this->session->userdata('language'))
						);

						foreach ($menu_utama as $row) 
						{
							$option_menu_utama[$row->id] = $row->nama;
						}

						echo form_dropdown('edit_parent_id', $option_menu_utama, $parent_id, 'id="edit_parent_id" class="form-control" disabled');

					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?=translate("Pindahkan Ke", $this->session->userdata("language"))?> :<span class="required">*</span></label>
				<div class="col-md-6">
					<?php

						$option_menu_parent = array(
							'0'	=> translate('Utama', $this->session->userdata('language'))
						);

						foreach ($menu_utama as $parent) 
						{
							$option_menu_parent[$parent->id] = $parent->nama;
						}

						echo form_dropdown('parent_id', $option_menu_parent,'', 'id="parent_id" class="form-control"');

					?>
				</div>
			</div>
		
			<div class="form-group hidden">
				<label class="control-label col-md-3"><?=translate("ID", $this->session->userdata("language"))?> :</label>
				<div class="col-md-8">
					<?php
						$id = array(
							"id"          => "id_menu",
							"name"        => "id_menu",
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
						$parent_id_awal = array(
							"id"          => "parent_id_awal",
							"name"        => "parent_id_awal",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("ID", $this->session->userdata("language")), 
							"value"       => $parent_id
						);
						echo form_input($parent_id_awal);
					?>
				</div>
			</div>
			<div class="form-group hidden">
				<label class="control-label col-md-3"><?=translate("User Level ID", $this->session->userdata("language"))?> :</label>
				<div class="col-md-8">
					<?php
						$user_level_id = array(
							"id"          => "user_level_id",
							"name"        => "user_level_id",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("ID", $this->session->userdata("language")), 
							"value"       => $user_level_id
						);
						echo form_input($user_level_id);
					?>
				</div>
			</div>
			<div class="form-group hidden">
				<label class="control-label col-md-3"><?=translate("User Level ID", $this->session->userdata("language"))?> :</label>
				<div class="col-md-8">
					<?php
						$m_order = array(
							"id"          => "m_order",
							"name"        => "m_order",
							"autofocus"   => true,
							"class"       => "form-control", 
							"placeholder" => translate("ID", $this->session->userdata("language")), 
							"value"       => $m_order
						);
						echo form_input($m_order);
					?>
				</div>
			</div>

		
		</div>
		<?php 
			$msg = translate('Apakah anda yakin memindahkan menu utama dari sub menu ini?', $this->session->userdata("language"));
			$proses = translate('Sedang Diproses', $this->session->userdata("language")).'...';
		 ?>
		
	</div>
</div>
</div>
<div class="modal-footer">
	<a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
	<a id="confirm_save_sub" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-proses="<?=$proses?>" ><?=translate("Simpan", $this->session->userdata("language"))?></a>
    <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
</div>

<script type="text/javascript">
$(document).ready(function(){
	baseAppUrl = mb.baseUrl() + 'master/user_level/';
	save();
});
function save() 
{
	$form = $('#form_menu_user_level');

	$('a#confirm_save_sub', $form).click(function(){

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
	                    url      : baseAppUrl + 'edit_menu_parent',
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
	});
}

</script>