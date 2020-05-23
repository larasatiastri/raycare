
<div class="portlet light">
    <div class="portlet-body form">
        <form class="form-horizontal" action="<?=base_url()?>master/pasien_meninggal/save" name="form_pasien_meninggal" id="form_pasien_meninggal" class="form-horizontal" method="POST">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("DATA PASIEN MENINGGAL", $this->session->userdata("language"))?></span>
                </div>
                <div class="actions">
                    <a class="btn btn-circle btn-default" href="javascript:history.go(-1)">
                        <i class="fa fa-chevron-left"></i>
                        <?=translate("Kembali", $this->session->userdata("language"))?>
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group hidden">
                        <label class="control-label col-md-3"><?=translate("ID Pasien", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <label class="control-label"><?=$pasien['id']?> </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Nomor Pasien", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <label class="control-label"><?=$pasien['no_member']?> </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                             <label class="control-label"><?=$pasien['nama']?> </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Tanggal Meninggal", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                             <label class="control-label"><?=date('d M Y', strtotime($form_data['tanggal_meninggal']))?> </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Tempat Meninggal", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <?php
                                $tipe_lokasi = '';
                                $hidden = '';
                                $hidden_lokasi = '';
                                if($form_data['tipe_lokasi'] == 1)
                                {
                                    $tipe_lokasi = 'Klinik Raycare';
                                    $hidden_lokasi = 'hidden';
                                    $hidden = '';
                                }
                                if($form_data['tipe_lokasi'] == 2)
                                {
                                    $tipe_lokasi = 'Lokasi Lain';
                                    $hidden = 'hidden';
                                    $hidden_lokasi = '';
                                }
                            ?>
                            <label class="control-label"><?=$tipe_lokasi?> </label>
                        </div>
                    </div>
                    <div class="form-group <?=$hidden?>" id="cabang_klinik">
                        <label class="control-label col-md-3"><?=translate("Cabang Klinik", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <?php
                                if($form_data['cabang_meninggal'] != '')
                                {
                                    $data_cabang = $this->cabang_m->get($form_data['cabang_meninggal']);
                                    echo '<label class="control-label">'.$data_cabang->nama.' </label>';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="form-group <?=$hidden_lokasi?>" id="lokasi_meninggal">
                        <label class="control-label col-md-3"><?=translate("Nama Tempat", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <label class="control-label"><?=$form_data['lokasi_meninggal']?> </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Jenazah Dibawa Ke", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <?php
                                $tipe_tujuan = '';
                                $hidden_tujuan = '';
                                if($form_data['tipe_lokasi_tujuan'] == 1)
                                {
                                    $tipe_tujuan = 'Rumah Tinggal';
                                    $hidden_tujuan = 'hidden';
                                }
                                if($form_data['tipe_lokasi_tujuan'] == 2)
                                {
                                    $tipe_tujuan = 'Rumah Sakit';
                                    $hidden_tujuan = '';
                                }
                                if($form_data['tipe_lokasi_tujuan'] == 3)
                                {
                                    $tipe_tujuan = 'Rumah Duka';
                                    $hidden_tujuan = '';
                                }
                            ?>
                            <label class="control-label"><?=$tipe_tujuan?> </label>
                        </div>
                    </div>
                    <div class="form-group <?=$hidden_tujuan?>" id="lokasi_tujuan">
                        <label class="control-label col-md-3" id="label_lokasi_tujuan"><?=translate("Nama Tempat", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <label class="control-label"><?=$form_data['lokasi_tujuan']?> </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Kendaraan Jenazah", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <?php
                                $tipe_kendaraan = '';
                                if($form_data['tipe_kendaraan'] == 1)
                                {
                                    $tipe_kendaraan = 'Ambulance';
                                }
                                if($form_data['tipe_kendaraan'] == 2)
                                {
                                    $tipe_kendaraan = 'Kendaraan Pribadi';
                                }
                                if($form_data['tipe_kendaraan'] == 3)
                                {
                                    $tipe_kendaraan = 'Kendaraan Umum';
                                }
                            ?>
                            <label class="control-label"><?=$tipe_kendaraan?> </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Nopol Kendaraan", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <label class="control-label"><?=$form_data['no_kendaraan']?> </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <label class="control-label"><?=($form_data['keterangan'] != '' && $form_data['keterangan'] != NULL)? $form_data['keterangan']:'-' ?> </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
