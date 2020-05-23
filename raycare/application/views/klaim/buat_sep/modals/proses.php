<?php
	$form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>

<div class="modal-header">
    <button type="button" class="btn btn-primary hidden" id="btnOK">OK</button>
	<button type="button" class="closeModal hidden" data-dismiss="modal" aria-hidden="true"></button>
</div>
<div class="modal-body">
	<div class="form-body">
		<div class="alert alert-danger display-hide">
	        <button class="close" data-close="alert"></button>
	        <?=$form_alert_danger?>
	    </div>
	    <div class="alert alert-success display-hide">
	        <button class="close" data-close="alert"></button>
	        <?=$form_alert_success?>
	    </div>
		<div class="row">
			&nbsp;
			&nbsp;
			&nbsp;
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<input type="hidden" id="tindak_hd_id" name="tindak_hd_id" value="<?=$tindakan_hd_id?>">
					<input type="hidden" id="pasien_penjamin_id" name="pasien_penjamin_id" value="<?=$pasien_penjamin_id?>">
					<label class="control-label col-md-5"><?=translate('No. SEP', $this->session->userdata('language'))?> :<span class="required">*</span></label>
					<div class="col-md-7">
						<?php
							$no_sep = array(
								"id"        => "no_sep",
								"name"      => "no_sep",
								"autofocus" => true,
								"class"     => "form-control",
								"required"  => "required"
							);

							echo form_input($no_sep);

							// $wheres = array(
							// 	'tipe IN (1,0)',
							// 	'is_active' => 1
							// );
							// $data_cabang = $this->cabang_m->get_cabang()->result();
       //      				die_dump($data_cabang);
            				?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('Tanggal SEP', $this->session->userdata('language'))?> :<span class="required">*</span></label>
					<div class="col-md-7">
							<div class="input-group date date-picker">
								<input class="form-control" id="tanggal" name="tanggal" readonly required value="<?=date('d F Y')?>">
								<span class="input-group-btn">
									<button class="btn" type="button"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('No. BPJS', $this->session->userdata('language'))?> :</label>
					<div class="col-md-7">
						<?php
							$data_tindakan = $this->tindakan_hd_m->get_by(array('id' => $tindakan_hd_id));
							$tindakan = object_to_array($data_tindakan);

							$data_sep_tindakan = $this->sep_tindakan_m->get_by(array('tindakan_id' => $pendaftaran_tindakan_id, 'tipe_tindakan' => 0), true);
							$sep_tindakan = object_to_array($data_sep_tindakan);
							// die_dump($this->db->last_query());

							$data_id_pasien = '';
							$sep_tindakan_id = '';
							if($sep_tindakan)
							{
								$data_id_pasien = $sep_tindakan['pasien_id'];
								$sep_tindakan_id = $sep_tindakan['id'];
							}

							$data_penjamin = $this->pasien_penjamin_m->get_by(array('pasien_id' => $data_id_pasien, 'penjamin_id' => $tindakan[0]['penjamin_id'],'is_active' => 1));
							$penjamin = object_to_array($data_penjamin);
							$bpjs = '';

							if($penjamin)
							{
								$bpjs = $penjamin[0]['no_kartu'];
							}

							$no_bpjs = array(
								"id"			=> "no_bpjs",
								"name"			=> "no_bpjs",
								"autofocus"			=> true,
								"class"			=> "form-control",
								"value"			=> $bpjs
							);

							echo form_input($no_bpjs);
						?>
					</div>
					<input type="hidden" id="sep_tindakan_id" name="sep_tindakan_id" value="<?=$sep_tindakan_id?>">
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
					<input type="hidden" id="id_pasien" name="id_pasien" value="<?=$pasien[0]['id']?>">
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
					<div class="col-md-7">
						<label class="control-label"><?=translate('Hemodialisa', $this->session->userdata('language'))?></label>
						<?php
							$data_poliklinik = $this->poliklinik_m->get();
							$poliklinik = object_to_array($data_poliklinik);
							// die_dump($data_poliklinik);
							$default = '';

							if($sep_tindakan)
							{
								$default = $sep_tindakan['poliklinik_id'];
							}

							$jenis_poli = array(
								''	=> 'Pilih..'
							);

							foreach ($poliklinik as $poliklinik) {
								$jenis_poli[$poliklinik['id']] = $poliklinik['nama'];
							}

							echo form_dropdown('jenis_poli', $jenis_poli, $default, "id=\"jenis_poli\" class=\"form-control input-sx warehouse hidden\"");
						?>
					</div>
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
					<input type="hidden" id="kd_diagnosa" name="kd_diagnosa" value="<?=$kode?>">
				</div>
				<div class="form-group">
					<label class="control-label col-md-5"><?=translate('Catatan', $this->session->userdata('language'))?> :</label>
					<div class="col-md-7"> 
						<?php

							$catatan = '';

							if($sep_tindakan)
							{
								$catatan = $sep_tindakan['catatan'];
							}

							$catatan = array(
								"id"          => "catatan",
								"name"        => "catatan",
								"autofocus"   => true,
								"rows"        => 5,
								"class"       => "form-control",
								"placeholder" => translate("Keterangan", $this->session->userdata("language")),
								"value"	      => $catatan
							);

							echo form_textarea($catatan);
						?>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('Peserta', $this->session->userdata('language'))?> :</label>
					<div class="col-md-6">
						<?php
							$peserta_select = '';

							if($sep_tindakan)
							{
								$peserta_select = $sep_tindakan['jenis_peserta'];
							}

							$jenis_peserta = $this->jenis_peserta_bpjs_m->get_by(array('is_active' => 1));
                            foreach ($jenis_peserta as $peserta) 
                            {
                                $jenis_peserta_option[$peserta->nama_jenis_peserta] = $peserta->nama_jenis_peserta;
                            }

                            echo form_dropdown('jenis_peserta', $jenis_peserta_option, $peserta_select, 'id="jenis_peserta" class="form-control" required');

						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('COB', $this->session->userdata('language'))?> :</label>
					<div class="col-md-6">
						<?php
							$valid_cob = '';

							if($sep_tindakan)
							{
								$valid_cob = $sep_tindakan['cob'];
							}

							$cob = array(
								"id"			=> "cob",
								"name"			=> "cob",
								"autofocus"			=> true,
								"class"			=> "form-control",
								"value"			=> $valid_cob
							);

							echo form_input($cob);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('Jenis Rawat', $this->session->userdata('language'))?> :</label>
					<div class="col-md-5">
						<?php

							$default = '';

							if($sep_tindakan)
							{
								$default = $sep_tindakan['jenis_rawat'];
							}
							
							$jenis_rawat = array(
								''	=> 'Pilih..',
								'1' => 'Rawat Jalan',
								'2'	=> 'Rawat Inap'
							);

							echo form_dropdown('jenis_rawat', $jenis_rawat, $default, "id=\"jenis_rawat\" class=\"form-control input-sx warehouse\"");
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
								$default = $sep_tindakan['kelas_rawat'];
							}

							$kelas_rawat = array(
								''	=> 'Pilih..',
								'1' => 'Kelas I',
								'2'	=> 'Kelas II',
								'3'	=> 'Kelas III',
							);

							echo form_dropdown('kelas_rawat', $kelas_rawat, $default, "id=\"kelas_rawat\" class=\"form-control input-sx warehouse\"");
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('Cetakan ke', $this->session->userdata('language'))?> :</label>
					<div class="col-md-2">
						<?php
							$cetakan_ke = array(
								"id"        => "cetakan_ke",
								"name"      => "cetakan_ke",
								"autofocus" => true,
								"class"     => "form-control",
								"value"		=> '1',
							);

							echo form_input($cetakan_ke);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('Tgl Cetak', $this->session->userdata('language'))?> :</label>
					<div class="col-md-6">
						<div class="input-group">
							<?php
								$tgl = array(
									"id"          => "tgl",
									"name"        => "tgl",
									"placeholder" => "dd",
									"maxlength"	  => 2,	
									"autofocus"   => true,
									"class"       => "form-control",
									"value"		  => date('d')
								);
								echo form_input($tgl);
							?>
							<span class="input-group-addon">
								<i>-</i>
							</span>
							<input type="text" class="form-control" id="bln" name="bln" maxlength="3" placeholder="mmm" value="<?=date('M')?>">
							<span class="input-group-addon">
								<i>-</i>
							</span>
							<input type="text" class="form-control" id="thn" name="thn" maxlength="2" placeholder="yy" value="<?=date('y')?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('Jam Cetak', $this->session->userdata('language'))?> :</label>
					<div class="col-md-6">
						<div class="input-group">
							<?php
								$jam = array(
									"id"          => "jam",
									"name"        => "jam",
									"placeholder" => "hh",
									"maxlength"	  => 2,	
									"autofocus"   => true,
									"class"       => "form-control",
									"value"		  => date('h')
								);
								echo form_input($jam);
							?>
							<span class="input-group-addon">
								<i>:</i>
							</span>
							<input type="text" class="form-control" id="mnt" name="mnt" maxlength="2" placeholder="ii" value="<?=date('i')?>">
							<span class="input-group-addon">
								<i>:</i>
							</span>
							<input type="text" class="form-control" id="dtk" name="dtk" maxlength="2" placeholder="ss" value="<?=date('s')?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('Waktu Cetak', $this->session->userdata('language'))?> :</label>
					<div class="col-md-3">
						<?php
							$am_pm = array(
								'AM'	=> 'AM',
								'PM'	=> 'PM',
							);

							echo form_dropdown('am_pm', $am_pm,date('A'), "id=\"am_pm\" class=\"form-control\"");
						?>						
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
	<?php
		$msg = 'Buat SEP untuk tindakan ini?';
	?>
	<a class="btn default" data-dismiss="modal" id="closeModal"><?=translate('Batal', $this->session->userdata('language'))?></a>
    <a class="btn btn-primary" id="modal_ok" data-confirm="<?=$msg?>"><?=translate("OK", $this->session->userdata("language"))?></a>
</div>
<script>
	$( document ).ready(function() {
		baseAppUrl = mb.baseUrl()+'klaim/buat_sep/';
		$form = $('#form_index_sep');
		handleValidation();
	    handleDatePickers();
	    handleModalOK();
	});

	function handleValidation() {
        var error1   = $('.alert-danger', $form);
        var success1 = $('.alert-success', $form);

        $form.validate({
            // class has-error disisipkan di form element dengan class col-*
            errorPlacement: function(error, element) {
                error.appendTo(element.closest('[class^="col"]'));
            },
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            // rules: {
            // buat rulenya di input tag
            // },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                Metronic.scrollTo(error1, -200);
            },

            highlight: function (element) { // hightlight error inputs
                $(element).closest('[class^="col"]').addClass('has-error');
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('[class^="col"]').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                $(label).closest('[class^="col"]').removeClass('has-error'); // set success class to the control group
            }

    
        });
        
       
    }

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

    function handleModalOK(){
            $('a#modal_ok').click(function() {
            	if (! $form.valid()) return;

            	var msg = $(this).data('confirm');
            	bootbox.confirm(msg, function(result) {
                	if (result==true) {
		            	$.ajax({
		                    type     : 'POST',
		                    url      : baseAppUrl + 'save',
		                    data     : $form.serialize(),
		                    dataType : 'json',
		                    beforeSend : function(){
		                        Metronic.blockUI({boxed: true });
		                    },
		                    success  : function( results ) {
		                        $('a.reset').click();
		                    },
		                    complete : function(){
		                        Metronic.unblockUI();
		                    }
		                });
		                $('#closeModal').click();
		            }
		        })
         	});
        }
</script>