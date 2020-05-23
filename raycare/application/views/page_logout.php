<!DOCTYPE html>
<html>
<head>
	<title>SIMRHS</title>

  <script src="<?=base_url()?>assets/mb/global/js/pace.min.js"></script>
    <link href="<?=base_url()?>assets/mb/global/css/pace-theme-center-radar.css" rel="stylesheet">

    

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="<?=base_url()?>assets/metronic/admin/layout3/css/google-font-open-sans-400-300-600-700.css" rel="stylesheet" type="text/css">

    <link href="<?=base_url()?>assets/metronic/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>assets/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>assets/metronic/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>assets/metronic/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->

    
    
    <link href="<?=base_url()?>assets/metronic/global/plugins/select2/select2.css" rel="stylesheet" type="text/css">

    
    <link href="<?=base_url()?>assets/metronic/admin/pages/css/login3.css" rel="stylesheet" type="text/css">

    
    
    <!-- END PAGE LEVEL STYLES -->

    <!-- BEGIN THEME STYLES -->
    <link href="<?=base_url()?>assets/metronic/global/css/components-rounded.min.css" id="style_components" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>assets/metronic/global/css/plugins.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>assets/metronic/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>assets/metronic/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color">
    <link href="<?=base_url()?>assets/metronic/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>assets/mb/global/css/maestrobyte.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>assets/metronic/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css">
<script src="<?=base_url()?>assets/mb/global/js/offline.min.js"></script>
    <link href="<?=base_url()?>assets/mb/global/css/offline-theme-chrome.css" rel="stylesheet">
<link href="<?=base_url()?>assets/mb/global/css/offline-language-english.css" rel="stylesheet">
<link href="<?=base_url()?>assets/metronic/pages/css/error.min.css" rel="stylesheet">

    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico">


<style type="text/css">
	.page-404 .number,.page-500 .number{letter-spacing:-10px;line-height:128px;font-size:128px;font-weight:300}.page-404 .details,.page-500 .details{margin-left:40px;display:inline-block}.page-404{text-align:center}.page-404 .number{position:relative;top:35px;display:inline-block;margin-top:0;margin-bottom:10px;color:#7bbbd6;text-align:right}.page-404-full-page .page-404,.page-500-full-page .page-500{margin-top:100px}.page-404 .details{padding-top:0;text-align:left}.page-500{text-align:center}.page-500 .number{display:inline-block;color:#ec8c8c;text-align:right}.page-500 .details{text-align:left}.page-404-full-page{overflow-x:hidden;padding:20px;margin-bottom:20px;background-color:#fafafa!important}.page-404-full-page .details input{background-color:#fff}.page-500-full-page{overflow-x:hidden;padding:20px;background-color:#fafafa!important}.page-500-full-page .details input{background-color:#fff}.page-404-3{background:#000!important}.page-404-3 .page-inner img{right:0;bottom:0;z-index:-1;position:absolute}.page-404-3 .error-404{color:#fff;text-align:left;padding:70px 20px 0}.page-404-3 h1{color:#fff;font-size:130px;line-height:160px}.page-404-3 h2{color:#fff;font-size:30px;margin-bottom:30px}.page-404-3 p{color:#fff;font-size:16px}@media (max-width:480px){.page-404 .details,.page-404 .number,.page-500 .details,.page-500 .number{text-align:center;margin-left:0}.page-404-full-page .page-404{margin-top:30px}.page-404-3 .error-404{text-align:left;padding-top:10px}.page-404-3 .page-inner img{right:0;bottom:0;z-index:-1;position:fixed}}</style>

</head>
<body class=" page-404-full-page">
        <div class="row">
            <div class="col-md-12 page-404">
                <div class="number font-red"> 404 </div>
                <div class="details">
                    <h3>Oops! <?=$user->username?> tidak memiliki akses.</h3>
                    <p> Kami tidak menemukan akses menu untuk userlevel <?=$user_level->nama?> anda pada program ini.
                        <br>
                        <a href="<?=$this->session->userdata('url_login')?>home/logout"> Kembali ke menu utama SIMRHS</a> untuk memilih program Raycare yang lain. </p>
                    <form action="#">
                        <div class="form-actions">
                            <a type="button" class="btn default" href="<?=$this->session->userdata('url_login')?>home/logout" style="margin-right:3px">
			<i class="glyphicon glyphicon-th-large"></i><span class="hidden-480">
	                             Main Menu	                        </span>
			</a>
                        </div>
                        <!-- /input-group -->
                    </form>
                </div>
            </div>
        </div>
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<script src="../assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script async="" src="//www.googletagmanager.com/gtm.js?id=GTM-W276BJ"></script><script async="" src="https://www.google-analytics.com/analytics.js"></script><script src="../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="../assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
        <script>
            $(document).ready(function()
            {
                $('#clickmewow').click(function()
                {
                    $('#radio1003').attr('checked', 'checked');
                });
            })
        </script>
    <!-- Google Code for Universal Analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-37564768-1', 'auto');
  ga('send', 'pageview');
</script>
<!-- End -->

<!-- Google Tag Manager -->
<noscript>&lt;iframe src="//www.googletagmanager.com/ns.html?id=GTM-W276BJ"
height="0" width="0" style="display:none;visibility:hidden"&gt;&lt;/iframe&gt;</noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-W276BJ');</script>
<!-- End -->



</body></html>