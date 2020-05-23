<?php

$btn_del           = '<div class="text-center"><a class="btn btn-sm red-intense del-this" title="Delete Persetujuan Pembelian"><i class="fa fa-times"></i></a></div>';
$btn_del_db        = '<div class="text-center"><a class="btn btn-sm red-intense del-item-db" title="Delete Persetujuan Pembelian"><i class="fa fa-times"></i></a></div>';

$attrs_user_level_id    = array (
    'id'          => 'user_level_id{0}',
    'name'        => 'user_level[{0}][id]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);
$attrs_user_level_name = array (
    'id'          => 'user_level_name{0}',
    'name'        => 'user_level[{0}][name]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);
$attrs_user_level_persetujuan_id    = array (
    'id'          => 'user_level_persetujuan_id{0}',
    'name'        => 'user_level[{0}][persetujuan_id]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);
$attrs_user_level_action    = array (
    'id'          => 'user_level_action{0}',
    'name'        => 'user_level[{0}][action]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
    'value'       => 'add',
);
$attrs_user_level_delete    = array (
    'id'          => 'user_level_delete{0}',
    'name'        => 'user_level[{0}][delete]',
    'type'        => 'hidden',
    'class'       => 'form-control',
    'readonly'    => 'readonly',
);

$order_option = array(
    '1' => '1',
    '2' => '2',
    '3' => '3'
);
 $form_option_payment2=' <div class="form-group">
                            <label class="control-label col-md-3">'.translate("File", $this->session->userdata("language")).'</label>
                                 
                            <div class="col-md-4">
                                <input type="file" id="upload_ID_{0}_dokumen" name="upload[_ID_0][dokumen]" class="uploadbutton up-this" value="" />
                               
                            </div>
                            <span class="input-group-btn">
                                        <a class="btn red-intense del-this2" title="Remove"><i class="fa fa-times"></i></a>
                            </span>
                             <input type="hidden" id="upload[_ID_0][filename]" name="upload[_ID_0][filename]"    />
                        </div> 
                        <div id="upload[_ID_0][choosen_file_container_1]" name="upload[_ID_0][choosen_file_container_1]" style="display:none" >
                                <div class="form-group">
                                    <label class="control-label col-md-3"></label>
                                    <div class="col-md-4">
                                        <label id="upload[_ID_0][choosen_file_1]" name="upload[_ID_0][choosen_file_1]">
                                           
                                        </label>
                                    </div>
                                </div>
                        </div>';
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Scan Klaim", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
                <a title="Tambah Scan" class="btn btn-primary add-payment2">
                    <i class="fa fa-plus"></i>
                    <span class="hidden-480">
                         <?=translate("Tambah", $this->session->userdata("language"))?>
                    </span>
                </a>
            </div>
	</div>
 <div class="portlet-body" style="display:none">
        <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
        <table class="table table-striped table-bordered table-hover" id="table_persetujuan">
            <thead>
                <tr class="heading">
                    <th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Order", $this->session->userdata("language"))?></th>
                    <th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></th>
                </tr>
            </thead>

            <tbody>
                 
            </tbody>
        </table>
    </div>

    <div class="portlet-body form">
        <div class="">
            <div class="form-group">
                <div class="col-md-12">
                    
                     <div id="section-payment2">

                        <input type="hidden" id="tpl-form-payment2" value="<?=htmlentities($form_option_payment2)?>">
                        <div class="form-body">
                            <ul class="list-unstyled">

                        </ul>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
   
               
	 
</div>
 