<div class="portlet light" id="section-gambar">
	<div class="portlet-title">
        <div class="caption">
            <?=translate("Upload Gambar", $this->session->userdata("language"))?>
        </div>
        <div class="actions">
            <a id="tambah_gambar" class="btn btn-circle btn-icon-only btn-default">
                <i class="fa fa-plus"></i>
            </a>
        </div>
	</div>
	<div class="portlet-body">
        <?php
            if ($flag == "add") {
                $i='';
                $form_gambar = '
                <div class="form-group">
                    <div class="form-group">
                        <label for="exampleInputFile" class="col-md-3 control-label hidden">'.translate("Url Gambar", $this->session->userdata("language")).'</label>
                        <input type="hidden" name="gambar[{0}][url]" id="gambar_url_{0}">
                    </div>

                    <div class="form-group ">
                        <label for="exampleInputFile" class="col-md-3 control-label">'.translate("Pilih Gambar", $this->session->userdata("language")).'<span class="required">*</span>:</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="gambar_url_{0}" name="gambar[{0}][url]" value="" class="form-control" readonly="readonly"/>
                                <span class="input-group-btn">
                                    <a class="btn red-intense del-gambar" title="'.translate('Hapus', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                                </span>
                            </div>
                            <input type="checkbox" id="primary_gambar_{0}" class="hidden" name="gambar[{0}][is_primary_gambar]"/><span class="hidden">&nbsp;'.translate('Utama', $this->session->userdata('language')).'</span>
                            <input type="hidden" name="gambar[{0}][is_primary_gambar]" id="primary_gambar_id_{0}">

                            
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile" class="col-md-3 control-label"></label>
                        <div id="upload_{0}" class="col-md-6">
                            <span class="btn default btn-file">
                                <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>       
                                <input type="file" name="upl" id="gambar_file_{0}" data-url="'.base_url().'upload/upload_photo" multiple />
                            </span>
                            

                        <div class="form-group">
                            <ul class="ul-img">
                                <!-- The file uploads will be shown here -->
                            </ul>
                        </div>

                        <div class="input-group">
                            <input type="radio" name="gambar_is_primary" id="radio_primary_gambar_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
                        </div>
                       
                        </div>
                    </div>
                    
                </div>';
            }else{
                $get_gambar = $this->item_gambar_m->get_by(array('item_id' => $pk_value));
                $data_gambar = object_to_array($get_gambar);
                $i=0;
                $check = '';

                if (empty($data_gambar)) {
                    $form_gambar_edit[] = '';
                }else{
                    foreach ($data_gambar as $data) {
                        // $nama_gambar = explode('/', $data['gambar_url']);
                        // die_dump($data_gambar);
                        if ($data['is_primary'] == '1') {
                            $check = 'checked';
                        }else{
                            $check = '';
                        }

                        $msg = translate("Apakah anda yakin ingin menghapus gambar ini ?", $this->session->userdata("language"));
                        $form_gambar_edit[] = '
                        <div id="gambar_'.$i.'">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="exampleInputFile" class="col-md-2 control-label hidden">'.translate("Url Gambar", $this->session->userdata("language")).'</label>
                                <input type="hidden" name="gambar['.$i.'][url]" id="gambar_url_'.$i.'" value="'.$data['gambar_url'].'" data-id="'.$i.'" class="url_gambar">
                            </div>
                            
                            <div class="form-group">
                                <label for="exampleInputFile" class="col-md-3 control-label">'.translate("Pilih Gambar", $this->session->userdata("language")).'<span class="required">*</span>:</label>
                                <div class="col-md-6 primary_gambar">
                                    <div class="input-group">
                                        <input type="text" id="gambar_url_'.$i.'" name="gambar['.$i.'][url]" value="'.$data['gambar_url'].'" class="form-control" readonly="readonly"/>
                                        <span class="input-group-btn">
                                            <a class="btn red-intense del-db-gambar" data-id="'.$i.'" data-confirm="'.$msg.'" title="'.translate('Hapus', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                                        </span>
                                    </div>
                                    <input type="hidden" id="gambar_id_'.$i.'" name="gambar['.$i.'][id]" value="'.$data['id'].'" class="form-control" readonly="readonly"/>
                                    <input type="hidden" id="gambar_is_delete_'.$i.'" name="gambar['.$i.'][is_delete]" class="form-control" readonly="readonly"/>
                                    <input type="hidden" id="primary_gambar_id_'.$i.'" name="gambar['.$i.'][is_primary_gambar]"  value="'.$data['is_primary'].'">
                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile" class="col-md-3 control-label"></label>
                                <div id="upload_'.$i.'" class="col-md-6">
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>       
                                        <input type="file" name="upl" id="gambar_file_'.$i.'" data-url="'.base_url().'upload/upload_photo" multiple />
                                    </span>

                                <div class="form-group">
                                    <ul class="ul-img">
                                        <li class="working">
                                            <div class="thumbnail">
                                                <a class="fancybox-button" href="'.config_item('site_img_item_temp_dir_copy').$data['gambar_url'].'" data-rel="fancybox-button">
                                                    <img src="'.config_item('site_img_item_temp_dir_copy').$data['gambar_url'].'" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;">
                                                </a>
                                            </div>
                                            <span></span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="input-group">
                                    <input type="radio" id="radio_primary_gambar_id_'.$i.'" name="gambar_is_primary" '.$check.' style="left: 20px;" data-id="'.$i.'"> '.translate('Utama', $this->session->userdata('language')).'
                                </div>
                               
                                </div>
                            </div>


                            
                        </div>
                        <hr/ style="border-color : rgb(228, 228, 228);">
                        </div>
                        ';
                    $i++;
                    }
                }
                

                $form_gambar = '
                <div class="form-group">
                    <div class="form-group">
                        <label for="exampleInputFile" class="col-md-3 control-label hidden">'.translate("Url Gambar", $this->session->userdata("language")).'</label>
                        <input type="hidden" name="gambar[{0}][url]" id="gambar_url_{0}">
                    </div>

                    <div class="form-group ">
                        <label for="exampleInputFile" class="col-md-3 control-label">'.translate("Pilih Gambar", $this->session->userdata("language")).'<span class="required">*</span>:</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="gambar_url_{0}" name="gambar[{0}][url]" value="" class="form-control" readonly="readonly"/>
                                <span class="input-group-btn">
                                    <a class="btn red-intense del-gambar" title="'.translate('Hapus', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                                </span>
                            </div>
                            <input type="checkbox" id="primary_gambar_{0}" class="hidden" name="gambar[{0}][is_primary_gambar]"/><span class="hidden">&nbsp;'.translate('Utama', $this->session->userdata('language')).'</span>
                            <input type="hidden" name="gambar[{0}][is_primary_gambar]" id="primary_gambar_id_{0}">

                            
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile" class="col-md-3 control-label"></label>
                        <div id="upload_{0}" class="col-md-6">
                            <span class="btn default btn-file">
                                <span class="fileinput-new">'.translate('Pilih Foto', $this->session->userdata('language')).'</span>       
                                <input type="file" name="upl" id="gambar_file_{0}" data-url="'.base_url().'upload/upload_photo" multiple />
                            </span>
                            

                        <div class="form-group">
                            <ul class="ul-img">
                                <!-- The file uploads will be shown here -->
                            </ul>
                        </div>

                        <div class="input-group">
                            <input type="radio" name="gambar_is_primary" id="radio_primary_gambar_id_{0}"> '.translate('Utama', $this->session->userdata('language')).'
                        </div>
                       
                        </div>
                    </div>
                    
                </div>';
            }
            
        ?>
        <div class="form-group">
            <label class="control-label col-md-4 hidden"><?=translate("Phone Counter", $this->session->userdata("language"))?> :</label>
            <div class="col-md-5">
                <input type="hidden" id="gambar_counter" value="<?=$i?>" >
            </div>
        </div>
        
    
        <div id="gambar_edit">
            
            <?php 
                if ($flag == "edit") {
                    foreach ($form_gambar_edit as $row){
                        echo $row;
                    }
                }
                
            ?>

        </div>
        <input type="hidden" id="tpl-form-gambar" value="<?=htmlentities($form_gambar)?>">
        <div class="form-body">

            <ul class="gambar list-unstyled">
            </ul>
        </div>
        
	</div>
</div>

