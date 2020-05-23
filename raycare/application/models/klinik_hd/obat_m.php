<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Obat_m extends MY_Model {

	protected $_table        = 'item';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'id',
		'item_sub_kategori',
		'kode',
		'nama', 
		'keterangan', 
		'is_discontinue', 
		'buffer_stock', 		
		'is_jual', 		
		'id_identitas', 		
	);

	private $_fillable_edit = array(
		'id',
		'item_sub_kategori',
		'kode',
		'nama', 
		'keterangan', 
		'is_discontinue', 
		'buffer_stock', 		
		'is_jual', 		
		'id_identitas',
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item.id'         		 => 'id', 
		'item.kode'   			 => 'kode', 
		'item.nama'   			 => 'nama', 
		'item.keterangan'  		 => 'keterangan',
		'item_satuan.nama'		 => 'nama_satuan',
		'item_satuan.id'		 => 'id_satuan',
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id=null)
	{	
		$join1 = array('item_satuan', $this->_table . '.id = item_satuan.item_id');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.nama';
		$params['sort_dir'] = 'asc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item_satuan.is_primary',1);
		$this->db->where("item.id IN (SELECT item_id from inventory WHERE gudang_id = 'WH-05-2016-002')");
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item_satuan.is_primary',1);
		$this->db->where("item.id IN (SELECT item_id from inventory WHERE gudang_id = 'WH-05-2016-002')");
		
		 
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item_satuan.is_primary',1);
		$this->db->where("item.id IN (SELECT item_id from inventory WHERE gudang_id = 'WH-05-2016-002')");
		
	 

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
	//  die(dump($result->records));
		return $result; 
	}

	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM item');
		
		return $query->row();
		return $this->db->get($this->_table);
	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable_add()
	{
		return $this->_fillable_add;
	}
	/**
	 * [fillable_edit description]
	 * @return [type] [description]
	 */
	public function fillable_edit()
	{
		return $this->_fillable_edit;
	}

	public function get_data_obat()
	{
		$this->db->select('item.id, item.kode, item.nama, item_satuan.id as item_satuan_id, item_satuan.nama as nama_satuan');
		$this->db->join('item_satuan', $this->_table.'.id = item_satuan.item_id');
		$this->db->where('item_satuan.is_primary', 1);
		$this->db->where('item.is_active', 1);
		$this->db->where("item.id IN (SELECT item_id from inventory WHERE gudang_id = 'WH-05-2016-002')");

		return $this->db->get($this->_table);
	}

}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */