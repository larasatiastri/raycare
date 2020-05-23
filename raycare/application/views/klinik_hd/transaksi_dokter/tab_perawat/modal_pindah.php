<form class="form-horizontal">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("pindah bed", $this->session->userdata("language"))?></span>
			</h4>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<div class="row">
					<div class="col-md-12">
						<label class="control-label col-md-3"><?=translate('Tujuan', $this->session->userdata('language'))?> :</label>
						<div class="col-md-3">
							<input class="form-control input-small hidden" value="<?=$bed_id?>" id="id_bed_pindah" name="id_bed_pindah">
							<?php 
								$lantai_options = array(
									''	=> 'Pilih Lantai..'
								);
								$lantai = $this->bed_m->get_lantai()->result();
								foreach ($lantai as $row) {
									$lantai_options[$row->lantai_id] = $row->lantai_id;
								}

								echo form_dropdown('lantai', $lantai_options,'', "id=\"lantai\" class=\"form-control\" onChange=\"handleLantai();\" ");
							 ?>
						</div>
						<div class="col-md-4">
							<?php 
								$bed_options = array(
									''	=> 'Pilih Bed..'
								);
								echo form_dropdown('bed_tujuan', $bed_options,'', "id=\"bed_tujuan\" class=\"form-control\" ");
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a class="btn default" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
			<a class="btn btn-primary" data-dismiss="modal" id="pindah_ok" onclick="handleModalPindah();"><?=translate('OK', $this->session->userdata('language'))?></a>
		</div>
	</div>
</form>

<script type="text/javascript">

$(document).ready(function() 
{
   	baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_perawat/';

});

	function handleModalPindah() 
	{
        var bed_asal = $('#id_bed_pindah').val(),
            bed_tujuan = $('#bed_tujuan').val();

        $.ajax ({ 
            type: "POST",
            url: baseAppUrl + "pindah_bed",  
            data:  {bed_asal: bed_asal, bed_tujuan: bed_tujuan},  
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
    }

    function handleLantai() 
    {
        $.ajax({
            type     : 'POST',
            url      : baseAppUrl + 'get_lantai',
            data     : {lantai: $('select#lantai').val()}, 
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success  : function( results ) {
                
                $('#bed_tujuan').empty();
                $('#bed_tujuan').append($("<option></option>").attr("value", "").text("Pilih Bed.."));

                $.each(results, function(key, value) {
                    $('#bed_tujuan').append($("<option></option>").attr("value", value.id).text('['+ value.kode +'] '+ value.nama));
                    $('#bed_tujuan').val('');

                });                    
            },
            complete : function() {
                Metronic.unblockUI();
            }
        });  
    }
	
</script>