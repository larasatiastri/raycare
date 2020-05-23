<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//config untuk nama aplikasi, foldering file
$config['site_name']                     = 'Klinik Raycare';
$config['site_initial']                  = 'RC_Core';
$config['site_dir']                      = 'raycare/';
$config['site_logo']                     = 'logo/logo-default.png';

$config['ip_default']                    = 'rhskld.com/';
$config['ip_real']                    	 = 'http://10.10.2.86/raycare/';
$config['ip_aja']                    	 = 'http://10.10.2.86/';
$config['folder_default']                = '';
$config['folder_cloud']                	 = '/'.$config['folder_default'].'cloud/'.$config['site_dir'].'pages/';

$config['url_core']                      = 'http://rhskld.com/core/';
$config['url_link_ravena']               = 'http://rhskld.com/ravena/';

$config['db_hostname']                   = '10.10.2.86';
$config['db_username']                   = 'cloud_raycare_kld';
$config['db_password']                   = 'P@ssw0rdkld';
// $config['db_name']                    = 'realnew_raycare';
$config['db_name']                       = 'realnew_raycare';

// $config['base_dir']                      = 'http://'.$config['ip_default'].$config['folder_default'];
$config['base_dir']                      = $config['ip_aja'].$config['folder_default'];
$config['base_url']                      = 'http://'.$config['ip_default'].$config['folder_default'].$config['site_dir'];

$config['site_img_temp_dir']             = '/'.$config['folder_default'].'cloud/temp/';
$config['site_img_temp_thumb_dir']       = '/'.$config['folder_default'].'cloud/temp/thumbs/';
$config['site_img_temp_thumb_small_dir'] = '/'.$config['folder_default'].'cloud/temp/thumbs2/';
$config['site_img_temp_thumb_fix_dir'] = '/'.$config['folder_default'].'cloud/temp/thumbsfix/';

$config['user_img_temp_dir']             = 'cloud/temp/';
$config['user_img_temp_thumb_dir']       = 'cloud/temp/thumbs/';
$config['user_img_temp_thumb_small_dir'] = 'cloud/temp/thumbs2/';

$config['site_img_temp_dir_new']             = '/'.$config['folder_default'].'cloud/temp/';
$config['site_img_temp_thumb_dir_new']       = '/'.$config['folder_default'].'cloud/temp/thumbs/';
$config['site_img_temp_thumb_small_dir_new'] = '/'.$config['folder_default'].'cloud/temp/thumbs2/';
$config['site_img_temp_thumb_fix_dir_new'] = '/'.$config['folder_default'].'cloud/temp/thumbsfix/';
$config['file_sep_ina_ori'] = '/'.$config['folder_default'].'cloud/temp/file_sep_ina_ori/';

$config['user_img_temp_dir_new']             = 'cloud/temp/';
$config['user_img_temp_thumb_dir_new']       = 'cloud/temp/thumbs/';
$config['user_img_temp_thumb_small_dir_new'] = 'cloud/temp/thumbs2/';
$config['user_img_temp_thumb_fix_dir_new'] = 'cloud/temp/thumbsfix/';

$config['site_logo_real']             = 'cloud/'.$config['site_dir'].'logo/logo-real.png';
$config['site_user_img_dir']             = 'cloud/'.$config['site_dir'].'pages/master/user/images/';
$config['site_img_pasien']               = 'cloud/'.$config['site_dir'].'pages/master/pasien/images/';
$config['site_img_inv_dir']               = 'cloud/'.$config['site_dir'].'pages/inventaris/inventaris/images/';
$config['site_img_sppd_dir']               = 'cloud/'.$config['site_dir'].'pages/klinik_hd/surat_dokter_sppd/images/';

$config['site_img_master']               = $config['folder_cloud'].'master/';
$config['site_img_temp_dir_copy']        = $config['folder_cloud'].'master/user/images/';
$config['site_img_pasien_temp_dir_copy'] = $config['folder_cloud'].'master/pasien/images/';
$config['site_img_penjamin_scan']        = $config['folder_cloud'].'master/penjamin/images/';
$config['site_img_item_temp_dir_copy']   = $config['folder_cloud'].'master/item/images/';
$config['site_img_jual_obat_dir_copy']   = $config['folder_cloud'].'apotik/penjualan_obat/images/';
$config['site_img_bukti_setoran']   	 = $config['folder_cloud'].'kasir/setoran_kasir/images/';

$config['site_img_invoice_doc']   = $config['folder_cloud'].'klinik_hd/history_transaksi/docs/';
$config['site_img_invoice']   = $config['folder_cloud'].'klinik_hd/history_transaksi/images/';
$config['site_img_bon']   			   		   = $config['folder_cloud'].'keuangan/permintaan_biaya/images/';
$config['site_img_saldo']   			   	   = $config['folder_cloud'].'keuangan/titip_terima_setoran/images/';
$config['site_img_petty_cash']   			   = $config['folder_cloud'].'keuangan/kirim_petty_cash/images/';
$config['site_img_edc']   			   = $config['folder_cloud'].'reservasi/pembayaran/images/';
$config['site_img_inventaris']   			   = $config['folder_cloud'].'inventaris/inventaris/images/';
$config['site_img_sppd']   			   = $config['folder_cloud'].'klinik_hd/surat_dokter_sppd/images/';
$config['site_img_hasil_lab']   			   = $config['folder_cloud'].'tindakan/input_hasil_lab/images/';
$config['site_img_hasil_lab_manual']   			 = $config['folder_cloud'].'tindakan/input_hasil_lab_manual/images/';
$config['site_img_ttf']   			 = $config['folder_cloud'].'keuangan/tanda_terima_faktur/images/';
$config['site_img_bayar']   			 = $config['folder_cloud'].'keuangan/pembayaran_transaksi/images/';
$config['site_img_proses_bayar']   			 = $config['folder_cloud'].'keuangan/proses_pembayaran_transaksi/images/';
$config['site_img_faktur_pmb_farmasi']   			 = $config['folder_cloud'].'gudang/barang_datang_farmasi/images/';
$config['site_img_faktur_pmb']   			 = $config['folder_cloud'].'gudang/barang_datang/images/';
$config['site_img_pengajuan_ps']   			 = $config['folder_cloud'].'keuangan/pengembalian_dana/images/';


$config['site_img_item_temp_dir']        = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/var/temp/';
$config['file_notif_location']           = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/var/file_notifikasi/';
$config['file_notif_antrian_location']           = '/'.$config['folder_default'].'antrian/assets/var/file_notifikasi/';

//config untuk encripsi password & password default
$config['password_encryption_key']       = 'kemarinHujanLebatSekaliManaAnginnyaKencengBanget';
$config['new_password_reset']            = '123456';

$config['email_protocol']                = 'smtp';
$config['email_smtp_host']               = 'ssl://smtp.googlemail.com';
$config['email_smtp_port']               = 465;
$config['email_smtp_user']               = 'sim.raycarehealthsolution@gmail.com';
$config['email_smtp_pass']               = 'simrhs2015';

//config untuk keperluan API
$config['api_key']                       = "bV0Vj1iBZHJypJOiwY6B7eodbHHlv8etTOE3pNqG";
$config['api_key_server']                = "bV0Vj1iBZHJypJOiwY6B7eodbHHlv8etTOE3pNqG";
$config['api_secret']                    = "qOT/YQXj6Q.IgpDIh64NEXb.YN70xYIi0tzSnkI1x2An6xnwP99nO9XqjvvL";
$config['api_secret_server']             = "qOT/YQXj6Q.IgpDIh64NEXb.YN70xYIi0tzSnkI1x2An6xnwP99nO9XqjvvL";
$config['api_host']                      = $config['url_core']."api_server";
$config['api_url']                       = $config['url_core']."api_server/";
$config['url_bpjs']                       = "http://10.10.2.124:8082/SepLokalRest/";

//config untuk keperluan maintenance
$config['under_maintenance']             = 1;
//format tanggal maintenance adalah Y-m-d H:i:s
$config['finish_maintenance_date']       = '2015-01-26 09:00:00';
$config['max_total_piutang']             = 2000000;
$config['penjamin_id']                   = array(2,3,5,6,7,8,9);
$config['dialyzer_id']             		 = '(1,133,134,259,271,392,431,432,433,434,459,460,461,462,501,724,867,886,1002,1063,1117,1130,1198,1341,1403,1404,1405,1406,1407,1408,1409,1410,1411,1635,1636,1713,1714,2969,2974,2968)';
$config['dialyzer_id_array']             		 = array(1,133,134,259,271,392,431,432,433,434,459,460,461,462,501,724,867,886,1002,1063,1117,1130,1198,1341,1403,1404,1405,1406,1407,1408,1409,1410,1411,1635,1636,1713,1714,2969,2974,2968);
$config['tarif_rs']					 	 = 900000;
$config['tarif_ina']					 = 737700;
$config['user_developer']				 = 1;
$config['file_ina_ori']				 	 = '/'.$config['folder_default'].$config['site_dir'].'assets/mb/var/inacbg/ori';
$config['file_ina_ori_import']			 = 'E:/www/raycare/assets/mb/var/inacbg/ori/';
$config['file_ina_modif_exp']			 = 'E:/www/raycare/assets/mb/var/inacbg/modif/';
$config['file_sep_ina_ori_import']		 = 'E:/www/cloud/temp/file_sep_ina_ori/';
$config['id_surat_sppd']				 = 18;
$config['id_surat_tiga_kali']		     = 21;
$config['penjamin_bpjs']           		 = '(2,3,5,6,7,8,9)';
$config['penunjang_medis']         		 = array(1,4);
$config['obat_vitamin']            		 = array(2,3);
$config['hbsag_id']            		 	 = 11;
$config['limit_cash']            		 = 2000000;
$config['apotik_id']            		 = 4;
$config['level_marketing_id']            = 20;
