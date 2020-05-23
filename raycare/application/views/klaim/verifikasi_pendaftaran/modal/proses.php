<?
    $form_attr = array(
        "id"            => "form_proses", 
        "name"          => "form_proses", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        
    );
?>

<form action="<?=base_url()?>klaim/verifikasi_pendaftaran/save" method="post" id="form_proses" class="form-horizontal">
                                       
    <div class="modal-body">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Proses", $this->session->userdata("language"))?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-group">
                    <div class="col-md-6">
                        <div class="form-group hidden">
                            <label class="control-label col-md-6"><?=translate("Pendaftaran Tindakan ID", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <?php
                                    $pendaftaran_tindakan = $this->pendaftaran_tindakan_m->get($pendaftaran_tindakan_id);
                                    $pendaftaran_tindakan = object_to_array($pendaftaran_tindakan);
                                ?>
                                <input type="text" id="pendaftaran_tindakan_id" name="pendaftaran_tindakan_id" class="form-control" value="<?=$pendaftaran_tindakan_id?>">
                            </div>
                        </div>
            
                        <div class="form-group hidden">
                            <label class="control-label col-md-6"><?=translate("Cabang ID", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <input type="text" id="cabang_id" name="cabang_id" class="form-control" value="<?=$pendaftaran_tindakan['cabang_id']?>">
                            </div>
                        </div>
                        
                        <div class="form-group hidden">
                            <label class="control-label col-md-6"><?=translate("Pasien ID", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <input type="text" id="pasien_id" name="pasien_id" class="form-control" value="<?=$pasien_id?>">
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <label class="control-label col-md-6"><?=translate("Pasien Penjamin ID", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <?php
                                    $pasien_penjamin = $this->pasien_penjamin_m->get($pasien_penjamin_id);
                                    $pasien_penjamin = object_to_array($pasien_penjamin);

                                    // die_dump($this->db->last_query());
                                ?>
                                <input type="text" id="pasien_penjamin_id" name="pasien_penjamin_id" class="form-control" value="<?=$pasien_penjamin_id?>" placeholder="<?=translate("No.BPJS", $this->session->userdata("language"))?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-6"><?=translate("No.BPJS", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <input type="text" id="no_bpjs" name="no_bpjs" class="form-control" value="<?=$pasien_penjamin['no_kartu']?>" placeholder="<?=translate("No.BPJS", $this->session->userdata("language"))?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-6"><?=translate("Nama Peserta", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <?php
                                    $pasien = $this->pasien_m->get($pasien_id);
                                    $pasien = object_to_array($pasien);
                                ?>
                                <label class="control-label"><?=strtoupper($pasien['nama'])?></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-6"><?=translate("Tanggal Lahir", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <label class="control-label"><?=date('d-m-Y', strtotime($pasien['tanggal_lahir']))?></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-6"><?=translate("Jenis Kelamin", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <?php
                                    $gender = "P";
                                    if ($pasien['gender'] == 'L') {
                                        $gender = "L";
                                    }
                                ?>
                                <label class="control-label"><?=$gender?></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-6"><?=translate("Poliklinik Tujuan", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <?php
                                    $poliklinik = $this->poliklinik_m->get_by(array('is_active' => '1'));
                                    // die(dump($this->db->last_query()));
                                    $poliklinik_option = array(
                                        '' => translate('Pilih..', $this->session->userdata('language'))
                                    );

                                    foreach ($poliklinik as $data)
                                    {
                                        $poliklinik_option[$data->id] = $data->nama;
                                    }
                                    echo form_dropdown('poliklinik_id', $poliklinik_option, $pendaftaran_tindakan['poliklinik_id'], 'id="poliklinik_id" class="form-control" required');
                                ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-6"><?=translate("Asal Faskes 1", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <input type="text" id="asal_faskes" name="asal_faskes" class="form-control" value="<?=strtoupper($pasien['ref_kode_rs_rujukan'])?>" placeholder="<?=translate("Asal Faskes 1", $this->session->userdata("language"))?>">
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <label class="control-label col-md-6"><?=translate("Diagnosa Awal", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <input type="text" id="diagnosa_awal" name="diagnosa_awal" class="form-control" placeholder="<?=translate("Diagnosa Awal", $this->session->userdata("language"))?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-6"><?=translate("Catatan", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <textarea id="catatan" rows="5" name="catatan" class="form-control" placeholder="<?=translate("Catatan", $this->session->userdata("language"))?>" value="HD <?=date('d-m-Y')?>" >HD <?=date('d-m-Y')?></textarea>
                            </div>
                        </div>
                        
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4"><?=translate("Peserta", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <?php
                                    $jenis_peserta = $this->jenis_peserta_bpjs_m->get_by(array('is_active' => 1));
                                    foreach ($jenis_peserta as $peserta) 
                                    {
                                        $jenis_peserta_option[$peserta->nama_jenis_peserta] = $peserta->nama_jenis_peserta;
                                    }

                                    echo form_dropdown('peserta', $jenis_peserta_option, '', 'id="peserta" class="form-control" required');
                                ?>
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4"><?=translate("COB", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <input type="text" id="cob" name="cob" class="form-control" placeholder="<?=translate("COB", $this->session->userdata("language"))?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4"><?=translate("Jenis Rawat", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <?php
                                    $jenis_rawat_option = array(
                                        '1' => translate('Rawat Jalan', $this->session->userdata('language')),
                                        '2' => translate('Rawat Inap', $this->session->userdata('language'))
                                    );

                                    echo form_dropdown('jenis_rawat', $jenis_rawat_option, '', 'id="jenis_rawat" class="form-control" required');
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4"><?=translate("Kelas Rawat", $this->session->userdata("language"))?></label>
                            <div class="col-md-6">
                                <?php
                                    $kelas_rawat_option = array(
                                        '1' => translate('Kelas I', $this->session->userdata('language')),
                                        '2' => translate('Kelas II', $this->session->userdata('language')),
                                        '3' => translate('Kelas III', $this->session->userdata('language'))
                                    );

                                    echo form_dropdown('kelas_rawat', $kelas_rawat_option, '3', 'id="kelas_rawat" class="form-control" required');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>
                <div class="form-group">
                    <label class="col-md-1"></label>
                    <label class="col-md-10">
                         Demikian data ini saya inputkan dengan sebenar-benarnya. Pasien ini dinyatakan aktif sebagai
                         Peserta BPJS.<br/> 
                         Saya bertanggung jawab penuh atas verifikasi data ini.
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <?php 
            $msg = translate("Verifikasi pendaftaran ini ?", $this->session->userdata("language"));
        ?>
        <a id="closeModal" class="btn btn-circle btn-default" data-dismiss="modal">Close</a>
        <button type="submit" class="btn btn-primary hidden" id="btnOK">OK</button>
        <a class="btn btn-circle btn-primary" id="confirm_save" data-confirm="<?=$msg?>">OK</a>
    </div>


    </form>
 
    <script type="text/javascript">

        $(document).ready(function(){
            initForm();
            baseAppUrl = mb.baseUrl()+'klaim/verifikasi_pendaftaran/'
            // modalOK();
        });
        
        function initForm()
        {
            confirmSave();
        };  

        function confirmSave(){
            $('a#confirm_save').click(function() {
                var msg = $(this).data('confirm');
                var i=0;
                bootbox.confirm(msg, function(result) {
                    Metronic.blockUI({boxed: true, message: 'Sedang Diproses..'});
                    if (result==true) {
                        i = parseInt(i) + 1;
                        $('a#confirm_save').attr('disabled','disabled');
                        if(i === 1)
                        {
                          $('#btnOK').click();
                        }
                    }
                });
            });
        };
    </script>
