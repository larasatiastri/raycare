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
		'SUM(inventory.jumlah)'         => 'jumlah',
		'inventory.harga_beli'     => 'harga_beli',
		'item.kode'                => 'item_kode',
		'item.nama'                => 'item_nama',
		'item_satuan.nama'         => 'satuan',
		'item_harga.harga'		   => 'harga'
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($gudang)
	{		
		$join2 = array('item', 'inventory.item_id = item.id', 'left');
		$join3 = array('item_satuan', 'inventory.item_satuan_id = item_satuan.id', 'left');
		$join4 = array('item_harga', 'item_satuan.id = item_harga.item_satuan_id', 'left');
		$join_tables = array($join2, $join3, $join4);

		// get params dari input postnya datatable

		
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('inventory.gudang_id', $gudang);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('inventory.gudang_id', $gudang);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('inventory.gudang_id', $gudang);
		$this->db->group_by('inventory.item_id');
		$this->db->group_by('inventory.item_satuan_id');

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

	public function update_jumlah_inventory($jumlah,$modified_by, $modified_date, $id){
		$format = "UPDATE inventory SET jumlah = $jumlah, modified_by = $modified_by, modified_date = '$modified_date' WHERE inventory_id =  $id";
		return $this->db->query($format);
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
		$format = "SELECT MAX(id) FROM inventory";

		return $this->db->query($format);
	}

	public function insert_inventory($id, $gudang_id, $item_id, $item_satuan_id, $jumlah, $tanggal){

		$id_user = $this->session->userdata('user_id');
		$format="INSERT INTO inventory(id, gudang_id, item_id, item_satuan_id, jumlah, tanggal_datang, created_by, created_date)
				 VALUE ($id, $gudang_id, $item_id, $item_satuan_id, $jumlah, '$tanggal', $id_user, '$tanggal')";

		return $this->db->query($format);
	}

	public function get_harga_terbaru($item_satuan_id)
	{
		$date_now = date('Y-m-d');
		$format = "SELECT
						MAX(harga) as harga_terbaru
					FROM
						item_harga,
						item_satuan
					WHERE
						item_satuan.id = item_harga.item_satuan_id
					AND item_harga.tanggal <= '$date_now'
					AND item_harga.item_satuan_id = $item_satuan_id";
		return $this->db->query($format);
	}

	public function get_item_stock($gudang_id, $item_id, $item_satuan_id)
	{
		$format = "SELECT inventory.inventory_id,
					inventory.gudang_id,
					inventory.item_id,
					inventory.item_satuan_id,
					SUM(inventory.jumlah) as jumlah
					FROM
					inventory
					WHERE
					inventory.gudang_id = $gudang_id AND
					inventory.item_id = $item_id AND
					inventory.item_satuan_id = $item_satuan_id";
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

	public function get_nomor_penjualan()
	{
		$format = "SELECT MAX(SUBSTRING(`no_penjualan`,12,3)) AS max_nomor_penjualan FROM `penjualan_obat` WHERE SUBSTRING(`no_penjualan`,5,4) = DATE_FORMAT(NOW(), '%y%m')";	
		return $this->db->query($format);
	}

}

