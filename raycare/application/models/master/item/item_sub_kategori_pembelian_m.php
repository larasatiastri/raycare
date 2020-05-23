<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_sub_kategori_pembelian_m extends MY_Model {

	protected $_table        = 'item_sub_kategori_pembelian';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'item_kategori_id',
		'kode',
		'nama', 
		'keterangan', 
	);

	private $_fillable_edit = array(
		'item_kategori_id',
		'kode',
		'nama', 
		'keterangan', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'item_sub_kategori_pembelian.kode'       => 'kode', 
		'item_sub_kategori_pembelian.nama'       => 'nama', 
		'item_kategori.nama'           => 'kategori', 
		'item_sub_kategori_pembelian.keterangan' => 'keterangan', 
		'item_sub_kategori_pembelian.id'         => 'id', 
		
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
		$join = array('item_kategori', $this->_table . '.item_kategori_id = item_kategori.id');
		$join_tables = array($join);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('item_sub_kategori.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('item_sub_kategori.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('item_sub_kategori.is_active',1);

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

	public function get_data($id)
	{	
		$this->db->select('user_level.nama,
							item_sub_kategori_pembelian.id,
							item_sub_kategori_pembelian.user_level_id,
							item_sub_kategori_pembelian.level_order,
							item_sub_kategori_pembelian.lewati,
							item_sub_kategori_pembelian.req
						');
		$this->db->join('user_level',$this->_table.'.user_level_id = user_level.id','left');
		$this->db->where('item_sub_kategori_id', $id);
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

}

