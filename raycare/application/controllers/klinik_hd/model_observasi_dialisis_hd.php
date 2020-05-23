<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_observasi_dialisis_hd extends CI_Model
{
	
	var $table;
	
	function __construct()
	{
		parent::__construct();
		$this->table = "observasi_dialisis_hd";
	}
	
	
	function get_all()
	{
		$this->db->order_by("id", "ASC");
		return $this->db->get($this->table);
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
	}
	
}