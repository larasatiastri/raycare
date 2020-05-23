<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-pencil font-blue-sharp"> </i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Edit Data Fitur", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_edit_fitur", 
			    "name"          => "form_edit_fitur", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    $hidden = array(
		        "command"   => "edit",
		        "id"		=> $pk
		    );
		    echo form_open(base_url()."master/fitur/save", $form_attr, $hidden);

			$item_row_template =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
		?>
			<div class="form-body">
				<div class="alert alert-danger display-hide">
			        <button class="close" data-close="alert"></button>
			        <?=$form_alert_danger?>
			    </div>
			    <div class="alert alert-success display-hide">
			        <button class="close" data-close="alert"></button>
			        <?=$form_alert_success?>
			    </div>
			     <input type="hidden" id="pk" name="pk" value="<?=$pk?>">
			    
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Nama", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-2">
						<?php
							$nama = array(
								"name"			=> "nama",
								"id"			=> "nama",
								"autofocus"			=> true,
								"class"			=> "form-control", 
								"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
								"required"		=> "required",
								"value"			=> $form_data[0]->nama
							);
							echo form_input($nama);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Path", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$path = array(
								"name"			=> "path",
								"id"			=> "path",
								"class"			=> "form-control", 
								"placeholder"	=> translate("Path", $this->session->userdata("language")), 
								"required"		=> "required",
								"value"			=> $form_data[0]->path
							);
							echo form_input($path);
						?>
					</div>
				</div>
				
				  <div class="row" style="display:none">
                                        <div class="col-md-12">
                                            <div class="portlet">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class=""></i><span class="caption-subject font-green-sharp bold uppercase"><?=translate("Fitur", $this->session->userdata("language"))?></span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered table-hover" id="table_order_item">
                                                            <thead>
                                                                <tr role="row" class="heading">
                                                                    <th colspan="2" class="text-center" width="150"><?=translate('Kode Tindakan', $this->session->userdata("language"))?></th>
                                                                    <th class="text-center" ><?=translate('Nama', $this->session->userdata("language"))?></th>
                                                                    <th class="text-center"><?=translate('Path', $this->session->userdata("language"))?></th>
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
			<?php $msg = translate("Apakah anda yakin akan merubah data fitur ini?",$this->session->userdata("language"));?>
			<div class="form-actions right">	
			<a class="btn default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
    				<a id="confirm_save" class="btn  btn-primary" href="#" data-confirm="<?=$msg?>" data-proses="<?=$msg_proses?>" data-toggle="modal">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?>
			</a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>	
    			</div>		
			</div>
		<?=form_close()?>
