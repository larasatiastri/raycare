<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	protected $menu_id = '4af53100a39d6113eeab916f28c75a16';

	private $menus = array();
	private $menu_tree = array();

	public function __construct()
	{		
		parent::__construct();

	}

	public function index()
	{
		redirect('home/dashboard');
		
	}

	public function dashboard()
	{
        $user_level = $this->user_m->level_id();
        $this->menus = $this->user_level_menu_m->get_nested($user_level);
	
		$assets = array();
        $config = 'assets/home/dashboard';
        $this->config->load($config, true);		

        $assets = $this->config->item('assets', $config);
			
		$data = array(
			'title'          => config_item('site_name').' | '.translate('Index', $this->session->userdata('language')), 
			'header'         => translate('Index', $this->session->userdata('language')), 
			'header_info'    => config_item('site_name'), 
			'breadcrumb'     => true,
			'menus'          => $this->menus,
			'menu_tree'      => $this->menu_tree,
			'css_files'      => $assets['css'],
			'js_files'       => $assets['js'],
			);
		
		// Load the view
		$this->load->view('_layout', $data);
	}

	

	public function login()
	{			
		$array_input = $this->input->post();
		// Redirect a user if already logged in
		// $dashboard = 'home/dashboard';
		if(isset($array_input['url_encod']))
		{
			$dashboard = base64_decode(urldecode($array_input['url_encod']));	
		}
		else
		{
		    $dashboard = $this->session->userdata('dashboard_url');	
		}
		if ($this->user_m->logged_in())
		{
			$user_id = $this->session->userdata('user_id');
			$users = $this->user_m->get($user_id);
			$new_password = config_item('new_password_reset');

			if($users->password == $this->user_m->hash($new_password))
			{
				redirect('pengaturan/ubah_password_awal');
				// redirect('home/logout');
			}
			redirect($dashboard);	
		} 
	
		
		// Set form
		$rules = $this->user_m->rules_login;
		$this->form_validation->set_rules($rules);
		
		// Process form
		if ($this->form_validation->run() == TRUE) {
			// login and redirect

			$input = $this->user_m->array_from_post(array('username', 'password', 'url_encod'));
			// die(dump($input));
				
			if ($this->user_m->login($input) == TRUE) {

				$user_id = $this->session->userdata('user_id');
				$users = $this->user_m->get($user_id);
				$new_password = config_item('new_password_reset');

				if($users->password == $this->user_m->hash($new_password))
				{
					redirect('pengaturan/ubah_password_awal');
					// redirect('home/logout');
				}
				if(isset($array_input['url_encod']))
				{
					$url_decoded = base64_decode(urldecode($array_input['url_encod']));	
					redirect($url_decoded);
				}
				else
				{
				    redirect($this->session->userdata('dashboard_url'));					
				}

			}else {
				$flashdata = array(
					"username"	=> $input['username'], 
					"error"		=> "Wrong Username and/or Password"
				);
				$this->session->set_flashdata($flashdata);
				redirect('home/login');
			}
		}

		$assets = array();
        $config = 'assets/home/login';
        $this->config->load($config, true);		

        $assets = $this->config->item('assets', $config);

		// die_dump( $assets['css'] );
		$data = array(
			'title'          => config_item('site_name').' | '.translate('Login', $this->session->userdata('language')), 
			'header'         => translate('Login', $this->session->userdata('language')), 
			'header_info'    => config_item('site_name'), 
			'css_files'      => $assets['css'],
			'js_files'       => $assets['js'],
			);		

		// Load the view
		$this->load->view('home/login',  $data);
	}

	public function login_site($url_encoded = null)
	{		
		if($url_encoded != null)
		{
	        $url_decoded = base64_decode(urldecode($url_encoded));	
		    $dashboard = $url_decoded;			
		}
		else
		{
			$dashboard = $this->session->userdata('dashboard_url');
		}
		// Redirect a user if already logged in
		// $dashboard = 'home/dashboard';
		if ($this->user_m->logged_in())
		{
			$user_id = $this->session->userdata('user_id');
			$users = $this->user_m->get($user_id);
			$new_password = config_item('new_password_reset');

			if($users->password == $this->user_m->hash($new_password))
			{
				redirect('pengaturan/ubah_password_awal');
				// redirect('home/logout');
			}
			redirect($dashboard);	
		} 
	
		
		// Set form
		$rules = $this->user_m->rules_login;
		$this->form_validation->set_rules($rules);
		
		// Process form
		if ($this->form_validation->run() == TRUE) {
			// login and redirect
			$input = $this->user_m->array_from_post(array('username', 'password'));
				

			
			if ($this->user_m->login($input) == TRUE) {

				$user_id = $this->session->userdata('user_id');
				$users = $this->user_m->get($user_id);
				$new_password = config_item('new_password_reset');

				if($users->password == $this->user_m->hash($new_password))
				{
					redirect('pengaturan/ubah_password_awal');
					// redirect('home/logout');
				}
				redirect($this->session->userdata('dashboard_url'));

			}else {
				$flashdata = array(
					"username"	=> $input['username'], 
					"error"		=> "Wrong Username and/or Password"
				);
				$this->session->set_flashdata($flashdata);
				redirect('home/login_site/'.$url_encoded);
			}
		}

		$assets = array();
        $config = 'assets/home/login';
        $this->config->load($config, true);		

        $assets = $this->config->item('assets', $config);

		// die_dump( $assets['css'] );
		$data = array(
			'title'          => config_item('site_name').' | '.translate('Login', $this->session->userdata('language')), 
			'header'         => translate('Login', $this->session->userdata('language')), 
			'header_info'    => config_item('site_name'), 
			'css_files'      => $assets['css'],
			'js_files'       => $assets['js'],
			'url_encoded'    => $url_encoded
			);		

		// Load the view
		$this->load->view('home/login',  $data);
	}

	public function lock_screen()
	{			

		$data = array(
			'img'          => base_url().config_item('site_user_img_dir').$this->session->userdata('url'),
			'usermame'     => $this->session->userdata('username'),
			'nama_lengkap' => $this->session->userdata('nama_lengkap')
		);
		// Load the view
		$this->load->view('home/lock_screen',  $data);
	}

	public function login_ajax()
	{
		if($this->input->is_ajax_request()) 
		{
				
			$response = new stdClass;
			$response->success = false;
			$response->msg = 'belum masuk';

			$input = $this->input->post();
			// Set form
			$rules = $this->user_m->rules_login;
			$this->form_validation->set_rules($rules);
			
				$this->session->sess_destroy();
			// Process form
			if ($this->form_validation->run() == TRUE) {
				// login and redirect

				$input = $this->input->post();
				$this->user_m->login($input);
					
					$response->success = true;
					$response->msg = 'masuk valid';
					

				

				die(json_encode($response));
			}
		}
		else
		{
			redirect(base_url());
		}

	}

	public function logout()
	{
		$url_login = $this->session->userdata('url_login');
		$this->user_m->logout();
		redirect($url_login.'home/login');
	}

	public function check_session()
	{
		$this->session->sess_update();
		echo $this->session->sess_update();

	}

	public function commet()
    {
       	$file = urlencode(base64_encode($this->session->userdata('url_login')));
       	// die(dump($file));

        $filename  = $_SERVER['DOCUMENT_ROOT'].config_item('file_notif_location').$file.'.txt';
        // die(dump($filename));
        // infinite loop until the data file is not modified
        $lastmodif    = isset($_POST['timestamp']) ? $_POST['timestamp'] : 0;
        $currentmodif = filemtime($filename);
          // die(dump($lastmodif));
        while ($currentmodif <= $lastmodif) // check if the data file has been modified
        {
          usleep(10000); // sleep 10ms to unload the CPU
          clearstatcache();
          $currentmodif = filemtime($filename);
        }

        // return a json array
        $response = array();
        $response['msg']       = file_get_contents($filename);
        $response['timestamp'] = $currentmodif;
        echo json_encode($response);
        flush();
    }

    public function getSessionTimeLeft()
    {
	    // $ci = & get_instance();
	    $SessTimeLeft    = 0;
	    $SessExpTime     = $this->config->config["sess_expiration"];
	    $CurrTime        = time();
	    $lastActivity = $this->session->userdata['last_activity'];
	    $SessTimeLeft = ($SessExpTime - ($CurrTime - $lastActivity));
	    die(json_encode($SessTimeLeft));
	}
   

    public function get_notif()
    {
    	$user_id = $this->session->userdata('user_id');
    	$level_id = $this->session->userdata('level_id');

    	$response = new stdClass;
        $response->success = false;
        $response->count = false;

        $notif = get_notif($user_id, $level_id);
        if($notif['success'] == true)
        {
        	$response->success = true;
            $response->count = $notif['count'];
            $response->rows = $notif['rows'];
            $response->title = $notif['title'];
        }
        else
        {
        	$response->success = false;
        }

        die(json_encode($response));
    }

    public function delete_notif()
    {
    	if($this->input->is_ajax_request())
    	{
    		$all_cabang = $this->cabang_m->get_by(array('is_active' => 1, 'url !=' => ''));

    		$response = new stdClass;
	        $response->success = false;

	        $delete_notif_id = false;

    		$id = $this->input->post('id');
    		$user_id = $this->session->userdata('user_id');
    		
    		$notif = delete_notif($id,$user_id);
    		$filename = urlencode(base64_encode($this->session->userdata('url_login')));
    		
    		if($notif['success'] == true)
    		{
    			foreach ($all_cabang as $cabang) 
	            {
	                change_file_notif($cabang->url,$filename);                    
	            }
    			$response->success = true;
    		}
    		die(json_encode($response));
    	}
    }



}

/* End of file home.php */
/* Location: ./application/controllers/home.php */