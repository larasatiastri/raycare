<?php
	$file = config_item('url_core').'assets/mb/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$rujukan_pasien_luar['value'];
    $result = @get_headers($file);

    if($result[0] == 'HTTP/1.1 200 OK' && isset($result[6]))
    {

?>
<div class="image_a4">
	<img id="foto_puskesmas" src="<?=config_item('url_core')?>assets/mb/pages/master/pasien/images/<?=$data_pasien['no_member']?>/dokumen/pelengkap/<?=$rujukan_pasien_luar['value']?>">	
</div>
<?php
    }else{
    	echo 'Surat Pengantar Luar Provinsi Belum Diupload';
    }
?>