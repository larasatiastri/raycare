 <?php
    $form_attr = array(
        "id"            => "form_add_cabang", 
        "name"          => "form_add_cabang", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        "role"          => "form"
    );

    
    $hidden = array(
        "command2"   => "add"
    );
   
    
    echo form_open("#", $form_attr, $hidden);

    $btn_search = '<div class="text-center"><button title="Search Item" class="btn btn-success search-item"><i class="fa fa-search"></i></button></div>';
    $btn_del    = '<div class="text-center"><button class="btn red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';


    $item_cols = array(
        'item_code'   => '<input type="hidden" id="tindakan_id_{0}" name="tindakan[{0}][tindakan_id]"><input type="text" id="tindakan_code_{0}" name="tindakan[{0}][code]" class="form-control" readonly>',
        'item_search' => $btn_search,
        'item_name'   => '<input type="text" id="tindakan_nama_{0}" name="tindakan[{0}][nama]" class="form-control" readonly>',
        'item_harga'  => '<input type="text" id="tindakan_harga_{0}" name="tindakan[{0}][harga]" class="form-control" readonly>',
        'action'      => $btn_del,
    );

    // gabungkan $item_cols jadi string table row
    $item_row_template =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols) . '</td></tr>';
    
    $form_alert_danger  = translate('Terdapat beberapa kesalahan. Silahkan cek kembali.', $this->session->userdata('language'));
    $form_alert_success = translate('Data yang diinputkan akan tersimpan.', $this->session->userdata('language'));

    $shift_aktif = 1;
        if(date('H:i:s') > '06:30:00' &&  date('H:i:s') <= '11:30:00'){
            $shift_aktif = 1;
        }if(date('H:i:s') > '11:30:01' &&  date('H:i:s') <= '18:30:00'){
            $shift_aktif = 2;
        }if(date('H:i:s') > '18:30:01' &&  date('H:i:s') <= '23:30:00'){
            $shift_aktif = 3;
        }


    $btn_search = '<div class="text-center"><button title="Search Item" name="77resep[{0}][add77]" class="btn btn-primary search-item"><i class="fa fa-search"></i></button></div>';
    $btn_del_resep    = '<div class="text-center"><button class="btn red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

    $options = array(
        '' => translate('Pilih', $this->session->userdata('language')).'...',
        '1' => translate('PC', $this->session->userdata('language')),
        '2' => translate('AC', $this->session->userdata('language')),
    );
    $item_cols_resep = array(
        'item_code'     => '<input type="hidden" id="tindakan_tipe_obat_{0}" name="resep[{0}][tipe_obat]"><input type="hidden" id="tindakan_itemrow_{0}" name="resep[{0}][itemrow]"><input type="hidden" id="tindakan_id_{0}" name="resep[{0}][tindakan_id]"><input type="hidden"                           id="tindakan_id2_{0}" name="resep[{0}][tindakan_id2]"><label id="tindakan_code_{0}" name="resep[{0}][code]" />', 
        'item_name'     => '<label id="tindakan_nama_{0}" name="resep[{0}][nama]" /><input type="hidden"                                id="tindakan_keterangan_{0}" name="resep[{0}][keteranganmodal]" class="form-control" readonly>', 
        'item_jumlah'   => '<div class="input-group" style="width:120px;"><input type="text" id="tindakan_jumlah_{0}" name="resep[{0}][jumlah]" class="form-control" value="1" ><span class="input-group-addon satuan"></span></div><input type="hidden" id="tindakan_satuan_{0}" name="resep[{0}][satuan]" class="form-control" readonly>',
        'item_dosis'    => '<input type="text" id="tindakan_item_dosis_{0}" name="resep[{0}][item_dosis]" class="form-control" style="width:100px;">',
        'item_aturan'   => form_dropdown('resep[{0}][item_aturan]', $options, '','id="tindakan_item_aturan_{0}" class="form-control" style="width:100px;"'),
        'item_bw_plg'   => '<div class="text-center"><input type="checkbox" id="tindakan_item_bawa_{0}" name="resep[{0}][item_bawa]" class="form-control" value="1"></div>',
        'action'        => $btn_del_resep,
    );

    // gabungkan $item_cols jadi string table row
    $item_row_template_resep  =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols_resep) . '</td></tr>';
    $btn_del2           = '<div class="text-center"><button class="btn red-intense del-this2" title="Delete Resep"><i class="fa fa-times"></i></button></div>';
    
    $item_cols2         = array(
        'item_keterangan'   => '<textarea cols="20" id="tindakan_keterangan_{0}" name="resepmanual[{0}][keterangan]" class="form-control"></textarea>',
        'action'            => $btn_del2,
    );

    // gabungkan $item_cols jadi string table row
    $item_row_template2     =  '<tr id="item_row_{0}" class="table_item"><td>' . implode('</td><td>', $item_cols2) . '</td></tr>';

// =============temp
    $btn_search776  = '<div class="text-center"><button title="Search Item" name="1resep[{0}][add1]" class="btn btn-primary search-item1"><i class="fa fa-search"></i></button></div>';
    $btn_del776     = '<div class="text-center"><button class="btn red-intense del-this1" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';


    $item_cols776 = array(
        'item_code'     => '<input type="hidden" id="tindakan_itemrow2_{0}" name="1resep[{0}][itemrow2]"><input type="hidden" id="1tindakan_id_{0}" name="1resep[{0}][tindakan_id1]"><input type="hidden" id="1tindakan_id2_{0}" name="1resep[{0}][tindakan_id2]"><input type="text" id="1tindakan_code_{0}" name="1resep[{0}][code1]" class="form-control" readonly>',
        'item_search'   => $btn_search776,
        'item_name'     => '<input type="text" id="1tindakan_nama_{0}" name="1resep[{0}][nama1]" class="form-control" readonly>',
        
        'item_jumlah'   => '<input type="text" id="1tindakan_jumlah_{0}" name="1resep[{0}][jumlah1]" value="1" class="form-control" style="width:300px;">',
        'item_satuan'   => '<div name="1resep[{0}][div1]" align="center"><input type="text" id="1tindakan_satuan_{0}" name="1resep[{0}][satuan1]" class="form-control"></div>',
         
        'action'        => $btn_del776,
    );

    // gabungkan $item_cols jadi string table row
    $item_row_template776 =  '<tr id="item_row556_{0}" class="table_item26"><td>' . implode('</td><td>', $item_cols776) . '</td></tr>';
    

    $item_cols211 = array(
        'item_keterangan'   => '<input type="hidden" id="tindakan_itemrow3_{0}" name="resepmanual3[{0}][itemrow3]"><input type="text" sid="tindakan_keterangan11_{0}" name="resepmanual3[{0}][keterangan11]" class="form-control">', 
    );

    // gabungkan $item_cols jadi string table row
    $item_row_template211 =  '<tr id="item_row556_{0}" class="table_item26"><td>' . implode('</td><td>', $item_cols211) . '</td></tr>';

    $btn_add='<a id="tambahrow"  class="btn btn-circle btn-primary" name="tambahrow">
            <i class="fa fa-plus"></i>
            <span class="hidden-480">
                 '.translate("Obat", $this->session->userdata("language")).'
            </span>
            </a>';

?>
<div class="portlet light">
	<div class="portlet-title">
		<?php $msg = translate("Apakah anda yakin akan membuat tindakan ini?",$this->session->userdata("language"));?>
		<div class="caption">
			<span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Formulir Tindakan Umum Shift", $this->session->userdata("language")).' '.$shift?></span>
            <span class="caption-helper"><?php echo '<label class="control-label ">'.date('d M Y').'</label>'; ?></span>
		</div>
	</div>
	<div class="portlet light" style="margin-bottom:0px !important;">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <?=translate("Informasi Shift", $this->session->userdata("language")).' '.$shift_aktif?> 
            </div>
            <ul class="nav nav-tabs">
                <li  class="active">
                    <a href="#tindakan" data-toggle="tab">
                        <?=translate('Tindakan', $this->session->userdata('language'))?> </a>
                </li>
                <li>
                    <a href="#rekammedis" data-toggle="tab">
                        <?=translate('Rekam Medis', $this->session->userdata('language'))?> </a>
                </li>
               
                <li>
                    <a href="#datapasien" data-toggle="tab">
                        <?=translate('Data Pasien', $this->session->userdata('language'))?> </a>
                </li>

            </ul>
        </div>
    </div>

	<div class="alert alert-danger display-hide">
        <button class="close" data-close="alert"></button>
        <?=$form_alert_danger?>
    </div>
    <div class="alert alert-success display-hide">
        <button class="close" data-close="alert"></button>
        <?=$form_alert_success?>
    </div>
    <div class="portlet-body">
        

        <input type="hidden" id="pasienid" name="pasienid" value="<?=$pasien_id?>">
        <input type="hidden" id="pk" name="pk" value="<?=$pk?>">
        <input type="hidden" id="username1" name="username1" value="<?=$this->session->userdata("username")?>">
        <input type="hidden" id="userid1" name="userid1" value="<?=$this->session->userdata("user_id")?>">
        <input type="hidden" id="observasiid" name="observasiid" >
        <input type="hidden" id="shift" name="shift" value="<?=$shift?>">
    	<div class="tab-content">
    	    <div class="tab-pane active" id="tindakan" >
    		    <?php include('tab_transaksi_dokter/tindakan2.php') ?>
    	    </div>
    		<div class="tab-pane" id="rekammedis">
    			<?php include('tab_transaksi_dokter/rekam_medis.php') ?>
    		</div>
    		<div class="tab-pane" id="datapasien">
    			<?php include('tab_transaksi_dokter/data.php') ?>
    		</div>

        
    	</div>
        <div class="form-actions right">                
            <a class="btn btn-circle btn-default" href="javascript:history.go(-1)"><i class="fa fa-chevron-left"></i>
                <?=translate("Kembali", $this->session->userdata("language"))?>
            </a>
            <a id="confirm_save_tindakan" class="btn btn-circle btn-primary" href="#" data-confirm="<?=$msg?>" data-toggle="modal">
                <i class="fa fa-check"></i> <?=translate("Simpan", $this->session->userdata("language"))?>
            </a>
            <a id="print_persetujuan" data-target="#basic" data-toggle="modal" class="btn default hidden" ><?=translate("Print", $this->session->userdata("language"))?></a>
            <button type="submit" id="savetindakan" class="btn default hidden" ><?=translate("Simpan", $this->session->userdata("language"))?></button>
        </div>
    </div>
    
</div>
<?=form_close()?>

<div class="modal fade" id="modal_add_dokumen" role="basic" aria-hidden="true" >
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
            

             
        </div>
    </div>
</div>
<div class="modal fade" id="ajax_notes3" role="basic" aria-hidden="true" >
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>
 

<div class="modal fade" id="ajax_notes2" role="basic" aria-hidden="true" >
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
            
        </div>                 
    </div>
</div>



<div class="modal fade" id="ajax_notes4" role="basic" aria-hidden="true" >
    <div class="page-loading page-loading-boxed">
        <span>
            &nbsp;&nbsp;Loading...
        </span>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
           
        </div>
    </div>
</div>

<div  class="modal fade bs-modal-lg"  id="ajax_resep" role="basic" aria-hidden="true">
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
<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Konfirmasi Cetak Rekam Medis</h4>
            </div>
            <div class="modal-body" id="modal-message">
                 
            </div>
            <div class="modal-footer">
                <a class="btn default" id="btn-batal">Lewati</button>
                <a class="btn btn-primary" target="_blank" id="btn-message">Cetak</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
        
        
  

