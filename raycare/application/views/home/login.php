<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.2.0
Version: 3.2.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<?php $this->load->view('_partials/head');?>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="login">
<!-- BEGIN LOGO -->
<div class="logo">
	<a href="index.html">
	<img src="<?=config_item('base_dir')?>cloud/<?=config_item('site_dir')?>logo/logo-big.png" alt="Klinik Raycare"/>
	</a></br></br>
	<span class="label label-lg label-danger bold" style="background-color:#2462AC; color:#ed3237;font-size:20px;padding-right:22px;padding-left:22px;"> K A L I D E R E S </span>
</div>
<!-- END LOGO -->
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGIN -->
<div class="content">
	<?php
	$form_attr = array(
		"id"			=> "form_login", 
		"name"			=> "form_login", 
		"autocomplete"	=> "off",
		"class"			=> "login-form",
	);
	echo form_open(base_url()."home/login", $form_attr);
	?>	
		<h3 class="form-title">Login</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>
				 Masukan Username dan Password Anda
			</span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				 <?php
					$project_status = array(
					"name"        => "username", 
					"id"          => "username", 
					"class"       => "form-control placeholder-no-fix",
					"autofocus"   => "autofocus", 
					"value"       => $this->session->flashdata("username"), 
					"placeholder" =>"Username"
				);
				
				echo form_input($project_status);
				?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<?php
					$password = array(
						"name"			=> "password",
						"id"			=> "password", 
						"placeholder"	=> "Password", 
						"class"         => "form-control placeholder-no-fix",
						"autocomplete"  =>  "off"

					);
					echo form_password($password);
				?>
			</div>
		</div>
		<?php 
		if(isset($url_encoded))
		{
		?>
		<div class="form-group hidden">
			<label class="control-label visible-ie8 visible-ie9">URL Encoded</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<?php
					$url_encod = array(
						"name"			=> "url_encod",
						"id"			=> "url_encod", 
						"placeholder"	=> "url_encod",
						"value"         => $url_encoded, 
						"class"         => "form-control placeholder-no-fix",
						"autocomplete"  =>  "off"

					);
					echo form_input($url_encod);
				?>
			</div>
		</div>
		<?php
	    }
 
		?>
		<div class="error_login" style="color:red;"><?=$this->session->flashdata("error")?></div>
		<div class="form-actions">
			
			<button type="submit" class="btn btn-primary pull-right">
				<i class="glyphicon glyphicon-log-in"></i>
				<span class="hidden-480">
                     <?=translate("Login", $this->session->userdata("language"))?>
                </span>
			</button>
			&nbsp;
			<button type="reset" class="btn btn-danger pull-right" style="margin-right:3px">
				<i class="fa fa-eraser"></i><span class="hidden-480">
	                             <?=translate("Reset", $this->session->userdata("language"))?>
	                        </span>
			</button>
		</div>
	<?=form_close()?>
</div>
<!-- END LOGIN -->
<!-- BEGIN LINK APPS -->
<!-- <div class="content apps-link">
	<a href="<?=config_item('url_link_ravena')?>">
		<img src="<?=base_url()?>assets/mb/global/image/logo/logo-big1.png" alt=""/>
		<h6>Ravena Login</h6>
	</a>
</div> -->
<!-- END LINK APPS -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
	 2018 &copy; Raycare Health Solution.
</div>
<!-- END COPYRIGHT -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="<?=base_url()?>assets/metronic/global/plugins/respond.min.js"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=base_url()?>assets/metronic/global/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?=base_url()?>assets/metronic/global/scripts/metronic.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=base_url()?>assets/mb/global/js/mb.js"></script>

<script>

    $(function() {    

        // set base url

        mb.baseUrl('<?=base_url()?>');

        // display flash message if any

        mb.showMessage('<?=$this->session->flashdata("type")?>', '<?=$this->session->flashdata("msg")?>', '<?=$this->session->flashdata("msgTitle")?>');

    });

</script>



<!-- BEGIN PAGE LEVEL SCRIPS -->

<?php if(isset($js_files) && count($js_files)): ?>

<?php foreach ($js_files as $js): ?>

<script type="text/javascript" src="<?=base_url().$js?>"></script>

<?php endforeach; ?>

<?php endif;?>

<!-- END PAGE LEVEL SCRIPS -->

<!-- END JAVASCRIPTS -->

</body>
<!-- END BODY -->
</html>