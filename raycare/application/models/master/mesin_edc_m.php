<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mesin_edc_m extends MY_Model {

	protected $_table      = 'mesin_edc';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'id',
		'nama',
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'mesin_edc.id'           => 'id', 
		'mesin_edc.nama'         => 'nama',
		'bank.nob'         		 => 'nob',
		'bank.acc_name'          => 'acc_name',
		'bank.acc_number'        => 'acc_number',
		'mesin_edc.is_active'    => 'is_active', 
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{
		$join1 = array('bank', $this->_table.'.bank_id = bank.id', 'left');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.created_date';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);

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

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}

	public function get_max_id()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,11,4)) AS max_id FROM `mesin_edc` WHERE SUBSTR(`id`,5,5) = DATE_FORMAT(NOW(), '%m-%y');";	
		return $this->db->query($format);
	}

	public function get_data_full()
	{
		$this->db->select('mesin_edc.id, mesin_edc.nama, bank.nob');
		$this->db->join('bank', $this->_table.'.bank_id = bank.id','left');
		$this->db->where($this->_table.'.is_active', 1);

		return $this->db->get($this->_table);
	}

}

/* End of file mesin_edc_m.php */
/* Location: ./application/models/mesin_edc_m.php */