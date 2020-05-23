<?php
	$form_attr = array(
	    "id"            => "form_add_box_paket", 
	    "name"          => "form_add_box_paket", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."apotik/master_box_paket/save", $form_attr, $hidden);
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
	$form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));
	
	$flash_form_data  = $this->session->flashdata('form_data');
	$flash_form_error = $this->session->flashdata('form_error');
?>


<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
			<i class="icon-plus font-blue-sharp"></i>
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Master Box Paket', $this->session->userdata('language'))?></span>
		</div>
	</div>
    <div class="portlet-body form">
        
   
    <div class="form-body">
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
							<label class="col-md-12"><?=translate("Nama Paket", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
							<div class="col-md-12">
                                    <input class="form-control" id="nama_paket" name="nama_paket" value="" required>
                                    <input type="hidden" class="form-control" id="harga_hidden" name="harga_hidden" value="">
                                    <input type="hidden" class="form-control" id="box_paket_id_hidden" name="box_paket_id_hidden" value="">
									<input type="hidden" class="form-control" id="tipe_paket" name="tipe_paket" value="">
							</div>	
						</div>	
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Jenis Paket", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
                            <div class="col-md-12">
                                <?php
                                    $jenis_paket_option = array(
                                        ''          => translate('Pilih', $this->session->userdata('language')).'..',
                                        '1'          => 'Tindakan HD',
                                        '2'          => 'Tindakan Transfusi',
                                    );

                                    echo form_dropdown('tipe_paket', $jenis_paket_option, '','id="tipe_paket" class="form-control" required');
                                ?>
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
                    
                    <?php
                        $option_satuan = array();
                        $btn_search         = '<span class="input-group-btn"><button type="button" title="'.translate('Pilih Item', $this->session->userdata('language')).'" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></span>';
                        $btn_del            = '<div class="text-center"><button class="btn red-intense del-this" title="Delete"><i class="fa fa-times"></i></button></div>';
                        
                        $btn_add_identitas     = '<span class="input-group-btn"><button type="button" data-toggle="modal" data-target="#popup_modal_jumlah_keluar" href="'.base_url().'apotik/box_paket/add_identitas/item_row_{0}" class="btn blue-chambray add-identitas" name="item[{0}][identitas]" title="Tambah Jumlah"><i class="fa fa-info"></i></button></span>'; 
   
                        $attrs_item_id = array(
                            'id'          => 'item_id_{0}',
                            'name'        => 'item[{0}][item_id]',
                            'class'       => 'form-control hidden',
                        );

                        $attrs_item_code = array(
                            'id'          => 'item_kode_{0}',
                            'name'        => 'item[{0}][kode]',
                            'class'       => 'form-control',
                            'width'       => '50%',
                            'readonly' => 'readonly',
                        );

                        $attrs_item_name = array(
                            'id'          => 'item_name_{0}',
                            'name'        => 'item[{0}][name]',
                            'class'       => 'form-control',
                            'readonly'    => 'readonly',
                        );

                        $attrs_item_jumlah = array(
                            'id'       => 'item_qty_{0}',
                            'name'     => 'item[{0}][qty]',
                            'class'    => 'form-control',
                            'value'    => 0,
                            'min'      => 0,
                            'type'     => 'number',
                        );

                        $attrs_item_harga = array(
                            'id'       => 'item_harga_{0}',
                            'name'     => 'item[{0}][harga]',
                            'class'    => 'form-control',
                            'type'     => 'hidden',
                             'value'    => 0,
                        );
                        $attrs_item_sub_total = array(
                            'id'       => 'item_sub_total_{0}',
                            'name'     => 'item[{0}][sub_total]',
                            'class'    => 'form-control',
                            'type'     => 'hidden',
                            'value'    => 0,
                        );



                        // item row column
                        $item_cols = array(// style="width:156px;
                            'item_code'   => '<div class="input-group">'.form_input($attrs_item_code).form_input($attrs_item_id).$btn_search.'</div>',
                            'item_name'   => form_input($attrs_item_name).'<div id="identitas_row" class="hidden"></div>',
                            'item_satuan' => form_dropdown('item[{0}][satuan_id]',  $option_satuan, "", "id='item_satuan_id_{0}' class='form-control'").form_input($attrs_item_harga),
                            'item_jumlah' => form_input($attrs_item_jumlah),
                            'action'      => $btn_del,
                        );

                        $item_row_template =  '<tr id="item_row_{0}" class="row_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';

                    ?>
                   
                    <span id="tpl_item_row" class="hidden"><?=htmlentities($item_row_template)?></span>
                    
                    <table class="table table-bordered" id="tabel_tambah_item">
                        <thead>
                            <tr>
                                <th class="text-center" width="15%"><?=translate("Kode", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="25%"><?=translate("Nama", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="15%"><?=translate("Satuan", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("Jumlah", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="1%" ><?=translate("Aksi", $this->session->userdata("language"))?></th>
                            </tr>
                        </thead>
                                
                        <tbody>
                            
                        </tbody>
                        
                    </table>

                </div>
            </div>
            
        </div><!-- end of <div class="col-md-8"> -->
		</div><!-- end of <div class="row"> -->
	
    <?php $msg = translate("Apakah anda yakin akan membuat box paket ini?",$this->session->userdata("language"));?>
        <div class="form-actions right">   
            <a class="btn default" href="<?=base_url()?>apotik/master_box_paket/history"><i class="fa fa-undo"></i>  <?=translate("History", $this->session->userdata("language"))?></a>
            <a id="confirm_save" class="btn btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal"><i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?></a>
            <button type="submit" id="save" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
        </div>
	</div>
     </div>
    
    
</div>


<?=form_close()?>


<div id="popover_item_content" class="row">
    <div class="col-md-12">
       	<table class="table table-condensed table-striped table-bordered table-hover" id="table_pilih_item">
            <thead>
                <tr role="row">
                    <th><div class="text-center"><?=translate('ID', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Kode', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('Nama', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" widht="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>      
    </div>
</div> 




