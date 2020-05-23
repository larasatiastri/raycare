<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <div class="caption">
		<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Lihat Jadwal Pasien", $this->session->userdata("language"))?></span>
	</div>
</div>

<div class="modal-body">
    <div class="portlet light">
        <div class="portlet-body form">
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label col-md-4"><?=translate("Nomor Pasien", $this->session->userdata("language"))?> :</label>
                    <div class="col-md-6">
                        <?php
                            $pasien = $this->pasien_m->get($pasien_id);
                            $nomor_pasien = array(
                                "id"          => "nomor_pasien",
                                "name"        => "nomor_pasien",
                                "autofocus"   => true,
                                "class"       => "form-control",
                                "placeholder" => translate("Pasien", $this->session->userdata("language")),
                                "style"       => "background-color: transparent;border: 0px solid;", 
                                "readonly"    => "readonly",
                                "value"       => $pasien->no_member
                            );
                            echo form_input($nomor_pasien);
                        ?>
                    </div>
                </div>
                <div class="form-group">
                	<label class="control-label col-md-4"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
            		<div class="col-md-6">
            			<?php
            				$nama_pasien = array(
                                "id"          => "nama_pasien",
                                "name"        => "nama_pasien",
                                "autofocus"   => true,
                                "class"       => "form-control",
                                "placeholder" => translate("Pasien", $this->session->userdata("language")),
                                "style"       => "background-color: transparent;border: 0px solid;", 
                                "readonly"    => "readonly",
                                "value"       => $pasien->nama
            				);
            				echo form_input($nama_pasien);
            			?>
            		</div>
               </div>
                <div class="form-group">
                	 <label class="control-label col-md-4"><?=translate("Jadwal", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-6">
                            <input type="text" name="jadwal_awal" class="form-control jadwal_awal" style ="background-color: transparent;border: 0px solid;" readonly="readonly" value="<?=$hari?>">
                        </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4"><?=translate("Waktu", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-6">
                            <input type="text" name="waktu" class="form-control waktu" style ="background-color: transparent;border: 0px solid;" readonly="readonly" value="<?=$tipe?>">
                        </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4"><?=translate("No.Urut", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-6">
                            <input type="text" name="no_bed" class="form-control no_urut" style ="background-color: transparent;border: 0px solid;" readonly="readonly" value="<?=$urut?>">
                        </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-6">
                            <input type="text" name="no_bed" class="form-control no_urut" style ="background-color: transparent;border: 0px solid;" readonly="readonly" value="<?=$keterangan?>">
                        </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <input type="text" class="form-control id hidden" id="id" name="id" value="<?=$id?>">
                        <input type="text" class="form-control id hidden" id="id_pasien" name="id_pasien" value="<?=$pasien_id?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" id="closeModal" class="btn default hidden" data-dismiss="modal">Close</button>
    <a class="btn default" data-dismiss="modal"><?=translate("Kembali", $this->session->userdata("language"))?></a>
</div>