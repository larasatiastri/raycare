<?php
	$form_attr = array(
	    // "id"            => "form_add_pasien", 
	    // "name"          => "form_add_pasien", 
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
<input type="hidden" id="flag2" name="flag2">
<div class="tabbable-custom nav-justified">
	<ul class="nav nav-tabs nav-justified">
		<li  class="active" id="li1">
			<a href="#tabel" data-toggle="tab"><?=translate('Tabel', $this->session->userdata('language'))?></a>
		</li>
		<li id="li2">
			<a href="#page" data-toggle="tab" id="page2"><?=translate('Page', $this->session->userdata('language'))?></a>
		</li>
	</ul>
		<div class="tab-content">
		<div class="tab-pane active" id="tabel" >
			<?php include('tab_data_pasien/tabel.php') ?>
		</div>
		<div class="tab-pane" id="page">
			<?php include('tab_data_pasien/page.php') ?>
		</div>
	</div>
</div>

<?=form_close();?>