<?php
	$data_tindakan = $this->tindakan_hd_m->get_by(array('id' => $tindakan_hd_id));
	$tindakan = object_to_array($data_tindakan);

	$data_sep_tindakan = $this->sep_tindakan_m->get_by(array('tindakan_id' => $tindakan_hd_id));
	$sep_tindakan = object_to_array($data_sep_tindakan);

	$data_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $sep_tindakan[0]['pasien_id'], 'penjamin_id' => $tindakan[0]['penjamin_id'], 'is_active' => 1));
	$penjamin = object_to_array($data_penjamin);

	$get_poli = $this->poliklinik_m->get($sep_tindakan[0]['poliklinik_id']);
	// die_dump($get_poli);
?>
<div class="modal-header">
    <button type="button" class="btn btn-primary hidden" id="btnOK">OK</button>
	<button type="button" class="closeModal hidden" data-dismiss="modal" aria-hidden="true"></button>
</div>
<div class="modal-body">
	<div class="form-body">
		<div class="row">
			&nbsp;
			&nbsp;
			&nbsp;
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('No. SEP', $this->session->userdata('language'))?> :</label>
					<label class="control-label col-md-7" style="text-align: left;"><?=$sep_tindakan[0]['no_sep']?></label>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('Tanggal SEP', $this->session->userdata('language'))?> :</label>
					<label class="control-label col-md-7" style="text-align: left;"><?=date('d-m-Y', strtotime($sep_tindakan[0]['tanggal_sep']))?></label>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('No. BPJS', $this->session->userdata('language'))?> :</label>
					<label class="control-label col-md-7" style="text-align: left;"><?=$penjamin[0]['no_kartu']?></label>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('Nama Peserta', $this->session->userdata('language'))?> :</label>
						<?php
							$data_pasien = $this->pasien_m->get_by(array('id' => $tindakan[0]['pasien_id']));
							$pasien = object_to_array($data_pasien);

							if($pasien[0]['gender'] == 'P')
							{
								$gender = 'P';
							}
							else
							{
								$gender = 'L';
							}
						?>

					<label class="control-label col-md-7" style="text-align : left;"><?=strtoupper($pasien[0]['nama'])?></label>
					<input type="hidden" id="id_pasien" name="id_pasien">
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('Tanggal Lahir', $this->session->userdata('language'))?> :</label>
					<label class="control-label col-md-7" style="text-align : left;"><?=date('d-m-Y', strtotime($pasien[0]['tanggal_lahir']))?></label>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('Jenis Kelamin', $this->session->userdata('language'))?> :</label>
					<label class="control-label col-md-7" style="text-align : left;"><?=$gender?></label>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('Poli Tujuan', $this->session->userdata('language'))?> :</label>
					<label class="control-label col-md-7" style="text-align : left;">Hemodialisa</label>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('Asal Faskes 1', $this->session->userdata('language'))?> :</label>
					<label class="control-label col-md-7" style="text-align : left;"><?=strtoupper($pasien[0]['ref_kode_rs_rujukan'])?></label>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('Diagnosa Awal', $this->session->userdata('language'))?> :</label>
						<?php
							$kode = '';
							$nama = '';
							$data_diagnosa = $this->tindakan_hd_m->get_diagnosa($tindakan_hd_id)->result_array();

							if($data_diagnosa)
							{
								$kode = $data_diagnosa[0]['kode'];
								$nama = $data_diagnosa[0]['nama'];
							}
							// die_dump($data_diagnosa);
						?>
					<label class="control-label col-md-7" style="text-align : left;">Chronic renal failure, unspecified</label>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('Catatan', $this->session->userdata('language'))?> :</label>
					<?php

						$catatan = '';

						if($sep_tindakan)
						{
							$catatan = $sep_tindakan[0]['catatan'];
						}
					?>
					
					<label class="control-label col-md-7" style="text-align : left;"><?=$catatan?></label>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('Peserta', $this->session->userdata('language'))?> :</label>
				
					<label class="control-label col-md-6" style="text-align : left;"><?=$sep_tindakan[0]['jenis_peserta']?></label>
					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('COB', $this->session->userdata('language'))?> :</label>
					<label class="control-label col-md-6" style="text-align : left;"><?=$sep_tindakan[0]['cob']?></label>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('Jenis Rawat', $this->session->userdata('language'))?> :</label>
					<div class="col-md-5">
						<?php

							$default = '';

							if($sep_tindakan)
							{
								$default = $sep_tindakan[0]['jenis_rawat'];
							}
							
							$jenis_rawat = array(
								''	=> 'Pilih..',
								'1' => 'Rawat Jalan',
								'2'	=> 'Rawat Inap'
							);

							echo form_dropdown('jenis_rawat', $jenis_rawat, $default, "id=\"jenis_rawat\" class=\"form-control input-sx warehouse\" disabled=\"disabled\" ");
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('Kelas Rawat', $this->session->userdata('language'))?> :</label>
					<div class="col-md-5">
						<?php

							$default = '';

							if($sep_tindakan)
							{
								$default = $sep_tindakan[0]['kelas_rawat'];
							}

							$kelas_rawat = array(
								''	=> 'Pilih..',
								'1' => 'Kelas I',
								'2'	=> 'Kelas II',
								'3'	=> 'Kelas III',
							);

							echo form_dropdown('kelas_rawat', $kelas_rawat, $default, "id=\"kelas_rawat\" class=\"form-control input-sx warehouse\" disabled=\"disabled\" ");
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('Cetakan ke', $this->session->userdata('language'))?> </label>
					<div class="col-md-5">
						<label class="control-label"><?=$sep_tindakan[0]['cetakan_ke']?> - <?=$sep_tindakan[0]['tanggal_cetak']?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			&nbsp;
			&nbsp;
			&nbsp;
		</div>
	</div>
</div>
<div class="modal-footer">
	<a class="btn default" data-dismiss="modal" id="closeModal"><?=translate('OK', $this->session->userdata('language'))?></a>
</div>
<script>
	$( document ).ready(function() {
	    handleDatePickers();
	});

	function handleDatePickers(){
            if (jQuery().datepicker) {
                $('.date').datepicker({
                    rtl: Metronic.isRTL(),
                    format : 'dd MM yyyy',
                    // autoclose: true
                }).on('changeDate', function(ev){
                    $('.datepicker-dropdown').hide();
                });
                $('body').removeClass("modal-open");
                $('.date').on('click', function(){
                    if ($('#popup_modal_identitas').is(":visible") && $('body').hasClass("modal-open") == false) {
                        $('body').addClass("modal-open");
                    }
                });
            }
        }
</script>