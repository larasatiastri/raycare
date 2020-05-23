<?php
	$form_attr = array(
	    "id"            => "form_index_so_online", 
	    "name"          => "form_index_so_online", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."gudang/stok_opname_online/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-sort-amount-asc font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Stok Opname Online", $this->session->userdata("language"))?></span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Filter", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-12"><?=translate("Gudang", $this->session->userdata("language"))?> :</label>
										<div class="col-md-12">
											<?php
												$user = $this->session->userdata('user_id');
												$cek = $this->stok_opname_online_set_m->get_by(array('user_id' => $user, 'is_set'=> 1));
												$data_cek = object_to_array($cek);

												$cek_so = $this->stok_opname_online_m->get_by(array('user_id' => $user, 'status'=> 1), true);
												// die_dump($gudang);
												$gudang_id =  $this->gudang_m->get_by(array('is_active' => 1));
												$gudang = object_to_array($gudang_id);
												// die_dump($gudang);

												$disabled = '';
												$hidden = '';
												$id_gudang = $gudang[0]['id'];
												$id_kategori = '';
												$id_so = '';
												$id_sub_kategori = '';
												$hide_btn_set = '';
												$hide_btn_unset = 'hidden';
												if($data_cek)
												{
													$disabled = 'disabled';
													$hidden = 'hidden';
													$id_gudang = $data_cek[0]['gudang_id'];
													$id_kategori = $data_cek[0]['item_kategori_id'];
													$id_sub_kategori = $data_cek[0]['sub_kategori_id'];
													$hide_btn_set = 'hidden';
													$hide_btn_unset = '';
												}
												if($cek_so){
													$id_so = $cek_so->id;
												}
												
												foreach ($gudang as $data_gudang) {
													$sub_gudang[$data_gudang['id']] = $data_gudang['nama'];
												}
												//die_dump($alamat_sub_option);
												
												echo form_dropdown('sub_gudang', $sub_gudang, $id_gudang, "id=\"tipe_gudang\" class=\"form-control input-sx warehouse\" $disabled");
											?>
											<input type="hidden" id="so_id" name="so_id" value="<?=$id_so?>">
											<input type="hidden" id="set_gudang" name="set_gudang" value="<?=$id_gudang?>">
											<input type="hidden" id="set_kategori" name="set_kategori" value="<?=$id_kategori?>">
											<input type="hidden" id="set_sub_kategori" name="set_sub_kategori" value="<?=$id_sub_kategori?>">
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-12"><?=translate("Kategori", $this->session->userdata("language"))?> :</label>
										<div class="col-md-12">
											<?php
												$kategori_id =  $this->item_kategori_m->get_by(array('is_active' => 1));
												$kategori = object_to_array($kategori_id);
												// die_dump($gudang);
												$sub_kategori = array(
													'' => "Pilih.."
												);
												foreach ($kategori as $data_kategori) {
													$sub_kategori[$data_kategori['id']] = $data_kategori['nama'];
												}
												//die_dump($alamat_sub_option);
												
												echo form_dropdown('item_kategori', $sub_kategori, $id_kategori, "id=\"tipe_kategori\" class=\"form-control input-sx warehouse\" $disabled");
											?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-12"><?=translate("Sub Kategori", $this->session->userdata("language"))?> :</label>
										<div class="col-md-12">
											<?php
												$sub_option = array(
													'' => "Pilih.."
												);

												if($data_cek)
												{
													if($data_cek[0]['sub_kategori_id'] != 0){
														$kategori_sub = $this->item_sub_kategori_m->get($data_cek[0]['sub_kategori_id']);
														$data_kategori_sub = object_to_array($kategori_sub);
														// die_dump($data_kategori_sub);
														$sub_option = array(
														'' => $data_kategori_sub['nama']
														);
													}
													
												}
												//die_dump($alamat_sub_option);
												
												echo form_dropdown('item_sub_kategori', $sub_option, $id_sub_kategori, "id=\"tipe_sub_kategori\" class=\"form-control input-sx warehouse\" $disabled");
											?>
										</div>
									</div>
								</div>
								<div class="col-md-8">
									<div class="form-group">
										<label class="col-md-12"><?=translate("Item", $this->session->userdata("language"))?> :</label>
										<div class="col-md-12">
											<div class="input-group">
											<?php
												$nama_item = array(
													"id"			=> "nama_item",
													"name"			=> "nama_item",
													"autofocus"			=> true,
													"class"			=> "form-control", 
													"disabled"		=> true
												);

												$id_item = array(
													"id"			=> "id_item",
													"name"			=> "id_item",
													"autofocus"			=> true,
													"class"			=> "form-control hidden",
												);
												echo form_input($nama_item);
												echo form_input($id_item);
											?>
											<span class="input-group-btn">
												<a class="btn btn-primary" id ="pilih-item" <?=$disabled?> title="<?=translate('Pilih Item', $this->session->userdata('language'))?>">
													<i class="fa fa-search"></i>
												</a>
											</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<!-- <input type="text" class="form-control" readonly="readonly" style="background-color: transparent;border: 0px solid;"> -->
										<label class="col-md-12"><?=translate("Set", $this->session->userdata("language"))?> :</label>

										<div class="col-md-12">
											<a class="btn btn-primary <?=$hide_btn_set?> btn-block" id="set" title="<?=translate('Set', $this->session->userdata('language'))?>"><i class="fa fa-sort-amount-asc"></i> <?=translate("Set", $this->session->userdata("language"))?>
											</a>
										</div>
										<div class="col-md-12">
											<a class="btn btn-primary  hidden btn-block" id="unset" title="<?=translate('Simpan', $this->session->userdata('language'))?>"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?>
											</a>
										</div>
										<div class="col-md-12">
											<a class="btn btn-primary <?=$hide_btn_unset?> btn-block" data-target="#ajax_notes2" data-toggle="modal" href="<?=base_url()?>apotik/stok_opname_online/modal_keterangan/<?=$id_so?>" id="simpan" title="<?=translate('Simpan', $this->session->userdata('language'))?>"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject"><?=translate("Daftar Item", $this->session->userdata("language"))?></span>
							</div>
						</div>
						<table class="table table-striped table-bordered table-hover" id="table_stok_opname">
							<thead>
							<tr>
								<th class="text-center"><?=translate("ID", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("K/SK", $this->session->userdata("language"))?> </th>
								<th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th>
								<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
							</tr>
							</thead>
							<tbody>
							
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<div id="popover_item_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_item_search">
            <thead>
                <tr>
                    <th class="text-center"><?=translate('ID', $this->session->userdata('language'))?></th>
                    <th class="text-center"><?=translate('Kode Item', $this->session->userdata('language'))?></th>
                    <th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
                    <th class="text-center"><?=translate('Tipe Item', $this->session->userdata('language'))?></th>
                    <th class="text-center"><?=translate('Keterangan', $this->session->userdata('language'))?></th>
                    <th class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="ajax_notes1" role="basic" aria-hidden="true">
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="modal fade" id="ajax_notes2" role="basic" aria-hidden="true">
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