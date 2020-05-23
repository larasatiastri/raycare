<form action="#" id="form_session" name="form_session" class="form-horizontal" role="form">
<div class="modal-header">
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Session Habis', $this->session->userdata('language'))?></span>
	</div>
</div>
<div class="modal-body">
<div class="portlet light">
	<div class="portlet-body form">
		<div class="form-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<div class="pull-left lock-avatar-block">
							<img src="<?=$img?>" width="220px" height="250px" class="lock-avatar">
						</div>
					</div>
					
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<h4><?=$nama_lengkap?></h4>
					</div>
					<div class="form-group hidden">						
						<?php
							$username = array(
								"id"          => "username",
								"name"        => "username",
								"autofocus"   => true,
								"class"       => "form-control", 
								"placeholder" => translate("Username", $this->session->userdata("language")), 
								"required"    => "required",
								"value"		  => $username

							);
							echo form_input($username);
						?>						
					</div>
					<div class="form-group">						
						<?php
							$password = array(
								"id"          => "password",
								"name"        => "password",
								"type"        => "password",
								"autofocus"   => true,
								"class"       => "form-control", 
								"placeholder" => translate("Password", $this->session->userdata("language")), 
								"required"    => "required",

							);
							echo form_input($password);
						?>						
					</div>
					<div class="form-group">
					<a href="<?=base_url()?>home/logout"><h6><?=translate('Bukan', $this->session->userdata('language')).' '.$nama_lengkap.'?'?></h6></a>
					</div>

				
				</div>
			</div>			
		</div>
	</div>
</div>
</div>
<div class="modal-footer">
	<a class="btn default hidden" id="close_modal" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
	<a id="confirm_save_sub" class="btn btn-sm btn-primary" onClick="javascript:save();"><?=translate("Login", $this->session->userdata("language"))?></a>
    <button type="submit" id="save" class="btn default hidden" ><?=translate("Login", $this->session->userdata("language"))?></button>
</div>
</form>

<script type="text/javascript" src="<?=base_url()?>assets/metronic/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	baseAppUrl = mb.baseUrl() + 'home/';
});
function save() 
{
	$form = $('#form_session');

	if(! $form.valid()) return;

	$('a#confirm_save_sub', $form).attr('disabled','disabled');

    $.ajax({
        type     : 'POST',
        url      : baseAppUrl + 'login_ajax',
        data     : $form.serialize(),
        dataType : 'json',
        beforeSend : function(){
            Metronic.blockUI({boxed: true });
        },
        success  : function( results ) {
           if(results.success === true)
           {
           		var time = parseInt('<?=getSessionTimeLeft()?>');
        		mb.timeout(time);
        		mb.handleSession();
                mb.showMessage('success',results.msg,'Sukses');
                $('a#confirm_save_sub', $form).removeAttr('disabled');
                $('a#close_modal').click();
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

</script>