<?php
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
?>
<form action="<?=base_url()?>klinik_hd/edit_transaksi/save_add" id="form_tindakan" class="form-horizontal" role="form">
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-medkit font-blue-sharp"></i>
                <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Input Tindakan HD Manual", $this->session->userdata("language"))?></span>
                <span class="caption-helper"><?php echo '<label class="control-label current_time">'.$form_tindakan['no_transaksi'].'</label>'; ?></span>
                <input class="form-control hidden" value="<?=$flag?>" id="flag_selesai">
            </div>
            
        </div>
        <div class="portlet-body form">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Nomor Pasien", $this->session->userdata("language"))?> :</label>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <?php
                                            $no_member = array(
                                                "id"          => "no_member",
                                                "name"        => "no_member",
                                                "autofocus"   => true,
                                                "class"       => "form-control",
                                                "required"    => "required",
                                                "readonly"    => "readonly",
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
                            <label class="col-md-12 bold"><?=translate("Nama Pasien", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <input type="text" name="nama_pasien" id="nama_pasien"  class="form-control nama_pasien"  readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Penjamin", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                    <input type="radio" id="swasta" value="1" name="penjamin_id" class="form-control"> Swasta</label>
                                    <label class="radio-inline">
                                    <input type="radio" id="bpjs" value="2" name="penjamin_id" class="form-control"> BPJS</label>
                                </div>               
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Tanggal", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <div class="input-group date" id="tanggal">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?=date('d M Y')?>" readonly required>
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Waktu", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <input type="text" name="waktu" id="waktu"  class="form-control waktu" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Shift", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <?php
                                    $shift = array(
                                        '' => 'Pilih..',
                                        '1' => 'Shift 1 "06:30 - 12:30"',
                                        '2' => 'Shift 2 "12:00 - 18:00"',
                                        '3' => 'Shift 3 "17:30 - 23:30"',

                                    );
                                    echo form_dropdown('shift', $shift, '', "id=\"shift\" class=\"form-control\" required=\"required\" ");
                                ?>
                            </div>
                        </div>
                        

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Bed", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <?php
                                    $beds = $this->bed_m->get_by(array('is_active' => 1, 'status !=' => 4));

                                    $sub_bed = array(
                                       '' => 'Pilih' 
                                    );

                                    foreach ($beds as $bed) {
                                        $sub_bed[$bed->id] = $bed->kode;
                                    }

                                    echo form_dropdown('bed_id', $sub_bed, '', "id=\"bed\" class=\"form-control\" required=\"required\" ");
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Dokter", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <?php
                                    $dokter = $this->user_m->get_by(array('user_level_id' => 10, 'is_active' => 1, 'cabang_id' => $this->session->userdata('cabang_id')));

                                    $sub = array(
                                       '' => 'Pilih' 
                                    );

                                    foreach ($dokter as $doc) {
                                        $sub[$doc->id] = $doc->nama;
                                    }

                                    echo form_dropdown('dokter', $sub, '', "id=\"dokter\" class=\"form-control\" required=\"required\" ");
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Recept", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <?php
                                    $recept = $this->user_m->get_by(array('user_level_id' => 19, 'is_active' => 1));

                                    $sub_recept = array(
                                       '' => 'Pilih' 
                                    );

                                    foreach ($recept as $doc) {
                                        $sub_recept[$doc->id] = $doc->nama;
                                    }

                                    echo form_dropdown('recept_id', $sub_recept, '', "id=\"recept_id\" class=\"form-control\" required=\"required\" ");
                                ?>
                            </div>
                        </div>

                        <div class="form_group hidden">
                            <label class="control-label col-md-4">ID Pasien:</label>
                            <div class="col-md-6">
                            <?php
                                $id_pasien = array(
                                    "id"          => "id_pasien",                    
                                    "name"        => "id_pasien",
                                    "autofocus"   => true,
                                    "class"       => "form-control",
                                    "required"    => "required",
                                    "placeholder" => translate("Pasien", $this->session->userdata("language"))
                                );
                                echo form_input($id_pasien);
                            ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Berat Badan Awal", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" id="berat" name="berat" class="form-control" required="required" value=""> 
                                    <span class="input-group-addon">
                                        Kg
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Berat Badan Akhir", $this->session->userdata("language"))?> :</label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" id="berat_akhir" name="berat_akhir" class="form-control" required="required" value=""> 
                                    <span class="input-group-addon">
                                        Kg
                                    </span>
                                </div>
                            </div>
                        </div>
                    

                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Tekanan Darah Awal", $this->session->userdata("language"))?> :</label>
                        <div class="col-md-12">
                            <div class="input-group">
                            <?php
                                    $tda = array(
                                    "name"          => "tdatas",
                                    "id"            => "tdatas",
                                    "required"      => "required",
                                     "size"          => "5",
                                    "class"         => "form-control", 
                                    "maxlength"     => "255", 
                                    );
                                echo form_input($tda);
                            ?>
                            
                            <span class="input-group-addon">/</span>
                            <?
                                $tdb = array(
                                    "name"          => "tdbawah",
                                    "required"      => "required",
                                    "id"            => "tdbawah",
                                      "class"         => "form-control", 
                                     "size"          => "5",
                                    "maxlength"     => "255", 
                                );
                                echo form_input($tdb);
                            ?>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Time of Dialysis", $this->session->userdata("language"))?> :</label>
                     
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="number" id="time_dialisis" name="time_dialisis" class="form-control" step="0.25" required="required" size="16" maxlength="255" value="4"> 
                                <span class="input-group-addon">
                                    <i>&nbsp;Hour(s)&nbsp;</i>
                                </span>  
                            </div>
                            <span class="help">
                                Gunakan angka dengan satuan jam. Jika waktu tindakan 4 Jam 30 Menit, inputkan 4.5
                            </span> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Quick of Blood", $this->session->userdata("language"))?> :</label>
                     
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" id="qb" name="qb" class="form-control" required="required" size="16" maxlength="255" value="0"> 
                                <span class="input-group-addon">
                                    <i>&nbsp;ml/Hour&nbsp;</i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Quick of Dialysate", $this->session->userdata("language"))?> :</label>
                     
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" id="qd" name="qd" class="form-control" required="required" size="16" maxlength="255" value="500"> 
                                <span class="input-group-addon">
                                    <i>&nbsp;ml/Hour&nbsp;</i>
                                </span>
                                </div>
                        </div> 
                    </div> 
                     <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("UF Goal", $this->session->userdata("language"))?> :</label>
                     
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" id="ufg" name="ufg" class="form-control" required="required" size="16" maxlength="255" value="0"> 
                                <span class="input-group-addon">
                                    <i>&nbsp;Liter(s)&nbsp;</i>
                                </span>
                                </div>
                        </div>
                    </div> 
                 
                    <div class="form-group">
                        <div class="col-md-3">
                            <input type="text" class="form-control hidden" id="command" name="command" value="add">
                        </div>
                    </div>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-md-12 text-left bold"><?=translate("Alergic :", $this->session->userdata("language"))?></label>
                             
                            <div class="col-md-12">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            <input class="" type="checkbox" id="alergic_medicine" name="alergic_medicine" value="1">
                                             Medicine
                                        </label>
                                        <label class="checkbox-inline">
                                            <input class="" type="checkbox" id="alergic_food" name="alergic_food"> Food  
                                        </label>
                                    </div>
                                     
                                 
                            </div>
                        </div>
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate('Dry Weight', $this->session->userdata('language'))?> :</label>
                        <div class="col-md-12">
                             <div class="input-group">
                                <input class="form-control" id="berat_kering" name="berat_kering" value="" required>
                                <span class="input-group-addon">
                                    &nbsp;Kg&nbsp;
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Assessment GCS :", $this->session->userdata("language"))?> </label>
                        
                        <div class="col-md-12">
                            <?php
                                $assessment_cgs = array(
                                    "name"          => "assessment_cgs_",
                                    "id"            => "assessment_cgs_",
                                    "rows"          => 4,
                                    "maxlength"     => "255",
                                    "class"         => "form-control",
                                    "placeholder"   => translate("Assessment GCS", $this->session->userdata("language")), 
                                    "value"         => "GCS:15\nKel(-)"
                                );
                            echo form_textarea($assessment_cgs);
                            ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Medical Diagnose :", $this->session->userdata("language"))?></label>
                        
                        <div class="col-md-12">
                            <?php
                                $medical_diagnose = array(
                                    "name"          => "medical_diagnose_",
                                    "id"            => "medical_diagnose_",
                                    "rows"          => 4,
                                    "maxlength"     => "255",
                                    "class"         => "form-control",
                                    "placeholder"   => translate("Medical Diagnose", $this->session->userdata("language")), 
                                    "value"         => 'CKD on HD'
                                    
                                );
                            echo form_textarea($medical_diagnose);
                            ?>  
                        </div>
                    </div>

                    <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Heparin :", $this->session->userdata("language"))?></label>
                            
                            <div class="col-md-12">

                                <div class="checkbox-list">
                                    <label class="checkbox-inline">
                                        <input  type="checkbox" id="regular_" name="regular_" value="1" class=""> Regular  
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="minimal_" name="minimal_" value="1" class=""> Minimal  
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="free_" name="free_" value="1" class=""> Free  
                                    </label>
                                </div> 
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Dose :", $this->session->userdata("language"))?></label>
                            
                            <div class="col-md-12">
                                
                                <?php
                                    $dose = array(
                                        "name"          => "dose_",
                                        "id"            => "dose_",
                                        "size"          => "16",
                                        "maxlength"     => "255",
                                        "class"         => "form-control", 
                                        "placeholder"   => translate("Dose", $this->session->userdata("language")), 
                                       
                                    );
                                    echo form_input($dose);
                                ?>      
                                    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("First :", $this->session->userdata("language"))?></label>
                            
                            <div class="col-md-12">
                                <div class="input-group">
                                <?php
                                    $first = array(
                                        "name"          => "first_",
                                        "id"            => "first_",
                                        "size"          => "16",
                                        "maxlength"     => "255",
                                        "class"         => "form-control", 
                                        "placeholder"   => translate("First", $this->session->userdata("language")), 
                                        
                                    );
                                    echo form_input($first);
                                ?>      
                                    <span class="input-group-addon">
                                        <i> U </i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Maintenance :", $this->session->userdata("language"))?></label>
                            
                            <div class="col-md-12">
                                <div class="input-group">
                                <?php
                                    $maintenance = array(
                                        "name"          => "maintenance_",
                                        "id"            => "maintenance_",
                                        "size"          => "16",
                                        "maxlength"     => "255",
                                        "class"         => "form-control", 
                                        "placeholder"   => translate("Maintenance", $this->session->userdata("language")), 
                                       
                                    );
                                    echo form_input($maintenance);
                                ?>      
                                    <span class="input-group-addon">
                                        <i> U / </i>
                                    </span>
                                    <input type="text" class="form-control" id="hour_" name="hour_" placeholder="Hour" value="">
                                    <span class="input-group-addon">
                                        <i> Hour </i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Type of Dialyzer :", $this->session->userdata("language"))?></label>
                            
                            <div class="col-md-12">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                    <input type="radio" id="new_" value="1" name="dializer" class="form-control"> New</label>
                                    <label class="radio-inline">
                                    <input type="radio" id="reuse_" value="2" name="dializer" class="form-control"> Reuse </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Dialyzer :", $this->session->userdata("language"))?></label>
                            
                            <div class="col-md-12">
                                
                                <?php
                                    $dialyzer = array(
                                        "name"          => "dialyzer_",
                                        "id"            => "dialyzer_",
                                        "size"          => "16",
                                        "maxlength"     => "255",
                                        "class"         => "form-control", 
                                        "placeholder"   => translate("Dialyzer", $this->session->userdata("language"))
                                    );
                                    echo form_input($dialyzer);
                                ?>      
                                    
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Blood Access :", $this->session->userdata("language"))?></label>
                            <div class="col-md-12">

                                <div class="checkbox-list">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="av_shunt_" name="av_shunt_" value="1" class="form-control" > AV Shunt  
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="femoral_" name="femoral_" value="1" class="form-control"> Femoral 
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="double_lument_" name="double_lument_" value="1"  class="form-control"> CDL
                                    </label>
                                </div> 

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Type of Dialysate :", $this->session->userdata("language"))?></label>
                            <div class="col-md-12">
                                <div class="checkbox-list">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="bicarbonate_" name="bicarbonate_" value="1" class="form-control" > Bicarbonate
                                    </label>
                                </div>  
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="form-section"><?=translate("Problems Found", $this->session->userdata("language"))?></h4>
                                <div class="form-group">                                    
                                    <div class="col-md-12">
                                        <div class="checkbox-list">
                                            <label>
                                                <input type="checkbox" id="problem_1" name="problem_1" value="1" class="pasien_problem" > Airway Clearance, ineffective
                                            </label> 
                                            <label>
                                                <input type="checkbox" id="problem_2" name="problem_2" value="1" class="pasien_problem" > Fluid balance
                                            </label> 
                                            <label>
                                                <input type="checkbox" id="problem_3" name="problem_3" value="1" class="pasien_problem" > High risk of infection
                                            </label>
                                            <label>
                                                <input type="checkbox" id="problem_4" name="problem_4" value="1" class="pasien_problem" > Impaired sense of comfort pain
                                            </label> 
                                            <label>
                                                <input type="checkbox" id="problem_5" name="problem_5" value="1" class="pasien_problem" > Disequilibrium Syndrome
                                            </label> 
                                            <label>
                                                <input type="checkbox" id="problem_6" name="problem_6" value="1" class="pasien_problem" > Shock Risk
                                            </label>
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h4 class="form-section"><?=translate("Complications", $this->session->userdata("language"))?></h4>
                                <div class="form-group">                                    
                                    <div class="col-md-12">
                                        <div class="checkbox-list">
                                            <label>
                                                <input type="checkbox" id="komplikasi_1" name="komplikasi_1" value="1" class="pasien_komplikasi" > Bleeding
                                            </label> 
                                            <label>
                                                <input type="checkbox" id="komplikasi_2" name="komplikasi_2" value="1" class="pasien_komplikasi" > Pruritus
                                            </label> 
                                            <label>
                                                <input type="checkbox" id="komplikasi_3" name="komplikasi_3" value="1" class="pasien_komplikasi" > Alergie
                                            </label>
                                            <label>
                                                <input type="checkbox" id="komplikasi_4" name="komplikasi_4" value="1" class="pasien_komplikasi" > Headache
                                            </label> 
                                            <label>
                                                <input type="checkbox" id="komplikasi_5" name="komplikasi_5" value="1" class="pasien_komplikasi" > Nausea
                                            </label> 
                                            <label>
                                                <input type="checkbox" id="komplikasi_6" name="komplikasi_6" value="1" class="pasien_komplikasi" > Chest Pain
                                            </label>
                                            <label>
                                                <input type="checkbox" id="komplikasi_7" name="komplikasi_7" value="1" class="pasien_komplikasi" > Hypotension
                                            </label>
                                            <label>
                                                <input type="checkbox" id="komplikasi_8" name="komplikasi_8" value="1" class="pasien_komplikasi" > Shiver
                                            </label>
                                            <label>
                                                <input type="checkbox" id="komplikasi_9" name="komplikasi_9" value="1" class="pasien_komplikasi" > Etc
                                            </label>
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>      
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-6">
                            <h4 class="form-section"><?=translate("Intake", $this->session->userdata("language"))?></h4>
                            <div class="form-group">
                                <label class="col-md-12 bold"><?=translate("Remaining of Priming :", $this->session->userdata("language"))?></label>
                                
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <?php
                                            $remaining = array(
                                                "name"          => "remaining",
                                                "id"            => "remaining",
                                                "size"          => "16",
                                                "maxlength"     => "255",
                                                "class"         => "form-control", 
                                                "placeholder"   => translate("Remaining of Priming", $this->session->userdata("language")), 
                                                
                                            );
                                            echo form_input($remaining);
                                        ?>      
                                        <span class="input-group-addon">
                                            <i> cc </i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 bold"><?=translate("Wash Out :", $this->session->userdata("language"))?></label>
                                
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <?php
                                            $washout = array(
                                                "name"          => "washout",
                                                "id"            => "washout",
                                                "size"          => "16",
                                                "maxlength"     => "255",
                                                "class"         => "form-control", 
                                                "placeholder"   => translate("Wash Out", $this->session->userdata("language")), 
                                               
                                            );
                                            echo form_input($washout);
                                        ?>      
                                        <span class="input-group-addon">
                                            <i> cc </i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 bold"><?=translate("Drip of Fluid :", $this->session->userdata("language"))?></label>
                                
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <?php
                                            $drip_of_fluid = array(
                                                "name"          => "drip_of_fluid",
                                                "id"            => "drip_of_fluid",
                                                "size"          => "16",
                                                "maxlength"     => "255",
                                                "class"         => "form-control", 
                                                "placeholder"   => translate("Drip of Fluid", $this->session->userdata("language")), 
                                               
                                            );
                                            echo form_input($drip_of_fluid);
                                        ?>      
                                        <span class="input-group-addon">
                                            <i> cc </i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 bold"><?=translate("Blood :", $this->session->userdata("language"))?></label>
                                
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <?php
                                            $blood = array(
                                                "name"          => "blood",
                                                "id"            => "blood",
                                                "size"          => "16",
                                                "maxlength"     => "255",
                                                "class"         => "form-control", 
                                                "placeholder"   => translate("Blood", $this->session->userdata("language")), 
                                               
                                            );
                                            echo form_input($blood);
                                        ?>      
                                        <span class="input-group-addon">
                                            <i> cc </i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 bold"><?=translate("Drink :", $this->session->userdata("language"))?></label>
                                
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <?php
                                            $drink = array(
                                                "name"          => "drink",
                                                "id"            => "drink",
                                                "size"          => "16",
                                                "maxlength"     => "255",
                                                "class"         => "form-control", 
                                                "placeholder"   => translate("Drink", $this->session->userdata("language")), 
                                                
                                            );
                                            echo form_input($drink);
                                        ?>      
                                        <span class="input-group-addon">
                                            <i> cc </i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <h4 class="form-section"><?=translate("Output", $this->session->userdata("language"))?></h4>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Vomiting :", $this->session->userdata("language"))?></label>
                            
                            <div class="col-md-12">
                                <div class="input-group">
                                    <?php
                                        $vomiting = array(
                                            "name"          => "vomiting",
                                            "id"            => "vomiting",
                                            "size"          => "16",
                                            "maxlength"     => "255",
                                            "class"         => "form-control", 
                                            "placeholder"   => translate("Vomiting", $this->session->userdata("language")), 
                                           
                                        );
                                        echo form_input($vomiting);
                                    ?>      
                                    <span class="input-group-addon">
                                        <i> cc </i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Urinate :", $this->session->userdata("language"))?></label>
                            
                            <div class="col-md-12">
                                <div class="input-group">
                                    <?php
                                        $urinate = array(
                                            "name"        => "urinate",
                                            "id"          => "urinate",
                                            "size"        => "16",
                                            "maxlength"   => "255",
                                            "class"       => "form-control", 
                                            "placeholder" => translate("Urinate", $this->session->userdata("language")), 
                                            
                                        );
                                        echo form_input($urinate);
                                    ?>      
                                    <span class="input-group-addon">
                                        <i> cc </i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        </div>

                    <div class="col-md-6">
                         <h4 class="form-section"><?=translate("Blood Transfusion", $this->session->userdata("language"))?></h4>
                        <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Type :", $this->session->userdata("language"))?></label>
                        
                        <div class="col-md-12">                                  
                            <?php
                                $type = array(
                                    "name"          => "type",
                                    "id"            => "type",
                                    "size"          => "16",
                                    "maxlength"     => "255",
                                    "class"         => "form-control", 
                                    "placeholder"   => translate("Type", $this->session->userdata("language")), 
                                    
                                );
                                echo form_input($type);
                            ?>                                      
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Quantity :", $this->session->userdata("language"))?></label>
                        
                        <div class="col-md-12">
                            <div class="input-group">
                                <?php
                                    $quantity = array(
                                        "name"          => "quantity",
                                        "id"            => "quantity",
                                        "size"          => "16",
                                        "maxlength"     => "255",
                                        "class"         => "form-control", 
                                        "placeholder"   => translate("Quantity", $this->session->userdata("language")), 
                                        
                                    );
                                    echo form_input($quantity);
                                ?>      
                                <span class="input-group-addon">
                                    <i> Bag </i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Blood Type :", $this->session->userdata("language"))?></label>
                        
                        <div class="col-md-12">                                  
                            <?php
                                $blood_type = array(
                                    "name"          => "blood_type",
                                    "id"            => "blood_type",
                                    "size"          => "16",
                                    "maxlength"     => "255",
                                    "class"         => "form-control", 
                                    "placeholder"   => translate("Blood Type", $this->session->userdata("language")), 
                                    
                                );
                                echo form_input($blood_type);
                            ?>                                      
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12 bold"><?=translate("Serial Number :", $this->session->userdata("language"))?></label>
                        
                        <div class="col-md-12">                                  
                            <?php
                                $serial_number = array(
                                    "name"          => "serial_number",
                                    "id"            => "serial_number",
                                    "rows"          => "4",
                                    "class"         => "form-control", 
                                    "placeholder"   => translate("Serial Number", $this->session->userdata("language")), 
                                    
                                );
                                echo form_textarea($serial_number);
                            ?>                                      
                        </div>
                    </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="form-section"><?=translate("Examination Support", $this->session->userdata("language"))?></h4>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Laboratory :", $this->session->userdata("language"))?> </label>
                            
                            <div class="col-md-12">
                                <?php
                                    $laboratory = array(
                                        "name"          => "laboratory",
                                        "id"            => "laboratory",
                                        "rows"          => 4,
                                        "maxlength"     => "255",
                                        "class"         => "form-control",
                                        "placeholder"   => translate("Laboratory", $this->session->userdata("language"))
                                    );
                                echo form_textarea($laboratory);
                                ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("ECG :", $this->session->userdata("language"))?></label>
                            
                            <div class="col-md-12">
                                <?php
                                    $ecg = array(
                                        "name"          => "ecg",
                                        "id"            => "ecg",
                                        "rows"          => 4,
                                        "maxlength"     => "255",
                                        "class"         => "form-control",
                                        "placeholder"   => translate("ECG", $this->session->userdata("language"))
                                    );
                                echo form_textarea($ecg);
                                ?>  
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Priming :", $this->session->userdata("language"))?></label>
                            <div class="col-md-12">
                                <?php
                                    $priming = array(
                                        "name"          => "priming",
                                        "id"            => "priming",
                                        "size"          => "16",
                                        "maxlength"     => "255",
                                        "class"         => "form-control", 
                                        "placeholder"   => translate("Priming", $this->session->userdata("language"))
                                    );
                                    echo form_input($priming);
                                ?>  
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Initiation :", $this->session->userdata("language"))?></label>
                            
                            <div class="col-md-12">
                                <?php
                                    $initiation = array(
                                        "name"          => "initiation",
                                        "id"            => "initiation",
                                        "size"          => "16",
                                        "maxlength"     => "255",
                                        "class"         => "form-control", 
                                        "placeholder"   => translate("Initiation", $this->session->userdata("language"))
                                    );
                                    echo form_input($initiation);
                                ?>      
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Termination :", $this->session->userdata("language"))?></label>
                            
                            <div class="col-md-12">
                                <?php
                                    $termination = array(
                                        "name"          => "termination",
                                        "id"            => "termination",
                                        "size"          => "16",
                                        "maxlength"     => "255",
                                        "class"         => "form-control", 
                                        "placeholder"   => translate("Termination", $this->session->userdata("language"))
                                    );
                                    echo form_input($termination);
                                ?>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    </div>
                </div>
            </div>
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-medkit font-blue-sharp"></i>
                    <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Monitoring Dialysis", $this->session->userdata("language"))?></span>
                </div>
            </div>
            <div class="portlet-body form">
            <?php
                $data_perawat = $this->user_m->get_by(array('user_level_id' => 18, 'is_active' => 1));

                $perawat_option = array();
                foreach ($data_perawat as $perawat) 
                {
                    $perawat_option[$perawat->id] = $perawat->nama;
                }

            ?>
                <div class="form-body">
                    <table class="table table-striped table-hover table-bordered" id="table_monitoring">
                        <thead>
                            <tr>
                                <th class="text-center" width="1%"><?=translate('No', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('Waktu', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('BP', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('UFG', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('UFR', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('UFV', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('QB', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('TMP', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('VP', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('AP', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('Cond', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('Temperature', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('Diinput Oleh', $this->session->userdata('language'))?></th>
                                <th class="text-center"><?=translate('Keterangan', $this->session->userdata('language'))?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input class="form-control" id="waktu_add[0]" name="monitoring[0][waktu_add]" value="<?=date('H:i')?>"> </td>
                                <td><div class="input-group"> <input class="form-control" id="tekanan_darah_1_add[0]" name="monitoring[0][tekanan_darah_1_add]" value=""> <span class="input-group-addon"> <i>  / </i> </span> <input class="form-control" id="tekanan_darah_2_add[0]" name="monitoring[0][tekanan_darah_2_add]" value=""> </div></td>
                                <td><input class="form-control" id="ufg_add[0]" name="monitoring[0][ufg_add]" value=""></td>
                                <td><input class="form-control" id="ufr_add[0]" name="monitoring[0][ufr_add]" value=""></td>
                                <td><input class="form-control" id="ufv_add[0]" name="monitoring[0][ufv_add]" value=""></td>
                                <td><input class="form-control" id="qb_add[0]" name="monitoring[0][qb_add]" value=""></td>
                                <td><input class="form-control" id="tmp_add[0]" name="monitoring[0][tmp_add]" value=""></td>
                                <td><input class="form-control" id="vp_add[0]" name="monitoring[0][vp_add]" value=""></td>
                                <td><input class="form-control" id="ap_add[0]" name="monitoring[0][ap_add]" value=""></td>
                                <td><input class="form-control" id="cond_add[0]" name="monitoring[0][cond_add]" value=""></td>
                                <td><input class="form-control" id="temp_add[0]" name="monitoring[0][temp_add]" value=""></td>
                                <td><?php 
                                        echo form_dropdown('monitoring[0][perawat_id]', $perawat_option,'', 'id="perawat_id[0]" class="form-control"');
                                    ?>
                                </td>
                                <td><textarea class="form-control" id="keterangan_add[0]" name="monitoring[0][keterangan_add]" rows="3"value="" ></textarea></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><input class="form-control" id="waktu_add[1]" name="monitoring[1][waktu_add]" value="<?=date('H:i')?>"> </td>
                                <td><div class="input-group"> <input class="form-control" id="tekanan_darah_1_add[1]" name="monitoring[1][tekanan_darah_1_add]" value=""> <span class="input-group-addon"> <i>  / </i> </span> <input class="form-control" id="tekanan_darah_2_add[1]" name="monitoring[1][tekanan_darah_2_add]" value=""> </div></td>
                                <td><input class="form-control" id="ufg_add[1]" name="monitoring[1][ufg_add]" value=""></td>
                                <td><input class="form-control" id="ufr_add[1]" name="monitoring[1][ufr_add]" value=""></td>
                                <td><input class="form-control" id="ufv_add[1]" name="monitoring[1][ufv_add]" value=""></td>
                                <td><input class="form-control" id="qb_add[1]" name="monitoring[1][qb_add]" value=""></td>
                                <td><input class="form-control" id="tmp_add[1]" name="monitoring[1][tmp_add]" value=""></td>
                                <td><input class="form-control" id="vp_add[1]" name="monitoring[1][vp_add]" value=""></td>
                                <td><input class="form-control" id="ap_add[1]" name="monitoring[1][ap_add]" value=""></td>
                                <td><input class="form-control" id="cond_add[1]" name="monitoring[1][cond_add]" value=""></td>
                                <td><input class="form-control" id="temp_add[1]" name="monitoring[1][temp_add]" value=""></td>
                                <td><?php 
                                        echo form_dropdown('monitoring[1][perawat_id]', $perawat_option,'', 'id="perawat_id[1]" class="form-control"');
                                    ?>
                                </td>
                                <td><textarea class="form-control" id="keterangan_add[1]" name="monitoring[1][keterangan_add]" rows="3"value="" ></textarea></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><input class="form-control" id="waktu_add[2]" name="monitoring[2][waktu_add]" value="<?=date('H:i')?>"> </td>
                                <td><div class="input-group"> <input class="form-control" id="tekanan_darah_1_add[2]" name="monitoring[2][tekanan_darah_1_add]" value=""> <span class="input-group-addon"> <i>  / </i> </span> <input class="form-control" id="tekanan_darah_2_add[2]" name="monitoring[2][tekanan_darah_2_add]" value=""> </div></td>
                                <td><input class="form-control" id="ufg_add[2]" name="monitoring[2][ufg_add]" value=""></td>
                                <td><input class="form-control" id="ufr_add[2]" name="monitoring[2][ufr_add]" value=""></td>
                                <td><input class="form-control" id="ufv_add[2]" name="monitoring[2][ufv_add]" value=""></td>
                                <td><input class="form-control" id="qb_add[2]" name="monitoring[2][qb_add]" value=""></td>
                                <td><input class="form-control" id="tmp_add[2]" name="monitoring[2][tmp_add]" value=""></td>
                                <td><input class="form-control" id="vp_add[2]" name="monitoring[2][vp_add]" value=""></td>
                                <td><input class="form-control" id="ap_add[2]" name="monitoring[2][ap_add]" value=""></td>
                                <td><input class="form-control" id="cond_add[2]" name="monitoring[2][cond_add]" value=""></td>
                                <td><input class="form-control" id="temp_add[2]" name="monitoring[2][temp_add]" value=""></td>
                                <td><?php 
                                        echo form_dropdown('monitoring[2][perawat_id]', $perawat_option,'', 'id="perawat_id[2]" class="form-control"');
                                    ?>
                                </td>
                                <td><textarea class="form-control" id="keterangan_add[2]" name="monitoring[2][keterangan_add]" rows="3"value="" ></textarea></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td><input class="form-control" id="waktu_add[3]" name="monitoring[3][waktu_add]" value="<?=date('H:i')?>"> </td>
                                <td><div class="input-group"> <input class="form-control" id="tekanan_darah_1_add[3]" name="monitoring[3][tekanan_darah_1_add]" value=""> <span class="input-group-addon"> <i>  / </i> </span> <input class="form-control" id="tekanan_darah_2_add[3]" name="monitoring[3][tekanan_darah_2_add]" value=""> </div></td>
                                <td><input class="form-control" id="ufg_add[3]" name="monitoring[3][ufg_add]" value=""></td>
                                <td><input class="form-control" id="ufr_add[3]" name="monitoring[3][ufr_add]" value=""></td>
                                <td><input class="form-control" id="ufv_add[3]" name="monitoring[3][ufv_add]" value=""></td>
                                <td><input class="form-control" id="qb_add[3]" name="monitoring[3][qb_add]" value=""></td>
                                <td><input class="form-control" id="tmp_add[3]" name="monitoring[3][tmp_add]" value=""></td>
                                <td><input class="form-control" id="vp_add[3]" name="monitoring[3][vp_add]" value=""></td>
                                <td><input class="form-control" id="ap_add[3]" name="monitoring[3][ap_add]" value=""></td>
                                <td><input class="form-control" id="cond_add[3]" name="monitoring[3][cond_add]" value=""></td>
                                <td><input class="form-control" id="temp_add[3]" name="monitoring[3][temp_add]" value=""></td>
                                <td><?php 
                                        echo form_dropdown('monitoring[3][perawat_id]', $perawat_option,'', 'id="perawat_id[3]" class="form-control"');
                                    ?>
                                </td>
                                <td><textarea class="form-control" id="keterangan_add[3]" name="monitoring[3][keterangan_add]" rows="3"value="" ></textarea></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td><input class="form-control" id="waktu_add[4]" name="monitoring[4][waktu_add]" value="<?=date('H:i')?>"> </td>
                                <td><div class="input-group"> <input class="form-control" id="tekanan_darah_1_add[4]" name="monitoring[4][tekanan_darah_1_add]" value=""> <span class="input-group-addon"> <i>  / </i> </span> <input class="form-control" id="tekanan_darah_2_add[4]" name="monitoring[4][tekanan_darah_2_add]" value=""> </div></td>
                                <td><input class="form-control" id="ufg_add[4]" name="monitoring[4][ufg_add]" value=""></td>
                                <td><input class="form-control" id="ufr_add[4]" name="monitoring[4][ufr_add]" value=""></td>
                                <td><input class="form-control" id="ufv_add[4]" name="monitoring[4][ufv_add]" value=""></td>
                                <td><input class="form-control" id="qb_add[4]" name="monitoring[4][qb_add]" value=""></td>
                                <td><input class="form-control" id="tmp_add[4]" name="monitoring[4][tmp_add]" value=""></td>
                                <td><input class="form-control" id="vp_add[4]" name="monitoring[4][vp_add]" value=""></td>
                                <td><input class="form-control" id="ap_add[4]" name="monitoring[4][ap_add]" value=""></td>
                                <td><input class="form-control" id="cond_add[4]" name="monitoring[4][cond_add]" value=""></td>
                                <td><input class="form-control" id="temp_add[4]" name="monitoring[4][temp_add]" value=""></td>
                                <td><?php 
                                        echo form_dropdown('monitoring[4][perawat_id]', $perawat_option,'', 'id="perawat_id[4]" class="form-control"');
                                    ?>
                                </td>
                                <td><textarea class="form-control" id="keterangan_add[4]" name="monitoring[4][keterangan_add]" rows="3"value="" ></textarea></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td><input class="form-control" id="waktu_add[5]" name="monitoring[5][waktu_add]" value="<?=date('H:i')?>"> </td>
                                <td><div class="input-group"> <input class="form-control" id="tekanan_darah_1_add[5]" name="monitoring[5][tekanan_darah_1_add]" value=""> <span class="input-group-addon"> <i>  / </i> </span> <input class="form-control" id="tekanan_darah_2_add[5]" name="monitoring[5][tekanan_darah_2_add]" value=""> </div></td>
                                <td><input class="form-control" id="ufg_add[5]" name="monitoring[5][ufg_add]" value=""></td>
                                <td><input class="form-control" id="ufr_add[5]" name="monitoring[5][ufr_add]" value=""></td>
                                <td><input class="form-control" id="ufv_add[5]" name="monitoring[5][ufv_add]" value=""></td>
                                <td><input class="form-control" id="qb_add[5]" name="monitoring[5][qb_add]" value=""></td>
                                <td><input class="form-control" id="tmp_add[5]" name="monitoring[5][tmp_add]" value=""></td>
                                <td><input class="form-control" id="vp_add[5]" name="monitoring[5][vp_add]" value=""></td>
                                <td><input class="form-control" id="ap_add[5]" name="monitoring[5][ap_add]" value=""></td>
                                <td><input class="form-control" id="cond_add[5]" name="monitoring[5][cond_add]" value=""></td>
                                <td><input class="form-control" id="temp_add[5]" name="monitoring[5][temp_add]" value=""></td>
                                <td><?php 
                                        echo form_dropdown('monitoring[5][perawat_id]', $perawat_option,'', 'id="perawat_id[5]" class="form-control"');
                                    ?>
                                </td>
                                <td><textarea class="form-control" id="keterangan_add[5]" name="monitoring[5][keterangan_add]" rows="3"value="" ></textarea></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td><input class="form-control" id="waktu_add[6]" name="monitoring[6][waktu_add]" value="<?=date('H:i')?>"> </td>
                                <td><div class="input-group"> <input class="form-control" id="tekanan_darah_1_add[6]" name="monitoring[6][tekanan_darah_1_add]" value=""> <span class="input-group-addon"> <i>  / </i> </span> <input class="form-control" id="tekanan_darah_2_add[6]" name="monitoring[6][tekanan_darah_2_add]" value=""> </div></td>
                                <td><input class="form-control" id="ufg_add[6]" name="monitoring[6][ufg_add]" value=""></td>
                                <td><input class="form-control" id="ufr_add[6]" name="monitoring[6][ufr_add]" value=""></td>
                                <td><input class="form-control" id="ufv_add[6]" name="monitoring[6][ufv_add]" value=""></td>
                                <td><input class="form-control" id="qb_add[6]" name="monitoring[6][qb_add]" value=""></td>
                                <td><input class="form-control" id="tmp_add[6]" name="monitoring[6][tmp_add]" value=""></td>
                                <td><input class="form-control" id="vp_add[6]" name="monitoring[6][vp_add]" value=""></td>
                                <td><input class="form-control" id="ap_add[6]" name="monitoring[6][ap_add]" value=""></td>
                                <td><input class="form-control" id="cond_add[6]" name="monitoring[6][cond_add]" value=""></td>
                                <td><input class="form-control" id="temp_add[6]" name="monitoring[6][temp_add]" value=""></td>
                                <td><?php 
                                        echo form_dropdown('monitoring[6][perawat_id]', $perawat_option,'', 'id="perawat_id[6]" class="form-control"');
                                    ?>
                                </td>
                                <td><textarea class="form-control" id="keterangan_add[6]" name="monitoring[6][keterangan_add]" rows="3"value="" ></textarea></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td><input class="form-control" id="waktu_add[7]" name="monitoring[7][waktu_add]" value="<?=date('H:i')?>"> </td>
                                <td><div class="input-group"> <input class="form-control" id="tekanan_darah_1_add[7]" name="monitoring[7][tekanan_darah_1_add]" value=""> <span class="input-group-addon"> <i>  / </i> </span> <input class="form-control" id="tekanan_darah_2_add[7]" name="monitoring[7][tekanan_darah_2_add]" value=""> </div></td>
                                <td><input class="form-control" id="ufg_add[7]" name="monitoring[7][ufg_add]" value=""></td>
                                <td><input class="form-control" id="ufr_add[7]" name="monitoring[7][ufr_add]" value=""></td>
                                <td><input class="form-control" id="ufv_add[7]" name="monitoring[7][ufv_add]" value=""></td>
                                <td><input class="form-control" id="qb_add[7]" name="monitoring[7][qb_add]" value=""></td>
                                <td><input class="form-control" id="tmp_add[7]" name="monitoring[7][tmp_add]" value=""></td>
                                <td><input class="form-control" id="vp_add[7]" name="monitoring[7][vp_add]" value=""></td>
                                <td><input class="form-control" id="ap_add[7]" name="monitoring[7][ap_add]" value=""></td>
                                <td><input class="form-control" id="cond_add[7]" name="monitoring[7][cond_add]" value=""></td>
                                <td><input class="form-control" id="temp_add[7]" name="monitoring[7][temp_add]" value=""></td>
                                <td><?php 
                                        echo form_dropdown('monitoring[7][perawat_id]', $perawat_option,'', 'id="perawat_id[7]" class="form-control"');
                                    ?>
                                </td>
                                <td><textarea class="form-control" id="keterangan_add[7]" name="monitoring[7][keterangan_add]" rows="3"value="" ></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                </div>


<div class="form-actions right">
                <a class="btn default" href="javascript:history.go(-1)">
                    <i class="fa fa-chevron-left"></i>
                    <?=translate('Kembali', $this->session->userdata('language'))?>
                </a>
                <a class="btn btn-primary" id="confirm_save" data-confirm="Anda yakin akan menambahkan data tindakan ini?">
                    <i class="fa fa-save"></i>
                    <?=translate('Simpan', $this->session->userdata('language'))?>
                </a>
            </div>
        
</div>
            </div>

    </div>

    

<?php 
    $msg = translate('Apakah anda yakin menambahkan tindakan ini?', $this->session->userdata("language"));
 ?>

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

</form>