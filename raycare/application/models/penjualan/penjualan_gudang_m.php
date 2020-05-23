<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_gudang_m extends MY_Model {

	protected $_table        = 'gudang';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		
		//column di table  => alias
		'gudang.nama'                   => 'nama_gudang',
		'gudang.alamat'                 => 'alamat',
		'inf_lokasi.nama_kelurahan'     => 'kelurahan',
		'inf_lokasi.nama_kecamatan'     => 'kecamatan',
		'inf_lokasi.nama_kabupatenkota' => 'kota',
		'gudang.id'                     => 'id',
		
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable($cabang_id)
	{	
		$join1 = array('inf_lokasi', 'gudang.kode_lokasi = inf_lokasi.lokasi_kode', 'left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('cabang_id', $cabang_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('cabang_id', $cabang_id);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('cabang_id', $cabang_id);

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

		return $result; 
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
