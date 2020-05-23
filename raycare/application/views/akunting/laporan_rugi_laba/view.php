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
			<i class="icon-search font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Laporan Rugi Laba', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan membuat laporan rugi laba ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="<?=base_url()?>akunting/laporan_rugi_laba"><i class="fa fa-chevron-left"></i>  <?=translate("Kembali", $this->session->userdata("language"))?></a>
			
		</div>
	</div>
	<div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <div class="form-group">
                            <label class="control-label col-md-3"><?=translate("Tanggal", $this->session->userdata("language"))?>:</label>
                            
                            <div class="col-md-6">
                                <label class="control-label"><?=date('d M Y', strtotime($form_data['tanggal']))?></label>
                            </div>
                        </div>
                </div>
            </div>
        </div>
		<div class="col-md-12">
			<div class="portlet light bordered">
				
				<div class="portlet-body">
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
                                    <td width="1%"><?=$pendapatan['no_akun']?></td>
                                    <td ><?=$pendapatan['akun_nama']?></td>
                                    <td width="9%" class="inline-button-table text-right"><?=formatrupiah($pendapatan['nominal'])?></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%" class="inline-button-table"><span id="akun_pendapatan_prosentase_<?=$i?>" > <?=formatkoma($pendapatan['prosentase'])?> % </span></td>
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
                                <td width="5%" class="inline-button-table text-right"><span id="total_pendapatan"><?=formatrupiah($form_data['total_pendapatan'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%" class="inline-button-table"><span name="prosentase_total_pendapatan" id="prosentase_total_pendapatan" > <?=formatkoma($form_data['prosentase_pendapatan'])?> % </span></td>
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
                                    <td width="1%"><?=$hpp['no_akun']?></td>
                                    <td ><?=$hpp['akun_nama']?></td>
                                    <td width="9%" class="inline-button-table text-right"><?=formatrupiah($hpp['nominal'])?></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%" class="inline-button-table"><span id="akun_hpp_prosentase_<?=$i?>" > <?=formatkoma($hpp['prosentase'])?> % </span></td>
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
                                <td width="5%" class="inline-button-table text-right"><span id="total_hpp"><?=formatrupiah($form_data['total_hpp'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%" class="inline-button-table"><span name="prosentase_hpp" id="prosentase_hpp" > <?=formatkoma($form_data['prosentase_hpp'])?> % </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>LABA KOTOR</td>
                                <td></td>
                                <td width="9%"></td>
                                <td width="9%"class="inline-button-table text-right"><span id="laba_kotor"><?=formatrupiah($form_data['laba_kotor'])?></span></td>
                                <td width="5%" class="inline-button-table"><span id="prosentase_laba_kotor" > <?=formatkoma($form_data['prosentase_laba_kotor'])?> % </span></td>
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
                                    <td width="1%"><?=$beban['no_akun']?></td>
                                    <td ><?=$beban['akun_nama']?></td>
                                    <td width="9%" class="inline-button-table text-right"><?=formatrupiah($beban['nominal'])?></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%" class="inline-button-table"><span id="akun_beban_prosentase_<?=$i?>" > <?=formatkoma($beban['prosentase'])?> % </span></td>
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
                                <td width="5%" class="inline-button-table text-right"><span id="total_hpp"><?=formatrupiah($form_data['total_beban'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%" class="inline-button-table"><span name="prosentase_beban" id="prosentase_beban" > <?=formatkoma($form_data['prosentase_beban'])?> % </span></td>
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
                                    <td width="1%"><?=$pendapatan_lain['no_akun']?></td>
                                    <td ><?=$pendapatan_lain['akun_nama']?></td>
                                    <td width="9%" class="inline-button-table text-right"><?=formatrupiah($pendapatan_lain['nominal'])?></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%" class="inline-button-table"><span id="akun_pendapatan_lain_prosentase_<?=$i?>" > <?=formatkoma($pendapatan_lain['prosentase'])?> % </span></td>
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
                                <td width="5%" class="inline-button-table text-right"><span id="total_pendapatan_lain"><?=formatrupiah($form_data['total_pendapatan_lain'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%" class="inline-button-table"><span name="prosentase_pendapatan_lain" id="prosentase_pendapatan_lain" > <?=formatkoma($form_data['prosentase_pendapatan_lain'])?> % </span></td>
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
                                    <td width="1%"><?=$beban_lain['no_akun']?></td>
                                    <td ><?=$beban_lain['akun_nama']?></td>
                                    <td width="9%" class="inline-button-table text-right"><?=formatrupiah($beban_lain['nominal'])?></td>
                                    <td width="9%"></td>
                                    <td width="9%"></td>
                                    <td width="5%" class="inline-button-table"><span id="akun_beban_lain_prosentase_<?=$i?>" > <?=formatkoma($beban_lain['prosentase'])?> % </span></td>
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
                                <td width="5%" class="inline-button-table text-right"><span id="total_beban_lain"><?=formatrupiah($form_data['total_beban_lain'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%" class="inline-button-table"><span name="prosentase_beban_lain" id="prosentase_beban_lain" > <?=formatkoma($form_data['prosentase_beban_lain'])?> % </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>TOTAL PENDAPATAN & BEBAN LAIN</td>
                                <td></td>
                                <td width="5%" class="inline-button-table text-right"><span id="total_beban_lain"><?=formatrupiah( $form_data['total_pendapatan_lain'] - $form_data['total_beban_lain'])?></span></td>
                                <td width="9%"></td>
                                <td width="5%" class="inline-button-table"><span name="prosentase_beban_lain" id="prosentase_beban_lain" > <?=formatkoma($form_data['prosentase_pendapatan_lain'] - $form_data['prosentase_beban_lain'] )?> % </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>LABA/RUGI BERSIH SEBELUM PAJAK</td>
                                <td></td>
                                <td></td>
                                <td width="5%" class="inline-button-table text-right"><span id="labrug_sebelum_pajak"><?=formatrupiah( $form_data['laba_rugi_bersih_sebelum_pajak'])?></span></td>
                                <td width="5%"><span id="prosentase_labrug_sebelum_pajak" ><?=formatkoma( $form_data['prosentase_labrug_sebelum_pajak'])?> % </span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Pajak Penghasilan Badan</td>
                                <td></td>
                                <td></td>
                                <td width="5%" class="inline-button-table text-right"><span id="pajak_penghasilan_badan"><?=formatrupiah( $form_data['pajak_penghasilan_badan'])?></span></td>
                                <td width="5%"><span id="prosentase_pajak_penghasilan_badan" ><?=formatkoma( $form_data['prosentase_pajak_penghasilan_badan'])?> %</span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>LABA/RUGI BERSIH SETELAH PAJAK</td>
                                <td></td>
                                <td></td>
                                <td width="5%" class="inline-button-table text-right"><span id="laba_rugi_bersih_setelah_pajak"><?=formatrupiah( $form_data['laba_rugi_bersih_setelah_pajak'])?></span></td>
                                <td width="5%"><span id="prosentase_labrug_setelah_pajak" ><?=formatkoma( $form_data['prosentase_labrug_setelah_pajak'])?> %</span></td>
                            </tr>
                        </tbody>
                       
                        
                    </table>
                    
                        	
				</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
	</div>
</div>


<?=form_close()?>
