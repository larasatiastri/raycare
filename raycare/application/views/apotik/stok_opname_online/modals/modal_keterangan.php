<div class="modal-header">
    <button type="button" class="btn btn-primary hidden" id="btnOK">OK</button>
	<button type="button" class="hidden" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title">
		<span class="caption-subject font-blue-sharp bold uppercase judul"><?=translate("Keterangan", $this->session->userdata("language"))?></span>
	</h4>
</div>
<div class="modal-body">
	<div class="row">
    <input type="hidden" name="so_id" id="so_id" value="<?=$so_id?>">
		<div class="form-group">
			<label class="control-label col-md-2"></label>
			<div class="col-md-8">
				<?php
					$keterangan = array(
						"id"          => "keterangan",
						"name"        => "keterangan",
						"autofocus"   => true,
						"rows"        => 5,
						"class"       => "form-control",
						"placeholder" => translate("Keterangan", $this->session->userdata("language"))
					);
					echo form_textarea($keterangan);
				?>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a class="btn default hidden" data-dismiss="modal" id="closeModalSimpan"><?=translate('Batal', $this->session->userdata('language'))?></a>
    <a class="btn btn-primary hidden" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
    <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
    <a class="btn btn-primary" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
<script type="text/javascript">
	
	$(document).ready(function(){
        initForm();
        baseAppUrl = mb.baseUrl()+'apotik/stok_opname_online/';

        // modalOK()
    });

     function initForm()
        {
            handleModalOK();    
            // handleDatePickers();
        }; 

         function handleModalOK(){
            $('a#modal_ok').click(function() {

            	var 
            		so_id = $('input#so_id').val(),
            		ket = $('#keterangan').val();
            		// alert(ket);
            	
            	$.ajax({
                    type     : 'POST',
                    url      : baseAppUrl + 'update_so',
                    data     : {so_id: so_id, keterangan: ket},
                    dataType : 'json',
                    beforeSend : function(){
                        Metronic.blockUI({boxed: true });
                    },
                    success  : function( results ) {
                        $('a#unset').click();
                        $('#closeModalSimpan').click(); 
                        location.href = baseAppUrl;
                        	
                    },
                    complete : function(){
                        Metronic.unblockUI();
                    }
                });
         	})
        }

</script>