<?php
			$form_attr = array(
			    "id"            => "form_edit_user_level", 
			    "name"          => "form_edit_user_level", 
			    "autocomplete"  => "off", 
			    "class"         => "form-horizontal",
			    "role"			=> "form"
		    );
		    
		    $hidden = array(
		        "command"   => "edit"
		    );

		    echo form_open(base_url()."keuangan/permintaan_biaya/save", $form_attr, $hidden);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
		?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Surat Permintaan Biaya", $this->session->userdata("language"))?></span>
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
                 <label class="control-label col-md-2">
                  <?=translate("Dibuat Oleh", $this->session->userdata("language"))?> :</label>
					 	<div class="col-md-3">
							<label class="control-label col-md-2"><?=translate("Andri", $this->session->userdata("language"))?></label>
						</div>
              	</div>
				 <div class="form-group">
                 <label class="control-label col-md-2">
                  <?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
					 	<div class="col-md-3">
							<?php
								$tanggal = array(
									"id"			=> "tanggal",
									"name"			=> "tanggal",
									"autofocus"		=> true,
									"class"			=> "form-control date-picker",
									"required"		=> true,
									"placeholder"	=> translate("Tanggal", $this->session->userdata("language"))
								);
								echo form_input($tanggal);
							?>
						</div>
					<!-- <div class="col-md-3">
	                  	<div class="input-group date" id="tanggal">
		                    <input type="text" class="form-control" id="tanggal" name="tanggal" readonly >
		                    <span class="input-group-btn">
		                    	<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
		                    </span>
	              		</div>
               	 	</div> -->
              	</div>
				<div class="form-group">
                 <label class="control-label col-md-2">
                  <?=translate("Subjek", $this->session->userdata("language"))?> :</label>
				 	<div class="col-md-3">
						<?php
							$subjek = array(
								"id"			=> "subjek",
								"name"			=> "subjek",
								"autofocus"		=> true,
								"class"			=> "form-control",
								"required"		=> true,
								"placeholder"	=> translate("Subjek", $this->session->userdata("language"))
							);
							echo form_input($subjek);
						?>
					</div>
              	</div>

				<div class="form-group last">
                    <label class="control-label col-md-2"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-9">
                        <textarea class="ckeditor form-control" name="editor1" rows="6"></textarea>
                    </div>
                </div>
			</div>
			<?php $msg = translate("Apakah anda yakin akan membuat Surat Permintaan Biaya ini?",$this->session->userdata("language"));?>
		<div class="form-actions fluid">	
			<div class="col-md-offset-1 col-md-9">
				<a class="btn default" href="javascript:history.go(-1)"><?=translate("Kembali", $this->session->userdata("language"))?></a>
				<a id="confirm_save" class="btn btn-sm btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><?=translate("Simpan", $this->session->userdata("language"))?></a>
	            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
			</div>		
		</div>
		<?=form_close()?>
	</div>


	
	<!-- <div class="portlet-title" style="margin-top:30px;">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Detail User Level", $this->session->userdata("language"))?></span>
		</div>
	</div>
	
	<div class="portlet-body form">
		<div class="form-body">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#akses_user" data-toggle="tab">
					<?=translate('Akses User', $this->session->userdata('language'))?> </a>
				</li>
				<li>
					<a href="#persetujuan" data-toggle="tab">
					<?=translate('Persetujuan', $this->session->userdata('language'))?> </a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="akses_user">
					<?php include('tab_user_level/akses_user.php') ?>
				</div>
				<div class="tab-pane" id="persetujuan">
					<?php include('tab_user_level/persetujuan.php') ?>
				</div>
			</div>
			
		</div>
		
	</div> -->
	
</div>



