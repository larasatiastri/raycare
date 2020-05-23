<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">
					<span class="caption-subject font-blue-sharp bold uppercase judul"><?=translate("Lantai ", $this->session->userdata("language"))?><?=$form_data[0]['lantai']?> - <?=$form_data[0]['bed_nama'].' ['.$form_data[0]['bed_kode'].']'?></span>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="embed-responsive embed-responsive-4by3" style="border: 2px solid #cecece;">
						  	<iframe class="embed-responsive-item" src=""></iframe>
						</div>
						<!-- <img src="img.jpg" class="img-thumbnail" width="100%" ></img> -->
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate('No. Transaksi', $this->session->userdata('language'))?> :</label>
							<div class="col-md-7">
								<input class="form-control hidden" id="id" value="<?=$bed_id?>">
								<label class="control-label no_transaksi"><?=$form_data[0]['no_transaksi']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate('Jam Mulai', $this->session->userdata('language'))?> :</label>
							<div class="col-md-7">
								<input class="form-control input-small hidden" id="bed_detail" name="bed_detail">
								<label class="control-label waktu_mulai"><?=$form_data[0]['jam_mulai']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate('Pasien', $this->session->userdata('language'))?> :</label>
							<div class="col-md-7">
								<label class="control-label pasien" style="text-align:left;"><?=$form_data[0]['pasien']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate('Dokter', $this->session->userdata('language'))?> :</label>
							<div class="col-md-7">
								<label class="control-label dokter" style="text-align:left;"><?=$form_data[0]['dokter']?></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-5"><?=translate('Perawat', $this->session->userdata('language'))?> :</label>
							<div class="col-md-7">
								<label class="control-label perawat" style="text-align:left;"><?=$form_data[0]['perawat']?></label>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a class="btn default" data-dismiss="modal"><?=translate('Batal', $this->session->userdata('language'))?></a>
			</div>