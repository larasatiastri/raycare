<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penerima_cabang_m extends MY_Model {

	protected $_table        = 'cabang';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'cabang.id'					=> 'id',
		'cabang.tipe'				=> 'tipe',
		'cabang.nama'				=> 'nama_cabang',
		'cabang.penanggung_jawab'	=> 'penanggung_jawab',
		'cabang_alamat.alamat'		=> 'alamat',
		'cabang_email.email'		=> 'email',
		'inf_lokasi.nama_kelurahan'	=> 'kelurahan',
		'inf_lokasi.nama_kecamatan'	=> 'kecamatan',
		'inf_lokasi.nama_kabupatenkota'	=> 'kota',
		'inf_lokasi.nama_propinsi'	=> 'propinsi',		
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
		$join1 = array('cabang_alamat', $this->_table.'.id = cabang_alamat.cabang_id','left');
		$join3 = array('cabang_email', $this->_table.'.id = cabang_email.cabang_id', 'left');
		$join4 = array('inf_lokasi', 'cabang_alamat.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$join_tables = array($join1, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('cabang_alamat.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('cabang_alamat.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('cabang_alamat.is_primary',1);
		$this->db->where($this->_table.'.is_active',1);

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

	public function get_data($cabang_id)
	{
		$this->db->select('cabang.*, cabang_alamat.alamat, cabang_alamat.rt_rw, cabang_alamat.kode_pos, inf_lokasi.nama_kelurahan, inf_lokasi.nama_kecamatan, inf_lokasi.nama_kabupatenkota, inf_lokasi.nama_propinsi, cabang_email.email, cabang_telepon.nomor');
		$this->db->join('cabang_alamat', $this->_table.'.id = cabang_alamat.cabang_id','left');
		$this->db->join('cabang_email', $this->_table.'.id = cabang_email.cabang_id', 'left');
		$this->db->join('cabang_telepon', $this->_table.'.id = cabang_telepon.cabang_id', 'left');
		$this->db->join('inf_lokasi', 'cabang_alamat.kode_lokasi = inf_lokasi.lokasi_kode','left');
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.id',$cabang_id);
		$this->db->where('cabang_alamat.is_primary',1);
		$this->db->where('cabang_telepon.is_primary',1);
		$this->db->where('cabang_email.is_primary',1);

		return $this->db->get($this->_table);

	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */