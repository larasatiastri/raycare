<?php
  $shift_aktif = 1;
  if(date('H:i:s') > '06:30:00' &&  date('H:i:s') <= '12:00:00'){
      $shift_aktif = 1;

  }if(date('H:i:s') > '12:00:01' &&  date('H:i:s') <= '18:30:00'){
      $shift_aktif = 2;
  }if(date('H:i:s') > '18:30:01' &&  date('H:i:s') <= '23:30:00'){
      $shift_aktif = 3;
  }
?>
<input type="hidden" class="form-control" id="shift_modal_foot" name="shift_modal_foot" value="<?=$shift_aktif?>"></input>
<div class="modal fade bs-modal-lg" id="modal_history" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" >
       <div class="modal-content">
       </div>
   </div>
</div>  <div class="modal fade bs-modal-lg" id="modal_history_kurir" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" >
       <div class="modal-content">
       </div>
   </div>
</div>
<div class="modal fade bs-modal-lg" id="modal_view" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" >
       <div class="modal-content">
       </div>
   </div>
</div> 
<div class="modal fade bs-modal-lg" id="modal_view_kurir" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" >
       <div class="modal-content">
       </div>
   </div>
</div>  
<div class="modal fade bs-modal-lg" id="modal_edit_jadwal" role="basic" aria-hidden="true">
   <div class="page-loading page-loading-boxed">
       <span>
           &nbsp;&nbsp;Loading...
       </span>
   </div>
   <div class="modal-dialog modal-lg" >
       <div class="modal-content">
       </div>
   </div>
</div>  
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="container-fluid">
         2018 &copy; Raycare Health Solution. All Rights Reserved.
    </div>
</div>
<div class="scroll-to-top">
    <i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="<?=base_url()?>assets/metronic/global/plugins/respond.min.js"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/global/plugins/bootstrap-sessiontimeout/jquery.sessionTimeout.min.js" type="text/javascript"></script>


<script src="<?=base_url()?>assets/metronic/global/scripts/metronic.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/admin/layout2/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/metronic/admin/layout3/scripts/demo.js" type="text/javascript"></script>

<script type="text/javascript" src="<?=base_url()?>assets/mb/global/js/mb.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/metronic/global/plugins/bootstrap-toastr/toastr.min.js"></script>

<script>

    $(function() {    

        var time = parseInt('<?=getSessionTimeLeft()?>');
        // alert(time);
        // set base url
        mb.baseUrl('<?=base_url()?>');
        mb.baseDir('<?=config_item("base_dir")?>');
        mb.loginUrl('<?=$this->session->userdata("url_login")?>');
        mb.timeout(time);
        // mb.showClock();
        // mb.handleSession();
        mb.handleCommet(); 
        // set Datatable language
        mb.DTLanguage(<?=json_encode(datatable_language($this->session->userdata("language")))?>  || {});
        // display flash message if any
        // mb.showMessage('<?=$this->session->flashdata("type")?>', '<?=$this->session->flashdata("msg")?>', '<?=$this->session->flashdata("msgTitle")?>');
        mb.showToast('<?=$this->session->flashdata("type")?>', '<?=$this->session->flashdata("msg")?>', '<?=$this->session->flashdata("msgTitle")?>');
        mb.handleAssetPath();
        QuickSidebar.init(); // init quick sidebar

        dis();
        check_jadwal();
        
    });

    function dis()
    {
      var dat        = new Date();
      var hr         = dat.getHours();
      var min        = dat.getMinutes();
      var sec        = dat.getSeconds();
      var set        = 1;
      var find        = false;
      var level_id   = '<?=$this->session->userdata("level_id")?>';


      hr2 = hr + 1;
      if(hr == 11 || hr == 15){
        hr2 = hr + 2;
      }if(hr == 19){
        hr2 = '09';
      }

      if(hr<10) hr   = '0'+hr;
      if(min<10) min = '0'+min;
      if(sec<10) sec = '0'+sec;

      $('input#d2').attr('value',hr2+'*00*00');

      document.getElementById('hour').value=hr;
      document.getElementById('min').value=min;
      document.getElementById('sec').value=sec;
      var dat2v = document.getElementById('d2').value;
      var dats =hr+'*'+min+'*'+sec;
      
      if(dat2v==dats && set)
      {
        if(level_id == 1 || level_id == 19){
          $('input#d2').attr('value',hr2+'*00*00');

          $('a#pasien_belum_datang').click();
        }
      }
      
      setTimeout("dis()",500);
      
    }

    function check_jadwal(){
      var dat        = new Date();
      var hr         = dat.getHours();
      var level_id   = '<?=$this->session->userdata("level_id")?>';
      baseAppUrl = '<?=config_item("url_core")?>';

      hr2 = hr + 1;
      if(hr == 11 || hr == 15){
        hr2 = hr + 2;
      }if(hr == 19){
        hr2 = '09';
      }

      $.ajax({
          type     : 'POST',
          url      : baseAppUrl + 'jadwal/get_jadwal_tindakan',
          data     : {shift:$('input#shift_modal_foot').val()},
          dataType : 'json',
          success  : function( results ) {
              var html = '';
              if(results.success == true){
                  if(hr2 == 13 || hr2 == 17 || hr == 19)
                  {
                    if(level_id == 19){
                      $('a#pasien_belum_datang').click();
                    }
                  }
              }
              if(results.success == false){
                 
              }  
          }
      });
    }

</script>



<!-- BEGIN PAGE LEVEL SCRIPS -->

<?php if(isset($js_files) && count($js_files)): ?>

<?php foreach ($js_files as $js): ?>

<script type="text/javascript" src="<?=base_url().$js?>"></script>

<?php endforeach; ?>

<?php endif;?>

<!-- END PAGE LEVEL SCRIPS -->

<!-- END JAVASCRIPTS -->

