<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<?php $this->load->view('_partials/head');?>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-menu-fixed page-footer-fixed">
<!-- BEGIN HEADER -->
<div class="page-header">
	<?php $this->load->view('_partials/nav_head');?>
	<?php $this->load->view('_partials/mega_menu');?>
</div>
<!-- END HEADER -->
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
<!--
<marquee direction="left" scrollamount="4" height="30px" width="100%" style="font-size:20px;margin-button:-4px;background-color:red;color:white;"><b>Pengumuman :</b> Demi kelancaran penggunaan program SIMRHS, diharapkan agar selalu membuat laporan kerja setiap hari. Mulai hari Senin, 26 Desember 2016, user tidak dapat membuka menu lain di program SIMRHS selain menu "Pembuatan Laporan Kerja" jika minimal satu hari hingga tiga hari sebelumnya tidak membuat laporan kerja. Seluruh pegawai yang diwajibkan membuat laporan kecuali dokter, perawat, receptionist, kasir, staff laundry, kurir, serta staff admin.</marquee>
-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->load->view('_partials/page_header');?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT -->

	<div class="page-content">
		<div class="container-fluid">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<?php $this->load->view('_partials/modal_dialog');?>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE BREADCRUMB -->
			<?php if (isset($breadcrumb) && $breadcrumb==true):?>

            	<?=urldecode(set_breadcrumb('&nbsp;<i class="fa fa-circle"></i>&nbsp;', '', array(), $this->session->userdata('language')))?>

        	<?php endif;?>
			<!-- END PAGE BREADCRUMB -->	
			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
				<?php if (isset($content_view)):?> 
					<?php if($content_view!=='') $this->load->view($content_view);?> 
				<?php else:?>
					Page content goes here
				<?php endif;?>
				<div class="modal fade" id="modal_session" role="basic" aria-hidden="true">
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
				</div>
			</div>
			<!-- END PAGE CONTENT-->		
		</div>
		<style>
				.page-quick-sidebar-wrapper .page-quick-sidebar .nav-justified > li > a {

	    padding: 45px 15px 8px !important;

	}
		</style>
		<!-- BEGIN QUICK SIDEBAR -->
		<a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-login"></i></a>
		<div class="page-quick-sidebar-wrapper">
			<div class="page-quick-sidebar">
				<div class="nav-justified">
					<ul class="nav nav-tabs nav-justified">
						<li class="active">
							<a href="#quick_sidebar_tab_1" data-toggle="tab">
							Chatting <span class="badge badge-danger"></span>
							</a>
						</li>
						<li>
							<a href="#quick_sidebar_tab_2" data-toggle="tab">
							Alur Sistem <span class="badge badge-success"></span>
							</a>
						</li>
						
					</ul>
					<div class="tab-content">
						<div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
							<div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
								<h3 class="list-heading">Office</h3>
								<ul class="media-list list-items">
									<li class="media">
										<div class="media-status">
											<span class="badge badge-success">8</span>
										</div>
										<img class="media-object" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png" alt="...">
										<div class="media-body">
											<h4 class="media-heading">Efan Efendi</h4>
											<div class="media-heading-sub">
												 Staf Keuangan
											</div>
										</div>
									</li>
									<!-- <li class="media">
										<img class="media-object" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png" alt="...">
										<div class="media-body">
											<h4 class="media-heading">Nick Larson</h4>
											<div class="media-heading-sub">
												 Art Director
											</div>
										</div>
									</li>
									<li class="media">
										<div class="media-status">
											<span class="badge badge-danger">3</span>
										</div>
										<img class="media-object" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png" alt="...">
										<div class="media-body">
											<h4 class="media-heading">Deon Hubert</h4>
											<div class="media-heading-sub">
												 CTO
											</div>
										</div>
									</li>
									<li class="media">
										<img class="media-object" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png" alt="...">
										<div class="media-body">
											<h4 class="media-heading">Ella Wong</h4>
											<div class="media-heading-sub">
												 CEO
											</div>
										</div>
									</li> -->
								</ul>
								<h3 class="list-heading">Clinic</h3>
								<ul class="media-list list-items">
									<li class="media">
										<div class="media-status">
											<span class="badge badge-warning">2</span>
										</div>
										<img class="media-object" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png" alt="...">
										<div class="media-body">
											<h4 class="media-heading">Ari Hartanto</h4>
											<div class="media-heading-sub">
												 Kepala Klinik
											</div>
											<div class="media-heading-small">
												 Last seen 03:10 AM
											</div>
										</div>
									</li>
									<!-- <li class="media">
										<div class="media-status">
											<span class="label label-sm label-success">new</span>
										</div>
										<img class="media-object" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png" alt="...">
										<div class="media-body">
											<h4 class="media-heading">Ernie Kyllonen</h4>
											<div class="media-heading-sub">
												 Project Manager,<br>
												 SmartBizz PTL
											</div>
										</div>
									</li>
									<li class="media">
										<img class="media-object" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png" alt="...">
										<div class="media-body">
											<h4 class="media-heading">Lisa Stone</h4>
											<div class="media-heading-sub">
												 CTO, Keort Inc
											</div>
											<div class="media-heading-small">
												 Last seen 13:10 PM
											</div>
										</div>
									</li>
									<li class="media">
										<div class="media-status">
											<span class="badge badge-success">7</span>
										</div>
										<img class="media-object" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png" alt="...">
										<div class="media-body">
											<h4 class="media-heading">Deon Portalatin</h4>
											<div class="media-heading-sub">
												 CFO, H&D LTD
											</div>
										</div>
									</li>
									<li class="media">
										<img class="media-object" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png" alt="...">
										<div class="media-body">
											<h4 class="media-heading">Irina Savikova</h4>
											<div class="media-heading-sub">
												 CEO, Tizda Motors Inc
											</div>
										</div>
									</li>
									<li class="media">
										<div class="media-status">
											<span class="badge badge-danger">4</span>
										</div>
										<img class="media-object" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png" alt="...">
										<div class="media-body">
											<h4 class="media-heading">Maria Gomez</h4>
											<div class="media-heading-sub">
												 Manager, Infomatic Inc
											</div>
											<div class="media-heading-small">
												 Last seen 03:10 AM
											</div>
										</div>
									</li> -->
								</ul>
							</div>
							<div class="page-quick-sidebar-item">
								<div class="page-quick-sidebar-chat-user">
									<div class="page-quick-sidebar-nav">
										<a href="javascript:;" class="page-quick-sidebar-back-to-list"><i class="icon-arrow-left"></i>Back</a>
									</div>
									<div class="page-quick-sidebar-chat-user-messages">
										<div class="post out">
											<img class="avatar" alt="" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png"/>
											<div class="message">
												<span class="arrow"></span>
												<a href="javascript:;" class="name">Efan Efendi</a>
												<span class="datetime">20:15</span>
												<span class="body">
												When could you send me the report ? </span>
											</div>
										</div>

										<div class="post out">
											<img class="avatar" alt="" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png"/>
											<div class="message">
												<span class="arrow"></span>
												<a href="javascript:;" class="name">Efan Efendi</a>
												<span class="datetime">20:00</span>
												<span class="body">
												No probs. Just take your time :) </span>
											</div>
										</div>

										<div class="post out">
											<img class="avatar" alt="" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png"/>
											<div class="message">
												<span class="arrow"></span>
												<a href="javascript:;" class="name">Efan Efendi</a>
												<span class="datetime">20:01</span>
												<span class="body">
												Sure. I will check and buzz you if anything needs to be corrected. </span>
											</div>
										</div>
									</div>
									<div class="page-quick-sidebar-chat-user-form">
										<div class="input-group">
											<input type="text" class="form-control" placeholder="Type a message here..." style="height: 34px; padding: 6px 12px;">
											<div class="input-group-btn">
												<button type="button" class="btn blue" style="padding: 7px 14px !important; font-size: 14px !important;"><i class="icon-paper-clip"></i></button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane page-quick-sidebar-alerts" id="quick_sidebar_tab_2">
							<div class="page-quick-sidebar-alerts-list">
								<h3 class="list-heading">Klinik</h3>
								<ul class="feeds list-items">

									<li>
										<div class="col1">
											<div class="cont">
												<div class="cont-col1">
													<div class="label label-sm label-success">
														<i class="fa fa-user"></i>
													</div>
												</div>
												<div class="cont-col2">
													<div class="desc">
														 Alur Registrasi Pasien Baru
													</div>
												</div>
											</div>
										</div>
										<div class="col2">
											<div class="date">
												 3 Step
											</div>
										</div>
									</li>
									<li>
										<div class="col1">
											<div class="cont">
												<div class="cont-col1">
													<div class="label label-sm label-danger">
														<i class="fa fa-bell-o"></i>
													</div>
												</div>
												<div class="cont-col2">
													<div class="desc">
														 Alur Tindakan HD Rutin
													</div>
												</div>
											</div>
										</div>
										<div class="col2">
											<div class="date">
												 5 Step
											</div>
										</div>
									</li>
								</ul>
								<h3 class="list-heading">Keuangan</h3>
								<ul class="feeds list-items">
									<li>
										<a href="http://rhskld.com/alur/alur_klinik_hd/tindakan_pasien_hd_rutin.html" target="_blank">
										<div class="col1">
											<div class="cont">
												<div class="cont-col1">
													<div class="label label-sm label-info">
														<i class="fa fa-briefcase"></i>
													</div>
												</div>
												<div class="cont-col2">
													<div class="desc">
														 Alur Pengajuan Dana Reimburse
													</div>
												</div>
											</div>
										</div>
										<div class="col2">
											<div class="date">
												 4 Step
											</div>
										</div>
										</a>
									</li>
								</ul>
								<h3 class="list-heading">HRD</h3>
								<ul class="feeds list-items">
									<li>
										<a href="javascript:;">
										<div class="col1">
											<div class="cont">
												<div class="cont-col1">
													<div class="label label-sm label-info">
														<i class="fa fa-briefcase"></i>
													</div>
												</div>
												<div class="cont-col2">
													<div class="desc">
														 Alur Pengajuan Izin
													</div>
												</div>
											</div>
										</div>
										<div class="col2">
											<div class="date">
												 4 Step
											</div>
										</div>
										</a>
									</li>
									<li>
										<a href="javascript:;">
										<div class="col1">
											<div class="cont">
												<div class="cont-col1">
													<div class="label label-sm label-info">
														<i class="fa fa-briefcase"></i>
													</div>
												</div>
												<div class="cont-col2">
													<div class="desc">
														 Alur Pengajuan Izin Ganti Hari
													</div>
												</div>
											</div>
										</div>
										<div class="col2">
											<div class="date">
												 4 Step
											</div>
										</div>
										</a>
									</li>
								</ul>
								<h3 class="list-heading">Farmasi</h3>
								<ul class="feeds list-items">
									<li>
										<a href="javascript:;">
										<div class="col1">
											<div class="cont">
												<div class="cont-col1">
													<div class="label label-sm label-info">
														<i class="fa fa-briefcase"></i>
													</div>
												</div>
												<div class="cont-col2">
													<div class="desc">
														 Alur Permintaan Barang
													</div>
												</div>
											</div>
										</div>
										<div class="col2">
											<div class="date">
												 4 Step
											</div>
										</div>
										</a>
									</li>
									<li>
										<a href="javascript:;">
										<div class="col1">
											<div class="cont">
												<div class="cont-col1">
													<div class="label label-sm label-info">
														<i class="fa fa-briefcase"></i>
													</div>
												</div>
												<div class="cont-col2">
													<div class="desc">
														 Alur Pengepakan Box Paket
													</div>
												</div>
											</div>
										</div>
										<div class="col2">
											<div class="date">
												 4 Step
											</div>
										</div>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END QUICK SIDEBAR -->
	</div>
	<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->
<!-- FOOTER AND JS SCRIPT -->
<?php $this->load->view('_partials/foot');?> 
<!-- END FOOTER AND JS SCRIPT-->
</body>
<!-- END BODY -->
</html>