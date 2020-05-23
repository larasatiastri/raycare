<?php

	$msg = translate('Anda yakin akan menambahkan invoice di tindakan ini?', $this->session->userdata('language'));
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$inv_id = '';
    $inv_identitas_id = '';


    $checkbn = ($data_item_identitas[0] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('Batch Number', $this->session->userdata("language")).'</th>':'';
    $checkexpire = ($data_item_identitas[1] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('Expire Date', $this->session->userdata("language")).'</th>':'';
    $checkdll1 = ($data_item_identitas[2] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('ID1', $this->session->userdata("language")).'</th>':'';
    $checkdll2 = ($data_item_identitas[3] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('ID2', $this->session->userdata("language")).'</th>':'';
    $checkdll3 = ($data_item_identitas[4] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('ID3', $this->session->userdata("language")).'</th>':'';

?>
<form id="modal_identitas" name="modal_identitas" role="form" class="form-horizontal" autocomplete="off">
	<input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       
	<input type="hidden" id="row_id" name="row_id" required="required" class="form-control" value="<?=$row_id?>">                       
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title"><?=$data_item['nama']?></h4>
	</div>
	<div class="modal-body" id="section-identitas">
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
	                if($data_inventory != ''){
						$identitas_row_template = '';
						$i = 1;

						foreach ($data_inventory as $inventory) {
							$konversi = $this->item_m->get_nilai_konversi($inventory['item_satuan_id']);
								$type = '';
								$type .='<tr id="identitas_row_'.$i.'" class="table_item"><td>'.$i.'</td>';
								$type .='<td>'.$inventory['nama_gudang'].'<input type="hidden" class="form-control" name="identitas_'.$item_id.'_'.$index.'['.$i.'][gudang_id]" value="'.$inventory['gudang_id'].'"></td>';
								$type .='<td>'.$inventory['nama_satuan'].'<input type="hidden" class="form-control" name="identitas_'.$item_id.'_'.$index.'['.$i.'][item_id]" value="'.$inventory['item_id'].'"><input type="hidden" class="form-control" name="identitas_'.$item_id.'_'.$index.'['.$i.'][item_satuan]" value="'.$inventory['item_satuan_id'].'"></td>';
								$type .='<td><label>'.$inventory['bn_sn_lot'].'</label><input type="hidden" id="bn_sn_lot_'.$i.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][bn_sn_lot]" value="'.$inventory['bn_sn_lot'].'"></td>';

								$type .='<td><label>'.date('d M Y', strtotime($inventory['expire_date'])).'</label><input type="hidden" id="expire_date_'.$i.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][expire_date]" value="'.$inventory['expire_date'].'"></td>';
						

								$type .='<td>'.$inventory['jumlah'].'<input type="number" class="form-control text-right stock_item hidden" id="identitas_stock_'.$i.'" name="identitas_'.$item_id.'_'.$index.'['.$i.'][stock]" min="1" value="'.$inventory['jumlah'].'">';
	

								$type .='<td><input type="number" class="form-control jumlah" name="identitas_'.$item_id.'_'.$index.'['.$i.'][jumlah_identitas]" min="0" value="0" max="'.$inventory['jumlah'].'" data-konversi="'.$konversi.'"></td></tr>';
								
								$identitas_row_template .=  $type;

							$i++;
						}
					}
				?>

					<div class="table-scrollable">
						<span id="tpl_identitas" class="hidden"><?=htmlentities($identitas_row_template)?></span>
				<?php 
					if($data_inventory != ''){
				?>

		                <table class="table table-striped table-bordered table-hover" id="table_identitas">
		                    <thead>
                                <tr class="heading">
                                    <th class="text-center" style="width : 5% !important;"><?=translate("No", $this->session->userdata("language"))?></th>
                                    <th class="text-center" style="width : 8% !important;"><?=translate("Gudang", $this->session->userdata("language"))?></th>
                                    <th class="text-center" style="width : 5% !important;"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                                    <?php
                                        echo $checkbn.$checkexpire.$checkdll1.$checkdll2.$checkdll3;
                                        
                                    ?>
                                    <th class="text-center" style="width : 10% !important;"><?=translate("Stock", $this->session->userdata("language"))?></th>
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
				<?php }else{
					echo 'Stok barang tidak tersedia';
				}

				?>
			        
			        <input type="hidden" class="form-control" id="total_jml" name="total_jml"></input>
		</div>
	</div>
	<div class="modal-footer">
		<a class="btn default" id="close" data-dismiss="modal"><?=translate("Batal", $this->session->userdata("language"))?></a>
		<a id="confirm_save_modal" class="btn btn-primary"><?=translate("Simpan", $this->session->userdata("language"))?></a>
		<button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
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
	$('a#confirm_save_modal').click(function(){
		rowId = $('input#row_id').val();
		$row = $('#' + rowId);

		jumlah_pesan = parseInt($('input[name$="[jumlah_pesan]"]', $row).val());
		jumlah_isi = parseInt($('input#total_jml', $form ).val());
		
		if(jumlah_isi <= jumlah_pesan){
			$('div#identitas_row', $row).html($('tbody#content_identitas').html());
			$('input[name$="[jumlah_kirim]"]', $row).attr('value', $('input#total_jml').val());
			
			$('a#close').click();
		}else{
			bootbox.alert('Jumlah kirim tidak boleh lebih dari jumlah pesan!');
		}
		
	});
}




</script>