<?php
	$form_attr = array(
	    "id"            => "form_add_faskes", 
	    "name"          => "form_add_faskes", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."reservasi/buat_invoice/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>

<div class="form-body">
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Buat Faskes Marketing', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat invoice ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="<?=base_url()?>master/faskes_marketing"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
		</div>
	</div>
	<div class="row" id="section-faskes">
		<div class="col-md-12">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Marketing', $this->session->userdata('language'))?>
					</div>
					
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<label class="col-md-3"><?=translate("Marketing", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
							<div class="col-md-8">
								<?php
									$marketing_option = array(
										''			=> translate('Pilih', $this->session->userdata('language')).'..',
									);

									$users = $this->user_m->get_by(array('user_level_id' => 20, 'is_active' => 1));
									foreach ($users as $user) {
										$marketing_option[$user->id] = $user->nama; 
									}
									echo form_dropdown('marketing_id', $marketing_option, '','id="marketing_id" class="form-control" ');
								?>
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
						<?=translate('Daftar Faskes', $this->session->userdata('language'))?>
					</div>
					<div class="actions">
					<a class="btn btn-icon-only btn-default btn-circle add-item">
						<i class="fa fa-plus"></i>
					</a>
				</div>
				</div>
				<div class="portlet-body" >
					<?php
						$btn_search 		= '<span class="input-group-btn"><button type="button" title="Pilih Faskes" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></span>';

	                    $btn_del            = '<div class="text-center"><button class="btn red-intense del-this" title="Delete" data-confirm="'.translate('Anda yakin akan menghapus faskes untuk marketing ini?', $this->session->userdata('language')).'"><i class="fa fa-times"></i></button></div>';
	                    $attrs_id = array(
	                        'id'          => 'faskes_id_{0}',
	                        'name'        => 'faskes[{0}][id]',
	                        'type'        => 'hidden',
	                        'class'       => 'form-control',
	                        'value'		  => 1
	                    );
	                    $attrs_is_active = array(
	                        'id'          => 'faskes_is_active_{0}',
	                        'name'        => 'faskes[{0}][is_active]',
	                        'type'        => 'hidden',
	                        'class'       => 'form-control',
	                        'value'		  => 1
	                    );
	                    $attrs_kode = array(
	                        'id'          => 'faskes_kode_{0}',
	                        'name'        => 'faskes[{0}][kode_faskes]',
	                        'class'       => 'form-control',
	                        'readonly'    => 'readonly',
	                        'value'		  => '',
	                    );
	                    $attrs_name = array(
	                        'id'          => 'faskes_name_{0}',
	                        'name'        => 'faskes[{0}][nama_faskes]',
	                        'class'       => 'form-control',
	                        'readonly'    => 'readonly',
	                    );
	                    $attrs_regional = array(
	                        'id'          => 'faskes_reg_{0}',
	                        'name'        => 'faskes[{0}][nama_reg]',
	                        'class'       => 'form-control',
	                        'readonly'    => 'readonly',
	                    );
	                    $attrs_alamat = array(
	                        'id'          => 'faskes_alamat_{0}',
	                        'name'        => 'faskes[{0}][alamat]',
	                        'class'       => 'form-control',
	                        'readonly'    => 'readonly',
	                        
	                    );
	                    $attrs_type = array(
	                        'id'          => 'faskes_type_{0}',
	                        'name'        => 'faskes[{0}][jenis]',
	                        'class'       => 'form-control',
	                        'readonly'    => 'readonly',
	                        
	                    );
	                    $attrs_telepon = array(
	                        'id'          => 'faskes_telepon_{0}',
	                        'name'        => 'faskes[{0}][telp]',
	                        'class'       => 'form-control',
	                        'readonly'    => 'readonly',
	                        
	                    );

	                    $tipe_option = array(
	                    	'1' => '-',
	                    	'2' => 'Obat & Vitamin',
	                    	'3' => 'Penunjang Medik',
	                    );

	                    $item_cols = array(
							'item_kode'      => '<div class="input-group">'.form_input($attrs_kode).form_input($attrs_id).$btn_search.'</div>',
							'item_name'      => form_input($attrs_name),
							'item_type'       => form_input($attrs_type),
							'item_reg'       => form_input($attrs_regional),
							'item_alamat'     => form_input($attrs_alamat),
							'item_telepon' => form_input($attrs_telepon).form_input($attrs_is_active),
							'action'         => $btn_del
	                    );

	                    // gabungkan $item_cols jadi string table row
	                    $item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
	                ?>
					<span id="tpl_faskes_row" class="hidden"><?=htmlentities($item_row_template)?></span>
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="tabel_tambah_faskes">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="10%"><?=translate("Kode Faskes", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="20%"><?=translate("Nama Faskes", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="12%"><?=translate("Tipe", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Regional", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="25%"><?=translate("Alamat", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Telepon", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    	<td colspan="7" align="center">No data available in table</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div><!-- end of <div class="portlet light bordered"> -->
		</div><!-- end of <div class="col-md-8"> -->
		
	</div><!-- end of <div class="row"> -->

	</div>
</div>


<?=form_close()?>

<div id="popover_faskes_content" class="row">   
  	<div class="col-md-12">
       	<table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_faskes">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('Jenis', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Regional', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" widht="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
]</div> 




