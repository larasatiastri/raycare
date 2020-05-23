<?php
	$form_attr = array(
	    "id"            => "form_edit_master_akun", 
	    "name"          => "form_edit_master_akun", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "edit",
        "id"		=> $form_data['id']
    );

    echo form_open(base_url()."akunting/master_akun/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	$msg = translate("Apakah anda yakin akan mengubah data akun  ini?",$this->session->userdata("language"));
	$msg_proses = translate("Sedang diproses",$this->session->userdata("language"));
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-edit font-blue-sharp"> </i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Edit Akun", $this->session->userdata("language"))?></span>
		</div>
		
		<div class="actions">	
			<a class="btn default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
			<a id="confirm_save" class="btn btn-primary" data-confirm="<?=$msg?>">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?>
			</a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>	
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
			<div class="form-group">
				<label class="control-label col-md-2"><?=translate("Kategori", $this->session->userdata("language"))?> :</label>
				
				<div class="col-md-6">
					<?php
						
						$tipe_option = array(
						    '' => translate('Pilih..', $this->session->userdata('language')),
						    '1'	=> "Harta Lancar",
						    '2'	=> "Harta Tidak Lancar",
						    '3'	=> "Utang Lancar",
						    '4'	=> "Utang Jangka Panjang",
						    '5'	=> "Modal",
						    '6'	=> "Laba",
						    '7'	=> "Pendapatan",
						    '8'	=> "Harga Pokok Penjualan",
						    '9'	=> "Beban Operasi",
						    '10'	=> "Pendapatan Lain - lain",
						    '11'	=> "Beban Lain - lain",
						);

						echo form_dropdown('akun_tipe', $tipe_option, $form_data['akun_tipe'], "id=\"akun_tipe\" class=\"form-control\" required disabled");
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2"><?=translate("Parent", $this->session->userdata("language"))?> :</label>
				
				<div class="col-md-6">
					<?php
						$akun = $this->akun_m->get_by(array('parent' => '0', 'is_suspended' => 0));
						// die_dump($cabang?);
						$parent_option = array(
						    '' => translate('Pilih..', $this->session->userdata('language')),
						     0  => 'Tanpa Parent'
						);

						foreach ($akun as $data)
						{
						    $parent_option[$data->id] = $data->no_akun.' - '.$data->nama;
						}
						echo form_dropdown('parent', $parent_option, $form_data['parent'], "id=\"parent\" class=\"form-control\" required disabled");
					?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-2"><?=translate("No. Akun", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-3">
					<?php
						$no_akun = array(
							"id"			=> "no_akun",
							"name"			=> "no_akun",
							"class"			=> "form-control", 
							"placeholder"	=> translate("No. Akun", $this->session->userdata("language")), 
							"required"		=> "required",
							"value"			=> $form_data['no_akun']
						);
						echo form_input($no_akun);
					?>
				</div>
			</div>	

			<div class="form-group">
				<label class="control-label col-md-2"><?=translate("Nama", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
				<div class="col-md-6">
					<?php
						$nama = array(
							"id"			=> "nama",
							"name"			=> "nama",
							"class"			=> "form-control", 
							"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
							"required"		=> "required",
							"value"			=> $form_data['nama']
						);
						echo form_input($nama);
					?>
				</div>
			</div>
			

		</div>
		
	</div>
</div>
<?=form_close()?>



