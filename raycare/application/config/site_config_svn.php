<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//config untuk nama aplikasi, foldering file
$config['site_name']                     = 'RayCare';
$config['site_initial']                  = 'RC_Core';
$config['site_dir']                      = 'raycare/';
$config['site_logo']                     = 'assets/mb/global/image/logo/logo-default.png';

$config['ip_default']                    = '10.10.1.123:8080/';
$config['folder_default']                = 'training/';

$config['url_core']						 = 'http://10.10.1.123:8080/training/core/';

$config['db_hostname']                   = 'localhost';
$config['db_username']                   = 'root';
$config['db_password']                   = '';
// $config['db_name']                       = 'training_raycare';
$config['db_name']                       = 'migrasi_raycare';

$config['base_url']                      = 'http://'.$config['ip_default'].$config['folder_default'].$config['site_dir'];

$config['site_img_temp_dir']             = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/var/temp/';
$config['site_img_temp_thumb_dir']       = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/var/temp/thumbs/';
$config['site_img_temp_thumb_small_dir'] = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/var/temp/thumbs2/';

$config['user_img_temp_dir']             = 'assets/mb/var/temp/';
$config['user_img_temp_thumb_dir']       = 'assets/mb/var/temp/thumbs/';
$config['user_img_temp_thumb_small_dir'] = 'assets/mb/var/temp/thumbs2/';

$config['site_user_img_dir']             = 'assets/mb/pages/master/user/images/';
$config['site_img_pasien']               = 'assets/mb/pages/master/pasien/images/';

$config['site_img_master']               = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/pages/master/';
$config['site_img_temp_dir_copy']        = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/pages/master/user/images/';
$config['site_img_pasien_temp_dir_copy'] = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/pages/master/pasien/images/';
$config['site_img_penjamin_scan']        = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/pages/master/penjamin/images/';
$config['site_img_item_temp_dir_copy']   = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/pages/master/item/images/';



$config['site_img_item_temp_dir']        = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/var/temp/';
$config['file_notif_location']           = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/var/file_notifikasi/';

//config untuk encripsi password & password default
$config['password_encryption_key']       = 'kemarinHujanLebatSekaliManaAnginnyaKencengBanget';
$config['new_password_reset']            = '123456';


//config untuk keperluan API
$config['api_key']                       = "bV0Vj1iBZHJypJOiwY6B7eodbHHlv8etTOE3pNqG";
$config['api_key_server']                = "bV0Vj1iBZHJypJOiwY6B7eodbHHlv8etTOE3pNqG";
$config['api_secret']                    = "qOT/YQXj6Q.IgpDIh64NEXb.YN70xYIi0tzSnkI1x2An6xnwP99nO9XqjvvL";
$config['api_secret_server']             = "qOT/YQXj6Q.IgpDIh64NEXb.YN70xYIi0tzSnkI1x2An6xnwP99nO9XqjvvL";
$config['api_host']                      = $config['url_core']."api_server";
$config['api_url']                       = $config['url_core']."api_server/";

//config untuk keperluan maintenance
$config['under_maintenance']             = 1;
//format tanggal maintenance adalah Y-m-d H:i:s
$config['finish_maintenance_date']       = '2015-01-26 09:00:00';

