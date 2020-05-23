<form class="form-horizontal">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("End Visit", $this->session->userdata("language"))?></span>
			</h4>
		</div>
		<div class="modal-body">
            <input class="form-control hidden" id="tindakan_hd_visit_id" name="tindakan_hd_visit_id" value="<?=$id_tindakan_visit?>">
            <input class="form-control hidden" id="bed_id_visit" name="bed_id_visit" value="<?=$bed_id?>">
			<div class="form-group">
				<label class="control-label col-md-4"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
                <div class="col-md-5">
                    <textarea class="form-control" name="keterangan_visit" id="keterangan_visit" rows="5"></textarea>
                </div>
			</div>
		</div>
		<div class="modal-footer">
			<a class="btn default" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
			<a class="btn btn-primary" data-dismiss="modal" id="btn_end_visit" onclick="handleBtnEndVisit()"><?=translate('OK', $this->session->userdata('language'))?></a>
		</div>
	</div>
</form>

<script type="text/javascript">

    $(document).ready(function() 
    {
       	baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_dokter/';
    });


    function handleBtnEndVisit(){

        $.ajax ({ 
            type: "POST",
            url: baseAppUrl + "set_visit",  
            data:  {
                id                  : $('input#bed_id_visit').val(), 
                type                : 1,
                id_tindakan_visit   : $('input#tindakan_hd_visit_id').val(),
                keterangan          : $('textarea#keterangan_visit').val(),
            },  
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            dataType : "json",
            success:function(data)          
            { 
                location.href = mb.baseUrl() + 'klinik_hd/transaksi_dokter/transaksi_diproses#tabdenah';
                $('a#refresh').click();
                // location.reload();  
            },
            complete : function() {
                Metronic.unblockUI();
            }
        });
    }

	
	
</script>