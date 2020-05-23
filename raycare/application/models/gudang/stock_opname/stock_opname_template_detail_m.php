<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_opname_template_detail_m extends MY_Model
{
	protected $_table        = 'stok_opname_template_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable = array(
		'stock_opname_template_id',
		'item_id',
		
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'stok_opname_template_detail.id'                       => 'id', 
		'stok_opname_template_detail.stok_opname_template_id' => 'stock_opname_template_id', 
		'stok_opname_template_detail.item_id'                  => 'item_id', 
		'item.nama'                                            => 'item_name',
		'item.kode'                                            => 'item_code',
		'item_satuan.nama'
																=>'item_satuan'
	);
	
	function __construct ()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id)
	{	
		$join1 = array("item", $this->_table . '.item_id = item.id', 'left');
		$join2 = array("item_satuan", $this->_table . '.item_satuan_id = item_satuan.id', 'left');
		$join_tables = array($join1,$join2);

		$wheres = array();
		$wheres['stok_opname_template_id'] = $id;

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

	// die(dump($this->db->last_query()));
		return $result; 
	}

	public function get_datatable_qty($id, $warehouse_id)
	{	
		$join1 = array("item", $this->_table . '.item_id = item.id', 'left');
		$join2 = array("inventory", $this->_table . '.item_id = inventory.item_id', 'left');
		$join_tables = array($join1, $join2);

		$wheres = array();
		$wheres['stok_opname_template_id'] = $id;
		$wheres['gudang_id'] = $warehouse_id;

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_qty);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->group_by('stok_opname_template_detail.item_id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->group_by('stok_opname_template_detail.item_id');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->group_by('stok_opname_template_detail.item_id');

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_qty as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_qty;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		// die(dump($result->records));
		return $result; 
	}

	public function get_data($id)
	{
		$this->db->select('stok_opname_template_detail.id, stok_opname_template_detail.item_id, item.code, item.name');
		$this->db->join('item',$this->_table.'.item_id = item.id','left');
		$this->db->where($this->_table.'.stok_opname_template_id',$id);
		return $this->db->get($this->_table);
	}

	public function get_data_by_template_id($template_id, $warehouse_id)
	{
		$this->db->select($this->_table.'.item_id,stok_opname_template_detail.item_satuan_id,item_satuan.nama AS nama_satuan, item.kode, item.nama, (SELECT SUM(inventory.jumlah) FROM inventory WHERE item_id = item.id AND item_satuan_id=item_satuan.id AND gudang_id = '.$warehouse_id.') AS system_qty');
		$this->db->join('item',$this->_table.'.item_id = item.id','left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id','left');
		$this->db->where($this->_table.'.stok_opname_template_id',$template_id);
		return $this->db->get($this->_table);
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
