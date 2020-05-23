<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gudang_m extends MY_Model {

	protected $_table        = 'gudang';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'gudang.id'         => 'id', 
		'gudang.nama'   	=> 'nama_gudang'
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

	public function get_data_gudang()
	{
		$format = "SELECT id as id, informasi as nama_gudang
					FROM gudang
					WHERE is_active = 1";

		return $this->db->query($format);
	}

	public function get_gudang_user($user_id)
	{
		$sql = "SELECT
					gudang_user.user_id,
					gudang_user.gudang_id,
					gudang.id AS id,
					gudang.nama AS nama,
					gudang.is_active
				FROM
					gudang_user
				RIGHT JOIN gudang ON gudang_user.gudang_id = gudang.id
				WHERE
					gudang_user.user_id = $user_id AND
					gudang.is_active = 1";

		return $this->db->query($sql);
	}

	///////////////digunakan di controller request_item///////////////////
	///
	public function get_id_gudang($id)
	{
		$sql = "SELECT
					*
				FROM
					gudang
				WHERE
					is_active = 1
				AND id != $id
				ORDER BY
					id";
		return $this->db->query($sql);
	}

	public function get_alamat($id){

		$this->db->select('gudang.nama AS nama, gudang.alamat AS alamat, gudang.rt_rw AS rt_rw, gudang.kode_pos AS kode_pos, 
							gudang.kode_lokasi AS kode_lokasi, inf_lokasi.lokasi_nama AS lokasi_nama, inf_lokasi.nama_propinsi AS nama_propinsi, 
							inf_lokasi.nama_kabupatenkota AS nama_kabupatenkota, inf_lokasi.nama_kecamatan AS nama_kecamatan, inf_lokasi.nama_kelurahan AS nama_kelurahan');
		
		$this->db->join('inf_lokasi', $this->_table.'.kode_lokasi = inf_lokasi.lokasi_kode');

		$this->db->where('id', $id);
		$this->db->where('is_active', 1);

		return $this->db->get($this->_table)->result_array();
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */