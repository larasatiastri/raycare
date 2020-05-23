<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_server extends CI_Controller {
	var $request_method;
	var $http_header;

	public function __construct() {
		parent::__construct();

		
		// Do the general validation here
		$method = $this->uri->segment(2);	// get controller method

		$this->request_method = $this->input->server('REQUEST_METHOD');

		// Get PHP Header
		$this->http_header = getallheaders();

		// echo json_encode($this->http_header);
		// exit;

		
		// Check header sent
		if(!isset($this->http_header['Accept']) && !isset($this->http_header['X-const-id']) && !isset($this->http_header['X-timestap']) && !isset($this->http_header['X-signature'])) {
			echo deliver_response(400, "fail", "Header Not Set Correctly");
			exit;
		}

		// Check header accept parameter
		if($this->http_header['Accept'] != "application/json"){
			echo deliver_response(400, "fail", "Content Type Not Supported");
			exit;
		}

		if(time_out_of_bound((int) gmdate('U'), $this->http_header['X-timestamp'])) {
			echo deliver_response(400, "fail", "Timestamp Not Valid");
			exit;
		}

		// Check if no method requested
		if(empty($method)) {
			echo deliver_response(404, "fail", "Method Parameter Can Not Empty");
			exit;
		}

		// Check if method not exists
		if(!method_exists(__CLASS__, $method)) {
			echo deliver_response(404, "fail", "Method Not Found");
			exit;
		}

		$this->load->model('master/user_level_menu_m');
		$this->load->model('master/user_m');
		$this->load->model('master/user_level_m');
		$this->load->model('master/user_level_persetujuan_m');
		$this->load->model('master/transaksi_dokter_m');
		$this->load->model('reservasi/pendaftaran/pendaftaran_tindakan_m');
		$this->load->model('others/notifikasi/notifikasi_setting_m');
		$this->load->model('others/notifikasi/notifikasi_m');
		$this->load->model('others/notifikasi/notifikasi_history_m');
		$this->load->model('others/fitur_m');
	}

	public function index() {
		$factory = new QueryAuth\Factory();
		$server  = $factory->newServer();
		
		$data_api = $this->api_m->get_by(array('secret'	=> $this->input->post('key'), 'status' => 1 ),true);
		$secret   = $data_api->secret;
		$method   = 'GET';
		$host     = $this->http_header['Host'];
		$path     = '/' . $this->router->class . '/' . $this->router->method;
		// querystring params or request body as an array,
		// which includes timestamp, key, and signature params from the client's
		// getSignedRequestParams method
		$params  = $this->input->post();
		
		$isValid = $server->validateSignature($secret, $method, $host, $path, $params);
		
		echo json_encode($isValid);
	}

	public function get_menu_file($menu_file,$user_level_id)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$menu_file = base64_decode(urldecode($menu_file));
	
		if (file_exists(FCPATH.$menu_file) && is_file(FCPATH.$menu_file)) {
			$serialized = read_file(FCPATH.$menu_file);
			$menus = unserialize($serialized);
            if (! is_array($menus)) $menus = array();

			echo json_encode($menus);			
		}
		else
		{
			$where = array(
        		'user_level_menu.user_level_id' => $user_level_id,
            );

	        $select = array(
	            'user_level_menu.*',
	            'cabang.url as base_url' 
	            );
	        
	        $this->db
	            ->select($select)
	            ->where($where)
	            ->join('cabang','user_level_menu.cabang_id = cabang.id','left')
	            ->order_by('m_order');
	            
	        $result     = $this->db->get('user_level_menu')->result_array();
	        
	        $row = array();
	        $i=0;
	        foreach ($result as $rows) 
	        {
	        	$row[] = $rows;
	        	if($rows['base_url'] == NULL && $rows['url'] == NULL)
	        	{
	        		$row[$i]['url'] = $rows['url'];
	        	}
	        	if($rows['base_url'] == NULL && $rows['url'] != NULL)
	        	{
	        		$row[$i]['url'] = base_url().$rows['url'];
	        	}
	        	if($rows['base_url'] != NULL && $rows['url'] != NULL)
	        	{
	        		$row[$i]['url'] = $rows['base_url'].$rows['url'];
	        	}
	        	
	        	$i++;
	        }
			$menus      = $this->user_level_menu_m->build_menu($result);
			$menus_file = $this->user_level_menu_m->build_menu($row);
			$serialized = serialize($menus_file);
	        // echo json_encode($serialized);
	        // exit();

	        if (! write_file(FCPATH.$menu_file, $serialized, 'w+')) {
	            die('unable to write menu file');
	        }

			echo json_encode($menus);
		}
	}

	public function delete_menu_file($menu_file)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$menu_file = base64_decode(urldecode($menu_file));
	
		if (file_exists(FCPATH.$menu_file) && is_file(FCPATH.$menu_file)) {
			unlink(FCPATH.$menu_file);

			echo json_encode(true);			
		}
	}

	public function get_menu_by_id($menu_id)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$result = $this->user_level_menu_m->get_by(array('unik_id' => $menu_id),true);

		if($result)
		{
			echo json_encode(true);			
		}
		else
		{
			echo json_encode(false);			
		}
	}

	public function get_menu_for_userlevel($menu_id, $user_level_id)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$access_bit = $this->user_level_menu_m->get_by(array('user_level_id' => $user_level_id, 'unik_id' => $menu_id));

		if($access_bit)
		{
			echo json_encode(true);			
		}
		else
		{
			echo json_encode(false);			
		}
	}

	public function insert_user()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$user_id = 0;
		$user_login_id = 0;		
		$apisign_param = $this->input->post();

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}

		if(isset($apisign_param['user_id'])) {
			$user_id = $apisign_param['user_id'];
			$user_id = str_replace('"', '', $user_id);
			unset($apisign_param['user_id']);
		}
		if(isset($apisign_param['created_by'])) {
			$user_login_id = $apisign_param['created_by'];
			unset($apisign_param['created_by']);
		}

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_user = $apisign_param;
		if(isset($data_user['apikey'])) {
			unset($data_user['apikey']);
		}

		$users = $this->user_m->get_data_by_id($user_id)->row(0);
		if(count($users) == 0)
		{
			$id = $this->user_m->save_api($user_login_id,$data_user);				
		}
		else
		{
			$id = $this->user_m->save_api($user_login_id,$data_user, $user_id);	
		}	

		echo json_encode($id);
		
		
	}

	public function edit_user_api()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$user_id = $this->input->post('id');
		$apisign_param = $this->input->post();

		// die(dump($apisign_param));
		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
			unset($apisign_param['id']);
		}
		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_user = $apisign_param;

		if(isset($data_user['apikey'])) {
			unset($data_user['apikey']);
		}

	
		$id = $this->user_m->save($data_user, $user_id);					

		echo json_encode($id);
	}

	public function edit_status_api()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$user_id = $this->input->post('id');
		$apisign_param = $this->input->post();

		// die(dump($apisign_param));
		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
			unset($apisign_param['id']);
		}
		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_user = $apisign_param;

		if(isset($data_user['apikey'])) {
			unset($data_user['apikey']);
		}

	
		$id = $this->user_m->save($data_user, $user_id);					

		echo json_encode($id);
	}

	public function edit_password_api()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$user_id = $this->input->post('id');
		$apisign_param = $this->input->post();

		// die(dump($apisign_param));
		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
			unset($apisign_param['id']);
		}
		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_user = $apisign_param;

		if(isset($data_user['apikey'])) {
			unset($data_user['apikey']);
		}

	
		$id = $this->user_m->save($data_user, $user_id);					

		echo json_encode($id);
	}

	public function move_user_photo($data_path)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');
		$data_path = base64_decode(urldecode($data_path));
		$data_path = unserialize($data_path);
		$data_path = object_to_array($data_path);
		

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$path_dokumen        = $data_path['path_dokumen'];
		$path_dokumen_thumb  = $data_path['path_dokumen_thumb'];
		$path_dokumen_thumb2 = $data_path['path_dokumen_thumb2'];
		$path_temporary      = $data_path['path_temporary'];
		$temp_filename       = $data_path['temp_filename'];             
		
		if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
        if (!is_dir($path_dokumen_thumb)){mkdir($path_dokumen_thumb, 0777, TRUE);}
        if (!is_dir($path_dokumen_thumb2)){mkdir($path_dokumen_thumb2, 0777, TRUE);}

        $full_path_temp_filename = $path_temporary."/".$temp_filename;


        $convtofile = new SplFileInfo($temp_filename);
        $extenstion = ".".$convtofile->getExtension();

        $new_filename = $data_path['username'].$extenstion;

		$ori_file    = $data_path['username'].'/'.$new_filename;
		$medium_file = $data_path['username'].'/medium/'.$new_filename;
		$small_file  = $data_path['username'].'/small/'.$new_filename;

		// if(file_exists($data_path['path_temp'].$temp_filename))
		// {
	        copy($data_path['path_temp'].$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_temp_dir_copy').$ori_file);
	        copy($data_path['path_temp_thumbs'].$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_temp_dir_copy').$medium_file);
	        copy($data_path['path_temp_thumbs2'].$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_temp_dir_copy').$small_file);
		// }
		
		echo json_encode($ori_file);
	}

	public function move_user_sign_photo($data_path)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');
		$data_path = base64_decode(urldecode($data_path));
		$data_path = unserialize($data_path);
		$data_path = object_to_array($data_path);
		

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$path_dokumen        = $data_path['path_dokumen'];
		$path_temporary      = $data_path['path_temporary'];
		$temp_filename       = $data_path['temp_filename'];             
		
		if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

        $full_path_temp_filename = $path_temporary."/".$temp_filename;


        $convtofile = new SplFileInfo($temp_filename);
        $extenstion = ".".$convtofile->getExtension();

        $new_filename = $data_path['username'].'_sign'.$extenstion;

		$ori_file    = $data_path['username'].'/'.$new_filename;

		// if(file_exists($data_path['path_temp'].$temp_filename))
		// {
	        copy($data_path['path_temp'].$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_temp_dir_copy').$ori_file);
		// }
		
		echo json_encode($new_filename);
	}
	public function move_pasien_photo($data_path)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');
		$data_path = base64_decode(urldecode($data_path));
		$data_path = unserialize($data_path);
		$data_path = object_to_array($data_path);
		

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$path_dokumen        = $data_path['path_dokumen'];
		$path_dokumen_real   = './assets/mb/pages/master/pasien/images/'.$data_path['no_member'].'/foto';
		$path_dokumen_thumb  = $data_path['path_dokumen_thumb'];
		$path_dokumen_thumb2 = $data_path['path_dokumen_thumb2'];
		$path_temporary      = $data_path['path_temporary'];
		$temp_filename       = $data_path['temp_filename'];      

		$date = date('ymdHis');       
		
		if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}
		if (!is_dir($path_dokumen_real)){mkdir($path_dokumen_real, 0777, TRUE);}
        if (!is_dir($path_dokumen_thumb)){mkdir($path_dokumen_thumb, 0777, TRUE);}
        if (!is_dir($path_dokumen_thumb2)){mkdir($path_dokumen_thumb2, 0777, TRUE);}

        $full_path_temp_filename = $path_temporary."/".$temp_filename;


        $convtofile = new SplFileInfo($temp_filename);
        $extenstion = ".".$convtofile->getExtension();

        $new_filename = $date.'_'.$data_path['no_member'].$extenstion;

		$ori_file    = $data_path['no_member'].'/foto/'.$new_filename;
		$medium_file = $data_path['no_member'].'/foto/medium/'.$new_filename;
		$small_file  = $data_path['no_member'].'/foto/small/'.$new_filename;

		// if(file_exists($data_path['path_temp'].$temp_filename))
		// {
	        copy($data_path['path_temp'].$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pasien_temp_dir_copy').$ori_file);
	        copy($data_path['path_temp_thumbs'].$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pasien_temp_dir_copy').$medium_file);
	        copy($data_path['path_temp_thumbs2'].$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pasien_temp_dir_copy').$small_file);
		// }
		
		echo json_encode($new_filename);
	}

	public function move_pasien_keluarga_doc($data_path)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');
		$data_path = base64_decode(urldecode($data_path));
		$data_path = unserialize($data_path);
		$data_path = object_to_array($data_path);
		

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$path_dokumen        = $data_path['path_dokumen'];
		$path_temporary      = $data_path['path_temporary'];
		$temp_filename       = $data_path['temp_filename'];             
		$tipe       		 = $data_path['tipe'];             
		
		if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

        $full_path_temp_filename = $path_temporary."/".$temp_filename;


        $convtofile = new SplFileInfo($temp_filename);
        $extenstion = ".".$convtofile->getExtension();

        $new_filename = $data_path['no_ktp'].$extenstion;

		$ori_file    = $data_path['no_ktp'].'/'.$new_filename;

		// if(file_exists($data_path['path_temp'].$temp_filename))
		// {
	        copy($data_path['path_temp'].$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pasien_temp_dir_copy').$data_path['no_member'].'/'.$tipe.'/'.$ori_file);
		// }
		
		echo json_encode($ori_file);
	}

	public function move_penjamin_scan($data_path)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');
		$data_path = base64_decode(urldecode($data_path));
		$data_path = unserialize($data_path);
		$data_path = object_to_array($data_path);
		

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$path_dokumen        = $data_path['path_dokumen'];
		$path_temporary      = $data_path['path_temporary'];
		$temp_filename       = $data_path['temp_filename'];             
		
		if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

        $full_path_temp_filename = $path_temporary."/".$temp_filename;


        $convtofile = new SplFileInfo($temp_filename);
        $extenstion = ".".$convtofile->getExtension();

        $new_filename = str_replace(' ', '_', $data_path['keterangan_scan']).'_'.$data_path['index'].$extenstion;
		$ori_file    = $data_path['penjamin_id'].'_'.$data_path['nama_dokumen'].'/'.$new_filename;

	    copy($data_path['path_temp'].$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_penjamin_scan').$ori_file);
	
		
		echo json_encode($ori_file);
	}

	public function move_pasien_penj_dok($data_path)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');
		$data_path = base64_decode(urldecode($data_path));
		$data_path = unserialize($data_path);
		$data_path = object_to_array($data_path);
		

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$tipe        = $data_path['tipe_dokumen'];
		$tipe_folder = '';
		if($tipe == 1)
		{
			$tipe_folder  = 'pelengkap';
			$path_dokumen = $data_path['path_dokumen'].'/pelengkap';			
		}
		if($tipe == 2)
		{
			$tipe_folder  = 'rekam_medis';
			$path_dokumen = $data_path['path_dokumen'].'/rekam_medis';			
		}
		$path_temporary      = $data_path['path_temporary'];
		$temp_filename       = $data_path['temp_filename'];             
		
		if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

        $full_path_temp_filename = $path_temporary."/".$temp_filename;


        $convtofile = new SplFileInfo($temp_filename);
        $extenstion = ".".$convtofile->getExtension();

        $new_filename = $temp_filename;
		$ori_file    = $data_path['no_pasien'].'/dokumen/'.$tipe_folder.'/'.$new_filename;

	    copy($data_path['path_temp'].$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pasien_temp_dir_copy').$ori_file);
	
		
		echo json_encode($new_filename);
	}


	public function move_pasien_penj_dok_rekmed($data_path)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');
		$data_path = base64_decode(urldecode($data_path));
		$data_path = unserialize($data_path);
		$data_path = object_to_array($data_path);
		

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$path_dokumen        = $data_path['path_dokumen'];
		$path_temporary      = $data_path['path_temporary'];
		$temp_filename       = $data_path['temp_filename'];             
		
		if (!is_dir($path_dokumen)){mkdir($path_dokumen, 0777, TRUE);}

        $full_path_temp_filename = $path_temporary."/".$temp_filename;


        $convtofile = new SplFileInfo($temp_filename);
        $extenstion = ".".$convtofile->getExtension();

        $new_filename = $data_path['pasien_id'].'_'.$data_path['dokumen_id'].'_'.$data_path['nama_dokumen'].'_'.date('ymdHi').'_'.$data_path['index'].$extenstion;
		$ori_file    = $data_path['no_pasien'].'/dokumen/rekam_medis/'.$new_filename;

	    copy($data_path['path_temp'].$temp_filename, $_SERVER['DOCUMENT_ROOT'] .config_item('site_img_pasien_temp_dir_copy').$ori_file);
	
		
		echo json_encode($ori_file);
	}



	public function insert_user_level()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$user_level_id = 0;
		$apisign_param = $this->input->post();
		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}

		if(isset($apisign_param['user_level_id'])) {
			$user_level_id = $apisign_param['user_level_id'];
			$user_level_id = str_replace('"', '', $user_level_id);
			unset($apisign_param['user_level_id']);
		}

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_user_level = $apisign_param;

		$persetujuan = unserialize($data_user_level['persetujuan']);
		unset($data_user_level['persetujuan']);

	
		if(isset($data_user_level['apikey'])) {
			unset($data_user_level['apikey']);
		}

		
		$user_levels = $this->user_level_m->get_by_id($user_level_id)->row(0);
		if(count($user_levels) == 0) // jika data user_level dengan id tsb belum ada maka insert
		{
			$id = $this->user_level_m->save($data_user_level);	
			foreach ($persetujuan as $user_level) 
	        {                    
	            if ($user_level['id'] != "") 
	            {
	                $data_persetujuan = array(
	                    'user_level_id'            => $id,
	                    'user_level_menyetujui_id' => $user_level['id'],
	                    'level_order'              => $user_level['order'],
	                    'is_active'                => '1',
	                );
	                $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan); 
	                //die_dump($this->db->last_query());
	            }                
	        }				
		}
		else //jika data user_level dengan id tsb sudah ada maka edit
		{
			$id = $this->user_level_m->save($data_user_level,$user_level_id);	
			foreach ($persetujuan as $user_level) 
	        {                    
	            if ($user_level['id'] != "") 
	            {
	            	$user_level_persetujuan = $this->user_level_persetujuan_m->get_by(array('user_level_id' => $user_level_id, 'user_level_menyetujui_id' => $user_level['id'], 'is_active' => 1));
	            	if(count($user_level_persetujuan) == 0)
	            	{
		                $data_persetujuan = array(
		                    'user_level_id'            => $user_level_id,
		                    'user_level_menyetujui_id' => $user_level['id'],
		                    'level_order'              => $user_level['order'],
		                    'is_active'                => '1',
		                );
		                $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan); 	            		
	            	}
	            	else
	            	{
	            		foreach ($user_level_persetujuan as $row) 
	            		{
		            		$data_persetujuan = array(
			                    'level_order'              => $user_level['order'],
			                );
			                $save_user_level_menyetujui = $this->user_level_persetujuan_m->save($data_persetujuan, $row->id); 
	            		}
	            	}
	                //die_dump($this->db->last_query());
	            }                
	        }			
		}


		echo json_encode($id);
	
		
	}

	public function insert_pendaftaran_tindakan()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$pendaftaran_id = 0;
		$user_login_id = 0;
		$apisign_param = $this->input->post();

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}
		if(isset($apisign_param['user_login_id'])) {
			$user_login_id = $apisign_param['user_login_id'];
			unset($apisign_param['user_login_id']);
		}

		if(isset($apisign_param['pendaftaran_id'])) {
			$pendaftaran_id = $apisign_param['pendaftaran_id'];
			$pendaftaran_id = str_replace('"', '', $pendaftaran_id);
			unset($apisign_param['pendaftaran_id']);
		}

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_pendaftaran = $apisign_param;
		if(isset($data_pendaftaran['apikey'])) {
			unset($data_pendaftaran['apikey']);
		}

		$pendaftaran = $this->pendaftaran_tindakan_m->get_data_by_id($pendaftaran_id)->row(0);
		
		if(count($pendaftaran) == 0)
		{
			$id = $this->pendaftaran_tindakan_m->save_api($user_login_id,$data_pendaftaran);				
		}
		else
		{
			$id = $this->pendaftaran_tindakan_m->save_api($user_login_id,$data_pendaftaran, $pendaftaran_id);	
		}	

		echo json_encode($id);
	}

	public function sent_notification($action_id,$param_text, $param_id=null)
	{
		$this->load->model('master/cabang_m');

		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$notif_setting = $this->notifikasi_setting_m->get_by(array('notifikasi_daftar_aksi_id' => $action_id ));

		foreach ($notif_setting as $row_setting) 
		{
			$cabang = $this->cabang_m->get($row_setting->cabang_id);

			$url = $row_setting->notifikasi_url;
			if($row_setting->is_add_parameter == 1)
			{
				$url = $row_setting->notifikasi_url.$param_id;
			}

			$param_text = str_replace('_', ' ', $param_text);

			$text = str_replace("*parameter_text", $param_text, $row_setting->notifikasi_text);

			$max_id = $this->notifikasi_m->max();

	        if ($max_id->max_id==null){
	            $id = 1;
	        } else {
	            $id = $max_id->max_id+1;
	        }

	        $data_notif = array(
				'notifikasi_id'         => $id,
				'user_level_id'         => $row_setting->notifikasi_user_level_id,
				'user_id'               => $row_setting->notifikasi_user_id,
				'notifikasi_text'       => $text,
				'notifikasi_param_text' => $param_text,
				'notifikasi_url'        => $cabang->url.$url,
				'notifikasi_parameter'  => $param_id,
				'created_date'           => date('Y-m-d H:i:s')
			);

			$hasil = $this->notifikasi_m->simpan($data_notif);

			if($row_setting->is_email == 1)
			{
				$url_encoded = urlencode(base64_encode($cabang->url.$url));
				$this->load->library('email');
		        $config = Array(
		          'protocol'  => config_item('email_protocol'),
		          'smtp_host' => config_item('email_smtp_host'),
		          'smtp_port' => config_item('email_smtp_port'),
		          'smtp_user' => config_item('email_smtp_user'), // change it to yours
		          'smtp_pass' => config_item('email_smtp_pass'), // change it to yours
		        );

		        $this->email->initialize($config);
		        $this->email->set_newline("\r\n");  
		        $this->email->from(config_item('email_smtp_user'));
		        $this->email->to($row_setting->email_address);                      
                $this->email->subject($text);
                $this->email->message($text."\nSilahkan buka link dibawah ini untuk membuka page.\n".base_url().'home/login_site/'.$url_encoded);

                $this->email->send();
			}

			if($hasil) echo json_encode(true);


		}

	}

	public function sent_notification_po($tipe_po,$param_text, $urutan)
	{

        $this->load->model('pembelian/setting_persetujuan_po_m');
        $this->load->model('master/cabang_m');

		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$notif_setting = $this->setting_persetujuan_po_m->get_by(array('tipe_po' => $tipe_po, 'level_order' => $urutan, 'is_active' => 1));
		//$notif_setting = object_to_array($notif_setting);

		foreach ($notif_setting as $row_setting) 
		{
			$cabang = $this->cabang_m->get($row_setting->cabang_id);

			$url = $row_setting->notifikasi_url;

			$param_text = str_replace('_', ' ', $param_text);

			$text = str_replace("*parameter_text", $param_text, $row_setting->notifikasi_text);

			$url_encoded = urlencode(base64_encode($cabang->url.$url));
			$this->load->library('email');
	        $config = array(
	          'protocol'  => config_item('email_protocol'),
	          'smtp_host' => config_item('email_smtp_host'),
	          'smtp_port' => config_item('email_smtp_port'),
	          'smtp_user' => config_item('email_smtp_user'), // change it to yours
	          'smtp_pass' => config_item('email_smtp_pass'), // change it to yours
	        );

	        $this->email->initialize($config);
	        $this->email->set_newline("\r\n");  
	        $this->email->from(config_item('email_smtp_user'));
	        $this->email->to($row_setting->email_notif);                      
            $this->email->subject($text);
            $this->email->message($text."\nSilahkan buka link dibawah ini untuk membuka page.\n".base_url().'home/login_site/'.$url_encoded);

            $this->email->send();
			

			echo json_encode(true);
		}

	}

	public function change_file_content($filename)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		
		$file  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').$filename.'.txt';
        $date = getDate();
        $jam = mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$date['mday'],$date['year']);
        file_put_contents($file,$jam);

	}

	public function get_notif($user_id,$level_id)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$response = new stdClass;
        $response->success = false;
        $response->count = false;

        $notif = $this->notifikasi_m->get_notif($user_id,$level_id);
      

        if (count($notif)){
            $response->success = true;
            $response->count = count($notif);
            $response->rows = $notif;
            $response->title = translate('You have ',$this->session->userdata('language')).' '.count($notif).' '.translate('notification(s)',$this->session->userdata('language'));
        }
        else
        {
        	$response->success = false;
        }

        echo json_encode($response);
	}

	public function delete_notif($notif_id,$user_id)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$response = new stdClass;
        $response->success = false;

        $delete_notif_id = false;

		$notif = $this->notifikasi_m->get_by(array('notifikasi_id' => $notif_id));
		
		foreach ($notif as $notif) 
		{
    		$data = array(
				'user_level_id'         => $notif->user_level_id,
				'user_id'               => $notif->user_id,
				'notifikasi_text'       => $notif->notifikasi_text,
				'notifikasi_text_param' => $notif->notifikasi_param_text,
				'notifikasi_url'        => $notif->notifikasi_url,
				'notifikasi_parameter'  => $notif->notifikasi_parameter,
				'created_date'          => $notif->created_date,
				'read_by'               => $user_id,
				'read_date'             => date('Y-m-d H:i:s')
    		);

    		$notif_history = $this->notifikasi_history_m->save($data);
		}
		
		

		if($notif_history)
		{
			$delete_notif_id = $this->notifikasi_m->delete_this($notif_id);			
		}

		if($delete_notif_id === true)
		{
			$response->success = true;

		}

		echo json_encode($response);
	}


	public function get_user_level_menu($level_id,$parent_id=NULL)
	{

		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$wheres = array(
			'user_level_id' => $level_id
		);
		if($parent_id != NULL)
		{
			$wheres['parent_id'] = $parent_id;
		}


		$menu_user_level = $this->user_level_menu_m->get_by($wheres);

		echo json_encode($menu_user_level);

	}

	public function get_user_level_menu_order($level_id,$parent_id)
	{

		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$menu_user_level = $this->user_level_menu_m->get_by_parent_order($level_id,$parent_id);

		echo json_encode($menu_user_level);

	}

	public function get_parent_menu($level_id)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$menu_user_level = $this->user_level_menu_m->get_by("(id in (select parent_id from user_level_menu) and parent_id <> 0 and user_level_id = $level_id ) OR (parent_id = 0 and user_level_id = $level_id)");

		echo json_encode($menu_user_level);
	}

	public function delete_menu_parent($id)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$menu = $this->user_level_menu_m->get_by(array('id'=>$id), true);

		$user_level_menu = $this->user_level_menu_m->get_order_max($menu->parent_id,$menu->user_level_id,$menu->m_order)->result_array();

	
		if(count($user_level_menu))
		{
			foreach ($user_level_menu as $menu) 
			{
				$data_save = array(
					'm_order' => ($menu['m_order'] - 1)
				);
				$this->user_level_menu_m->edit_data($data_save, $menu['id']);
			}
		}

		$this->user_level_menu_m->delete_by_parent($id);
		$menu_user_level = $this->user_level_menu_m->delete($id);

		echo json_encode(true);

	}

	public function insert_user_level_menu()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		
		$apisign_param = $this->input->post();
		

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}
		

		$apisign_param['apikey'] = $this->http_header['X-const-id'];

		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_menu = $apisign_param;

		if(isset($data_menu['apikey'])) {
			unset($data_menu['apikey']);
		}

		$menu_parent = unserialize($data_menu['menu_parent']);
		$parent_id = $data_menu['parent_id'];

		foreach ($menu_parent as $parent) 
        {
            if($parent['nama'] != '')
            {
            	if($parent['id'] == '')
            	{
	                $menu_parent = $this->user_level_menu_m->get_by_level_parent_id($data_menu['user_level_id'],$parent_id)->result_array();
	                $menu_id = $this->user_level_menu_m->get_max_id()->result_array();

	                if($menu_id[0]['max_id'] == NULL)
	                {
	                    $id = 1;
	                }
	                else
	                {
	                    $id = $menu_id[0]['max_id'] + 1;
	                }
	    
	                $data_parent = array(
	                    'id'            => $id,
	                    'parent_id'     => $parent_id,
	                    'user_level_id' => $data_menu['user_level_id'],
	                    'nama'          => $parent['nama'],
	                    'cabang_id'      => ($parent['cabang_id'] != '')?$parent['cabang_id']:NULL,
	                    'url'           => ($parent['url'] != '')?$parent['url']:NULL,
	                    'icon_class'    => $parent['icon_class']
	                );

	                $base_url = base_url();
	                if($parent['base_url'] !== NULL || $parent['base_url'] !== '')
	                {
	                    $base_url = $parent['base_url'];
	                }
	                $full_url = $parent['url'];

	                $hash = md5($full_url);

	                $data_parent['unik_id'] = $hash;
	                $data_parent['m_order'] = count($menu_parent)+1;

	                $save_menu = $this->user_level_menu_m->add_data($data_parent);            		
            	}
            	else
            	{
            		$data_parent = array(
	                    'parent_id'     => $parent_id,
	                    'user_level_id' => $data_menu['user_level_id'],
	                    'nama'          => $parent['nama'],
	                    'cabang_id'      => ($parent['cabang_id'] != '')?$parent['cabang_id']:NULL,
	                    'url'           => ($parent['url'] != '')?$parent['url']:NULL,
	                    'icon_class'    => $parent['icon_class']
	                );

	                $base_url = base_url();
	                if($parent['base_url'] !== NULL || $parent['base_url'] !== '')
	                {
	                    $base_url = $parent['base_url'];
	                }
	                $full_url = $data_parent['url'];

	                $hash = md5($full_url);

	                $data_parent['unik_id'] = $hash;

	                $save_menu = $this->user_level_menu_m->edit_data($data_parent, $parent['id']);  
            	}

            }
        }

        echo json_encode(true);
	}

	public function edit_user_level_menu()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		
		$apisign_param = $this->input->post();
		

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}
		

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_menu = $apisign_param;

		if(isset($data_menu['apikey'])) {
			unset($data_menu['apikey']);
		}

		$id             = $data_menu['id'];
		$user_level_id  = $data_menu['user_level_id'];
		$parent_id      = $data_menu['parent_id'];
		$parent_id_awal = $data_menu['parent_id_awal'];
		$m_order        = $data_menu['m_order'];

		$user_level_menu = $this->user_level_menu_m->get_order_max($parent_id_awal,$user_level_id,$m_order)->result_array();
		$menu_parent = $this->user_level_menu_m->get_by_level_parent_id($user_level_id,$parent_id)->result_array();
	
		if(count($user_level_menu))
		{
			foreach ($user_level_menu as $menu) 
			{
				$data_save = array(
					'm_order' => ($menu['m_order'] - 1)
				);
				$this->user_level_menu_m->edit_data($data_save, $menu['id']);
			}
		}

		$data_edit['parent_id'] = $parent_id;
		$data_edit['m_order'] = count($menu_parent)+1;
		$edit = $this->user_level_menu_m->edit_data($data_edit,$id);

        echo json_encode(true);
	}

	public function get_fitur()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$fitur = $this->fitur_m->get();

		echo json_encode($fitur);

	}

	public function up_order($parent_id,$id,$order,$level_id)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$up = $this->user_level_menu_m->up_order($parent_id,$id,$order,$level_id);

		echo json_encode($up);

	}

	public function down_order($parent_id,$id,$order,$level_id)
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$down = $this->user_level_menu_m->down_order($parent_id,$id,$order,$level_id);

		echo json_encode($down);

	}

	public function get_jadwal_cabang($cabang_id,$date)
	{
		$this->load->model('klinik_hd/jadwal_m');

		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		// echo json_encode($this->db->last_query());
		// exit;
		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$result=$this->jadwal_m->check_jadwal2($cabang_id,$date)->result_array();

		echo json_encode($result);
	}

	public function get_frequensi_tindakan($startdate,$enddate,$id)
	{
		$this->load->model('master/transaksi_dokter_m');

		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$result=$this->transaksi_dokter_m->getdatatindakanfrekuensi($startdate,$enddate,$id)->result_array();

		echo json_encode($result);
	}

	public function insert_faskes_temp()
	{
		$this->load->model('master/faskes_temp_m');

		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$faskes_temp_id = 0;
		$user_login_id = 0;
		$apisign_param = $this->input->post();

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}

		if(isset($apisign_param['faskes_temp_id'])) {
			$faskes_temp_id = $apisign_param['faskes_temp_id'];
			$faskes_temp_id = str_replace('"', '', $faskes_temp_id);
			unset($apisign_param['faskes_temp_id']);
		}
		if(isset($apisign_param['created_by'])) {
			$user_login_id = $apisign_param['created_by'];
			unset($apisign_param['created_by']);
		}

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_faskes_temp = $apisign_param;
		if(isset($data_faskes_temp['apikey'])) {
			unset($data_faskes_temp['apikey']);
		}

		$faskes_temp = $this->faskes_temp_m->get_data_by_id($faskes_temp_id);
		if(count($faskes_temp) == 0)
		{
			$id = $this->faskes_temp_m->save_api($user_login_id,$data_faskes_temp);				
		}
		else
		{
			$id = $this->faskes_temp_m->save_api($user_login_id,$data_faskes_temp, $faskes_temp_id);	
		}	

		echo json_encode($id);
	}

	public function insert_pasien()
	{
		$this->load->model('master/pasien_m');
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$id_pasien = 0;
		$user_login_id = 0;		
		$apisign_param = $this->input->post();

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}

		if(isset($apisign_param['id_pasien'])) {
			$id_pasien = $apisign_param['id_pasien'];
			$id_pasien = str_replace('"', '', $id_pasien);
			unset($apisign_param['id_pasien']);
		}
		if(isset($apisign_param['created_by'])) {
			$user_login_id = $apisign_param['created_by'];
			unset($apisign_param['created_by']);
		}

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_pasien = $apisign_param;
		if(isset($data_pasien['apikey'])) {
			unset($data_pasien['apikey']);
		}

		$pasien = $this->pasien_m->get_data_by_id($id_pasien);
		if(count($pasien) == 0)
		{
			$id = $this->pasien_m->save_api($user_login_id,$data_pasien);				
		}
		else
		{
			$id = $this->pasien_m->save_api($user_login_id,$data_pasien, $id_pasien);	
		}	

		echo json_encode($id);
	}

	public function insert_pasien_telepon()
	{
		$this->load->model('master/pasien_telepon_m');
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$pasien_tlp_id = 0;
		$user_login_id = 0;		
		$apisign_param = $this->input->post();

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}

		if(isset($apisign_param['pasien_tlp_id'])) {
			$pasien_tlp_id = $apisign_param['pasien_tlp_id'];
			$pasien_tlp_id = str_replace('"', '', $pasien_tlp_id);
			unset($apisign_param['pasien_tlp_id']);
		}
		if(isset($apisign_param['created_by'])) {
			$user_login_id = $apisign_param['created_by'];
			unset($apisign_param['created_by']);
		}

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_pasien_telepon = $apisign_param;
		if(isset($data_pasien_telepon['apikey'])) {
			unset($data_pasien_telepon['apikey']);
		}

		$pasien = $this->pasien_telepon_m->get_data_by_id($pasien_tlp_id);
		if(count($pasien) == 0)
		{
			$id = $this->pasien_telepon_m->save_api($user_login_id,$data_pasien_telepon);				
		}
		else
		{
			$id = $this->pasien_telepon_m->save_api($user_login_id,$data_pasien_telepon, $pasien_tlp_id);	
		}	

		echo json_encode($id);
	}

	public function insert_pasien_alamat()
	{
		$this->load->model('master/pasien_alamat_m');
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$pasien_alamat_id = 0;
		$user_login_id = 0;		
		$apisign_param = $this->input->post();

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}

		if(isset($apisign_param['pasien_alamat_id'])) {
			$pasien_alamat_id = $apisign_param['pasien_alamat_id'];
			$pasien_alamat_id = str_replace('"', '', $pasien_alamat_id);
			unset($apisign_param['pasien_alamat_id']);
		}
		if(isset($apisign_param['created_by'])) {
			$user_login_id = $apisign_param['created_by'];
			unset($apisign_param['created_by']);
		}

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_pasien_telepon = $apisign_param;
		if(isset($data_pasien_telepon['apikey'])) {
			unset($data_pasien_telepon['apikey']);
		}

		$pasien = $this->pasien_alamat_m->get_data_by_id($pasien_alamat_id);
		if(count($pasien) == 0)
		{
			$id = $this->pasien_alamat_m->save_api($user_login_id,$data_pasien_telepon);				
		}
		else
		{
			$id = $this->pasien_alamat_m->save_api($user_login_id,$data_pasien_telepon, $pasien_alamat_id);	
		}	

		echo json_encode($id);
	}

	public function insert_pasien_hubungan()
	{
		$this->load->model('master/pasien_hubungan_m');
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$id_index = 0;
		$user_login_id = 0;		
		$apisign_param = $this->input->post();

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}

		if(isset($apisign_param['id_index'])) {
			$id_index = $apisign_param['id_index'];
			$id_index = str_replace('"', '', $id_index);
			unset($apisign_param['id_index']);
		}
		if(isset($apisign_param['created_by'])) {
			$user_login_id = $apisign_param['created_by'];
			unset($apisign_param['created_by']);
		}

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_pasien_telepon = $apisign_param;
		if(isset($data_pasien_telepon['apikey'])) {
			unset($data_pasien_telepon['apikey']);
		}

		$pasien = $this->pasien_hubungan_m->get_data_by_id($id_index);
		if(count($pasien) == 0)
		{
			$id = $this->pasien_hubungan_m->save_api($user_login_id,$data_pasien_telepon);				
		}
		else
		{
			$id = $this->pasien_hubungan_m->save_api($user_login_id,$data_pasien_telepon, $id_index);	
		}	

		echo json_encode($id);
	}

	public function insert_pasien_hub_tlp_alm()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$id_index = 0;
		$user_login_id = 0;	
		$model = '';	
		$apisign_param = $this->input->post();

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}

		if(isset($apisign_param['id_index'])) {
			$id_index = $apisign_param['id_index'];
			$id_index = str_replace('"', '', $id_index);
			unset($apisign_param['id_index']);
		}
		if(isset($apisign_param['created_by'])) {
			$user_login_id = $apisign_param['created_by'];
			unset($apisign_param['created_by']);
		}

		if(isset($apisign_param['model'])) {
			$model = $apisign_param['model'];
			unset($apisign_param['model']);
		}

		$this->load->model('master/'.$model);

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_pasien_telepon = $apisign_param;
		if(isset($data_pasien_telepon['apikey'])) {
			unset($data_pasien_telepon['apikey']);
		}

		$pasien = $this->$model->get_data_by_id($id_index);
		if(count($pasien) == 0)
		{
			$id = $this->$model->save_api($user_login_id,$data_pasien_telepon);				
		}
		else
		{
			$id = $this->$model->save_api($user_login_id,$data_pasien_telepon, $id_index);	
		}	

		echo json_encode($id);
	}

	public function insert_data_api()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$id_index = 0;
		$user_login_id = 0;	
		$model = '';	
		$path_model = '';	
		$apisign_param = $this->input->post();

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}

		if(isset($apisign_param['id_index'])) {
			$id_index = $apisign_param['id_index'];
			$id_index = str_replace('"', '', $id_index);
			unset($apisign_param['id_index']);
		}
		if(isset($apisign_param['created_by'])) {
			$user_login_id = $apisign_param['created_by'];
			unset($apisign_param['created_by']);
		}

		if(isset($apisign_param['model'])) {
			$model = $apisign_param['model'];
			unset($apisign_param['model']);
		}

		if(isset($apisign_param['path_model'])) {
			$path_model = $apisign_param['path_model'];
			unset($apisign_param['path_model']);
		}

		$this->load->model($path_model);

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_pasien_telepon = $apisign_param;
		if(isset($data_pasien_telepon['apikey'])) {
			unset($data_pasien_telepon['apikey']);
		}if(isset($data_pasien_telepon['index'])) {
			$data_pasien_telepon['`index`'] = $data_pasien_telepon['index'];
			unset($data_pasien_telepon['index']);
		}

		$pasien = $this->$model->get_data_by_id($id_index);
		
		if(count($pasien) == 0)
		{
			$id = $this->$model->save_api($user_login_id,$data_pasien_telepon);	
		}
		else
		{
			$id = $this->$model->save_api($user_login_id,$data_pasien_telepon, $id_index);	
		}	

		echo json_encode($id);
	}

	public function insert_data_api_id()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$id_index = 0;
		$user_login_id = 0;	
		$model = '';	
		$path_model = '';	
		$apisign_param = $this->input->post();

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}

		if(isset($apisign_param['id_index'])) {
			$id_index = $apisign_param['id_index'];
			$id_index = str_replace('"', '', $id_index);
			unset($apisign_param['id_index']);
		}

		if(isset($apisign_param['model'])) {
			$model = $apisign_param['model'];
			unset($apisign_param['model']);
		}

		if(isset($apisign_param['path_model'])) {
			$path_model = $apisign_param['path_model'];
			unset($apisign_param['path_model']);
		}

		$this->load->model($path_model);

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_pasien_telepon = $apisign_param;
		if(isset($data_pasien_telepon['apikey'])) {
			unset($data_pasien_telepon['apikey']);
		}

		if($id_index != '0') {
			$id = $this->$model->edit_data($data_pasien_telepon, $id_index);

		}else{
			$id = $this->$model->add_data($data_pasien_telepon);	
			
		}	
			

		echo json_encode($id);
	}

	public function update_data_api()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$apisign_sender = $this->input->post('apisign');
		$id_index = array();
		$user_login_id = 0;	
		$model = '';	
		$path_model = '';	
		$apisign_param = $this->input->post();

		if(isset($apisign_param['apisign'])) {
			unset($apisign_param['apisign']);
		}

		if(isset($apisign_param['id_index'])) {
			$id_index = unserialize($apisign_param['id_index']);
			unset($apisign_param['id_index']);
		}
		if(isset($apisign_param['created_by'])) {
			$user_login_id = $apisign_param['created_by'];
			unset($apisign_param['created_by']);
		}

		if(isset($apisign_param['model'])) {
			$model = $apisign_param['model'];
			unset($apisign_param['model']);
		}

		if(isset($apisign_param['path_model'])) {
			$path_model = $apisign_param['path_model'];
			unset($apisign_param['path_model']);
		}

		$this->load->model($path_model);

		$apisign_param['apikey'] = $this->http_header['X-const-id'];
		
		$apisign_checker = generate_api_signature_server($apisign_param);
		if($apisign_checker != $apisign_sender) {
			echo deliver_response(400, "fail", "$apisign_sender, $apisign_checker, Api Signature Is Incorrect");
			exit;
		}

		$data_pasien_telepon = $apisign_param;
		if(isset($data_pasien_telepon['apikey'])) {
			unset($data_pasien_telepon['apikey']);
		}

		$id = $this->$model->update_by($user_login_id,$data_pasien_telepon, $id_index);	
		

		echo json_encode($id);
	}

	public function get_data_sejarah_assesment($transaksi_id,$pasien_id)
	{
		$this->load->model('klinik_hd/tindakan_hd_penaksiran_m');
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$penaksiran = $this->tindakan_hd_penaksiran_m->get_by_transaction_id($transaksi_id,$pasien_id)->result_array();
		$problem = $this->tindakan_hd_penaksiran_m->getproblem($transaksi_id,$pasien_id)->result_array();
		$komplikasi = $this->tindakan_hd_penaksiran_m->getkomplikasi($transaksi_id,$pasien_id)->result_array();

		$data[0] = $penaksiran;
		$data[1] = $problem;
		$data[2] = $komplikasi;
		
		echo json_encode($data);

	}

	public function get_data_api()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		$wheres = array();
		$model = '';	
		$path_model = '';	
		$apisign_param = $this->input->post();


		if(isset($apisign_param['wheres'])) {
			$wheres = unserialize($apisign_param['wheres']);
			unset($apisign_param['wheres']);
		}
		

		if(isset($apisign_param['model'])) {
			$model = $apisign_param['model'];
			unset($apisign_param['model']);
		}

		if(isset($apisign_param['path_model'])) {
			$path_model = $apisign_param['path_model'];
			unset($apisign_param['path_model']);
		}

		$this->load->model($path_model);

		$data = $this->$model->get_by($wheres);		

		echo json_encode($data);
	}

	public function get_data_api_item()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		$wheres = array();
		$model = '';	
		$path_model = '';	
		$apisign_param = $this->input->post();


		if(isset($apisign_param['wheres'])) {
			$wheres = unserialize($apisign_param['wheres']);
			unset($apisign_param['wheres']);
		}
		

		if(isset($apisign_param['model'])) {
			$model = $apisign_param['model'];
			unset($apisign_param['model']);
		}

		if(isset($apisign_param['path_model'])) {
			$path_model = $apisign_param['path_model'];
			unset($apisign_param['path_model']);
		}

		$this->load->model($path_model);

		$data = $this->$model->get_item_invoice($wheres)->result_array();		

		echo json_encode($data);
	}

	public function get_jumlah_tindakan_minggu($pasien_id,$penjamin_id)
	{
		$this->load->model('klinik_hd/tindakan_hd_m');
		header("Content-Type: application/json");

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$data = $this->tindakan_hd_m->get_tindakan_minggu($pasien_id,$penjamin_id);
				
		echo json_encode($data);
	}

	public function get_max_saldo_invoice()
	{
		$this->load->model('reservasi/kasir_arus_kas/kasir_arus_kas_m');

		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		// echo json_encode($this->db->last_query());
		// exit;
		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$result=$this->kasir_arus_kas_m->get_max_saldo_invoice();

		echo json_encode($result);
	}

	public function get_saldo($tanggal)
	{
		$this->load->model('keuangan/kasir_arus_kas_m');
		header("Content-Type: application/json");

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$response = new stdClass;

		$response->before = $this->kasir_arus_kas_m->get_saldo_before($tanggal,1)->result_array();
		$response->after = $this->kasir_arus_kas_m->get_after_after($tanggal,1)->result_array();
				
		echo json_encode($response);
	}

	public function get_saldo_bank($tanggal,$bank_id)
	{
		$this->load->model('keuangan/arus_kas_bank/keuangan_arus_kas_m');
		header("Content-Type: application/json");

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$response = new stdClass;

		$response->before = $this->keuangan_arus_kas_m->get_saldo_before($tanggal,$bank_id)->result_array();
		$response->after = $this->keuangan_arus_kas_m->get_after_after($tanggal,$bank_id)->result_array();
				
		echo json_encode($response);
	}

	public function get_data_pembelian($permintaan_id,$tipe)
	{
		$this->load->model('apotik/pembelian/pembelian_m');
		header("Content-Type: application/json");

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$data = $this->pembelian_m->get_by(array('id_permintaan' => $permintaan_id, 'tipe_pembelian' => $tipe), true);
				
		echo json_encode($data);
	}

	public function get_tipe_bayar_supplier($supplier_id)
	{
		$this->load->model('master/supplier/supplier_tipe_pembayaran_m');
		header("Content-Type: application/json");

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$data = $this->supplier_tipe_pembayaran_m->get_by(array('supplier_id' => $supplier_id, 'is_active' => 1), true);
				
		echo json_encode($data);
	}

	public function get_max_id_pembelian()
	{
		$this->load->model('apotik/pembelian/pembelian_m');
		header("Content-Type: application/json");

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$response = new stdClass;

		$last_id       = $this->pembelian_m->get_max_id_pembelian()->result_array();
        $last_id       = intval($last_id[0]['max_id'])+1;
        $format_id     = 'PO-'.date('m').'-'.date('Y').'-%04d';
        $id_po         = sprintf($format_id, $last_id, 4);
        
        
        $last_number   = $this->pembelian_m->get_no_pembelian()->result_array();
        $last_number   = intval($last_number[0]['max_no_pembelian'])+1;
        $format        = '#PO#%03d/RHS-RI/'.date('Y');
        $no_po     	   = sprintf($format, $last_number, 3);

        $response->id_po = $id_po;
        $response->no_po = $no_po;
				
		echo json_encode($response);
	}

	public function get_max_id_pembelian_detail()
	{
		$this->load->model('apotik/pembelian/pembelian_detail_m');
		header("Content-Type: application/json");

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$response = new stdClass;

		$last_id      = $this->pembelian_detail_m->get_max_id_detail_pembelian()->result_array();
		$last_id      = intval($last_id[0]['max_id'])+1;
		$format_id    = 'POD-'.date('m').'-'.date('Y').'-%04d';
		$id_po_detail = sprintf($format_id, $last_id, 4);

        $response->id_po_detail = $id_po_detail;
				
		echo json_encode($response);
	}

	public function get_saldo_external($tanggal)
	{
		$this->load->model('keuangan/external_arus_kas_m');
		header("Content-Type: application/json");

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$response = new stdClass;

		$response->before = $this->external_arus_kas_m->get_saldo_before($tanggal)->result_array();
		$response->after = $this->external_arus_kas_m->get_after_after($tanggal)->result_array();
				
		echo json_encode($response);
	}	

	public function delete_data_api()
	{
		header("Content-Type: application/json");
		// $this->output->set_content_type("application/json");

		if(strtoupper($this->request_method) != "POST") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		if(!$this->input->post()) {
			echo deliver_response(405, "fail", "Incomplete Parameter");
			exit;
		}

		// Because this is write request, check apisign parameter
		// debug_var($this->input->post(), 1);
		$id_index = 0;
		$user_login_id = 0;	
		$model = '';	
		$path_model = '';	
		$apisign_param = $this->input->post();


		if(isset($apisign_param['model'])) {
			$model = $apisign_param['model'];
			unset($apisign_param['model']);
		}

		if(isset($apisign_param['path_model'])) {
			$path_model = $apisign_param['path_model'];
			unset($apisign_param['path_model']);
		}

		$this->load->model($path_model);

		$data_delete = $apisign_param;

		$id = $this->$model->delete_by($data_delete);		
			

		echo json_encode($id);
	}

	public function get_data_pasien_tindakan($shift,$cabang_id)
	{
		$this->load->model('klinik_hd/jadwal_m');
		header("Content-Type: application/json");

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$response = new stdClass;

		$response->jadwal = $this->jadwal_m->get_pasien_tidak_hadir($shift,$cabang_id)->result_array();
		$response->jadwal_real = $this->jadwal_m->get_pasien_tidak_hadir_real($shift,$cabang_id)->result_array();
		$response->jadwal_all = $this->jadwal_m->get_pasien_jadwal($shift,$cabang_id)->result_array();

				
		die(json_encode($response));
	}

	public function get_data_pasien_non_tindakan($shift,$cabang_id)
	{
		$this->load->model('klinik_hd/tindakan_hd_m');
		header("Content-Type: application/json");

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$response = new stdClass;

		$response->tindakan = $this->tindakan_hd_m->get_pasien_non_jadwal($shift,$cabang_id)->result_array();

				
		die(json_encode($response));
	}

	public function get_data_jadwal($id,$cabang_id)
	{
		$this->load->model('klinik_hd/jadwal_m');
		header("Content-Type: application/json");

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$response = new stdClass;

		$response->jadwal = $this->jadwal_m->get_by(array('id' => $id, 'cabang_id' => $cabang_id), true);

				
		die(json_encode($response));
	}

	public function get_data_invoice($tindakan_id, $tipe, $penjamin_id)
	{
		$this->load->model('reservasi/invoice/invoice_m');
		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$data = $this->invoice_m->get_by(array('tindakan_id' => $tindakan_id, 'tipe' => $tipe, 'penjamin_id' => $penjamin_id));

		echo json_encode($data);

	}
	public function get_max_no_invoice()
	{
		$this->load->model('reservasi/invoice/invoice_m');

		header("Content-Type: application/json");
		// $this->output->set_content_type('application/json');

		if(strtoupper($this->request_method) != "GET") {
			echo deliver_response(405, "fail", "Method Not Allowed");
			exit;
		}

		$last_number_invoice  = $this->invoice_m->get_nomor_invoice()->result_array();
        if($last_number_invoice[0]['max_nomor_invoice'] != NULL)
        {
            $last_number_invoice  = intval($last_number_invoice[0]['max_nomor_invoice'])+1;
        }
        else
        {
            $last_number_invoice = intval(1);
        }

        $format_invoice = date('Ymd').' - '.'%06d';
        $no_invoice    = sprintf($format_invoice, $last_number_invoice, 6);

		echo json_encode($no_invoice);

	}
}