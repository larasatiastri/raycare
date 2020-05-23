<form class="form-horizontal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("KONFIRMASI TOLAK", $this->session->userdata("language"))?></span>
		</h4>
	</div>
	<div class="modal-body">
		<div class="form-group">
			<label class="control-label col-md-3"><?=translate('Keterangan', $this->session->userdata('language'))?> :</label>
			<div class="row">
				<div class="col-md-8">
					<input class="form-control input-small hidden" value="<?=$bed_id?>" id="id_bed" name="id_bed">
					<textarea id="keterangan" name="keterangan" rows="5" cols="50"></textarea>
				</div>
			</div>
		</div>
		
	</div>
	<div class="modal-footer">
		<a class="btn default" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
		<a class="btn btn-primary" data-dismiss="modal" id="tolak_ok" onclick="handleModalTolak();"><?=translate('OK', $this->session->userdata('language'))?></a>
	</div>
</form>

<script type="text/javascript">

	$(document).ready(function() 
	{
	   	baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_perawat/';

	});
	
	function handleModalTolak()
	{
        // $('#tolak_ok').on('click', function(){

            var id = $('#id_bed').val(),
                ket = $('#keterangan').val();

            $.ajax ({ 
                type: "POST",
                url: baseAppUrl + "tolak_bed",  
                data:  {id: id, keterangan: ket},  
                dataType : "json",
                beforeSend : function(){
	                Metronic.blockUI({boxed: true });
	            },
                success:function(data)         
                { 
                	$.ajax ({ 
	                    type: "POST",
	                    url: baseAppUrl + "show_denah_lantai_2_html",
	                    dataType : "text",
	                    success: function(data2)         
	                    { 
	                        $("div.svg_file_lantai_2").html(data2);
                    		mb.showMessage(data[0],data[1],data[2]);
	                    },
	                });
                },
                complete : function() {
	                Metronic.unblockUI();
	            }
            });            
        // });
    }
</script>