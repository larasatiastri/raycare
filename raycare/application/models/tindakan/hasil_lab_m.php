<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hasil_lab_m extends MY_Model {

	protected $_table        = 'hasil_lab';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'kode',
		'nama',
		'keterangan', 
		'tipe_akun', 
	);

	private $_fillable_edit = array(
		'kode',
		'nama',
		'keterangan', 
		'tipe_akun', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'hasil_lab.id'   			=> 'id', 
		'hasil_lab.tanggal'  	=> 'tanggal', 
		'hasil_lab.no_hasil_lab'  	=> 'no_hasil_lab', 
		'laboratorium_klinik.nama'  => 'nama_lab_klinik',
		'pasien.nama'  				=> 'nama_pasien',
		'hasil_lab.usia'         	=> 'usia', 
		'hasil_lab.dokter'         	=> 'dokter', 
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($jenis)
	{	
		$join1 = array('laboratorium_klinik','hasil_lab.laboratorium_klinik_id = laboratorium_klinik.id');
		$join2 = array('pasien','hasil_lab.pasien_id = pasien.id');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']	= 'hasil_lab.created_date';
		$params['sort_dir']	= 'DESC';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('hasil_lab.is_active', 1);
		$this->db->where('hasil_lab.jenis', $jenis);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('hasil_lab.is_active', 1);
		$this->db->where('hasil_lab.jenis', $jenis);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('hasil_lab.is_active', 1);
		$this->db->where('hasil_lab.jenis', $jenis);

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

	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id, kode FROM cabang_');
		
		return $query->row();
		return $this->db->get($this->_table);
	}

	public function get_max_id_hasil_lab()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `hasil_lab` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_last_hasil($tanggal, $pasien_id)
	{	
		$this->db->select('hasil_lab.*, laboratorium_klinik.nama');
		$this->db->join('laboratorium_klinik', $this->_table.'.laboratorium_klinik_id = laboratorium_klinik.id');
		$this->db->where('pasien_id', $pasien_id);
		$this->db->where('date(tanggal) <=', $tanggal);
		$this->db->order_by('tanggal', 'desc');
		return $this->db->get($this->_table);
	}

	public function get_last_hasil_prev($tanggal, $pasien_id)
	{	
		$this->db->select('hasil_lab.*, laboratorium_klinik.nama');
		$this->db->join('laboratorium_klinik', $this->_table.'.laboratorium_klinik_id = laboratorium_klinik.id');
		$this->db->where('pasien_id', $pasien_id);
		$this->db->where('date(tanggal) <', $tanggal);
		$this->db->order_by('tanggal', 'desc');
		return $this->db->get($this->_table);
	}

	public function get_last_hasil_next($tanggal, $pasien_id)
	{	
		$this->db->select('hasil_lab.*, laboratorium_klinik.nama');
		$this->db->join('laboratorium_klinik', $this->_table.'.laboratorium_klinik_id = laboratorium_klinik.id');
		$this->db->where('pasien_id', $pasien_id);
		$this->db->where('date(tanggal) >', $tanggal);
		$this->db->order_by('tanggal', 'asc');
		return $this->db->get($this->_table);
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

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */