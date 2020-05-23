<?php
	$form_attr = array(
	    "id"            => "form_add_laporan_rugi_laba", 
	    "name"          => "form_add_laporan_rugi_laba", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."akunting/laporan_rugi_laba/save", $form_attr, $hidden);
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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Laporan Rugi Laba', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat laporan rugi laba ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="<?=base_url()?>akunting/laporan_rugi_laba"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
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
		<div class="col-md-12">
			<div class="portlet light bordered">
				
				<div class="portlet-body">
                    <input type="hidden" id="total_aktiva" name="total_aktiva" value="0">
				    <table class="table table-bordered" id="tabel_pendapatan_asset">
                        <thead>
                            <tr>
                                <th class="text-center" width="10%"> Kategori </th>
                                <th class="text-center" width="1%"> No. Akun </th>
                                <th class="text-center"> Nama Akun </th>
                                <th class="text-center" colspan="3"> Nominal </th>
                                <th class="text-center"  width="5%"> Prosentase </th>
                            </tr> 
                        </thead>
                                
                        <tbody>
                            <tr>
                                <td class="inline-button-table"> Pendapatan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php

                                $i = 0;
                                foreach ($akun_pendapatan as $pendapatan) :
                                   
                                    $akun_child = $this->akun_m->get_by(array('parent' => $pendapatan['id']));
                                    $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                    $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$pendapatan['id'].'"';

                            ?>
                                <tr>
                                    <td></td>
                                    <td width="1%"><input type="hidden" name="akun_pendapatan[<?=$i?>][id]" value="<?=$pendapatan['id']?>"><input type="hidden" name="akun_pendapatan[<?=$i?>][akun_tipe]" value="<?=$pendapatan['akun_tipe']?>"><?=$pendapatan['no_akun']?></td>
                                    <td ><input type="hidden" name="akun_pendapatan[<?=$i?>][nama]" value="<?=$pendapatan['nama']?>"><?=$pendapatan['nama']?></td>
                                    <td width="9%"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_pendapatan[<?=$i?>][nominal_pendapatan]" value="0" data-index="<?=$i?>" class="form-control akun_pendapatan text-right"></div></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%"><input type="hidden" name="akun_pendapatan[<?=$i?>][prosentase]" id="akun_pendapatan_prosentase_<?=$i?>" value="0"><span id="akun_pendapatan_prosentase_<?=$i?>" > 0% </span></td>
                                </tr>
                            <?php    
                                
                                $i++;
                                endforeach;
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>TOTAL PENDAPATAN</td>
                                <td></td>
                                <td width="5%" class="text-right"><input type="hidden" name="total_pendapatan" id="total_pendapatan" value="0"><span id="total_pendapatan">Rp. 0</span></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="prosentase_total_pendapatan" id="prosentase_total_pendapatan" value="0"><span name="prosentase_total_pendapatan" id="prosentase_total_pendapatan" > 0% </span></td>
                            </tr>
                            <tr>
                                <td class="inline-button-table"> Harga Pokok Penjualan</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="inline-button-table"><input type="hidden" name="akun_hpp[0][id]" value="hpp_0"><input type="hidden" name="akun_hpp[0][akun_tipe]" value="8"><input type="hidden" name="akun_hpp[0][nama]" value="Persediaan Awal"> Persediaan Awal</td>
                                <td width="9%"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_hpp[0][nominal_hpp]" value="0"  data-index="0" class="form-control akun_hpp text-right"></div></td>
                                <td width="9%"></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="akun_hpp[0][prosentase]" id="akun_hpp_prosentase_0" value="0"><span id="akun_hpp_prosentase_0" > 0% </span></td>
                            </tr>
                            <?php

                                $ii = 1;
                                foreach ($akun_hpp as $hpp) :
                                   
                                    $akun_child = $this->akun_m->get_by(array('parent' => $hpp['id']));
                                    $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                    $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$hpp['id'].'"';

                            ?>
                                <tr>
                                    <td></td>
                                    <td width="1%"><input type="hidden" name="akun_hpp[<?=$ii?>][id]" value="<?=$hpp['id']?>"><input type="hidden" name="akun_hpp[<?=$ii?>][akun_tipe]" value="<?=$hpp['akun_tipe']?>"><?=$hpp['no_akun']?></td>
                                    <td ><input type="hidden" name="akun_hpp[<?=$ii?>][nama]" value="<?=$hpp['nama']?>"><?=$hpp['nama']?></td>
                                    <td width="9%"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_hpp[<?=$ii?>][nominal_hpp]" value="0"  data-index="<?=$ii?>" class="form-control akun_hpp text-right"></div></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%"><input type="hidden" name="akun_hpp[<?=$ii?>][prosentase]" id="akun_hpp_prosentase_<?=$ii?>" value="0"><span id="akun_hpp_prosentase_<?=$ii?>"> 0% </span></td>
                                </tr>
                            <?php    
                                
                                $ii++;
                                endforeach;
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="inline-button-table"><input type="hidden" name="akun_hpp[<?=$ii?>][id]" value="hpp_<?=$ii?>"><input type="hidden" name="akun_hpp[<?=$ii?>][nama]" value="Persediaan Akhir"><input type="hidden" name="akun_hpp[<?=$ii?>][akun_tipe]" value="8">  Persediaan Akhir</td>
                                <td width="9%"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_hpp[<?=$ii?>][nominal_hpp]" value="0"  data-index="<?=$ii?>" class="form-control akun_hpp text-right"></div></td>
                                <td width="9%"></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="akun_hpp[<?=$ii?>][prosentase]" id="akun_hpp_prosentase_<?=$ii?>" value="0"><span id="akun_hpp_prosentase_<?=$ii?>"> 0% </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>TOTAL HPP</td>
                                <td></td>
                                <td width="5%" class="text-right"><input type="hidden" name="total_hpp" id="total_hpp" value="0"><span id="total_hpp">Rp. 0</span></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="prosentase_total_hpp" id="prosentase_total_hpp" value="0"><span id="prosentase_total_hpp" > 0% </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>LABA KOTOR</td>
                                <td></td>
                                <td width="9%"></td>
                                <td width="9%"><input type="hidden" name="laba_kotor" id="laba_kotor" value="0"><span id="laba_kotor">Rp. 0</span></td>
                                <td width="5%"><input type="hidden" id="prosentase_laba_kotor" name="prosentase_laba_kotor" value="0"><span id="prosentase_laba_kotor" > 0% </span></td>
                            </tr>
                            <tr>
                                <td class="inline-button-table"> Beban Operasional</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php

                                $i = 0;
                                foreach ($akun_beban as $beban) :
                                   
                                    $akun_child = $this->akun_m->get_by(array('parent' => $beban['id']));
                                    $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                    $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$beban['id'].'"';

                            ?>
                                <tr>
                                    <td></td>
                                    <td width="1%"><input type="hidden" name="akun_beban[<?=$i?>][id]" value="<?=$beban['id']?>"><input type="hidden" name="akun_beban[<?=$i?>][akun_tipe]" value="<?=$beban['akun_tipe']?>"><?=$beban['no_akun']?></td>
                                    <td ><input type="hidden" name="akun_beban[<?=$i?>][nama]" value="<?=$beban['nama']?>"><?=$beban['nama']?></td>
                                    <td width="9%"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_beban[<?=$i?>][nominal_beban]" value="0" data-index="<?=$i?>" class="form-control akun_beban text-right"></div></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%"><input type="hidden" name="akun_beban[<?=$i?>][prosentase]" id="akun_beban_prosentase_<?=$i?>" value="0"><span id="akun_beban_prosentase_<?=$i?>" > 0% </span></td>
                                </tr>
                            <?php    
                                
                                $i++;
                                endforeach;
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>TOTAL BEBAN OPERASIONAL</td>
                                <td></td>
                                <td width="5%"  class="text-right"><input type="hidden" name="total_beban_operasional" id="total_beban_operasional" value="0"><span id="total_beban_operasional">Rp. 0</span></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="prosentase_total_beban_operasional" id="prosentase_total_beban_operasional" value="0"><span id="prosentase_total_beban_operasional"> 0% </span></td>
                            </tr>
                            <tr>
                                <td class="inline-button-table"> Pendapatan Lain</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php

                                $i = 0;
                                foreach ($akun_pendapatan_lain as $pendapatan_lain) :
                                   
                                    $akun_child = $this->akun_m->get_by(array('parent' => $pendapatan_lain['id']));
                                    $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                    $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$pendapatan_lain['id'].'"';

                            ?>
                                <tr>
                                    <td></td>
                                    <td width="1%"><input type="hidden" name="akun_pendapatan_lain[<?=$i?>][id]" value="<?=$pendapatan_lain['id']?>"><input type="hidden" name="akun_pendapatan_lain[<?=$i?>][akun_tipe]" value="<?=$pendapatan_lain['akun_tipe']?>"><?=$pendapatan_lain['no_akun']?></td>
                                    <td ><input type="hidden" name="akun_pendapatan_lain[<?=$i?>][nama]" value="<?=$pendapatan_lain['nama']?>"><?=$pendapatan_lain['nama']?></td>
                                    <td width="9%"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_pendapatan_lain[<?=$i?>][nominal_pendapatan_lain]" value="0" data-index="<?=$i?>" class="form-control akun_pendapatan_lain text-right"></div></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%"><input type="hidden" name="akun_pendapatan_lain[<?=$i?>][prosentase]" id="akun_pendapatan_lain_prosentase_<?=$i?>" value="0"><span id="akun_pendapatan_lain_prosentase_<?=$i?>" > 0% </span></td>
                                </tr>
                            <?php    
                                
                                $i++;
                                endforeach;
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>TOTAL PENDAPATAN LAIN</td>
                                <td></td>
                                <td width="5%"  class="text-right"><input type="hidden" name="total_pendapatan_lain" id="total_pendapatan_lain" value="0"><span id="total_pendapatan_lain">Rp. 0</span></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="prosentase_total_pendapatan_lain" id="prosentase_total_pendapatan_lain" value="0"><span id="prosentase_total_pendapatan_lain" > 0% </span></td>
                            </tr>

                            <tr>
                                <td class="inline-button-table"> Beban Lain</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php

                                $i = 0;
                                foreach ($akun_beban_lain as $beban_lain) :
                                   
                                    $akun_child = $this->akun_m->get_by(array('parent' => $beban_lain['id']));
                                    $readonly = (count($akun_child) == 0)?'':'readonly="readonly"';
                                    $id = (count($akun_child) == 0)?'id="akun_parent"':'id="akun_'.$beban_lain['id'].'"';

                            ?>
                                <tr>
                                    <td></td>
                                    <td width="1%"><input type="hidden" name="akun_beban_lain[<?=$i?>][id]" value="<?=$beban_lain['id']?>"><input type="hidden" name="akun_beban_lain[<?=$i?>][akun_tipe]" value="<?=$beban_lain['akun_tipe']?>"><?=$beban_lain['no_akun']?></td>
                                    <td ><input type="hidden" name="akun_beban_lain[<?=$i?>][nama]" value="<?=$beban_lain['nama']?>"><?=$beban_lain['nama']?></td>
                                    <td width="9%"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_beban_lain[<?=$i?>][nominal_beban_lain]" value="0" data-index="<?=$i?>" class="form-control akun_beban_lain text-right"></div></td>
                                    <td width="5%"></td>
                                    <td width="9%"></td>
                                    <td width="5%"><input type="hidden" id="akun_beban_lain_prosentase_<?=$i?>" name="akun_beban_lain[<?=$i?>][prosentase]" value="0"><span id="akun_beban_lain_prosentase_<?=$i?>" > 0% </span></td>
                                </tr>
                                <?php    
                                
                                $i++;
                                endforeach;
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>TOTAL BEBAN LAIN</td>
                                <td></td>
                                <td width="5%" class="text-right"><input type="hidden" name="total_beban_lain" id="total_beban_lain" value="0"><span id="total_beban_lain">Rp. 0</span></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="prosentase_total_beban_lain" id="prosentase_total_beban_lain" value="0"><span id="prosentase_total_beban_lain" > 0% </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>TOTAL PENDAPATAN & BEBAN LAIN</td>
                                <td></td>
                                <td width="5%" class="text-right"><input type="hidden" name="total_pendapatan_beban_lain" id="total_pendapatan_beban_lain" value="0"><span id="total_pendapatan_beban_lain">Rp. 0</span></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="prosentase_total_pendapatan_beban_lain" id="prosentase_total_pendapatan_beban_lain" value="0"><span id="prosentase_total_pendapatan_beban_lain" > 0% </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>LABA/RUGI BERSIH SEBELUM PAJAK</td>
                                <td></td>
                                <td></td>
                                <td width="5%" class="text-right"><input type="hidden" name="labrug_sebelum_pajak" id="labrug_sebelum_pajak" value="0"><span id="labrug_sebelum_pajak">Rp. 0</span></td>
                                <td width="5%"><input type="hidden" name="prosentase_labrug_sebelum_pajak" id="prosentase_labrug_sebelum_pajak" value="0"><span id="prosentase_labrug_sebelum_pajak" > 0% </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Pajak Penghasilan Badan</td>
                                <td></td>
                                <td></td>
                                <td width="5%" class="text-right"><input type="hidden" name="pajak_penghasilan_badan" id="pajak_penghasilan_badan" value="0"><span id="pajak_penghasilan_badan">Rp. 0</span></td>
                                <td width="5%"><input type="hidden" name="prosentase_pajak_penghasilan_badan" id="prosentase_pajak_penghasilan_badan" value="0"><span id="prosentase_pajak_penghasilan_badan" > 0% </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>LABA/RUGI BERSIH SETELAH PAJAK</td>
                                <td></td>
                                <td></td>
                                <td width="5%" class="text-right"><input type="hidden" name="labrug_setelah_pajak" id="labrug_setelah_pajak" value="0"><span id="labrug_setelah_pajak">Rp. 0</span></td>
                                <td width="5%"><input type="hidden" name="prosentase_labrug_setelah_pajak" id="prosentase_labrug_setelah_pajak" value="0"><span id="prosentase_labrug_setelah_pajak" > 0% </span></td>
                            </tr>
                        </tbody>
                       
                        
                    </table>
                    
                        	
				</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
	</div>
</div>


<?=form_close()?>
