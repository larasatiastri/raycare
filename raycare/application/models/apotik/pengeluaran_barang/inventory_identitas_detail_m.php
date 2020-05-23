<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_identitas_detail_m extends MY_Model {

	protected $_table        = 'inventory_identitas_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
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
		$join_tables = array();

		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where($wheres);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where($wheres);

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

	public function update_stock_identitas($jumlah, $id, $modified_by, $modified_date){
		$format = "UPDATE inventory_identitas SET jumlah = $jumlah, modified_by = $modified_by, modified_date = '$modified_date' WHERE inventory_identitas_id =  $id";
		return $this->db->query($format);
	}

	public function delete_inventory_identitas($id)
	{
		$format = "DELETE FROM inventory_identitas WHERE inventory_identitas_id = $id";

		return $this->db->query($format);
	}
	
	public function get_id()
	{
		$format = "SELECT MAX(inventory_identitas_id) as id FROM inventory_identitas";

		return $this->db->query($format);
	}

	public function get_id_detail()
	{
		$format = "SELECT MAX(inventory_identitas_detail_id) as id FROM inventory_identitas_detail";

		return $this->db->query($format);
	}

	public function save_identitas_detail($inventory_identitas_detail_id, $inventory_identitas_id, $identitas_id, $judul, $value)
	{
		$created_by = $this->session->userdata('user_id');
		$created_date = date('Y-m-d H:i:s');

		$format = "INSERT INTO inventory_identitas_detail(inventory_identitas_detail_id, inventory_identitas_id, identitas_id, judul, value, created_by, created_date)VALUES($inventory_identitas_detail_id, $inventory_identitas_id, $identitas_id, '$judul', '$value', '$created_by', '$created_date')";

		return $this->db->query($format);
	}

	public function get_search_item($search)
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
					inventory.pmb_id as pmb_id,
					inventory.inventory_id as inventory_id
					FROM
					inventory_identitas
					LEFT JOIN inventory_identitas_detail ON inventory_identitas.inventory_identitas_id = inventory_identitas_detail.inventory_identitas_id
					LEFT JOIN identitas ON inventory_identitas_detail.identitas_id = identitas.id
					LEFT JOIN inventory ON inventory_identitas.inventory_id = inventory.inventory_id
					WHERE
					 	inventory_identitas_detail.`value` LIKE '%$search%'
					GROUP BY inventory_identitas_id
					";

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

	public function get_data_identitas($identitas_detail_id, $identitas_id)
	{
		$format = "SELECT inventory_identitas_detail.*, inventory_identitas.jumlah
					FROM
					inventory_identitas_detail
					JOIN inventory_identitas ON inventory_identitas_detail.inventory_identitas_id = inventory_identitas.inventory_identitas_id
					WHERE inventory_identitas_detail.inventory_identitas_id IN ($identitas_detail_id) 
					ORDER BY inventory_identitas_detail.identitas_id ASC, inventory_identitas_detail.value ASC";
		return $this->db->query($format);
	}
}

