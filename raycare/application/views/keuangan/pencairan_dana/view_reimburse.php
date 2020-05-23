<?php

    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_view_permintaan_biaya", 
		"name"			=> "form_view_permintaan_biaya", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "view",
		"id"		=> $pk_value
	);

	echo form_open(base_url()."keuangan/proses_permintaan_biaya/save", $form_attr,$hidden);
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

 //    /////////////////////////////////////////////////////////////////////

	$submit_text = translate('Simpan', $this->session->userdata('language'));
	$confirm_save = translate('Anda yakin akan memproses permintaan biaya ini?', $this->session->userdata('language'));

    $user_level_id = $this->session->userdata('level_id');
    // die_dump($user_level_id);

    if($form_data['status'] == 1)
    {
        $status = '<div class="text-center"><span class="label label-md label-warning">Menunggu Persetujuan</span></div>';
    
    } elseif($form_data['status'] == 2){

        $status = '<div class="text-center"><span class="label label-md label-info">Dibaca</span></div>';

    }elseif($form_data['status'] == 3){

        $status = '<div class="text-center"><span class="label label-md label-success">Disetujui</span></div>';

    } elseif($form_data['status'] == 4 || $form_data['status'] == 19){

        $status = '<div class="text-center"><span class="label label-md label-danger">Ditolak</span></div>';
    } elseif($form_data['status'] == 5 || $form_data['status'] == 18){

        $status = '<div class="text-center"><span class="label label-md label-success">Diproses</span></div>';
    }

    $user_setuju_id = $form_data['disetujui_oleh'];
    $user_setuju = $this->user_m->get($user_setuju_id);

    $tgl_setuju = date('d M Y', strtotime($form_data['created_date']));	

?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-search font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("View Permintaan Biaya Diproses", $this->session->userdata("language"))?></span>
		</div>
		<div class="actions">    
	        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
	        	<i class="fa fa-chevron-left"></i>
	        	<?=translate('Kembali', $this->session->userdata('language'));?>
	        </a>
	       
		</div>
	</div>
	<?php
		if($form_data['status'] == 5 || $form_data['status'] == 18){
			?>
		<div class="note note-success note-bordered">
			<p>
				 NOTE: <?=$form_data['keterangan_tolak'].'. Created By: '.$user_setuju->nama.', Created Date: '.$tgl_setuju?>
			</p>
		</div>
		<?php
		}if($form_data['status'] == 19){
			?>
		<div class="note note-danger note-bordered">
			<p>
				 NOTE: <?=$form_data['keterangan_tolak'].'. Created By: '.$user_setuju->nama.', Created Date: '.$tgl_setuju?>
			</p>
		</div>
		<?php
		}if($form_data['keterangan_revisi'] != NULL && $form_data['keterangan_revisi'] != '' ){
			?>
		<div class="note note-danger note-bordered">
			<p>
				 NOTE: <?=$form_data['keterangan_revisi']?>
			</p>
		</div>
		<?php
		}

	?>
	<div class="row">
		<div class="col-md-3">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12"><?=translate("Tanggal Permintaan", $this->session->userdata("language"))?> :</label>
					<div class="col-md-12">
						<label class="control-label"><b><?=date('d M Y', strtotime($form_data['tanggal']))?></b></label>
						<input class="form-control" type="hidden" name="status_kasbon" id="status_kasbon" value="<?=$form_data['status']?>"></input>

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
							<label class="control-label"><b><?=$nama_user['nama']?></b></label>
						</div>
					</div>
				</div>

				<!-- <div class="form-group">
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
						<label><b><?=$tipe?></b></label>
						<input class="form-control" type="hidden" name="tipe" id="tipe" value="<?=$form_data['tipe']?>"></input>
					</div>
				</div> -->

				<div class="form-group">
					<label class="col-md-12"><?=translate("Nominal", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group">
							<label class="control-label"><b><?=formatrupiah($form_data['nominal'])?></b></label>
							<input class="form-control" type="hidden" name="nominal" id="nominal" value="<?=$form_data['nominal']?>"></input>
						</div>
					</div>
				</div>
				<?php
					if($form_data['status'] == 5 || $form_data['status'] == 18){
				?>
				<div class="form-group">								
					<div class="col-md-12">
						<label><?=translate('Jumlah Biaya', $this->session->userdata('language'))?> :</label>
					</div>
					<div class="col-md-12">
						<label><b><?=formatrupiah($form_data['nominal'] + $form_data['sisa'])?></b></label>
					</div>
				</div>
				<?php
					if($form_data['sisa'] > 0){
				?>
				<div class="form-group">								
					<div class="col-md-12">
						<label><?=translate('Selisih', $this->session->userdata('language'))?> :</label>
					</div>
					<div class="col-md-12">
						<label><b><?=formatrupiah($form_data['sisa'])?></b></label>
						<input class="form-control" type="hidden" name="sisa" id="sisa" value="<?=$form_data['sisa']?>"></input>

					</div>
				</div>

						<?php
						}
					}
				?>
				<div class="form-group">
					<label class="col-md-12"><?=translate("Terbilang", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group">
							<label class="control-label"><b><?='#'.terbilang($form_data['nominal']).' Rupiah#'?></b></label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12"><?=translate("Keperluan", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
							<label><b><?=$form_data['keperluan']?></b></label>
						<input class="form-control" type="hidden" name="keperluan" id="keperluan" value="<?=$form_data['keperluan']?>"></input>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-12"><?=translate("Tanggal Proses", $this->session->userdata("language"))?> :</label>
					<div class="col-md-12">
						<label class="control-label"><b><?=date('d M Y', strtotime($form_data['tanggal_proses']))?></b></label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-12"><?=translate("Diproses Oleh", $this->session->userdata("language"))?> :</label>
					<div class="col-md-12">
					<?php
						$user_proses = $this->user_m->get($form_data['diproses_oleh']);
					?>
						<label class="control-label"><b><?=$user_proses->nama?></b></label>
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
	                <div class="form-group">
						<label class="col-md-12"><?=translate("Disetujui Oleh", $this->session->userdata("language"))?> :</label>
						<div class="col-md-12">
						<?php
							$nama_setuju = 'System';
							if($form_data['disetujui_oleh'] != 0){
								$user_setuju = $this->user_m->get($form_data['disetujui_oleh']);
								$nama_setuju = $user_setuju->nama;
							}
						?>
							<label class="control-label"><?=$nama_setuju?></label>
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
		<div class="col-md-9">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject"><?=translate("Bon", $this->session->userdata("language"))?></span>
					</div>
				</div>
				<div class="form-body">
				    <div class="portlet-body">
						<?php

							$form_upload_bon = '';
							if(count($form_data_bon) != 0){
								foreach ($form_data_bon as $key => $bon) {

									$biaya = $this->biaya_m->get_by(array('id' => $bon['biaya_id']), true);

									$form_upload_bon .= '<tr>
									<td style="vertical-align: top !important;">'.$biaya->nama.'</td>
									<td><a class="fancybox-button" title="'.$bon['url'].'" href="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bon').$bon['permintaan_biaya_id'].'/'.$bon['url'].'" alt="Smiley face" class="img-responsive" ></a></td>
									<td style="vertical-align: top !important;">'.$bon['no_bon'].'</td>
									<td style="vertical-align: top !important;">'.date('d M Y', strtotime($bon['tgl_bon'])).'</td>
									<td style="vertical-align: top !important;">'.formatrupiah($bon['total_bon']).'</td>
									<td style="vertical-align: top !important;">'.$bon['keterangan'].'</td>
									</tr>';
								}	
							}
							
							?>

							<table class="table table-bordered table-hover">
								<thead>
								<tr role="row" class="heading">
									<th class="text-center" width="10%">
										 Biaya
									</th>
									<th class="text-center" width="8%">
								 		 Image
									</th>
									
									<th class="text-center" width="10%">
										 No. Bon
									</th>
									<th class="text-center" width="8%">
										 Tgl. Bon
									</th>
									<th class="text-center" width="10%">
										 Total Bon
									</th>
									<th class="text-center" width="25%">
										 Keterangan
									</th>
								</tr>
								</thead>
								<tbody>
									<?=$form_upload_bon?>
								</tbody>
							</table>        
						</div>
	                </div>
					
				</div>
			</div>
			
		</div>
		<!-- end of pilih item -->
	
	</div>

</div>


<?=form_close();?>

