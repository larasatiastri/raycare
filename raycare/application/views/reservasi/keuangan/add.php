<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tambah Keuangan", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_keuangan", 
			    "name"          => "form_add_form_add_keuangan", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    $hidden = array(
		        "command"   => "add"
		    );
		    echo form_open(base_url()."reservasi/keuangan/save", $form_attr, $hidden);

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
					<label class="control-label col-md-2"><?=translate("Bulan", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-2">
						<div class="input-group input-medium-date date date-picker2">
										 
											<input class="form-control" id="date2" name="tggl2" readonly value="<?=date('d F Y')?>">
											<span class="input-group-btn">
												<button type="button" class="btn default date-set">
													<i class="fa fa-calendar"></i>
												</button> 
											</span>
											  
										 	 
										 
										 
							</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Tipe", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						 <select id="tipe2" name="tipe2" class="form-control">
						 	<option value="1">Tambah Saldo</option>
						 	<option value="2">Biaya</option>
						 </select>
					</div>
				</div>
					<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Rupiah", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						<?php
							$nama_cabang = array(
								"name"			=> "rupiah2",
								"id"			=> "rupiah2",
								"class"			=> "form-control", 
								"placeholder"	=> translate("Rupiah", $this->session->userdata("language")), 
								"required"		=> "required"
							);
							echo form_input($nama_cabang);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
					<div class="col-md-3">
						<?php
							$alamat_cabang = array(
								"name"			=> "keterangan2",
								"id"			=> "keterangan2",
								"class"			=> "form-control",
								"rows"			=> 5, 
								"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
							);
							echo form_textarea($alamat_cabang);
						?>
					</div>
				</div>
				
	 		   	<div class="row" style="display:none">
					<div class="col-md-12">
                        <div class="portlet">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class=""></i><span class="caption-subject font-green-sharp bold uppercase"><?=translate("Tindakan", $this->session->userdata("language"))?></span>
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
			<?php $msg = translate("Apakah anda yakin akan membuat poliklinik ini?",$this->session->userdata("language"));?>
			<div class="form-actions fluid">	
				<div class="col-md-offset-1 col-md-9">
    				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
                  
    				<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
                    <button type="submit" id="savekeuangan" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
    			</div>		
			</div>
		<?=form_close()?>
	</div>
</div>
 