<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Neraca_m extends MY_Model {

	protected $_table        = 'neraca';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(

		'neraca.tanggal' => 'tanggal',
		'neraca.nomor' => 'nomor',
		'neraca.total_aktiva' => 'total_aktiva',
		'neraca.total_pasiva' => 'total_pasiva',
		'user.nama'			=> 'nama_user',
		'neraca.id'   => 'id',
		
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable()
	{	
		$join = array('user', $this->_table.'.created_by = user.id', 'left');
		$join_tables = array($join);

		$wheres = array(
			'neraca.is_active' => 1
		);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_max_id_neraca()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `neraca` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}
	

	public function get_max_nomor_neraca($month)
	{
		$format = "SELECT MAX(RIGHT(`nomor`,2)) AS max_nomor FROM `neraca` WHERE SUBSTR(`id`,4,6) = '$month';";	
		return $this->db->query($format);
	}

	public function get_data_neraca($status)
	{
		$format = " SELECT neraca.nama, neraca.kode_neraca, neraca.id, neraca.neraca_id FROM neraca JOIN neraca
					ON neraca.neraca_id = neraca.id
					WHERE neraca.`status` = $status
					ORDER BY neraca.created_date ASC";
		return $this->db->query($format);

	}

}
