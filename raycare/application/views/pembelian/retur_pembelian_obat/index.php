<?php
	$form_attr = array(
	    "id"            => "form_add_retur_pembelian_obat", 
	    "name"          => "form_add_retur_pembelian_obat", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."pembelian/retur_pembelian/save", $form_attr, $hidden);
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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Retur Pembelian', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan menyimpan data retur penjualan ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="<?=base_url()?>pembelian/retur_pembelian/history"><i class="fa fa-undo"></i>  <?=translate("History", $this->session->userdata("language"))?></a>
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
                            <label class="col-md-12"><?=translate("Tanggal Retur", $this->session->userdata("language"))?><span class="required" style="color:red;"> *</span>:</label>
                            
                            <div class="col-md-12">
                                <div class="input-group date" id="tanggal">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" readonly required value="<?=date('d M Y')?>">
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button" ><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Jenis Retur", $this->session->userdata("language"))?><span class="required" style="color:red;"> *</span>:</label>
                            
                            <div class="col-md-12">
                                <?php
                                    $option_tipe = array(
                                        '' => 'Pilih...',
                                        '1' => 'Tukar Barang',
                                    );


                                    echo form_dropdown("tipe_retur", $option_tipe, "", 'id="tipe_retur" name="tipe_retur" class="form-control" required="required" ');

                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Supplier", $this->session->userdata("language"))?><span class="required" style="color:red;"> *</span>:</label>
                            
                            <div class="col-md-12">
                                <?php
                                    $data_supplier = $this->supplier_m->get_by(array('is_active' => 1));
                                    $data_supplier = object_to_array($data_supplier);

                                    $option_supplier = array('' => 'Pilih...');

                                    foreach ($data_supplier as $supplier) {
                                        $option_supplier[$supplier['id']] = $supplier['kode'].' - '.$supplier['nama'];
                                    }

                                    echo form_dropdown("supplier_id", $option_supplier, "", 'id="supplier_id" name="supplier_id" class="form-control" required="required" ');

                                ?>
                            </div>
                        </div>
						
						<div class="form-group">
                            <label class="col-md-12"><?=translate("No. Pembelian", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input class="form-control" id="no_pembelian" name="no_pembelian" value="" readonly placeholder="<?=translate("No. Pembelian", $this->session->userdata("language"))?>" required>
                                    <span class="input-group-btn">
                                        <a class="btn grey-cascade pilih-pembelian" title="<?=translate('Pilih PO', $this->session->userdata('language'))?>">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </span>
                                </div>
                                <input class="form-control hidden" id="pembelian_id" name="pembelian_id" value="" required >
                            </div>  
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Tanggal Beli", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                                    <input class="form-control" id="tanggal_pesan" name="tanggal_pesan" value="" readonly >
                            </div>  
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> : <span style="color:red;" class="required">*</span></label>
                            <div class="col-md-12">
                                <textarea name="keterangan" id="keterangan" class="form-control" > </textarea>
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
                    <table class="table table-bordered" id="tabel_tambah_item">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%"><?=translate("Kode", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="25%"><?=translate("Nama", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("BN", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("ED", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("Jml Terima", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("Harga Beli", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("Jml Retur", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("Subtotal Retur", $this->session->userdata("language"))?></th>
                            </tr>
                        </thead>
                                
                        <tbody>
                        <tr>
                            <td colspan="9" class="text-center"> There are no item(s) on table  </td>
                        </tr>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <input name="total" id="total_hidden" type="hidden" class="form-control"></input>
                                <th colspan="7" class="text-right">Total</th>
                                <th colspan="2" class="text-right" id="total">Rp. 0,-</th>
                            </tr>
                           
                            
                        </tfoot>
                        
                    </table>

                </div>
            </div>
            
        </div><!-- end of <div class="col-md-8"> -->
		
	</div><!-- end of <div class="row"> -->

	</div>
</div>


<?=form_close()?>

<div id="popover_item_content" class="row">
    <div class="col-md-12">
       	<table class="table table-condensed table-striped table-bordered table-hover" id="table_pembelian">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Tgl', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('No. PO', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Supplier', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" widht="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>      
    </div>
</div> 




