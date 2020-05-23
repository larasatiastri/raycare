<?php
	$form_attr = array(
	    "id"            => "form_add_pengecekan_sisa_box_paket", 
	    "name"          => "form_add_pengecekan_sisa_box_paket", 
	    "autocomplete"  => "off", 
	    "class"         => "form-horizontal",
	    "role"			=> "form"
    );
    
    $hidden = array(
        "command"   => "add"
    );

    echo form_open(base_url()."apotik/pengecekan_sisa_box_paket/save", $form_attr, $hidden);
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
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate('Pengecekan Sisa Box Paket', $this->session->userdata('language'))?></span>
		</div>
		<?php $msg = translate("Apakah anda yakin akan menyimpan data sisa box paket ini?",$this->session->userdata("language"));?>
		<div class="actions">	
			<a class="btn btn-circle btn-default" href="<?=base_url()?>apotik/pengecekan_sisa_box_paket/history"><i class="fa fa-undo"></i>  <?=translate("History", $this->session->userdata("language"))?></a>
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
                            <label class="col-md-12"><?=translate("Periode Tindakan", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
                            <div class="col-md-12">
                                <div id="reportrange" class="btn default">
                                    <i class="fa fa-calendar"></i>
                                    &nbsp; <span>
                                    </span>
                                    <b class="fa fa-angle-down"></b>
                                </div>
                                <input type="hidden" class="form-control" id="tgl_awal" name="tgl_awal" value="<?=date('01-m-Y')?>"></input>
                                <input type="hidden" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?=date('t-m-Y')?>"></input>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12"><?=translate("Jenis Tindakan", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
                            <div class="col-md-12">
                                <?php
                                    $jenis_tindakan_option = array(
                                        ''          => translate('Pilih', $this->session->userdata('language')).'..',
                                        '1'         => 'Tindakan HD',
                                        '2'         => 'Tindakan Transfusi'
                                    );

                                    echo form_dropdown('jenis_tindakan_id', $jenis_tindakan_option, '','id="jenis_tindakan_id" class="form-control" ');
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
							<label class="col-md-12"><?=translate("Keterangan", $this->session->userdata("language"))?> <span style="color:red;" class="required">*</span>:</label>
							<div class="col-md-12">
								<textarea id="keterangan" name="keterangan" class="form-control" rows="4" cols="5"></textarea>
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
   
                        $attrs_item_id_detail = array(
                            'id'          => 'item_id_detail_{0}',
                            'name'        => 'item[{0}][item_id_detail]',
                            'class'       => 'form-control hidden',
                        );

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

                        $attrs_item_bn = array(
                            'id'          => 'item_bn_{0}',
                            'name'        => 'item[{0}][bn]',
                            'class'       => 'form-control',
                            'type'        => 'hidden',
                            'readonly'    => 'readonly',
                        );

                        $attrs_item_ed = array(
                            'id'          => 'item_ed_{0}',
                            'name'        => 'item[{0}][ed]',
                            'class'       => 'form-control',
                            'type'        => 'hidden',
                            'readonly'    => 'readonly',
                        );



                        // item row column
                        $item_cols = array(// style="width:156px;
                            'item_code'   => '<div class="input-group">'.form_input($attrs_item_code).form_input($attrs_item_id).$btn_search.'</div>',
                            'item_name'   => form_input($attrs_item_name).form_input($attrs_item_id_detail),
                            'item_bn'     => form_input($attrs_item_bn).'<div class="text-left" id="div_item_bn_{0}" name="item[{0}][bn]"></div>',
                            'item_ed'     => form_input($attrs_item_ed).'<div class="text-left" id="div_item_ed_{0}" name="item[{0}][ed]"></div>',
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
                                <th class="text-center" width="15%"><?=translate("BN", $this->session->userdata("language"))?></th>
                                <th class="text-center" width="10%"><?=translate("ED", $this->session->userdata("language"))?></th>
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
                    <th><div class="text-center"><?=translate('BN', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center"><?=translate('ED', $this->session->userdata('language'))?></div></th>
                    <th><div class="text-center" widht="1%"><?=translate('Aksi', $this->session->userdata('language'))?></div></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>      
    </div>
</div> 
<div class="modal fade bs-modal-lg" id="popup_modal_jumlah_keluar" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>




