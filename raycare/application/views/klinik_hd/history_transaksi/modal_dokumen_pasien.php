<?php
	$ktp = '<i class="fa fa-times" style="color:red;"></i><span style="color:red;">KTP</span>';
	$file_ktp = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$ktp_pasien['value'];
    $result_ktp = @get_headers($file_ktp);

    if($result_ktp[0] == 'HTTP/1.1 200 OK' && isset($result_ktp[6])){
     	$ktp = '<i class="fa fa-check" style="color:blue;"></i><span style="color:blue;">KTP</span>';
    }

    $kk = '<i class="fa fa-times" style="color:red;"></i><span style="color:red;">Kartu Keluarga</span>';
	$file_kk = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$kartu_keluarga_pasien['value'];
    $result_kk = @get_headers($file_kk);

    if($result_kk[0] == 'HTTP/1.1 200 OK' && isset($result_kk[6])){
     	$kk = '<i class="fa fa-check" style="color:blue;"></i><span style="color:blue;">Kartu Keluarga</span>';
    }
	
	$kartu_bpjs = '<i class="fa fa-times" style="color:red;"></i><span style="color:red;">Kartu Penjamin</span>';
	$file_bpjs = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$kartu_penjamin_pasien['value'];
    $result_bpjs = @get_headers($file_bpjs);

    if($result_bpjs[0] == 'HTTP/1.1 200 OK' && isset($result_bpjs[6])){
     	$kartu_bpjs = '<i class="fa fa-check" style="color:blue;"></i><span style="color:blue;">Kartu Penjamin</span>';
    }

    $kartu_bpjs = '<i class="fa fa-times" style="color:red;"></i><span style="color:red;">Kartu Penjamin</span>';
	$file_bpjs = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$kartu_penjamin_pasien['value'];
    $result_bpjs = @get_headers($file_bpjs);

    if($result_bpjs[0] == 'HTTP/1.1 200 OK' && isset($result_bpjs[6])){
     	$kartu_bpjs = '<i class="fa fa-check" style="color:blue;"></i><span style="color:blue;">Kartu Penjamin</span>';
    }

    $kartu_bpjs = '<i class="fa fa-times" style="color:red;"></i><span style="color:red;">Kartu Penjamin</span>';
	$file_bpjs = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$kartu_penjamin_pasien['value'];
    $result_bpjs = @get_headers($file_bpjs);

    if($result_bpjs[0] == 'HTTP/1.1 200 OK' && isset($result_bpjs[6])){
     	$kartu_bpjs = '<i class="fa fa-check" style="color:blue;"></i><span style="color:blue;">Kartu Penjamin</span>';
    }

    $rujukan_pasien_check = '<i class="fa fa-times" style="color:red;"></i><span style="color:red;">Rujukan</span>';
	$file_rujukan = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$rujukan_pasien['value'];
    $result_rujukan = @get_headers($file_rujukan);

    if($result_rujukan[0] == 'HTTP/1.1 200 OK' && isset($result_rujukan[6])){
     	$rujukan_pasien_check = '<i class="fa fa-check" style="color:blue;"></i><span style="color:blue;">Rujukan</span>';
    }

    $rujukan_pasien_rs_check = '<i class="fa fa-times" style="color:red;"></i><span style="color:red;">Rujukan RS</span>';
	$file_rujukan_rs = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$rujukan_pasien_rs['value'];
    $result_rujukan_rs = @get_headers($file_rujukan_rs);

    if($result_rujukan_rs[0] == 'HTTP/1.1 200 OK' && isset($result_rujukan_rs[6])){
     	$rujukan_pasien_rs_check = '<i class="fa fa-check" style="color:blue;"></i><span style="color:blue;">Rujukan RS</span>';
    }

    $sppd_check = '<i class="fa fa-times" style="color:red;"></i><span style="color:red;">Surat Sp.PD</span>';
	$file_sppd = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/rekam_medis/'.$sppd['value'];
    $result_sppd = @get_headers($file_sppd);

    if($result_sppd[0] == 'HTTP/1.1 200 OK' && isset($result_sppd[6])){
     	$sppd_check = '<i class="fa fa-check" style="color:blue;"></i><span style="color:blue;">Surat Sp.PD</span>';
    }

    $sppd_tiga_check = '<i class="fa fa-times" style="color:red;"></i><span style="color:red;">Surat Sp.PD Ket. HD 3x</span>';
	$file_sppd_tiga = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$sppd_tiga['value'];
    $result_sppd_tiga = @get_headers($file_sppd_tiga);

    if($result_sppd_tiga[0] == 'HTTP/1.1 200 OK' && isset($result_sppd_tiga[6])){
     	$sppd_tiga_check = '<i class="fa fa-check" style="color:blue;"></i><span style="color:blue;">Surat Sp.PD Ket. HD 3x</span>';
    }

    $traveling_check = '<i class="fa fa-times" style="color:red;"></i><span style="color:red;">Traveling</span>';
	$file_traveling = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$traveling['value'];
    $result_traveling = @get_headers($file_traveling);

    if($result_traveling[0] == 'HTTP/1.1 200 OK' && isset($result_traveling[6])){
     	$traveling_check = '<i class="fa fa-check" style="color:blue;"></i><span style="color:blue;">Traveling</span>';
    }

?>
<form id="form_upload_invoice" name="form_upload_invoice" role="form" class="form-horizontal" autocomplete="off">
	<input type="hidden" id="pasien_id" name="pasien_id" required="required" class="form-control" value="<?=$data_pasien['id']?>">
	<input type="hidden" id="command" name="command" required="required" class="form-control" value="add">                       

	<div class="modal-body" id="section-upload">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<b>Dokumen Pasien <?=$data_pasien['nama']?></b>
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
	                <div class="form-grup">
	                	<?=$ktp;?>
	                </div>
	                <div class="form-grup">
	                	<?=$kk;?>
	                </div>
	                <div class="form-grup">
	                	<?=$kartu_bpjs;?>
	                </div>
	                <div class="form-grup">
	                	<?=$rujukan_pasien_check;?>
	                </div>
	                <div class="form-grup">
	                	<?=$rujukan_pasien_rs_check;?>
	                </div>
	                <div class="form-grup">
	                	<?=$sppd_check;?>
	                </div>
	                <div class="form-grup">
	                	<?=$sppd_tiga_check;?>
	                </div>
	                <div class="form-grup">
	                	<?=$traveling_check;?>
	                </div>
	                
			        
			        
	   			</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a class="btn btn-primary" target="_blank" name="print_perstujuan" href="<?=base_url()?>klinik_hd/history_transaksi/cetak_dokumen/<?=$data_pasien['id']?>/<?=$penjamin_id?>" ><?=translate("Cetak", $this->session->userdata("language"))?></a>
		<a class="btn default" data-dismiss="modal" ><?=translate("Tutup", $this->session->userdata("language"))?></a>
		
	</div>
</form>