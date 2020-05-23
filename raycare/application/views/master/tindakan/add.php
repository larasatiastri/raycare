<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp bold"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_cabang", 
			    "name"          => "form_add_cabang", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    $hidden = array(
		        "command"   => "add"
		    );
		    echo form_open(base_url()."master/tindakan/save", $form_attr, $hidden);

			$btn_search = '<div class="text-center"><button title="Search Item" class="btn btn-sm btn-success search-item"><i class="fa fa-search"></i></button></div>';
			$btn_del    = '<div class="text-center"><button class="btn btn-sm red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';
 
			// $item_cols = array(
			//     'item_code'   => form_input($attrs_tindakan_id).form_input($attrs_tindakan_code),
			//     'item_search' => $btn_search,
			//     'item_name'   => $attrs_tindakan_nama,
			//     'item_harga'  => $attrs_tindakan_harga,
			//     'action'      => $btn_del,
			// );

			$item_cols = array(
			    'item_code'   => '<input type="hidden" id="tindakan_id_{0}" name="tindakan[{0}][tindakan_id]"><input type="text" id="tindakan_code_{0}" name="tindakan[{0}][code]" class="form-control" readonly>',
			    'item_search' => $btn_search,
			    'item_name'   => '<input type="text" id="tindakan_nama_{0}" name="tindakan[{0}][nama]" class="form-control" readonly>',
			    'item_harga'  => '<input type="text" id="tindakan_harga_{0}" name="tindakan[{0}][harga]" class="form-control" readonly>',
			    'action'      => $btn_del,
			);

			// gabungkan $item_cols jadi string table row
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
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Kode", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$kode_cabang = array(
								"name"			=> "kode1",
								"id"			=> "kode1",
								"autofocus"		=> true,
								"class"			=> "form-control", 
								"placeholder"	=> translate("Kode", $this->session->userdata("language")), 
								"required"		=> "required"
							);
							echo form_input($kode_cabang);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$nama_cabang = array(
								"name"			=> "nama1",
								"id"			=> "nama1",
								"class"			=> "form-control", 
								"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
								"required"		=> "required"
							);
							echo form_input($nama_cabang);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Harga Pusat", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$nama_cabang = array(
								"name"			=> "harga1",
								"id"			=> "harga1",
								"class"			=> "form-control", 
								"placeholder"	=> translate("Harga Pusat", $this->session->userdata("language")), 
								"required"		=> "required"
							);
							echo form_input($nama_cabang);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
					<div class="col-md-3">
						<?php
							$alamat_cabang = array(
								"name"			=> "keterangan1",
								"id"			=> "keterangan1",
								"class"			=> "form-control",
								"rows"			=> 5, 
								"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
							);
							echo form_textarea($alamat_cabang);
						?>
					</div>
				</div>
				  
			</div>
			<?php $msg = translate("Apakah anda yakin akan membuat tindakan ini?",$this->session->userdata("language"));?>
			<div class="form-actions fluid">	
				<div class="col-md-offset-3">
    				<a class="btn btn-circle default" href="javascript:history.go(-1)">
    					<i class="glyphicon glyphicon-chevron-left"></i>
    					<?=translate("Kembali", $this->session->userdata("language"))?>
    				</a>
                    <button type="reset" class="btn btn-circle default" >
                    	<i class="fa fa-eraser"></i>
                    	<?=translate("Reset", $this->session->userdata("language"))?>
                    </button>
    				<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
    					<i class="glyphicon glyphicon-floppy-disk"></i>
    					<?=translate("Simpan", $this->session->userdata("language"))?>
    				</a>
                    <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
    			</div>		
			</div>
		<?=form_close()?>
	</div>
</div>