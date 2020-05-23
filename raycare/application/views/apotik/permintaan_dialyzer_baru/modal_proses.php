<?php
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));			
?>
<form id="form_proses_dialyzer" name="form_proses_dialyzer" role="form" class="form-horizontal" autocomplete="off">
 <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("KONFIRMASI PERMINTAAN DIALYZER", $this->session->userdata("language"))?></h4>
</div>
<div class="modal-body">
	<input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       
	<input type="hidden" id="id" name="id" required="required" class="form-control" value="<?=$id?>">                       
	<input type="hidden" id="tindakan_id" name="tindakan_id" required="required" class="form-control" value="<?=$permintaan['tindakan_id']?>">                       
	<input type="hidden" id="cabang_id" name="cabang_id" required="required" class="form-control" value="<?=$permintaan['cabang_id']?>">                       
	<input type="hidden" id="pasien_id" name="pasien_id" required="required" class="form-control" value="<?=$permintaan['pasien_id']?>">                       

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
						<div class="form-group hidden">
							<label class="control-label col-md-3"><?=translate("Reason", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<input type="hidden" id="reason" name="reason" value="<?=$permintaan['reason']?>">
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
								<label class="control-label bold"><?=$bed[0]['kode'].' / '.$bed[0]['lantai_id']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Gudang", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<?php

									$gudang = $this->gudang_m->get_by(array('is_active' => 1));
									$gudang = object_to_array($gudang);
									$option_gudang = array(
										''	=> 'Pilih...'
									);

									foreach ($gudang as $gdg) {
										$option_gudang[$gdg['id']] = $gdg['nama'];
									}

									echo form_dropdown('gudang_id', $option_gudang, '','id="gudang_id" class="form-control select2"');
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Dialyzer", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<?php

									$dializer = $this->item_m->get_dialyzer()->result_array();

									$option_dialyzer = array(
										''	=> 'Pilih...'
									);

									foreach ($dializer as $dlz) {
										$option_dialyzer[$dlz['id']] = $dlz['nama'];
									}

									echo form_dropdown('dialyzer_id', $option_dialyzer, '','id="dialyzer_id" class="form-control select2"');
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("BN / ED", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<?php

									$option_bn = array(
										''	=> 'Pilih...'
									);

									echo form_dropdown('bn_sn_lot', $option_bn, '','id="bn_sn_lot" class="form-control"');
								?>
							</div>
						</div>
						<div class="form-group hidden">
							<label class="control-label col-md-3"><?=translate("ED", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<input class="form-control" id="expired_date" name="expired_date"></input>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate("Diberikan Ke", $this->session->userdata("language"))?> :</label>
							<div class="col-md-9">
								<?php

									$perawat = $this->user_m->get_data_perawat_klinik()->result_array();

									$option_perawat = array(
										''	=> 'Pilih...'
									);

									foreach ($perawat as $prwt) {
										$option_perawat[$prwt['id']] = $prwt['nama'];
									}

									echo form_dropdown('perawat_penerima', $option_perawat, '','id="perawat_penerima" class="form-control select2"');
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
    get_dialyzer();
    save();
});

function get_dialyzer(){
	$('select#dialyzer_id').on('change', function(){
		var gudang_id = $('select#gudang_id').val(),
			dialyzer_id = $(this).val();
			$bnSnLot = $('select#bn_sn_lot');

		if(gudang_id != '' && dialyzer_id != ''){			
			$.ajax
	        ({
	            type: 'POST',
	            url: mb.baseUrl() + 'apotik/permintaan_dialyzer_baru/get_dialyzer',  
	            data:  {dialyzer_id:dialyzer_id, gudang_id:gudang_id},  
	            dataType : 'json',
	            beforeSend : function(){
	                Metronic.blockUI({boxed: true, target:'#bn_sn_lot'});
	            },
	            success:function(data)          //on recieve of reply
	            { 
	                if(data.success == true){
	                   	
	               		var inv = data.inventory;

	               		$bnSnLot.empty();
	               		$bnSnLot.append($("<option></option>").attr("value", '').text('Pilih...'));
	               		$.each(inv, function(idx, inv) {
	               			$bnSnLot.append($("<option></option>").attr("value", inv.bn_sn_lot+'|'+inv.expire_date).text(inv.bn_sn_lot+' / '+inv.expire_date));
	               		});

	               		$bnSnLot.select2();
	                }if(data.success == false){
	                    mb.showMessage('error',data.msg,'Error');
	                }
	            
	            },
	            complete : function(){
	              Metronic.unblockUI('#bn_sn_lot');
	            }
	        });
		}else{
			bootbox.alert('Silahkan pilih gudang dan dialyzer');
		}

	});

	$('select#gudang_id').on('change', function(){
		var gudang_id = $(this).val(),
			dialyzer_id = $('select#dialyzer_id').val();
			$bnSnLot = $('select#bn_sn_lot');

		if(gudang_id != '' && dialyzer_id != ''){

			$.ajax
	        ({
	            type: 'POST',
	            url: mb.baseUrl() + 'apotik/permintaan_dialyzer_baru/get_dialyzer',  
	            data:  {dialyzer_id:dialyzer_id, gudang_id:gudang_id},  
	            dataType : 'json',
	            beforeSend : function(){
	                Metronic.blockUI({boxed: true, target:'#bn_sn_lot'});
	            },
	            success:function(data)          //on recieve of reply
	            { 
	                if(data.success == true){
	                   	
	               		var inv = data.inventory;

	               		$bnSnLot.empty();
	               		$bnSnLot.append($("<option></option>").attr("value", '').text('Pilih...'));
	               		$.each(inv, function(idx, inv) {
	               			$bnSnLot.append($("<option></option>").attr("value", inv.bn_sn_lot+'|'+inv.expire_date).text(inv.bn_sn_lot+' / '+inv.expire_date));
	               		});

	               		$bnSnLot.select2();
	                }if(data.success == false){
	                    mb.showMessage('error',data.msg,'Error');
	                }
	            
	            },
	            complete : function(){
	              Metronic.unblockUI('#bn_sn_lot');
	            }
	        });
		}else{
			//bootbox.alert('Silahkan pilih gudang dan dialyzer');
		}

	});
}

function save() {
    $form = $('#form_proses_dialyzer');

    var j = 0;


    $('a#save',$form).click(function() {

    	j = parseInt(j) + 1;

    	if(j === 1){
			$.ajax
	        ({
	            type: 'POST',
	            url: mb.baseUrl() + 'apotik/permintaan_dialyzer_baru/save_proses',  
	            data:  $form.serialize(),  
	            dataType : 'json',
	            beforeSend : function(){
	                Metronic.blockUI({boxed: true });
	            },
	            success:function(data)          //on recieve of reply
	            { 
	                if(data.success == true){
	                    mb.showMessage('success',data.msg,'Sukses');
	                    location.href = mb.baseUrl() + 'apotik/permintaan_dialyzer_baru';
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
