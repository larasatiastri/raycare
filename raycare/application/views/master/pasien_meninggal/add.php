<?php
    $msg = translate("Apakah anda yakin akan membuat data pasien meninggal ini?",$this->session->userdata("language"));

?>
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
                    <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
                        <i class="fa fa-check"></i>
                        <?=translate("Simpan", $this->session->userdata("language"))?>
                    </a>
                    <button type="submit" id="save" class="btn default hidden" >
                        <?=translate("Simpan", $this->session->userdata("language"))?>
                    </button>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group hidden">
                        <label class="control-label col-md-3"><?=translate("ID Pasien", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <input type="text" name="id_pasien" id="id_pasien"  class="form-control id_pasien" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Nomor Pasien", $this->session->userdata("language"))?> :<span class="required">*</span></label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <?php
                                    $no_member = array(
                                        "id"          => "no_member",
                                        "name"        => "no_member",
                                        "autofocus"   => true,
                                        "class"       => "form-control",
                                        "required"    => "required",
                                        "placeholder" => translate("Nomor Pasien", $this->session->userdata("language"))
                                    );
                                    
                                    echo form_input($no_member);
                                ?>
                                <span class="input-group-btn">
                                    <a class="btn btn-primary pilih-pasien" title="<?=translate('Pilih Pasien', $this->session->userdata('language'))?>">
                                    <i class="fa fa-search"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :<span class="required">*</span></label>
                        <div class="col-md-4">
                            <input type="text" name="nama_pasien" id="nama_pasien"  class="form-control nama_pasien" required readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Tanggal Meninggal", $this->session->userdata("language"))?> :<span class="required">*</span></label>
                        <div class="col-md-4">
                            <div class="input-group date" id="tanggal_meninggal">
                                <input type="text" class="form-control" id="tanggal_meninggal" name="tanggal_meninggal" value="<?=date('d-M-Y')?>" required readonly >
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Tempat Meninggal", $this->session->userdata("language"))?> :<span class="required">*</span></label>
                        <div class="col-md-4">
                            <?php
                                $tipe_lokasi_option = array(
                                    ''  => translate('Pilih', $this->session->userdata('language')).'...',
                                    '1' => 'Klinik Raycare',
                                    '2' => 'Lokasi lain'
                                );

                                echo form_dropdown('tipe_lokasi', $tipe_lokasi_option, '', 'id="tipe_lokasi" class="form-control" required');
                            ?>
                        </div>
                    </div>
                    <div class="form-group hidden" id="cabang_klinik">
                        <label class="control-label col-md-3"><?=translate("Cabang Klinik", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <?php
                                $cabang_klinik_option = array(
                                    ''  => translate('Pilih', $this->session->userdata('language')).'...',
                                    '11' => 'Klinik Raycare Kalideres',
                                    '12' => 'Klinik Raycare Depok' ,
                                );

                                echo form_dropdown('cabang_meninggal', $cabang_klinik_option, '', 'id="cabang_meninggal" class="form-control"');
                            ?>
                        </div>
                    </div>
                    <div class="form-group hidden" id="lokasi_meninggal">
                        <label class="control-label col-md-3"><?=translate("Nama Tempat", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <input type="text" name="lokasi_meninggal" id="lokasi_meninggal"  class="form-control lokasi_meninggal">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Jenazah Dibawa Ke", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <?php
                                $tujuan_option = array(
                                    ''  => translate('Pilih', $this->session->userdata('language')).'...',
                                    '1' => 'Rumah Tinggal', 
                                    '2' => 'Rumah Sakit', 
                                    '3' => 'Rumah Duka'
                                );

                                echo form_dropdown('tipe_lokasi_tujuan', $tujuan_option, '', 'id="tipe_lokasi_tujuan" class="form-control"');
                            ?>
                        </div>
                    </div>
                    <div class="form-group hidden" id="lokasi_tujuan">
                        <label class="control-label col-md-3" id="label_lokasi_tujuan"><?=translate("Nama Tempat", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <input type="text" name="lokasi_tujuan" id="lokasi_tujuan"  class="form-control lokasi_tujuan">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Kendaraan Jenazah", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <?php
                                $kendaraan_option = array(
                                    ''  => translate('Pilih', $this->session->userdata('language')).'...',
                                    '1' => 'Ambulance', 
                                    '2' => 'Kendaraan Pribadi', 
                                    '3' => 'Kendaraan Umum'
                                );

                                echo form_dropdown('tipe_kendaraan', $kendaraan_option, '', 'id="tipe_kendaraan" class="form-control"');
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Nopol Kendaraan", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <input type="text" name="no_kendaraan" id="no_kendaraan"  class="form-control no_kendaraan">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Keterangan", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <?php
                                echo form_textarea('keterangan', '', 'id="keterangan" class="form-control" rows="4"');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
<div id="popover_pasien_content" class="row">
    <div class="col-md-12">
        <table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_pasien">
            <thead>
                <tr role="row" class="heading">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No Member', $this->session->userdata('language'))?></div></th>
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
