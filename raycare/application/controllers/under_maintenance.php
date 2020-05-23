<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Under_maintenance extends CI_Controller {

	public $data = array();		//data view

	public function __construct() {

		parent::__construct();

		$is_maintenance = config_item('under_maintenance');
        $finish_date = config_item('finish_maintenance_date');
        $today = date('Y-m-d H:i:s');

        if($is_maintenance == 0 && $today > $finish_date)
        {
            redirect('home/login');
        }

		$this->load->model('master/user_m');     //panggil pake $this->user_m
		$this->load->model('master/menu_m'); 
	}

	public function index()
	{
		$this->user_m->logout();
		$is_maintenance = config_item('under_maintenance');
        $finish_date = config_item('finish_maintenance_date');

        $data = array(
        	'finish_date' => $finish_date, 
        );
		$this->load->view('under_maintenance',$data);

	}

}