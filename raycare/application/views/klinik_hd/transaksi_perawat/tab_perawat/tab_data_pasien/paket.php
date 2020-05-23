<div class="portlet light">
	<div class="portlet-body form">
		<?php
			$form_attr = array(
			    "id"            => "form_add_pasien", 
			    "name"          => "form_add_pasien", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "add"
		    );

		    echo form_open(base_url()."master/pasien/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
			
			$flash_form_data  = $this->session->flashdata('form_data');
			$flash_form_error = $this->session->flashdata('form_error');
		?>

			<div class="row">
					 
				<div class="col-md-12">
					<div class="portlet light" id="section-alamat">
						 
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover" id="table_dokumen_pelengkap1">
							<thead>
							<tr role="row" class="heading">
									<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> </th>
									<th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
								 
								</tr>
								</thead>
								<tbody>
								
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

		<?=form_close()?>
	</div>
</div>
 




