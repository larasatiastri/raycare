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


?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-search font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("View Pengeluaran Kas Eksternal", $this->session->userdata("language"))?></span>
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
					<label class="col-md-12"><?=translate("Dikeluarkan Oleh", $this->session->userdata("language"))?> :</label>
					
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
				<div class="form-group">
					<label class="col-md-12"><?=translate("Keperluan", $this->session->userdata("language"))?> :</label>
					
					<div class="col-md-12">
						<div class="input-group">
							<label class="control-label"><?=$form_data['keperluan']?></label>
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
									$form_upload_bon .= '<tr>
									<td><a class="fancybox-button" title="'.$bon['url'].'" href="'.config_item('site_img_bon_eksternal').$bon['pengeluaran_kas_eksternal_id'].'/'.$bon['url'].'" data-rel="fancybox-button"><img src="'.config_item('site_img_bon_eksternal').$bon['pengeluaran_kas_eksternal_id'].'/'.$bon['url'].'" alt="Smiley face" class="img-responsive" ></a></td>
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
		<!-- end of pilih item -->
	
	</div>

</div>


<?=form_close();?>

