<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_m extends MY_Model {
	
	protected $_table        = 'item';
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

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns_join = array(
		//column di table  => alias
		'items.id'                                           => 'id', 
		'items.code'                                         => 'code', 
		'items.name'                                         => 'name', 
		'sum(inventory.qty)/2'                               => 'stock',
		'sum(sales_order_details.approved_quantity_finance)/2' => 'total_sales',
	);

	// Array of database columns which should be read and sent back to DataTables

	

	
	
	function __construct ()
	{
		parent::__construct();
	}

	
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
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
		// die(dump($result->records));
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
	
	function get_id()
	{
		
		$this->db->select('id');
		$this->db->from('regions');
		 
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		return $this->db->get()->result();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_po()
	{
		$join1 = array("inventory", $this->_table . '.id = inventory.item_id', 'left');
		$join2 = array("sales_order_details", $this->_table . '.id = sales_order_details.item_id');
		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_join);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->group_by('items.id');
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('sales_order_details.item_id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->group_by('items.id');
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('sales_order_details.item_id');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->group_by('items.id');
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('sales_order_details.item_id');

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_join as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_join;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_trend()
	{	
		$datatable_columns_trend = array(
			//column di table  => alias
			'items.id' => 'id',
	        'items.code' => 'code',
	        'items.name' => 'name',
	        '(select sum(inventory.qty) from inventory where item_id = items.id)' => 'stock',
	        '(select sum(sales_order_details.approved_quantity_finance) from sales_order_details JOIN sales_orders ON (sales_order_details.sales_order_id = sales_orders.id) WHERE sales_order_details.item_id = items.id AND sales_orders.order_date >= "'.date('Y-m-01').'" AND sales_orders.order_date <= "'.date('Y-m-t').'"   )' => 'total_sales',
	        '(select sum(sales_order_details.approved_quantity_finance/3) from sales_order_details JOIN sales_orders ON (sales_order_details.sales_order_id = sales_orders.id) WHERE sales_order_details.item_id = items.id AND sales_orders.order_date >= "'.date('Y-m-01', strtotime('- 3 months')).'" AND sales_orders.order_date < "'.date('Y-m-01').'")' => 'avg_sales',
	        '(select sum(purchase_order_items.undelived_quantity) FROM purchase_order_items JOIN purchase_orders ON (purchase_order_items.purchase_order_id = purchase_orders.id) WHERE purchase_order_items.item_id = items.id AND purchase_orders.is_closed = 0 AND purchase_orders.expire_date > "'.date('Y-m-d').'")' => 'undelived_quantity',
	        '(select GROUP_CONCAT(`suppliers`.`code` SEPARATOR "/") FROM supplier_items JOIN suppliers ON (supplier_items.`supplier_id` = suppliers.id) WHERE item_id = items.id)' => 'supplier_code'
		);

		$join4 = array("inventory", $this->_table . '.id = inventory.item_id', 'left');
		$join5 = array("sales_order_details", $this->_table . '.id = sales_order_details.item_id','left');
		$join6 = array("purchase_order_items", $this->_table . '.id = purchase_order_items.item_id','left');

		// $join_tables = array($join1,$join2,$join3,$join4,$join5);
		$join_tables = array($join4,$join5,$join6);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_trend);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->group_by('items.id');
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('sales_order_details.item_id');
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->group_by('items.id');
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('sales_order_details.item_id');
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->group_by('items.id');
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('sales_order_details.item_id');

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_trend as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_trend;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_item($warehouse_id){

		$datatable_columns_item = array(
			//column di table  => alias
			'item.id' 		=> 'item_id',
	        'item.kode' 	=> 'code',
	        'item.nama' 	=> 'name',
	        '(SELECT SUM(inventory.jumlah) FROM inventory WHERE item_id = item.id AND gudang_id = '.$warehouse_id.')' => 'system_qty',
	    );
 	
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->group_by('item.id');
		// $this->db->group_by('inventory.item_id');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->group_by('item.id');
		// $this->db->group_by('inventory.item_id');

		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->group_by('item.id');
		// $this->db->group_by('inventory.item_id');

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_join($warehouse_id){

		$datatable_columns_item = array(
			//column di table  => alias
			'items.id'      => 'item_id',
			'items.code'    => 'code',
			'items.name'    => 'name',
			'items.join_id' => 'join_id',
	        
	    );
 	
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('items.is_joined',1);
		
		$this->db->group_by('items.id');
		// $this->db->group_by('inventory.item_id');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('items.is_joined',1);
		
		$this->db->group_by('items.id');
		// $this->db->group_by('inventory.item_id');

		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('items.is_joined',1);
		
		$this->db->group_by('items.id');
		// $this->db->group_by('inventory.item_id');

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}
	
}

/* End of file item_m.php */
/* Location: ./application/models/master/item/item_m.php */