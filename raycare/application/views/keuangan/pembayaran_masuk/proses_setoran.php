<?php
	$form_attr = array(
	    "id"            => "form_proses_invoice", 
	    "name"          => "form_proses_invoice", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   	=> "proses_setoran",
        "id"			=> $pk_value,
        "setoran_id"	=> $setoran_id,
    );

    echo form_open(base_url()."keuangan/pembayaran_masuk/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>

<div class="form-body">
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('View Setoran', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat setoran ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
			<a class="btn btn-circle btn-primary" id="confirm_save" data-confirm="Anda yakin akan menerima setoran invoice ini?"><i class="fa fa-save"></i>  <?=translate("Terima", $this->session->userdata("language"))?></a>
			<button type="submit" id="save" class="btn btn-primary hidden" ><?=$submit_text?></button>

		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="portlet box blue-sharp">
				<div class="portlet-title" style="margin-bottom: 0px !important;">
					<div class="caption">
						<?=translate('Informasi', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<div class="form-group">
						    <label class="col-md-12 bold"><?=translate("Tanggal Setor", $this->session->userdata("language"))?> :</label>
						    <label class="col-md-12"><?=date('d M Y', strtotime($form_data['tanggal_setor']))?></label>
						    <input type="hidden" id="tanggal_setor" name="tanggal_setor" value="<?=date('d M Y', strtotime($form_data['tanggal_setor']))?>"></input>
		                	
		              	</div>
		              	<div class="form-group">
						    <label class="col-md-12 bold"><?=translate("Tanggal Tindakan", $this->session->userdata("language"))?> :</label>
						    <label class="col-md-12"><?=date('d M Y', strtotime($form_data['tanggal']))?></label>
						    <input type="hidden" id="tanggal" name="tanggal" value="<?=date('d M Y', strtotime($form_data['tanggal']))?>"></input>
		                	
		              	</div>
						
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Shift", $this->session->userdata("language"))?> :</label>
							<label class="col-md-12"><?=$form_data['shift']?></label>
						    <input type="hidden" id="tipe" name="tipe" value="<?=$form_data['shift']?>"></input>
						    <input type="hidden" id="bank_id" name="bank_id" value="<?=$form_data['bank_id']?>"></input>
							
						</div>

						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Jumlah Setoran", $this->session->userdata("language"))?> :</label>
							<label class="col-md-12"><?=formatrupiah($form_data['total'])?></label>
							<input type="hidden" id="total" name="total" value="<?=$form_data['total']?>"></input>
							
						</div>

						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Nomor Bukti Setoran", $this->session->userdata("language"))?> :</label>
							<label class="col-md-12"><?=$form_data['nomor_bukti_setor']?></label>
							
						</div>

						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Bukti Setor", $this->session->userdata("language"))?> <span>:</span></label>
							<div class="col-md-12">
								<input type="hidden" name="url_bukti_setor" id="url_bukti_setor" value="<?=$form_data['url_bukti_setor']?>">
								<div id="upload">

									<ul class="ul-img">
									<li class="working"><div class="thumbnail"><a class="fancybox-button" title="<?=$form_data['url_bukti_setor']?>" href="<?=config_item('base_dir')?>cloud/<?=config_item('site_dir')?>pages/kasir/setoran_kasir/images/<?=$form_data['id']?>/<?=$form_data['url_bukti_setor']?>" data-rel="fancybox-button"><img src="<?=config_item('base_dir')?>cloud/<?=config_item('site_dir')?>pages/kasir/setoran_kasir/images/<?=$form_data['id']?>/<?=$form_data['url_bukti_setor']?>" alt="Smiley face" class="img-thumbnail" style="max-width:200px; max-height:200px;"></a></div><span></span></li>
									</ul>

								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
							<div class="col-md-12">
								<textarea class="form-control" id="keterangan" name="keterangan" rows="6" cols="4" readonly="" value="<?=$form_data['keterangan']?>"><?=$form_data['keterangan']?></textarea> 
							</div>
						</div>

					</div>
				</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
		<div class="col-md-9">
			<div class="portlet box blue-sharp">
				<div class="portlet-title" style="margin-bottom: 0px !important;">
					<div class="caption">
						<?=translate('Detail Invoice', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="table-responsive">
                            <table class="table table-condensed table-striped table-hover" id="table_invoice">
                                <thead>
                                    <th class="text-center" width="15%"><?=translate("No Invoice", $this->session->userdata('language'))?></th>
                                    <th class="text-center" width="20%"><?=translate("Tanggal", $this->session->userdata('language'))?></th>
                                    <th class="text-center" width="20%"><?=translate("Pasien", $this->session->userdata('language'))?></th>
                                    <th class="text-center" width="15%"><?=translate("Total Invoice", $this->session->userdata('language'))?></th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div><!-- end of <div class="portlet light bordered"> -->
		</div><!-- end of <div class="col-md-8"> -->
		
	</div><!-- end of <div class="row"> -->

	</div>
</div>


<?=form_close()?>




