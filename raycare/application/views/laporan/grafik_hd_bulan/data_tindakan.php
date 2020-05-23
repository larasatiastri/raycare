<form id="modal_identitas" name="modal_identitas" role="form" class="form-horizontal" autocomplete="off">
	<input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Tanggal : <b style="color:#00afef;"><?=date('d M Y', strtotime($tanggal))?></b></h4>
    </div>
	<div class="modal-body" id="section-identitas">

				<div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover table-advance" id="table_detail_tindakan">
                        <thead>
                            <tr role="row">
                                <th class="text-left" rowspan="2" width="10px"><?=translate('Bulan', $this->session->userdata('language'))?></th>
                                <th class="text-center" colspan="2" width="15px"><?=date('M y', strtotime($tanggal))?></th>
                                <th class="text-center" colspan="2" width="30px"><?=date('M y', strtotime($tanggalpast))?></th>
                                <th class="text-center" colspan="2" width="15px"><?=date('M y', strtotime($tanggalpast2))?></th>
                                <th class="text-center" colspan="2" width="30px"><?=date('M y', strtotime($tanggalpast3))?></th>
                            </tr>
                            <tr>
                            	
                                <th class="text-center">
                            		<?=date('d', strtotime($tanggal))?>
                            	</th>
                            	<th class="text-center">
                            		<?='01 - '.date('d', strtotime($tanggal))?>
                            	</th>
                            	<th class="text-center">
                            		<?=date('d', strtotime($tanggalpast))?>
                            	</th>
                            	<th class="text-center">
                            		<?='01 - '.date('d', strtotime($tanggalpast))?>
                            	</th>
                            	<th class="text-center">
                            		<?=date('d', strtotime($tanggalpast2))?>
                            	</th>
                            	<th class="text-center">
                            		<?='01 - '.date('d', strtotime($tanggalpast2))?>
                            	</th>
                            	<th class="text-center">
                            		<?=date('d', strtotime($tanggalpast3))?>
                            	</th>
                            	<th class="text-center">
                            		<?='01 - '.date('d', strtotime($tanggalpast3))?>
                            	</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr>
                                <th class="text-left">
                                   Tindakan
                                </th>
                            	<td class="text-center">
                            		<?=$countdatenow?>
                            	</td>
                            	<td class="text-center">
                            		<?=$countmonthnow?>
                            	</td>
                            	<td class="text-center">
                            		<?=$countdatepast?>
                            	</td>
                            	<td class="text-center">
                            		<?=$countmonthpast?>
                            	</td>
                            	<td class="text-center">
                            		<?=$countdatepast2?>
                            	</td>
                            	<td class="text-center">
                            		<?=$countmonthpast2?>
                            	</td>
                            	<td class="text-center">
                            		<?=$countdatepast3?>
                            	</td>
                            	<td class="text-center">
                            		<?=$countmonthpast3?>
                            	</td>
                            </tr>
                        </tbody>
                    </table>
				</div>
                <?php
                	$totalnow = array(
                		0 => $countdatepast,
                		1 => $countdatepast2,
                		2 => $countdatepast3,
                	);

                	$tahunnow = array(
                		$countdatepast => date('d M Y', strtotime($tanggalpast)),
                		$countdatepast2 => date('d M Y', strtotime($tanggalpast2)),
                		$countdatepast3 => date('d M Y', strtotime($tanggalpast3))
                	);
                	
                	

                	$tahun = array();
                	$total = array();
                	foreach ($grand_total_year as $key=>$row) {
                		$total[$key] = $row['jumlah_total'];
                		$tahun[$row['jumlah_total']] = date('M Y', strtotime($row['month']));
                	}

                	$tahunall = array();
                	$totalall = array();
                	foreach ($grand_total_all as $key=>$row) {
                		$totalall[$key] = $row['jumlah_total'];
                		$tahunall[$row['jumlah_total']] = date('M Y', strtotime($row['month']));
                	}

                	
                ?>
                <div class="table-scrollable">
                    <table class="table table-light" id="table_tindakan_tertinggi">
                       
                        <tbody>
                            <tr role="row">
                                <th class="text-center" rowspan="4" width="10px" style="background-color:#00afef;color:#FFFFFF;"><?=translate('Tindakan Tertinggi', $this->session->userdata('language'))?></th>
                                <th class="text-left" width="30px" style="background-color:#eaeaea;"><?=translate('Range', $this->session->userdata('language'))?></th>
                                <th class="text-left" width="30px" style="background-color:#eaeaea;"><?=translate('Tindakan', $this->session->userdata('language'))?></th>
                                <th class="text-left" width="30px" style="background-color:#eaeaea;"><?=translate('Waktu', $this->session->userdata('language'))?></th>
                            </tr>
                            <tr>
                                <td class="text-left" width="10px"><span class="bold theme-font"><?=translate('3 Bulan', $this->session->userdata('language'))?></span></td>
                                <td class="text-left" width="10px"><span class="bold theme-font"><?=max($totalnow)?></span></td>
                                <td class="text-left" width="10px"><span class="bold theme-font"><?=$tahunnow[max($totalnow)]?></span></td>
                            </tr>
                            <tr>
                                <td class="text-left" width="10px"><span class="bold theme-font"><?=translate('Tahun', $this->session->userdata('language')).' '.date('Y', strtotime($tanggal))?></span></td>
                                <td class="text-left" width="10px"><span class="bold theme-font"><?=max($total)?></span></td>
                                <td class="text-left" width="10px"><span class="bold theme-font"><?=$tahun[max($total)]?></span></td>
                            </tr>
                            <tr>
                                <td class="text-left" width="10px"><span class="bold theme-font"><?=translate('Keseluruhan', $this->session->userdata('language'))?></span></td>
                                <td class="text-left" width="10px"><span class="bold theme-font"><?=max($totalall)?></span></td>
                                <td class="text-left" width="10px"><span class="bold theme-font"><?=$tahunall[max($totalall)]?></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                

	</div>
	<div class="modal-footer">
		<a class="btn default" id="close" data-dismiss="modal"><?=translate("Tutup", $this->session->userdata("language"))?></a>
		
	</div>
</form>
