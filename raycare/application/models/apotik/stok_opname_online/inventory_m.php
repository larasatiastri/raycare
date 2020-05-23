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


	public function get_item_identitas($id, $search)
	{
		if(empty($search))
		{
			$wheres = 'inventory_identitas.inventory_identitas_id = '.$id;
		}
		else
		{
			$wheres = 'inventory_identitas.inventory_identitas_id = '.$id.' AND 
				inventory_identitas_detail.`value` LIKE "%' .$search. '%" ';
		}

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
					 	$wheres
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

	public function update_identitas($identitas_id, $inventory_id, $jumlah)
	{
		$modified_by = $this->session->userdata('user_id');
		$modified_date = date('Y-m-d H:i:s');
		$format = "UPDATE inventory_identitas SET jumlah = $jumlah, modified_by = $modified_by, modified_date = '$modified_date' WHERE inventory_identitas_id = $identitas_id AND inventory_id = $inventory_id";

		return $this->db->query($format);
	}

	public function update_inventory($inventory_id, $jumlah)
	{
		$user = $this->session->userdata('user_id');
		$modified_date = date('Y-m-d H:i:s');
		$format = "UPDATE inventory SET jumlah = $jumlah, modified_by = $user, modified_date = '$modified_date' WHERE inventory_id = $inventory_id";

		return $this->db->query($format);
	}

	public function get_id()
	{
		$format = "SELECT MAX(inventory_id) as id FROM inventory";

		return $this->db->query($format);
	}

	public function save_item($data_inventory_id, $gudang_id, $item_id, $item_satuan_id, $jumlah, $date_item)
	{
		$created_by = $this->session->userdata('user_id');
		$created_date = date('Y-m-d H:i:s');

		$format = "INSERT INTO inventory(inventory_id, gudang_id, item_id, item_satuan_id, jumlah, tanggal_datang, created_by, created_date)VALUES($data_inventory_id, $gudang_id, $item_id, '$item_satuan_id', '$jumlah', '$date_item', '$created_by', '$created_date')";
		
		return $this->db->query($format);
	}

	public function save_data($jumlah, $inventory_id)
	{
		$user = $this->session->userdata('user_id');
		$modified_date = date('Y-m-d H:i:s');
		$format = "UPDATE inventory SET jumlah = $jumlah, modified_by = $user, modified_date = '$modified_date' WHERE inventory_id = $inventory_id";

		return $this->db->query($format);
	}

	public function get_data_by($id)
	{

		$wheres = '';
		foreach ($id as $inventory_id) {
        	$wheres .= $inventory_id.',';
        }

        $wheres = rtrim($wheres,',');

		$sql = "SELECT * FROM inventory WHERE inventory_id in ($wheres)";

		return $this->db->query($sql);
	}

	public function get_data_inventory($item_id,$item_satuan_id,$gudang_id='',$search)
	{
		$SQL = "SELECT
					inventory.inventory_id,
					item.nama,
					item_satuan.nama AS nama_satuan,
					SUM(inventory.jumlah) AS jumlah,
					inventory.bn_sn_lot, 
					inventory.expire_date
				FROM
					inventory
				JOIN item ON inventory.item_id = item.id
				JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id
				WHERE
					inventory.item_id = $item_id
				AND inventory.gudang_id = '$gudang_id'
				AND inventory.item_satuan_id = $item_satuan_id
				AND inventory.bn_sn_lot LIKE '%$search%'
				GROUP BY
					inventory.item_id, inventory.item_satuan_id,inventory.bn_sn_lot, inventory.expire_date
				ORDER BY inventory.expire_date ASC";

		return $this->db->query($SQL);
	}

	public function get_data_inventory_satuan($item_id,$item_satuan_id,$gudang_id)
	{
		$SQL = "SELECT
					inventory.inventory_id,
					item.nama,
					item_satuan.nama AS nama_satuan,
					SUM(inventory.jumlah) AS jumlah,
					inventory.bn_sn_lot, 
					inventory.expire_date
				FROM
					inventory
				JOIN item ON inventory.item_id = item.id
				JOIN item_satuan ON inventory.item_satuan_id = item_satuan.id
				WHERE
					inventory.item_id = $item_id
				AND inventory.gudang_id = '$gudang_id'
				AND inventory.item_satuan_id = $item_satuan_id
				GROUP BY
					inventory.item_id, inventory.item_satuan_id
				ORDER BY inventory.expire_date ASC";

		return $this->db->query($SQL);
	}

}

