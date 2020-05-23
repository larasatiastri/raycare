<?php
	$form_attr = array(
	    "id"            => "form_add_cek_lab", 
	    "name"          => "form_add_cek_lab", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
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
?>


<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('View Tindakan Cek Lab', $this->session->userdata('language'))?></span>
		</div>
		<div class="actions"> 
			<a href="<?=base_url()?>laboratorium/tindakan_cek_lab" class="btn default"> <i class="fa fa-chevron-left"></i> <span class="hidden-480"><?=translate("Kembali", $this->session->userdata("language"))?></span> </a>
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
						<input class="form-control hidden" id="tindakan_lab_id" name="tindakan_lab_id" value="<?=$data_tindakan_lab['id']?>" >
						<input type="hidden" class="form-control" id="tanggal_periksa" name="tanggal_periksa" value="<?=date('d M Y')?>" readonly >
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
				<table class="table table-condensed table-striped table-hover">
					<thead>
						<tr>
							<th class="text-center" width="1%">No</th>
							<th class="text-center">Nama Pemeriksaan</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 1;
							foreach ($data_tindakan_lab_detail as $lab_detail):
						?>
							<tr>
								<td width="1%"><?=$i?></td>
								<td><?=$lab_detail['nama_pemeriksaan']?></td>
							</tr>
						<?php
							$i++;
							endforeach;
						?>
						
					</tbody>
				</table>
				</div>
			</div> 

		</div>	
	</div>
</div>
			


<?=form_close()?>


