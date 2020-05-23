<?php
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$user_setuju_id = $form_data['disetujui_oleh'];
    $user_setuju = $this->user_m->get($user_setuju_id);

    $tgl_setuju = date('d M Y', strtotime($form_data['created_date']));			
?>
<form id="form_pencairan_kasbon" name="form_pencairan_kasbon" role="form" class="form-horizontal" autocomplete="off">
 <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title font-blue-sharp bold uppercase"><?=translate("PENCAIRAN KASBON", $this->session->userdata("language")).' '. $form_data['nomor_permintaan']?></h4>
</div>
<div class="modal-body">
	<input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       
	<input type="hidden" id="id" name="id" required="required" class="form-control" value="<?=$pk_value?>">                       

	<div class="modal-body" id="section-identitas">
	<?php
		if($form_data['status'] == 13){
			?>
		<div class="note note-success note-bordered">
			<p>
				 <b>NOTE:</b> <?=$form_data['keterangan_tolak'].'. Accepted By: '.$user_setuju->nama.', Accepted Date: '.$tgl_setuju?>
			</p>
		</div>
		<?php
		}if($form_data['status'] == 14){
			?>
		<div class="note note-danger note-bordered">
			<p>
				  <b>NOTE:</b> <?=$form_data['keterangan_tolak'].'. Rejected By: '.$user_setuju->nama.', Rejected Date: '.$tgl_setuju?>
			</p>
		</div>
		<?php
		}

	?>
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
							<label class="col-md-12"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<label class="control-label bold"><?=date('d M Y', strtotime($form_data['tanggal']))?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<div class="input-group">
									<?php

										$nama = $this->user_m->get_by(array('id' => $form_data['diminta_oleh_id']), true);
										$nama_user = object_to_array($nama);
										// die_dump($nama);

									?>
									<label class="control-label bold"><?=$nama_user['nama']?></label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate('Tipe', $this->session->userdata('language'))?> :</label>
							<div class="col-md-12">
								<?php
									$tipe = '';
									
									if($form_data['tipe'] == 1){
										$tipe = 'Kasbon';
									}if($form_data['tipe'] == 2){
										$tipe = 'Rembes';
									}

								?>
								<label class="control-label bold"><?=$tipe?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Jenis Biaya", $this->session->userdata("language"))?> :</label>
							<?php
								$biaya = $this->biaya_m->get_by(array('id' => $form_data['biaya_id']), true);
							?>
							<div class="col-md-12">
								<div class="input-group">
									<label class="control-label bold"><?=$biaya->nama?></label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Nominal", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<div class="input-group">
									<input type="hidden" class="form-control" id="nominal" name="nominal" value="<?=$form_data['nominal']?>"> </input>
									<label class="control-label bold"><?=formatrupiah($form_data['nominal'])?></label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Terbilang", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<div class="input-group">
									<label class="control-label bold"><?='#'.terbilang($form_data['nominal']).' Rupiah#'?></label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Nominal Disetujui", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<div class="input-group">
									<input type="hidden" class="form-control" id="nominal_setujui" name="nominal_setujui" value="<?=$form_data['nominal_setujui']?>"></input>
									<label class="control-label bold"><?=formatrupiah($form_data['nominal_setujui'])?></label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Terbilang", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<div class="input-group">
									<label class="control-label bold"><?='#'.terbilang($form_data['nominal_setujui']).' Rupiah#'?></label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Keperluan", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<div class="input-group">
									<input type="hidden" class="form-control" id="keperluan" name="keperluan" value="<?=$form_data['keperluan']?>"></input>
									<label class="control-label bold"><?=$form_data['keperluan']?></label>
								</div>
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
	<a class="btn btn-primary" id="save"><?=translate("Simpan", $this->session->userdata("language"))?></a>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    save();
});

function save() {
    $form = $('#form_pencairan_kasbon');

    $('a#save',$form).click(function() {
        $.ajax
        ({
            type: 'POST',
            url: mb.baseUrl() + 'keuangan/pembayaran_transaksi/save_proses_cair',  
            data:  $form.serialize(),  
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success:function(data)          //on recieve of reply
            { 
                if(data.success == true){
                    mb.showMessage('success',data.msg,'Sukses');
                    location.href = mb.baseUrl() + 'keuangan/pembayaran_transaksi';
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
