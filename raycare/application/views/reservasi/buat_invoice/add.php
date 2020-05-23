<?php
	$form_attr = array(
	    "id"            => "form_add_invoice", 
	    "name"          => "form_add_invoice", 
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


<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Buat Invoice', $this->session->userdata('language'))?></span>
		</div>

	</div>
	<div class="note note-danger note-bordered">
		<p>
			 NOTE: Mulai tanggal 2 April 2018, Heparin tidak lagi di cantumkan kedalam invoice, Harga paket hemodialisa menjadi Rp. 1.050.000(Single) dan Rp. 850.000(Reuse) untuk pengguna BPJS.
		</p>
	</div>
	<div class="portlet-body form">
	<div class="row">
		<div class="col-md-3">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Informasi', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
				<div class="form-body" id="section-diagnosis">
					<div class="form-group">
		   	           	<div class="col-md-12">
          					<div class="btn-group btn-group-justified">
								<a id="btn_terdaftar" class="btn btn-primary">
									<?=translate("Terdaftar", $this->session->userdata("language"))?>
								</a>
								<a id="btn_tidak_terdaftar" class="btn btn-default">
									<?=translate("Tidak Terdaftar", $this->session->userdata("language"))?>
								</a>
							</div>
		              	</div>
	              	</div>
					<input class="form-control hidden" id="tipe_pasien" name="tipe_pasien" value="" >
						<div class="form-group pasien_terdaftar">
							<label class="col-md-12"><?=translate("No. Rekam Medis", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-12">
								<div class="input-group">
									<input class="form-control" id="no_rekmed" name="no_rekmed" value="" placeholder="<?=translate("No. Rekam Medis", $this->session->userdata("language"))?>" required>
									<span class="input-group-btn">
										<a class="btn grey-cascade pilih-pasien" title="<?translate('Pilih Pasien', $this->session->userdata('language'))?>">
											<i class="fa fa-search"></i>
										</a>
									</span>
								</div>
								<input class="form-control hidden" id="id_ref_pasien" name="id_ref_pasien" value="" required placeholder="<?=translate("ID Referensi Pasien", $this->session->userdata("language"))?>">
							</div>	
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Pasien", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
									<input class="form-control" id="nama_ref_pasien" name="nama_ref_pasien" value="" readonly  required placeholder="<?=translate("Nama Pasien", $this->session->userdata("language"))?>">
							</div>	
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Penanggung", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<div class="radio-list">
									<label>
									<input type="radio" name="penanggung" id="bpjs" value="1" checked> BPJS</label>
									<label>
									<input type="radio" name="penanggung" id="umum" value="2"> Umum (Pemasukan Klinik, Cek & Transfusi)</label>
									<label class="hidden">
									<input type="radio" name="penanggung" id="bpjs_umum" value="3"> BPJS & Umum </label>
								</div>
							</div>	
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Jenis Invoice", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
							<div class="col-md-12">
								<?php
									$jenis_option = array(
										'1'			=> translate('Internal (Pemasukan Klinik)', $this->session->userdata('language')),
									);
									echo form_dropdown('jenis_invoice', $jenis_option, '','id="jenis_invoice" class="form-control" required');
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Shift", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
							<div class="col-md-12">
								<?php
									$jenis_option = array(
										''			=> translate('Pilih', $this->session->userdata('language')).'..',
										'1'			=> translate('Shift 1', $this->session->userdata('language')),
										'2'			=> translate('Shift 2', $this->session->userdata('language')),
										'3'			=> translate('Shift 3', $this->session->userdata('language')),
										'4'			=> translate('Shift 4', $this->session->userdata('language')),
									);
									echo form_dropdown('tipe', $jenis_option, '','id="tipe" class="form-control" ');
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-12"><?=translate("Waktu", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
									<input class="form-control" id="waktu" name="waktu" value="" required placeholder="<?=translate("Waktu", $this->session->userdata("language"))?>">
							</div>	
						</div>
				
						
						</div>
					</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
		<div class="col-md-9">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Detail Invoice', $this->session->userdata('language'))?>
					</div>
					<div class="actions">
					<a class="btn btn-icon-only btn-default btn-circle add-item">
						<i class="fa fa-plus"></i>
					</a>
				</div>
				</div>
				<div class="portlet-body">
					<?php
						$btn_search 		= '<span class="input-group-btn"><button type="button" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></span>';

	                    $btn_del            = '<div class="text-center"><button class="btn red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
	                    $attrs_id = array(
	                        'id'          => 'item_id_{0}',
	                        'name'        => 'item[{0}][id]',
	                        'type'        => 'hidden',
	                        'class'       => 'form-control',
	                        //'value'		  => 1
	                    );
	                    $attrs_tipe_item = array(
	                        'id'          => 'item_tipe_item_{0}',
	                        'name'        => 'item[{0}][tipe_item]',
	                        'type'        => 'hidden',
	                        'class'       => 'form-control',
	                       // 'value'		  => 1
	                    );
	                    $attrs_kode = array(
	                        'id'          => 'item_kode_{0}',
	                        'name'        => 'item[{0}][kode]',
	                        'class'       => 'form-control',
	                        'style'		  => 'min-width:90px;',
	                        'readonly'    => 'readonly',
	                       // 'value'		  => '',
	                    );
	                    $attrs_name = array(
	                        'id'          => 'item_name_{0}',
	                        'name'        => 'item[{0}][name]',
	                        'class'       => 'form-control',
	                        'style'		  => 'min-width:160px;',
	                        'readonly'    => 'readonly',
	                        //'value'		  => 'Paket Hemodialisa'
	                    );
	                    $attrs_qty = array(
	                        'id'          => 'item_qty_{0}',
	                        'name'        => 'item[{0}][qty]',
	                        'class'       => 'form-control',
	                        'style'		  => 'min-width:60px;',
	                        //'value'		 => 1,
	                    );
	                    $attrs_harga = array(
	                        'id'          => 'item_harga_{0}',
	                        'name'        => 'item[{0}][harga]',
	                        'class'       => 'form-control',
	                        'style'		  => 'min-width:90px;',
	                        //'value'	   	  => 860000,
	                        'readonly'	=> "readonly"
	                    );

	                    $attrs_diskon = array(
	                        'id'          => 'item_diskon_{0}',
	                        'name'        => 'item[{0}][diskon]',
	                        'class'       => 'form-control',
	                        'style'		  => 'min-width:60px;',
	                    );
	                    $attrs_tipe = array(
	                        'id'          => 'item_tipe_{0}',
	                        'name'        => 'item[{0}][tipe]',
	                        'class'       => 'form-control',

	                    );
	                    $attrs_sub_total = array(
	                        'id'          => 'item_sub_total_{0}',
	                        'name'        => 'item[{0}][sub_total]',
	                        'class'       => 'form-control',
	                        'style'		  => 'min-width:90px;',
	                        'readonly'    => 'readonly',
	                        //'value'	   	  => 860000
	                    );

	                    $tipe_option = array(
	                    	''  => 'Pilih..',
	                    	//'1' => '-',
	                    	'2' => 'Obat & Vitamin',
	                    	'3' => 'Penunjang Medik',
	                    );

	                    $satuan_option = array(
	                    	
	                    );

	                    $item_cols = array(
							'item_kode'      => '<div class="input-group">'.form_input($attrs_kode).form_input($attrs_id).$btn_search.'</div>',
							'item_name'      => form_input($attrs_name).form_input($attrs_tipe_item),
							'item_qty'       => form_input($attrs_qty),
							'item_satuan'      => form_dropdown('item[{0}][satuan_id]', $satuan_option, '','id="item_satuan_id_{0}" class="form-control"  style="min-width:80px;"'),
							'item_harga'     => form_input($attrs_harga),
							'item_diskon'     => form_input($attrs_diskon),
							'item_tipe'      => form_dropdown('item[{0}][tipe]', $tipe_option, '','id="item_tipe_{0}" class="form-control" required  style="min-width:120px;"'),
							'item_sub_total' => form_input($attrs_sub_total),
							'action'         => $btn_del
	                    );

	                    // gabungkan $item_cols jadi string table row
	                    $item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
	                ?>
					<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-bordered table-hover" id="tabel_tambah_item">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" width="15%"><?=translate("Kode Item", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="20%"><?=translate("Nama Item", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="5%"><?=translate("Qty", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="10%"><?=translate("Satuan", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Harga", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="5%"><?=translate("Disc(%)", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="20%"><?=translate("Tipe", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="15%"><?=translate("Subtotal", $this->session->userdata('language'))?></th>
                                        <th class="text-center" width="1%"><?=translate("Aksi", $this->session->userdata('language'))?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <?//=$item_row?> -->
                                </tbody>

                                <tfoot>
                                	<tr>
                                		<th colspan="7"><div class="text-right">Akomodasi</div></th>
                                		<th colspan="2"><input type="number" name="akomodasi" id="item_akomodasi" value="0" class="form-control text-right"></th>
                                	</tr>
                                </tfoot>
                            </table>
                        </div>
					</div>
				</div>
			</div><!-- end of <div class="portlet light bordered"> -->
		</div><!-- end of <div class="col-md-8"> -->
		
	</div><!-- end of <div class="row"> -->
	<?php $msg = translate("Apakah anda yakin akan membuat invoice ini?",$this->session->userdata("language"));?>
	<div class="form-actions right">	
		<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
		<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="glyphicon glyphicon-floppy-disk"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
	</div>
</div>
	</div>



<?=form_close()?>

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No. RM', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tempat, Tanggal Lahir', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 

<div id="popover_item_content" class="row">
    <div class="col-md-12">
	    <div class="portlet light bordered">

		  <div class="portlet-title tabbable-line">
			<div class="caption">
				<?=translate("Pilih Paket", $this->session->userdata("language"))?>
			</div>
		    <ul class="nav nav-tabs">
		      <li  class="hidden">
		          <a href="#paket" data-toggle="tab">
		              <?=translate('Paket', $this->session->userdata('language'))?> </a>
		      </li>
		      <li  class="hidden">
		          <a href="#item" data-toggle="tab">
		              <?=translate('Item', $this->session->userdata('language'))?> </a>
		      </li>
		      <li  class="active">
		          <a href="#tindakan" data-toggle="tab">
		              <?=translate('Tindakan', $this->session->userdata('language'))?> </a>
		      </li>
		    </ul>
		  </div>
		  <div class="portlet-body form">
		    <div class="tab-content">
		        <div class="tab-pane hidden" id="paket" >
		            <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_paket">
			            <thead>
			                <tr role="row">
			                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
			                </tr>
			            </thead>
			            <tbody>
			            </tbody>
			        </table>
		        </div>
		        <div class="tab-pane hidden" id="item" >
		           	<table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item">
			            <thead>
			                <tr role="row">
			                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center" widht="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
			                </tr>
			            </thead>
			            <tbody>
			            </tbody>
			        </table>
		        </div>
		        <div class="tab-pane active" id="tindakan" >
		        	<table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_tindakan">
			            <thead>
			                <tr role="row">
			                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
			                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
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




