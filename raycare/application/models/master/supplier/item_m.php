<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_m extends MY_Model {

	protected $_table      = 'item';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
	
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item.id'          => 'id',
		'item.kode'        => 'kode',
		'item.nama'        => 'nama',
		'item.keterangan'  => 'keterangan',

	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($item_id)
	{

		$join1 = array('item_satuan', 'item.id = item_satuan.item_id','left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where('item_satuan.is_primary', 1);

		if ($item_id != null) {
			$this->db->where('item_id NOT IN ('.$item_id.')');
		}

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where('item_satuan.is_primary', 1);

		if ($item_id != null) {
			$this->db->where('item_id NOT IN ('.$item_id.')');
		}

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where('item_satuan.is_primary', 1);
		
		if ($item_id != null) {
			$this->db->where('item_id NOT IN ('.$item_id.')');
		}

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


	public function get_data_item($item_id) {

		$sql = "SELECT
					item.id AS id,
					item.kode AS kode,
					item.nama AS nama
				FROM
					item
				LEFT JOIN item_satuan ON item.id = item_satuan.item_id
				WHERE
					item.is_active = 1
				AND item_satuan.is_primary = 1
				AND item_id NOT IN ($item_id)";

		return $this->db->query($sql)->result_array();
	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}

	public function get_all()
	{
		return $this->db->get($this->_table)->result_array();
	}

}

/* End of file supplier_m.php */
/* Location: ./application/models/supplier_m.php */