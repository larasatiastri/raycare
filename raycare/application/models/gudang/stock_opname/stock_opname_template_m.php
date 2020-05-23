<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_opname_template_m extends MY_Model
{
	protected $_table        = 'stok_opname_template';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable = array(
		'name',
		'warehouse_id',
		'warehouse_people_id'		
	);




	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'stok_opname_template.id'                  => 'id', 
		'stok_opname_template.nama'                => 'name',
		'stok_opname_template.gudang_orang_id' => 'warehouse_people_id', 
		'gudang_orang.nama'                     => 'people',
		'stok_opname_template.gudang_id'        => 'warehouse_id',
		'gudang.nama'        => 'warehouse_name',
	);
	
	function __construct ()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{

		$join1 = array("gudang_orang", $this->_table . '.gudang_orang_id = gudang_orang.id', 'left');
		$join2 = array("gudang", $this->_table . '.gudang_id = gudang.id', 'left');		
		//$join2 = array("warehouses", $this->_table . '.warehouse_id = warehouses.id', 'left');		
		$join_tables = array($join1,$join2);

		$wheres = array(
			// $this->_table . '.warehouse_id' => $warehouse_id
		);
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

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

	 //	  die(dump($result->records));
		return $result; 
	}

	public function get_datatable_history($dt, $status=null)
	{
		$join1 = array("warehouse_people", $this->_table . '.warehouse_people_id = warehouse_people.id', 'left');
		$join2 = array("users", $this->_table . '.create_by = users.id', 'left');
		
		$join_tables = array($join1, $join2);

		$wheres = array();

		if($dt != null)
		{
			$wheres['stock_opname.warehouse_id'] = $dt;
			$wheres['is_finish'] = 1;

			if ($status != null && $status != 2) {
	
				$wheres['is_mismatch'] = $status;
			}
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

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

		// die(dump($result->records));
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
	public function get_people($id)
	{
		$this->db->join('warehouse_people',$this->_table.'.warehouse_people_id = warehouse_people.id','left');
		$this->db->where($this->_table.'.id',$id);

		return $this->db->get($this->_table);
	}

	public function get_warehouse($id)
	{
		$this->db->join('warehouses',$this->_table.'.warehouse_id = warehouses.id','left');
		$this->db->where($this->_table.'.id',$id);

		return $this->db->get($this->_table);
	}
}
