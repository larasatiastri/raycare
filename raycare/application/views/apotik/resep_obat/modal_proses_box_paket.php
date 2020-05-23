<?php
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));			
?>
<form id="form_proses_box_paket" name="form_proses_box_paket" role="form" class="form-horizontal" autocomplete="off">
 <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("KONFIRMASI PERMINTAAN BOX PAKET", $this->session->userdata("language"))?></h4>
</div>
<div class="modal-body">
	<input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       
	<input type="hidden" id="id" name="id" required="required" class="form-control" value="<?=$id?>">                       
	<input type="hidden" id="tindakan_id" name="tindakan_id" required="required" class="form-control" value="<?=$permintaan['tindakan_id']?>">                       
	<input type="hidden" id="pasien_id" name="pasien_id" required="required" class="form-control" value="<?=$permintaan['pasien_id']?>">                       
	<input type="hidden" id="dokter_id" name="dokter_id" required="required" class="form-control" value="<?=$permintaan['created_by']?>">                       

	<div class="modal-body" id="section-identitas">
		<div class="portlet light">
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
	                
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("No. Permintaan", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<label class="control-label bold"><?=$permintaan['no_permintaan']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Pasien", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<label class="control-label bold"><?=$pasien['nama']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<label class="control-label bold"><?=$pasien_alamat['alamat']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-9">
								<div class="input-group">
									<?php

										$nama = $this->user_m->get_by(array('id' => $permintaan['created_by']), true);
										$nama_user = object_to_array($nama);
										// die_dump($nama);

									?>
									<label class="control-label bold"><?=$nama_user['nama']?></label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Waktu Permintaan", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<label class="control-label bold"><?=date('d M Y H:i', strtotime($permintaan['created_date']))?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Bed / Lantai", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<label class="control-label bold"><?=$bed['kode'].' / '.$bed['lantai_id']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Box Paket", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<?php

									$box_paket = $this->t_box_paket_m->get_data_box_paket(1)->result_array();

									$option_box_paket = array(
										''	=> 'Pilih...'
									);

									foreach ($box_paket as $bxp) {
										$option_box_paket[$bxp['id']] = $bxp['nama'].' - '.$bxp['kode_box_paket'];
									}

									echo form_dropdown('t_box_paket_id', $option_box_paket, '','id="t_box_paket_id" class="form-control select2" required');
								?>
							</div>
						</div>
						
						
					</div>
			        
	   			</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
	<a class="btn btn-danger" id="tolak"><?=translate("Tolak", $this->session->userdata("language"))?></a>
	<a class="btn btn-primary" id="save"><?=translate("Simpan", $this->session->userdata("language"))?></a>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $('select.select2').select2();
    save();
});


function save() {
    $form = $('#form_proses_box_paket');

    var j = 0;


    $('a#save',$form).click(function() {

    	j = parseInt(j) + 1;

    	if(j === 1){
			$.ajax
	        ({
	            type: 'POST',
	            url: mb.baseUrl() + 'apotik/resep_obat/save_proses',  
	            data:  $form.serialize(),  
	            dataType : 'json',
	            beforeSend : function(){
	                Metronic.blockUI({boxed: true });
	            },
	            success:function(data)          //on recieve of reply
	            { 
	                if(data.success == true){
	                    mb.showMessage('success',data.msg,'Sukses');
	                    location.href = mb.baseUrl() + 'apotik/resep_obat';
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
}


</script>
