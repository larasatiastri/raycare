<div class="portlet light">
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_resep_manual", 
			    "name"          => "form_add_resep_manual", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
				"command" => "add_resep_manual",
				"id"      => $pk_value,
		    );

		    echo form_open(base_url()."apotik/resep_obat/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');

			// build item row

			$btn_search        = '<div class="text-center"><button title="" class="btn btn-sm btn-primary search-item" data-original-title="Search Item"><i class="fa fa-search"></i></button></div>';
			$btn_search_result = '<div class="text-center"><button title="" class="btn btn-sm btn-success search-item-result" data-original-title="Search Item"><i class="fa fa-search"></i></button></div>';
			$btn_del           = '<div class="text-center"><button class="btn btn-sm red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

			$attrs_item_id  = array ( 
			    'id'       => 'items_item_id_{0}',
			    'type'     => 'hidden',
			    'name'     => 'items[{0}][item_id]',
			    'class'    => 'form-control',
			    // 'hidden'   => 'hidden',
			    // 'style'    => 'width:80px;',
			    'readonly' => 'readonly',
			    // 'value' => 'BLSG01',
			);

			$attrs_item_kode = array (
			    'id'          => 'items_kode_{0}',
			    'name'        => 'items[{0}][item_kode]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			);

			$attrs_item_nama = array(
			    'id'          => 'items_nama_{0}',
			    'name'        => 'items[{0}][item_nama]',
			    'class'       => 'form-control hidden',
			    'readonly'    => 'readonly',
			);


			$attrs_jumlah = array(
			    'id'    => 'items_jumlah_{0}',
			    'name'  => 'items[{0}][jumlah]', 
			    'type'  => 'number',
			    'min'   => 0,
			    'class' => 'form-control text-right',
			    /*'style' => 'width:80px;',*/
			    'value' => 1,
			);

			$satuan_option = array(
				'' => 'Pilih..'
			);

			$item_cols = array(// style="width:156px;
				'item_kode'   => '<label class="control-label" name="items[{0}][item_kode]" style="text-align : left !important; width : 150px !important;"></label>'.form_input($attrs_item_id).form_input($attrs_item_kode),
				'item_search' => $btn_search,
				'item_name'   => '<label class="control-label" name="items[{0}][item_nama]"></label>'.form_input($attrs_item_nama),
				'item_jumlah' => form_input($attrs_jumlah),
				'item_satuan' => form_dropdown('items[{0}][satuan]', $satuan_option, "", "id=\"items_satuan_{0}\" class=\"form-control\""),
				'action'      => $btn_del,
			);

			$item_row_template =  '<tr id="item_row_{0}" class="table_resep_obat"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
		?>

		<div class="form-body">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Resep Obat", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<div class="alert alert-danger display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_danger?>
					    </div>
					    <div class="alert alert-success display-hide">
					        <button class="close" data-close="alert"></button>
					        <?=$form_alert_success?>
					    </div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
									<div class="col-md-5">
										<?php
											$nama = array(
												"id"			=> "nama",
												"name"			=> "nama",
												"autofocus"			=> true,
												"class"			=> "form-control required", 
												"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
												"value"			=> $flash_form_data['nama'],
												"help"			=> $flash_form_data['nama'],
											);
											echo form_input($nama);
										?>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-5">
										<?php
											$keterangan = array(
												"id"			=> "keterangan",
												"name"			=> "keterangan",
												"rows"			=> 6,
												"autofocus"			=> true,
												"class"			=> "form-control", 
												"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
												"value"			=> $flash_form_data['keterangan'],
												"help"			=> $flash_form_data['keterangan'],
											);
											echo form_textarea($keterangan);
										?>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-2"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
									<div class="col-md-5">
										
										<?php
											$user = $this->user_m->get($form_data['created_by']);
            								$user_array = object_to_array($user);

											$dokter_id = array(
												"id"			=> "dokter_id",
												"name"			=> "dokter_id",
												"autofocus"			=> true,
												"class"			=> "form-control required hidden", 
												"placeholder"	=> translate("Dokter Id", $this->session->userdata("language")), 
												"value"			=> $form_data['created_by'],
												"help"			=> $flash_form_data['dokter_id'],
											);
											echo form_input($dokter_id);
										?>

										<label class="control-label"><?=$user_array['nama']?></label>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
									<div class="col-md-5">
										<label class="control-label"><?=date('d F Y', strtotime($form_data['created_date']));?></label>
										<?php

											$tanggal = array(
												"id"			=> "tanggal",
												"name"			=> "tanggal",
												"autofocus"			=> true,
												"class"			=> "form-control required hidden", 
												"placeholder"	=> translate("Dokter Id", $this->session->userdata("language")), 
												"value"			=> $form_data['created_date'],
												"help"			=> $flash_form_data['created_date'],
											);
											echo form_input($tanggal);
										?>

										
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-2"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
									<div class="col-md-5">
										<label class="control-label"><?=$form_data['keterangan']?></label>
										<?php
											$keterangan_dokter = array(
												"id"			=> "keterangan_dokter",
												"name"			=> "keterangan_dokter",
												"rows"			=> 6,
												"autofocus"			=> true,
												"class"			=> "form-control hidden", 
												"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
												"value"			=> $form_data['keterangan'],
												"help"			=> $flash_form_data['keterangan'],
											);
											echo form_textarea($keterangan_dokter);
										?>
									</div>
								</div>
							</div>
						</div>
						

						
					</div>
				</div>	
			</div>
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Item Yang Digunakan', $this->session->userdata('language'))?></span>
					</div>
				</div>
				<div class="portlet-body">
					<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
					<table class="table table-striped table-bordered table-hover" id="table_item_digunakan">
						<thead>

							<tr class="heading">
								<th colspan="2" class="text-center" style="width : 250px !important;"><?=translate("Kode", $this->session->userdata("language"))?></th>
								<th class="text-center" style="width : 750px !important;"><?=translate("Nama", $this->session->userdata("language"))?></th>
			                    <th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
			                    <th class="text-center" style="width : 150px !important;"><?=translate("Satuan", $this->session->userdata("language"))?></th>
								<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?></th>
							</tr>
						</thead>

						<tbody>
						</tbody>
					</table>
				</div>
			</div>

			<?php $msg = translate("Apakah anda yakin akan membuat resep obat ini?",$this->session->userdata("language"));?>
			<div class="form-actions fluid">	
				<div class="col-md-offset-1 col-md-9">
					<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
					<a id="confirm_save" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
			        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
				</div>		
			</div>
			<?=form_close()?>
		</div>
	</div>
</div>

<div id="popover_item_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_item_search">
            <thead>
                <tr role="row" class="heading">
                    <th><div class="text-center"><?=translate('Id', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Stok', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 




