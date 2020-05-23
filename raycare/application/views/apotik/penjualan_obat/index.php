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

    echo form_open(base_url()."apotik/penjualan_obat/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>


<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Penjualan Obat', $this->session->userdata('language'))?></span>
		</div>
		
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
										<a class="btn grey-cascade pilih-pasien" title="<?=translate('Pilih Pasien', $this->session->userdata('language'))?>">
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
							<label class="col-md-12"><?=translate("Alamat", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<textarea class="form-control" id="alamat_pasien" name="alamat_pasien" value="" readonly cols="6" rows="4"></textarea>
							</div>	
						</div>
						
						<div class="form-group hidden">
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
						<div class="form-group hidden">
							<label class="col-md-12"><?=translate("Waktu", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
									<input class="form-control" id="waktu" name="waktu" value="" placeholder="<?=translate("Waktu", $this->session->userdata("language"))?>">
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
                        <span class="caption-subject"><?=translate("Daftar Item", $this->session->userdata("language"))?></span>
                    </div>                      
                </div>
                <div class="portlet-body"> 
                    <div class="form-group">
                        <label class="col-md-2"><?=translate("Gudang", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
                        <div class="col-md-6">
                            <?php
                                $gudang = $this->gudang_m->get_by(array('is_active' => 1));
                                $gudang = object_to_array($gudang);

                                foreach ($gudang as $row) {
                                    $gudang_options[$row['id']] = $row['nama'];
                                }

                                echo form_dropdown('gudang_id', $gudang_options, '','id="gudang_id" class="form-control"');
                            ?>
                        </div>
                    </div>
                    <?php
                        $option_satuan = array();
                        $btn_search         = '<span class="input-group-btn"><button type="button" title="'.translate('Pilih Item', $this->session->userdata('language')).'" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></span>';
                        $btn_del            = '<div class="text-center"><button class="btn red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
                        
                        $btn_add_identitas     = '<span class="input-group-btn"><button type="button" data-toggle="modal" data-target="#popup_modal_jumlah_keluar" href="'.base_url().'apotik/penjualan_obat/add_identitas/item_row_{0}" class="btn blue-chambray add-identitas" name="item[{0}][identitas]" title="Tambah Jumlah"><i class="fa fa-info"></i></button></span>'; 
   
                        $attrs_item_id = array(
                            'id'          => 'item_id_{0}',
                            'name'        => 'item[{0}][item_id]',
                            'class'       => 'form-control hidden',
                        );

                        $attrs_item_code = array(
                            'id'          => 'item_kode_{0}',
                            'name'        => 'item[{0}][kode]',
                            'class'       => 'form-control',
                            'width'       => '50%',
                            'readonly' => 'readonly',
                        );

                        $attrs_item_name = array(
                            'id'          => 'item_name_{0}',
                            'name'        => 'item[{0}][name]',
                            'class'       => 'form-control',
                            'readonly'    => 'readonly',
                        );

                        $attrs_item_jumlah = array(
                            'id'       => 'item_qty_{0}',
                            'name'     => 'item[{0}][qty]',
                            'class'    => 'form-control',
                            'value'    => 0,
                            'min'      => 0,
                            'type'     => 'number',
                            'readonly' => 'readonly',
                        );

                        $attrs_item_harga = array(
                            'id'       => 'item_harga_{0}',
                            'name'     => 'item[{0}][harga]',
                            'class'    => 'form-control',
                            'type'     => 'hidden',
                             'value'    => 0,
                        );

                        $attrs_item_diskon_persen = array(
                            'id'       => 'item_diskon_persen_{0}',
                            'name'     => 'item[{0}][diskon_persen]',
                            'class'    => 'form-control',
                            'type'     => 'number',
                             'value'    => 0,
                        ); 
                        $attrs_item_diskon = array(
                            'id'       => 'item_diskon_{0}',
                            'name'     => 'item[{0}][diskon]',
                            'class'    => 'form-control',
                            'type'     => 'number',
                             'value'    => 0,
                        );
                        $attrs_item_sub_total = array(
                            'id'       => 'item_sub_total_{0}',
                            'name'     => 'item[{0}][sub_total]',
                            'class'    => 'form-control',
                            'type'     => 'hidden',
                            'value'    => 0,
                        );



                        // item row column
                        $item_cols = array(// style="width:156px;
                            'item_code'   => '<div class="input-group">'.form_input($attrs_item_code).form_input($attrs_item_id).$btn_search.'</div>',
                            'item_name'   => form_input($attrs_item_name).'<div id="identitas_row" class="hidden"></div>',
                            'item_satuan' => form_dropdown('item[{0}][satuan_id]',  $option_satuan, "", "id='item_satuan_id_{0}' class='form-control'"),
                            'item_jumlah' => '<div class="input-group">'.form_input($attrs_item_jumlah).$btn_add_identitas.'</div>',
                            'item_harga'   => form_input($attrs_item_harga).'<div id="item_harga" class="text-right">Rp.0,-</div>',
                            'item_diskon_persen'   => form_input($attrs_item_diskon_persen),
                            'item_diskon'   => form_input($attrs_item_diskon),
                            'item_sub_total'   => form_input($attrs_item_sub_total).'<div id="item_sub_total" class="text-right">Rp.0,-</div>',
                            'action'      => $btn_del,
                        );

                        $item_row_template =  '<tr id="item_row_{0}" class="row_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

                    ?>
                   
                    <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
                    
                    <table class="table table-bordered tabel_tambah_item" id="tabel_tambah_item">
                        <thead>
                            <tr>
                                <th class="text-center" width="9%"><?=translate("Kode", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="20%"><?=translate("Nama", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("Harga", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="5%"><?=translate("Disk(%)", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="8%"><?=translate("Disk(Rp)", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="15%"><?=translate("Sub Total", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="1%" ><?=translate("Aksi", $this->session->userdata("language"))?></th>
                            </tr>
                        </thead>
                                
                        <tbody>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <input name="total" id="total_hidden" type="hidden" class="form-control"></input>
                                <th colspan="7" class="text-right">Total</th>
                                <th colspan="2" class="text-right" id="total">Rp. 0,-</th>
                            </tr>
                            <tr>
                                <th colspan="6" class="text-right">Diskon</th>
                                <th class="text-right"><div class="input-group col-md-12">
                                                <input class="form-control text-right" type="number" value="0" min="0" max="100" id="diskon_persen" name="diskon_persen">
                                                <span class="input-group-addon">
                                                    &nbsp;%&nbsp;
                                                </span>
                                            </div></th>
                                <th colspan="2" class="text-right"><input name="diskon" id="diskon" type="number" class="form-control"></th>
                            </tr>
                            <tr>
                                <th colspan="7" class="text-right">Biaya Tambahan (Rp)</th>
                                <th colspan="2" class="text-right"><input name="biaya_tambahan" id="biaya_tambahan" type="number" class="form-control"></th>
                            </tr>
                            <tr>
                                <input name="grand_total" id="grand_total_hidden" type="hidden" class="form-control"></input>
                                <th colspan="7" class="text-right">Grand Total</th>
                                <th colspan="2" class="text-right" id="grand_total">Rp. 0,-</th>
                            </tr>
                            
                        </tfoot>
                    </table>

                </div>
            </div>
            
        </div><!-- end of <div class="col-md-8"> -->
		
	</div><!-- end of <div class="row"> -->
    <?php $msg = translate("Apakah anda yakin akan membuat penjualan obat ini?",$this->session->userdata("language"));?>
        <div class="form-actions right">    
            <a class="btn default" href="<?=base_url()?>apotik/penjualan_obat/history"><i class="fa fa-undo"></i>  <?=translate("History", $this->session->userdata("language"))?></a>
            <a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
        </div>

	</div>
</div>


<?=form_close()?>

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
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
       	<table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Gambar', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" widht="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>      
    </div>
</div> 
<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_keluar" role="basic" aria-hidden="true">
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




