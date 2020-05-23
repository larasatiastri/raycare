<html>
<head>
	
</head>
<body>
	<div id="header" style="padding-top:30px;background-color:#FFF;border-bottom:1px solid #2462AC;">
		<?php 
			if (file_exists($_SERVER['DOCUMENT_ROOT']."cloud/".config_item('site_dir')."logo/logo-real-pt.png") && is_file($_SERVER['DOCUMENT_ROOT']."cloud/".config_item('site_dir')."logo/logo-real-pt.png")) 
	        {
	            $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."/logo/logo-real-pt.png";
	        }
	        else 
	        {
	            $image_header = config_item('base_dir')."cloud/".config_item('site_dir')."/logo/logo-real-pt.png";
	        }
		?>
		<div class="header-logo" style="float:left;width:200px;background-color:#fff;padding-bottom:8px;">
			<img src="<?=$image_header?>">
		</div>
		<div class="header-info" style="float:right;text-align:right;">
			<div style="font-size:11px !important;color: #2462AC;margin-bottom:6px;">Page {PAGENO} of {nb}</div>
	
			<div style="font-size:12px;">
				<?=$form_data['no_retur']?>
			</div>
		</div>
	</div>
</body>
</html>
