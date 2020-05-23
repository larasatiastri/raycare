<?php
    $form_attr = array(
        "id"            => "form_jurnal_template", 
        "name"          => "form_jurnal_template", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
    $hidden = array(
        "command"   => "add"
    );
    echo form_open(base_url()."akunting/jurnal_template/add/", $form_attr,$hidden);
?>

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?=translate("Jurnal Sistem Template", $this->session->userdata("language"))?></span>
                </div>
                
                <div class="actions"> <!-- <a href="#" class="btn default btn-sm">
                                                <i class="fa fa-pencil icon-black"></i> Edit </a> -->
                    <a href="<?=base_url()?>akunting/jurnal_template/add" class="btn green-haze">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
                             <?=translate("Tambah Template ", $this->session->userdata("language"))?>
                        </span>
                    </a>
                </div>
                
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="table_jurnal_template">
                    <thead>
                        <tr role="row" class="heading">
                            <th class="text-center" ><?=translate("Name", $this->session->userdata("language"))?></th>
                            <th class="text-center" ><?=translate("Transaction", $this->session->userdata("language"))?></th>
                            <th class="text-center" ><?=translate("Description", $this->session->userdata("language"))?></th>
                            <th class="text-center" ><?=translate("Create By", $this->session->userdata("language"))?></th>
                            <th class="text-center" ><?=translate("Actions", $this->session->userdata("language"))?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

        </div>
