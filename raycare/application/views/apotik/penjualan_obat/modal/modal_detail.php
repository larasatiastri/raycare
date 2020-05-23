<?php

	$msg = translate('Anda yakin akan menambahkan invoice di tindakan ini?', $this->session->userdata('language'));
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$checkbn = ($data_item_identitas[0] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('Batch Number', $this->session->userdata("language")).'</th>':'';
    $checkexpire = ($data_item_identitas[1] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('Expire Date', $this->session->userdata("language")).'</th>':'';
    $checkdll1 = ($data_item_identitas[2] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('ID1', $this->session->userdata("language")).'</th>':'';
    $checkdll2 = ($data_item_identitas[3] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('ID2', $this->session->userdata("language")).'</th>':'';
    $checkdll3 = ($data_item_identitas[4] == '1')?'<th class="text-center" style="width : 16% !important;">'.translate('ID3', $this->session->userdata("language")).'</th>':'';

			
?>
<form id="modal_identitas" name="modal_identitas" role="form" class="form-horizontal" autocomplete="off">
	<input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       

	<div class="modal-body" id="section-identitas">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<?=$data_item['nama'].' / '.$data_item_satuan['nama']?>
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
	                
					<div class="form-body" >
		                <table class="table table-striped table-bordered table-hover" id="table_identitas">
		                   <thead>
                                <tr class="heading">
                                    <th class="text-center" style="width : 5% !important;"><?=translate("No", $this->session->userdata("language"))?></th>
                                    <?php
                                        echo $checkbn.$checkexpire.$checkdll1.$checkdll2.$checkdll3;
                                        
                                    ?>
                                    <th class="text-center" style="width : 15% !important;"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                                </tr>
                            </thead>
		                        
		                    <tbody id="content_identitas">
		                         <?php
                                    if($data_item['is_identitas'] == 1){
                                        $i = 1;
                                        $index = 0;
                                        foreach ($penjualan_detail as $inventory) {
                                            $rowbn = ($inventory['bn_sn_lot'] != null)?'<td class="text-left" style="width : 16% !important;"><input type="hidden" id="bn_sn_lot_'.$i.'" name="identitas_'.$item_id.'['.$index.'][bn_sn_lot]" value="'.$inventory['bn_sn_lot'].'">'.$inventory['bn_sn_lot'].'</td>':'';
                                            $rowexpire = ($inventory['expire_date'] != null)?'<td class="text-left" style="width : 16% !important;"><input type="hidden" id="bn_sn_lot_'.$i.'" name="identitas_'.$item_id.'['.$index.'][expire_date]" value="'.date('d M Y', strtotime($inventory['expire_date'])).'">'.date('d M Y', strtotime($inventory['expire_date'])).'</td>':'';
                                            $rowdll1 = ($inventory['identitas_1'] != null)?'<td class="text-left" style="width : 16% !important;"><input type="hidden" id="bn_sn_lot_'.$i.'" name="identitas_'.$item_id.'['.$index.'][identitas_1]" value="'.$inventory['identitas_1'].'">'.$inventory['identitas_1'].'</td>':'';
                                            $rowdll2 = ($inventory['identitas_2'] != null)?'<td class="text-left" style="width : 16% !important;"><input type="hidden" id="bn_sn_lot_'.$i.'" name="identitas_'.$item_id.'['.$index.'][identitas_2]" value="'.$inventory['identitas_2'].'">'.$inventory['identitas_2'].'</td>':'';
                                            $rowdll3 = ($inventory['identitas_3'] != null)?'<td class="text-left" style="width : 16% !important;"><input type="hidden" id="bn_sn_lot_'.$i.'" name="identitas_'.$item_id.'['.$index.'][identitas_3]" value="'.$inventory['identitas_3'].'">'.$inventory['identitas_3'].'</td>':'';


                                            echo '<td class="text-center" style="width : 5% !important;">'.$i.'</td>'.$rowbn.$rowexpire.$rowdll1.$rowdll2.$rowdll3.' <td class="text-left" style="width : 16% !important;">'.$inventory['jumlah'].'</td></tr>';

                                            $i++;
                                            $index++;
                                        }
                                    }
                                ?>
		                    </tbody>
		                </table>
					</div>
			        
	   			</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a class="btn default" id="close" data-dismiss="modal"><?=translate("OK", $this->session->userdata("language"))?></a>
		
	</div>
</form>
