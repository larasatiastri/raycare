<form class="form-horizontal" id="form_proses_notes">
	<div class="modal-body">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<?=translate("PROSES NOTE PINDAH SHIFT", $this->session->userdata("language"))?> <?=$data_tindakan['no_transaksi'].' ( '.$data_pasien['nama'].' ['.$data_bed['kode'].'] )'?>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					<input class="form-control" type="hidden" name="tindakan_hd_id" id="tindakan_hd_id" value="<?=$data_tindakan['id']?>"></input>
					<input class="form-control" type="hidden" name="bed_id" id="bed_id" value="<?=$data_bed['id']?>"></input>
					<input class="form-control" type="hidden" name="lantai_id" id="lantai_id" value="<?=$data_bed['lantai_id']?>"></input>
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="tabel_proses_note">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="1%"><?=translate("No", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="30%"><?=translate("Catatan", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Dibuat Oleh", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Diproses Oleh", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Ya ", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Tdk", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <?//=$item_row?> -->
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<?php
		$msg = translate('Anda yakin untuk memproses note ini?', $this->session->userdata('language'));
	?>
	<div class="modal-footer">
		<a class="btn default" id="close_modal" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
		<a class="btn btn-primary" data-confirm="<?=$msg?>" id="proses_notes"><?=translate('OK', $this->session->userdata('language'))?></a>
	</div>
</form>

<script type="text/javascript">

	$(document).ready(function() 
	{
		$form = $('#form_proses_notes');

		baseAppUrl       = mb.baseUrl() + 'klinik_hd/transaksi_perawat/';
		handleSaveNotes();
		handleDataTabelNotes();
		
	});

	function handleDataTabelNotes() 
	{
		oTableNotes = $('#tabel_proses_note').dataTable({
          	'processing'            : true,
          	'serverSide'            : true,
          	'language'              : mb.DTLanguage(),
          	'ajax'                  : {
              'url' : baseAppUrl + 'listing_note_shift/'+$('input#tindakan_hd_id',$form).val(),
              'type' : 'POST',
          	},          
          	'pageLength'            : 5,
          	'paginate'				: false,
          	'filter'				: false,
          	'info'				: false,
          	'lengthMenu'            : [[5,10, 25, 50, 100], [5,10, 25, 50, 100]],
          	'order'                 : [[0, 'asc']],
          	'columns'               : [
              { 'visible' : true, 'searchable': true, 'orderable': true },
              { 'visible' : true, 'searchable': true, 'orderable': true },
              { 'visible' : true, 'searchable': true, 'orderable': false },
              { 'visible' : true, 'searchable': true, 'orderable': false },
              { 'visible' : true, 'searchable': true, 'orderable': false },
              { 'visible' : true, 'searchable': true, 'orderable': false },
          	]
        });

		$('#tabel_proses_note').on('draw dt', function() {
			$('input[type=radio]', this).uniform();
		});
	}
	
	function handleSaveNotes()
	{
        $('a#proses_notes').on('click', function(){

        	var msg = $(this).data('confirm');
        	bootbox.confirm(msg, function(result){
        		if(result === true)
        		{
		            $.ajax ({ 
		                type: "POST",
		                url: baseAppUrl + "edit_notes",  
		                data:  $form.serialize(),  
		                dataType : "json",
		                beforeSend : function(){
			                Metronic.blockUI({boxed: true });
			            },
		                success:function(data1)         
		                { 
		                // 	// data1[3] = lantai didapatkan dari tolak_bed
		                	$.ajax ({ 
			                    type: "POST",
			                    url: baseAppUrl + "show_denah_lantai_html",
		           	 			data:  {lantai: data1[3] },  
			                    dataType : "text",
			                    success: function(data2)         
			                    { 
			                    	$('a#close_modal').click();
			                        $("div.svg_file_lantai_"+data1[3]).html(data2);
		                    		mb.showMessage(data1[0],data1[1],data1[2]);
			                    },
			                });
		                },
		                complete : function() {
			                Metronic.unblockUI();
			            }
		            });                    			
        		}
        	});

        });
    }
</script>