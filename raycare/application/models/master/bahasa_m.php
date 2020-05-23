<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bahasa_m extends MY_Model {

	// public $variable;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'bahasa';
	}

	public function get_active_langs()
	{
		return $this->get_by(array('is_active' => 1));
	}

}

/* End of file bahasa_m.php */
/* Location: ./application/models/master/bahasa_m.php */