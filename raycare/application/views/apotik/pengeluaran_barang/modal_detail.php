<?php

	$msg = translate('Anda yakin akan menambahkan invoice di tindakan ini?', $this->session->userdata('language'));
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$inv_id = '';
	$inv_identitas_id = '';
	

			
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
						<?php
							$total = 1;
							$identitas_row_template = '';
							foreach ($pengeluaran_detail as $detail) {
								$pengeluaran_identitas = $this->pengeluaran_barang_identitas_m->get_data_identitas($detail['id'])->result_array();
								//die(dump($pengeluaran_identitas));
									//(dump($detail));
								$i = $total;
								$count = count($pengeluaran_identitas);

                                if ($i >= $count) {
                                    $total = $i + 1;
                                }
                                $type = '';
								$type .='<td>'.$i.'</td>';
								foreach ($pengeluaran_identitas as $identitas) {

									$keluar_ident_detail = $this->pengeluaran_barang_identitas_detail_m->get_data_identitas_detail($identitas['id'])->result_array();
									
									$detail_iden = '';
									
									foreach ($keluar_ident_detail as $row_identitas) {
										if($row_identitas['identitas_id'] == 3 ){
											$detail_iden .='<td><label>'.date('d-M-Y', strtotime($row_identitas['value'])).'</label></td>';
										}
										else{
											$detail_iden .='<td><label>'.$row_identitas['value'].'</label></td>';
										}	
											
											$row = $type.$detail_iden.'<td>'.$identitas['jumlah'].'</td>';
									}
									$identitas_row_template .=  '<tr id="identitas_row_'.$i.'" class="table_item">'.$row.'</tr>';	

									
								}
									
							}

						?>
		                <table class="table table-striped table-bordered table-hover" id="table_identitas">
		                    <thead>
		                        <tr class="heading">
		                            <th class="text-center" style="width : 5% !important;"><?=translate("No", $this->session->userdata("language"))?></th>
		                            <?php
		                                $widthCell = '';

		                                if (!empty($data_item_identitas)) {
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
			        
	   			</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a class="btn default" id="close" data-dismiss="modal"><?=translate("OK", $this->session->userdata("language"))?></a>
		
	</div>
</form>
