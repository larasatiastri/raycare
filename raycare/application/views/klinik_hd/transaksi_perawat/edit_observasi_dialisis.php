<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Edit Monitoring Dialisis", $this->session->userdata("language"))?></span>
					</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Waktu', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input type="hidden" id="user_id" name="user_id" value="<?=$this->session->userdata("user_id")?>">
							<input type="hidden" id="monitoring_id" name="monitoring_id" value="<?=$observasi_id_value?>">
							<input class="form-control" id="waktu" name="waktu" value="<?=$waktu_pencatatan_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Tekanan Darah', $this->session->userdata('language'))?> :</label>
						<div class="col-md-6">
							<div class="input-group">
								<input class="form-control" id="tekanan_darah_1" name="tekanan_darah_1" value="<?=$tda_value?>">
								<span class="input-group-addon">
									<i>  / </i>
								</span>
								<input class="form-control" id="tekanan_darah_2" name="tekanan_darah_2" value="<?=$tdb_value?>">
							</div>							
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('UFG', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input class="form-control" id="ufg_" name="ufg_" value="<?=$ufg_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('UFR', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input class="form-control" id="ufr_" name="ufr_" value="<?=$ufr_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('UFV', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input class="form-control" id="ufv_" name="ufv_" value="<?=$ufv_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('QB', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input class="form-control" id="qb_" name="qb_" value="<?=$qb_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('TMP', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input class="form-control" id="tmp_" name="tmp_" value="<?=$tmp_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Vena Pressure', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input class="form-control" id="vp_" name="vp_" value="<?=$vp_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Arteri Pressure', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input class="form-control" id="ap_" name="ap_" value="<?=$ap_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Conductivity', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input class="form-control" id="cond_" name="cond_" value="<?=$cond_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Temperature', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input class="form-control" id="temperature_" name="temperature_" value="<?=$temperature_value?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Perawat', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input class="form-control" id="perawat_" name="perawat_" value="<?=$user_name?>" readonly>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
						<div class="col-md-6">
							<input class="form-control input-small hidden" id="id_bed" name="id_bed">
							<textarea class="form-control" id="keterangan_" name="keterangan_" rows="5" value="<?=$keterangan_value?>"><?=$keterangan_value?></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<?php 
						$msg = translate('Apakah anda yakin mengubah monitoring dialisis ini?', $this->session->userdata("language"));
					 ?>
					<a class="btn default" id="close" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
					<a class="btn btn-primary"  data-confirm="<?=$msg?>" id="save_monitoring" onClick="javascript:save();"><?=translate('OK', $this->session->userdata('language'))?></a>
				</div>


<script type="text/javascript">
$(document).ready(function(){

});
	
function save()
{
    var msg = $('a#save_monitoring').data('confirm');
    bootbox.confirm(msg, function(result) {
        if (result==true) {

            $.ajax
            ({ 

                type: 'POST',
                url: mb.baseUrl() +  "klinik_hd/transaksi_perawat/updateobservasi",  
                data:  {jam:$("#waktu").val(),
                	    tda:$("#tekanan_darah_1").val(),
                	    tdb:$("#tekanan_darah_2").val(),
                	    ufg:$("#ufg_").val(),
                	    ufr:$("#ufr_").val(),
                	    ufv:$("#ufv_").val(),
                	    qb:$("#qb_").val(),
                	    tmp:$("#tmp_").val(),
                	    vp:$("#vp_").val(),
                	    ap:$("#ap_").val(),
                	    cond:$("#cond_").val(),
                	    temperature:$("#temperature_").val(),
                	    userid:$("#user_id").val(),
                	    keterangan:$("#keterangan_").val(),
                	    id_observasi:$("#monitoring_id").val()
                	},  
                dataType : 'json',
                success:function(data)          //on recieve of reply
                { 
                    
                    mb.showMessage(data[0],data[1],data[2]);
                    (data[3] != '') ? $('input#waktu_').val(data[3]) : '';
                    $('a#refresh_table_observasi').click();
                    $('a#close').click();
                }
       
           });
        }
    });
}
</script>