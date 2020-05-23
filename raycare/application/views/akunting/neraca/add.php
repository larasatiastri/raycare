<?php
	$form_attr = array(
	    "id"            => "form_add_neraca", 
	    "name"          => "form_add_neraca", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
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
		<?php $msg = translate("Apakah anda yakin akan membuat neraca ini?",$this->session->userdata("language"));?>
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
                            <label class="control-label col-md-3"><?=translate("Tanggal", $this->session->userdata("language"))?><span class="required" style="color:red;"> *</span>:</label>
                            
                            <div class="col-md-6">
                                <div class="input-group date" id="tanggal">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" readonly required value="<?=date('d-M-Y')?>">
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
						<b> Rp. 0,-</b>
					</div>
				</div>
				<div class="portlet-body">
                    <input type="hidden" id="total_aktiva" name="total_aktiva" value="0">
				    <table class="table table-bordered" id="tabel_current_asset">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-left"> Current Asset</th>
                            </tr>
                            
                        </thead>
                                
                        <tbody>
                            <?php

                                $i = 0;
                                foreach ($akun_current_asset as $current) :
                                    if($current['parent'] == '0'){
                                        $akun_child = $this->akun_m->get_by(array('parent' => $current['id']));
                                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$current['id'].'"';

                            ?>
                                <tr style="background-color: #357ebd;">
                                    <th><?=$current['no_akun']?></th>
                                    <th width="60%"><input type="hidden" name="akun_current[<?=$i?>][id]" value="<?=$current['id']?>"><input type="hidden" name="akun_current[<?=$i?>][parent]" value="<?=$current['parent']?>"><?=$current['nama']?></th>
                                    <th width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_current[<?=$i?>][nominal]" value="0" class="form-control text-right parent_current_asset" <?=$id?> data-parent_id="<?=$current['parent']?>" <?=$readonly?>></div></th>
                                </tr>
                            <?php    
                                }elseif($current['parent'] != '0'){
                            ?>
                                <tr>
                                    <td><?=$current['no_akun']?></td>
                                    <td width="60%"><input type="hidden" name="akun_current[<?=$i?>][id]" value="<?=$current['id']?>"><input type="hidden" name="akun_current[<?=$i?>][parent]" value="<?=$current['parent']?>"><?=$current['nama']?></td>
                                    <td width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_current[<?=$i?>][nominal]" value="0" class="form-control text-right <?=$current['parent']?>" data-parent_id="<?=$current['parent']?>"></div></td>
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
                                <th id="total_current_asset" class="text-right"> Rp. 0 </th><input type="hidden" name="total_current_asset" id="total_current_asset" value="0">
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
                                foreach ($akun_fixed_asset as $fixed) :
                                    if($fixed['parent'] == '0'){
                                        $akun_child = $this->akun_m->get_by(array('parent' => $fixed['id']));
                                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$fixed['id'].'"';
                            ?>
                                <tr style="background-color: #357ebd;">
                                    <th><?=$fixed['no_akun']?></th>
                                    <th width="60%"><input type="hidden" name="akun_fixed[<?=$i?>][id]" value="<?=$fixed['id']?>"><input type="hidden" name="akun_fixed[<?=$i?>][parent]" value="<?=$fixed['parent']?>"><?=$fixed['nama']?></th>
                                    <th width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_fixed[<?=$i?>][nominal]" value="0" class="form-control text-right parent_fixed_asset" <?=$id?> data-parent_id="<?=$fixed['parent']?>" <?=$readonly?>></div></th>
                                </tr>
                            <?php    
                                }elseif($fixed['parent'] != '0'){
                            ?>
                                <tr>
                                    <td><?=$fixed['no_akun']?></td>
                                    <td width="60%"><input type="hidden" name="akun_fixed[<?=$i?>][id]" value="<?=$fixed['id']?>"><input type="hidden" name="akun_fixed[<?=$i?>][parent]" value="<?=$fixed['parent']?>"><?=$fixed['nama']?></td>
                                    <td width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_fixed[<?=$i?>][nominal]" value="0" class="form-control text-right <?=$fixed['parent']?>" data-parent_id="<?=$fixed['parent']?>"></div></td>
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
                                <th class="text-right" id="total_fixed_asset"> Rp. 0 </th><input type="hidden" name="total_fixed_asset" id="total_fixed_asset" value="0"> 
                            </tr>
                        </tfoot>
                        
                    </table>
                        	
				</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
    
		<div class="col-md-6">
            <input type="hidden" id="total_pasiva" name="total_pasiva" value="0">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject"><?=translate("PASIVA", $this->session->userdata("language"))?></span>
                    </div>  
                    <div class="actions" id="total_pasiva">
                        <b> Rp. 0,-</b>
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
                                foreach ($akun_liability as $liability) :
                                    if($liability['parent'] == '0'){
                                        $akun_child = $this->akun_m->get_by(array('parent' => $liability['id']));
                                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$liability['id'].'"';
                            ?>
                                <tr style="background-color: #357ebd;">
                                    <th><?=$liability['no_akun']?></th>
                                    <th width="60%"><input type="hidden" name="akun_liability[<?=$i?>][id]" value="<?=$liability['id']?>"><input type="hidden" name="akun_liability[<?=$i?>][parent]" value="<?=$liability['parent']?>"><?=$liability['nama']?></th>
                                    <th width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_liability[<?=$i?>][nominal]" value="0" class="form-control text-right parent_liability" <?=$id?> data-parent_id="<?=$liability['parent']?>" <?=$readonly?>></div></th>
                                </tr>
                            <?php    
                                }elseif($liability['parent'] != '0'){
                            ?>
                                <tr>
                                    <td><?=$liability['no_akun']?></td>
                                    <td width="60%"><input type="hidden" name="akun_liability[<?=$i?>][id]" value="<?=$liability['id']?>"><input type="hidden" name="akun_liability[<?=$i?>][parent]" value="<?=$liability['parent']?>"><?=$liability['nama']?></td>
                                    <td width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_liability[<?=$i?>][nominal]" value="0" class="form-control text-right <?=$liability['parent']?>" data-parent_id="<?=$liability['parent']?>"></div></td>
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
                                <th class="text-right" id="total_liability"> Rp. 0 </th><input type="hidden" name="total_liability" id="total_liability" value="0"> 
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
                                foreach ($akun_equity as $equity) :
                                    if($equity['parent'] == '0'){
                                        $akun_child = $this->akun_m->get_by(array('parent' => $equity['id']));
                                        $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                        $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$equity['id'].'"';
                            ?>
                                <tr style="background-color: #357ebd;">
                                    <th><?=$equity['no_akun']?></th>
                                    <th width="60%"><input type="hidden" name="akun_equity[<?=$i?>][id]" value="<?=$equity['id']?>"><input type="hidden" name="akun_equity[<?=$i?>][parent]" value="<?=$equity['parent']?>"><?=$equity['nama']?></th>
                                    <th width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_equity[<?=$i?>][nominal]" value="0" class="form-control text-right parent_equity" <?=$id?> data-parent_id="<?=$equity['parent']?>" <?=$readonly?>></div></th>
                                </tr>
                            <?php    
                                }elseif($equity['parent'] != '0'){
                            ?>
                                <tr>
                                    <td><?=$equity['no_akun']?></td>
                                    <td width="60%"><input type="hidden" name="akun_equity[<?=$i?>][id]" value="<?=$equity['id']?>"><input type="hidden" name="akun_equity[<?=$i?>][parent]" value="<?=$equity['parent']?>"><?=$equity['nama']?></td>
                                    <td width="25%"><div class="input-group col-md-12"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_equity[<?=$i?>][nominal]" value="0" class="form-control text-right <?=$equity['parent']?>" data-parent_id="<?=$equity['parent']?>"></div></td>
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
                                <th class="text-right" id="total_equity"> Rp. 0 </th><input type="hidden" name="total_equity" id="total_equity" value="0">
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
