<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_identitas_m extends MY_Model {

	protected $_table        = 'inventory_identitas';
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

	public function get_jumlah($inventory_id)
	{
		$format = "SELECT SUM(jumlah) as jumlah FROM inventory_identitas WHERE inventory_id = $inventory_id";

		return $this->db->query($format);
	}

	public function save_identitas($inventory_identitas_id, $inventory_id, $jumlah)
	{
		$created_by = $this->session->userdata('user_id');
		$created_date = date('Y-m-d H:i:s');

		$format = "INSERT INTO inventory_identitas(inventory_identitas_id, inventory_id, jumlah, created_by, created_date)VALUES($inventory_identitas_id, $inventory_id, $jumlah, $created_by, '$created_date')";

		return $this->db->query($format);
	}

	public function get_data_by($id)
	{
		$wheres = '';
		foreach ($id as $inventory_identitas_id) {
        	$wheres .= $inventory_identitas_id.',';
        }

        $wheres = rtrim($wheres,',');

		$sql = "SELECT * FROM inventory_identitas WHERE inventory_identitas_id in ($wheres) GROUP BY inventory_id";

		return $this->db->query($sql);
	}

	public function get_data_inventory($id)
	{
        $wheres = rtrim($id,',');

		$sql = "SELECT * FROM inventory_identitas WHERE inventory_id IN ($wheres)";

		return $this->db->query($sql);
	}

	public function get_stok($inventory_identitas_id)
	{
		$format = "SELECT jumlah FROM inventory_identitas WHERE inventory_identitas_id = $inventory_identitas_id";

		return $this->db->query($format);
	}
}

