<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_m extends MY_Model {

	protected $_table        = 'inventory';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'inventory.id'             => 'id',
		'inventory.gudang_id'      => 'gudang_id',
		'inventory.item_id'        => 'item_id',
		'inventory.item_satuan_id' => 'satuan_id',
		'inventory.jumlah'         => 'jumlah',
		'inventory.harga_beli'     => 'harga',
		'gudang.nama'              => 'nama_gudang',
		'item.kode'                => 'item_kode',
		'item.nama'                => 'item_nama',
		'item_satuan.nama'         => 'satuan',
		'gudang.is_active'		   => 'is_active'
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
		$join_tables = array($join1, $join2, $join3);

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

	public function get_item_identitas($id)
	{
		$format = "SELECT
					inventory.id,
					inventory.item_id,
					inventory_identitas.identitas_id,
					inventory_identitas.judul,
					inventory_identitas.`value`,
					inventory_identitas.jumlah,
					identitas.judul,
					identitas.tipe
					FROM
					inventory
					LEFT JOIN inventory_identitas ON inventory.id = inventory_identitas.inventory_id
					LEFT JOIN identitas ON inventory_identitas.identitas_id = identitas.id
					WHERE
					inventory.id = $id";

		return $this->db->query($format);
	}

	public function get_item_identitas1($id)
	{


		$sql = "SELECT
				inventory.inventory_id,
				inventory.item_id,
				inventory.harga_beli AS harga_beli,
				inventory_identitas.jumlah,
				inventory.gudang_id,
				inventory.pmb_id,
				inventory_identitas_detail.inventory_identitas_id,
				inventory_identitas_detail.identitas_id,
				identitas.judul as judul,
				identitas.`value` as `value`,
				identitas.tipe as tipe
				FROM
				inventory
				LEFT JOIN inventory_identitas ON inventory_identitas.inventory_id = inventory.inventory_id
				LEFT JOIN inventory_identitas_detail ON inventory_identitas_detail.inventory_identitas_id = inventory_identitas.inventory_identitas_id
				LEFT JOIN identitas ON inventory_identitas_detail.identitas_id = identitas.id
				WHERE
					inventory.inventory_id = $id";

		return $this->db->query($sql);

	}

	public function cek_identitas($inventory_id)
	{
		$format = "SELECT *
					FROM `inventory_identitas`
					WHERE
					inventory_identitas.inventory_id = $inventory_id
					";

		return $this->db->query($format);
	}

	public function delete_inventory($inventory_id)
	{
		$format = "DELETE FROM inventory WHERE id = $inventory_id";

		return $this->db->query($format);
	}

	public function get_last_id()
	{
		$format = "SELECT MAX(id) FROM inventory";

		return $this->db->query($format);
	}

	public function get_data_identitas($id)
	{
		$format = "SELECT
					inventory_identitas.inventory_identitas_id as inventory_identitas_id,
					inventory_identitas.inventory_id,
					inventory_identitas.jumlah,
					inventory_identitas_detail.identitas_id,
					inventory_identitas_detail.judul,
					inventory_identitas_detail.`value`,
					identitas.tipe,
					inventory.inventory_id,
					inventory.gudang_id,
					inventory.pmb_id,
					inventory.tanggal_datang,
					inventory.harga_beli,
					inventory.jumlah as jumlah_inventory
					FROM
					inventory_identitas
					LEFT JOIN inventory_identitas_detail ON inventory_identitas.inventory_identitas_id = inventory_identitas_detail.inventory_identitas_id
					LEFT JOIN identitas ON inventory_identitas_detail.identitas_id = identitas.id
					LEFT JOIN inventory ON inventory_identitas.inventory_id = inventory.inventory_id
					WHERE
					inventory_identitas.inventory_identitas_id = $id 
				";

		return $this->db->query($format);
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

	public function get_datatable_item($id)
	{	
		$datatable_item = array(

			'item.id'	=> 'item_id',
			'item.kode' => 'item_kode',
			'item.nama' => 'item_nama',
			'item_satuan.id' => 'item_satuan_id',
			'item_satuan.nama' => 'satuan',
			'SUM(inventory.jumlah)' => 'stock',

		);

		$join1 = array('item', 'item.id = inventory.item_id');
		$join2 = array('item_satuan','item_satuan.id = inventory.item_satuan_id');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('inventory.gudang_id', $id);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');
		// $this->db->where('request_item_detail.item_id', $item_id);
		// $this->db->where('request_item_detail.item_satuan_id', $item_satuan_id);
		// $this->db->where('item_satuan.id = request_item_detail.item_satuan_id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('inventory.gudang_id', $id);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');
		// $this->db->where('request_item_detail.item_id', $item_id);
		// $this->db->where('request_item_detail.item_satuan_id', $item_satuan_id);
		// $this->db->where('item_satuan.id = request_item_detail.item_satuan_id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('inventory.gudang_id', $id);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');
		// $this->db->where('request_item_detail.item_id', $item_id);
		// $this->db->where('request_item_detail.item_satuan_id', $item_satuan_id);
		// $this->db->where('item_satuan.id = request_item_detail.item_satuan_id');

		// tentukan kolom yang mau diselect
		foreach ($datatable_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function update_stock_identitas($jumlah, $id, $modified_by, $modified_date){
		$format = "UPDATE inventory SET jumlah = $jumlah, modified_by = $modified_by, modified_date = '$modified_date' WHERE inventory_id =  $id";
		return $this->db->query($format);
	}

}

