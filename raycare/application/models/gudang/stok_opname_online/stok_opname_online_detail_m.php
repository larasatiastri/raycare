<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stok_opname_online_detail_m extends MY_Model {

	protected $_table        = 'stok_opname_online_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'kode',
		'nama',
		'alamat', 
		'nomor_telepon1', 
		'nomor_telepon2', 
		'nomor_fax', 		
	);

	private $_fillable_edit = array(
		'kode',
		'nama',
		'alamat', 
		'nomor_telepon1', 
		'nomor_telepon2', 
		'nomor_fax', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'cabang_.id'         => 'id', 
		'cabang_.kode'   => 'kode', 
		'cabang_.nama'   => 'nama', 
		'cabang_.alamat'   => 'alamat',
		'cabang_.nomor_telepon1'     => 'nomor_telepon1',
		'cabang_.nomor_telepon2'     => 'nomor_telepon2',
		'cabang_.nomor_fax'     => 'nomor_fax',
		'cabang_.is_active'     => 'is_active'
		
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

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);

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

	
	public function save_data($jumlah, $so_id)
	{
		$format = "UPDATE stok_opname_online_detail SET jumlah_input = $jumlah WHERE stok_opname_online_id = $so_id";

		return $this->db->query($format);
	}

	public function delete_so($so_id)
	{
		$format = "DELETE FROM stok_opname_online_detail WHERE stok_opname_online_id = $so_id";

		return $this->db->query($format);
	}

	public function update_identitas($stok_opname_online_detail_id, $jumlah_sistem, $jumlah)
	{
		$format = "UPDATE stok_opname_online_identitas SET jumlah_input = $jumlah WHERE stok_opname_online_detail_id = $stok_opname_online_detail_id";

		return $this->db->query($format);
	}


	public function save_identitas_detail($inventory_identitas_detail_id, $inventory_identitas_id, $identitas_id, $judul, $value)
	{
		$format = "INSERT INTO inventory_identitas_detail(inventory_identitas_detail_id, inventory_identitas_id, identitas_id, judul, value)VALUES($inventory_identitas_detail_id, $inventory_identitas_id, $identitas_id, '$judul', '$value')";

		return $this->db->query($format);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/stok_opname_online/stok_opname_online_m.php */