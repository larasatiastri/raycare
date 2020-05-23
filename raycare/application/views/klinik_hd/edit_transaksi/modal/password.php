<form action="#" method="post" id="form_konfirmasi" class="form-horizontal">                             
    <div class="modal-body">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Hapus Tindakan ", $this->session->userdata("language")).$form_data['no_transaksi']?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-group hidden">
                    <label class="control-label col-md-3"><?=translate("Password Anda", $this->session->userdata("language"))?></label>
                    <div class="col-md-3">
                    	<input type="text" id="user_pass" name="user_pass" value="<?=$form_user['password']?>" class="form-control"></input>
                    	<input type="text" id="tindakan_id" name="tindakan_id" value="<?=$form_data['id']?>" class="form-control"></input>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3"><?=translate("Password Anda", $this->session->userdata("language"))?></label>
                    <div class="col-md-5">
                    	<input type="password" id="user_pass_input" name="user_pass_input" class="form-control"></input>
                    	<span class="message-pass text-danger"></span>
                    </div>
                </div>
               
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <?php 
            $msg = translate("Hapus tindakan ini?", $this->session->userdata("language"));
        ?>
        <a id="closeModal" class="btn btn-circle btn-default" data-dismiss="modal">Close</a>
        <button type="submit" class="btn btn-primary hidden" id="btnTolakOK">OK</button>
        <a class="btn btn-circle btn-primary" id="confirm_hapus" data-confirm="<?=$msg?>">OK</a>
    </div>
</form>

<script type="text/javascript">

    $(document).ready(function(){
        initForm();
        baseAppUrl = mb.baseUrl()+'klinik_hd/edit_transaksi/'
        // modalOK();
    });
    
    function initForm()
    {
        confirmHapus();
    };  

    function confirmHapus(){
        $('a#confirm_hapus').click(function() {

        	var pwd = $('input#user_pass').val();
        	var pwd_input = $('input#user_pass_input').val();
            var msg = $(this).data('confirm');

        	$.ajax({
                type     : 'POST',
                url      : baseAppUrl + 'check_password',
                data     : {pwd:pwd, pwd_input : pwd_input},
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success  : function( results ) {
                   if(results.success === true)
                   {
			            var i=0;
			            bootbox.confirm(msg, function(result) {
			                Metronic.blockUI({boxed: true, message: 'Sedang Diproses..'});
			                if (result==true) {
			                    i = parseInt(i) + 1;
			                    $('a#confirm_hapus').attr('disabled','disabled');
			                    if(i === 1)
			                    {
			                      save();
			                    }
			                }
			            });                   	
                    }
                    else if(results.success === false)
                    {
                    	$('input#user_pass_input').closest('[class^="col"]').addClass('has-error');
                    	$('span.message-pass').text(results.msg);
                    }
                },
                complete : function(){
                    Metronic.unblockUI();
                }
			});
        });  
    };

    function save(){
    	$.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'delete_tindakan',
            data     : {tindakan_id:$('input#tindakan_id').val()},
            dataType : 'json',
            beforeSend : function(){
                
            },
            success  : function( results ) {
            	if(results.success === true)
            	{
            		$('a#refresh_history').click();
            	}
            },
            complete : function(){
            	$('a#closeModal').click();
            	Metronic.unblockUI();
            }
        });
    };


</script>
