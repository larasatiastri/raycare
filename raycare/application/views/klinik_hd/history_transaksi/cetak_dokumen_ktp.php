<style type="text/css">
.body{width: 100%;height: 100%;}
.center{display: table-cell;vertical-align: middle;}
.image-kartu{float:left;border:1px solid #cccccc;width:330px;padding:4px;}
/*.image-kartu-keluarga{float:left;background-color:yellow;border:1px solid #cccccc;width:734px;padding:4px;}*/
.image-kartu-keluarga-portlet{
	border: 1px solid #cccccc;
	margin:auto;
    width: 500px;
    margin-top: 50px;
    padding: 4px;
}
.image-kartu-keluarga-landscape{
	border: 1px solid #cccccc;
	margin:auto;
    width: 734px;
    margin-top: 50px;
    padding: 4px;
}
</style>
<div class="body">
<div class="center">
<div class="image-kartu">
<img id="foto-ktp" src="<?=config_item('base_dir')?>cloud/core/pages/master/pasien/images/<?=$data_pasien['no_member']?>/dokumen/pelengkap/<?=$ktp_pasien['value']?>">
</div>

<div class="image-kartu" style="margin-left:64px;">
<img id="foto-bpjs" src="<?=config_item('base_dir')?>cloud/core/pages/master/pasien/images/<?=$data_pasien['no_member']?>/dokumen/pelengkap/<?=$kartu_penjamin_pasien['value']?>">
</div>
<!--<div style="float:left;width:744px; height:400px; background-color:green ;background-size: contain;-webkit-transform: rotate(180deg); transform: rotate(180deg); -sand-transform: rotate(180deg); background-image: url(http://thinkingstiff.com/images/matt.jpg); background-repeat: no-repeat; ">
	<div class="image-kartu-keluarga">
	<img id="foto_kk" src="<?=config_item('base_dir')?>cloud/core/pages/master/pasien/images/<?=$data_pasien['no_member']?>/dokumen/pelengkap/<?=$kartu_keluarga_pasien['value']?>">	

</div>
</div>-->
<?php
	list($width, $height) = getimagesize(config_item('base_dir').'cloud/core/pages/master/pasien/images/'.$data_pasien['no_member'].'/dokumen/pelengkap/'.$kartu_keluarga_pasien['value']);

	if($height > $width)
	{
		$class = 'image-kartu-keluarga-portlet';
	}
	else
	{


		$class = 'image-kartu-keluarga-landscape';
	}
?>

<div class="<?=$class?>">

	<img id="foto_kk" src="<?=config_item('base_dir')?>cloud/core/pages/master/pasien/images/<?=$data_pasien['no_member']?>/dokumen/pelengkap/<?=$kartu_keluarga_pasien['value']?>">

</div>
</div>
</div>