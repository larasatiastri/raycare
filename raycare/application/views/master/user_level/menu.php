<?php
	$form_attr = array(
	    "id"            => "form_menu_user_level", 
	    "name"          => "form_menu_user_level", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add_menu",
        "id"		=> $pk_value
    );

    echo form_open(base_url()."master/user_level/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-list font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("User Level Menu", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>		
			<a class="btn btn-circle btn-default" id="refresh" name="refresh">
				<i class="fa fa-refresh"></i>
				<?=translate("Refresh", $this->session->userdata("language"))?>
			</a>		
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
			    	<div class="col-md-12">
			    		<div class="portlet light bordered">
			    			<div class="portlet-title">
			    				<div class="caption">
			    					<?=translate("Informasi", $this->session->userdata("language"))?>
			    				</div>
			    			</div>
			    			<div class="form-group">
								<label class="control-label col-md-4"><?=translate("User Level", $this->session->userdata("language"))?> :</label>
								<div class="col-md-8">
									<label class="control-label"><?=$form_data['nama']?></label>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Login Dari Cabang", $this->session->userdata("language"))?> :</label>
								<div class="col-md-8">
									<?php

										$cabang = $this->cabang_m->get_by(array('is_active' => 1));

										$option_cabang = array(
											''	=> translate('Pilih', $this->session->userdata('language')).'...'
										);

										foreach ($cabang as $cabang) 
										{
											$option_cabang[$cabang->id] = $cabang->nama;
										}

										echo form_dropdown('cabang_id', $option_cabang, $this->session->userdata('cabang_id'), 'id="cabang_id" class="form-control" required="required"');

									?>
								</div>
							</div>
			    		</div>
			    		
			    	</div><!-- AKHIR DARI COL-MD-6 -->
					<div class="col-md-12">
						<div class="portlet light bordered">
							<div class="portlet-title tabbable-line">
								<div class="caption">
									<?=translate("Setup", $this->session->userdata("language"))?>
								</div><!-- AKHIR DARI CAPTION -->
								<ul class="nav nav-tabs">
				                    <li class="active">
				                        <a href="#menu_all" data-toggle="tab">
				                            <?=translate('Menu User Level', $this->session->userdata('language'))?> </a>
				                    </li>
				                    <li >
				                        <a href="#menu_parent" data-toggle="tab">
				                            <?=translate('Menu Utama', $this->session->userdata('language'))?> </a>
				                    </li>
				                    <li>
				                        <a href="#menu_anak" data-toggle="tab">
				                            <?=translate('Sub Menu', $this->session->userdata('language'))?> </a>
				                    </li>
				                </ul><!-- AKHIR DARI NAV -->
							</div><!-- AKHIR DARI PORTLET-TITLE -->
							<div class="tab-content">
				                <div class="tab-pane active" id="menu_all" >
				                	<?php include('tab_menu/menu_all.php') ?>
				                </div>
				                <div class="tab-pane " id="menu_parent" >
				                    <?php include('tab_menu/menu_induk.php') ?>
				                </div>
				                <div class="tab-pane " id="menu_anak" >
				                	<?php include('tab_menu/sub_menu.php') ?>
				                </div> 
				           </div><!-- AKHIR DARI TAB-CONTENT -->
						</div><!-- AKHIR DARI PORTLET -->
					</div><!-- AKHIR DARI COL-MD-6 -->
			    </div><!--  AKHIR DARI ROW -->			
			</div><!-- AKHIR DARI FORM-BODY -->
	</div><!-- AKHIR DARI PORTLET-BODY -->
	
<div class="modal fade" id="modal_sub_menu" role="basic" aria-hidden="true">
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

<div class="modal fade" id="modal_edit_menu" role="basic" aria-hidden="true">
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

<div class="modal fade" id="modal_edit_parent" role="basic" aria-hidden="true">
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



