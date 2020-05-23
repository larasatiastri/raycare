<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Controller extends CI_Controller {	

	// protected $menu_parent_id;	//this is database Parent menu_id
	protected $menu_id;	        //this is database menu_id
	public $data = array();		//data view

	public function __construct() {

		parent::__construct();

		$this->data['errors'] = array();
		$this->data['site_name'] = config_item('site_name');

		$is_maintenance = config_item('under_maintenance');
        $finish_date = config_item('finish_maintenance_date');
        $today = date('Y-m-d H:i:s');

        if($is_maintenance == 1 && $today <= $finish_date)
        {
            redirect('under_maintenance');
        }

		$this->load->model('master/user_m');     //panggil pake $this->user_m
		$this->load->model('master/user_level_menu_m'); //panggil pake $this->user_level_menu_m
		$this->load->model('master/user_level_m');     //panggil pake $this->user_m
		$this->load->model('master/user_log_m'); //panggil pake $this->user_log_m
		$this->load->model('master/bahasa_m'); //panggil pake $this->bahasa_m
		$this->load->model('master/cabang_m'); //panggil pake $this->bahasa_m
 		$this->load->model('pegawai/pegawai_user_m');
        $this->load->model('pegawai/laporan_kerja_m');
        $this->load->model('pegawai/laporan_kerja_detail_m');
        $this->load->model('pegawai/pengajuan_cuti_detail_m');
		// Login check
		// no check untuk method login & logout di home ctrl 
		// to avoid redirect loop
		$exception_uris = array(
			'home/lock_screen', 
			'home/login_ajax', 
			'home/login', 
			'home/login_site/aHR0cDovL3NpbXJocy5jb20vY29yZS9rbGFpbS92ZXJpZmlrYXNpX3BlbmRhZnRhcmFu', 
			'home/login_site/aHR0cDovLzEwLjEwLjEuMTIzOjgwODAvcHJlc2VudGF0aW9uL3JheWNhcmUva2xhaW0vYnVhdF9zZXA%3D', 
			'home/login_site/aHR0cDovL3NpbXJocy5jb20vcmF5Y2FyZS9rZXVhbmdhbi9wZXJzZXR1anVhbl9wZW1iYXlhcmFuX2thc2Jvbg%3D%3D', 
			'home/login_site/aHR0cDovL3NpbXJocy5jb20vcmF5Y2FyZS9rZXVhbmdhbi9wZXJzZXR1anVhbl9raXJpbV9wZXR0eV9jYXNo', 
			'home/login_site/aHR0cDovL3NpbXJocy5jb20vcmF2ZW5hL3BlbWJlbGlhbi9wZXJzZXR1anVhbl9wZXJtaW50YWFuX3Bv',
			'home/login_site/aHR0cDovL3NpbXJocy5jb20vcmF5Y2FyZS9rZXVhbmdhbi9wZXJzZXR1anVhbl9wZXJtaW50YWFuX2JpYXlh',
			'home/login_site/aHR0cDovL3NpbXJocy5jb20vY29yZS9wZWdhd2FpL3ZlcmlmX2xhcG9yYW5fa2VyamE%3D',
			'home/login_site/aHR0cDovL3NpbXJocy5jb20vY29yZS9wZWdhd2FpL2xhcG9yYW5fa2VyamE%3D',
			'home/logout', 
			
		);
        
        // die(dump(uri_string()));
		if (in_array(uri_string(), $exception_uris) === false) 
		{
			if (! $this->user_m->logged_in() )
				redirect($this->session->userdata('url_login').'home/login');			

			//Check User Level, in case user manually typing in address bar
			$result = get_menu_by_id($this->menu_id);

			$access_bit = get_menu_for_user($this->menu_id,$this->user_m->level_id());

			if(!$result) redirect($this->session->userdata('url_login').'home/login');	
	
			if (!$access_bit) redirect($this->session->userdata('url_login').'page_logout');
		
			$user_id = $this->session->userdata('user_id');
			$level_id = $this->session->userdata('level_id');
			$users = $this->user_m->get($user_id);
			$new_password = config_item('new_password_reset');

			$data_pegawai = $this->pegawai_user_m->get_by(array('user_id' => $user_id), true);

			if($users->password == $this->user_m->hash($new_password))
			{
				redirect($this->session->userdata('url_login').'pengaturan/ubah_password_awal');
				// redirect('home/logout');
			}
			
			$tgl_tiga = date('Y-m-d', strtotime("- 3 days"));
			$day_tiga = date('D', strtotime("- 3 days"));
			$tgl_dua = date('Y-m-d', strtotime("- 2 days"));
			$day_dua = date('D', strtotime("- 2 days"));
			$tgl_satu = date('Y-m-d', strtotime("- 1 days"));
			$day_satu = date('D', strtotime("- 1 days"));

			$cuti_tiga = $this->pengajuan_cuti_detail_m->get_cuti($data_pegawai->pegawai_id, $tgl_tiga)->result_array();
			$cuti_dua = $this->pengajuan_cuti_detail_m->get_cuti($data_pegawai->pegawai_id, $tgl_dua)->result_array();
			$cuti_satu = $this->pengajuan_cuti_detail_m->get_cuti($data_pegawai->pegawai_id, $tgl_satu)->result_array();

			$libur_satu = $this->pengajuan_cuti_detail_m->get_libur($tgl_satu)->result_array();
			$libur_dua = $this->pengajuan_cuti_detail_m->get_libur($tgl_dua)->result_array();
			$libur_tiga = $this->pengajuan_cuti_detail_m->get_libur($tgl_tiga)->result_array();

			$user_level_pass = array(
				'9','29','32','47'
			);

			if (in_array($level_id, $user_level_pass)) 
			{

			// if($level_id != 1 && $level_id != 2 && $level_id != 5 && $level_id != 10 && $level_id != 18 && $level_id != 19 && $level_id != 21 && $level_id != 22 && $level_id != 24 && $level_id != 26 && $level_id != 31 && $level_id != 33 && $level_id != 34 && $level_id != 36 && $level_id != 37 && $level_id != 39 && $level_id != 25 && $level_id != 46 && $level_id != 49  && $level_id != 50  && $level_id != 51 && $level_id != 40){
			// if($level_id == 1){
				// count($cuti_satu);

				if($day_tiga != 'Sat' && $day_tiga != 'Sun' && count($cuti_tiga) == 0 && count($libur_tiga) == 0){

		            $get_laporan_tiga = $this->laporan_kerja_detail_m->get_by(array('pegawai_id' => $data_pegawai->pegawai_id, 'date(tanggal)' => $tgl_tiga));

		            if(count($get_laporan_tiga)){
		                if($day_dua != 'Sat' && $day_dua != 'Sun' && count($cuti_dua) == 0 && count($libur_dua) == 0){
		                    $get_laporan_dua = $this->laporan_kerja_detail_m->get_by(array('pegawai_id' => $data_pegawai->pegawai_id, 'date(tanggal)' => $tgl_dua));
		                    if(count($get_laporan_dua)){

		                        if($day_satu != 'Sat' && $day_satu != 'Sun' && count($cuti_satu) == 0 && count($libur_satu) == 0){
		                            $get_laporan_satu = $this->laporan_kerja_detail_m->get_by(array('pegawai_id' => $data_pegawai->pegawai_id, 'date(tanggal)' => $tgl_satu));

		                            if(count($get_laporan_satu) == 0){
		                                redirect(config_item('url_core').'pegawai/laporan_kerja_pending/add/'.$tgl_satu);
		                            }
		                        }
		                        
		                    }else{
		                        redirect(config_item('url_core').'pegawai/laporan_kerja_pending/add/'.$tgl_dua);
		                    }

		                } 
		                
		            }else{
		                redirect(config_item('url_core').'pegawai/laporan_kerja_pending/add/'.$tgl_tiga);
		            }
		        }
		        if($day_dua != 'Sat' && $day_dua != 'Sun' && count($cuti_dua) == 0 && count($libur_dua) == 0){
		            $get_laporan_dua = $this->laporan_kerja_detail_m->get_by(array('pegawai_id' => $data_pegawai->pegawai_id, 'date(tanggal)' => $tgl_dua));
		            if(count($get_laporan_dua)){

		                if($day_satu != 'Sat' && $day_satu != 'Sun' && count($cuti_satu) == 0 && count($libur_satu) == 0){
		                    $get_laporan_satu = $this->laporan_kerja_detail_m->get_by(array('pegawai_id' => $data_pegawai->pegawai_id, 'date(tanggal)' => $tgl_satu));

		                    if(count($get_laporan_satu) == 0){
		                        redirect(config_item('url_core').'pegawai/laporan_kerja_pending/add/'.$tgl_satu);
		                    }
		                }
		                
		            }else{
		                redirect(config_item('url_core').'pegawai/laporan_kerja_pending/add/'.$tgl_dua);
		            }
		        }
		        if($day_satu != 'Sat' && $day_satu != 'Sun' && count($cuti_satu) == 0 && count($libur_satu) == 0){
		            $get_laporan_satu = $this->laporan_kerja_detail_m->get_by(array('pegawai_id' => $data_pegawai->pegawai_id, 'date(tanggal)' => $tgl_satu));

		            if(count($get_laporan_satu) == 0){
		                redirect(config_item('url_core').'pegawai/laporan_kerja_pending/add/'.$tgl_satu);
		            }
		        }
			}
		}
	}
}
/* End of file MY_Controller.php */
/* Location: ./application/controllers/MY_Controller.php */
