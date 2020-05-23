<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_m extends MY_Model {
	
	protected $_table        = 'inventory';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable = array(
		'item_category_id',
		'join_id',
		'name',
		'code',
		'size',
		'sell_price',
		'is_joined',
		'is_selling',
		'is_green_block',
		'motorcycle',
		'packaging',
		'person_in_change',
		'need_operational_approval',
		'description'
		
	);


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'items.id'                        => 'id', 
		'items.code'                      => 'code', 
		'items.name'                      => 'name', 
		'items.motorcycle'                => 'motorcycle', 
		'items.size'                      => 'size', 
		'items.packaging'                 => 'packaging', 
		'items.sell_price'                => 'sell_price',
		'items.is_selling'                => 'is_selling',
		'items.is_green_block'            => 'is_green_block',
		'items.is_joined'                 => 'is_joined',
		'items.need_operational_approval' => 'need_operational_approval',
		'items.is_active' 				  => 'is_active',

	);


	protected $datatable_columns_inventory = array(
		//column di table  => alias
		'inventory.warehouse_id'		=> 'warehouse_id', 
		'warehouses.name'				=> 'warehouse_name',
		'inventory.pmb_id'   			=> 'pmb_id', 
		'inventory.item_id'             => 'item_id', 
		'sum(inventory.qty)'			=> 'qty',
		'items.name'             		=> 'item_name', 
		'items.code'             		=> 'item_code', 
		// 'inventory.qty'                	=> 'qty', 
		'inventory.arrival_date'        => 'purchase_date', 
		'inventory.purchase_price'      => 'purchase_price', 

	);
	
	function __construct ()
	{
		parent::__construct();
	}

	
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */


	public function update_by_pmb($data,$pmb_id, $item_id){
		
		$this->db->set($data);
		$this->db->where('pmb_id', $pmb_id);
		$this->db->where('item_id', $item_id);
		$this->db->update($this->_table);
	
	}

	public function get_datatable_inventory($id) {
		
		$join1 = array("warehouses", $this->_table . '.warehouse_id = warehouses.id', 'left');
		$join2 = array("items", $this->_table . '.item_id = items.id', 'left');

		$join_tables = array($join1, $join2);

		$wheres = array();
		$wheres['warehouse_id'] = $id;

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_inventory);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->group_by($this->_table . '.item_id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->group_by($this->_table . '.item_id');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->group_by($this->_table . '.item_id');

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_inventory as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_inventory;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_qty($id, $item_id = null)
	{	
		$join1 = array("items", $this->_table . '.item_id = items.id', 'left');
		$join2 = array("warehouses", $this->_table . '.warehouse_id = warehouses.id', 'left');

		$join_tables = array($join1, $join2);

		$wheres = array();
		$wheres['warehouse_id'] = $id;

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_inventory);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		if ($item_id != null) {
			$this->db->where_not_in('item_id', $item_id);
		}

		$this->db->group_by($this->_table . '.item_id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);

		if ($item_id != null) {
			$this->db->where_not_in('item_id', $item_id);
		}

		$this->db->group_by($this->_table . '.item_id');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);

		if ($item_id != null) {
			$this->db->where_not_in('item_id', $item_id);
		}
		
		$this->db->group_by($this->_table . '.item_id');

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_inventory as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_inventory;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}


	public function get_datatable_list_item($id, $item_id = null) {
		
		$join1 = array("items", $this->_table . '.item_id = items.id', 'left');

		$join_tables = array($join1);

		$wheres = array();
		$wheres['warehouse_id'] = $id;

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		if ($item_id != null) {
			$this->db->where_not_in('item_id', $item_id);
		}

		$this->db->group_by($this->_table . '.item_id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);

		if ($item_id != null) {
			$this->db->where_not_in('item_id', $item_id);
		}

		$this->db->group_by($this->_table . '.item_id');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);

		if ($item_id != null) {
			$this->db->where_not_in('item_id', $item_id);
		}
		
		$this->db->group_by($this->_table . '.item_id');

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


	public function get_datatable($status=null,$block=null,$join=null,$approve=null)
	{
		$join_tables = array();

		$wheres = array();

		if($status != null && $status != 3)
		{
			$wheres['is_selling'] = $status;
		}
		if($block != null && $block != 3)
		{
			$wheres['is_green_block'] = $block;
		}
		if($join != null && $join != 3)
		{
			$wheres['is_joined'] = $join;
		}
		if($approve != null && $approve != 3)
		{
			$wheres['need_operational_approval'] = $approve;
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
		// die(dum($result->records));
		return $result; 
	}


	public function get_datatable2($status=null,$block=null,$join=null)
	{
		$join_tables = array();

		$wheres = array();

		if($status != null && $status != 3)
		{
			$wheres['is_selling'] = $status;
		} 

		if($block != null && $block != 3)
		{
			$wheres['is_green_block'] = $block;
		} 

		if($join != null && $join != 3)
		{
			$wheres['is_joined'] = $join;
		} 
		 
		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->where('is_active',1);

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
		// die(dum($result->records));
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

	public function get_items($warehouse_id){

		$this->db->select('items.id AS id, inventory.warehouse_id AS wh_id, items.code AS code, items.name AS name');
		$this->db->join('items',$this->_table.'.item_id = items.id','left');
		$this->db->where($this->_table.'.warehouse_id',$warehouse_id);
		$this->db->group_by('inventory.item_id');

		return $this->db->get($this->_table);

	}

	function get_id()
	{
		
		$this->db->select('id');
		$this->db->from('regions');
		 
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		return $this->db->get()->result();
	}

	function get_item_modal($id)
	{
		$query="SELECT items.id,items.name,items.code,sum(inventory.qty) as qty FROM inventory join items on items.id=inventory.item_id WHERE items.is_active=1 and inventory.warehouse_id='".$id. "' group by code,name order by code asc";
		$hslquery=$this->db->query($query); 
	 	return $hslquery->result();
	}

	function get_item_modal2($id)
	{
		$query="SELECT items.id,items.name,items.code,sum(inventory.qty) as qty FROM inventory join items on items.id=inventory.item_id WHERE items.is_active=1 and inventory.warehouse_id='".$id. "' group by code,name order by code asc";
		$hslquery=$this->db->query($query); 
	 	return $hslquery->result();
	}

	function get_pmb($id){
		$query="SELECT * from pmb_detail join items on pmb_detail.item_id=items.id where pmb_detail.pmb_id=".$id;
		$hslquery=$this->db->query($query); 

		return $hslquery->result_array();
	}

	function get_last_stok($item_id,$warehouse_id)
	{
		$this->db->where('item_id', $item_id);
		$this->db->where('warehouse_id', $warehouse_id);
		$this->db->where('purchase_price IS NOT NULL');

		$this->db->order_by('arrival_date', 'asc');
		return $this->db->get($this->_table);
	}

	function get_last_stok_new($item_id)
	{
		$this->db->where('item_id', $item_id);
		$this->db->where("purchase_price != ''");
		$this->db->where("is_assign",1);
		$this->db->order_by('arrival_date', 'asc');
		return $this->db->get($this->_table);
	}

	function get_last_stok2($item_id, $warehouse_id)
	{
		$this->db->where('item_id', $item_id);
		$this->db->where('warehouse_id', $warehouse_id);
		$this->db->where("purchase_price != ''");
		$this->db->where("is_assign",1);


		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		return $this->db->get($this->_table);

	}

	function get_last_stok3($item_id, $warehouse_id)
	{
		$this->db->where('item_id', $item_id);
		$this->db->where('warehouse_id', $warehouse_id);
		$this->db->where("purchase_price != ''");
		

		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		return $this->db->get($this->_table);

	}

	function get_max_pmb()
	{
		$this->db->select();
		$this->db->where('pmb_id','(SELECT MAX(pmb_id) as max_pmb FROM inventory)');
		$this->db->where('is_assign',1);
		$this->db->where("purchase_price != ''");
		return $this->db->get($this->_table);

		// die_dump($this->db->get($this->_table));
	}
}

/* End of file item_m.php */
/* Location: ./application/models/master/item/item_m.php */