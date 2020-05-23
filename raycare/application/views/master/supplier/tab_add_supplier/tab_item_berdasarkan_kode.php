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
            <div class="form-group" style="margin-top : 20px;">
                    <?php
                        $item = $this->item_m->get_by(array('is_active' => 1));
                        $item_option = array(
                            '' => ''
                        );

                        foreach ($item as $row) {
                            $item_option[$row->id] = $row->nama.' '.'"'.$row->kode.'"';
                        }                    
                        
                        echo form_dropdown("items_berdasarkan_kode[]", $item_option, '', "id=\"multi_select_item\" class=\"multi-select\" multiple=\"multiple\"");
                    ?>
            </div>
        </div>
	</div> <!-- end of class="portlet-body" tab_item_berdasarkan_item_kode -->
</div> <!-- end of class="portlet light" tab_item_berdasarkan_item_kode -->

