<?php

	$msg = translate('Anda yakin akan menambahkan invoice di tindakan ini?', $this->session->userdata('language'));
			
	$label = translate('Batch Number', $this->session->userdata('language'));
	if($tipe == 2){
		$label = translate('Kode Dialyzer', $this->session->userdata('language'));
	}

	$gudang_id = 'WH-05-2016-00'.($data_bed->lantai_id + 2);

	// echo 'WH-05-2016-00'.($data_bed->lantai_id + 2);
?>

<form id="modal_identitas" name="modal_identitas" role="form" class="form-horizontal" autocomplete="off">
	<input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       

	<div class="modal-body" id="section-identitas">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<?= $data_item->nama?>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
		            

					<div class="form-group">
						<input type="hidden" class="form-control" id="item_id" name="item_id" value="<?=$data_item->id?>">
						<input type="hidden" class="form-control" id="assesment_id" name="assesment_id" value="<?=$assesment_id?>">
						<input type="hidden" class="form-control" id="tindakan_hd_id" name="tindakan_hd_id" value="<?=$tindakan_hd_id?>">
						<input type="hidden" class="form-control" id="pasien_id" name="pasien_id" value="<?=$pasien_id?>">
						<input type="hidden" class="form-control" id="tipe" name="tipe" value="<?=$tipe?>">
						<input type="hidden" class="form-control" id="gudang_id" name="gudang_id" value="<?=$gudang_id?>">
						<label class="col-md-12"><?=$label?></label>
							<div class="col-md-12">
								<?php
					                $batch_number = array(
					                    "name"			=> "batch_number",
					                    "id"			=> "batch_number",
					                    "class"			=> "form-control", 
					                    "autofocus"			=> 1, 
					                );
					                echo form_input($batch_number);
					            ?>		
							</div>
					</div>
			        
	   			</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
		<a id="confirm_save" class="btn btn-primary"><?=translate("Simpan", $this->session->userdata("language"))?></a>
		<button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	var baseAppUrl = mb.baseUrl()+'klinik_hd/transaksi_perawat/';
    handleConfirmSave();

});	


function handleConfirmSave() {
	$('a#confirm_save').click(function(){
		var bn = $('input#batch_number').val();

		if(bn == ''){
			bootbox.alert('Batch Number Wajib Diisi');
			$('input#batch_number').parent().addClass('has-error');
			$('input#batch_number').focus();
		}else{
			$.ajax
            ({ 

                type: 'POST',
                url: mb.baseUrl()+'klinik_hd/transaksi_perawat/get_inventory',   
                data:  {
                    assesment_id: $('input#assesment_id').val(), item_id : $('input#item_id').val(), tipe: $('input#tipe').val(), tindakan_hd_id: $('input#tindakan_hd_id').val(), value : $('input#batch_number').val(), pasien_id: $('input#pasien_id').val(), gudang_id: $('input#gudang_id').val()
                },  
                dataType : 'json',
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(data)          //on recieve of reply
                { 
                    if(data.success == true){
                    	var tipe = $('input#tipe').val();

                    	mb.showMessage('success',data.msg,'Sukses');
                    	$('input#bn_dialyzer_').val($('input#batch_number').val());
                    	$('input#bn_dialyzer_').attr('value',$('input#batch_number').val());

                    	if(tipe == 2){
                    		$('input#kode_dialyzer_').val($('input#batch_number').val());
                    		$('input#kode_dialyzer_').attr('value',$('input#batch_number').val());
	                    	$('input#bn_dialyzer_').val(data.bn);
	                    	$('input#bn_dialyzer_').attr('value',data.bn);
                    	}if(tipe == 1){
                    		$('input#kode_dialyzer_').val(data.bn);
                    		$('input#kode_dialyzer_').attr('value',data.bn);
	                    	
                    	}
	                    $('input#bn_dialyzer_').attr('readonly','readonly');
	                    $('input#kode_dialyzer_').attr('readonly','readonly');
                    	$('a#close').click();	
                    }else{
                    	mb.showMessage('error',data.msg,'Error');	
                    }

                },
                complete : function() {
                    Metronic.unblockUI();
                }
            });
		}
	});
}




</script>