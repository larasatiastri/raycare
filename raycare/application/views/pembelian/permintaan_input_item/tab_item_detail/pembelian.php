<div class="portlet light">
	<div class="portlet-title">
        <div class="caption">
            <?=translate("Setup Pembelian", $this->session->userdata("language"))?>
        </div>
	</div>
	<div class="portlet-body">
        <div class="form-group">
            <label class="control-label col-md-4"><?=translate("Persediaan Minimum", $this->session->userdata("language"))?> :</label>
            
            <div class="col-md-4">
                <?php
                    $value='';
                    if ($flag == "add") {
                        $value = '1';
                    }else{
                        $value = $form_data['buffer_stok'];
                    }
                    $persediaan_minimum = array(
                        "id"        => "persediaan_minimum",
                        "name"      => "persediaan_minimum",
                        "autofocus" => true,
                        "class"     => "form-control", 
                        "type"      => "number",
                        "min"       => 1,
                        "class"     => "form-control input-sm text-right",
                        "value"     => $value,
                    );
                    echo form_input($persediaan_minimum);
                ?>
            </div>
        </div>
	</div>
</div>

