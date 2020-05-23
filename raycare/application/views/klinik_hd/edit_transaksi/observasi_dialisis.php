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
?>	
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-medkit font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Tindakan HD", $this->session->userdata("language"))?></span>
			<span class="caption-helper"><?php echo '<label class="control-label current_time">'.$form_tindakan['no_transaksi'].'</label>'; ?></span>
			<input class="form-control hidden" value="<?=$flag?>" id="flag_selesai">
		</div>
		<div class="actions">
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
				<i class="fa fa-chevron-left"></i>
				<?=translate('Kembali', $this->session->userdata('language'))?>
			</a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">	

			<input class="form-control hidden" id="pasienid" name="pasienid" value="<?=$form_tindakan['pasien_id']?>">
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
					<?=translate('Item', $this->session->userdata('language'))?> </a>
				</li>
				<li class="tab <?=$hidden?>">
					<a href="#paket" data-toggle="tab">
					<?=translate('Paket', $this->session->userdata('language'))?> </a>
				</li>
				<li class="tab <?=$hidden?>">
					<a href="#rekam_medis" data-toggle="tab">
					<?=translate('Rekam Medis', $this->session->userdata('language'))?> </a>
				</li>
				<li class="tab <?=$hidden?>">
					<a href="#data_pasien" data-toggle="tab">
					<?=translate('Data Pasien', $this->session->userdata('language'))?> </a>
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

				<div class="tab-pane" id="paket">
					<?php include("tab_perawat/paket.php"); ?>
				</div>

				<div class="tab-pane" id="rekam_medis">
					<?php include("tab_perawat/rekam_medis.php"); ?>
				</div>
				
				<div class="tab-pane" id="data_pasien">
					<?php include("tab_perawat/data.php"); ?>
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


