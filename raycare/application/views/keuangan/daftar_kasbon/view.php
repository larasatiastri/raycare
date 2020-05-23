<?php

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_view_permintaan_biaya", 
		"name"			=> "form_view_permintaan_biaya", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "view"
	);

	echo form_open(base_url()."keuangan/permintaan_biaya/save", $form_attr,$hidden);
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

 //    /////////////////////////////////////////////////////////////////////

    $user_level_id = $this->session->userdata('level_id');
    // die_dump($user_level_id);

    if($form_data['status'] == 1)
    {
        $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
    
    } elseif($form_data['status'] == 2){

        $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';

    }elseif($form_data['status'] == 3){

        $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

    } elseif($form_data['status'] == 4){

        $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
    }elseif($form_data['status'] == 5){

        $status = '<div class="text-center"><span class="label label-md label-success">Diproses</span></div>';
    }elseif($form_data['status'] > 5){

        $status = '<div class="text-center"><span class="label label-md label-success">Pencairan</span></div>';
    }


?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-search font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("View Permintaan Biaya", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=translate('Kembali', $this->session->userdata('language'));?>
	        </a>
	        <!-- <button type="reset" class="btn default"><?=$reset_text?></button> -->
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
					<div class="col-md-12">
						<label class="control-label"><?=date('d M Y', strtotime($form_data['tanggal']))?></label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12"><?=translate("Diminta Oleh", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group">
							<?php

								$nama = $this->user_m->get_by(array('id' => $form_data['diminta_oleh_id']), true);
								$nama_user = object_to_array($nama);
								// die_dump($nama);

							?>
							<label class="control-label"><?=$nama_user['nama']?></label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12"><?=translate('Tipe', $this->session->userdata('language'))?> :</label>
					<div class="col-md-12">
						<?php
							$tipe = '';
							
							if($form_data['tipe'] == 1){
								$tipe = 'Kasbon';
							}if($form_data['tipe'] == 2){
								$tipe = 'Reimburse / Pencairan';
							}

						?>
						<label><?=$tipe?></label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-12"><?=translate("Nominal", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group">
							<label class="control-label"><?=formatrupiah($form_data['nominal'])?></label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12"><?=translate("Terbilang", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group">
							<label class="control-label"><?='#'.terbilang($form_data['nominal']).' Rupiah#'?></label>
						</div>
					</div>
				</div>
						
			</div>

		</div>
		<div class="col-md-9">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject"><?=translate("Keperluan", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="form-body">
				    <div class="portlet-body">
						<div class="form-group">
							<div class="col-md-12">
							<?php
								$notes = explode("\n", $form_data['keperluan']);
            					$notes    = implode('</br>', $notes);
							?>
								<label><?=$notes?></label>
							</div>
						</div>
	                </div>
					
				</div>
			</div>
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject"><?=translate("Persetujuan", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="form-body">
				    <div class="portlet-body">
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label"><?=translate('Status', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label class="control-label"><?=$status?></label>
							</div>
						</div>
	                </div>
	                <div class="portlet-body">
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label"><?=translate('Nominal Disetujui', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label class="control-label"><?=formatrupiah($form_data['nominal_setujui'])?></label>
							</div>
						</div>
	                </div>
	                <div class="portlet-body">
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label"><?=translate('Terbilang', $this->session->userdata('language'))?> :</label>
							</div>
							<div class="col-md-12">
								<label class="control-label"><?='#'.terbilang($form_data['nominal_setujui']).' Rupiah#'?></label>
							</div>
						</div>
	                </div>
					
				</div>
			</div>
		</div>
		<!-- end of pilih item -->
	
	</div>

</div>


<?=form_close();?>

