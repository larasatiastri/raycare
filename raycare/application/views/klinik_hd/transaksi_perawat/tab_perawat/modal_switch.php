<form class="form-horizontal">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tukar bed", $this->session->userdata("language"))?></span>
			</h4>
		</div>
		<div class="modal-body">
			<div class="form-group">
                <div class="col-md-12">
                    <label class=""><?=translate('Tindakan', $this->session->userdata('language'))?> :</label>
                    <input class="form-control input-small hidden" value="<?=$bed_id?>" id="id_bed_awal" name="id_bed_awal">
                    <input class="form-control input-small hidden" value="<?=$lantai?>" id="lantai" name="lantai">
                    <input class="form-control input-small hidden" value="<?=$shift?>" id="shift" name="shift">
                    <input class="form-control input-small hidden" value="<?=$id_tindakan?>" id="id_tindakan_asal" name="id_tindakan_asal">
                </div> 
                <div class="col-md-12">
                    <label class=""><?=$tindakan_awal->nama_pasien.' ['.$tindakan_awal->kode_bed.']'?></label>
                    
                </div>
            </div>
            <div class="form-group">
				<div class="col-md-12">
                    <label><?=translate('Tukar Dengan', $this->session->userdata('language'))?> :</label>   
                </div>
                <div class="col-md-12">
					<?php
                        $options = array(
                            '' => translate('Pilih', $this->session->userdata('language')).'...'
                        );

                        if(count($tindakan_tujuan) != 0){
                            foreach ($tindakan_tujuan as $key => $tujuan) {
                                $options[$tujuan['id'].'-'.$tujuan['bed_id']] = $tujuan['nama_pasien'].' ['.$tujuan['kode_bed'].']';
                            } 
                        }
                        echo form_dropdown('id_tindakan_tujuan', $options, '', 'id="id_tindakan_tujuan" class="form-control"');
                    ?>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a class="btn default" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
			<a class="btn btn-primary" data-dismiss="modal" id="pindah_ok" ><?=translate('OK', $this->session->userdata('language'))?></a>
		</div>
	</div>
</form>

<script type="text/javascript">

$(document).ready(function() 
{
   	baseAppUrl = mb.baseUrl() + 'klinik_hd/transaksi_perawat/';
    handleModalPindah();

});

	function handleModalPindah() 
	{
        $('a#pindah_ok').click(function(){
            var id_asal = $('input#id_tindakan_asal').val(),
                id_tujuan = $('select#id_tindakan_tujuan').val();

            $.ajax ({ 
                type: "POST",
                url: baseAppUrl + "tukar_bed",  
                data:  {id_asal: id_asal, id_tujuan: id_tujuan, lantai:$('input#lantai').val(),shift:$('input#shift').val(), id_bed_awal:$('input#id_bed_awal').val()},  
                dataType : "json",
                beforeSend : function(){
                    Metronic.blockUI({boxed: true });
                },
                success:function(data1)         
                { 
                	// data1[3] = lantai didapatkan dari pindah_bed

                    $.ajax ({ 
                        type: "POST",
                        url: baseAppUrl + "show_denah_lantai_html",
               	 		data:  {lantai: data1[3]},  
                        dataType : "text",
                        success: function(data2)         
                        { 
                            $("div.svg_file_lantai_"+data1[3]).html(data2);
                    		mb.showMessage(data1[0],data1[1],data1[2]);
                        },
                    });
                },
                complete : function() {
                    Metronic.unblockUI();
                }
            });            
        });
    }

    
	
</script>