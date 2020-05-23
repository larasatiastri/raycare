 
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("INFORMASI KELOMPOK KLAIM", $this->session->userdata("language"))?></span>
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
		        "command"   => "edit"
		    );
		    echo form_open(base_url()."master/penjamin/save", $form_attr, $hidden);

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
					<label class="control-label col-md-3"><?=translate("Nama", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-4">
						<?php
							$kode_cabang = array(
								"name"			=> "nama",
								"id"			=> "nama",
								"autofocus"		=> true,
								"class"			=> "form-control", 
								"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
								"required"		=> "required",
								"value"			=> $form_data->nama
							);
							echo form_input($kode_cabang);
						?>
					</div>
					<input type="hidden" id="pk" name="pk" value="<?=$pk?>">
					<input type="hidden" id="flag" name="flag" value="<?=$flag?>">
					<input type="hidden" id="suspval" name="suspval" value="0"><input type="checkbox" id="susp" name="susp" class="make-switch" value="1" <?if($form_data->is_suspended==1){?>checked<?}?>> Suspended
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Masuk Kelompok", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-3">
						 <input type="radio" class="make-switch" name="kel" id="kel1" value="1" <?if($form_data->is_parent==1){?>checked<?}?>>Ya &nbsp;&nbsp&nbsp;&nbsp;&nbsp;<input type="radio" class="make-switch" name="kel" id="kel2" value="2" <?if($form_data->is_parent==0){?>checked<?}?>>Tidak
					</div>
				</div>
				<div class="form-group" id="url1">
					<label class="control-label col-md-3"><?=translate("URL", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-4">
						 <input type="text" class="form-control" name="url" id="url" placeholder="URL" value="<?=$form_data->url?>"> 
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Masa Aktif", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				    <div class="col-md-2">
				    	<div class="input-group input-medium-date date date-picker">
										 
											<input class="form-control" id="date1" name="tggl1" readonly value="<?=date('d F Y',strtotime($form_data->tanggal_aktif))?>">
											<span class="input-group-btn">
												<button type="button" class="btn default date-set">
													<i class="fa fa-calendar"></i>
												</button> 
											</span>
											  
										 	 
										 
										 
						</div>
					</div>

					<div class="col-md-2">
					<div class="input-group input-medium-date date date-picker">
											<input class="form-control" id="date2" name="tggl2" readonly value="<?=date('d F Y',strtotime($form_data->tanggal_non_aktif))?>">
											<span class="input-group-btn">
												<button type="button" class="btn default date-set">
													<i class="fa fa-calendar"></i>
												</button>
											</span>
					</div>
					</div>			 
								 
					 
					 
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
					<div class="col-md-4">
						<?php
							$alamat_cabang = array(
								"name"			=> "keterangan1",
								"id"			=> "keterangan1",
								"class"			=> "form-control",
								"rows"			=> 6, 
								"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
								"value"			=> $form_data->keterangan
							);
							echo form_textarea($alamat_cabang);
						?>
					</div>
				</div>
				<div class="portlet">
				<div class="portlet-title" style="margin-top:30px;">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Dokumen Klaim", $this->session->userdata("language"))?></span>
					</div>
				</div>
	
				<div class="portlet-body form">
					<div class="form-body">
						<ul class="nav nav-tabs">
							<li  class="active">
								<a href="#akses_user" data-toggle="tab" onclick="$('#akses_user').fadeIn('slow', function() { });$('#persetujuan').hide();$('#kelompok').hide()">
									<?=translate('Syarat Pasien', $this->session->userdata('language'))?> </a>
							</li>
							<li>
								<a href="#persetujuan" data-toggle="tab" onclick="$('#persetujuan').fadeIn('slow', function() { });$('#akses_user').hide();$('#kelompok').hide()">
									<?=translate('Scan Klaim', $this->session->userdata('language'))?> </a>
							</li>
							<li id="grup" style="display:none">
								<a href="#kelompok" data-toggle="tab" onclick="$('#kelompok').fadeIn('slow', function() { });$('#persetujuan').hide();$('#akses_user').hide()">
									<?=translate('Kelompok', $this->session->userdata('language'))?> </a>
							</li>
						</ul>
					<div class="tab-content">
							<div class="tab-pane active" id="akses_user" >
								<?php include('tab_klaim_asuransi/akses_user.php') ?>
							</div>
							<div class="tab-pane" id="persetujuan">
								<?php include('tab_klaim_asuransi/persetujuan.php') ?>
							</div>
							<div class="tab-pane" id="kelompok">
								<?php include('tab_klaim_asuransi/kelompok.php') ?>
							</div>
					</div>
			
				</div>
			</div>
		<?php $msg = translate("Apakah anda yakin akan membuat jaminan ini?",$this->session->userdata("language"));?>
		<div class="form-actions fluid">	
			<div class="col-md-offset-1 col-md-9">
				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
				<a id="confirm_save" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
	            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			</div>		
		</div>
		 
	</div>
		 
		<?=form_close()?>
	</div>
</div>
</div>

 
