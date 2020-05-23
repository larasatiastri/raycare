<head>
    <meta charset="utf-8"/>
    <title><?php isset($title) || $title = ''; echo $title;?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

     <!--Start of Zopim Live Chat Script-->
    <!--<script type="text/javascript">
        window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
        d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
        _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
        $.src="//v2.zopim.com/?3lrVX1H8Uwe8T1NEMmszv9plRpz3Lsyj";z.t=+new Date;$.
        type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
    </script>-->

    <script src="<?=base_url()?>assets/mb/global/js/pace.min.js"></script>
    <link href="<?=base_url()?>assets/mb/global/css/pace-theme-center-radar.css" rel="stylesheet" />

    

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="<?=base_url()?>assets/metronic/admin/layout3/css/google-font-open-sans-400-300-600-700.css" rel="stylesheet" type="text/css"/>

    <link href="<?=base_url()?>assets/metronic/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>assets/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>assets/metronic/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url()?>assets/metronic/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->

    <?php if(isset($css_files) && count($css_files)): ?>

    <?php foreach ($css_files as $css): ?>

    <link href="<?=base_url().$css?>" rel="stylesheet" type="text/css"/>

    <?php endforeach; ?>

    <?php endif; ?>

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
    <link href="<?=base_url()?>assets/mb/global/css/offline-theme-chrome.css" rel="stylesheet" />
<link href="<?=base_url()?>assets/mb/global/css/offline-language-english.css" rel="stylesheet" />

    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico">
</head>

