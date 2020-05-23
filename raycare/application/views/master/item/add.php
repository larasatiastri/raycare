<?php
	$form_attr = array(
	    "id"            => "form_add_item", 
	    "name"          => "form_add_item", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."master/item/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$cabang_id = $this->session->userdata('cabang_id');

	$data_cabang = $this->cabang_m->get_by(array('id' => $cabang_id));
	
	$tipe_cabang = object_to_array($data_cabang);

	$check_of_tipe ='';
	if ($tipe_cabang[0]['tipe'] == '1') {
		$check_of_tipe = '';
	}else{
		$check_of_tipe = 'hidden';
	}
?>


<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Item", $this->session->userdata("language"))?></span>
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
		    	<div class="col-md-3">
		    		<div class="portlet light bordered">
		    			<div class="portlet-title">
		    				<div class="caption">
		    					<?=translate("Informasi", $this->session->userdata("language"))?>
		    				</div><!-- akhir dari caption -->
		    			</div><!-- akhir dari portlet-title -->
			    		<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Kategori", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<?php
									
									$kategori = $this->item_kategori_m->get_by(array('is_active' => '1'));
									// die_dump($this->db->last_query());
									$kategori_option = array(
									    '' => translate('Pilih Kategori', $this->session->userdata('language'))
									);

									foreach ($kategori as $data)
									{
									    $kategori_option[$data->id] = $data->nama;
									}
									echo form_dropdown('kategori', $kategori_option, '', "id=\"kategori\" class=\"form-control select2me\"");
								?>
							</div>
						</div>
			    		<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Tipe Akun", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<label class="control-label" id="tipe_akun" style="text-align:left !important;">Tipe Akun</label>
								<?php
									$tipe_akun = array(
										"id"			=> "tipe_akun",
										"name"			=> "tipe_akun",
										"autofocus"			=> true,
										"class"			=> "form-control hidden", 
										"placeholder"	=> translate("Tipe Akun", $this->session->userdata("language")), 
										"value"			=> $flash_form_data['tipe_akun'],
										"help"			=> $flash_form_data['tipe_akun'],
									);
									echo form_input($tipe_akun);
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Sub Kategori", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<?php
									
									$sub_kategori = $this->item_sub_kategori_m->get_by(array('is_active' => '1'));
									// die(dump($this->db->last_query()));
									$sub_kategori_option = array(
									    '' => translate('Pilih Sub Kategori', $this->session->userdata('language'))
									);

									echo form_dropdown('sub_kategori', $sub_kategori_option, '', "id=\"sub_kategori\" class=\"form-control select2me\"");
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Kode", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<?php
									$kode = array(
										"id"			=> "kode",
										"name"			=> "kode",
										"autofocus"			=> true,
										"class"			=> "form-control", 
										"placeholder"	=> translate("Kode", $this->session->userdata("language")), 
										"value"			=> $flash_form_data['kode'],
										"help"			=> $flash_form_data['kode'],
									);
									echo form_input($kode);
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<?php
									$nama = array(
										"id"			=> "nama",
										"name"			=> "nama",
										"autofocus"			=> true,
										"class"			=> "form-control", 
										"placeholder"	=> translate("Nama", $this->session->userdata("language")), 
										"value"			=> $flash_form_data['nama'],
										"help"			=> $flash_form_data['nama'],
									);
									echo form_input($nama);
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Ketentuan", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<div class="checkbox-list">
									<label>
										<input type="checkbox" id="discontinue" name="discontinue"><?=translate("Discontinue", $this->session->userdata("language"))?>
									</label>
									<label>
										<input type="checkbox" id="is_sale" name="is_sale"><?=translate("Gunakan Item Ini Untuk di Jual", $this->session->userdata("language"))?>
									</label>
									<label>
										<input type="checkbox" id="is_identitas" name="is_identitas"><?=translate("Gunakan Identitas Item", $this->session->userdata("language"))?>
									</label>
								</div>
							</div>
						</div>
			    		
			    		<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<?php
									$keterangan = array(
										"id"			=> "keterangan",
										"name"			=> "keterangan",
										"rows"			=> 6,
										"autofocus"			=> true,
										"class"			=> "form-control", 
										"placeholder"	=> translate("Keterangan", $this->session->userdata("language")), 
										"value"			=> $flash_form_data['keterangan'],
										"help"			=> $flash_form_data['keterangan'],
									);
									echo form_textarea($keterangan);
								?>
							</div>
						</div>
		    		</div><!-- akhir dari portlet -->
		    	</div><!-- akhir dari col-md-6 -->
		    	<div class="col-md-9">
		    		<div class="portlet light bordered">
						<div class="portlet-title tabbable-line">
							<!-- <div class="caption">
								<?=translate('Detail', $this->session->userdata('language'))?>
							</div> -->
							<ul class="nav nav-tabs">
								<li class="active">
									<a href="#satuan" data-toggle="tab">
									<?=translate('Satuan', $this->session->userdata('language'))?> </a>
								</li>
								<li class="penjualan hidden">
									<a href="#penjualan" data-toggle="tab">
									<?=translate('Penjualan', $this->session->userdata('language'))?> </a>
								</li>
								<li>
									<a href="#pembelian" data-toggle="tab">
									<?=translate('Pembelian', $this->session->userdata('language'))?> </a>
								</li>
								<li>
									<a href="#spesifikasi" data-toggle="tab">
									<?=translate('Spesifikasi', $this->session->userdata('language'))?> </a>
								</li>
								<li>
									<a href="#gambar" data-toggle="tab">
									<?=translate('Gambar', $this->session->userdata('language'))?> </a>
								</li>
								<li class="identitas hidden">
									<a href="#identitas" data-toggle="tab">
									<?=translate('Identitas', $this->session->userdata('language'))?> </a>
								</li>
								<li class="<?=$check_of_tipe?>">
									<a href="#penjamin" data-toggle="tab">
									<?=translate('Penjamin', $this->session->userdata('language'))?> </a>
								</li>
								<li>
									<a href="#pabrik" data-toggle="tab">
									<?=translate('Pabrikan', $this->session->userdata('language'))?> </a>
								</li>

							</ul>
						</div><!-- akhir dari portlet-title -->
						<div class="portlet-body">
							<div class="tab-content">
								<div class="tab-pane active" id="satuan">
									<?php include('tab_item_detail/satuan.php') ?>
								</div>
								<div class="tab-pane" id="penjualan">
									<?php include('tab_item_detail/penjualan.php') ?>
								</div>
								<div class="tab-pane" id="pembelian">
									<?php include('tab_item_detail/pembelian.php') ?>
								</div>
								<div class="tab-pane" id="spesifikasi">
									<?php include('tab_item_detail/spesifikasi.php') ?>
								</div>
								<div class="tab-pane" id="gambar">
									<?php include('tab_item_detail/gambar.php') ?>
								</div>
								<div class="tab-pane" id="identitas">
									<?php include('tab_item_detail/identitas.php') ?>
								</div>
								<div class="tab-pane" id="penjamin">
									<?php include('tab_item_detail/penjamin.php') ?>
								</div>
								<div class="tab-pane" id="pabrik">
									<?php include('tab_item_detail/pabrik.php') ?>
								</div>
							</div>
						</div><!-- akhir dari portlet-title -->
					</div><!-- akhir dari portlet -->
		    	</div><!-- akhir dari col-md-6 -->
			</div><!-- akhir dari row -->
		<?php $msg = translate("Apakah anda yakin akan membuat item ini?",$this->session->userdata("language"));?>
		<?php $msg_proses = translate("Sedang diproses",$this->session->userdata("language"));?>
		<div class="form-actions right">	
			<a class="btn default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
			<a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-proses="<?=$msg_proses?>" data-toggle="modal">
				<i class="fa fa-check"></i>
				<?=translate("Simpan", $this->session->userdata("language"))?>
			</a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>	
		</div>
	</div>	
</div><!-- akhir dari portlet -->
<?=form_close()?>








