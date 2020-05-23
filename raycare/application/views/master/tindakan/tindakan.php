
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Poliklinik Tindakan", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_tindakan", 
			    "name"          => "form_tindakan", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    $hidden = array(
		        "command"   => "add"
		    );
            echo form_open("", $form_attr, $hidden);

		    $btn_search = '<div class="text-left"><button title="Cari Tindakan" class="btn btn-success search-item"><i class="fa fa-search"></i></button></div>';
			$btn_del    = '<div class="text-left"><button class="btn btn-primary save-this" title="Simpan Tindakan"><i class="fa fa-check"></i></button> <button class="btn red-intense del-this" title="Hapus Tindakan"><i class="fa fa-times"></i></button></div>';
			$btn_dollar = '<a title="Set Harga" class="btn btn-primary select" href="'.base_url().'master/tindakan/add_harga" data-target="#ajax_notes" data-toggle="modal" style="margin:0px;" id="btn_select_{0}"><i class="fa fa-dollar"></i></a>';

             $result = $this->poliklinik_m->get_by(array('is_active' => 1));
                     //           die_dump($result);
              foreach($result as $row)
              {
                 $poli_option[$row->id] = $row->nama;
               //  $warehouse[$row->id] = $row->harga;
              }
                                
 
			$item_cols = array(
    			// 'item_code'   => '<input type="hidden" id="tindakan_idt_{0}" name="tindakan[{0}][idt]"><input type="hidden" id="tindakan_id_{0}" name="tindakan[{0}][tindakan_id]"><input type="text" id="tindakan_code_{0}" name="tindakan[{0}][code]"  readonly style="background-color: transparent;border: 0px solid;">',
    			// 'item_search' => $btn_search,
    			// 'item_name'   => '<input type="text" id="tindakan_nama_{0}" name="tindakan[{0}][nama]" readonly style="background-color: transparent;border: 0px solid">',
                'item_poliklinik' =>'<input type="hidden" id="tindakan_idt_{0}" name="tindakan[{0}][idt]"><input type="hidden" id="tindakan_idp_{0}" name="tindakan[{0}][idp]">'.form_dropdown("tindakan[{0}][poli]", $poli_option, "", "id=\"tindakan_poli_{0}\" class=\"form-control select2me\" required data-placeholder=\"".translate('Choose People', $this->session->userdata('language'))."\" "),
    			'item_harga'  => '<div class="input-group"><input type="text" id="tindakan_harga_{0}" name="tindakan[{0}][harga]" value="'.formatrupiah($form_data->harga).'" readonly style="background-color: transparent;border: 0 solid;text-align:right" size="70"><span class="input-group-btn">'.$btn_dollar.'</span></div>',
    			'action'      => '<div class="text-left inline-button-table">'.$btn_del.'</div>',
			);

 			$item_row_template =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

		?>
			<div class="form-body">
			 	<input type="hidden" id="pk" name="pk" value="<?=$pk?>"> 
			 	<input type="hidden" id="flag" name="flag" value="<?=$flag?>"> 
			 	<input type="hidden" id="jml" name="jml" value="<?=count($form_data3)?>"> 
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Kode", $this->session->userdata("language"))?> :</label>
					<div class="col-md-2">
						<label class="control-label"><strong><?=$form_data->kode?></strong></label>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
					<div class="col-md-3">
						<label class="control-label"><strong><?=$form_data->nama?></strong></label>
				    </div>

				</div>
                <div class="form-group">
                    <label class="control-label col-md-3"><?=translate("Harga", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-3">
                        <label class="control-label"><strong><?=formatrupiah($form_data->harga)?></strong></label>
                    </div>
                </div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
					<div class="col-md-3">
						<label class="control-label" style="text-align: left;"><strong><?=$form_data->keterangan?></strong></label>
					</div>
				</div>
				<div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <div class="portlet">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class=""></i><span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan", $this->session->userdata("language"))?></span>
                                </div>
                                <div class="actions">
                                    <a  class="btn btn-primary" id="addrow">
                                    <i class="fa fa-plus"></i>
                                        <span class="hidden-480">
                                            <?=translate("Tambah", $this->session->userdata("language"))?>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                        <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
                                        <table class="table table-striped table-bordered table-hover" id="table_order_item2">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="500"><?=translate('Poliklinik', $this->session->userdata("language"))?></th>
                                                    <th class="text-center" width="500"><?=translate('Harga', $this->session->userdata("language"))?></th>
                                                    <th class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata("language"))?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>   
			</div>
            <div class="form-actions fluid right">
                <div class="col-md-12">
                    <a class="btn btn-circle default" href="javascript:history.go(-1)">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        <?=translate("Kembali", $this->session->userdata("language"))?>
                    </a>
                </div>
            </div>
			<input type="hidden" id="pk2" name="pk2">
		<?=form_close()?>
	</div>
</div>
 

    <div class="modal fade" id="ajax_notes" role="basic" aria-hidden="true" style="display:none">
        <div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>


<div id="popover_item_content">
    <table id="table_item_search" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?></th>
                <th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?></th>
                <th class="text-center"><?=translate("Harga", $this->session->userdata("language"))?></th>
                <th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>