<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ubah_password_awal extends CI_Controller {

	protected $menu_id = 1;

	private $menus = array();
	private $menu_tree = array(0, 1);
	public function __construct()
    {       
        parent::__construct();
        $this->load->model('master/bahasa_m');
        $this->load->model('master/user_m');
        $this->load->model('master/cabang_m');
    }

    public function index()
    {
    	$assets = array();
        $assets_config = 'assets/pengaturan/ubah_password_awal/index';
        $this->config->load($assets_config, true);

        $assets = $this->config->item('assets', $assets_config);

        $data = array(
            'title'          => config_item('site_name'). ' &gt;'.translate("Ubah Password", $this->session->userdata("language")), 
            'header'         => translate("Ubah Password", $this->session->userdata("language")), 
            'header_info'    => config_item('site_name'), 
            'breadcrumb'     => TRUE,
            'menus'          => $this->menus,
            'menu_tree'      => $this->menu_tree,
            'css_files'      => $assets['css'],
            'js_files'       => $assets['js'],
            'content_view'   => 'pengaturan/ubah_password_awal/index',
        );

        // Load the view
        $this->load->view('_layout', $data);      
    }

    public function save()
	{
		$command = $this->input->post('command');
		$data_cabang = $this->cabang_m->get();
		if($command == "ubah_password")
		{
			$id = $this->session->userdata("user_id");
			// validasi form
            $rules = $this->user_m->rules_change_password;
            $this->form_validation->set_rules($rules);
            $this->form_validation->set_error_delimiters('', '');
            // process form
            if ($this->form_validation->run() == TRUE) 
            {
            	// exit("HELLO");
                $data = $this->user_m->array_from_post($this->user_m->fillable_changepass());
				$data['password']   = $this->user_m->hash($data['password']);
				
				$url = base_url();
		        $user_id = edit_password_api($data,$url,$id);

		        foreach ($data_cabang as $row_cabang) 
		        {
		            if($row_cabang->url != '')
		            {
		                $url = $row_cabang->url;
		                $user_id = edit_password_api($data,$url,$id);
		            }
		        }
		        
				// $user_id = $this->user_m->save($data, $id);
				if ($user_id) 
				{
					$flashdata = array(
						"type"     => "success",
						"msg"      => translate("Password Changed", $this->session->userdata("language")),
						"msgTitle" => translate("Success", $this->session->userdata("language"))	
						);
					$this->session->set_flashdata($flashdata);
					redirect($this->session->userdata('dashboard_url'));
					
				}
            }
            else
            {
                // collect form error dan form data dari form_validation
                $flashdata = $this->form_validation->get_flashdata();
                $this->session->set_flashdata($flashdata);

                redirect('pengaturan/ubah_password_awal');
            }

            
			
		}
			
	}

    public function validate_password()
	{
		if(! $this->input->is_ajax_request()) redirect(base_url());

		$old_password	= $this->input->post('old_password');
		$user_id	 	= $this->session->userdata('user_id');
		
		$response = array(
			'success'	=> false
			);

		if($this->user_m->validate_password($user_id, $old_password))
		{
			$response['success']  = true;
		}
		
		echo json_encode($response);
	
	}
}