<?php
    $form_attr = array(
        "id"            => "form_edit_neraca", 
        "name"          => "form_edit_neraca", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        "role"          => "form"
    );
    
    $hidden = array(
        "command"   => "edit",
        "id"        => $form_data['id']
    );

    echo form_open(base_url()."akunting/neraca/save", $form_attr, $hidden);
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
            <span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Neraca', $this->session->userdata('language'))?></span>
        </div>
        <?php $msg = translate("Apakah anda yakin akan mengubah neraca ini?",$this->session->userdata("language"));?>
        <div class="actions">   
            <a class="btn btn-circle btn-default" href="<?=base_url()?>akunting/neraca"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
            <a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Nomor", $this->session->userdata("language"))?> :</label>
                        
                        <div class="col-md-6">
                            <label><?=$form_data['nomor']?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Tanggal", $this->session->userdata("language"))?><span class="required" style="color:red;"> *</span>:</label>
                        
                        <div class="col-md-6">
                            <div class="input-group date" id="tanggal">
                                <input type="text" class="form-control" id="tanggal" name="tanggal" readonly required value="<?=date('d-M-Y', strtotime($form_data['tanggal']))?>">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <?=translate('AKTIVA', $this->session->userdata('language'))?>
                    </div>
                    <div class="actions" id="total_aktiva">
                        <b> <?=formatrupiah($form_data['total_aktiva'])?></b>
                    </div>
                </div>
                <div class="portlet-body">
                    <input type="hidden" id="total_aktiva" name="total_aktiva" value="<?=$form_data['total_aktiva']?>">
                    <table class="table table-bordered" id="tabel_current_asset">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-left"> Current Asset</th>
                            </tr>
                            
                        </thead>
                                
                        <tbody>
                            <?php

                                $i = 0;
                                $total_current_asset = 0;
                                foreach ($akun_current_asset as $current) :
                                    if($current['parent'] == '0'){
                                        $akun_child = $this->akun_m->get_by(array('parent' => $current['akun_id']));
                                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$current['akun_id'].'"';

                            ?>
                                <tr style="background-color: #357ebd;">
                                    <th><?=$current['no_akun']?></th>
                                    <th width="60%"><input type="hidden" name="akun_current[<?=$i?>][detail_id]" value="<?=$current['id']?>"><input type="hidden" name="akun_current[<?=$i?>][id]" value="<?=$current['akun_id']?>"><input type="hidden" name="akun_current[<?=$i?>][parent]" value="<?=$current['parent']?>"><?=$current['nama']?></th>
                                    <th width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_current[<?=$i?>][nominal]" value="<?=$current['nominal']?>" class="form-control text-right parent_current_asset" <?=$id?> data-parent_id="<?=$current['parent']?>" <?=$readonly?>></div></th>
                                </tr>
                            <?php 
                                $total_current_asset = $total_current_asset + $current['nominal'];   
                                }elseif($current['parent'] != '0'){
                            ?>
                                <tr>
                                    <td><?=$current['no_akun']?></td>
                                    <td width="60%"><input type="hidden" name="akun_current[<?=$i?>][detail_id]" value="<?=$current['id']?>"><input type="hidden" name="akun_current[<?=$i?>][id]" value="<?=$current['akun_id']?>"><input type="hidden" name="akun_current[<?=$i?>][parent]" value="<?=$current['parent']?>"><?=$current['nama']?></td>
                                    <td width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_current[<?=$i?>][nominal]" value="<?=$current['nominal']?>" class="form-control text-right <?=$current['parent']?>" data-parent_id="<?=$current['parent']?>"></div></td>
                                </tr>
                            <?php       
                                }
                               
                                $i++;
                                endforeach;
                            ?>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-center" > Total Current Asset</th>
                                <th id="total_current_asset" class="text-right"> <?=formatrupiah($total_current_asset)?> </th><input type="hidden" name="total_current_asset" id="total_current_asset" value="<?=$total_current_asset?>">
                            </tr>
                        </tfoot>
                        
                    </table>
                    <br>
                    <table class="table table-bordered" id="tabel_fixed_asset">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-left"> Fixed Asset</th>
                            </tr>
                            
                        </thead>
                                
                        <tbody>
                            <?php

                                $i = 0;
                                $total_fixed_asset = 0;
                                foreach ($akun_fixed_asset as $fixed) :
                                    if($fixed['parent'] == '0'){
                                        $akun_child = $this->akun_m->get_by(array('parent' => $fixed['akun_id']));
                                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$fixed['akun_id'].'"';
                            ?>
                                <tr style="background-color: #357ebd;">
                                    <th><?=$fixed['no_akun']?></th>
                                    <th width="60%"><input type="hidden" name="akun_fixed[<?=$i?>][detail_id]" value="<?=$fixed['id']?>"><input type="hidden" name="akun_fixed[<?=$i?>][id]" value="<?=$fixed['akun_id']?>"><input type="hidden" name="akun_fixed[<?=$i?>][parent]" value="<?=$fixed['parent']?>"><?=$fixed['nama']?></th>
                                    <th width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_fixed[<?=$i?>][nominal]" value="<?=$fixed['nominal']?>" class="form-control text-right parent_fixed_asset" <?=$id?> data-parent_id="<?=$fixed['parent']?>" <?=$readonly?>></div></th>
                                </tr>
                            <?php
                                $total_fixed_asset = $total_fixed_asset + $fixed['nominal'];    
                                }elseif($fixed['parent'] != '0'){
                            ?>
                                <tr>
                                    <td><?=$fixed['no_akun']?></td>
                                    <td width="60%"><input type="hidden" name="akun_fixed[<?=$i?>][detail_id]" value="<?=$fixed['id']?>"><input type="hidden" name="akun_fixed[<?=$i?>][id]" value="<?=$fixed['akun_id']?>"><input type="hidden" name="akun_fixed[<?=$i?>][parent]" value="<?=$fixed['parent']?>"><?=$fixed['nama']?></td>
                                    <td width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_fixed[<?=$i?>][nominal]" value="<?=$fixed['nominal']?>" class="form-control text-right <?=$fixed['parent']?>" data-parent_id="<?=$fixed['parent']?>"></div></td>
                                </tr>
                            <?php       
                                }
                                
                                $i++;
                                endforeach;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-center"> Total Fixed Asset</th>
                                <th class="text-right" id="total_fixed_asset"> <?=formatrupiah($total_fixed_asset)?> </th><input type="hidden" name="total_fixed_asset" id="total_fixed_asset" value="<?=$total_fixed_asset?>"> 
                            </tr>
                        </tfoot>
                        
                    </table>
                            
                </div><!-- end of <div class="portlet-body"> -->    
            </div>
        </div><!-- end of <div class="col-md-4"> -->
    
        <div class="col-md-6">
            <input type="hidden" id="total_pasiva" name="total_pasiva" value="<?=$form_data['total_pasiva']?>">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject"><?=translate("PASIVA", $this->session->userdata("language"))?></span>
                    </div>  
                    <div class="actions" id="total_pasiva">
                        <b> <?=formatrupiah($form_data['total_pasiva'])?></b>
                    </div>                    
                </div>
                <div class="portlet-body">
                   <table class="table table-bordered" id="tabel_liability">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-left"> Liability</th>
                            </tr>
                            
                        </thead>
                                
                        <tbody>
                            <?php

                                $i = 0;
                                $total_liability = 0;
                                foreach ($akun_liability as $liability) :
                                    if($liability['parent'] == '0'){
                                        $akun_child = $this->akun_m->get_by(array('parent' => $liability['akun_id']));
                                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$liability['akun_id'].'"';
                            ?>
                                <tr style="background-color: #357ebd;">
                                    <th><?=$liability['no_akun']?></th>
                                    <th width="60%"><input type="hidden" name="akun_liability[<?=$i?>][detail_id]" value="<?=$liability['id']?>"><input type="hidden" name="akun_liability[<?=$i?>][id]" value="<?=$liability['akun_id']?>"><input type="hidden" name="akun_liability[<?=$i?>][parent]" value="<?=$liability['parent']?>"><?=$liability['nama']?></th>
                                    <th width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_liability[<?=$i?>][nominal]" value="<?=$liability['nominal']?>" class="form-control text-right parent_liability" <?=$id?> data-parent_id="<?=$liability['parent']?>" <?=$readonly?>></div></th>
                                </tr>
                            <?php  
                                $total_liability = $total_liability + $liability['nominal'];  
                                }elseif($liability['parent'] != '0'){
                            ?>
                                <tr>
                                    <td><?=$liability['no_akun']?></td>
                                    <td width="60%"><input type="hidden" name="akun_liability[<?=$i?>][detail_id]" value="<?=$liability['id']?>"><input type="hidden" name="akun_liability[<?=$i?>][id]" value="<?=$liability['akun_id']?>"><input type="hidden" name="akun_liability[<?=$i?>][parent]" value="<?=$liability['parent']?>"><?=$liability['nama']?></td>
                                    <td width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_liability[<?=$i?>][nominal]" value="<?=$liability['nominal']?>" class="form-control text-right <?=$liability['parent']?>" data-parent_id="<?=$liability['parent']?>"></div></td>
                                </tr>
                            <?php       
                                }
                                $i++;
                                endforeach;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-center"> Total Liability</th>
                                <th class="text-right" id="total_liability"> <?=formatrupiah($total_liability)?> </th><input type="hidden" name="total_liability" id="total_liability" value="<?=$total_liability?>"> 
                            </tr>
                        </tfoot>
                        
                    </table>
                    <br>
                    <table class="table table-bordered" id="tabel_equity">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-left"> Equity</th>
                            </tr>
                            
                        </thead>
                                
                        <tbody>
                             <?php

                                $i = 0;
                                $total_equity = 0;
                                foreach ($akun_equity as $equity) :
                                    if($equity['parent'] == '0'){
                                        $akun_child = $this->akun_m->get_by(array('parent' => $equity['akun_id']));
                                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$equity['akun_id'].'"';
                            ?>
                                <tr style="background-color: #357ebd;">
                                    <th><?=$equity['no_akun']?></th>
                                    <th width="60%"><input type="hidden" name="akun_equity[<?=$i?>][detail_id]" value="<?=$equity['id']?>"><input type="hidden" name="akun_equity[<?=$i?>][id]" value="<?=$equity['akun_id']?>"><input type="hidden" name="akun_equity[<?=$i?>][parent]" value="<?=$equity['parent']?>"><?=$equity['nama']?></th>
                                    <th width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_equity[<?=$i?>][nominal]" value="<?=$equity['nominal']?>" class="form-control text-right parent_equity" <?=$id?> data-parent_id="<?=$equity['parent']?>" <?=$readonly?>></div></th>
                                </tr>
                            <?php   
                                $total_equity = $total_equity + $equity['nominal'];
                                }elseif($equity['parent'] != '0'){
                            ?>
                                <tr>
                                    <td><?=$equity['no_akun']?></td>
                                    <td width="60%"><input type="hidden" name="akun_equity[<?=$i?>][detail_id]" value="<?=$equity['id']?>"><input type="hidden" name="akun_equity[<?=$i?>][id]" value="<?=$equity['akun_id']?>"><input type="hidden" name="akun_equity[<?=$i?>][parent]" value="<?=$equity['parent']?>"><?=$equity['nama']?></td>
                                    <td width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_equity[<?=$i?>][nominal]" value="<?=$equity['nominal']?>" class="form-control text-right <?=$equity['parent']?>" data-parent_id="<?=$equity['parent']?>"></div></td>
                                </tr>
                            <?php       
                                }
                                $i++;
                                endforeach;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-center"> Total Equity</th>
                                <th class="text-right" id="total_equity"> <?=formatrupiah($total_equity)?> </th><input type="hidden" name="total_equity" id="total_equity" value="<?=$total_equity?>">
                            </tr>
                        </tfoot>
                        
                    </table>
                            
                </div><!-- end of <div class="portlet-body"> -->    
            </div>
            
        </div><!-- end of <div class="col-md-8"> -->
        
    </div><!-- end of <div class="row"> -->

    </div>
</div>


<?=form_close()?>
