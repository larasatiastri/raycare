<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse_people_m extends MY_Model
{
	protected $_table        = 'gudang_orang';
	protected $_order_by     = 'nama';
	protected $_timestamps   = true;

	private $_fillable = array(
		'name',
	);



	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'gudang_orang.id'     			=> 'id', 
		'gudang_orang.gudang_id'     => 'warehouse_id', 
		'gudang_orang.nama'     		=> 'nama', 
	);
	
	function __construct ()
	{
		parent::__construct();
	}

	
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable1()
	{
		// $join1 = array("reff_type_status", $this->_table . '.type = reff_type_status.reff_id', 'left');
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		// $this->db->where('reff_type_status.reff_type','warehouse_type');

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('tipe',0);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('tipe',0);
		// $this->db->where('reff_type_status.reff_type','warehouse_type');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		$this->db->where('tipe',0);
		// $this->db->where('reff_type_status.reff_type','warehouse_type');
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable2()
	{
		// $join1 = array("reff_type_status", $this->_table . '.type = reff_type_status.reff_id', 'left');
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		// $this->db->where('reff_type_status.reff_type','warehouse_type');

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('tipe',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('tipe',1);
		// $this->db->where('reff_type_status.reff_type','warehouse_type');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		$this->db->where('tipe',1);
		// $this->db->where('reff_type_status.reff_type','warehouse_type');
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable3()
	{
		// $join1 = array("reff_type_status", $this->_table . '.type = reff_type_status.reff_id', 'left');
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		// $this->db->where('reff_type_status.reff_type','warehouse_type');

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('tipe',2);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('tipe',2);
		// $this->db->where('reff_type_status.reff_type','warehouse_type');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		$this->db->where('tipe',2);
		// $this->db->where('reff_type_status.reff_type','warehouse_type');
		
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

	
	
}

/* End of file warehouse_m.php */
/* Location: ./application/models/master/warehouse_m.php */