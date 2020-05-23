<?php
    $msg = translate("Apakah anda yakin akan mengubah data pasien pindah ini?",$this->session->userdata("language"));

?>
<div class="portlet light">
    <div class="portlet-body form">
        <form class="form-horizontal" action="<?=base_url()?>master/pasien_pindah/save" name="form_pasien_pindah" id="form_pasien_pindah" class="form-horizontal" method="POST">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("EDIT DATA PASIEN PINDAH", $this->session->userdata("language"))?></span>
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
                        <label class="control-label col-md-3"><?=translate("ID", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <input type="text" name="id" id="id"  class="form-control" value="<?=$pk_value?>" >
                        </div>
                    </div>
                    <div class="form-group hidden">
                        <label class="control-label col-md-3"><?=translate("Command", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <input type="text" name="command" id="command"  class="form-control" value="edit" >
                        </div>
                    </div>
                    <div class="form-group hidden">
                        <label class="control-label col-md-3"><?=translate("ID Pasien", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-4">
                            <input type="text" name="id_pasien" id="id_pasien"  class="form-control id_pasien" value="<?=$pasien['id']?>" >
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
                                        "placeholder" => translate("Nomor Pasien", $this->session->userdata("language")),
                                        "value"       => $pasien['no_member']
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
                            <input type="text" name="nama_pasien" id="nama_pasien"  class="form-control nama_pasien" required readonly="readonly" value=<?=$pasien['nama']?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Tanggal Pindah", $this->session->userdata("language"))?> :<span class="required">*</span></label>
                        <div class="col-md-4">
                            <div class="input-group date" id="tanggal_pindah">
                                <input type="text" class="form-control" id="tanggal_pindah" name="tanggal_pindah" value="<?=date('d-M-Y', strtotime($form_data['tanggal_surat']))?>" required readonly >
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Tujuan Pindah", $this->session->userdata("language"))?> :<span class="required">*</span></label>
                        <div class="col-md-4">
                            <input type="text" name="rs_tujuan" id="rs_tujuan"  class="form-control" required value="<?=$form_data['rs_tujuan']?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Alasan Pindah", $this->session->userdata("language"))?> :<span class="required">*</span></label>
                        <div class="col-md-4">
                            <?php
                                echo form_textarea('alasan_pindah', $form_data['alasan_pindah'], 'id="alasan_pindah" class="form-control" rows="4" required');
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
