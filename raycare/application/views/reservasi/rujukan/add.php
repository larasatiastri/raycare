<div class="portlet light">
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_rujukan", 
			    "name"          => "form_add_rujukan", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."reservasi/rujukan/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');
		?>

		<div class="form-body">
			<div class="portlet light" id="section-telepon">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Buat Rujukan', $this->session->userdata('language'))?></span>
					</div>
				</div>
				<div class="portlet-body">
					<?php
						$this->poliklinik_m->set_columns(array('id', 'nama'));
						$poli_sub = $this->poliklinik_m->get();
						// die_dump($poli_sub);
						$poli_sub_option = array(
							'' => "Pilih..",

						);
					    foreach ($poli_sub as $select) {
					        $poli_sub_option[$select->id] = $select->nama;
					    }
						// die_dump($poli_sub_option);
								// <a class="btn btn-xs green-haze hidden" id="btn_save_subjek_telp_{0}" style="height:20px; width:20px;" title="'.translate('Save', $this->session->userdata('language')).'"><i class="fa fa-check" style="margin-left:-6px;"></i></a>

						$form_phone = '
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Pasien", $this->session->userdata("language")).' :</label>
							
							<div class="col-md-2">
								<input class="form-control input-sm" id="nomer_{0}" name="nama_ref_pasien" value="'.$flash_form_data["nama_ref_pasien"].'" placeholder="Referensi Pasien">
								<input class="form-control input-sm hidden" id="nomer_{0}" name="id_ref_pasien" value="'.$flash_form_data["id_ref_pasien"].'" placeholder="ID Referensi Pasien">
							</div>
							<span class="input-group-btn" style="left:-15px;">
								<a class="btn btn-xs grey-cascade pilih-pasien" style="height:20px;" title="'.translate('Pilih Pasien', $this->session->userdata('language')).'">
									<i class="fa fa-search"></i>
								</a>
							</span>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Tanggal Di Rujuk", $this->session->userdata("language")).' :</label>
							
							<div class="col-md-2">
								<div class="input-group date" id="tanggal_dirujuk">
									<input type="text" class="form-control" id="tanggal_dirujuk" name="tanggal_dirujuk" readonly >
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Tanggal Rujukan", $this->session->userdata("language")).' :</label>
							
							<div class="col-md-2">
								<div class="input-group date" id="tanggal_rujukan">
									<input type="text" class="form-control" id="tanggal_rujukan" name="tanggal_rujukan" readonly >
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Poliklinik Asal", $this->session->userdata("language")).' :</label>
							<div class="col-md-2">
								'.form_dropdown('phone[{0}][poliklinik_asal_id]', $poli_sub_option, '', "id=\"subjek_telp_{0}\" class=\"form-control input-sx\"").'
								<input type="text" id="input_subjek_telp_{0}" name="phone[{0}][poliklinik_asal]" class="form-control hidden">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Poliklinik Tujuan", $this->session->userdata("language")).' :</label>
							<div class="col-md-2">
								'.form_dropdown('phone[{0}][poliklinik_tujuan_id]', $poli_sub_option, '', "id=\"subjek_telp_{0}\" class=\"form-control input-sx\"").'
								<input type="text" id="input_subjek_telp_{0}" name="phone[{0}][poliklinik_tujuan]" class="form-control hidden">
							</div>
							<span class="input-group-btn" style="left:-15px;">
								<a class="btn btn-xs blue-chambray" id="btn_edit_subjek_telp_{0}" style="height:20px; width:20px;" title="'.translate('Edit', $this->session->userdata('language')).'"><i class="fa fa-pencil" style="margin-left:-6px;"></i></a>
								<a class="btn btn-xs yellow hidden" id="btn_cancel_subjek_telp_{0}" style="height:20px; width:20px;" title="'.translate('Cancel', $this->session->userdata('language')).'"><i class="fa fa-undo" style="margin-left:-6px;"></i></a>
							</span>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
							<div class="col-md-2">
								<input class="form-control input-sm" id="nomer_{0}" name="phone[{0}][subjek]" placeholder="Subjek">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Keterangan", $this->session->userdata("language")).' :</label>
							<div class="col-md-2">
								<textarea class="form-control input-sm" id="nomer_{0}" name="phone[{0}][keterangan]" placeholder="Keterangan"></textarea>
							</div>
						</div>';
					?>

			<input type="hidden" id="tpl-form-phone" value="<?=htmlentities($form_phone)?>">
			<div class="form-body">
				<ul class="list-unstyled">
					<!-- <div class="form-group">
						<label class="control-label col-md-4"><?=translate("Pasien", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-5">
							<?php
								$nama_ref_pasien = array(
									"id"			=> "nama_ref_pasien",
									"name"			=> "nama_ref_pasien",
									"autofocus"			=> true,
									"class"			=> "form-control", 
									"placeholder"	=> translate("Referensi Pasien", $this->session->userdata("language")), 
									"value"			=> $flash_form_data['nama_ref_pasien'],
									"help"			=> $flash_form_data['nama_ref_pasien'],
								);

								$id_ref_pasien = array(
									"id"			=> "id_ref_pasien",
									"name"			=> "id_ref_pasien",
									"autofocus"			=> true,
									"class"			=> "form-control hidden", 
									"placeholder"	=> translate("ID Referensi Pasien", $this->session->userdata("language")), 
									"value"			=> $flash_form_data['id_ref_pasien'],
									"help"			=> $flash_form_data['id_ref_pasien'],
								);
								echo form_input($nama_ref_pasien);
								echo form_input($id_ref_pasien);
							?>
							
						</div>
						<span class="input-group-btn" style="left:-15px;">
							<a class="btn btn-xs grey-cascade pilih-pasien" style="height:20px;" title="<?=translate('Pilih Pasien', $this->session->userdata('language'))?>">
								<i class="fa fa-search"></i>
							</a>
						</span>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Tanggal Di RUjuk", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-5">
							<div class="input-group date" id="tanggal_dirujuk">
								<input type="text" class="form-control" id="tanggal_dirujuk" name="tanggal_dirujuk" readonly >
								<span class="input-group-btn">
									<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate("Tanggal Rujukan", $this->session->userdata("language"))?> :</label>
						
						<div class="col-md-5">
							<div class="input-group date" id="tanggal_rujukan">
								<input type="text" class="form-control" id="tanggal_rujukan" name="tanggal_rujukan" readonly >
								<span class="input-group-btn">
									<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
					</div> -->
				</ul>
			</div>
		</div>
	</div>
</div>

		<?php $msg = translate("Apakah anda yakin akan membuat rujukan ini?",$this->session->userdata("language"));?>
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

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row" class="heading">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tempat, Tanggal Lahir', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 




