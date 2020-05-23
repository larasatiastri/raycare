<?php
	$file = config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$sppd_tiga['value'];
    $result = @get_headers($file);

    if($result[0] == 'HTTP/1.1 200 OK' && isset($result[6]))
    {

?>
<div class="image_a4">
	<img id="foto_puskesmas" src="<?=config_item('base_dir')?>cloud/core/pages/master/pasien/images/<?=$data_pasien['no_member']?>/dokumen/pelengkap/<?=$sppd_tiga['value']?>">	
</div>
<?php
    }else{
    	echo 'Surat Keterangan HD 3x Belum Diupload';
    }
?>