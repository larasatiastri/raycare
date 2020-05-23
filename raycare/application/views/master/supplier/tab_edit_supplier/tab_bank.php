<div class="portlet light" id="section-bank"> <!-- begin of class="portlet light" tab_telepon -->
	<div class="portlet-title">
		<div class="caption">
			<span><?=translate('Bank', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-icon-only btn-default add-bank">
                <i class="fa fa-plus"></i>
            </a>										
		</div>
	</div>
	<div class="portlet-body"> <!-- begin of class="portlet-body" tab_telepon -->
		<?php
			$data_bank = $this->supplier_bank_m->get_by(array('supplier_id' => $pk_value, 'is_active' => 1));
			if(count($data_bank))
			{
				$data_bank = object_to_array($data_bank);
				$i = 1;
				foreach ($data_bank as $bank) 
				{
					$form_bank_edit[] = 
					'<li id="bank_'.$i.'" class="fieldset">
						<div class="form-group hidden">
							<label class="control-label col-md-4">'.translate("ID Bank", $this->session->userdata("language")).' :</label>
							<div class="col-md-8">
								<input type="text" id="bank_id_'.$i.'" class="form-control" name="bank['.$i.'][id]" value="'.$bank['id'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Nama Bank", $this->session->userdata("language")).' :</label>
							<div class="col-md-8">
								<div id="subjek_'.$i.'" class="input-group">
									<input type="text" id="bank_nama_'.$i.'" class="form-control" name="bank['.$i.'][nama]" value="'.$bank['nob'].'" placeholder="'.translate('Nama Bank', $this->session->userdata('language')).'">
									<span class="input-group-btn">
										<a class="btn red-intense del-this-bank-db" id="btn_delete_bank_'.$i.'" data-index="'.$i.'" data-confirm="'.translate('Anda yakin akan menghapus data bank ini?', $this->session->userdata('language')).'" data-id="'.$bank['id'].'" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Cabang Bank", $this->session->userdata("language")).' :</label>
							<div class="col-md-8">
									<input type="text" id="bank_cabang_'.$i.'" class="form-control" name="bank['.$i.'][cabang]" value="'.$bank['cabang_bank'].'" placeholder="'.translate('Cabang Bank', $this->session->userdata('language')).'">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("No. Rekening", $this->session->userdata("language")).' :</label>
							<div class="col-md-8">
									<input type="text" id="bank_norek_'.$i.'" class="form-control" name="bank['.$i.'][norek]" value="'.$bank['acc_number'].'" placeholder="'.translate('No. Rekening', $this->session->userdata('language')).'">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Atas Nama", $this->session->userdata("language")).' :</label>
							<div class="col-md-8">
									<input type="text" id="bank_atasnama_'.$i.'" class="form-control" name="bank['.$i.'][atasnama]" value="'.$bank['acc_name'].'" placeholder="'.translate('Atas Nama', $this->session->userdata('language')).'">
							</div>
						</div>
						<div class="form-group hidden">
							<label class="control-label col-md-4">'.translate("Active", $this->session->userdata("language")).' :</label>
							<div class="col-md-8">
								<input type="text" id="bank_is_active_'.$i.'" class="form-control" name="bank['.$i.'][is_active]" value="1">
							</div>
						</div>
						<hr style="border-color: rgb(228, 228, 228);">
					</li>';

					$i++;
				}
			}
			else
			{
				$i = 1;
			}
			$form_bank = '
			<div class="form-group hidden">
				<label class="control-label col-md-4">'.translate("ID Bank", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<input type="text" id="bank_id_{0}" class="form-control" name="bank[{0}][id]" value="">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Nama Bank", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<div id="subjek_{0}" class="input-group">
						<input type="text" id="bank_nama_{0}" class="form-control" name="bank[{0}][nama]" placeholder="'.translate('Nama Bank', $this->session->userdata('language')).'">
						<span class="input-group-btn">
							<a class="btn red-intense del-this-bank" id="btn_delete_bank{0}" title="'.translate('Remove', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Cabang Bank", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
						<input type="text" id="bank_cabang_{0}" class="form-control" name="bank[{0}][cabang]" placeholder="'.translate('Cabang Bank', $this->session->userdata('language')).'">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("No. Rekening", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
						<input type="text" id="bank_norek_{0}" class="form-control" name="bank[{0}][norek]" placeholder="'.translate('No. Rekening', $this->session->userdata('language')).'">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">'.translate("Atas Nama", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
						<input type="text" id="bank_atasnama_{0}" class="form-control" name="bank[{0}][atasnama]" placeholder="'.translate('Atas Nama', $this->session->userdata('language')).'">
				</div>
			</div>
			<div class="form-group hidden">
				<label class="control-label col-md-4">'.translate("Active", $this->session->userdata("language")).' :</label>
				<div class="col-md-8">
					<input type="text" id="bank_is_active_{0}" class="form-control" name="bank[{0}][is_active]" value="1">
				</div>
			</div>
			';
		?>

		<input type="hidden" id="tpl-form-bank" value="<?=htmlentities($form_bank)?>">
		<div class="form-body">
		<input type="hidden" class="form-control" name="jml_data" id="jml_data" value="<?=$i?>">
			<ul class="list-unstyled">
			<?php
				if(count($data_bank))
				{
					foreach ($form_bank_edit as $row) 
					{
						echo $row;
					}
				}
			?>
			</ul>
		</div>
	</div> <!-- end of class="portlet-body" tab_telepon -->
</div> <!-- end of class="portlet light" tab_telepon -->