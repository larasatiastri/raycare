<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_logout extends CI_Controller {    

	protected $menu_id = 1;

    private $menus = array();
    private $menu_tree = array(0, 1);

	public function __construct() {
		parent::__construct();

		$this->load->model('master/cabang_m');
		$this->load->model('master/user_m');
		$this->load->model('master/user_level_m');

	}

	public function index(){
        // Load the view
        $user_id = $this->session->userdata('user_id');
        $user_level_id = $this->session->userdata('level_id');

        $user = $this->user_m->get_by(array('id' => $user_id), true);
        $user_level = $this->user_level_m->get_by(array('id' => $user_level_id), true);

        $data = array(
        	'user' => $user,
        	'user_level' => $user_level,
        );

        $this->load->view('page_logout', $data);
		
	}
}