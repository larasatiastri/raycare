<?php
    if ($flag == "add") {
        $check_identitas = '';
        // $form_data['is_identitas']
        $form_identitas_edit = '';   
        $i = 0;     
        $identitas = $this->identitas_m->get_by(array('is_active' => 1));
        $identitas = object_to_array($identitas);

        $identitas_option = array(
            '' => translate('Pilih', $this->session->userdata('language'))
        );
        foreach ($identitas as $option) {
            $identitas_option[$option['id']] = $option['judul'];
        }

        $form_identitas = '
        <div class="form-group">
            <label class="control-label col-md-2">Identitas<span class="required">*</span> :</label>
            <div class="col-md-6">
                <div class="input-group">'.form_dropdown('identitas[{0}][identitas_id]', $identitas_option, '','id="identitas_id_{0}" class="form-control input-identitas" required').'
                    <span class="input-group-btn">
                        <a class="btn red-intense del-identitas" title="Remove"><i class="fa fa-times"></i></a>
                    </span>
                </div>
            </div>
        </div>
        ';
    }else{

        $identitas = $this->identitas_m->get_by(array('is_active' => 1));
        $identitas = object_to_array($identitas);

        $item_identitas = $this->item_identitas_m->get_by(array('item_id' => $pk_value, 'is_active' => 1));
        $item_identitas = object_to_array($item_identitas);

        $identitas_option = array(
            '' => translate('Pilih', $this->session->userdata('language'))
        );
        foreach ($identitas as $option) {
            $identitas_option[$option['id']] = $option['judul'];
        }

        if(count($item_identitas)){
            $i=0;
            $form_identitas_edit = '';
            foreach ($item_identitas as $row_identitas) {

                $form_identitas_edit .= '<li class="fieldset" id="list_'.$i.'"><div class="form-group">
                <label class="control-label col-md-2">Identitas<span class="required">*</span> :</label>
                <div class="col-md-6">
                    <div class="input-group">'.form_dropdown('identitas['.$i.'][identitas_id]', $identitas_option, $row_identitas['identitas_id'],'id="identitas_id_'.$i.'" class="form-control input-identitas" required').'
                        <input type="hidden" name="identitas['.$i.'][is_active]" value="1">
                        <input type="hidden" name="identitas['.$i.'][id]" value="'.$row_identitas['id'].'">
                        
                        <span class="input-group-btn">
                            <a class="btn red-intense del-identitas-db" data-index="'.$i.'" title="Remove" data-confirm="'.translate('Anda yakin akan menghapus identitas ini?', $this->session->userdata('language')).'"><i class="fa fa-times"></i></a>
                        </span>
                    </div>
                </div>
            </div></li>
            <hr style="border-color: rgb(228, 228, 228);">';
            $i++;
            }
        }else{
            $form_identitas_edit = '';
            $i = 0;
        }

        $form_identitas = '
        <div class="form-group">
            <label class="control-label col-md-2">Identitas<span class="required">*</span> :</label>
            <div class="col-md-6">
                <div class="input-group">'.form_dropdown('identitas[{0}][identitas_id]', $identitas_option, '','id="identitas_id_{0}" class="form-control input-identitas" required').'
                    <span class="input-group-btn">
                        <a class="btn red-intense del-identitas" title="Remove"><i class="fa fa-times"></i></a>
                    </span>
                </div>
            </div>
        </div>
        ';
        


        $check_identitas = '';
        if($form_data['is_identitas'] == '1'){
            $check_identitas = 'checked';
        }else{
            $check_identitas = '';
        }
    }
    
?>
<div class="form-group hidden">
    <div class="col-md-6">
        <input type="checkbox" <?=$check_identitas?> name="gunakan_identitas"><span><?=translate("Gunakan Identitas Item", $this->session->userdata("language"))?></span>
    </div>
</div>
<div class="portlet light" id="section-identitas">
    <div class="portlet-title">
        <div class="caption">
            <?=translate("Identitas Item", $this->session->userdata("language"))?>
        </div>
        <div class="actions">
            <a id="tambah_identitas" class="btn btn-circle btn-default btn-icon-only">
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>
    <div class="portlet-body">  
        <input type="hidden" id="input_is_identitas" name="input_is_identitas" value="0">  
        <input type="hidden" id="jml_identitas" name="jml_identitas" value="<?=$i?>">  
        <div class="">           
            <div class="form-group">
                <div class="col-md-12">
                    <input type="hidden" id="counter" name="counter" >
                     <div id="section-identitas">
                        <input type="hidden" id="tpl-form-identitas" value="<?=htmlentities($form_identitas)?>">
                        <div class="form-body">
                            <ul class="list-unstyled">
                                <?php
                                    echo $form_identitas_edit;
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>               
            </div>
        </div>       
    </div>
</div>

