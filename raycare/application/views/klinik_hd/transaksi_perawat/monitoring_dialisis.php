<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Monitoring Dialisis", $this->session->userdata("language"))?></span>
	</h4>
</div>
<div class="modal-body">
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Waktu', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
			<input type="hidden" id="user_id" name="user_id" value="<?=$this->session->userdata("user_id")?>">
			<input type="hidden" id="tindakan_penaksiran_id" name="tindakan_penaksiran_id" value="<?=$tindakan_penaksiran_id?>">
			<input class="form-control" id="waktu_add" name="waktu_add" value="<?=date('H:i')?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Tekanan Darah', $this->session->userdata('language'))?> :</label>
		<div class="col-md-6">
		<div class="input-group">
			<input class="form-control" id="tekanan_darah_1_add" name="tekanan_darah_1_add" value="<?=$tda_value?>">
			<span class="input-group-addon">
				<i>  / </i>
			</span>
			<input class="form-control" id="tekanan_darah_2_add" name="tekanan_darah_2_add" value="<?=$tdb_value?>">
		</div>
		
		</div>
		
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('UFG', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
			<input class="form-control" id="ufg_add" name="ufg_add" value="<?=$ufg_value?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('UFR', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
			<input class="form-control" id="ufr_add" name="ufr_add" value="<?=$ufr_value?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('UFV', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
			<input class="form-control" id="ufv_add" name="ufv_add" value="<?=$ufv_value?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('QB', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
			<input class="form-control" id="qb_add" name="qb_add" value="<?=$qb_value?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('TMP', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
			<input class="form-control" id="tmp_add" name="tmp_add" value="<?=$tmp_value?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Vena Pressure', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
			<input class="form-control" id="vp_add" name="vp_add" value="<?=$vp_value?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Arteri Pressure', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
			<input class="form-control" id="ap_add" name="ap_add" value="<?=$ap_value?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Conductivity', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
			<input class="form-control" id="cond_add" name="cond_add" value="<?=$cond_value?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Temperature', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
			<input class="form-control" id="temp_add" name="temp_add" value="<?=$temperature_value?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Perawat', $this->session->userdata('language'))?> :</label>
		<div class="col-md-3">
			<input class="form-control" id="perawat_add" name="perawat_add" value="<?=$this->session->userdata("nama_lengkap")?>" readonly>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-4"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
		<div class="col-md-6">
			<input class="form-control input-small hidden" id="id_bed" name="id_bed">
			<textarea class="form-control" id="keterangan_add" name="keterangan_add" rows="5"value="<?=$keterangan_value?>" ><?=$keterangan_value?></textarea>
		</div>
	</div>
</div>
<div class="modal-footer">
	<?php 
		$msg = translate('Apakah anda yakin menyimpan monitoring dialisis ini?', $this->session->userdata("language"));
	 ?>
	<a class="btn default" id="close" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
	<a class="btn btn-primary"  data-confirm="<?=$msg?>" id="save_monitoring_add" onClick="javascript:save();"><?=translate('OK', $this->session->userdata('language'))?></a>
</div>

<script type="text/javascript">
$(document).ready(function(){

});
	
function save()
{
    var msg = $('a#save_monitoring_add').data('confirm');
    bootbox.confirm(msg, function(result) {
        if (result==true) {

            $.ajax
            ({ 

                type: 'POST',
                url: mb.baseUrl() + "klinik_hd/transaksi_perawat/simpanobservasi",  
                data:  {
                			jam:$("#waktu_add").val(),
                			tda:$("#tekanan_darah_1_add").val(),
                			tdb:$("#tekanan_darah_2_add").val(),
                			ufg:$("#ufg_add").val(),
                			ufr:$("#ufr_add").val(),
                			ufv:$("#ufv_add").val(),
                			qb:$("#qb_add").val(),
                			tmp:$("#tmp_add").val(),
                			vp:$("#vp_add").val(),
                			ap:$("#ap_add").val(),
                			cond:$("#cond_add").val(),
                			temp:$("#temp_add").val(),
                			userid:$("#user_id").val(),
                			keterangan:$("#keterangan_add").val(), 
                			transaksiid:$("#tindakan_hd_id").val(), 
                			tindakan_penaksiran_id: $('#tindakan_penaksiran_id').val() 
                		},  
                dataType : 'json',
                beforeSend : function(){
	                Metronic.blockUI({boxed: true });
	            },
                success:function(data)          //on recieve of reply
                { 
                    mb.showMessage(data[0],data[1],data[2]);
                    $('a#refresh_table_observasi').click();
                    $('a#close').click();

                    (data[3] != '') ? $('input#priming').val(data[3]) : '';
                    (data[3] != '') ? $('input#initiation').val(data[3]) : '';
                    (data[5] != '') ? $('input#waktu_').val(data[5]) : '';

                    $('input#termination').val(data[4]);
                    // oTableMonitoring.api().ajax.url(mb.baseUrl() +  'klinik_hd/transaksi_perawat/listing_monitoring/' + $("#tindakan_hd_id").val()).load();
                },
                complete : function() {
	                Metronic.unblockUI();
	            }
       
           });
        }
    });
}
</script>