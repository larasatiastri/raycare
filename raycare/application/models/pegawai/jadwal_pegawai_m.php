<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jadwal_pegawai_m extends MY_Model {

	protected $_table      = 'jadwal_pegawai';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'nama',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{

		$join1 = array('jadwal_pegawai', $this->_table.'.jadwal_pegawai_id = jadwal_pegawai.id','left');
		$join2 = array('divisi', 'jadwal_pegawai.divisi_id = divisi.id','left');
		$join3 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		
		
		 
		// $join_tables = array($join1, $join2, $join3);
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('pasien_alamat.is_primary', 1);

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

	public function get_max_id()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `jadwal_pegawai` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data_jadwal($tanggal, $jabatan_id, $pegawai_id)
	{
		$format = "SELECT jadwal_pegawai.id as id_jadwal, jadwal_pegawai.tanggal, jadwal_pegawai.shift, jadwal_pegawai.pekerjaan, jadwal_pegawai.lokasi_kerja, pegawai.id, pegawai.nama FROM `jadwal_pegawai` JOIN pegawai ON jadwal_pegawai.pegawai_id = pegawai.id
				   WHERE date(jadwal_pegawai.tanggal) = '$tanggal' AND jadwal_pegawai.jabatan_id = $jabatan_id AND jadwal_pegawai.pegawai_id != $pegawai_id AND jadwal_pegawai.is_active = 1;";
		return $this->db->query($format);		   
	}

}

/* End of file jadwal_pegawai_m.php */
/* Location: ./application/models/jadwal_pegawai_m.php */