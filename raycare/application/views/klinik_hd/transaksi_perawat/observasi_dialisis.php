<?php
	$form_attr = array(
	    "id"            => "form_observasi_dialisis", 
	    "name"          => "form_observasi_dialisis", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
	);

	$hidden = array(
	    "id"		=> $pk_value,
	);

	// echo form_open(base_url()."klinik_hd/transaksi_perawat/", $form_attr, $hidden);
	// UTK KEBUTUHAN HIDE SHOW TAB PADA SAAT MEMILIH MENU OBSERVASI DAN SELESAI TINDAKAN
	$hidden    = '';
	$show      = 'hidden';
	$active    = 'active';
	$is_hidden = 'hidden';
	if ($flag) {
		$hidden    = 'hidden';
		$show      = 'active';
		$active    = '';
		$is_hidden = '';
	}

	$notif_obat = '';
	if($data_item_resep != 0){
		$notif_obat = "<span class='badge badge-warning' style='width:15px;height:15px'>!</span>";
	}

?>	
<div class="row">
	<div class="col-md-3">
		<div class="portlet light">
	<!-- ITEM TERSIMPAN -->
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Permintaan Obat & Alkes", $this->session->userdata("language"))?></span>
				</div>
				<div class="actions">
					<a class="btn btn-primary" id="tambah_obat_alkes" data-toggle="modal" data-target="#modal_minta_item" href="<?=base_url()?>klinik_hd/transaksi_perawat/modal_minta_item/<?=$form_tindakan['pasien_id']?>/<?=$form_tindakan['id']?>"><i class="fa fa-plus"></i></a>
					<a class="btn btn-primary hidden reload-table-minta" id="reload_table_minta" ><i class="fa fa-reload"></i></a>
				</div>
			</div>
			<div class="portlet-body">
				<div class="form-body">
					<table class="table table-striped table-hover table-bordered" id="table_item_permintaan">
						<thead>
							<tr>
		                        <!-- <th class="text-center" width="1%"><?=translate('Kode Item', $this->session->userdata('language'))?></th> -->
		                        <th class="text-center"><?=translate('Nama Item', $this->session->userdata('language'))?></th>
		                        <th class="text-center" width="1%"><?=translate('Jumlah', $this->session->userdata('language'))?></th>
		                        <th class="text-center" width="1%"><?=translate('Status', $this->session->userdata('language'))?></th>
		                    </tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-medkit font-blue-sharp"></i>
					<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan HD", $this->session->userdata("language"))?></span>
					<span class="caption-helper"><?php echo '<label class="control-label current_time" style="font-size:30px;color:red;"><b>'.$form_pasien['nama'].'</b></label>'; ?></span>
					<input class="form-control hidden" value="<?=$flag?>" id="flag_selesai">
				</div>
				<div class="actions">
					<a class="btn btn-circle btn-default kembali" data-bed="<?=$pk_value?>">
						<i class="fa fa-chevron-left"></i>
						<?=translate('Kembali', $this->session->userdata('language'))?>
					</a>
					<a class="btn btn-circle btn-primary <?=$show?>" id="confirm_save_selesaikan" data-toggle="modal" data-confirm="<?=translate('Apakah anda yakin akan menyelesaikan tindakan ini?', $this->session->userdata('language'))?>"><?=translate('Selesaikan Tindakan', $this->session->userdata('language'))?></a>
				</div>
			</div>
			<div class="portlet-body">
				<div class="form-body">	

					<input class="form-control hidden" id="pasienid" name="pasienid" value="<?=$form_tindakan['pasien_id']?>">
					<input class="form-control hidden" id="bed_id" name="bed_id" value="<?=$pk_value?>">
					<input class="form-control hidden" id="tindakan_hd_id" name="tindakan_hd_id" value="<?=$form_tindakan['id']?>">
					<input class="form-control hidden" id="tindakan_hd_penaksiran_id" name="tindakan_hd_penaksiran_id" value="<?=$form_assesment[0]['id']?>">


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
							<?=translate('Obat & Alkes', $this->session->userdata('language')).' '.$notif_obat?>  </a>
						</li>
						<li class="tab <?=$hidden?>">
							<a href="#tindakan_lain" data-toggle="tab">
							<?=translate('Tindakan Lain', $this->session->userdata('language'))?> </a>
						</li>
						<li class="tab <?=$hidden?>" id="list_hasil_lab">
							<a href="#hasil_lab" data-toggle="tab">
							<?=translate('Hasil Lab', $this->session->userdata('language'))?> </a>
						</li>
						<li class="tab <?=$hidden?>">
							<a href="#data_pasien" data-toggle="tab">
							<?=translate('Data Pasien', $this->session->userdata('language'))?> </a>
						</li>
						<li class="tab <?=$show?>">
							<a href="#selesai_tindakan" data-toggle="tab">
							<?=translate('Selesaikan Tindakan', $this->session->userdata('language'))?> </a>
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
							<?php include("tab_perawat/item2.php"); ?>
						</div>
						
						<div class="tab-pane" id="tindakan_lain">
							<?php include("tab_perawat/tindakan_lain.php"); ?>
						</div>

						<div class="tab-pane" id="hasil_lab">
							<?php include("tab_perawat/hasil_lab.php"); ?>
						</div>

						<div class="tab-pane" id="data_pasien">
							<?php include("tab_perawat/data.php"); ?>
						</div>

						<div class="tab-pane <?=$show?>" id="selesai_tindakan">
							<?php include("tab_perawat/selesaikan_tindakan.php"); ?>
						</div>

						

					</div>
				</div>		
			</div>
		</div>
	</div>
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

<div id="modal_identitas_paket" class="modal fade" >
    <form class="form-horizontal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
            </div>
        </div>
    </form>
</div>

<!-- GUNAKAN ITEM PERMINTAAN  -->
<div id="modal_minta_item" class="modal fade">
	<form class="form-horizontal" id="form_minta_item">
		<div class="modal-dialog">
			<div class="modal-content">
				
			</div>
		</div>
	</form>
</div>




<!-- SELESAIKAN TINDAKAN  -->
<div id="modal_selesai" class="modal fade" >
	<form class="form-horizontal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">
						<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("KONFIRMASI SIMPAN DATA", $this->session->userdata("language"))?></span>
					</h4>
				</div>
				<div class="modal-body">
					<label class="control-label">Apakah anda yakin akan menyelesaikan tindakan ini ?</label>
					<div class="form-group" style="margin-top:10px;">
						<label class="control-label col-md-3" style="text-align:left;"><?=translate('Berat Badan Akhir', $this->session->userdata('language'))?> :</label>
						<div class="row">
							<div class="col-md-2">
								<input class="form-control" id="berat_akhir" name="berat_akhir"> 
							</div>
							<div class="col-md-3">
								<label class="control-label">Kg</label> 
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


