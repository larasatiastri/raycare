<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testing extends CI_Controller {

	public function __construct()
	{		
		parent::__construct();

	}

	public function index()
	{
		// $data = get_data_website_supplier('http://simrhs.com/supplier/');

		$ftpHost   = 'ftp.restupratamafarmasi.com';
		$ftpUsername = 'cloud@klinikraycare.com';
		$ftpPassword = 'P@ssw0rddesign';


		// open an FTP connection
		$connId = ftp_connect($ftpHost) or die("Couldn't connect to $ftpHost");


		// login to FTP server
		$ftpLogin = ftp_login($connId, $ftpUsername, $ftpPassword);
		ftp_pasv($connId, true);




				$localFilePath  = '../cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/test2.jpg';
				//$localFilePath1  = '../cloud/'.config_item('site_dir').'pages/keuangan/tanda_terima_faktur/images/test_2.jpg';

				$remoteFilePath = 'supblt/pages/transaksi/tanda_terima_faktur/images/STF-12-2018-0003/181218153550_2018-12-18-0007_14.jpg';
				//$remoteFilePath1 = 'supblt/pages/transaksi/tanda_terima_faktur/images/'.str_replace(' ', '_', $ttf_detail['tanda_terima_faktur_id']).'/'.$ttf_detail['url_faktur_pajak'];

				// try to download a file from server
				if(ftp_get($connId, $localFilePath, $remoteFilePath, FTP_BINARY)){
				    echo "File transfer successful - $localFilePath";
				}else{
					die(dump(ftp_get($connId, $localFilePath, $remoteFilePath, FTP_BINARY)));
				}


		// close the connection
		ftp_close($connId);



	}

}

/* End of file grab_data.php */
/* Location: ./application/controllers/grab_data.php */