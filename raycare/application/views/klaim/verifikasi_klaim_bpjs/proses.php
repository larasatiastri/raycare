<?php
	$form_attr = array(
	    "id"            => "form_proses_verif_klaim", 
	    "name"          => "form_proses_verif_klaim", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "edit"
    );

    echo form_open(base_url()."klaim/verifikasi_klaim_bpjs/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$user_direktur = $this->user_m->get($data_klaim['user_id']);
	$penerima = $this->petugas_bpjs_m->get_by(array('jabatan' => 2, 'is_active' => 1));
	$verif = $this->petugas_bpjs_m->get_by(array('jabatan' => 3, 'is_active' => 1));

	$diterima_option = array();
	$verif_option = array();


	foreach ($penerima as $penerima) 
	{
		$diterima_option[$penerima->id] = $penerima->nama;
	};

	foreach ($verif as $verif) 
	{
		$verif_option[$verif->id] = $verif->nama;
	};

	$options = array();

	$btn_del           	= '<div class="text-center"><button class="btn red-intense del-this" title="'.translate('Delete', $this->session->userdata('language')).'"><i class="fa fa-times"></i></button></div>';
	
	$item_cols = array(// style="width:156px;
		'pasien_kode'      => '<input type="hidden" id="verif[{0}][id_pasien]" name="verif[{0}][id_pasien]"><div class ="input-group">'
								. '<input type="text" name="verif[{0}][no_rm]" id="verif[{0}][no_rm]" readonly class="form-control" autofocus="1">'
								. '<span class="input-group-btn"><button class="btn btn-success search-pasien" title="Search Pasien"><i class="fa fa-search"></i></button></span>'
								. '</div>',
		'pasien_nama'      => '<input type="text" name="verif[{0}][nama_pasien]" id="verif[{0}][nama_pasien]" class="form-control" readonly>',
		'tanggal_tindakan' => form_dropdown('verif[{0}][tanggal_tindakan]', $options, '','id="verif[{0}][tanggal_tindakan]" class="form-control"'),
		'no_skp'           => '<input type="text" name="verif[{0}][no_skp]" id="verif[{0}][no_skp]" class="form-control">',
		'ina_cbg'          => '<input type="text" name="verif[{0}][ina_cbg]" id="verif[{0}][ina_cbg]" class="form-control">',
		'tarif'            => '<input type="text" name="verif[{0}][tarif]" id="verif[{0}][tarif]" class="form-control">',
		'amhp'             => '<input type="text" name="verif[{0}][amhp]" id="verif[{0}][amhp]" class="form-control">',
		'biaya_lain'       => '<input type="text" name="verif[{0}][biaya_lain]" id="verif[{0}][biaya_lain]" class="form-control">',
		'total'            => '<input type="text" name="verif[{0}][total]" id="verif[{0}][total]" class="form-control">',
		'keterangan'       => '<input type="text" name="verif[{0}][keterangan]" id="verif[{0}][keterangan]" class="form-control">',
		'action'           => $btn_del,
	);

	$item_row_template =  '<tr id="item_row_{0}"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';



?>

<div class="form-body">
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Proses Verifikasi Klaim BPJS', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan memproses verifikasi klaim bpjs ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Pengajuan Klaim', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<input type="hidden" class="form-control" id="proses_id" name="proses_id" value="<?=$data_klaim['id']?>"></input>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("Tanggal FPK", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<div class="input-group date" id="date">
									<input type="text" class="form-control" id="tanggal_proses" name="tanggal_proses" value="<?=date('d M Y')?>" readonly required>
									<span class="input-group-btn">
										<button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-4"><?=translate("No FPK", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-5">
								<input class="form-control" name="no_fpk" id="no_fpk" required></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate("Tanggal Surat", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label class="control-label"><?=date('d M Y', strtotime($data_klaim['tanggal']))?> </label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate("No Surat", $this->session->userdata("language"))?> : </label>
							<div class="col-md-7">
								<label class="control-label"><?=$data_klaim['no_surat']?> </label>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate("No Surat Perjanjian", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label class="control-label"><?=$data_klaim['no_surat_perjanjian']?> </label>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate("Periode Tindakan", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label class="control-label"><?=date('M Y', strtotime($data_klaim['periode_tindakan']))?>  </label>
								<input type="hidden" class="form-control" id="periode_tindakan" name="periode_tindakan" value="<?=date('m-Y', strtotime($data_klaim['periode_tindakan']))?>"></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate("Jumlah Tindakan", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label class="control-label"><?=$data_klaim['jumlah_tindakan']?> </label>
								<input type="hidden" class="form-control" id="jumlah_tindakan" name="jumlah_tindakan" value="<?=$data_klaim['jumlah_tindakan']?>"></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate("Jumlah Tarif Riil RS", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label class="control-label"><?=formatrupiah($data_klaim['jumlah_tarif_riil'])?> </label>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate("Jumlah Tarif INA CBGs", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label class="control-label"><?=formatrupiah($data_klaim['jumlah_tarif_ina'])?> </label>
								<input type="hidden" class="form-control" id="jumlah_tarif_ina" name="jumlah_tarif_ina" value="<?=$data_klaim['jumlah_tarif_ina']?>"></input>
							</div>	
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate("Diserahkan oleh", $this->session->userdata("language"))?> : </label>
							<div class="col-md-5">
								<label class="control-label"><?=$user_direktur->nama?> </label>
							</div>	
						</div>
						
					</div>
				</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
		<div class="col-md-8">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Verifikasi Klaim BPJS', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
					<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
					<div class="form-body">
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label"><b><?=translate('Data Tindakan Tidak Layak', $this->session->userdata('language'))?></b></label>
							</div>
						</div>
					</div>
					<div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover" id="tabel_tindakan_tidak_layak">
                            <thead>
                                <tr role="row">
                                    <th width="20%"><div class="text-center"><?=translate("No MR", $this->session->userdata('language'))?></div></th>
                                    <th width="25%"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
                                    <th width="15%"><div class="text-center"><?=translate("Tgl Pelayanan", $this->session->userdata('language'))?></div></th>
                                    <th width="10%"><div class="text-center"><?=translate("No SKP/SJP", $this->session->userdata('language'))?></div></th>
                                    <th width="10%"><div class="text-center"><?=translate("INA-CBG", $this->session->userdata('language'))?></div></th>
                                    <th width="15%"><div class="text-center"><?=translate("Tarif", $this->session->userdata('language'))?></div></th>
                                    <th width="15%"><div class="text-center"><?=translate("AHMP", $this->session->userdata('language'))?></div></th>
                                    <th width="15%"><div class="text-center"><?=translate("Biaya Lainnya", $this->session->userdata('language'))?></div></th>
                                    <th width="25%"><div class="text-center"><?=translate("Total", $this->session->userdata('language'))?></div></th>
                                    <th width="20%"><div class="text-center"><?=translate("Keterangan", $this->session->userdata('language'))?></div></th>
                                    <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
                                </tr>
                            </thead>
                            <tbody>
                              
                                <!-- <?//=$item_row?> -->
                            </tbody>
                        </table>
                    </div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Jumlah Tindakan Tidak Layak", $this->session->userdata("language"))?> : </label>
						<div class="col-md-2">
							<input type="text" class="form-control" name="tindakan_tidak_layak" id="tindakan_tidak_layak" value="0"></input>
						</div>	
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Total Tarif Tidak Layak", $this->session->userdata("language"))?> : </label>
						<div class="col-md-5">
							<input type="text" class="form-control" name="tarif_tidak_layak" id="tarif_tidak_layak" value="0"></input>
						</div>	
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Jumlah Tindakan Verifikasi", $this->session->userdata("language"))?> : </label>
						<div class="col-md-9">
							<label class="control-label"><?=translate("Jumlah Tindakan (Pengajuan)", $this->session->userdata("language"))?> - <?=translate("Jumlah Tindakan Tidak Layak", $this->session->userdata("language"))?></label>
						</div>	
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"> : </label>
						<div class="col-md-9">
							<label class="control-label" id="label_jumlah_tindakan_verif"><?=$data_klaim['jumlah_tindakan']?> </label>
							<input type="hidden" class="form-control" name="jumlah_tindakan_verif" id="jumlah_tindakan_verif" value="<?=$data_klaim['jumlah_tindakan']?>"></input>
						</div>	
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Jumlah Tarif INA CBGs Verifikasi", $this->session->userdata("language"))?> : </label>
						<div class="col-md-9">
							<label class="control-label"><?=translate("Jumlah Tarif INA CBGs (Pengajuan)", $this->session->userdata("language"))?> - <?=translate("Jumlah Total Tarif Tidak Layak", $this->session->userdata("language"))?> </label>
						</div>	
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"> : </label>
						<div class="col-md-9">
							<label class="control-label" id="label_jumlah_tarif_ina_verif"><?=formatrupiah($data_klaim['jumlah_tarif_ina'])?></label>
							<input type="hidden" class="form-control" name="jumlah_tarif_ina_verif" id="jumlah_tarif_ina_verif" value="<?=$data_klaim['jumlah_tarif_ina']?>"></input>
						</div>	
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?> : </label>
						<div class="col-md-6">
							<textarea name="keterangan" id="keterangan" class="form-control" cols="3"></textarea>
						</div>	 
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Diterima oleh", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
						<div class="col-md-5">
							<?php
								echo form_dropdown('penerima_id', $diterima_option, '','id="penerima_id" class="form-control" required');
							?>
						</div>	
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Diverifikasi oleh", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
						<div class="col-md-5">
							<?php
								echo form_dropdown('verif_id', $verif_option, '','id="verif_id" class="form-control" required');
							?>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div><!-- end of <div class="row"> -->

	</div>
</div>


<?=form_close()?>

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
      	<table class="table table-condensed table-striped table-bordered table-hover" id="table_pasien">
            <thead>
                <tr role="row" class="heading">
                    <th><div class="text-center">No MR</div></th>
                    <th><div class="text-center">Nama</div></th>
                    <th><div class="text-center">Alamat</div></th>
                    <th><div class="text-center">Aksi</div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 






