<?php
	$form_attr = array(
	    "id"            => "form_observasi_dialisis5", 
	    "name"          => "form_observasi_dialisis5", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
	);

	// $hidden = array(
	//     "id"		=> $pk_value,
	// );

	// echo form_open(base_url()."klinik_hd/transaksi_perawat/", $form_attr, $hidden);

	// UTK KEBUTUHAN HIDE SHOW TAB PADA SAAT MEMILIH MENU OBSERVASI DAN SELESAI TINDAKAN
	$hidden = '';
	$show = 'hidden';
	$active = 'active';
	if ($flag) {
		$hidden = 'hidden';
		$show = 'active';
		$active = '';
	}
?>	

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan HD", $this->session->userdata("language"))?> - <?=$form_tindakan['no_transaksi']?></span>
			<input class="form-control hidden" value="<?=$flag?>" id="flag_selesai">
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-default kembali" data-toggle="modal" data-target="#modal_end_visit" href="<?=base_url()?>klinik_hd/transaksi_dokter/modal_end_visit/<?=$bed_id?>/<?=$id_tindakan_visit?>">
				<i class="fa fa-chevron-left"></i>
				<span class="hidden-480"><?=translate('Kembali', $this->session->userdata('language'))?></span>
			</a>
			<a class="btn btn-primary <?=$show?>" href="#modal_selesai" data-toggle="modal"><?=translate('Selesaikan Tindakan', $this->session->userdata('language'))?></a>
		</div>
	</div>
	<input type="hidden" id="flagjs" name="flagjs" value="0">
	<input class="form-control hidden" id="pasienid" name="pasienid" value="<?=$form_tindakan['pasien_id']?>">
	<input class="form-control hidden" id="tindakan_hd_id" name="tindakan_hd_id" value="<?=$form_tindakan['id']?>">
	<input class="form-control hidden" id="tindakan_hd_visit_id" name="tindakan_hd_visit_id" value="<?=$id_tindakan_visit?>">
	<input class="form-control hidden" id="bed_id" name="bed_id" value="<?=$bed_id?>">
	<div class="portlet-body form">
		<div class="form-body">
			<div class="row">
		    	<div class="col-md-5">
		    		<div class="row">
		    			
		    			<div class="col-md-9">
		    				<div class="form-group">
		    					 
									<label class="control-label col-md-4"><?=translate("No.Transaksi", $this->session->userdata("language"))?> :</label>
									<div class="col-md-8">
						 				<label class="control-label"><?=$form_data_kiri[0]['no_transaksi']?></label>
									</div>
							</div>

							<div class="form-group">
						 
								<label class="control-label col-md-4"><?=translate("Dialisis Terakhir", $this->session->userdata("language"))?> :</label>
					 
									<div class="col-md-8">
						 				<label class="control-label"><?=date('d M Y',strtotime($form_data_kiri[0]['tanggal']))?></label>
									</div>
							</div>


							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Berat Awal", $this->session->userdata("language"))?> :</label>
							 
								<div class="col-md-8">
								 	<label class="control-label"><?=$form_data_kanan[0]['berat_awal']?></label>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Berat Akhir", $this->session->userdata("language"))?> :</label>
							 
								<div class="col-md-8">
								 	<label class="control-label"><?=$form_data_kanan[0]['berat_akhir']?></label>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-4"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
							 
								<div class="col-md-8">
								 	<label class="control-label"><?=$form_data_kanan[0]['nama']?></label>
								</div>
							</div>

							<div class="form-group">
						 
								<label class="control-label col-md-4"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
					 
									<div class="col-md-8">
						 				<label class="control-label"><?=$form_data_kiri[0]['keterangan']?></label>
									</div>
							</div>
		    			</div>
		    		</div>
		    		

					
		    	</div>

		    	<div class="col-md-5">

		    		<div class="form-group">
		    			 
							<label class="control-label col-md-3"><?=translate("No.Pasien", $this->session->userdata("language"))?> :</label>
			 
							<div class="col-md-8">
				 				<label class="control-label"><?=$form_data_kiri[0]['no_member']?></label>
							</div>
				 
					</div>

					<div class="form-group">
				 
							<label class="control-label col-md-3"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
			 
							<div class="col-md-8">
				 				<label class="control-label"><?=$form_data_kiri[0]['nama']?></label>
							</div>
					</div>


		    		
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Rujukan", $this->session->userdata("language"))?> :</label>
					 
						<div class="col-md-9">
						 	<label class="control-label"><?=$form_data_kanan[0]['nama_poli']?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("No.Bed", $this->session->userdata("language"))?> :</label>
					 
						<div class="col-md-9">
						 	<label class="control-label"><?=$form_data_kanan[0]['kode']?></label>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?=translate("Klaim", $this->session->userdata("language"))?> :</label>
					 
						<div class="col-md-9">
						 	<label class="control-label"><?=$form_data_kanan[0]['nama_penjamin']?></label>
						</div>
					</div>
		    	</div>
		    	<?php
				$url = array();
	            if ($form_data_kiri[0]['url_photo'] != '') 
	            {
	                $url = explode('/', $form_data_kiri[0]['url_photo']);
	                // die(dump($row['url_photo']));
	                if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').'foto/'.$form_data_kiri[0]['url_photo']) && is_file($_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').'foto/'.$form_data_kiri[0]['url_photo'])) 
	                {
	                    $img_url = $_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').'foto/'.$form_data_kiri[0]['url_photo'];
	                }
	                else
	                {
	                    $img_url = $_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').'global/global.png';
	                }
	            } else {

	                $img_url = $_SERVER['DOCUMENT_ROOT'].'/'.config_item('site_img_pasien').'global/global.png';
	            }

			?>
		    	<div class="col-md-2">
    				<div class="img">
    					<img class="img-thumbnail" src="<?=$img_url?>" style="max-width:120px">
    				</div>
    			</div>
			</div>
		</div>
	</div>	
</div>
<div class="portlet light">
	<div class="portlet-body tabbable-line">
		<ul class="nav nav-tabs">
			<li class="<?=$active?> tab <?=$hidden?>">
				<a href="#assesment" data-toggle="tab">
				<?=translate('Assesment', $this->session->userdata('language'))?> </a>
			</li>
			<li class="tab <?=$hidden?>">
				<a href="#supervising" data-toggle="tab">
				<?=translate('Supervising', $this->session->userdata('language'))?> </a>
			</li>
			<li class="tab <?=$hidden?>">
				<a href="#monitoring_dialisis" data-toggle="tab">
				<?=translate('Monitoring Dialisis', $this->session->userdata('language'))?> </a>
			</li>
			<li class="tab <?=$hidden?>">
				<a href="#examination" data-toggle="tab">
				<?=translate('Examination Support', $this->session->userdata('language'))?> </a>
			</li>
			<li class="tab <?=$hidden?>">
				<a href="#item" data-toggle="tab">
				<?=translate('Item', $this->session->userdata('language'))?> </a>
			</li>
			<li>
                <a href="#buatresep" data-toggle="tab" >
                <?=translate('Buat Resep', $this->session->userdata('language'))?> </a>
            </li>
			<li>
                <a href="#tindakan_lain" data-toggle="tab" >
                <?=translate('Tindakan Lain', $this->session->userdata('language'))?> </a>
            </li>
			
			<li class="tab <?=$hidden?>" id="list_hasil_lab">
				<a href="#hasil_lab" data-toggle="tab">
				<?=translate('Hasil Lab', $this->session->userdata('language'))?> </a>
			</li>
			<li class="tab <?=$hidden?>">
				<a href="#rekam_medis" data-toggle="tab">
				<?=translate('Rekam Medis', $this->session->userdata('language'))?> </a>
			</li>
			<li class="tab <?=$hidden?>">
				<a href="#data_pasien" data-toggle="tab">
				<?=translate('Data Pasien', $this->session->userdata('language'))?> </a>
			</li>
			<li class="tab <?=$show?>">
				<a href="#simpan_item" data-toggle="tab">
				<?=translate('Item Digunakan', $this->session->userdata('language'))?> </a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane <?=$active?>" id="assesment">
				<?php include("tab_perawat/assesment.php"); ?>
			</div>

			<div class="tab-pane" id="supervising">
				<?php include("tab_perawat/supervising.php"); ?>
			</div>

			<div class="tab-pane" id="monitoring_dialisis">
				<?php include("tab_perawat/monitoring_dialisis.php"); ?>
			</div>

			<div class="tab-pane" id="examination">
				<?php include("tab_perawat/examination.php"); ?>
			</div>

			<div class="tab-pane" id="item">
				<?php include("tab_perawat/item.php"); ?>
			</div>
			
		    <div class="tab-pane" id="buatresep">
			    <?php include('tab_transaksi_dokter/resep.php') ?>
		    </div>
		    <div class="tab-pane" id="tindakan_lain">
			    <?php include('tab_transaksi_dokter/tindakan_lain_visit.php') ?>
		    </div>
            
			

			<div class="tab-pane" id="hasil_lab">
				<?php include("tab_perawat/hasil_lab.php"); ?>
			</div>

			<div class="tab-pane" id="rekam_medis">
				<?php include("tab_perawat/rekam_medis.php"); ?>
			</div>
			
			<div class="tab-pane" id="data_pasien">
				<?php include("tab_perawat/data.php"); ?>
			</div>

			<div class="tab-pane <?=$show?>" id="simpan_item">
				<?php include("tab_perawat/simpan_item.php"); ?>
			</div>

		</div>
	</div>
</div>
<?=form_close()?>


<!-- EDIT MONITORING DIALISIS -->
<div id="modal_dialisis" class="modal fade">
	<form class="form-horizontal">
		<div class="modal-dialog">
			<div class="modal-content">
				
			</div>
		</div>
	</form>
</div>

<!-- TAMBAH  MONITORING DIALISIS -->
<div id="modal_dialisis_add" class="modal fade">
	<form class="form-horizontal">
		<div class="modal-dialog">
			<div class="modal-content">
				
			</div>
		</div>
	</form>
</div>


<!-- SISA ITEM TERSIMPAN  -->
<div id="modal_item_tersimpan" class="modal fade">
	<form class="form-horizontal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Gunakan Item Tersimpan", $this->session->userdata("language"))?></span>
					</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-4"><?=translate('Gunakan Item', $this->session->userdata('language'))?> :</label>
						<div class="row">
							<div class="col-md-2">
								<input class="form-control" id="gunakan_item" name="gunakan_item" type="number">
							</div>
							<div class="col-md-3">
								<?php 
									$options = array(
										''	=> 'Pilih Satuan',
										'1'	=> 'Dus',
										'2'	=> 'Kotak',
										'3'	=> 'Strip',
										'4'	=> 'Tablet',
									);
									echo form_dropdown('satuan', $options, '', "id=\"satuan\" class=\"form-control\" ");
								 ?>
							</div>
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<a class="btn default" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
					<a class="btn btn-primary" data-dismiss="modal" id=""><?=translate('OK', $this->session->userdata('language'))?></a>
				</div>
			</div>
		</div>
	</form>
</div>

<!--TAMBAH PAKET  -->
<?php 
	$btn_del    = '<div class="text-center"><button class="btn btn-sm red-intense del-this3" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';
    $item_cols = array(
	    'item_code'   => '<input type="hidden" id="paket_id_{0}" name="paket[{0}][idpaket]"><input type="hidden" id="paket_harga_{0}" name="paket[{0}][harga]"><input type="text" id="paket_nama_{0}" name="paket[{0}][namapaket]" class="form-control" readonly style="background-color: transparent;border: 0px solid;">',
	    'action'      => $btn_del,
	);

	// gabungkan $item_cols jadi string table row
	$item_row_template3 =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
?>

<div id="modal_end_visit" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form class="form-horizontal">
		<div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
		<div class="modal-dialog">
			<div class="modal-content">
				 
			</div>
		</div>
	</form>
</div>

<div id="modal_paket" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form class="form-horizontal">
		<div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
		<div class="modal-dialog" id="modaldialogpaket">
			<div class="modal-content" id="modpaket">
				 
			</div>
		</div>
	</form>
</div>

<div id="modal_viewpaket bs-modal-lg" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form class="form-horizontal">
		<div class="page-loading page-loading-boxed">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				 
			</div>
		</div>
	</form>
</div>


<!-- GUNAKAN ITEM DILUAR PAKET  -->
<div id="modal_item_diluar" class="modal fade">
	<form class="form-horizontal">
		<div class="modal-dialog">
			
			</div>
		</div>
	</form>
</div>

	<!-- JUMLAH INVENTORY IDENTITAS  -->
<div id="modal_identitas_paket" class="modal fade">
    <form class="form-horizontal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
            </div>
        </div>
    </form>
</div>

<div id="popover_item_content_transfusi" style="display:none">
    <div class="portlet-body form">
        <div class="form-body">
            <div class="portlet-body form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light" id="section-alamat">
                            <div class="portlet-body">

                                <table class="table table-striped table-bordered table-hover" id="table_obat_transfusi">
                                <thead>
                                <tr role="row" class="heading">
                                        <th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?> </th>
                                        <th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
                                        <th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
                                         
                                        <th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
                                     
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
    </div>
</div>


