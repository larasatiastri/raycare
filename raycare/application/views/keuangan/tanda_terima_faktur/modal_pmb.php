<form class="form-horizontal" id="form_add_notes">
	
	<div class="modal-body">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<?=translate("Nama Item :", $this->session->userdata("language"))?> <?=$data_po_detail['nama']?>
				</div>
				<div class="actions">
					<a class="btn btn-icon-only btn-default btn-circle add-notes">
						<i class="fa fa-plus"></i>
					</a>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="table_pmb">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="8%"><?=translate("Tanggal", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="5%"><?=translate("Jumlah", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Satuan", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Penerima", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    	$i = 1;
                                    	if(count($data_pmb)){
                                    		foreach ($data_pmb as $pmb) {
                                    ?>
                                    <tr>
                                    	<td><?=date('d M Y', strtotime($pmb['created_date']))?></td>
                                    	<td><?=$pmb['jumlah']?></td>
                                    	<td><?=$pmb['nama_satuan']?></td>
                                    	<td><?=$pmb['nama']?></td>
                                    </tr>
                                    <?php
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
		
	</div>
	<div class="modal-footer">
		<a class="btn default" id="close_modal" data-dismiss="modal"><?=translate('OK', $this->session->userdata('language'))?></a>
	</div>
</form>

