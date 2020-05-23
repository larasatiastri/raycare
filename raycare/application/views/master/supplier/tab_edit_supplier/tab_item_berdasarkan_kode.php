<div class="portlet light"> <!-- begin of class="portlet light" tab_item_berdasarkan_item_kode -->
	<div class="portlet-title">
        <div class="caption">
            <span><?=translate("Item Berdasarkan Kode", $this->session->userdata("language"))?></span>
        </div>
	</div>
	<div class="portlet-body"> <!-- begin of class="portlet-body" tab_item_berdasarkan_item_kode -->
        <div class="form-body">
            <div class="form-group">
                <a href='#' id='select-all' class="btn btn-primary"><?=translate('Pilih Semua', $this->session->userdata("language"))?></a>
                <a href='#' id='deselect-all' class="btn bg-red-thunderbird"><?=translate('Hapus Semua', $this->session->userdata("language"))?></a>
            </div>
            <div class="form-group">
                <select id="multi_select_item" name="items_berdasarkan_kode[]" class="multi-select" required="required" multiple="multiple">
                    <?php

                        $supplier_item = $this->supplier_item_m->get_data_by_supplier($pk_value);

                        $item = $this->item_m->get_by(array('is_active' => 1));
                        $item = object_to_array($item);
                        $item_option = array(
                            '' => ''
                        );

                        foreach ($item as $row) {
                            $found = false;
                            $selected = '';
                            foreach ($supplier_item as $supp_item) 
                            {
                                if($row['id'] == $supp_item['item_id'])
                                {
                                    $found == true;
                                    $selected = 'selected="selected"';
                                }
                            }
                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['nama'].' '.'"'.$row['kode'].'</option>';
                        }                    
                    ?>
                </select>
            </div>
        </div>
	</div> <!-- end of class="portlet-body" tab_item_berdasarkan_item_kode -->
</div> <!-- end of class="portlet light" tab_item_berdasarkan_item_kode -->

