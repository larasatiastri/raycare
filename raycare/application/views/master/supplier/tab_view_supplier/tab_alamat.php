<div class="portlet light" id="section-alamat"><!-- begin of class="portlet light" tab_alamat -->
	<div class="portlet-title">
		<div class="caption">
			<span><?=translate('Alamat', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions hidden">
			<a class="btn btn-primary add-alamat">
                <i class="fa fa-plus"></i>
                <span class="hidden-480">
                     <!-- <?=translate("Tambah", $this->session->userdata("language"))?> -->
                </span>
            </a>										
		</div>
	</div>
	<div class="portlet-body"> <!-- begin of class="portlet-body" tab_alamat -->

			<div class="form-group">
				<label class="control-label col-md-4 hidden"><?=translate("Counter", $this->session->userdata("language"))?> :</label>
				<div class="col-md-5">
					<input type="hidden" id="counter" value="1">	
				</div>
			</div>

		<?php
					    
		    $get_data_supplier_alamat = $this->supplier_alamat_m->get_by(array('supplier_id' => $form_data['id'], 'is_active' => 1));
		    $data_supplier_alamat = object_to_array($get_data_supplier_alamat);
		    
		    $i = 1;
		    foreach ($data_supplier_alamat as $data) {
		    	// Begin of alamat form < //
		    	$is_primary_alamat = '';
		    	if ($data['is_primary'] == 1) {
		    		$is_primary_alamat = 'checked';
		    	}

		    	$subjek_alamat = $this->subjek_m->get($data['subjek_alamat_id']); 

		    	$data_lokasi = $this->info_alamat_m->get_by(array('lokasi_kode' => $data['kode_lokasi']));
				$data_lokasi = object_to_array($data_lokasi);

		    	$rt_rw = explode('/', $data['rt_rw']);
				$rt = $rt_rw[0];
				$rw = $rt_rw[1];

				$form_alamat_edit[] = '
				<li id="alamat_'.$i.'" class="fieldset">
					<div class="form-group">
					<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">'.$subjek_alamat->nama.'</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Alamat", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">'.$data['alamat'].'</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("RT/RW", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">'.$rt.'/'.$rw.'</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Kelurahan / Desa", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">'.$data_lokasi[0]['nama_kelurahan'].'</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Kecamatan", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">'.$data_lokasi[0]['nama_kecamatan'].'</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Kabupaten/Kota", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">'.$data_lokasi[0]['nama_kabupatenkota'].'</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Provinsi", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">'.$data_lokasi[0]['nama_propinsi'].'</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Negara", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">Indonesia</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">'.translate("Kode Pos", $this->session->userdata("language")).' :</label>
						<div class="col-md-8">
							<label class="control-label">'.$data['kode_pos'].'</label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"></label>
						<div class="col-md-8">
							<input type="radio" '.$is_primary_alamat.' disabled name="alamat_is_primary" id="radio_primary_alamat_id_'.$i.'" data-id="'.$i.'"> '.translate('Utama', $this->session->userdata('language')).'
						</div>
					</div>
					<hr/>
				</li>';
					// End of alamat form > //
				$i++;
		    }
		?>

		<div class="form-group">
			<label class="control-label col-md-4 hidden"><?=translate("Alamat Counter", $this->session->userdata("language"))?> :</label>
			<div class="col-md-8">
				<input type="hidden" id="alamat_counter" value="<?=$i?>" >
			</div>
		</div>

		<div class="form-body">

			<ul class="list-unstyled">
				<?php foreach ($form_alamat_edit as $row):?>
		            <?=$row?>
		        <?php endforeach;?>
			</ul>
		</div>
	</div> <!-- end of class="portlet-body" tab_alamat -->
</div> <!-- end of class="portlet light" tab_alamat -->