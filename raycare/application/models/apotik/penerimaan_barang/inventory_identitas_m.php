<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_identitas_m extends MY_Model {

	protected $_table        = 'inventory_identitas';
	protected $_order_by     = 'inventory_identitas_id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'inventory.inventory_id'             => 'id',
		// 'inventory.inventory_id'   => 'inventory_id',
		'inventory.gudang_id'      => 'gudang_id',
		'inventory.pmb_id'	       => 'pmb_id',
		'inventory.item_id'        => 'item_id',
		'inventory.item_satuan_id' => 'satuan_id',
		'inventory.jumlah'         => 'jumlah',
		'inventory.harga_beli'     => 'harga_beli',
		'gudang.nama'              => 'nama_gudang',
		'item.kode'                => 'item_kode',
		'item.nama'                => 'item_nama',
		'item_satuan.nama'         => 'satuan',
		'gudang.is_active'		   => 'is_active',
		'item_harga.harga'		   => 'harga'
	);

	protected $datatable_columns_item = array(
		//column di table  => alias
		//'inventory.id'             => 'id',
		'inventory.gudang_id'      => 'gudang_id',
		'inventory.inventory_id'   => 'inventory_id',
		'inventory.pmb_id'	       => 'pmb_id',
		'inventory.item_id'        => 'item_id',
		'inventory.item_satuan_id' => 'satuan_id',
		'inventory.jumlah'         => 'jumlah',
		'inventory.harga_beli'     => 'harga',
		'gudang.nama'              => 'nama_gudang',
		'item.kode'              => 'kode', 
		'item.nama'              => 'nama', 
		'item_satuan.nama'       => 'unit',
		'item_harga.harga'       => 'harga',
		'item_kategori.id'       => 'item_kategori_id',
		'item.keterangan'        => 'keterangan',
		'item.id'                => 'id', 
		'item_kategori.nama'     => 'kategori_item',
		'item.item_sub_kategori' => 'item_sub_kategori', 

	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($gudang_id)
	{		
		$join1 = array('gudang', $this->_table.'.gudang_id = gudang.id', 'left');
		$join2 = array('item', 'inventory.item_id = item.id', 'left');
		$join3 = array('item_satuan', 'inventory.item_satuan_id = item_satuan.id', 'left');
		$join4 = array('item_harga', 'item_satuan.id = item_harga.item_satuan_id', 'left');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable

		if ($gudang_id == null) {
			$wheres = array(
				'gudang.is_active' => 1
			);
		}else{
			$wheres = array(
				'gudang.is_active'    => 1,
				'inventory.gudang_id' => $gudang_id
			);
		}
		
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

	public function get_max_id_identitas()
	{
		$format = "SELECT MAX(inventory_identitas_id) as max_id_identitas FROM inventory_identitas";

		return $this->db->query($format);
	}


}

