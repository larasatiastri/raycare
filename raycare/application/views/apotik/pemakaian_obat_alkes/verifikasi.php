<?php
	$msg = translate("Apakah anda yakin untuk menerima item ini?",$this->session->userdata("language"));
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title">Verifikasi Terima Item</h4>
</div>
<div class="modal-body">
	<form class="form-horizontal">
		
		<div class="form-group">
			<label class="control-label col-md-4">Nama Penerima :</label>
			<div class="col-md-5">
				<?php
				// die(dump($data_surat_tugas['cabang_id']));
					$cabang_id = $this->session->userdata('cabang_id');
					$user_cabang = $this->user_m->get_by(array('cabang_id' => $cabang_id, 'is_active' => 1));

					$option_verifikator = array();

					foreach ($user_cabang as $usr_cabang) {
						$option_verifikator[$usr_cabang->id] = $usr_cabang->nama;
					}
					echo form_dropdown('user_terima_id', $option_verifikator,'','id="user_terima_id" class="form-control"');
				?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">Username :</label>
			<div class="col-md-5">
				<input type="text" class="form-control" name="username" id="username">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">Password :</label>
			<div class="col-md-5">
				<input type="password" class="form-control" name="password" id="password">
			</div>
		</div>
	</form>
</div>
<div class="modal-footer">
	<a id="save_verif" class="btn green btn-primary" >Submit</a>
</div>
<script type="text/javascript">

$(document).ready(function(){

	baseAppUrl = mb.baseUrl() + 'apotik/pemakaian_obat_alkes/';
	
	handleConfirmSave();

});


function handleConfirmSave(){

    $('a#save_verif').click(function() {
        // alert('klik');
        $.ajax
        ({
            type: 'POST',
            url: baseAppUrl +  "save_verif",  
            data:  {user_id:$('select#user_terima_id').val(), username:$('input#username').val(), password:$('input#password').val()},  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            { 
                if(data.success == true){

                	$('input#user_penerima_id', $('form#form_pemakaian_obat_alkes')).val(data.user_id);
                	$('input#user_penerima_id', $('form#form_pemakaian_obat_alkes')).attr('value',data.user_id);
                	mb.showToast('success',data.msg,'Sukses');
               		$('button#save', $('form#form_pemakaian_obat_alkes')).click();
                }if(data.success == false){
                	mb.showToast('error',data.msg,'Error');
                	$('input#username').val('');
                	$('input#password').val('');
                }
            
            },
            complete : function(){
                Metronic.unblockUI();
            }
        });
       
           
    });
};
</script>