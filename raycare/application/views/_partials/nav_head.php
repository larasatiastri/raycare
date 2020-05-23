    <!-- BEGIN HEADER TOP -->
    <div class="page-header-top">
        <div class="container-fluid">
            <!-- BEGIN LOGO -->
            <?php
                $cabang_id=$this->session->userdata('cabang_id');
                $cabang = $this->cabang_m->get($cabang_id);
            ?>
            <div class="page-logo">
                <a href="<?=base_url()?>home/dashboard"><img src="<?=config_item('base_dir').'cloud/'.config_item('site_dir').'logo/'.$this->session->userdata('site_logo')?>" alt="logo" class="logo-default"></a>
                <?php
                    if($cabang->tipe == 1){
                ?>
                <span class="label label-sm label-success"> <?=$cabang->kode;?> </span>
                <?php  
                    }
                ?>
            </div>
            <!-- END LOGO -->
            <div id="sound"></div>
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler"></a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <a title="" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#modal_session" name="session" id="session" href="<?=base_url()?>home/lock_screen" class="btn btn-xs blue-chambray hidden"><i class="fa fa-edit"></i></a>
                <ul class="nav navbar-nav pull-right maestrobyte">
                <li class="dropdown dropdown-extended dropdown-dark">
                    <input type="hidden" id="hour">
                    <input type="hidden" id="min">
                    <input type="hidden" id="sec">
                    <input type="hidden" id="d2">
                </li>
                <li class="dropdown dropdown-extended dropdown-dark" id="session">
                    <a title="Session" href="<?=config_item('url_core')?>upload/session" id="session" class="dropdown-toggle hidden" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#modal_session">
                        <i class="fa fa-user" style="color:#2462AC;"></i>
                    </a>
                </li> 
                <li class="dropdown dropdown-extended dropdown-dark" id="pasien_belum_datang">
                    <a title="Pasien Belum Datang" href="<?=config_item('url_core')?>jadwal/pasien_belum_datang" id="pasien_belum_datang" class="dropdown-toggle hidden" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#modal_pasien_tidak_hadir">
                        <i class="fa fa-user" style="color:#2462AC;"></i>
                    </a>
                </li>
                <li class="dropdown dropdown-extended dropdown-dark dropdown-notification" id="show_calendar">
                    <a title="Kalender Kegiatan" href="<?=config_item('url_core')?>calendar" class="dropdown-toggle">
                        <i class="fa fa-calendar" style="color:#2462AC;"></i>
                    </a>
                </li>
                <li class="dropdown dropdown-extended dropdown-dark dropdown-notification" id="sent_correction">
                    <a title="Permintaan Perubahan Data" href="<?=config_item('url_core')?>correction/send_correction" class="dropdown-toggle" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#modal_correction">
                        <i class="fa fa-ticket" style="color:#2462AC;"></i>
                    </a>
                </li>
                <li class="dropdown dropdown-extended dropdown-dark dropdown-notification" id="ask_kurir">
                    <a title="Permintaan Jasa Kurir" href="<?=config_item('url_core')?>correction/ask_kurir" class="dropdown-toggle" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#modal_ask_kurir">
                        <!-- <i class="fa fa-truck" style="color:#FFF"></i> -->
                        <i class="fa fa-truck" style="color:#2462AC"></i>
                    </a>
                </li>
                                        <!-- BEGIN CONTACT DEVELOPER -->
                    
                    <!-- END CONTACT DEVELOPER -->
                    <!-- BEGIN LANGUAGE BAR -->
                    <li class="dropdown dropdown-language">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <!-- <img alt="" src="../../assets/global/img/flags/us.png"> -->
                            <img src="<?=base_url()?>assets/global/img/flags/<?=$this->session->userdata('language')?>.png"/>
                            <span class="username"> <?=$this->session->userdata('language')?> </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                        <?php foreach ($this->bahasa_m->get_active_langs() as $lang):?>
                            <li>
                                <a href="<?=base_url()?>master/bahasa/ganti_bahasa/<?=$lang->id?>/<?=urlencode(base64_encode(base_url().$this->uri->uri_string()));?>">
                                    <img src="<?=base_url()?>assets/global/img/flags/<?=$lang->kode?>.png"/> <?=$lang->nama?>
                                </a>
                            </li>
                        <?php endforeach;?>
                        </ul>
                    </li>
                    <!-- END LANGUAGE BAR -->
                    <!-- BEGIN NOTIFICATION DROPDOWN -->
                    <li class="dropdown dropdown-extended dropdown-dark dropdown-notification hidden" id="header_notification_bar">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-bell"></i>
                        <span class="badge badge-default" id="notif_count"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="external">
                                <h3 id="notif_title"></h3>
                            
                            </li>
                            <li>
                                <ul class="dropdown-menu-list scroller" style="height: 250px;" id="notif_content" data-handle-color="#637283">
                                    
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- END NOTIFICATION DROPDOWN -->
                    
                    <li class="droddown dropdown-separator">
                        <span class="separator"></span>
                    </li>
                   
                    <!-- END INBOX DROPDOWN -->
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <li class="dropdown dropdown-user dropdown-dark">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?php
                            if($this->session->userdata('url') != ''){


                            $url = config_item('base_dir').config_item('site_user_img_dir').$this->session->userdata('url');
                            $result = @get_headers($url);
                            if($result[0] == 'HTTP/1.1 200 OK')
                            {
                        ?>
                                <img alt="" class="img-circle" src="<?=config_item('base_dir').config_item('site_user_img_dir').$this->session->userdata('url')?>">
                        <?php
                            }
                            else
                            {
                         ?>
                               <img alt="" class="img-circle" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png">

                         <?php       
                            }
                        }
                        else{
                            ?>
                            <img alt="" class="img-circle" src="<?=config_item('base_dir').config_item('site_user_img_dir')?>global/global.png">
                            <?php
                        }
                        ?>
                        <span class="username username-hide-mobile"><?=$this->session->userdata('nama_lengkap')?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                        
                            <li>
                                <a href="<?=config_item('url_core')?>pegawai/pegawai/profile">
                                <i class="icon-user"></i><?=translate('Profile Saya', $this->session->userdata('language'))?>
                                </a>
                            </li>
                            <li>
                                <a href="<?=base_url()?>pengaturan/ubah_password">
                                <i class="icon-settings"></i><?=translate('Ganti Password', $this->session->userdata('language'))?> </a>
                            </li>
                            <li class="divider">
                            </li>
                            <li class="hidden">
                                <a href="extra_lock.html">
                                <i class="icon-lock"></i><?=translate('Kunci Layar', $this->session->userdata('language'))?></a>
                            </li>
                            <li>
                                <a onclick="bootbox.confirm('<?=translate("Anda yakin akan keluar dari aplikasi ini?",$this->session->userdata("language"))?>', function(result) {if(result==true) {location.href='<?=base_url()?>home/logout'; } });">
                                <i class="icon-logout"></i><?=translate('Keluar', $this->session->userdata('language'))?></a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <li class="dropdown dropdown-extended quick-sidebar-toggler">
                        <span class="sr-only">Toggle Quick Sidebar</span>
                        <i class="icon-logout"></i>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
    </div>
    <!-- END HEADER TOP -->
<div class="modal fade bs-modal-lg" id="modal_correction" role="basic" aria-hidden="true">
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
<div class="modal fade bs-modal-lg" id="modal_ask_kurir" role="basic" aria-hidden="true">
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
<div class="modal fade bs-modal-lg" id="modal_pasien_tidak_hadir" role="basic" aria-hidden="true">
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
<div class="modal fade bs-modal-lg" id="modal_session" role="basic" aria-hidden="true">
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