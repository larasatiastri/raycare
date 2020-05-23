<?php

	$msg = translate('Anda yakin akan menambahkan invoice di tindakan ini?', $this->session->userdata('language'));
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

?>
<form id="modal_identitas" name="modal_identitas" role="form" class="form-horizontal" autocomplete="off">
	<input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       

	<div class="modal-body" id="section-identitas">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<?=$data_item['nama']?>
				</div>
			</div>
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
	                <?php
	                if($data_detail != ''){
						$identitas_row_template = '';
						$total = 1;
						foreach ($data_detail as $detail) {
							$data_item_satuan = $this->item_satuan_m->get($detail['item_satuan_id']);
							$konversi = $this->item_m->get_nilai_konversi($detail['item_satuan_id']);
							$pengiriman_identitas = $this->pengiriman_identitas_m->get_data_inventory($detail['id'])->result_array();
							$i = $total;
							if(count($pengiriman_identitas)){
								$count = count($pengiriman_identitas);

                                if ($i >= $count) {
                                    $total = $i + 1;
                                }
								$type = '';
								$type .='<td>'.$i.'</td>';
								$type .='<td>'.$data_item_satuan->nama.'<input type="hidden" class="form-control" name="identitas_'.$item_id.'['.$i.'][item_satuan]" value="'.$detail['item_satuan_id'].'"></td>';
								foreach ($pengiriman_identitas as $kirim_identitas) {
									$z = 1;
									$kirim_iden_detail = $this->pengiriman_identitas_detail_m->get_data_identitas($kirim_identitas['id'],1)->result_array();
									foreach ($kirim_iden_detail as $row_identitas) {
										if($row_identitas['identitas_id'] == 3 )
											$type .='<td><label>'.date('d-M-Y', strtotime($row_identitas['value'])).'</label><input type="hidden" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][id]" value="'.$row_identitas['identitas_id'].'">
                                                    <input type="hidden" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][judul]" value="'.$row_identitas['judul'].'">
                                                    <input type="hidden" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][value]" value="'.$row_identitas['value'].'"></td>';
										else	
											$type .='<td><label>'.$row_identitas['value'].'</label><input type="hidden" id="identitas_id_'.$row_identitas['identitas_id'].'_'.$i.'" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][id]" value="'.$row_identitas['identitas_id'].'">
                                                    <input type="hidden" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][judul]" value="'.$row_identitas['judul'].'">
                                                    <input type="hidden" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][value]" value="'.$row_identitas['value'].'"></td>';

									$z++;
									}
									$type .='<td>'.$kirim_identitas['jumlah'].'</td>';

									$identitas_row_template .=  '<tr id="identitas_row_'.$i.'" class="table_item">'.$type.'</tr>';
								}
							}

						}
					}
					
					
				?>

					<div class="form-body" >
						<span id="tpl_identitas" class="hidden"><?=htmlentities($identitas_row_template)?></span>

		                <table class="table table-striped table-bordered table-hover" id="table_identitas">
		                    <thead>
		                        <tr class="heading">
		                            <th class="text-center" style="width : 5% !important;"><?=translate("No", $this->session->userdata("language"))?></th>
		                            <th class="text-center" style="width : 8% !important;"><?=translate("Satuan", $this->session->userdata("language"))?></th>
		                            <?php
		                                $widthCell = '';

		                                if (!empty($data_item_identitas)) {
		                                	$data_item_identitas = object_to_array($data_item_identitas);
		                                    foreach ($data_item_identitas as $item_identitas) {
		                                        if ($item_identitas['identitas_id'] != NULL) {
		                                            echo '<th class="text-center" style="width : 16% !important;">'.translate($item_identitas['judul'], $this->session->userdata("language")).'</th>';
		                                            # code...
		                                        }
		                                    }
		                                }
		                                
		                            ?>
		                            <th class="text-center" style="width : 15% !important;"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
		                        </tr>
		                    </thead>
		                        
		                    <tbody id="content_identitas">
		                        <?php
		                        	echo $identitas_row_template;
		                        ?>
		                    </tbody>
		                </table>
					</div>
			        
			        <input type="hidden" class="form-control" id="total_jml" name="total_jml"></input>
	   			</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a class="btn btn-primary" id="close" data-dismiss="modal"><?=translate("OK", $this->session->userdata("language"))?></a>
		
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){

	$table = $('#table_identitas');
	$inputJumlah = $('input.jumlah', $table);
	$inputJumlah.on('change keyup', function() {
		var jml = $(this).val();
		$(this).attr('value', jml);

		calculateTotal();
	});

    handleCheckHtml();
    handleConfirmSave();


});	

function handleCheckHtml(){
	rowId = $('input#row_id').val();
	$row = $('#' + rowId);

	html_content = $('div#identitas_row', $row).html();
	if(html_content != ''){
		$('tbody#content_identitas').html($('div#identitas_row', $row).html());
		calculateTotal();
		$table = $('#table_identitas');
		$inputJumlah = $('input.jumlah', $table);
		$inputJumlah.on('change keyup', function() {
			var jml = $(this).val();
			$(this).attr('value', jml);

			calculateTotal();
		});
	}
}

function addFieldsetParent(form)
{
    var 
        $section           = form.section,
        $fieldsetContainer = $('ul.list-unstyled', $section),
        counter            = form.counter(),
        $newFieldset       = $(form.template(counter)).appendTo($fieldsetContainer)
    ;

    $('select.select2me', $newFieldset).select2({});
   	
   	$('input[name$="[jumlah]"]', $newFieldset).on('change keyup', function(){

   		calculateTotal();
   	});

    $('a.del-this', $newFieldset).on('click', function(){
    
        handleDeleteFieldset($(this).parents('.fieldset').eq(0));
    });

    $('input[name$="[value]"]', $newFieldset).on('change', function() {
    	// body...
    	$('input[name$="[value]"]', $newFieldset).attr('value', $(this).val());
    });
    $('select[name$="[value]"]', $newFieldset).on('change', function(){
    	var id = $(this).val();

    	$('option:selected', this).attr('selected','selected');
    	$.ajax({
            type     : 'POST',
            url      : mb.baseUrl() + 'gudang/pengeluaran_barang/get_stok_identitas',
            data     : {id:id},
            dataType : 'json',
            beforeSend : function(){
                Metronic.blockUI({boxed: true });
            },
            success  : function( results ) {
                
                $('input[name$="[jumlah]"]', $newFieldset).attr('max',results.stok);
               
            },
            complete : function(){
                Metronic.unblockUI();
            }
        });
    });
    //jelasin warna hr pemisah antar fieldset
    $('hr', $newFieldset).css('border-color', 'rgb(228, 228, 228)');
};

function handleDeleteFieldset($fieldset)
{
	var 
        $parentUl     = $fieldset.parent(),
        fieldsetCount = $('.fieldset', $parentUl).length,
        hasId         = false ; 

        if (fieldsetCount<=1) return; //jika fieldset cuma tinggal atu lagi, jgn dihapus.
        $fieldset.remove();            
    
}

function calculateTotal(){
	$form = $('#modal_identitas');
	$table = $('#table_identitas');
	$inputJumlah = $('input.jumlah', $table);

	total = 0;
	$.each($inputJumlah, function(index, jml){
		var konversi = $(this).data('konversi');

		total = parseInt(total) + (parseInt($(this).val()) * konversi);
	});

	$('input#total_jml', $form).val(total);
}
function handleConfirmSave() {
	$('a#confirm_save').click(function(){
		rowId = $('input#row_id').val();
		$row = $('#' + rowId);

		$('div#identitas_row', $row).html($('tbody#content_identitas').html());
		$('input[name$="[jumlah_kirim]"]', $row).attr('value', $('input#total_jml').val());

		$('a#close').click();
	});
}




</script>