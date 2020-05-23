<?php
	$form_attr = array(
	    "id"            => "form_proses_cek_lab", 
	    "name"          => "form_proses_cek_lab", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add",
        "id"		=> $pk_value
    );

    echo form_open(base_url()."laboratorium/tindakan_cek_lab/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

	$check_terdaftar = '';
	$check_umum = '';
	$pasien_id = '0';
	$no_rekmed = '-';

	$nama_pasien = $data_tindakan_lab['nama_pasien'];
	$tanggal_lahir = date('d M Y', strtotime($data_tindakan_lab['tanggal_lahir']));
	$umur_pasien = $data_tindakan_lab['umur_pasien'];

	$jenkel = ($data_tindakan_lab['jenis_kelamin'] == 'L')?'Laki-Laki':'Perempuan';

	$no_telp = $data_tindakan_lab['no_telp_pasien'];
	$alamat = $data_tindakan_lab['alamat_pasien'];

	if($data_tindakan_lab['tipe_pasien'] == 1){
		$check_terdaftar = '';
		$check_umum = 'hidden';
		$pasien_id = $data_tindakan_lab['pasien_id'];

		$data_pasien = $this->pasien_m->get_by(array('id' => $data_tindakan_lab['pasien_id']), true);

		$no_rekmed = $data_pasien->no_member;
		


	}if($data_tindakan_lab['tipe_pasien'] == 2){
		$check_terdaftar = 'hidden';
		$check_umum = '';		
	}

	$status = '';
	switch ($data_tindakan_lab['status']) {
        case 1:
            $status = '<a class="btn btn-warning">Menunggu Pembayaran</a>';
   
            break; 

        case 2:
            $status = '<a class="btn btn-success">Menunggu Diproses</a>';
           
            break;
        
        case 3:
            $status = '<a class="btn btn-info">Selesai</a>';
           
            break;
            
        case 4:
            $status = '<a class="btn btn-danger">Dibatalkan</a>';
           
            break;
        
        default:
            break;
    }
    
    $msg = translate("Apakah anda yakin akan menyimpan data hasil lab ini?",$this->session->userdata("language"));
?>


<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-chemistry font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Proses Tindakan Cek Lab', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions"> 
			<a href="<?=base_url()?>laboratorium/tindakan_cek_lab" class="btn default"> <i class="fa fa-chevron-left"></i> <span class="hidden-480"><?=translate("Kembali", $this->session->userdata("language"))?></span> </a>
            <a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-save"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
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
					<div class="row"> 
						<div class="col-md-12"> 

						<div class="form-body" id="section-diagnosis">
						<div class="form-group">
			   	           	<div class="col-md-12">
									<a id="btn_terdaftar" class="btn btn-primary btn-group-justified <?=$check_terdaftar?>">
										<?=translate("Pasien Terdaftar", $this->session->userdata("language"))?>
									</a>
									<a id="btn_tidak_terdaftar" class="btn btn-default btn-group-justified <?=$check_umum?>">
										<?=translate("Umum", $this->session->userdata("language"))?>
									</a>
								
			              	</div>
		              	</div>
						<input class="form-control hidden" id="tipe_pasien" name="tipe_pasien" value="<?=$data_tindakan_lab['tipe_pasien']?>" >
						<input class="form-control hidden" id="pasien_id" name="pasien_id" value="<?=$data_tindakan_lab['pasien_id']?>" >
						<input class="form-control hidden" id="nama_pasien" name="nama_pasien" value="<?=$data_tindakan_lab['nama_pasien']?>" >
						<input class="form-control hidden" id="tanggal_lahir" name="tanggal_lahir" value="<?=$data_tindakan_lab['tanggal_lahir']?>" >
						<input class="form-control hidden" id="umur_pasien" name="umur_pasien" value="<?=$data_tindakan_lab['umur_pasien']?>" >
						<input class="form-control hidden" id="tindakan_lab_id" name="tindakan_lab_id" value="<?=$data_tindakan_lab['id']?>" >
						<input class="form-control hidden" id="laboratorium_klinik_id" name="laboratorium_klinik_id" value="<?=$data_tindakan_lab['laboratorium_klinik_id']?>" >
						<input class="form-control hidden" id="nama_dokter" name="nama_dokter" value="<?=$data_tindakan_lab['nama_dokter']?>" >
						<input class="form-control hidden" id="no_pemeriksaan" name="no_pemeriksaan" value="<?=$data_tindakan_lab['no_pemeriksaan']?>" >
						<input type="hidden" class="form-control" id="tanggal_periksa" name="tanggal_periksa" value="<?=$data_tindakan_lab['tanggal']?>" readonly >
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("No. Pemeriksaan", $this->session->userdata("language"))?> : </label>
								<label class="col-md-12"><?=$data_tindakan_lab['no_pemeriksaan']?>  </label>
							</div>
							<div class="form-group">
								<label class="col-md-4 bold"><?=translate("No. RM", $this->session->userdata("language"))?> : </label>
								<label class="col-md-8 bold"><?=translate("Nama Pasien", $this->session->userdata("language"))?> : </label>
								<label class="col-md-4"><?=$no_rekmed?></label>
								<label class="col-md-8"><?=$nama_pasien?></label>	
							</div>
							<div class="form-group">
								<label class="col-md-4 bold"><?=translate("Tgl.Lahir", $this->session->userdata("language"))?> : </label>
								<label class="col-md-8 bold"><?=translate("Umur", $this->session->userdata("language"))?> : </label>
								<label class="col-md-4"><?=$tanggal_lahir?></label>
								<label class="col-md-8"><?=$umur_pasien?></label>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 bold"><?=translate("JenKel", $this->session->userdata("language"))?> : </label>
								<label class="col-md-8 bold"><?=translate("No. Telp", $this->session->userdata("language"))?> : </label>
								<label class="col-md-4"><?=$jenkel?></label>
								<label class="col-md-8"><?=$no_telp?></label>
									
							</div>
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Alamat", $this->session->userdata("language"))?> : </label>
								<label class="col-md-12"><?=$alamat?></label>
								
							</div>
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Dokter Pengirim", $this->session->userdata("language"))?> : </label>
								<label class="col-md-12"><?=$data_tindakan_lab['nama_dokter']?></label>
								
							</div>
							<div class="form-group">
								<label class="col-md-12 bold"><?=translate("Diagnosa Klinis", $this->session->userdata("language"))?> : </label>
								<label class="col-md-12"><?=$data_tindakan_lab['diagnosa_klinis']?></label>
								
							</div>
						</div>
					</div><!-- end of <div class="portlet-body"> -->	
					
					</div>	
				</div>	
			</div>
		</div>
		<div class="col-md-9"> 
		<?php
                        $option_satuan = array();
                        $btn_search         = '<span class="input-group-btn"><button type="button" title="'.translate('Pilih Item', $this->session->userdata('language')).'" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></span>';
                        $btn_del            = '<div class="text-center"><button class="btn red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
                        
                        $btn_add_identitas     = '<span class="input-group-btn"><button type="button" data-toggle="modal" data-target="#popup_modal_jumlah_keluar" href="'.base_url().'tindakan/input_hasil_lab/add_identitas/item_row_{0}" class="btn blue-chambray add-identitas" name="item[{0}][identitas]" title="Tambah Jumlah"><i class="fa fa-info"></i></button></span>'; 
   
                        $attrs_item_pemeriksaan_lab_id = array(
                            'id'          => 'item_pemeriksaan_lab_id_{0}',
                            'name'        => 'item[{0}][pemeriksaan_lab_id]',
                            'class'       => 'form-control hidden',
                        );

                        $attrs_item_code = array(
                            'id'          => 'item_kode_{0}',
                            'name'        => 'item[{0}][kode]',
                            'class'       => 'form-control',
                            'width'       => '50%',
                            'readonly' => 'readonly',
                        );

                        $attrs_item_pemeriksaan = array(
                            'id'          => 'item_pemeriksaan_{0}',
                            'name'        => 'item[{0}][pemeriksaan]',
                            'class'       => 'form-control',
                        );
                        
                        $attrs_item_hasil = array(
                            'id'          => 'item_hasil_{0}',
                            'name'        => 'item[{0}][hasil]',
                            'class'       => 'form-control',
                        );

                        $attrs_item_id_detail = array(
                            'id'       => 'item_id_detail_{0}',
                            'name'     => 'item[{0}][id_detail]',
                            'class'    => 'form-control',
                            'type'     => 'hidden'
                        ); 

                        $attrs_item_nilai_normal = array(
                            'id'       => 'item_nilai_normal_{0}',
                            'name'     => 'item[{0}][nilai_normal]',
                            'class'    => 'form-control',
                        );
                        
                       
                        $attrs_item_satuan = array(
                            'id'       => 'item_satuan_{0}',
                            'name'     => 'item[{0}][satuan]',
                            'class'    => 'form-control',
                        );
                        $attrs_item_keterangan = array(
                            'id'       => 'item_keterangan_{0}',
                            'name'     => 'item[{0}][keterangan]',
                            'class'    => 'form-control'
                        );


                        // item row column
                        $item_cols = array(// style="width:156px;
                        	'item_no'	  => '<label id="item_nomor_{0}">{0}</label>',
                            'item_code'   => form_input($attrs_item_pemeriksaan_lab_id).form_input($attrs_item_pemeriksaan),
                            'item_hasil' => form_input($attrs_item_hasil),
                            'item_nilai_normal' => form_input($attrs_item_nilai_normal),
                            'item_satuan'   => form_input($attrs_item_satuan),
                            'item_keterangan'   => form_input($attrs_item_keterangan),
                            'action'      => $btn_del,
                        );

                        $item_row_template =  '<tr id="item_row_{0}" class="row_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

                    ?>
                   
                   
			<div class="portlet light bordered"> 
				<div class="portlet-title"> 
					<div class="caption">
						<?=translate('Detail Pemeriksaan', $this->session->userdata('language'))?>
					</div>
					<div class="actions">
						<?=$status?>
					</div>
				</div>	
				<div class="portlet-body"> 
				<span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
				<table class="table table-condensed table-striped table-hover" id="tabel_tambah_item">
					<thead>
						<tr>
							<th class="text-center" width="1%">No</th>
							<th class="text-center" width="30%"><?=translate("Pemeriksaan", $this->session->userdata("language"))?></th>
                            <th class="text-center" width="10%"><?=translate("Hasil", $this->session->userdata("language"))?></th>
                            <th class="text-center" width="20%"><?=translate("Nilai Normal", $this->session->userdata("language"))?></th>
                            <th class="text-center" width="5%"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                            <th class="text-center" width="25%"><?=translate("Keterangan", $this->session->userdata("language"))?></th>
                            <th class="text-center" width="1%" ><?=translate("Aksi", $this->session->userdata("language"))?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 1;
							foreach ($data_tindakan_lab_detail as $lab_detail):
						?>
							<tr>
								<td width="1%"><input type="hidden" value="<?=$lab_detail['pemeriksaan_lab_id']?>" name="item[<?=$i?>][pemeriksaan_lab_id]" id="item_pemeriksaan_lab_id_<?=$i?>" class="form-control"><?=$i?></td>
								<td><input type="hidden" value="<?=$lab_detail['nama_pemeriksaan']?>" name="item[<?=$i?>][pemeriksaan]" id="item_pemeriksaan_<?=$i?>" class="form-control"><?=$lab_detail['nama_pemeriksaan']?></td>
								<td><input type="text" name="item[<?=$i?>][hasil]" id="item_hasil_<?=$i?>" class="form-control"></td>
								<td><input type="text" name="item[<?=$i?>][nilai_normal]" id="item_nilai_normal_<?=$i?>" class="form-control"></td>
								<td><input type="text" name="item[<?=$i?>][satuan]" id="item_satuan_<?=$i?>" class="form-control"></td>
								<td><input type="text" name="item[<?=$i?>][keterangan]" id="item_keterangan_<?=$i?>" class="form-control"></td>
								<td></td>
							</tr>
						<?php
							$i++;
							endforeach;
						?>
						
					</tbody>
					<input type="hidden" id="jml_baris" value="<?=$i?>">
				</table>
				<a id="tambah_identitas" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">
                             <?=translate("Tambah", $this->session->userdata("language"))?>
                        </span>
                    </a>
				</div>
			</div> 

		</div>	
	</div>
</div>
			


<?=form_close()?>


