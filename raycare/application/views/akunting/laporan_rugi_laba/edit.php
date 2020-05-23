<?php
	$form_attr = array(
	    "id"            => "form_edit_laporan_rugi_laba", 
	    "name"          => "form_edit_laporan_rugi_laba", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "edit",
        "id"        => $pk_value
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
		<?php $msg = translate("Apakah anda yakin akan mengubah laporan rugi laba ini?",$this->session->userdata("language"));?>
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
                                <input type="text" class="form-control" id="tanggal" name="tanggal" readonly required value="<?=date('d-M-Y', strtotime($form_data['tanggal']))?>">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3"><?=translate("Nomor", $this->session->userdata("language"))?>:</label>
                        
                        <div class="col-md-6">
                            <label class="control-label"><?=$form_data['nomor']?>:</label>
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
                            ?>
                                <tr>
                                    <td></td>
                                    <td width="1%"><input type="hidden" name="akun_pendapatan[<?=$i?>][id_detail]" value="<?=$pendapatan['id']?>"><input type="hidden" name="akun_pendapatan[<?=$i?>][id]" value="<?=$pendapatan['akun_id']?>"><input type="hidden" name="akun_pendapatan[<?=$i?>][akun_tipe]" value="<?=$pendapatan['kategori_id']?>"><?=$pendapatan['no_akun']?></td>
                                    <td ><input type="hidden" name="akun_pendapatan[<?=$i?>][nama]" value="<?=$pendapatan['akun_nama']?>"><?=$pendapatan['akun_nama']?></td>
                                    <td width="9%"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_pendapatan[<?=$i?>][nominal_pendapatan]" value="<?=$pendapatan['nominal']?>" data-index="<?=$i?>" class="form-control akun_pendapatan text-right"></div></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%"><input type="hidden" name="akun_pendapatan[<?=$i?>][prosentase]" id="akun_pendapatan_prosentase_<?=$i?>" value="<?=$pendapatan['prosentase']?>"><span id="akun_pendapatan_prosentase_<?=$i?>" > <?=formatkoma($pendapatan['prosentase'])?> % </span></td>
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
                                <td width="5%" class="inline-button-table text-right"><input type="hidden" name="total_pendapatan" id="total_pendapatan" value="<?=$form_data['total_pendapatan']?>"><span id="total_pendapatan"><?=formatrupiah($form_data['total_pendapatan'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%" class="inline-button-table"><input type="hidden" name="prosentase_total_pendapatan" id="prosentase_total_pendapatan" value="<?=$form_data['prosentase_pendapatan']?>"><span name="prosentase_total_pendapatan" id="prosentase_total_pendapatan" > <?=formatkoma($form_data['prosentase_pendapatan'])?> % </span></td>
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
                            <?php

                                $i = 0;
                                foreach ($akun_hpp as $hpp) :
                            ?>
                                <tr>
                                    <td></td>
                                    <td width="1%"><input type="hidden" name="akun_hpp[<?=$i?>][id_detail]" value="<?=$hpp['id']?>"><input type="hidden" name="akun_hpp[<?=$i?>][id]" value="<?=$hpp['akun_id']?>"><input type="hidden" name="akun_hpp[<?=$i?>][akun_tipe]" value="<?=$hpp['kategori_id']?>"><?=$hpp['no_akun']?></td>
                                    <td ><input type="hidden" name="akun_hpp[<?=$i?>][nama]" value="<?=$hpp['akun_nama']?>"><?=$hpp['akun_nama']?></td>
                                    <td width="9%"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_hpp[<?=$i?>][nominal_hpp]" value="<?=$hpp['nominal']?>" data-index="<?=$i?>" class="form-control akun_hpp text-right"></div></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%"><input type="hidden" name="akun_hpp[<?=$i?>][prosentase]" id="akun_hpp_prosentase_<?=$i?>" value="<?=$hpp['prosentase']?>"><span id="akun_hpp_prosentase_<?=$i?>" > <?=formatkoma($hpp['prosentase'])?> % </span></td>
                                </tr>
                            <?php    
                                
                                $i++;
                                endforeach;
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>TOTAL HPP</td>
                                <td></td>
                                <td width="5%" class="inline-button-table text-right"><input type="hidden" name="total_hpp" id="total_hpp" value="<?=$form_data['total_hpp']?>"><span id="total_hpp"><?=formatrupiah($form_data['total_hpp'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="prosentase_total_hpp" id="prosentase_total_hpp" value="<?=$form_data['prosentase_hpp']?>"><span id="prosentase_total_hpp" > <?=formatkoma($form_data['prosentase_hpp'])?> % </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>LABA KOTOR</td>
                                <td></td>
                                <td width="9%"></td>
                                <td width="9%" class="inline-button-table text-right"><input type="hidden" name="laba_kotor" id="laba_kotor" value="<?=$form_data['laba_kotor']?>"><span id="laba_kotor"><?=formatrupiah($form_data['laba_kotor'])?></span></td>
                                <td width="5%"><input type="hidden" id="prosentase_laba_kotor" name="prosentase_laba_kotor" value="<?=$form_data['prosentase_laba_kotor']?>"><span id="prosentase_laba_kotor" > <?=formatkoma($form_data['prosentase_laba_kotor'])?> % </span></td>
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
                            ?>
                                <tr>
                                    <td></td>
                                    <td width="1%"><input type="hidden" name="akun_beban[<?=$i?>][id_detail]" value="<?=$beban['id']?>"><input type="hidden" name="akun_beban[<?=$i?>][id]" value="<?=$beban['akun_id']?>"><input type="hidden" name="akun_beban[<?=$i?>][akun_tipe]" value="<?=$beban['kategori_id']?>"><?=$beban['no_akun']?></td>
                                    <td ><input type="hidden" name="akun_beban[<?=$i?>][nama]" value="<?=$beban['akun_nama']?>"><?=$beban['akun_nama']?></td>
                                    <td width="9%" class="inline-button-table text-right"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_beban[<?=$i?>][nominal_beban]" value="<?=$beban['nominal']?>" data-index="<?=$i?>" class="form-control akun_beban text-right"></div></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%"><input type="hidden" name="akun_beban[<?=$i?>][prosentase]" id="akun_beban_prosentase_<?=$i?>" value="<?=$beban['prosentase']?>"><span id="akun_beban_prosentase_<?=$i?>" > <?=formatkoma($beban['prosentase'])?> % </span></td>
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
                                <td width="5%"  class="inline-button-table text-right"><input type="hidden" name="total_beban_operasional" id="total_beban_operasional" value="<?=$form_data['total_beban']?>"><span id="total_beban_operasional"><?=formatrupiah($form_data['total_beban'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="prosentase_total_beban_operasional" id="prosentase_total_beban_operasional" value="<?=$form_data['prosentase_beban']?>"><span id="prosentase_total_beban_operasional"> <?=formatkoma($form_data['prosentase_beban'])?> % </span></td>
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
                            ?>
                                <tr>
                                    <td></td>
                                    <td width="1%"><input type="hidden" name="akun_pendapatan_lain[<?=$i?>][id_detail]" value="<?=$pendapatan_lain['id']?>"><input type="hidden" name="akun_pendapatan_lain[<?=$i?>][id]" value="<?=$pendapatan_lain['akun_id']?>"><input type="hidden" name="akun_pendapatan_lain[<?=$i?>][akun_tipe]" value="<?=$pendapatan_lain['kategori_id']?>"><?=$pendapatan_lain['no_akun']?></td>
                                    <td ><input type="hidden" name="akun_pendapatan_lain[<?=$i?>][nama]" value="<?=$pendapatan_lain['akun_nama']?>"><?=$pendapatan_lain['akun_nama']?></td>
                                    <td width="9%" class="inline-button-table text-right"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_pendapatan_lain[<?=$i?>][nominal_pendapatan_lain]" value="<?=$pendapatan_lain['nominal']?>" data-index="<?=$i?>" class="form-control akun_pendapatan_lain text-right"></div></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%"><input type="hidden" name="akun_pendapatan_lain[<?=$i?>][prosentase]" id="akun_pendapatan_lain_prosentase_<?=$i?>" value="<?=$pendapatan_lain['prosentase']?>"><span id="akun_pendapatan_lain_prosentase_<?=$i?>" > <?=formatkoma($pendapatan_lain['prosentase'])?> % </span></td>
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
                                <td width="5%"  class="inline-button-table text-right"><input type="hidden" name="total_pendapatan_lain" id="total_pendapatan_lain" value="<?=$form_data['total_pendapatan_lain']?>"><span id="total_pendapatan_lain"><?=formatrupiah($form_data['total_pendapatan_lain'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="prosentase_total_pendapatan_lain" id="prosentase_total_pendapatan_lain" value="<?=$form_data['prosentase_pendapatan_lain']?>"><span id="prosentase_total_pendapatan_lain" > <?=formatkoma($form_data['prosentase_pendapatan_lain'])?> % </span></td>
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
                            ?>
                                <tr>
                                    <td></td>
                                    <td width="1%"><input type="hidden" name="akun_beban_lain[<?=$i?>][id_detail]" value="<?=$beban_lain['id']?>"><input type="hidden" name="akun_beban_lain[<?=$i?>][id]" value="<?=$beban_lain['akun_id']?>"><input type="hidden" name="akun_beban_lain[<?=$i?>][akun_tipe]" value="<?=$beban_lain['kategori_id']?>"><?=$beban_lain['no_akun']?></td>
                                    <td ><input type="hidden" name="akun_beban_lain[<?=$i?>][nama]" value="<?=$beban_lain['akun_nama']?>"><?=$beban_lain['akun_nama']?></td>
                                    <td width="9%" class="inline-button-table text-right"><div class="input-group"><span class="input-group-addon">&nbsp;Rp&nbsp;</span><input type="number" name="akun_beban_lain[<?=$i?>][nominal_beban_lain]" value="<?=$beban_lain['nominal']?>" data-index="<?=$i?>" class="form-control akun_beban_lain text-right"></div></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%"><input type="hidden" name="akun_beban_lain[<?=$i?>][prosentase]" id="akun_beban_lain_prosentase_<?=$i?>" value="<?=$beban_lain['prosentase']?>"><span id="akun_beban_lain_prosentase_<?=$i?>" > <?=formatkoma($beban_lain['prosentase'])?> % </span></td>
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
                                <td width="5%" class="inline-button-table text-right"><input type="hidden" name="total_beban_lain" id="total_beban_lain" value="<?=$form_data['total_beban_lain']?>"><span id="total_beban_lain"><?=formatrupiah($form_data['total_beban_lain'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="prosentase_total_beban_lain" id="prosentase_total_beban_lain" value="<?=$form_data['prosentase_beban_lain']?>"><span id="prosentase_total_beban_lain" > <?=formatkoma($form_data['prosentase_beban_lain'])?> % </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>TOTAL PENDAPATAN & BEBAN LAIN</td>
                                <td></td>
                                <td width="5%" class="inline-button-table text-right"><input type="hidden" name="total_pendapatan_beban_lain" id="total_pendapatan_beban_lain" value="<?=($form_data['total_pendapatan_lain'] - $form_data['total_beban_lain'])?>"><span id="total_pendapatan_beban_lain"><?=formatrupiah($form_data['total_pendapatan_lain'] - $form_data['total_beban_lain'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%"><input type="hidden" name="prosentase_total_pendapatan_beban_lain" id="prosentase_total_pendapatan_beban_lain" value="<?=($form_data['prosentase_pendapatan_lain'] - $form_data['prosentase_beban_lain'])?>"><span id="prosentase_total_pendapatan_beban_lain" > <?=formatkoma($form_data['prosentase_pendapatan_lain'] - $form_data['prosentase_beban_lain'])?> % </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>LABA/RUGI BERSIH SEBELUM PAJAK</td>
                                <td></td>
                                <td></td>
                                <td width="5%" class="inline-button-table text-right"><input type="hidden" name="labrug_sebelum_pajak" id="labrug_sebelum_pajak" value="<?=$form_data['laba_rugi_bersih_sebelum_pajak']?>"><span id="labrug_sebelum_pajak"><?=formatrupiah($form_data['laba_rugi_bersih_sebelum_pajak'])?></span></td>
                                <td width="5%"><input type="hidden" name="prosentase_labrug_sebelum_pajak" id="prosentase_labrug_sebelum_pajak" value="<?=$form_data['prosentase_labrug_sebelum_pajak']?>"><span id="prosentase_labrug_sebelum_pajak" > <?=formatkoma($form_data['prosentase_labrug_sebelum_pajak'])?> % </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Pajak Penghasilan Badan</td>
                                <td></td>
                                <td></td>
                                <td width="5%" class="inline-button-table text-right"><input type="hidden" name="pajak_penghasilan_badan" id="pajak_penghasilan_badan" value="<?=$form_data['pajak_penghasilan_badan']?>"><span id="pajak_penghasilan_badan"><?=formatrupiah($form_data['pajak_penghasilan_badan'])?></span></td>
                                <td width="5%"><input type="hidden" name="prosentase_pajak_penghasilan_badan" id="prosentase_pajak_penghasilan_badan" value="<?=$form_data['prosentase_pajak_penghasilan_badan']?>"><span id="prosentase_pajak_penghasilan_badan" > <?=formatkoma($form_data['prosentase_pajak_penghasilan_badan'])?> % </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>LABA/RUGI BERSIH SETELAH PAJAK</td>
                                <td></td>
                                <td></td>
                                <td width="5%" class="inline-button-table text-right"><input type="hidden" name="labrug_setelah_pajak" id="labrug_setelah_pajak" value="<?=$form_data['laba_rugi_bersih_setelah_pajak']?>"><span id="labrug_setelah_pajak"><?=formatrupiah($form_data['laba_rugi_bersih_setelah_pajak'])?></span></td>
                                <td width="5%"><input type="hidden" name="prosentase_labrug_setelah_pajak" id="prosentase_labrug_setelah_pajak" value="<?=$form_data['prosentase_labrug_setelah_pajak']?>"><span id="prosentase_labrug_setelah_pajak" > <?=formatkoma($form_data['prosentase_labrug_setelah_pajak'])?> % </span></td>
                            </tr>
                        </tbody>
                       
                        
                    </table>
                    
                        	
				</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
	</div>
</div>


<?=form_close()?>
