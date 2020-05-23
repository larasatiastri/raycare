<?php
	$form_attr = array(
	    "id"            => "form_edit_user_level", 
	    "name"          => "form_edit_user_level", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
	);

	$hidden = array(
	    "command"   => "edit",
	    "id"		=> $pk_value,
	);

	echo form_open(base_url()."master/user_level/save", $form_attr, $hidden);
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$records = $form_data_persetujuan->result_array();

    $modified_by = $this->user_m->get_by(array('id' => $form_data['modified_by']));
    // die_dump($this->db->last_query()); 


	// die_dump($modified_by);
	
?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-pencil font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Edit User Level", $this->session->userdata("language"))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat user level ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"> <i class="fa fa-chevron-left"></i> <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"> <i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
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
			    <div class="row">
					<div class="col-md-6">
						<div class="portlet light bordered">
							<div class="portlet-title">
								<div class="caption">
									<?=translate("Informasi", $this->session->userdata("language"))?>
								</div>
							</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Nama", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
									<div class="col-md-8">
										<?php
											$nama_user = array(
												"id"			=> "nama_user",
												"name"			=> "nama_user",
												"autofocus"			=> true,
												"class"			=> "form-control", 
												"placeholder"	=> translate("Nama User", $this->session->userdata("language")), 
												"required"		=> "required",
												"value"			=> $form_data['nama']
											);
											echo form_input($nama_user);
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Cabang", $this->session->userdata("language"))?> <span class="required">*</span>:</label>
									<div class="col-md-8">
										<?php
											$cabang = $this->cabang_m->get_by(array('is_active' => 1));

											$cabang_option = array();
											foreach ($cabang as $key => $cbg) {
												$cabang_option[$cbg->id] = $cbg->nama;
											}
											echo form_dropdown('cabang_id', $cabang_option, $form_data['cabang_id'], 'id="cabang_id" class="form-control" required');
										?>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Dashboard Url", $this->session->userdata("language"))?> :</label>
									<div class="col-md-8">
										<?php
											$dashboard_url = array(
												"id"			=> "dashboard_url",
												"name"			=> "dashboard_url",
												"autofocus"		=> true,
												"class"			=> "form-control", 
												"placeholder"	=> translate("Dashboard Url", $this->session->userdata("language")), 
												"required"		=> "required",
												"value"			=> $form_data['dashboard_url']
											);
											echo form_input($dashboard_url);
										?>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Create By", $this->session->userdata("language"))?>:</label>
									<div class="col-md-8">
										<?php $user_create = $this->user_m->get($form_data['created_by']) ?>
										<label class="control-label"> <?=$user_create->nama?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Create Date", $this->session->userdata("language"))?> :</label>
									<div class="col-md-8">
										<label class="control-label"><?=$form_data['created_date']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Modified By", $this->session->userdata("language"))?> :</label>
									<div class="col-md-8">
										<?php 
											if ($form_data['modified_by']) 
											{
												$user_modified = $this->user_m->get($form_data['modified_by']);
												$user_modified = object_to_array($user_modified);
											}
											else {
												$user_modified['nama'] = $form_data['modified_by'];
											}
										?>
										<label class="control-label"><?=$user_modified['nama']?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4"><?=translate("Modified Date", $this->session->userdata("language"))?> :</label>
									<div class="col-md-8">
										<label class="control-label"><?=$form_data['modified_date']?></label>
										<input type="hidden" name="modified_date" value="<?=$form_data['modified_date']?>">
										<a target="_blank" id="open_new_tab" class="btn btn-sm btn-primary hidden" href="<?=base_url()?>master/user_level/edit/<?=$pk_value?>" ><?=translate("Open", $this->session->userdata("language"))?></a>
									</div>
								</div>
								
							
						</div>
					</div>
					<div class="col-md-6">
						<div class="portlet light bordered">
							<div class="portlet-title tabbable-line">
								<div class="caption">
									<?=translate("Persetujuan", $this->session->userdata("language"))?>
								</div>
				                <ul class="nav nav-tabs">
				                    <li  class="active">
				                        <a href="#persetujuan" data-toggle="tab">
				                            <?=translate('Permintaan Barang', $this->session->userdata('language'))?> </a>
				                    </li>
				                    <li>
				                        <a href="#biaya" data-toggle="tab">
				                            <?=translate('Biaya', $this->session->userdata('language'))?> </a>
				                    </li>
				                    <li>
				                        <a href="#item" data-toggle="tab">
				                            <?=translate('Item', $this->session->userdata('language'))?> </a>
				                    </li>
				                    <li>
				                        <a href="#supplier" data-toggle="tab">
				                            <?=translate('Supplier', $this->session->userdata('language'))?> </a>
				                    </li>
				                    <li>
				                        <a href="#customer" data-toggle="tab">
				                            <?=translate('Customer', $this->session->userdata('language'))?> </a>
				                    </li>
				                </ul>
							</div>
							<div class="portlet-body form">
								<div class="form-body">
									<div class="form-body">
						            <div class="tab-content">
						                <div class="tab-pane active" id="persetujuan" >
						                    <?php include('tab_user_level/persetujuan.php') ?>
						                </div>
						                <div class="tab-pane" id="biaya" >
						                    <?php include('tab_user_level/persetujuan_biaya.php') ?>
						                </div>
						                <div class="tab-pane " id="item" >
						                	 <?php include('tab_user_level/persetujuan_item.php')	 ?> 
						                </div>
						                <div class="tab-pane " id="supplier" >
						                	 <?php include('tab_user_level/persetujuan_supplier.php') ?>
						                </div>
						                <div class="tab-pane " id="customer" >
						                	 <?php include('tab_user_level/persetujuan_customer.php') ?>
						                </div>
						            </div>
						        </div>
								<!-- 	<?php $this->load->view('master/user_level/tab_user_level/persetujuan.php') ?> -->
									
								</div>
								
							</div>
							
						</div>
					</div>
				</div>

				
			</div>
	</div>
	
	
	
</div>
<?=form_close()?>


