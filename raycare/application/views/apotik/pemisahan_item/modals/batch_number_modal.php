<div class="modal-header">
    <button type="button" class="btn green-haze hidden" id="btnOK">OK</button>
	<button type="button" class="closeModal hidden" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title">
		<span class="caption-subject font-blue-sharp bold uppercase judul"><?=translate("Identitas Item", $this->session->userdata("language"))?></span>
	</h4>
</div>
<div class="modal-body">
    <?php
    	$item_identitas='';
        $identitas_row_template='';
        $check;
        $get_group_indetitas = $this->inventory_m->get_by(array('item_id' => $item_id, 'item_satuan_id' => $item_satuan_id, 'gudang_id' => $gudang_id));

        // die_dump($this->db->last_query());
        $group_indetitas = object_to_array($get_group_indetitas);
        // die_dump($group_indetitas);
        $total = 1;
        foreach ($group_indetitas as $group) {

            $cek_identitas = $this->inventory_m->cek_identitas($group['inventory_id'])->result_array();

            // die_dump($cek_identitas);
            if (!empty($cek_identitas)) {
                // $get_item_identitas = $this->inventory_m->get_item_identitas($group['inventory_id']);

                $get_item_identitas = $this->inventory_identitas_m->get_by(array('inventory_id' => $group['inventory_id']));

                $item_identitas = object_to_array($get_item_identitas);
                // die_dump($item_identitas);
                $i = $total;
                foreach ($item_identitas as $data_item_identitas) {
                // die_dump($data_item_identitas);

                    $get_item_identitas_detail = $this->inventory_m->get_item_identitas($data_item_identitas['inventory_identitas_id']);
                    $item_identitas_detail = $get_item_identitas_detail->result_array();
                    // die_dump($item_identitas_detail);
                    $count = count($item_identitas);

                    if ($i >= $count) {
                        $total = $i + 1;
                    }
                    $type = '';

                    $type .= '<td class="text-center no_urut" id="no">'.$i.'</td>';

                    $z = 1;
                    foreach ($item_identitas_detail as $data) {
                        // die_dump($data);
                        if ($data['inventory_identitas_id'] != NULL) {
                            $type .= '<td>
                                        <label class="control-label">'.$data['value'].'</label>
                                        <input type="hidden" id="identitas_id_'.$data['identitas_id'].'_'.$i.'" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][id]" value="'.$data['identitas_id'].'">
                                        <input type="hidden" id="identitas_judul_'.$data['identitas_id'].'_'.$i.'" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][judul]" value="'.$data['judul'].'">
                                        <input type="hidden" id="identitas_value_'.$data['identitas_id'].'_'.$i.'" name="identitas_detail_'.$item_id.'_'.$i.'['.$z.'][value]" value="'.$data['value'].'">
                                      </td>';
                            // die_dump($type);
                        }
                    $z++;
                        
                    }
                    $type .= '<td class="text-center">
                            <label class="control-label">'.$data['jumlah'].'</label>
                            <input type="number" class="form-control text-right stock_item hidden" id="identitas_stock_'.$i.'" name="identitas_'.$item_id.'['.$i.'][stock]" min="1" value="'.$data['jumlah'].'">
                            <input type="number" class="form-control text-right update_jumlah" id="identitas_update_'.$i.'" name="identitas_'.$item_id.'['.$i.'][update_jumlah]">
                            <input type="number" class="hidden" id="identitas_harga_beli_'.$i.'" name="identitas_'.$item_id.'['.$i.'][harga_beli]" value="'.$data['harga_beli'].'">
                            <input type="hidden" id="identitas_gudang_id_'.$data['identitas_id'].'_'.$i.'" name="identitas_'.$item_id.'['.$i.'][gudang_id]" value="'.$group['gudang_id'].'">
                            <input type="hidden" id="identitas_pmb_id_'.$data['identitas_id'].'_'.$i.'" name="identitas_'.$item_id.'['.$i.'][pmb_id]" min="1" value="'.$group['pmb_id'].'">
                            <input type="hidden" id="identitas_inventory_id_'.$data['identitas_id'].'_'.$i.'" name="identitas_'.$item_id.'['.$i.'][inventory_id]" value="'.$group['inventory_id'].'">
                            <input type="number" class="form-control text-right hidden" id="identitas_inventory_identitas_id_'.$i.'" name="identitas_'.$item_id.'['.$i.'][inventory_identitas_id]" min="1" value="'.$data['inventory_identitas_id'].'">

                            
                          </td>';
                        $type .= '<td>
                                    <input type="number" class="form-control text-right jumlah_item" id="identitas_jumlah_'.$i.'" name="identitas_'.$item_id.'['.$i.'][jumlah]" min="0"  max="'.$data['jumlah'].'" data-row="'.$i.'" value="0">
                                  </td>';


                        
                        $identitas_row_template[] =  '<tr id="identitas_row_template'.$i.'" class="table_item">'.$type.'</tr>';
                        // die_dump($identitas_row_template);
                $i++;                      
                }
            }
            // else{
            //     $i = 0;
            //     $item_identitas_detail = array();
            //     $data = array();
            //     $identitas_row_template[] = '';
            // }
        }   

    
        // echo '<input type="hidden" id="identitasCounter" name="identitasCounter" value="'.$i.'">';
        $type = '';

        $type .= '<td></td>
                  <td>
                    <input type="number" class="form-control text-right jumlah_item" id="identitas_jumlah_{0}" name="identitas_'.$item_id.'[{0}][jumlah]" min="0"  data-row="{0}" value="0">
                    <input type="number" class="form-control hidden text-right jumlah_per_satuan" id="identitas_jumlah_per_satuan_{0}" name="identitas_'.$item_id.'_'.$item_satuan_id.'[{0}][jumlah_per_satuan]" min="0"  data-row="{0}" value="0">
                    <input type="number" class="form-control hidden text-right send-input" id="identitas_numrow_{0}" name="identitas_'.$item_id.'_'.$item_satuan_id.'[{0}][numrow]" min="0"  data-row="{0}" value="{0}">
                    <input type="number" class="form-control text-right hidden" id="identitas_inventory_identitas_id_'.$i.'" name="identitas_'.$item_id.'['.$i.'][inventory_identitas_id]" min="1" value="">
                  </td>';

        $identitas_row =  '<tr id="identitas_row_{0}" class="table_item">'.$type.'</tr>';
    ?>

    <span id="tpl_identitas" class="hidden"><?=htmlentities($identitas_row)?></span>

    <table class="table table-striped table-bordered table-hover" id="table_identitas_item">
        <thead>
            <tr class="heading">
                <th class="text-center" style="width : 5% !important;"><?=translate("No", $this->session->userdata("language"))?></th>
                <?php
                    $widthCell = '';

                    if (!empty($item_identitas)) {
                        foreach ($item_identitas_detail as $data) {
                            if ($data['identitas_id'] != NULL) {
                                echo '<th class="text-center" style="width : 16% !important;">'.translate($data['judul'], $this->session->userdata("language")).'</th>';
                                # code...
                            }
                        }
                    }
                    
                ?>
                <th class="text-center" style="width : 5% !important;"><?=translate("Stock", $this->session->userdata("language"))?></th>
                <th class="text-center" style="width : 15% !important;"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
            </tr>
        </thead>
            
        <tbody>
            <?php foreach ($identitas_row_template as $row):?>
                <?=$row?>
            <?php endforeach;?>
        </tbody>
    </table>
</div>
<div class="modal-footer">
	<a class="btn default" data-dismiss="modal" id="closeModal"><?=translate('Batal', $this->session->userdata('language'))?></a>
    <a class="btn btn-primary" id="modal_ok"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
<script>
	$( document ).ready(function() {
	    // sub_konvert_options();
	    // hasil_konvert_options();
	    handlemodalOk();
	    handleInputChange();
	    // addKonversiRow();
	});

	var  $tableKonversi = $('#table_konversi'),
     tplKonversiRow  = $.validator.format( $('#tpl_item_row_konversi').text()),
     itemCounter        = $('input#itemRow').val();

    function handleInputChange()
    {
    		// alert('a');
		$.each($('input.stock_item', $('#table_identitas_item')), function(idx, value){
			// alert('a');
			var id = idx+1;
			var stok = parseInt(this.value);

			$('input#identitas_jumlah_'+id).keyup(function()
			{
				// alert(stok);
				var update = stok-parseInt($('input#identitas_jumlah_'+id).val());
				
				if (!isNaN(update))
        		{
        			$('input#identitas_update_'+id).val(update);
	            
	            } else {

	            $('input#identitas_update_'+id).val(0);

            	}
					
			})
		})
    }

	function handlemodalOk()
	{
		$('a#modal_ok').click(function(){

			var total_jumlah = 0;
            $.each($('input.jumlah_item', $('#table_identitas_item')), function(idx, value){
                total_jumlah = total_jumlah + parseInt(this.value);
                $(this).attr('value', $(this).val());
            });

            // alert(total_jumlah);
            // var total_jumlah = 0;
            $.each($('input.update_jumlah', $('#table_identitas_item')), function(idx, value){
                // total_jumlah = total_jumlah + parseInt(this.value);
                $(this).attr('value', $(this).val());
            });

            //$('input#warehouse_id').val($(this).val());
            var numRow   = itemCounter++ -1,
                $row     = $('#item_row_'+numRow, $tableKonversi),
                max_item = parseInt($('input[name$="[item_sebelum_jumlah]"]', $row).val());

            // alert(parseInt($('input[name$="[item_sebelum_jumlah]"]', $row).val()));
            if(total_jumlah > max_item)
            {
            	$('a#closeModal').click();
            	bootbox.confirm('Jumlah Item Tidak Sesuai', function(result) {
	                if (result==true) {
	                    
	                }
	            });
            }
            else if(total_jumlah < max_item)
            {
            	$('a#closeModal').click();
            	bootbox.confirm('Jumlah Item Tidak Sesuai', function(result) {
	                if (result==true) {
	                    
	                }
	            });
            }
            else
            {
           		$('div#simpan_identitas', $row).html($('table#table_identitas_item > tbody').html());
            	$('a#closeModal').click();
            }
        })
	}
</script>