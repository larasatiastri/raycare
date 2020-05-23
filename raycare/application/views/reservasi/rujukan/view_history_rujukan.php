<div class="portlet light">
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_view_history_rujukan", 
			    "name"          => "form_view_history_rujukan", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."master/rujukan/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');
		?>

		<div class="form-body">
			<div class="portlet light" id="section-telepon">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('View History Rujukan', $this->session->userdata('language'))?></span>
					</div>
				</div>
				<div class="portlet-body">
					<?php

						$poliklinik = '';

						if($form_data['poliklinik_tujuan'] != null) {
							$poliklinik = $form_data['poliklinik_tujuan'];

						} elseif($data_poliklinik[0]['nama'] != null) {
							$poliklinik = $data_poliklinik[0]['nama'];
						}

						///////////////////////////////////////////////////////
						
						if($form_data['poliklinik_asal_id'] != null) 
							$poliklinik_asal = $data_poliklinik_asal[0]['nama'];
						

						$form_phone = '
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Poliklinik Asal", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<label class="control-label">'.$poliklinik_asal.'</label>
								<input class="form-control hidden" type="text" value="'.$pk_value.'" id="input_subjek_telp_{0}" name="phone[{0}][poliklinik]" class="form-control hidden">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Poliklinik Tujuan", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<label class="control-label">'.$poliklinik.'</label>
								<input class="form-control hidden" type="text" value="'.$pk_value.'" id="input_subjek_telp_{0}" name="phone[{0}][poliklinik]" class="form-control hidden">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Subjek", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<label class="control-label">'.$form_data['subjek'].'</label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">'.translate("Keterangan", $this->session->userdata("language")).' :</label>
							<div class="col-md-5">
								<label class="control-label">'.$form_data['keterangan'].'</label>
							</div>
						</div>';
					?>

					<input type="hidden" id="tpl-form-phone" value="<?=htmlentities($form_phone)?>">
					<div class="form-body">
						<ul class="list-unstyled">
							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Pasien", $this->session->userdata("language"))?> :</label>
								<div class="col-md-5">
									<label class="control-label"><?=$data_pasien[0]['nama']?></label>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Tanggal Di Rujuk", $this->session->userdata("language"))?> :</label>
								<div class="col-md-5">
									<label class="control-label"><?=date('d M Y', strtotime($form_data['tanggal_dirujuk']))?></label>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Tanggal Rujukan", $this->session->userdata("language"))?> :</label>
								
								<div class="col-md-5">
									<label class="control-label"><?=date('d M Y', strtotime($form_data['tanggal_rujukan']))?></label>
								</div>
							</div>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<?php $msg = translate("Apakah anda yakin akan membuat rujukan ini?",$this->session->userdata("language"));?>
		<div class="form-actions fluid">	
			<div class="col-md-offset-1 col-md-9">
				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
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




