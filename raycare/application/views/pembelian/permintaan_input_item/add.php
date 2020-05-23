<?php
	$form_attr = array(
	    "id"            => "form_add_item", 
	    "name"          => "form_add_item", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add",
        "id_detail"	=> $pk_value,
        "id_permintaan"	=> $data_item['order_permintaan_barang_id'],
        "jumlah_minta"	=> $data_item['jumlah'],
        "created_by"	=> $data_item['created_by']
    );

    echo form_open(base_url()."pembelian/permintaan_input_item/save", $form_attr, $hidden);
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
		<?php $msg = translate("Apakah anda yakin akan membuat item ini?",$this->session->userdata("language"));?>
		<?php $msg_proses = translate("Sedang diproses",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate("Kembali", $this->session->userdata("language"))?>
			</a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-proses="<?=$msg_proses?>" data-toggle="modal">
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
		    <div class="row">
		    	<div class="col-md-12">
			    	<div class="portlet light bordered">
		    			<div class="portlet-title">
		    				<div class="caption">
		    					<?=translate("Permintaan Item", $this->session->userdata("language"))?>
		    				</div><!-- akhir dari caption -->
		    			</div><!-- akhir dari portlet-title -->
	    				<div class="portlet-body">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="table_add_item_titipan">
                                <thead>
                                    <tr role="row">
                                        <th width="25%"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
                                        <th width="10%"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
                                        <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                  	<?php
                                  		if($data_item != '')
                                  		{
                              				$link_pdf = '';
								            $data_pdf = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $data_item['id'], 'tipe' => 1 ), true);

								            $link_img = '';
								            $data_img = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $data_item['id'], 'tipe' => 2 ), true);


								            if(count($data_pdf))
								            {
								                $link_pdf = '<a target="_blank" href="'.base_url().'assets/mb/pages/pembelian/permintaan_po/doc/'.str_replace(' ', '_', strtolower($data_item['nama'])).'/'.$data_pdf->url.'" class="btn grey-cascade unggah-file" name="item2[1][file]" title="Lihat File"><i class="fa fa-file"></i></a>';
								            }
								            if(count($data_img))
								            {
								            	$link_img = '<a data-toggle="modal" data-target="#popup_modal_item" href="'.base_url().'pembelian/permintaan_input_item/lihat_gambar/'.$data_item['id'].'" class="btn blue-chambray unggah-gambar" name="item2[1][gambar]" title="Lihat Gambar"><i class="fa fa-image"></i></a>';
								            }

								            $action     = $link_pdf.$link_img;

                              				echo '
                              				<tr>
                              					<td>'.$data_item['nama'].'</td>
                              					<td>'.$data_item['satuan'].'</td>
                              					<td>'.$action.'</td>
                              				</tr>';			
                                  		}
                                  	?>
                                    
                                </tbody>
                            </table>
                        </div>
			    	</div>
		    	</div>
		    	<div class="col-md-3">
		    		<div class="portlet light bordered">
		    			<div class="portlet-title">
		    				<div class="caption">
		    					<?=translate("Informasi", $this->session->userdata("language"))?>
		    				</div><!-- akhir dari caption -->
		    			</div><!-- akhir dari portlet-title -->
			    		<div class="form-group">
							<label class="col-md-12"><?=translate("Kategori", $this->session->userdata("language"))?> :</label>
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
							<label class="col-md-12"><?=translate("Tipe Akun", $this->session->userdata("language"))?> :</label>
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
							<label class="col-md-12"><?=translate("Sub Kategori", $this->session->userdata("language"))?> :</label>
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
							<label class="col-md-12"><?=translate("Kode", $this->session->userdata("language"))?> :</label>
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
							<label class="col-md-12"><?=translate("Nama", $this->session->userdata("language"))?> :</label>
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
							<label class="col-md-12"><?=translate("Ketentuan", $this->session->userdata("language"))?> :</label>
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
							<label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
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
	</div>	
</div><!-- akhir dari portlet -->
<?=form_close()?>
<div class="modal fade bs-modal-sm" id="popup_modal_item" role="basic" aria-hidden="true" style="">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-sm">
       <div class="modal-content">

       </div>
   </div>
</div>

<div class="modal fade bs-modal-sm" id="popup_modal_item_file" role="basic" aria-hidden="true" style="">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-sm">
       <div class="modal-content">
       
       </div>
   </div>
</div>







