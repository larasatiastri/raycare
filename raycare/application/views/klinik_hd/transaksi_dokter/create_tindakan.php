 <?php
    $form_attr = array(
        "id"            => "form_add_cabang", 
        "name"          => "form_add_cabang", 
        "autocomplete"  => "off", 
        "class"         => "form-horizontal",
        "role"          => "form"
    );

    $com='';
    
    if($flagg==1)
    {
        $hidden = array(
        "command2"   => "add"
            );
        
    }else{
        $hidden = array(
        "command2"   => "edit"
            );
        
    }
    echo $com;
    
    echo form_open(base_url()."klinik_hd/transaksi_dokter/save", $form_attr, $hidden);

    $btn_search = '<div class="text-center"><button title="Search Item" class="btn btn-success search-item"><i class="fa fa-search"></i></button></div>';
    $btn_del    = '<div class="text-center"><button class="btn red-intense del-this" title="Delete Purchase Item"><i class="fa fa-times"></i></button></div>';

    // $item_cols = array(
    //     'item_code'   => form_input($attrs_tindakan_id).form_input($attrs_tindakan_code),
    //     'item_search' => $btn_search,
    //     'item_name'   => $attrs_tindakan_nama,
    //     'item_harga'  => $attrs_tindakan_harga,
    //     'action'      => $btn_del,
    // );

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

?>



<!-- BEGIN PROFILE SIDEBAR -->
                    <div class="profile-sidebar" style="width: 250px;">
                        <!-- PORTLET MAIN -->
                        <div class="portlet light profile-sidebar-portlet" style="padding-left:0px !important;padding-right:0px !important;">
                        <div class="patient-padding-picture"></div>
                            <div class="form-body">
                             <?php
                                    $url = array();
                                    if ($form_data3[0]['url_photo'] != '') 
                                    {
                                        $url = explode('/', $form_data3[0]['url_photo']);
                                        // die(dump($row['url_photo']));
                                        if (file_exists(FCPATH.config_item('site_img_pasien').$form_data3[0]['no_member'].'/foto/'.$form_data3[0]['url_photo']) && is_file(FCPATH.config_item('site_img_pasien').$form_data3[0]['no_member'].'/foto/'.$form_data3[0]['url_photo'])) 
                                        {
                                            $img_url = base_url().config_item('site_img_pasien').$form_data3[0]['no_member'].'/foto/'.$form_data3[0]['url_photo'];
                                        }
                                        else
                                        {
                                            $img_url = base_url().config_item('site_img_pasien').'global/global.png';
                                        }
                                    } else {

                                        $img_url = base_url().config_item('site_img_pasien').'global/global.png';
                                    }

                                ?>

                                <!-- SIDEBAR USERPIC -->
                                <input type="hidden" name="counter" id="counter" value="0" >
                                <input type="hidden" name="tanggal" id="tanggal" value="<?=date('M Y')?>" >
                                <input type="hidden" name="url" id="url" value="<?=$form_data['url_photo']?>" >
                                <input type="hidden" name="pegawai_id" id="pegawai_id" value="<?=$pk_value?>" >
                                <div id="upload" class="profile-userpic" style="text-align:center">

                                <!-- <a class="fancybox-button" title="<?=$form_data['url_photo']?>" href="<?=$img_src?>" data-rel="fancybox-button">
                                    <img src="<?=$img_src?>" alt="Smiley face" class="img-responsive img-thumbnail">
                                </a>
 -->                                <!-- <img src="<?=$img_src?>" class="img-responsive" alt="<?=$form_data['url_photo']?>"> -->
                                <ul class="ul-img">
                                    <li class="working">
                                        <div class="thumbnail" style="border:0px !important;">
                                            <a class="fancybox-button" title="<?=$img_url?>" href="<?=$img_url?>" data-rel="fancybox-button">
                                                <img src="<?=$img_url?>" alt="Smiley face" class="img-thumbnail img-responsive" style="padding:0px;border:0px;">
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                                
                            </div>
                            <!-- END SIDEBAR USERPIC -->
                            </div>
                            
                            <!-- SIDEBAR USER TITLE -->
                            <div class="profile-usertitle">
                                <div class="profile-usertitle-name">
                                    <?=$form_data3[0]['no_member']?>
                                </div>
                                <div class="profile-usertitle-job">
                                    <?=$form_data3[0]['nama']?>
                                </div>
                            </div>
                            <!-- END SIDEBAR USER TITLE -->
                            
                        </div>

                        <!-- END PORTLET MAIN -->
                        <!-- PORTLET MAIN -->
                        <div class="portlet light">

                            <div>

                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Alamat", $this->session->userdata("language"))?> :</label>
                                        
                                        <div class="col-md-12">
                                             <label class="control-label"><?=$form_data3[0]['alamat']?></label>
                                        </div>
                                    </div>
                                        <div class="form-group bold">
                                        <label class="col-md-12 bold"><?=translate("Gender", $this->session->userdata("language"))?> :</label>
                                        
                                        <div class="col-md-12">
                                             <label class="control-label"><?=$form_data3[0]['gender']?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Umur", $this->session->userdata("language"))?> :</label>
                                        
                                        <div class="col-md-12"> 
                                              <label class="control-label"><? if(floor($form_data3[0]['usia']/365)==0){?> <?=translate("Dibawah 1 Tahun", $this->session->userdata("language"))?> <?}else{?><?=floor($form_data3[0]['usia']/365)?> Tahun<?}?> </label> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Telepon", $this->session->userdata("language"))?> :</label>
                                        
                                        <div class="col-md-12">
                                              <label class="control-label"><?=$form_data4[0]['nomor']?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Tanggal Registrasi", $this->session->userdata("language"))?> :</label>
                                        
                                        <div class="col-md-12">
                                              <label class="control-label"><?=date('d M Y',strtotime($form_data3[0]['tanggal_registrasi']))?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Frequensi Perawatan", $this->session->userdata("language"))?> :</label>
                                        
                                        <div class="col-md-12">
                                             <label class="control-label"><?=$form_data3[0]['jangka_waktu'].translate(' kali dalam seminggu', $this->session->userdata('language'))?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Tanggal Daftar", $this->session->userdata("language"))?> :</label>
                                        
                                        <div class="col-md-12">
                                             <label class="control-label"><?=date('d M Y',strtotime($form_data[0]['created_date']))?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Dokter Penanggung Jawab", $this->session->userdata("language"))?> :</label>
                                        
                                        <div class="col-md-12">
                                             <label class="control-label"><?=$form_data[0]['nama']?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Tindakan Terakhir", $this->session->userdata("language"))?> :</label>
                                        
                                        <div class="col-md-12">
                                             <label class="control-label"><?=($form_data10 != '')?date('d M Y', strtotime($form_data10->tanggal)):'-' ?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Jumlah Tindakan", $this->session->userdata("language"))?> :</label>
                                        
                                        <div class="col-md-12">
                                             <i><label class="control-label"><?=$form_data2[0]['counts']?></label><b> kali dalam minggu ini</b><input type="hidden" id="freq" name="freq" value="<?=$form_data2[0]['counts']?>"></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Berat Badan Akhir Tindakan Sebelumnya", $this->session->userdata("language"))?> :</label>
                                        
                                        <div class="col-md-12">
                                             <label class="control-label"><?=($form_data10 != '')?$form_data10->berat_akhir.' Kg':'-' ?></label>
                                             <input type="hidden" id="berat_akhir_post" name="berat_akhir_post" class="form-control" value="<?=($form_data10 != '')?$form_data10->berat_akhir:0?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12 bold"><?=translate("Item Tersimpan", $this->session->userdata("language"))?> :</label>
                                        <input type="hidden" id="pk" name="pk" value="<?=$pk?>">
                                    </div>
                                    <table class="table table-striped table-bordered table-hover" id="table_cabang">
                                        <thead>
                                            <tr role="row">
                                                <th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?> </th>
                                                <th class="text-center"><?=translate("Nama Item", $this->session->userdata("language"))?> </th>
                                                <th class="text-center"><?=translate("Jumlah", $this->session->userdata("language"))?> </th> 
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                            </div>

                        </div>
                        <!-- END PORTLET MAIN -->
                    </div>
                    <!-- END BEGIN PROFILE SIDEBAR -->

<!-- BEGIN PROFILE CONTENT -->
                    <div class="profile-content">
                        <div class="row">
                            <div class="col-md-12">


            <div class="portlet light" style="margin-bottom:0px !important;">
                <div class="portlet-title tabbable-line">

                    <?php $msg = translate("Apakah anda yakin akan membuat tindakan ini?",$this->session->userdata("language"));?>
                    <div class="caption">
                        <span class="caption-subject font-blue-sharp bold uppercase"><?=translate("Formulir Konsultasi HD Shift", $this->session->userdata("language")).' '.$shift?></span>
                        <span class="caption-helper"><?php echo '<label class="control-label ">'.date('d M Y').'</label>'; ?> </span>
                    </div>
                    <ul class="nav nav-tabs">
                        <li  class="active">
                            <a href="#tindakan" data-toggle="tab">
                                <?=translate('Tindakan HD', $this->session->userdata('language'))?> </a>
                        </li>
                        <li>
                            <a href="#tindakan_lain" data-toggle="tab">
                                <?=translate('Tindakan Lain', $this->session->userdata('language'))?> </a>
                        </li>
                        <li>
                            <a href="#rekammedis" data-toggle="tab">
                                <?=translate('Rekam Medis', $this->session->userdata('language'))?> </a>
                        </li>
                        <li >
                            <a href="#rujukan" data-toggle="tab">
                                <?=translate('Rujukan', $this->session->userdata('language'))?> </a>
                        </li>
                        <li>
                            <a href="#denah" data-toggle="tab">
                                <?=translate('Denah', $this->session->userdata('language'))?> </a>
                        </li>
                        <li class="hidden">
                            <a href="#klaim" data-toggle="tab" id="kl">
                                <?=translate('Klaim', $this->session->userdata('language'))?> </a>
                        </li>
                        <li>
                            <a href="#buatresep" data-toggle="tab">
                                <?=translate('Buat Resep', $this->session->userdata('language'))?> </a>
                        </li>
                        
                        <li id="list_hasil_lab">
                            <a href="#hasil_lab" data-toggle="tab">
                                <?=translate('Hasil Lab', $this->session->userdata('language'))?> </a>
                        </li>
                        <li>
                            <a href="#datapasien" data-toggle="tab">
                                <?=translate('Data Pasien', $this->session->userdata('language'))?> </a>
                        </li>

                    </ul>
                </div>
                
                <div class="portlet-body form">
                    <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <?=$form_alert_danger?>
                </div>
                <div class="alert alert-success display-hide">
                    <button class="close" data-close="alert"></button>
                    <?=$form_alert_success?>
                </div>
                    <input type="hidden" id="pasienid" name="pasienid" value="<?=$pasien_id?>">
                    <input type="hidden" id="pk" name="pk" value="<?=$pk?>">
                    <input type="hidden" id="username1" name="username1" value="<?=$this->session->userdata("username")?>">
                    <input type="hidden" id="userid1" name="userid1" value="<?=$this->session->userdata("user_id")?>">
                    <input type="hidden" id="observasiid" name="observasiid" >
                    <input type="hidden" id="shift" name="shift" value="<?=$shift?>">

<!--                <div class="portlet-body form">
                    <div class="form-body"> -->
                        
                    <div class="tab-content">
                            <?if($flagg==1){?>
                                 <div class="tab-pane active" id="tindakan" >
                                        <?php include('tab_transaksi_dokter/tindakan.php') ?>
                                 </div>
                             <?}else{?>
                                <div class="tab-pane active" id="tindakan" >
                                    <?php include('tab_transaksi_dokter/tindakan2.php') ?>
                                </div>
                             <?}?>
                            <div class="tab-pane" id="tindakan_lain">
                                <?php include('tab_transaksi_dokter/tindakan_lain.php') ?>
                            </div>
                            <div class="tab-pane" id="rekammedis">
                                <?php include('tab_transaksi_dokter/rekam_medis.php') ?>
                            </div>
                            <div class="tab-pane" id="rujukan">
                                <?php include('tab_transaksi_dokter/rujukan.php') ?>
                            </div>
                            <div class="tab-pane" id="denah">
                                <?php include('tab_transaksi_dokter/denah.php') ?>
                            </div>
                            <div class="tab-pane hidden" id="klaim">
                                <?php include('tab_transaksi_dokter/klaim.php') ?>
                            </div>
                            <div class="tab-pane" id="buatresep">
                                <?php include('tab_transaksi_dokter/resep2.php') ?>
                            </div>
                            
                            <div class="tab-pane" id="hasil_lab">
                                <?php include('tab_transaksi_dokter/hasil_lab.php') ?>
                            </div>
                            <div class="tab-pane" id="datapasien">
                                <?php include('tab_transaksi_dokter/data.php') ?>
                            </div>
                    </div>
                    
                    <div class="form-actions right">
                        <a class="btn btn-circle btn-default" id="refresh"><i class="fa fa-undo"></i>
                            <?=translate("Refresh", $this->session->userdata("language"))?>
                        </a>
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
            

    
            </div>


</div>

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
                <h4 class="modal-title">Konfirmasi Cetak Persetujuan</h4>
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

<div id="popover_item_content_transfusi" style="display:none">
    <div class="portlet-body form">
        <div class="form-body">
            <div class="portlet-body form">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light" id="section-alamat">
                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover" id="table_obat_transfusi">
                    <thead>
                    <tr role="row" class="heading">
                            <th class="text-center"><?=translate("Kode", $this->session->userdata("language"))?> </th>
                            <th class="text-center"><?=translate("Nama", $this->session->userdata("language"))?> </th>
                            <th class="text-center"><?=translate("Keterangan", $this->session->userdata("language"))?> </th>
                             
                            <th class="text-center"><?=translate("Aksi", $this->session->userdata("language"))?> </th>
                         
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                     
                </div>
            </div>
    </div>
</div>
</div>







        </div>
    </div>   
</div>  
        
        
  

