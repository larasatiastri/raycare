<?php

    $form_attr = array(
        "id"            => "form_terima_saldo", 
        "name"          => "form_terima_saldo", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );

    $hidden = array(
        "command"           => "add",
        "id_terima_setoran" => $form_data['id'],
        "tanggal"           => $form_data['tanggal'],
        "rupiah"            => $form_data['total_setor'],
        "bank_id"           => $form_data['bank_id'],
        "subjek"           => $form_data['subjek'],
    );


    echo form_open("", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $flash_form_data  = $this->session->flashdata('form_data');
    $flash_form_error = $this->session->flashdata('form_error');

    $confirm_save       = translate('Apa kamu yakin ingin menambahkan titip setoran ini ?',$this->session->userdata('language'));
    $submit_text        = translate('Simpan', $this->session->userdata('language'));
    $reset_text         = translate('Reset', $this->session->userdata('language'));
    $back_text          = translate('Kembali', $this->session->userdata('language'));


?>  
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

    <?php $confirm_save       = translate('Anda yakin akan menerima saldo ini?',$this->session->userdata('language'));?>
    <a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" id="save" data-confirm="<?=$confirm_save?>" ><?=translate("OK", $this->session->userdata("language"))?></a>
    
</div>
<?=form_close();?>

<script type="text/javascript">
$(document).ready(function(){
    save();
});

function save() {
    $form = $('#form_terima_saldo');

    $('a#save',$form).click(function() {

        if (! $form.valid()) return;
        var msg = $(this).data('confirm');
        bootbox.confirm(msg, function(result) {
            if (result==true) {
                $.ajax
                ({
                    type: 'POST',
                    url: mb.baseUrl() + 'keuangan/titip_terima_setoran/verifikasi',  
                    data:  $form.serialize(),  
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success:function(data)          //on recieve of reply
                    { 
                        if(data.success == true){
                            mb.showMessage('success',data.msg,'Sukses');
                            location.href = mb.baseUrl() + 'keuangan/titip_terima_setoran';
                        }if(data.success == false){
                            mb.showMessage('error',data.msg,'Error');
                            $('a#close').click();
                        }
                    
                    },
                    complete : function(){
                      Metronic.unblockUI();
                    }
                });
            }
        });
    });
}


</script>