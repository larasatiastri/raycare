<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class T_box_paket_history_m extends MY_Model {

	protected $_table        = 't_box_paket_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(

		't_box_paket_history.id' => 'id',
		't_box_paket_history.kode_box_paket' => 'kode_box_paket',
		'box_paket.nama' => 'nama_box_paket',
		't_box_paket_history.harga_paket' => 'harga_paket',
		't_box_paket_history.tipe_tindakan' => 'tipe_tindakan',
		't_box_paket_history.created_date' => 'created_date',
		't_box_paket_history.status' => 'status',
		'user.nama' => 'nama_dibuat_oleh',
		'box_paket.id'   => 'id_box',
		
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable()
	{	
		$join1 = array('box_paket', $this->_table.'.box_paket_id = box_paket.id', 'left');
		$join2 = array('user', $this->_table.'.created_by = user.id', 'left');
		$join_tables = array($join1, $join2);

		$wheres = array(
			'status !=' => 4
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

	public function get_max_id_box_paket()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,14,4)) AS max_id FROM `t_box_paket_history` WHERE SUBSTR(`id`,6,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

}
