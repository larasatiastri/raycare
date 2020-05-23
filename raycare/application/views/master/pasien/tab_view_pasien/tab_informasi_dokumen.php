<div class="portlet light" id="section-alamat">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Informasi Dokumen', $this->session->userdata('language'))?></span>
		</div>
	</div>
	<div class="portlet-body">


		<?php 
				$id_pasien_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $pk_value, 'penjamin_id' => 1));

				$penjamin_syarat = $this->penjamin_syarat_m->get_data_penjamin_syarat(1)->result_array();
		        $data_penjamin_syarat = object_to_array($penjamin_syarat);
		        //die_dump($this->db->last_query());
		        $show_penjamin = "";
		        $input      = "";
		        $radio      = "";
		        $checkbox   = "";
		        $value      = "";
		        $i          = 0;
		        $z          = 0;
		        foreach ($data_penjamin_syarat as $data) 
		        {
		            $pasien_penjamin = $this->pasien_penjamin_m->get_data_pasien_penjamin($id_pasien_penjamin[0]->id, $data['syarat_id'])->result_array();
		            $data_pasien_penjamin = object_to_array($pasien_penjamin);
		        // die_dump($this->db->last_query());

		            foreach ($data_pasien_penjamin as $pasien_penjamin_value) {
		                $value = $pasien_penjamin_value['value'];

		                $pasien_syarat_penjamin_id = $pasien_penjamin_value['pasien_syarat_penjamin_id'];
		                //die_dump($pasien_syarat_penjamin_id);
		                
		                if ($data['tipe'] == 1)
		                {
		                    
	                        $input = '<label class="control-label col-md-4">'.$data['judul'].' :</label>
	                                  <div class="col-md-5">
	                                    <input type="hidden" class="form-control" id="dokumen_'.$i.'" name="dokumen_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$value.'">
	                                    <label class="control-label">'.$value.'</label>
	                                    <input type="hidden" class="form-control" id="'.$data['judul'].'" name="syarat_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['syarat_id'].'">
	                                    <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
	                                    <input type="hidden" class="form-control" id="'.$data['judul'].'" name="pasien_syarat_penjamin_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$pasien_syarat_penjamin_id.'">
	                                  </div>';
		                    
		                    //die_dump($this->db->last_query());
		                    
		                }elseif ($data['tipe'] == 2)
		                {
		                    $input = '<label class="control-label col-md-4">'.$data['judul'].' :</label>
		                              <div class="col-md-5">
		                                <textarea class="form-control hidden" id="'.$data['judul'].'" name="dokumen_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" rows="4">'.$value.'</textarea>
	                                    <label class="control-label">'.$value.'</label>
		                                <input type="hidden" class="form-control" id="dokumen_'.$i.'" name="syarat_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['syarat_id'].'">
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="pasien_syarat_penjamin_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$pasien_syarat_penjamin_id.'">
		                              </div>
		                              ';
		                }elseif ($data['tipe'] == 3) {
		                    $input = '<label class="control-label col-md-4">'.$data['judul'].' :</label>
		                              <div class="col-md-2">
		                                <input type="hidden" min="1" class="form-control text-right" id="'.$data['judul'].'" name="dokumen_'.$i.'" value="'.$value.'">
	                                    <label class="control-label">'.$value.'</label>
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="syarat_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['syarat_id'].'">
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="pasien_syarat_penjamin_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$pasien_syarat_penjamin_id.'">
		                              </div>
		                              ';
		                }elseif ($data['tipe'] == 4) 
		                {
		                    $judul = $data['judul'];
		                    $syarat_detail = $this->pasien_penjamin_m->get_data_syarat_detail($data['syarat_id'], 4)->result_array();
		                    $syarat_detail_option = array(
		                        '' => translate('Pilih..', $this->session->userdata('language'))
		                    );

		                    foreach ($syarat_detail as $data_syarat)
		                    {
		                        $syarat_detail_option[$data_syarat['value']] = $data_syarat['text'];
		                    }

							// echo $id_pasien_penjamin[0];		            

		                    $pasien_syarat_penjamin_detail = $this->pasien_syarat_penjamin_detail_m->get_data_pasien_syarat_penjamin_detail($id_pasien_penjamin[0]->id, $data['syarat_id'])->result_array();
		                    $data_pasien_syarat_penjamin_detail = object_to_array($pasien_syarat_penjamin_detail);
		                    $id_detail = "";
		                    $selected = array();
		                    foreach ($data_pasien_syarat_penjamin_detail as $data_selected) {
		                        $selected[$data_selected['value_detail']] = $data_selected['value_detail'];
		                        $id_detail = $data_selected['id_detail'];
		                    }

		                    // print_r($data_pasien_syarat_penjamin_detail);

		                    $input = '<label class="control-label col-md-4">'.$data['judul'].' :</label>
		                              <div class="col-md-3">
		                                '.form_dropdown('dokumen_'.$i, $syarat_detail_option, $selected, "disabled id=\"$judul\" class=\"form-control\"").'
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="syarat_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['syarat_id'].'">
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="pasien_syarat_penjamin_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$pasien_syarat_penjamin_id.'">
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="pasien_syarat_penjamin_detail_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$id_detail.'">
		                              </div>';
		                }
		                elseif ($data['tipe'] == 5)
		                {
		                    $syarat_detail = $this->pasien_penjamin_m->get_data_syarat_detail($data['syarat_id'], 5)->result_array();
		                    $pasien_syarat_penjamin_detail = $this->pasien_syarat_penjamin_detail_m->get_data_pasien_syarat_penjamin_detail($id_pasien_penjamin[0]->id, $data['syarat_id'])->result_array();
		                    $data_pasien_syarat_penjamin_detail = object_to_array($pasien_syarat_penjamin_detail);
		                    
		                    
		                    foreach ($syarat_detail as $data_syarat)
		                    {
		                        $check = "";
		                        $found = false;
		                        foreach ($data_pasien_syarat_penjamin_detail as $data_selected)
		                        {
		                            $id_detail = $data_selected['id_detail'];
		                            if ($data_selected['value_detail'] == $data_syarat['value']){
		                               $found = true;
		                            }
		                            //die_dump($data_selected['value_detail']);
		                            //die_dump($data_syarat['value']);
		                            //die_dump($check);
		                            
		                        }
		                        ($found == true) ? $check = 'checked' : $check = '';

		                        $radio .= '<label class="radio-inline" style="padding-left:20px;">
		                                    <input disabled type="radio" name="dokumen_'.$i.'" '.$check.' value="'.$data_syarat['value'].'">'.$data_syarat['text'].'
		                                  </label>';
		                    }

		                    $input = '<label class="control-label col-md-4">'.$data['judul'].' :</label>
		                              <div class="col-md-5">
		                                <div class="radio-list">
		                                    '.$radio.'
		                                    <input type="hidden" class="form-control" id="'.$data['judul'].'" name="syarat_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['syarat_id'].'">
		                                    <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
		                                    <input type="hidden" class="form-control" id="'.$data['judul'].'" name="pasien_syarat_penjamin_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$pasien_syarat_penjamin_id.'">     
		                                    <input type="hidden" class="form-control" id="'.$data['judul'].'" name="pasien_syarat_penjamin_detail_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$id_detail.'">
		                                </div>
		                              </div>';
		                }
		                elseif ($data['tipe'] == 6)
		                {
		                    $syarat_detail = $this->pasien_penjamin_m->get_data_syarat_detail($data['syarat_id'], 6)->result_array();
		                    $pasien_syarat_penjamin_detail = $this->pasien_syarat_penjamin_detail_m->get_data_pasien_syarat_penjamin_detail($id_pasien_penjamin[0]->id, $data['syarat_id'])->result_array();
		                    $data_pasien_syarat_penjamin_detail = object_to_array($pasien_syarat_penjamin_detail);
		                    
		                    

		                    $selected = array();
		                    foreach ($syarat_detail as $data_syarat){
		                        $check = "";
		                        $found = false;
		                        foreach ($data_pasien_syarat_penjamin_detail as $data_selected)
		                        {
		                            if ($data_selected['value_detail'] == $data_syarat['value']){
		                               $found = true;
		                            }
		                            //die_dump($data_selected['value_detail']);
		                            //die_dump($data_syarat['value']);
		                            //die_dump($check);
		                            
		                        }
		                        ($found == true) ? $check = 'checked' : $check = '';
		                        $checkbox .= '<label class="checkbox-inline" style="padding-left:20px;">
		                                        <input disabled type="checkbox" '.$check.' name="dokumen_'.$i.'[]" value="'.$data_syarat['value'].'">'.$data_syarat['text'].'
		                                      </label>';

		                    }

		                    $input = '<label class="control-label col-md-4">'.$data['judul'].' :</label>
		                              <div class="col-md-5">
		                                <div class="checkbox-list">
		                                    '.$checkbox.'
		                                    <input type="hidden" class="form-control" id="'.$data['judul'].'" name="syarat_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['syarat_id'].'">
		                                    <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
		                                    <input type="hidden" class="form-control" id="'.$data['judul'].'" name="pasien_syarat_penjamin_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$pasien_syarat_penjamin_id.'">
		                                </div>
		                              </div>';

		                }elseif ($data['tipe'] == 7) {
		                    $syarat_detail = $this->pasien_penjamin_m->get_data_syarat_detail($data['syarat_id'], 7)->result_array();
		                    //die_dump($syarat_detail);
		                    $syarat_detail_option = array();

		                    foreach ($syarat_detail as $data_syarat)
		                    {
		                        $syarat_detail_option[$data_syarat['value']] = $data_syarat['text'];
		                    }

		                    $pasien_syarat_penjamin_detail = $this->pasien_syarat_penjamin_detail_m->get_data_pasien_syarat_penjamin_detail($id_pasien_penjamin[0]->id, $data['syarat_id'])->result_array();
		                    $data_pasien_syarat_penjamin_detail = object_to_array($pasien_syarat_penjamin_detail);
		                    
		                    $selected = array();
		                    foreach ($data_pasien_syarat_penjamin_detail as $data_selected) {
		                        $selected[$data_selected['value_detail']] = $data_selected['value_detail'];
		                    }

		                    $judul = $data['judul'];
		                    $input = '<label class="control-label col-md-4">'.$data['judul'].' :</label>
		                              <div class="col-md-5">
		                                '.form_dropdown("dokumen_".$i."[]", $syarat_detail_option, $selected, "disabled id=\"$judul\" class=\"multi-select\" multiple=\"multiple\"").'
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="syarat_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['syarat_id'].'">
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="pasien_syarat_penjamin_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$pasien_syarat_penjamin_id.'">
		                              </div>';
		                }
		                elseif ($data['tipe'] == 8) {


		                    $input = '<label class="control-label col-md-4">'.$data['judul'].' :</label>
		                              <div class="col-md-5">
            							<input type="file" id="upload_'.$z.'" name="upload_'.$i.'" class="uploadbutton hidden" value="" />
		                                <input type="hidden" id="url_'.$z.'" name="dokumen_'.$i.'" value="" class="requiredfile" />
										<input type="hidden" id="url_photo_'.$i.'" name="url_photo_'.$i.'" value="'.$value.'" />
		                                <label class="control-label">'.$value.'</label>
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="syarat_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['syarat_id'].'">
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="tipe_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$data['tipe'].'">
		                                <input type="hidden" class="form-control" id="'.$data['judul'].'" name="pasien_syarat_penjamin_id_'.$i.'" placeholder="'.$data['judul'].'" maxlength="'.$data['maksimal_karakter'].'" value="'.$pasien_syarat_penjamin_id.'">

		                              </div>
		                              <div id="choosen_file_container_'.$z.'">						
											<div class="form-group">
												<label class="control-label col-md-4">'.translate("Thumbnail", $this->session->userdata("language")).' :</label>
												<div class="col-md-4">
													<label id="choosen_file_'.$z.'" class="control-label col-md-4" >
														<a href="'.config_item('site_img_pasien_temp_dir_copy').$value.'" target="_blank"><img style="max-width:200px; max-height:200px;" class="img-thumbnail" src="'.config_item('site_img_pasien_temp_dir_copy').$value.'"></a>
													</label>
												</div>
											</div>
										</div>';
							$z++;
		                }
		            }

		            echo '<div class="form-group">'.$input.'</div>';
		            $i++;
		        }

				echo '<div class="form-group hidden">
                        <label class="control-label col-md-4 ">Total Dokumen</label>
                        <div class="col-md-3 ">
                        	<input type="text" id="data_upload" name="data_upload" value="'.$z.'" />
                            <input type="text" name="total_dokumen" value="'.$i.'">
                        </div>
                    </div>';

		 	?>

		<!-- <ul class="nav nav-tabs">
			<li class="active">
				<a href="#pelengkap" data-toggle="tab">
				<?=translate('Pelengkap', $this->session->userdata('language'))?> </a>
			</li>
			<li>
				<a href="#rekam_medis" data-toggle="tab">
				<?=translate('Rekam Medis', $this->session->userdata('language'))?> </a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="pelengkap">
				<?php include('tab_dokumen/pelengkap.php') ?>
			</div>
			<div class="tab-pane" id="rekam_medis">
				<?php include('tab_dokumen/rekam_medis.php') ?>
			</div>
		</div> -->
	</div>
</div>