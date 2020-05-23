<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Verifikasi', $this->session->userdata('language'))?></span>
    </div>
</div>
<div class="modal-body">
<div class="portlet light">
    <div class="portlet-body">
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label">Username</label>
            <div class="input-icon">
                <i class="fa fa-user"></i>
                 <?php
                    $project_status = array(
                        "name"        => "username", 
                        "id"          => "username", 
                        "class"       => "form-control placeholder-no-fix",
                        "readonly"    => "readonly",
                        "value"       => $this->session->userdata("username"), 
                        "placeholder" =>"Username"
                    );
                    
                    echo form_input($project_status);
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Password</label>
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                <?php
                    $password = array(
                        "name"          => "password",
                        "id"            => "password", 
                        "autofocus"     => "autofocus", 
                        "required"     => "required", 
                        "placeholder"   => "Password", 
                        "class"         => "form-control placeholder-no-fix",
                        "autocomplete"  =>  "off"

                    );
                    echo form_password($password);
                ?>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal-footer">

    <?php $confirm_save       = translate('Anda yakin akan menerima setoran ini?',$this->session->userdata('language'));?>
    <a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" id="save" data-confirm="<?=$confirm_save?>" ><?=translate("OK", $this->session->userdata("language"))?></a>
    
</div>

<script type="text/javascript">
$(document).ready(function(){
    save();
});

function save() {
    $form = $('#form_proses_titip_setoran');

    $('a#save',$form).click(function() {

        if (! $form.valid()) return;
                $.ajax
                ({
                    type: 'POST',
                    url: mb.baseUrl() + 'keuangan/proses_terima_setoran/verifikasi',  
                    data:  $form.serialize(),  
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success:function(data)          //on recieve of reply
                    { 
                        if(data.success == true){
                            mb.showMessage('success',data.msg,'Sukses');
                            location.href = mb.baseUrl() + 'keuangan/proses_terima_setoran';
                        }if(data.success == false){
                            mb.showMessage('error',data.msg,'Error');
                            $('a#close').click();
                        }
                    
                    },
                    complete : function(){
                      Metronic.unblockUI();
                    }
                });
    });
}


</script>