<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fee_dokter_m extends MY_Model {

	protected $_table      = 'realnew_core.fee_dokter';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'nama',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pegawai.id'                     => 'id',
		'pegawai.nik'                     => 'nik',
		'pegawai.nama'                   => 'nama',
		'pegawai.gelar'                   => 'gelar',
		'jabatan.nama'              => 'jabatan',
		'cabang.nama'				=> 'cabang',
		'pegawai.url_photo'              => 'url_photo',
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($bulan, $tahun, $cabang_id, $jabatan_id, $divisi_id, $pegawai_id)
	{
		
		$join1 = array('pegawai', $this->_table.'.dokter_id = pegawai.id','left');
		$join2 = array('jabatan', 'pegawai.jabatan_id = jabatan.id','left');
		$join3 = array('cabang', 'pegawai.cabang_id = cabang.id','left');
		
	
		// $join_tables = array($join1, $join2, $join3);
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pegawai.id';
		$params['sort_dir'] = 'asc';
  
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pegawai.is_active', 1);
		$this->db->where('pegawai.status_aktivasi', 1);
		$this->db->where('pegawai.jabatan_id', config_item('dokter_pj_id'));
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pegawai.is_active', 1);
		$this->db->where('pegawai.status_aktivasi', 1);
		$this->db->where('pegawai.jabatan_id', config_item('dokter_pj_id'));
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pegawai.is_active', 1);
		$this->db->where('pegawai.status_aktivasi', 1);
		$this->db->where('pegawai.jabatan_id', config_item('dokter_pj_id'));
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


		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_active()
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
		
		return $result; 
	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}

	public function get_data_is_active()
    {
        $this->db->where("is_active", 1);
        return $this->db->get($this->_table);
    }

    public function get_data_subjek($type)
	{
		$format = "SELECT id as id, nama as nama
				   FROM subjek
				   WHERE tipe = $type";

		return $this->db->query($format);
	}
	public function get_id()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,10,4)) AS max_id FROM realnew_core.`fee_dokter` WHERE SUBSTRING(`id`,5,4) = DATE_FORMAT(NOW(), '%m%y')";	
		return $this->db->query($format);
	}

	public function get_current_salary($pegawai_id)
	{
		$now = date('Y-m-d');
		$SQL = "SELECT * FROM realnew_core.fee_dokter WHERE dokter_id = $pegawai_id AND date(tanggal) <= '$now' AND is_active = 1 ORDER BY tanggal DESC, created_date DESC LIMIT 1";

		return $this->db->query($SQL);
	}

	public function get_current_salary_month($pegawai_id, $month, $year)
	{
		$SQL = "SELECT * FROM fee_dokter WHERE dokter_id = $pegawai_id AND MONTH(tanggal) <= '$month' AND YEAR(tanggal) = '$year' AND is_active = 1 ORDER BY tanggal DESC LIMIT 1";

		return $this->db->query($SQL);
	}

}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */