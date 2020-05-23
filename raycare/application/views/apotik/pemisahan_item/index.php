<?php

	$form_attr = array(
	    "id"            => "form_index_gudang", 
	    "name"          => "form_index_gudang", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
		    echo form_open(base_url()."klinik_hd/surat_dokter_sppd/save", $form_attr);
		    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
			$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<div class="portlet light">


	<div class="portlet-title">
		<div class="caption">
			<span class="caption font-blue-sharp bold uppercase"><?=translate("Daftar Item", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">
			<a title="<?=translate("History", $this->session->userdata("language"))?>" href="<?=base_url()?>apotik/pemisahan_item/history" class="btn btn-circle btn-default batal">
				<i class="fa fa-history"></i> <?=translate("History", $this->session->userdata("language"))?>
			</a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-md-12"><?=translate("Gudang", $this->session->userdata("language"))?> :</label>
						<div class="col-md-12">
							<?php
								$gudang_inventory = $this->gudang_m->get_data_gudang()->result_array();
								// die_dump($gudang_inventory);
								$gudang_option = array(
									'0' => "Pilih Gudang"
								);
								foreach ($gudang_inventory as $gudang) {
									$gudang_option[$gudang['id']] = $gudang['nama_gudang'];
								}
								
								echo form_dropdown('tipe_gudang', $gudang_option, 1, "id=\"tipe_gudang\" class=\"form-control warehouse\"");
							?>
						</div>					
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-md-12"><?=translate("Kategori", $this->session->userdata("language"))?> :</label>
						<div class="col-md-12">
							<?php
								$kategori = $this->kategori_m->get_by(array('is_active' => 1));
								$kategori_options = array(
									'0' => "Pilih Kategori"
								);
								foreach ($kategori as $data_kategori) {
									$kategori_options[$data_kategori->id] = $data_kategori->nama;
								}
								
								echo form_dropdown('kategori', $kategori_options, "", "id=\"kategori\" class=\"form-control kategori\"");
							?>
						</div>						
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-md-12"><?=translate("Sub Kategori", $this->session->userdata("language"))?> :</label>
						<div class="col-md-12">
							<?php

								$sub_kategori_option = array(
									'' => "Pilih Sub Kategori"
								);
								
								echo form_dropdown('sub_kategori', $sub_kategori_option, "", "id=\"sub_kategori\" class=\"form-control sub_kategori\"");
							?>
						</div>
						
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="col-md-12">&nbsp;</label>
						<div class="col-md-12">
							<a class="btn btn-primary cari_listing"><i class="fa fa-search"></i> <?=translate("Cari", $this->session->userdata("language"))?></a>
						</div>
						
					</div>
				</div>
			</div><!-- end of <div class="row"> -->
			<div class="form-group">
				<div class="col-md-2">
				</div>
				<div class="col-md-2">
				</div>
				<div class="col-md-2">
				</div>
				<div class="col-md-2">
				</div>
				
			</div>

			<table class="table table-striped table-bordered table-hover" id="table_daftar_item">
			<thead>
			<tr>
				<th class="text-center hidden"><?=translate("ID", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="12%"><?=translate("Kode", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="72%"><?=translate("Nama", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Satuan", $this->session->userdata("language"))?> </th>
				<th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
			</tr>
			</thead>
			<tbody>
				
			</tbody>
			</table>
			
		</div>
	</div>


</div>

<div id="popover_info_item" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_info_item">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Satuan', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>