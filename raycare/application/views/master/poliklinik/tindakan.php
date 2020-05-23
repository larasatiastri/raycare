<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-green-sharp bold uppercase"><?=translate("Poliklinik Tindakan", $this->session->userdata("language"))?></span>
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

		    $btn_search = '<div class="text-center"><button title="Cari Tindakan" class="btn btn-sm btn-success search-item"><i class="fa fa-search"></i></button></div>';
			$btn_del    = '<div class="text-center"><button class="btn btn-sm green-haze save-this" title="Simpan Tindakan"><i class="fa fa-check"></i></button> <button class="btn btn-sm red-intense del-this" title="Hapus Tindakan"><i class="fa fa-times"></i></button></div>';
			$btn_dollar = '<a title="Set Harga" class="btn btn-sm green-haze select" data-target="#ajax_notes" data-toggle="modal" style="margin:0px;"><i class="fa fa-dollar"></i></a>';

			// $item_cols = array(
            //  'item_code'   => '<input type="hidden" id="tindakan_idt_{0}" name="tindakan[{0}][idt]"><input type="hidden" id="tindakan_id_{0}" name="tindakan[{0}][tindakan_id]"><input type="text" id="tindakan_code_{0}" name="tindakan[{0}][code]" class="form-control" readonly>',
            //  'item_search' => $btn_search,
            //  'item_name'   => '<input type="text" id="tindakan_nama_{0}" name="tindakan[{0}][nama]" class="form-control" readonly>',
            //  'item_harga'  => '<input type="text" id="tindakan_harga_{0}" name="tindakan[{0}][harga]" class="form-control" readonly>'.$btn_dollar,
            //  'action'      => $btn_del,
			// );

			$item_cols = array(
    			'item_code'   => '<input type="hidden" id="tindakan_idt_{0}" name="tindakan[{0}][idt]"><input type="hidden" id="tindakan_id_{0}" name="tindakan[{0}][tindakan_id]"><input type="text" id="tindakan_code_{0}" name="tindakan[{0}][code]"  readonly style="background-color: transparent;border: 0px solid;">',
    			'item_search' => $btn_search,
    			'item_name'   => '<input type="text" id="tindakan_nama_{0}" name="tindakan[{0}][nama]" readonly style="background-color: transparent;border: 0px solid">',
    			'item_harga'  => '<div class="input-group"><input type="text" id="tindakan_harga_{0}" name="tindakan[{0}][harga]" readonly style="background-color: transparent;border: 0px solid;text-align:right;" size="22"><span class="input-group-btn">'.$btn_dollar.'</span></div>',
    			'action'      => $btn_del,
			);

 			$item_row_template =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

		?>
			<div class="form-body">
			 	<input type="hidden" id="pk" name="pk" value="<?=$pk?>"> 
			 	<input type="hidden" id="flag" name="flag" value="<?=$flag?>"> 
			 	<input type="hidden" id="jml" name="jml" value="<?=count($form_data3)?>"> 
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Kode Poliklinik", $this->session->userdata("language"))?> :</label>
					<div class="col-md-2">
						<label class="control-label"><?=$form_data->kode?></label>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Nama Poliklinik", $this->session->userdata("language"))?> :</label>
					<div class="col-md-3">
						<label class="control-label"><?=$form_data->nama?></label>
				</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
					<div class="col-md-3">
						<label class="control-label" style="text-align: left;"><?=$form_data->keterangan?></label>
					</div>
				</div>
				<div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <div class="portlet">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class=""></i><span class="caption-subject font-green-sharp bold uppercase"><?=translate("Tindakan", $this->session->userdata("language"))?></span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                        <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
                                        <table class="table table-striped table-bordered table-hover" id="table_order_item2">
                                            <thead>
                                                <tr role="row" class="heading">
                                                    <th colspan="2" class="text-center" width="150"><?=translate('Kode Tindakan', $this->session->userdata("language"))?></th>
                                                    <th class="text-center" ><?=translate('Nama', $this->session->userdata("language"))?></th>
                                                    <th class="text-center" width="200"><?=translate('Harga', $this->session->userdata("language"))?></th>
                                                    <th class="text-center" width="200"><?=translate('Aksi', $this->session->userdata("language"))?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                </div>
                                <div class="table-responsive" style="display:none">
                                    <table class="table table-striped table-bordered table-hover" id="table_order_item">
                                        <thead>
                                            <tr role="row" class="heading">
                                                <th   class="text-center" width="150"><?=translate('Kode Tindakan', $this->session->userdata("language"))?></th>
                                                <th class="text-center" ><?=translate('Nama', $this->session->userdata("language"))?></th>
                                                <th class="text-center"><?=translate('Harga', $this->session->userdata("language"))?></th>
                                                <th class="text-center"><?=translate('Aksi', $this->session->userdata("language"))?></th>
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
            <div class="form-actions fluid">
                <div class="col-md-offset-1 col-md-9">
                    <a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
                </div>
            </div>
			<input type="hidden" id="pk2" name="pk2">
		<?=form_close()?>
	</div>
</div>
 
<form id="modaltindakan" name="modaltindakan"  role="form" autocomplete="off">
    <?
    	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
        $msg = translate("Apakah anda yakin akan menyimpan harga ini?",$this->session->userdata("language"));
    ?>
    <div class="modal fade" id="ajax_notes" role="basic" aria-hidden="true" style="display:none">
        <div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title caption-subject font-green-sharp bold uppercase"><?=translate('Harga tindakan per poliklinik', $this->session->userdata('language'))?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
        	            <div class="alert alert-danger display-hide">
        			        <button class="close" data-close="alert"></button>
        			        <?=$form_alert_danger?>
                		</div>
                		<div class="alert alert-success display-hide">
        			        <button class="close" data-close="alert"></button>
        			        <?=$form_alert_success?>
                        </div>
                        <div class="style" style="margin-top: 10px;">
                	        <div class="form-group">
        						<label class="control-label col-md-3"><?=translate("Tanggal", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
        						<div class="col-md-4">
        						    <div class="input-group input-medium-date date date-picker">
        								<input class="form-control" id="date" readonly required="required" value="<?=date('d M Y')?>">
        								<span class="input-group-btn">
        								    <button type="button" class="btn default date-set">
        									    <i class="fa fa-calendar"></i>
        									</button>
                                		</span>
        						    </div>
        						</div>
        					    <div class="col-md-12"></div>
        			        </div>
                            <div class="form-group">
        						<label class="control-label col-md-3"><?=translate("Harga", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
        						<div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" id="harga" name="harga" required="required" class="form-control">
                                        <span class="input-group-btn">
                                            <a title="Tambahkan" id="confirm_save" class="btn green-haze" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-plus"></i></a>
                                        </span>
                                    </div>

        						</div>
        					    <div class="col-md-12"></div>
        			        </div>
                        </div>
                    </div>

                    <div class="form-body" style="margin-top: 15px;">
                        <table class="table table-striped table-bordered table-hover" id="table_addperson">
                            <thead>
                                <tr role="row" class="heading">
                                    <th scope="col" ><div class="text-center"><?=translate("Tanggal", $this->session->userdata("language"))?></div></th>
                                    <th scope="col" ><div class="text-center"><?=translate("Harga", $this->session->userdata("language"))?></div></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions fluid">	
    				    <div class="col-md-12">
                            <button type="reset" class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></button>
                            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
        		      	</div>		
                    </div>
                    <!-- <button type="button" class="btn default" data-dismiss="modal">Simpan</button> -->
                </div>
            </div>
        </div>
    </div>
</form>

<div id="popover_item_content">
    <table id="table_item_search" class="table table-striped table-bordered table-hover">
        <thead>
            <tr class="heading">
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