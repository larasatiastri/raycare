<?php
	$form_attr = array(
	    "id"            => "form_add_perpanjang", 
	    "name"          => "form_add_perpanjang", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."klinik_hd/surat_perpanjang_rujukan/save", $form_attr, $hidden);
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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Buat Surat Pengantar Perpanjang Rujukan', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat surat pengantar ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="<?=base_url()?>klinik_hd/surat_perpanjang_rujukan/history"><i class="fa fa-history"></i>  <?=translate("History", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Informasi', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
				<?php

					$id_pasien = '';
					$no_rekmed = '';
					$nama_pasien = '';
					if($pasien_id != null)
					{
						$id_pasien = $pasien_id;
						$data_pasien = $this->pasien_m->get($id_pasien);
						$no_rekmed = $data_pasien->no_member;
						$nama_pasien = $data_pasien->nama;

					}

				?>
				<div class="form-body" id="section-diagnosis">
					<div class="form-group">
							<label class="col-md-12 bold"><?=translate("No. Rekam Medis", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-12">
								<div class="input-group">
									<input class="form-control" id="no_rekmed" name="no_rekmed" value="<?=$no_rekmed?>" placeholder="<?=translate("No. Rekam Medis", $this->session->userdata("language"))?>" required>
									<span class="input-group-btn">
										<a class="btn grey-cascade pilih-pasien" title="<?translate('Pilih Pasien', $this->session->userdata('language'))?>">
											<i class="fa fa-search"></i>
										</a>
									</span>
								</div>
								<input class="form-control hidden" id="id_ref_pasien" name="id_ref_pasien" value="<?=$id_pasien?>" required placeholder="<?=translate("ID Referensi Pasien", $this->session->userdata("language"))?>">
							</div>	
						</div>
						<div class="form-group">
							<label class="col-md-12 bold"><?=translate("Pasien", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							
							<div class="col-md-12">
									<input class="form-control" id="nama_ref_pasien" name="nama_ref_pasien" value="<?=$nama_pasien?>" readonly  required placeholder="<?=translate("Nama Pasien", $this->session->userdata("language"))?>">
							</div>	
						</div>
				
						<div id="poliklinik_tujuan" class="form-group">
							<label class="col-md-12 bold"><?=translate("Kepada", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
							<div class="col-md-12">
								<input type="text" id="kepada" name="kepada" class="form-control" required></input>
							</div>
						</div>
						
				</div>
		</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
		<div class="col-md-8">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('KETERANGAN', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body">
						<div class="form-group">
							<div class="col-md-12">
								<div class="col-md-12">
									<textarea class="form-control" id="keterangan" name="keterangan" rows="14" placeholder="<?=translate("Keterangan", $this->session->userdata("language"))?>" required></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end of <div class="portlet light bordered"> -->
		</div><!-- end of <div class="col-md-8"> -->
	</div><!-- end of <div class="row"> -->

	</div>
</div>


<?=form_close()?>

<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No. RM', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tempat, Tanggal Lahir', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Alamat', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> 




