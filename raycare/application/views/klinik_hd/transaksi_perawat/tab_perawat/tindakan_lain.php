<?php
	if(count($pasien_tindakan_transfusi)){

	$tindakan_transfusi = $this->tindakan_transfusi_m->get_by(array('id' => $pasien_tindakan_transfusi['tindakan_id']), true);
	$tindakan_transfusi = object_to_array($tindakan_transfusi);
?>
<!-- AWAL TINDAKAN TRANSFUSI -->
	<div class="portlet light bordered">
<!-- ITEM TERSIMPAN -->
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-user-md font-blue-sharp"></i>
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan Transfusi", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
			<div class="btn-group">
	            <?php	
	            	$option_status_transfusi = array(
	            		'1'  => translate('Menunggu Diambil Sample', $this->session->userdata('language')),
	            		'2'  => translate('Sudah Diambil Sample', $this->session->userdata('language')),
	            		'3'  => translate('Menunggu Tranfusi', $this->session->userdata('language')),
	            		'4'  => translate('Sudah Ditranfusi', $this->session->userdata('language')),
	            	);
	            ?>
            	<div class="input-group ">
	            <?php
	            	echo form_dropdown('status_transfusi', $option_status_transfusi, $tindakan_transfusi['status'], 'id="status_transfusi" class="form-control input-sm"');
	            ?>
            		<span class="input-group-btn">
						<a class="btn btn-primary save_status_transfusi" disabled>
							<i class="fa fa-check"></i>
						</a>
					</span>
            	</div>
				
			</div>
				
			</div>
		</div>
		<div class="portlet-body">

		<div class="form-group hidden">
			<label class="control-label col-md-4"><?=translate("ID Transfusi :", $this->session->userdata("language"))?></label>
			<div class="col-md-5">
				<input class="form-control" id="id_transfusi" name="id_transfusi" value="<?=$tindakan_transfusi['id']?>">
				<input class="form-control" id="kantong_darah" name="kantong_darah" value="<?=$tindakan_transfusi['jumlah_kantong_darah']?>">
			</div>     	
		</div>
		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Tanggal :", $this->session->userdata("language"))?></label>
			<div class="col-md-5">
				<label class="control-label"><?=date('d M Y', strtotime($tindakan_transfusi['tanggal']))?></label>
			</div>     	
		</div>
		<div class="form-group">
			<label class="control-label col-md-4"><?=translate("Jumlah :", $this->session->userdata("language"))?></label>
			<div class="col-md-5">
			<label class="control-label"><?=$tindakan_transfusi['jumlah_kantong_darah']?> Kantong Darah</label>
			</div>   	
		</div>

			<div class="portlet light">
				<!-- ITEM TERSIMPAN -->
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Obat & Alkes Tindakan Transfusi", $this->session->userdata("language"))?></span>
					</div>
					<div class="actions">
						<a class="btn btn-primary reload-table" id="reload-table-resep-transfusi">Reload</a>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<table class="table table-striped table-hover table-bordered" id="table_item_transfusi">
							<thead>
								<tr>
			                        <th class="text-center"><?=translate('Kode Item', $this->session->userdata('language'))?></th>
			                        <th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
			                        <th class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
			                        <th class="text-center"><?=translate('Batch Number', $this->session->userdata('language'))?></th>
			                        <th class="text-center"><?=translate('Expire Date', $this->session->userdata('language'))?></th>
			                        <th class="text-center"><?=translate('Dibuat Oleh', $this->session->userdata('language'))?></th>
			                        <th class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></th>
			                    </tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="portlet light">
				<!-- ITEM TELAH DIGUNAKAN -->
				<div class="portlet-title">
					<div class="caption"> 
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Obat & Alkes Transfusi Telah Digunakan", $this->session->userdata("language"))?></span>
					</div>
					<div class="actions hidden">
						
						<?php
			    			$user_level_id = $this->session->userdata('level_id');
			        		
			        		$data = '<a class="btn btn-circle btn-default" data-backdrop="static" data-keyboard="false" data-target="#modal_item_diluar" data-toggle="modal" href="'.base_url().'klinik_hd/transaksi_perawat/modal_item_diluar_paket/'.$form_tindakan['id'].'/'.$pk_value.'"><i class="fa fa-plus"></i> '.translate("Gunakan Item Diluar Paket", $this->session->userdata("language")).'</a>';
			        		echo restriction_button($data, $user_level_id, 'klinik_hd_transaksi_perawat', 'gunakan_item_diluar');
			            ?>
						<a class="btn btn-primary reload-table2" id="reload-table-digunakan"><i class="fa fa-undo"></i></a>

					</div>
					
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<table class="table table-striped table-hover table-bordered" id="table_item_transfusi_telah_digunakan">
							<thead>
								<tr>
									<th class="text-center" width="5%"><?=translate('Waktu Pemberian', $this->session->userdata('language'))?></th>
									<th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
									<th class="text-center" width="25%"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
									<th class="text-center" width="25%"><?=translate('Batch Number', $this->session->userdata('language'))?></th>
									<th class="text-center" width="25%"><?=translate('Expire Date', $this->session->userdata('language'))?></th>
									<th class="text-center" width="25%" ><?=translate('Diberikan Oleh', $this->session->userdata('language'))?></th>
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

<!-- AKHIR TINDAKAN TRANSFUSI -->
<?php }

if(count($pasien_tindakan_cek_lab)){ ?>

<!-- AWAL TINDAKAN LAB -->
	<div class="portlet light bordered">
<!-- ITEM TERSIMPAN -->
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-user-md font-blue-sharp"></i>
				<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan LAB", $this->session->userdata("language"))?></span>
			</div>
			<div class="actions">
			<div class="btn-group">


	            <?php
	            	$option_status_cek_lab = array(
	            		'1'  => translate('Menunggu Diambil Sample', $this->session->userdata('language')),
	            		'2'  => translate('Sudah Diambil Sample', $this->session->userdata('language')),
	            		'3'  => translate('Menunggu Tranfusi', $this->session->userdata('language')),
	            		'4'  => translate('Sudah Ditranfusi', $this->session->userdata('language')),
	            	);

	            	echo form_dropdown('status_cek_lab', $option_status_cek_lab, '', 'id="status_cek_lab" class="form-control input-sm"');
	            ?>
				
			</div>
				
			</div>
		</div>
		<div class="portlet-body">
					<div class="form-body">
						<table class="table table-striped table-hover table-bordered" id="table_detail_cek">
							<thead>
								<tr>
			                        <th class="text-center"><?=translate('Tindakan', $this->session->userdata('language'))?></th>
			                        <th class="text-center"><?=translate('Status', $this->session->userdata('language'))?></th>
			                        <th class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></th>
			                    </tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>

			<div class="portlet light">
				<!-- ITEM TERSIMPAN -->
				<div class="portlet-title">
					<div class="caption">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Obat & Alkes Resep Dokter", $this->session->userdata("language"))?></span>
					</div>
					<div class="actions">
						<a class="btn btn-primary reload-table" id="reload-table-resep">Reload</a>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<table class="table table-striped table-hover table-bordered" id="table_item_search">
							<thead>
								<tr>
			                        <th class="text-center"><?=translate('Kode Item', $this->session->userdata('language'))?></th>
			                        <th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
			                        <th class="text-center"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
			                        <th class="text-center"><?=translate('Batch Number', $this->session->userdata('language'))?></th>
			                        <th class="text-center"><?=translate('Expire Date', $this->session->userdata('language'))?></th>
			                        <th class="text-center"><?=translate('Dibuat Oleh', $this->session->userdata('language'))?></th>
			                        <th class="text-center" width="1%"><?=translate('Aksi', $this->session->userdata('language'))?></th>
			                    </tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="portlet light">
				<!-- ITEM TELAH DIGUNAKAN -->
				<div class="portlet-title">
					<div class="caption"> 
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Obat & Alkes Telah Digunakan", $this->session->userdata("language"))?></span>
					</div>
					<div class="actions hidden">
						
						<?php
			    			$user_level_id = $this->session->userdata('level_id');
			        		
			        		$data = '<a class="btn btn-circle btn-default" data-backdrop="static" data-keyboard="false" data-target="#modal_item_diluar" data-toggle="modal" href="'.base_url().'klinik_hd/transaksi_perawat/modal_item_diluar_paket/'.$form_tindakan['id'].'/'.$pk_value.'"><i class="fa fa-plus"></i> '.translate("Gunakan Item Diluar Paket", $this->session->userdata("language")).'</a>';
			        		echo restriction_button($data, $user_level_id, 'klinik_hd_transaksi_perawat', 'gunakan_item_diluar');
			            ?>
						<a class="btn btn-primary reload-table2" id="reload-table-digunakan"><i class="fa fa-undo"></i></a>

					</div>
					
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<table class="table table-striped table-hover table-bordered" id="table_item_lab_telah_digunakan">
							<thead>
								<tr>
									<th class="text-center" width="5%"><?=translate('Waktu Pemberian', $this->session->userdata('language'))?></th>
									<th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
									<th class="text-center" width="25%"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
									<th class="text-center" width="25%"><?=translate('Batch Number', $this->session->userdata('language'))?></th>
									<th class="text-center" width="25%"><?=translate('Expire Date', $this->session->userdata('language'))?></th>
									<th class="text-center" width="25%" ><?=translate('Diberikan Oleh', $this->session->userdata('language'))?></th>
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

<!-- AKHIR TINDAKAN LAB -->

<?php } ?>

<!-- GUNAKAN ITEM DALAM BOX PAKET  -->
<div id="modal_tindakan_lain" class="modal fade">
	<form class="form-horizontal" id="form_tindakan_lain">
		<div class="modal-dialog">
			<div class="modal-content">
				
			</div>
		</div>
	</form>
</div>	
