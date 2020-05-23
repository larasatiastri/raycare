<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_m extends MY_Model {

	protected $_table        = 'inventory';
	protected $_order_by     = 'inventory_id';
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

	// public function get_item_identitas($id)
	// {
	// 	$format = "SELECT
	// 				inventory.id,
	// 				inventory.item_id,
	// 				inventory_identitas.identitas_id,
	// 				inventory_identitas.judul,
	// 				inventory_identitas.`value`,
	// 				inventory_identitas.jumlah,
	// 				identitas.tipe
	// 				FROM
	// 				inventory
	// 				LEFT JOIN inventory_identitas ON inventory.id = inventory_identitas.inventory_id
	// 				LEFT JOIN identitas ON inventory_identitas.identitas_id = identitas.id
	// 				WHERE
	// 				inventory.id = $id";

	// 	return $this->db->query($format);
	// }
	
	public function get_item_satuan($item_id)
	{

		$format = "SELECT *
						FROM 
						inventory
						LEFT JOIN item_satuan on inventory.item_satuan_id = item_satuan.id
						WHERE inventory.item_id =  $item_id
						
						GROUP BY inventory.item_satuan_id
						ORDER BY 'inventory_id'";

		return $this->db->query($format);
	}


	public function get_item_identitas($id)
	{
		$format = "SELECT
					inventory_identitas.inventory_identitas_id as inventory_identitas_id,
					inventory_identitas.inventory_id,
					inventory_identitas.jumlah,
					inventory_identitas_detail.identitas_id,
					inventory_identitas_detail.judul,
					inventory_identitas_detail.`value`,
					identitas.tipe,
					inventory.harga_beli,
					inventory.jumlah as jumlah_inventory,
					inventory.gudang_id as gudang_id, 
					inventory.gudang_id as pmb_id

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

	public function update_jumlah_inventory($jumlah,$modified_by, $modified_date, $id){
		$format = "UPDATE inventory SET jumlah = $jumlah, modified_by = $modified_by, modified_date = '$modified_date' WHERE inventory_id =  $id";
		return $this->db->query($format);
	}

	// Created by Abu for update jumlah in inventory
	public function save_inventory($data, $inventory_id){

		$this->db->where('inventory_id', $inventory_id);
		$this->db->update($this->_table, $data);
	}

	// Created by Abu for delete jumlah in inventory = 0
	public function delete_inventory_kosong($inventory_id){

		$this->db->where('inventory_id', $inventory_id);
		$this->db->delete($this->_table);
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
		$format = "DELETE FROM inventory WHERE inventory_id = $inventory_id";

		return $this->db->query($format);
	}

	public function get_last_id()
	{
		$format = "SELECT MAX(inventory_id) FROM inventory";

		return $this->db->query($format);
	}
	
	public function get_max_id()
	{
		$format = "SELECT MAX(inventory_id) as max_id FROM inventory";

		return $this->db->query($format);
	}

	public function insert_inventory($id, $gudang_id, $item_id, $item_satuan_id, $jumlah, $tanggal){

		$id_user = $this->session->userdata('user_id');
		$format="INSERT INTO inventory(inventory_id, gudang_id, pmb_id, item_id, item_satuan_id, jumlah, tanggal_datang, created_by, created_date)
				 VALUE ($id, $gudang_id, '0', $item_id, $item_satuan_id, $jumlah, '$tanggal', $id_user, '$tanggal')";

		return $this->db->query($format);
	}

	public function get_fifo($gudang_id, $item_id, $item_satuan_id)
	{
		$sql = "SELECT
				inventory.inventory_id,
				inventory.jumlah
				FROM
				inventory
				WHERE
				gudang_id = $gudang_id AND
				item_id = $item_id AND
				item_satuan_id = $item_satuan_id
				ORDER BY
				tanggal_datang";

		return $this->db->query($sql);
	}

	public function update_inventory($id, $jumlah, $modified_date){

		$modified_by= $this->session->userdata('user_id');
		$format = "UPDATE inventory SET jumlah = $jumlah, modified_by = $modified_by, modified_date = '$modified_date' WHERE inventory_id =  $id";
		return $this->db->query($format);
	}

	public function save_data($jumlah, $inventory_id)
	{
		$user = $this->session->userdata('user_id');
		$modified_date = date('Y-m-d H:i:s');
		$format = "UPDATE inventory SET jumlah = $jumlah, modified_by = $user, modified_date = '$modified_date' WHERE inventory_id = $inventory_id";

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

	public function get_datatable_penjualan_obat($status_so_history)
	{	

		$join1 = array('item', $this->_table.'.item_id = item.id', 'left');
		$join2 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id', 'left');
		$join3 = array('item_kategori', 'item_kategori.id = item_sub_kategori.item_kategori_id', 'left');
		$join4 = array('item_harga', 'item_harga.item_id = item.id', 'left');
		$join5 = array('item_satuan', 'item_satuan.item_id = item.id', 'left');
		$join6 = array('gudang', $this->_table.'.gudang_id = gudang.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4, $join5, $join6);
		
		if($status_so_history == 1) 
		{

			$kategori = 1;

		} elseif ($status_so_history == 2) 

		{

			$kategori = 2;

		} elseif ($status_so_history == 3)
		{

			$kategori = 3;
		}

		// $wheres = array(

		// 		'item_harga.cabang_id' => $cabang_id,
		// 		'item_satuan.is_primary' => 1,

		// 	);

		

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item);
		// die_dump($params);
		$params["sort_by"] = "inventory.inventory_id";
		$params['sord_dir'] = "asc";
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_kategori_id', 1);
			$this->db->where('inventory.gudang_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('inventory.gudang_id', 2);
			$this->db->or_where('inventory.gudang_id', 3);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_kategori_id', $kategori);
			$this->db->where('inventory.gudang_id', $kategori);
			$this->db->group_by('id', 'asc');
			
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_kategori_id', 1);
			$this->db->where('inventory.gudang_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('inventory.gudang_id', 2);
			$this->db->or_where('inventory.gudang_id', 3);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_kategori_id', $kategori);
			$this->db->where('inventory.gudang_id', $kategori);
			$this->db->group_by('id', 'asc');
			
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where($wheres);
		if ($status_so_history == null)
		{
			$this->db->where('item_kategori_id', 1);
			$this->db->where('inventory.gudang_id', 1);
			$this->db->or_where('item_kategori_id', 2);
			$this->db->where('inventory.gudang_id', 2);
			$this->db->or_where('inventory.gudang_id', 3);
			$this->db->group_by('id', 'asc');


		} else {

			$this->db->where('item_kategori_id', $kategori);
			$this->db->where('inventory.gudang_id', $kategori);
			$this->db->group_by('id', 'asc');
			
		}

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_nomor_penjualan()
	{
		$format = "SELECT MAX(SUBSTRING(`no_penjualan`,12,3)) AS max_nomor_penjualan FROM `penjualan_obat` WHERE SUBSTRING(`no_penjualan`,5,4) = DATE_FORMAT(NOW(), '%y%m')";	
		return $this->db->query($format);
	}

	public function delete_inventory_id($inventory_id)
	{

		$this->db->where('inventory_id', $inventory_id);
		$this->db->delete($this->_table);		
	}

}

