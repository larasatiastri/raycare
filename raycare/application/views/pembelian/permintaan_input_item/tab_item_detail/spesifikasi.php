<div class="portlet light">
	<div class="portlet-title">
        <div class="caption">
            <?=translate("Buat Spesifikasi", $this->session->userdata("language"))?>
        </div>
	</div>
	<div class="portlet-body">
        <div class="form-body">
            <?php
                $item_sub_kategori = '';
                if ($flag == "add") {
                    $item_sub_kategori = '';
                }else{
                    $item_sub_kategori = $form_data['item_sub_kategori'];
                }
            ?>
            <div class="form-group">
                <label class="control-label col-md-4 hidden">Sub Kategori Id</label>
                <div class="col-md-8">
                    <input type="hidden" id="sub_kategori_id" value="<?=$item_sub_kategori?>">
                </div>
            </div>
            
            <div id="show_spesifikasi">
            </div>
        </div>
        
	</div>
</div>

