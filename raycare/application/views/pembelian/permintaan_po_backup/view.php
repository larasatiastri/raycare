<?php

    //////////////////////////////////////////////////////////////////////////////////////

    $this->item_satuan_m->set_columns(array('id','nama'));
    $categories = $this->item_satuan_m->get();
    // die_dump($categories);
    $categories_satuan = array(
    
    '' => translate('Pilih Satuan', $this->session->userdata('language')) . '..',
    );

    $categories_supplier = array(
    
    '' => translate('Pilih Supplier', $this->session->userdata('language')) . '..',
    );


    //////////////////////////////////////////////////////////////////////////////////////

	$form_attr = array(
		"id"			=> "form_add_permintaan_po", 
		"name"			=> "form_add_permintaan_po", 
		"autocomplete"	=> "off", 
		"class"			=> "form-horizontal",
		
	);

	$hidden = array(
		"command"	=> "add"
	);


	echo form_open(base_url()."pembelian/permintaan_po/save", $form_attr,$hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');


?>	
<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
				<i class="fa fa-search font-blue-sharp"></i>
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("View Permintaan Barang", $this->session->userdata("language"))?></span>
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
				</div>
				<div class="form-wizard">
					<div class="row">
						<div class="col-md-3">
							<div class="portlet box blue-sharp">
								<div class="portlet-title" style="margin-bottom: 0px !important;">
									<div class="caption">
										<span class="caption-subject"><?=translate("Informasi", $this->session->userdata("language"))?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
											 	<label class="col-md-12 bold"><?=translate('Tanggal', $this->session->userdata('language'))?> :</label>
											 	<label class="col-md-12"><?=date('d M Y', strtotime($form_data['tanggal']))?></label>
												
											</div>
										</div>
										<div class="col-md-6">
											<?php
												$user = $this->user_m->get($form_data['user_id']);
												$user_level = $this->user_level_m->get($form_data['user_level_id']);
												// die_dump($this->db->last_query());
											?>
											<div class="form-group">
												<label class="col-md-12 bold"><?=translate('Ditujukan Ke', $this->session->userdata('language'))?> :</label>
												<label class="col-md-12"><?=$user->nama.' ['.$user_level->nama.']'?></label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<?php
												$tipe = ($form_data['tipe'] == 1)?translate('Terdaftar', $this->session->userdata('language')):translate('Tidak Terdaftar', $this->session->userdata('language'));
												if($form_data['tipe'] == 2)
												{
													$hidden_trdaftar = 'hidden';
													$hidden_tdk_trdaftar = '';
												}
												else
												{
													$hidden_trdaftar = '';
													$hidden_tdk_trdaftar = 'hidden';

												}
												
												
											?>
											<div class="form-group">
								              	<label class="col-md-12 bold"><?=translate('Tipe Permintaan', $this->session->userdata('language'))?> :</label>
												<label class="col-md-12"><?=$tipe?></label>
							              	</div>
										</div>
										<div class="col-md-6">
											
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Subjek", $this->session->userdata("language"))?> :</label>		
										<label class="col-md-12"><?=$form_data['subjek']?></label>
									</div>
									<div class="form-group">
										<label class="col-md-12 bold"><?=translate("Keterangan :", $this->session->userdata("language"))?></label>
										<label class="col-md-12"><?=($form_data['keterangan'] != '')?$form_data['keterangan']:'-'?></label>
										
									</div>
								</div>
								 
								
								
								
							</div>
						</div>
						<div class="col-md-9 <?=$hidden_trdaftar?>" id="section_terdaftar">
							<div class="portlet box blue-sharp">
								<div class="portlet-title" style="margin-bottom: 0px !important;">
									<div class="caption">
										<span class="caption-subject"><?=translate("Order Deskripsi", $this->session->userdata("language"))?></span>
									</div>
								</div>
							    <div class="portlet-body">
	                                <div class="table-scrollable">
	                                    <table class="table table-striped table-hover" id="tabel_detail_permintaan">
	                                        <thead>
	                                            <tr role="row">
	                                                <th width="7%"><div class="text-center"><?=translate("Kode", $this->session->userdata('language'))?></div></th>
	                                                <th width="15%"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Jumlah Pesan", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Jumlah Setuju", $this->session->userdata('language'))?></div></th>
	                                                <th width="8%"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
	                                                <th width="7%"><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Supplier", $this->session->userdata('language'))?></div></th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                          	<?php
	                                          		if($form_data_detail != '')
	                                          		{
	                                          			foreach ($form_data_detail as $detail) 
	                                          			{
	                                          				$supplier = '-';
	                                          				if($detail['kode_supp'] != ''){
	                                          					$supplier = $detail['nama_supplier'].' ['.$detail['kode_supp'].']';
	                                          				}

	                                          				$harga_ref = 0;
	                                          				if($detail['harga_ref'] != '')
	                                          				{
	                                          					$harga_ref = $detail['harga_ref'];
	                                          				}
	                                          				echo '
	                                          				<tr>
	                                          					<td>'.$detail['kode'].'</td>
	                                          					<td>'.$detail['nama'].'</td>
	                                          					<td>'.$detail['jumlah'].'</td>
	                                          					<td><a class="info" data-id="'.$detail['id'].'" data-permintaan_id="'.$detail['order_permintaan_barang_id'].'" data-user_level_id="'.$this->session->userdata('level_id').'" data-order="1" >'.$detail['jumlah_disetujui'].'</a></td>
	                                          					<td>'.$detail['nama_satuan'].'</td>
	                                          					<td class="text-right">'.formatrupiah($harga_ref).'</td>
	                                          					<td>'.$supplier.'</td>

	                                          				</tr>';		                                          				
	                                          			}
	                                          		}
	                                          	?>
	                                            
	                                        </tbody>
	                                    </table>
	                                </div>
	                            </div>
							</div>
						</div>
						<!-- end of pilih item -->

						<div class="col-md-9 <?=$hidden_tdk_trdaftar?>" id="section_tidak_terdaftar">
							<div class="portlet box blue-sharp">
								<div class="portlet-title" style="margin-bottom: 0px !important;">
									<div class="caption">
										<span class="caption-subject"><?=translate("Detail Item", $this->session->userdata("language"))?></span>
									</div>
									
								</div>
								<div class="portlet-body">
	                                <div class="table-scrollable">
	                                    <table class="table table-striped table-hover" id="table_add_item_titipan">
	                                        <thead>
	                                            <tr role="row">
	                                                <th width="25%"><div class="text-center"><?=translate("Nama", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Jumlah Pesan", $this->session->userdata('language'))?></div></th>
	                                                <th width="5%"><div class="text-center"><?=translate("Jumlah Setujui", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Satuan", $this->session->userdata('language'))?></div></th>
	                                                <th width="7%"><div class="text-center"><?=translate("Harga", $this->session->userdata('language'))?></div></th>
	                                                <th width="10%"><div class="text-center"><?=translate("Supplier", $this->session->userdata('language'))?></div></th>
	                                                <th width="1%"><div class="text-center"><?=translate("Aksi", $this->session->userdata('language'))?></div></th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                          	<?php
	                                          		if($form_data_detail_other != '')
	                                          		{
	                                          			foreach ($form_data_detail_other as $detail) 
	                                          			{
	                                          				$link_pdf = '';
												            $data_pdf = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $detail['id'], 'tipe' => 1 ), true);

												            $link_img = '';
												            $data_img = $this->o_p_p_d_o_item_file_m->get_by(array('order_permintaan_pembelian_detail_other_id' => $detail['id'], 'tipe' => 2 ), true);


												            if(count($data_pdf))
												            {
												                $link_pdf = '<a target="_blank" href="'.base_url().'assets/mb/pages/pembelian/permintaan_po/doc/'.str_replace(' ', '_', strtolower($detail['nama'])).'/'.$data_pdf->url.'" class="btn grey-cascade unggah-file" name="item2[{0}][file]" title="Lihat File"><i class="fa fa-file"></i></a>';
												            }
												            if(count($data_img))
												            {
												            	$link_img = '<button type="button" data-toggle="modal" data-target="#popup_modal" href="'.base_url().'pembelian/permintaan_po/lihat_gambar/'.$detail['id'].'" class="btn blue-chambray unggah-gambar name="item2[{0}][gambar]" title="Lihat Gambar"><i class="fa fa-image"></i></button>';
												            }

												            $action     = $link_pdf.$link_img;
												            $harga_ref = ($detail['harga_ref'] == '')?0:$detail['harga_ref'];

	                                          				echo '
	                                          				<tr>
	                                          					<td>'.$detail['nama'].'</td>
	                                          					<td>'.$detail['jumlah'].'</td>
	                                          					<td><a class="info" data-id="'.$detail['id'].'" data-permintaan_id="'.$detail['order_permintaan_barang_id'].'" data-user_level_id="'.$this->session->userdata('level_id').'" data-order="1" >'.$detail['jumlah_disetujui'].'</a></td>
	                                          					<td>'.$detail['satuan'].'</td>
	                                          					<td class="text-right">'.formatrupiah($harga_ref).'</td>
	                                          					<td>'.$detail['supplier'].'</td>
	                                          					<td>'.$action.'</td>
	                                          				</tr>';		                                          				
	                                          			}
	                                          		}
	                                          	?>
	                                            
	                                        </tbody>
	                                    </table>
	                                </div>
	                            </div>
							</div>
						</div>
							
					</div>
				</div>
				<div class="form-actions right">    
			        <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
			        	<i class="fa fa-chevron-left"></i>
			        	<?=translate('Kembali', $this->session->userdata('language'))?>
			        </a>
				</div>
			</div>

</div>

<?=form_close();?>

<div id="popover_item_content" class="row" style="display:none;">
    <div class="col-md-12">
		<div class="portlet">
			<div class="portlet-body">
		        <table class="table table-condensed table-striped table-hover" id="table_user_persetujuan">
		            <thead>
		                <tr>
		                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
		                    <th width="15%" style="width: 100px;"><div class="text-center"><?=translate('User Level.', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Order', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Status', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal Baca', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Dibaca Oleh', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Tanggal Persetujuan', $this->session->userdata('language'))?></div></th>
		                    <th><div class="text-center"><?=translate('Disetujui Oleh', $this->session->userdata('language'))?></div></th>
		                    <th widht="5%"><div class="text-center"><?=translate('Jumlah Persetujuan', $this->session->userdata('language'))?></div></th>
		                </tr>
		            </thead>
		            <tbody>
		            </tbody>
		        </table>
		    </div>
		</div>
    </div>
</div>

<div class="modal fade bs-modal-sm" id="popup_modal" role="basic" aria-hidden="true" style="">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-sm">
       <div class="modal-content">

       </div>
   </div>
</div>

<div class="modal fade bs-modal-sm" id="popup_modal_file" role="basic" aria-hidden="true" style="">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-sm">
       <div class="modal-content">
       
       </div>
   </div>
</div>