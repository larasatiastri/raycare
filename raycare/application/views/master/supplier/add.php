<?php
	$form_attr = array(
	    "id"            => "form_add_supplier", 
	    "name"          => "form_add_supplier", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Supplier", $this->session->userdata("language"))?></span>
		</div>

		<div class="actions">
			<?php $msg = translate("Apakah anda yakin akan membuat data supplier ini?",$this->session->userdata("language"));?>
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?>
			</a>
    		<button type="submit" id="save" class="btn default hidden" >
    			<?=translate("Simpan", $this->session->userdata("language"))?>
    		</button>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="alert alert-danger display-hide">
	        <button class="close" data-close="alert"></button>
	        <?=$form_alert_danger?>
	    </div>
	    <div class="alert alert-success display-hide">
	        <button class="close" data-close="alert"></button>
	        <?=$form_alert_success?>
	    </div>
		<div class="row">
			<div class="col-md-3">
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<span><?=translate("Informasi", $this->session->userdata("language"))?></span>
						</div>
					</div>

					<div class="form-body">
						<div class="form-group">
							<label class="col-md-12"><?=translate("Tipe", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
		                        <div class="radio-list">
		                            <label class="radio-inline">
		                                <div class="radio-inline" style="padding-left:0px !important;">
		                                    <span class="">
		                                        <input type="radio" name="tipe_supplier"  checked value="1" id="ts-dalam-negeri">
		                                    </span>
		                                </div> 

		                                <span>Dalam Negeri</span> 
		                            </label>
		                            <label class="radio-inline">
		                                <div class="radio-inline"  >
		                                    <span class="">
		                                        <input type="radio" name="tipe_supplier"  value="2" id="ts-luar-negeri">
		                                    </span>
		                                </div> 
		                                <span>Luar Negeri</span> 
		                            </label>
		                        </div>
		                    </div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Kode", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<input type="text" id="kode" name="kode" class="form-control" placeholder="<?=translate("Kode", $this->session->userdata("language"))?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<input type="text" id="nama" name="nama" class="form-control" placeholder="<?=translate("Nama", $this->session->userdata("language"))?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Orang Yang Bersangkutan", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<input type="text" id="orang_yang_bersangkutan" name="orang_yang_bersangkutan" class="form-control" placeholder="<?=translate("Orang Yang Bersangkutan", $this->session->userdata("language"))?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Jenis Supplier", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<div class="checkbox-list">
								 	<label class="checkbox-inline">
										<input type="checkbox" id="obat" name="obat" value="1" class="form-control"> PBF  
								 	</label>
								 	<label class="checkbox-inline">
										<input type="checkbox" id="alkes" name="alkes" value="2" class="form-control"> PBAK  
								 	</label>
								 	<label class="checkbox-inline">
										<input type="checkbox" id="lain" name="lain" value="3" class="form-control"> Lain - lain  
								 	</label>
							 	</div> 
							</div>
						</div>

						<div class="hidden" id="div_pbf">
							<div class="form-group">
								<label class="col-md-12"><?=translate("No. Surat Izin", $this->session->userdata("language"))?> :</label>
								
								<div class="col-md-12">
									<input type="text" id="no_surat_izin" name="no_surat_izin" class="form-control" placeholder="<?=translate("No. Surat Izin", $this->session->userdata("language"))?>">
								</div>
							</div>
							<div class="form-group">	
								<label class="col-md-12"><?=translate("Masa Berlaku Hingga", $this->session->userdata("language"))?> :</label>
									
								<div class="col-md-12">
									<div class="input-group date">
										<input type="text" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="<?=date('d M Y')?>" readonly >
										<span class="input-group-btn">
											<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-12"><?=translate("Upload Surat Izin", $this->session->userdata("language"))?> :<span class="required">*</span></label>
								<div class="col-md-12">
									<input type="hidden" name="url" id="url">
									<div id="upload_surat">
										<span class="btn default btn-file">
											<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>		
											<input type="file" class="upl_invoice" name="upl" id="upl_surat" data-url="<?=base_url()?>upload/upload_photo" multiple />
										</span>

									<ul class="ul-img">
									</ul>

									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Nama Apoteker", $this->session->userdata("language"))?> :</label>
								
								<div class="col-md-12">
									<input type="text" id="nama_apoteker" name="nama_apoteker" class="form-control" placeholder="<?=translate("Nama Apoteker", $this->session->userdata("language"))?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("No. SIA", $this->session->userdata("language"))?> :</label>
								
								<div class="col-md-12">
									<input type="text" id="no_sia" name="no_sia" class="form-control" placeholder="<?=translate("No. SIA", $this->session->userdata("language"))?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("No. SIKA", $this->session->userdata("language"))?> :</label>
								
								<div class="col-md-12">
									<input type="text" id="no_sika" name="no_sika" class="form-control" placeholder="<?=translate("No. SIKA", $this->session->userdata("language"))?>">
								</div>
							</div>
							<div class="form-group">	
								<label class="col-md-12"><?=translate("Masa Berlaku SIKA", $this->session->userdata("language"))?> :</label>
									
								<div class="col-md-12">
									<div class="input-group date">
										<input type="text" class="form-control" id="tanggal_kadaluarsa_sika" name="tanggal_kadaluarsa_sika" value="<?=date('d M Y')?>" readonly >
										<span class="input-group-btn">
											<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-12"><?=translate("Upload SIKA", $this->session->userdata("language"))?> :<span class="required">*</span></label>
								<div class="col-md-12">
									<input type="hidden" name="url_sika" id="url_sika">
									<div id="upload_sika">
										<span class="btn default btn-file">
											<span class="fileinput-new"><?=translate('Pilih Foto', $this->session->userdata('language'))?></span>		
											<input type="file" class="upl_invoice" name="upl" id="upl_sika" data-url="<?=base_url()?>upload/upload_photo" multiple />
										</span>

									<ul class="ul-img">
									</ul>

									</div>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Perusahaan Kena Pajak", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<div class="radio-list">
									<label class="radio-inline">
									<input type="radio" name="is_pkp" id="optionspkpya" value="1"> Ya </label>
									<label class="radio-inline">
									<input type="radio" name="is_pkp" id="optionspkptdk" value="0"> Tidak </label>
									
								</div>
							</div>
						</div>
						<div class="form-group hidden" id="div_npwp">
							<label class="col-md-12"><?=translate("NPWP", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<input type="text" id="npwp" name="npwp" class="form-control" placeholder="<?=translate("NPWP", $this->session->userdata("language"))?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12"><?=translate("Perusahaan Jasa (PPH 23)", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<div class="radio-list">
									<label class="radio-inline">
									<input type="radio" name="is_pph" id="optionspphya" value="1"> Ya </label>
									<label class="radio-inline">
									<input type="radio" name="is_pph" id="optionspphtdk" value="0"> Tidak </label>
									
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-12"><?=translate("Rating", $this->session->userdata("language"))?> :</label>
							
							<div class="col-md-12">
								<input id="input-1" class="rating" min="0" max="5" step="1" data-size="xs" id="rating" name="rating" value="5">
							</div>
							
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-9">

					<div class="tabbable-custom nav-justified">
						<ul class="nav nav-tabs nav-justified">
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
							<li>
								<a href="#bank" data-toggle="tab">
								<?=translate('Bank', $this->session->userdata('language'))?> </a>
							</li>
							<li>
								<a href="#item_berdasarkan_kode" data-toggle="tab">
								<?=translate('Item Berdasarkan Kode', $this->session->userdata('language'))?> </a>
							</li>
							<li class="hidden">
								<a href="#item_berdasarkan_detail" data-toggle="tab">
								<?=translate('Item Berdasarkan Detail', $this->session->userdata('language'))?> </a>
							</li>
							<li>
								<a href="#tipe_pembayaran" data-toggle="tab">
								<?=translate('Tipe Pembayaran', $this->session->userdata('language'))?> </a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="telepon">
								<?php include('tab_add_supplier/tab_telepon.php') ?>
							</div>
							<div class="tab-pane" id="alamat">
								<?php include('tab_add_supplier/tab_alamat.php') ?>
							</div>
							<div class="tab-pane" id="email">
								<?php include('tab_add_supplier/tab_email.php') ?>
							</div>
							<div class="tab-pane" id="bank">
								<?php include('tab_add_supplier/tab_bank.php') ?>
							</div>
							<div class="tab-pane" id="item_berdasarkan_kode">
								<?php include('tab_add_supplier/tab_item_berdasarkan_kode.php') ?>
							</div>
							<div class="tab-pane hidden" id="item_berdasarkan_detail">
								<?php include('tab_add_supplier/tab_item_berdasarkan_detail.php') ?>
							</div>
							<div class="tab-pane" id="tipe_pembayaran">
								<?php include('tab_add_supplier/tab_tipe_pembayaran.php') ?>
							</div>
						</div>
					</div>
			</div>
		</div>
		
	</div>	
</div>
<?=form_close()?>

<div class="modal fade" id="modal_alamat" role="basic" aria-hidden="true">
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



