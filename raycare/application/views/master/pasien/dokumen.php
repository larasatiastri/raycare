<?php
	$form_attr = array(
	    "id"            => "form_edit_pasien", 
	    "name"          => "form_edit_pasien", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "edit",
        "id"		=> $pk_value
    );

    echo form_open("#", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$msg = translate("Apakah anda yakin akan mengubah penjamin pasien ini?",$this->session->userdata("language"));
?>	
<!-- BEGIN PROFILE SIDEBAR -->
<div class="profile-sidebar" style="width: 250px;">
	<!-- PORTLET MAIN -->
	<div class="portlet light profile-sidebar-portlet" style="padding-left:0px !important;padding-right:0px !important;">
	<div class="patient-padding-picture"></div>
		<div class="form-body">






			<!-- SIDEBAR USERPIC -->
			<input type="hidden" name="tanggal" id="tanggal" value="<?=date('M Y')?>" >
			<input type="hidden" name="url" id="url" value="<?=$form_data['url_photo']?>" >
			<input type="hidden" name="no_member" id="no_member" value="<?=$form_data['no_member']?>" >
			<input type="hidden" name="pasien_id" id="pasien_id" value="<?=$pk_value?>" >
			<div id="upload" class="profile-userpic" style="text-align:center">
			<?php
	
				$url_photo = 'global.png';
				$img_src = config_item('site_img_pasien_temp_dir_copy').'global/global.png';

				if($form_data['url_photo'] != '' && $form_data['url_photo'] != 'global.png' || $form_data['url_photo'] != NULL && $form_data['url_photo'] != 'global.png')
				{
					$url_photo = $form_data['url_photo'];
					$img_src = config_item('site_img_pasien_temp_dir_copy').$form_data['url_photo'];
					if($form_data['url_photo'] != 'global/global.png')
					{
						$img_src = config_item('site_img_pasien_temp_dir_copy').$form_data['no_member'].'/foto/'.$form_data['url_photo'];													
					}
				}
			?>								<!-- <a class="fancybox-button" title="<?=$form_data['url_photo']?>" href="<?=$img_src?>" data-rel="fancybox-button">
				<img src="<?=$img_src?>" alt="Smiley face" class="img-responsive img-thumbnail">
			</a>
-->								<!-- <img src="<?=$img_src?>" class="img-responsive" alt="<?=$form_data['url_photo']?>"> -->
			<ul class="ul-img">
				<li class="working">
					<div class="thumbnail" style="border:0px !important;">
						<a class="fancybox-button" title="<?=$form_data['url_photo']?>" href="<?=$img_src?>" data-rel="fancybox-button">
							<img src="<?=$img_src?>" alt="Smiley face" class="img-thumbnail img-responsive" style="padding:0px;border:0px;">
						</a>
					</div>
				</li>
			</ul>
			
			
		</div>
		<!-- END SIDEBAR USERPIC -->
		</div>
		
		<!-- SIDEBAR USER TITLE -->
		<div class="profile-usertitle">
			<div class="profile-usertitle-name">
				 <?=$form_data["nama"]?>								
			</div>
			<div class="profile-usertitle-job">
					<?=$form_data['no_member']?>
			</div>
		</div>
		<!-- END SIDEBAR USER TITLE -->
		
		<!-- SIDEBAR MENU -->
		<div class="profile-usermenu">
			<ul class="nav">
				<li class="">
					<a href="<?=base_url()?>master/pasien/edit/<?=$pk_value?>">
					<i class="icon-user"></i>
					Profil </a>
				</li>
				<li class="">
					<a href="<?=base_url()?>master/pasien/kelayakan_anggota/<?=$pk_value?>">
					<i class="icon-briefcase"></i>
					Kelayakan Anggota </a>
				</li>
				<li class="">
					<a href="<?=base_url()?>master/pasien/penjamin_pasien/<?=$pk_value?>">
					<i class="fa fa-list-alt"></i>
					Penjamin </a>
				</li>
				<li class="">
					<a href="<?=base_url()?>master/pasien/penanggung/<?=$pk_value?>">
					<i class="icon-check"></i>
					Penanggung </a>
				</li>
				<li class="active">
					<a href="<?=base_url()?>master/pasien/dokumen_pasien/<?=$pk_value?>">
					<i class="icon-docs"></i>
					Dokumen </a>
				</li>

				<li>
					<a href="<?=base_url()?>master/pasien/info_lain_pasien/<?=$pk_value?>">
					<i class="icon-info"></i>
					Info Lain </a>
				</li>				
			</ul>
		</div>
		<!-- END MENU -->
	</div>

	<!-- END PORTLET MAIN -->
</div>
<!-- END BEGIN PROFILE SIDEBAR -->
<!-- BEGIN PROFILE CONTENT -->
<div class="profile-content">
	<div class="portlet">
		<div class="portlet light" id="section-hubungan-pasien">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-docs font-blue-sharp"></i>
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Dokumen Pasien", $this->session->userdata("language"))?></span>
				</div>
				
			</div>
			<div class="portlet-body form">
				
					<table class="table table-striped table-hover" id="table_dokumen_pasien">
						<thead>
							<tr role="row">
								<th class="text-center"><?=translate("Nama Dokumen", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Tanggal Kadaluarsa", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Jenis", $this->session->userdata("language"))?> </th>
								 <th class="text-center" width="1%"><?=translate("Status", $this->session->userdata("language"))?> </th>
						 		<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
								
				<div class="form-actions" style="padding:10px 0px !important;">
					<a id="adddoc" href="<?=base_url()?>master/pasien/add_dokumen/<?=$pk_value?>" data-target="#ajax_notes" data-toggle="modal" 	class="btn btn-primary">
					    <i class="fa fa-plus"></i>
					    <?=translate("Tambah Dokumen", $this->session->userdata("language"))?>

					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="ajax_notes" role="basic" aria-hidden="true" style="display:none">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">               
        </div>
    </div>
</div>
<div class="modal fade" id="ajax_notes3" role="basic" aria-hidden="true" style="display:none">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">               
        </div>
    </div>
</div>
<?=form_close()?>
