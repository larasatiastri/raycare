<?php
	
	//$form_data_alamat_view = object_to_array($form_data_alamat);

	//die_dump($form_data_alamat_view);
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Informasi Cabang", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_cabang", 
			    "name"          => "form_add_cabang", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."master/cabang/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
		?>
			<div class="form-body">
				<div class = "row">
					<div class="alert alert-danger display-hide">
			        	<button class="close" data-close="alert"></button>
			        <?=$form_alert_danger?>
				    </div>
				    <div class="alert alert-success display-hide">
				        <button class="close" data-close="alert"></button>
				        <?=$form_alert_success?>
				    </div>
					<div class="form-group">
						<label class="control-label col-md-2"><?=translate("Tipe", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
						<div class="col-md-6">
							<?php
								
							    if($form_data['tipe'] == 1){
							    	$option = "Rumah Sakit";
							    }
							    if($form_data['tipe'] == 2){
							    	$option = "Distributor";
							    }
							    if($form_data['tipe'] == 3){
							    	$option = "Produksi";
							    }
								//die_dump($alamat_sub_option);
								
								echo '<label class="control-label ">'.$option.'</label>';
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2"><?=translate("Kode Cabang", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
						<div class="col-md-2">
							<?php
								
								echo '<label class="control-label ">'.$form_data['kode'].'</label>';
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2"><?=translate("Nama Cabang", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
						<div class="col-md-3">
							<?php
								
								echo '<label class="control-label ">'.$form_data['nama'].'</label>';
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
						<div class="col-md-3">
							<?php
								
								echo '<label class="control-label ">'.$form_data['keterangan'].'</label>';
							?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="portlet" id="section-add-alamat">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Alamat', $this->session->userdata('language'))?></span>
								</div>
							</div>
							<div class="portlet-body">
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alamat Subjek", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											$id_subjek = $form_data_alamat[0]->subjek_id;
											$alamat_sub = $this->cabang_m->get_data_sub_view($id_subjek);
											$alamat_sub_option = $alamat_sub->result_array();

											$sub_option = "";
										    foreach ($alamat_sub_option as $select) {
										        $sub_option = $select['nama'];
										    }
											//die_dump($alamat_sub_option);
											
											echo '<label class="control-label ">'.$sub_option.'</label>';
										?>
									</div>
									
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											echo '<label class="control-label ">'.$form_data_alamat[0]->alamat.'</label>';
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("RT / RW", $this->session->userdata("language"))?> :</label>
									<div class="col-md-2">
										<?php
											
											echo '<label class="control-label ">'.$form_data_alamat[0]->rt_rw.'</label>';
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Negara", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php

											$id_form_negara = $form_data_alamat[0]->negara_id;
											//die_dump($id_form_negara);
											$data_negara = $this->region_m->get_by(array('id' => $id_form_negara,  'parent' => null));
											$data_negara_array = object_to_array($data_negara);
											//die_dump($sub_negara);
											$sub_negara_option = "";
										    foreach ($data_negara_array as $select) {
										        $sub_negara_option = $select['nama'];
										    }
											//die_dump($alamat_sub_option);
											
											echo '<label class="control-label">'.$sub_negara_option.'</label>';
										?>
									</div>
									
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Provinsi", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											
											$id_form_propinsi = $form_data_alamat[0]->propinsi_id;
											//die_dump($id_form_negara);
											$data_propinsi = $this->region_m->get_by(array('id'=> $id_form_propinsi,  'parent' => $id_form_negara));
											$data_propinsi_array = object_to_array($data_propinsi);
											//die_dump($sub_negara);
											$sub_propinsi_option = "";
										    foreach ($data_propinsi_array as $select) {
										        $sub_propinsi_option = $select['nama'];
										    }
											//die_dump($alamat_sub_option);
											
											echo '<label class="control-label">'.$sub_propinsi_option.'</label>';
		                                ?>
		                                
									</div>
									
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kota / Kabupaten", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php

											$id_form_kota = $form_data_alamat[0]->kota_id;
											//die_dump($id_form_negara);
											$data_kota = $this->region_m->get_by(array('id' => $id_form_kota, 'parent' => $id_form_propinsi));
											$data_kota_array = object_to_array($data_kota);
											//die_dump($sub_negara);
											$sub_kota_option = "";
										    foreach ($data_kota_array as $select) {
										        $sub_kota_option = $select['nama'];
										    }
											//die_dump($alamat_sub_option);
											
											echo '<label class="control-label">'.$sub_kota_option.'</label>';
		                                ?>
		                                
									</div>
									
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kecamatan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											$id_form_kecamatan = $form_data_alamat[0]->kecamatan_id;
											//die_dump($id_form_negara);
											$data_kecamatan = $this->region_m->get_by(array('id' => $id_form_kecamatan, 'parent' => $id_form_kota));
											$data_kecamatan_array = object_to_array($data_kecamatan);
											//die_dump($sub_negara);
											$sub_kecamatan_option = "";
										    foreach ($data_kecamatan_array as $select) {
										        $sub_kecamatan_option = $select['nama'];
										    }
											//die_dump($alamat_sub_option);
											
											echo '<label class="control-label">'.$sub_kecamatan_option.'</label>';
		                                ?>
		                                
									</div>
									
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kelurahan / Desa", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											$id_form_kelurahan = $form_data_alamat[0]->kelurahan_id;
											//die_dump($id_form_negara);
											$data_kelurahan = $this->region_m->get_by(array('id' => $id_form_kelurahan, 'parent' => $id_form_kecamatan));
											$data_kelurahan_array = object_to_array($data_kelurahan);
											//die_dump($sub_negara);
											$sub_kelurahan_option = "";
										    foreach ($data_kelurahan_array as $select) {
										        $sub_kelurahan_option = $select['nama'];
										    }

										    echo '<label class="control-label">'.$sub_kelurahan_option.'</label>';
		                                ?>
		                               
									</div>
									
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Kode Pos", $this->session->userdata("language"))?> :</label>
									<div class="col-md-6">
										<?php
											echo '<label class="control-label ">'.$form_data_alamat[0]->kode_pos.'</label>';
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="portlet" id="section-telepon">
							<div class="portlet-title">
								<div class="caption">
									<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Telepon', $this->session->userdata('language'))?></span>
								</div>
							</div>
							<div class="portlet-body">
								<?php
									$telp_sub = $this->cabang_m->get_data_sub_telp();
									$telp_sub_option = $telp_sub->result_array();
									$sub_option = array('' => "--Pilih Subjek--");
								    foreach ($telp_sub_option as $select) {
								        $sub_option[$select['id']] = $select['nama'];
								    }

								    $records = $customer_phone->result_array();
									//die_dump($records);
									$i = 0;
									foreach ($records as $key=>$data) {

										$primary = "";
										if ($data['is_prim'] == 1) {
											$primary = "checked";
										}
										$i++;

										$id_telepon = $data['subjek_id'];
										$telepon_sub = $this->cabang_m->get_data_sub_telp_view($id_telepon);
										$telepon_sub_option = $telepon_sub->result_array();

										$sub_option_telp = "";
									    foreach ($telepon_sub_option as $select) {
									        $sub_option_telp = $select['nama'];
									    }

									//die_dump($alamat_sub_option);
									$form_phone[] = '
									<div id="phone_'.$i.'">
									<div class="form-group">
										<label class="control-label col-md-4 hidden">'.translate("Id Telepon", $this->session->userdata("language")).' :</label>
										
									</div>
									<div class="form-group">
										<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
										<div class="col-md-5">
											<label for="subjek" class="control-label">'.$sub_option_telp.'</label>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4">'.translate("Nomor Telepon", $this->session->userdata("language")).' :</label>
										<div class="col-md-5"><label for="subjek" class="control-label">'.$data['nomor'].'</label>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4"></label>
										<div class="col-md-5">
											<span class="">
		                                        <input type="checkbox" id="primary_id_'.$i.'" name="phone['.$i.'][is_primary]" '.$primary.' disabled>
		                                    </span>
		                                    <span>'.translate("Utama", $this->session->userdata("language")).'</span>
				                        </div>
										
									</div>
									<hr>
									</div>';
									$i++;
								};
								?>
									<?php foreach ($form_phone as $row):?>
								         <?=$row?>						                                
		                            <?php endforeach;?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Poliklinik & Dokter", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<?php
						   	$btn_del    = '<div class="text-center"><button type="button" class="btn btn-sx red del-this" title="Delete Phone"><i class="fa fa-times"></i></button></div>';
						    $poliklinik = array(
						        '1' => '',
						        '2' => 'Fax',
						        '3' => 'Home',
						    );

						   

						    $dokter_sub = $this->cabang_m->get_data_dokter();
						    $dokter_sub_option = $dokter_sub->result_array();
						    $options = array();
						    foreach ($dokter_sub_option as $key) {
						    	$options[$key['id']] = $key['dokter'];
						    }

						    $perawat_sub = $this->cabang_m->get_data_perawat();
						    $perawat_sub_option = $perawat_sub->result_array();
						    $perawat_options = array();
						    foreach ($perawat_sub_option as $key) {
						    	$perawat_options[$key['id']] = $key['perawat'];
						    }

                           $records = object_to_array($form_data_poliklinik);
			                if(empty($records)){
							    $dummy_rows = array();
							}
							else{
								foreach ($records as $data) {
									
                            		$time_range = '<div class="col-md-8">
			                                <div class="input-group input-large" id="defaultrange">
			                                	<label class="control-label col-md-4">'.translate("Jam Buka", $this->session->userdata("language")).' :</label>
			                                    <div class="input-group">
													<label class="control-label" id="jam_buka_'.$i.'" name="poliklinik['.$i.'][jam_buka]">'.$data['jam_buka'].'</label>
												</div>
												<label class="control-label col-md-4">'.translate("Jam Tutup", $this->session->userdata("language")).' :</label>
												<div class="input-group">
													<label class="control-label" id="jam_tutup_'.$i.'" name="poliklinik['.$i.'][jam_tutup]">'.$data['jam_tutup'].'</label>
												</div>
			                                </div>
			                            </div>';

			                        $is_delete = '<div class="form-group">
													<label class="control-label col-md-4 hidden">'.translate("Deleted", $this->session->userdata("language")).' :</label>
													<div class="col-md-5">
														<input class="form-control input-sm hidden" id="is_delete_'.$i.'" name="poliklinik['.$i.'][is_deleted]" placeholder="Is Delete">
													</div>
												</div>';
									$id = ' <div class="col-md-5">
											 	<input class="form-control input-sm hidden" id="id'.$i.'" name="poliklinik['.$i.'][id]" placeholder="Id Telepon" value="'.$data['id'].'">
											</div>';

									$poliklinik_sub = $this->cabang_m->get_data_poliklinik_view($data['poliklinik_id']);
									$poliklinik_sub_option = $poliklinik_sub->result_array();
									$sub_option = "";
								    foreach ($poliklinik_sub_option as $select) {
								        $sub_option = $select['nama'];
								    }
								    //die_dump($sub_option);
			                        $dokter = $this->cabang_poliklinik_dokter_m->get_by(array('cabang_poliklinik_id' => $data['id']));
			                        $rec_dokter = object_to_array($dokter);
			                        $array_dokter = array();
			                        foreach ($rec_dokter as $doktor) {
			                        	$array_dokter[$doktor['id']] = $doktor['dokter_id'];
			                        }

			                        $perawat = $this->cabang_poliklinik_perawat_m->get_by(array('cabang_poliklinik_id' => $data['id']));
			                        $rec_perawat = object_to_array($perawat);
			                        $array_perawat = array();
			                        foreach ($rec_perawat as $suster) {
			                        	$array_perawat[$suster['id']] = $suster['perawat_id'];
			                        }
			                        //die_dump($dokter);
						    		// item row column
								    $item_cols = array(
										'poliklinik_subject' => '<label class="control-label">'.$sub_option.'</label>',
										'poliklinik_waktu'   => $time_range,
										'dokter'             => form_dropdown('poliklinik[{0}][dokter][]', $options, $array_dokter, "id=\"dokter_{0}\" class=\"form-control	phone_sub\" multiple=\"multiple\" disabled"),
										'perawat'            => form_dropdown('poliklinik[{0}][dokter][]', $options, $array_perawat, "id=\"perawat_{0}\" class=\"form-control phone_sub\" multiple=\"multiple\" disabled")
								    );

								  $item_rows =  '<tr id="item_row_get_'.$i.'"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
								  $get_row[] = $item_rows;
								  $i++;   
								}
							}
						?>
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover table-condensed" id="table_poliklinik_dokter">
							<thead>
							<tr>
								<th class="text-center"><?=translate("Poliklinik", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Waktu Kerja", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Dokter", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Perawat", $this->session->userdata("language"))?> </th>
							</tr>
							</thead>
							<tbody>
								<?php foreach ($get_row as $row):?>
                                    <?=$row?>
                                <?php endforeach;?>
							</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
				
			<?php $msg = translate("Apakah anda yakin akan membuat cabang ini?",$this->session->userdata("language"));?>
			<div class="form-actions fluid">	
				<div class="col-md-offset-1 col-md-9">
    				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
                   <!--  <button type="reset" class="btn default" ><?=translate("Reset", $this->session->userdata("language"))?></button>
    				<a id="confirm_save" class="btn green-haze" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
                    <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button> -->
    			</div>		
			</div>
		<?=form_close()?>
	</div>
</div>
