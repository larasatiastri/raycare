<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_log_m extends MY_Model {

	// public $variable;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'user_logs';
	}

}

/* End of file user_log_m.php */
/* Location: ./application/models/user_log_m.php */