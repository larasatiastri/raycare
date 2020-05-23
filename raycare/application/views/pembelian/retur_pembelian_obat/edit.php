<?php
	$form_attr = array(
	    "id"            => "form_edit_retur_pembelian_obat", 
	    "name"          => "form_edit_retur_pembelian_obat", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "edit",
        "id"       => $pk_value
    );

    echo form_open(base_url()."pembelian/retur_pembelian_obat/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');

    $hidden = '';
    if($form_data['tipe'] == 1){
        $hidden = 'class="hidden"';
    }
?>

<div class="form-body">
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Retur Pembelian Obat / Alkes', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan menyimpan data retur penjualan ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="<?=base_url()?>pembelian/retur_pembelian_obat/history"><i class="fa fa-undo"></i>  <?=translate("History", $this->session->userdata("language"))?></a>
			<a id="confirm_save" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
	        <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<?=translate('Informasi', $this->session->userdata('language'))?>
					</div>
				</div>
				<div class="portlet-body form">
				    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("No. Retur", $this->session->userdata("language"))?>:</label>
                            <label class="col-md-12"><?=$form_data['no_retur']?></label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("No. Surat Jalan", $this->session->userdata("language"))?>:</label>
                            <label class="col-md-12"><?=$form_data['no_surat_jalan']?></label>
                        </div>
                       
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Supplier", $this->session->userdata("language"))?>:</label>
                            <label class="col-md-12"><?=$supplier['nama']?></label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("No. PO", $this->session->userdata("language"))?>:</label>
                            <label class="col-md-12"><?=$form_data_po['no_pembelian']?></label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Tanggal Beli", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                           <label class="col-md-12"><?=$form_data_po['no_pembelian']?></label>
                        </div>
                         <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Tanggal Retur", $this->session->userdata("language"))?><span class="required" style="color:red;"> *</span>:</label>
                            
                            <div class="col-md-12">
                                <div class="input-group date" id="tanggal">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" readonly required value="<?=date('d M Y',  strtotime($form_data['tanggal']))?>">
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Jenis Retur", $this->session->userdata("language"))?><span class="required" style="color:red;"> *</span>:</label>
                            
                            <div class="col-md-12">
                                <?php
                                    $option_tipe = array(
                                        '' => 'Pilih...',
                                        '1' => 'Tukar Barang',
                                        '2' => 'Kembali Uang',
                                    );


                                    echo form_dropdown("tipe_retur", $option_tipe, $form_data['tipe'], 'id="tipe_retur" name="tipe_retur" class="form-control" required="required" ');

                                ?>
                            </div>
                        </div>
                        <div id="div_cn" <?=$hidden?>>
                            <div class="form-group">
                                <label class="col-md-12 bold"><?=translate("No. CN", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                                <div class="col-md-12">
                                    <input name="no_cn" id="no_cn" class="form-control" value="<?=$form_data['no_cn']?>">
                                </div>  
                            </div> 
                            <div class="form-group">
                                <label class="col-md-12 bold"><?=translate("Nominal CN", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                                <div class="col-md-12">
                                    <input name="nominal_cn" id="nominal_cn" class="form-control" value="<?=$form_data['nominal_cn']?>">
                                </div>  
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 bold"><?=translate("Upload CN", $this->session->userdata("language"))?> <span>:</span></label>
                                <div class="col-md-12">
                                    <input type="hidden" name="url_cn" id="url_cn" required>
                                    <div id="upload">
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"><?=translate('Pilih Gambar', $this->session->userdata('language'))?></span>   
                                            <input type="file" name="upl" id="upl" data-url="<?=base_url()?>upload_new/upload_photo" />
                                        </span>

                                        <ul class="ul-img">
                                        <!-- The file uploads will be shown here -->
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 bold"><?=translate("Keterangan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                                <textarea name="keterangan" id="keterangan" class="form-control" value="<?=$form_data['keterangan']?>"><?=$form_data['keterangan']?> </textarea>
                            </div>  
                        </div>
						
                        	
				</div><!-- end of <div class="portlet-body"> -->	
			</div>
		</div><!-- end of <div class="col-md-4"> -->
    </div>
		<div class="col-md-9">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject"><?=translate("Daftar Item", $this->session->userdata("language"))?></span>
                    </div>                      
                </div>
                <div class="portlet-body"> 
                     <table class="table table-bordered" id="table_pengeluaran_item">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%"><?=translate("Kode", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="25%"><?=translate("Nama", $this->session->userdata("language"))?></th>
                                    <th class="text-left" width="1%"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                                    <th class="text-left" width="1%"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                                    <th class="text-left" width="8%"><?=translate("BN", $this->session->userdata("language"))?></th>
                                    <th class="text-left" width="8%"><?=translate("ED", $this->session->userdata("language"))?></th>
                                    <th class="text-center" width="15%"><?=translate("Harga", $this->session->userdata("language"))?></th>
                                    <th class="text-left" width="10%"><?=translate("Sub Total", $this->session->userdata("language"))?></th>
                                </tr>
                            </thead>
                                    
                            <tbody>
                                <?php
                                    $grand_total = 0;
                                    if(count($form_data_detail)){
                                        foreach ($form_data_detail as $data_detail) {
                                            ?>
                                            <tr>
                                                <td><?=$data_detail['kode']?></td>
                                                <td><?=$data_detail['nama']?></td>
                                                <td><?=$data_detail['jumlah']?></td>
                                                <td><?=$data_detail['nama_satuan']?></td>
                                                <td><?=$data_detail['bn_sn_lot']?></td>
                                                <td><?=date('d M Y', strtotime($data_detail['expire_date']))?></td>
                                                <td class="text-right"><?=formatrupiah($data_detail['hpp'])?></td>
                                                <td class="text-right"><?=formatrupiah($data_detail['hpp']*$data_detail['jumlah'])?></td>

                                            </tr>
                                            <?php
                                                $grand_total = $grand_total + ($data_detail['hpp']*$data_detail['jumlah']);
                                        }
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <th colspan="7" class="text-right">Grand Total</th>
                                <th class="text-right"><?=formatrupiah($grand_total)?></th>
                            </tfoot>
                        </table>


                </div>
            </div>
            
        </div><!-- end of <div class="col-md-8"> -->
		
	</div><!-- end of <div class="row"> -->

	</div>
</div>


<?=form_close()?>




