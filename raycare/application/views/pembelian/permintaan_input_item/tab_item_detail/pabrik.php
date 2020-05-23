<div class="portlet light">
	<div class="portlet-title">
        <div class="caption">
            <?=translate("Pilih Pabrik", $this->session->userdata("language"))?>
        </div>
	</div>
	<div class="portlet-body">
        <div class="form-body">
            <div class="form-group">
                <a href='#' id='select-all' class="btn btn-primary"><?=translate('Pilih Semua', $this->session->userdata("language"))?></a>
                <a href='#' id='deselect-all' class="btn bg-red-thunderbird"><?=translate('Hapus Semua', $this->session->userdata("language"))?></a>
            </div>
            <div class="form-group">
                    <?php
                        $pabrik = $this->pabrik_m->get();
                        $pabrik_option = array(
                            '' => ''
                        );

                        foreach ($pabrik as $row) {
                            $pabrik_option[$row->id] = $row->nama.' '.'"'.$row->kode.'"';
                        }                    
                        
                        if($flag == "add"){
                            echo form_dropdown("pabrik[]", $pabrik_option, '', "id=\"multi_select_pabrik\" class=\"multi-select\" multiple=\"multiple\"");
                        }else{

                            $item_pabrik = $this->item_pabrik_m->get_by(array('item_id' => $form_data['id'], 'is_active' => 1));

                            $item_pabrik_selected = array(
                                '' => ''
                            );
                            foreach ($item_pabrik as $item_pabrik) {
                                $item_pabrik_selected[$item_pabrik->pabrik_id] = $item_pabrik->pabrik_id;
                            }
                            echo form_dropdown("pabrik[]", $pabrik_option, $item_pabrik_selected, "id=\"multi_select_pabrik\" class=\"multi-select\" multiple=\"multiple\"");
                        }

                    ?>
            </div>
        </div>
	</div>
</div>

