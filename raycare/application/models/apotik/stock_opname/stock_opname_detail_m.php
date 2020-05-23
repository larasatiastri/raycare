<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_opname_detail_m extends MY_Model
{
	protected $_table        = 'stok_opname_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable = array(
		'stock_opname_id',
		'item_id',
		'input_qty',
		'counted_qty',
		// 'system_qty',
	);



	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item.kode'							=> 'item_code',
		'item.nama'							=> 'item_name',
		'stok_opname_detail.jumlah_hitung'       => 'counted_qty', 
		'stok_opname_detail.jumlah_sistem'		=> 'system_qty', 
		'stok_opname_detail.id'               	=> 'id', 
		'stok_opname_detail.stok_opname_id'   => 'stock_opname_id', 
		'stok_opname_detail.item_id'           => 'item_id', 
		'item_satuan.nama'           => 'nama_satuan',

	);

	protected $datatable_columns_qty = array(
		//column di table  => alias
		'stok_opname_detail.id'               	=> 'id', 
		'stok_opname_detail.stok_opname_id'   => 'stock_opname_id', 
		'stok_opname_detail.item_id'           => 'item_id', 
		'stok_opname_detail.jumlah_sistem'        => 'system_qty', 
		'stok_opname_detail.jumlah_input'       	=> 'input_qty', 
		'stok_opname_detail.jumlah_hitung'      	=> 'counted_qty', 
		'item.nama'							=> 'item_name',
		'item.kode'							=> 'item_code',
		'item_satuan.nama'							=> 'nama_satuan',
		'stok_opname_detail.item_satuan_id'				=>'item_satuan_id'
		// 'inventory.warehouse_id'				=> 'warehouse_id',
		// 'SUM(inventory.qty)'					=> 'qty'
	);
	
	function __construct ()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */

public function get_datatable_detail($id,$wareid)
	{	
		
		$datatable_columns_detail = array(
			//column di table  => alias
			'stok_opname_detail.id'               	=> 'id', 
			'stok_opname_detail.stok_opname_id'   => 'stock_opname_id', 
			'stok_opname_detail.item_id'           => 'item_id', 
			'stok_opname_detail.jumlah_sistem'        => 'system_qty', 
			'stok_opname_detail.jumlah_input'       	=> 'input_qty', 
			'stok_opname_detail.jumlah_hitung'      	=> 'counted_qty', 
			'item.nama'							=> 'item_name',
			'item.kode'							=> 'item_code',
			'(SELECT inventory.harga_beli FROM inventory WHERE inventory.gudang_id = '.$wareid.' AND inventory.item_id = items.id AND inventory.harga_beli IS NOT NULL ORDER BY inventory.tanggal_datang ASC LIMIT 1)'  => 'price',
		);

		$join1 = array("item", $this->_table . '.item_id = item.id', 'left');
		$join_tables = array($join1);

		$wheres = array();
		$wheres['stok_opname_id'] = $id;

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_detail);

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
		foreach ($datatable_columns_detail as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_detail;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		return $result; 
	}

	public function get_datatable($id)
	{	
		$join1 = array("item", $this->_table . '.item_id = item.id', 'left');
		$join2 = array("item_satuan", $this->_table . '.item_satuan_id = item_satuan.id', 'left');
		$join_tables = array($join1,$join2);

		$wheres = array();
		$wheres['stok_opname_id'] = $id;

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

	public function get_datatable_qty($id, $warehouse_id)
	{	
		$join1 = array("item", $this->_table . '.item_id = item.id', 'left');
		$join2 = array("item_satuan", $this->_table . '.item_satuan_id = item_satuan.id', 'left');
		// $join2 = array("inventory", $this->_table . '.item_id = inventory.item_id', 'left');
		$join_tables = array($join1, $join2);

		$wheres = array();
		$wheres['stok_opname_id'] = $id;
		// $wheres['warehouse_id'] = $warehouse_id;

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_qty);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->group_by(array('stok_opname_detail.item_id','stok_opname_detail.item_satuan_id'));
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->group_by(array('stok_opname_detail.item_id','stok_opname_detail.item_satuan_id'));
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->group_by(array('stok_opname_detail.item_id','stok_opname_detail.item_satuan_id'));

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

//	  die(dump($result->records));
		return $result; 
	}

	public function get_data($id)
	{
		$this->db->select('stok_opname_detail.jumlah_sistem,stok_opname_detail.item_satuan_id,stok_opname_detail.id, stok_opname_detail.item_id, item.kode, item.nama,item_satuan.nama as nama_satuan');
		$this->db->join('item',$this->_table.'.item_id = item.id','left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id','left');
		$this->db->where($this->_table.'.stok_opname_id',$id);
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
