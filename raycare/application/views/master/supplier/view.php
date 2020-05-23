<?php
	$form_attr = array(
	    "id"            => "form_view_supplier", 
	    "name"          => "form_view_supplier", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
		"command"                    => "view",
		"id"                         => $pk_value,
		"akun_hutang_jurnal_akun_id" => $form_data['akun_hutang_jurnal_akun_id'],
		"kode" => $form_data['kode'],
    );

    echo form_open(base_url()."master/supplier/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-user-follow font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("View Supplier", $this->session->userdata("language"))?></span>
		</div>

		<div class="actions">
			<?php $msg = translate("Apakah anda yakin akan membuat data supplier ini?",$this->session->userdata("language"));?>
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
			<a id="confirm_save" class="btn btn-circle btn-primary hidden" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?>
			</a>
    		<button type="submit" id="save" class="btn default hidden" >
    			<?=translate("Simpan", $this->session->userdata("language"))?>
    		</button>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="row">
			<div class="col-md-6">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<span><?=translate("Informasi", $this->session->userdata("language"))?></span>
						</div>
					</div>

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
							<label class="control-label col-md-4"><?=translate("Tipe Supplier", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-8">
								<?php
									$tipe_supplier = 'Luar Negeri';
									if ($form_data['tipe'] == '1') {
										$tipe_supplier = 'Dalam Negeri';
									}
								?>
								<label class="control-label"><?=$tipe_supplier?></label>
		                    </div>
							
						</div>

						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-8">
								<label class="control-label"><?=$form_data['nama']?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Orang Yang Bersangkutan", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-8">
								<label class="control-label"><?=$form_data['orang_yang_bersangkutan']?></label>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Rating", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-8">
								<?php
									$star = intval($form_data['rating']) / 2;
								?>
								<input id="input-1" class="rating" min="0" disabled max="5" step="1" data-size="xs" id="rating" name="rating" value="<?=$star?>">
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="portlet light bordered">
					<div class="portlet-title tabbable-line">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#telepon" data-toggle="tab">
								<?=translate('Telepon', $this->session->userdata('language'))?> </a>
							</li>
							<li>
								<a href="#alamat" data-toggle="tab">
								<?=translate('Alamat', $this->session->userdata('language'))?> </a>
							</li>
							<li>
								<a href="#email" data-toggle="tab">
								<?=translate('Email', $this->session->userdata('language'))?> </a>
							</li>
						</ul>
					</div>
					<div class="portlet-body">
						<div class="tab-content">
							<div class="tab-pane active" id="telepon">
								<?php include('tab_view_supplier/tab_telepon.php') ?>
							</div>
							<div class="tab-pane" id="alamat">
								<?php include('tab_view_supplier/tab_alamat.php') ?>
							</div>
							<div class="tab-pane" id="email">
								<?php include('tab_view_supplier/tab_email.php') ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>	
</div>
<?=form_close()?>





