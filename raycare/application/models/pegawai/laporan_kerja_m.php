<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_kerja_m extends MY_Model {

	protected $_table      = 'core.laporan_kerja';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'nama',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'laporan_kerja.id'              => 'id',
		'laporan_kerja.tanggal'         => 'tanggal',
		'laporan_kerja.issue'             => 'issue',
		'laporan_kerja.master_kpi_id'             => 'master_kpi_id',
		'master_kpi.nama'             => 'nama_kpi',
		'laporan_kerja.action'   		=> 'action',
		'laporan_kerja.progress'   		=> 'progress',
		'laporan_kerja.deadline'		=> 'deadline',
		'pegawai.nama'           		=> 'nama_pegawai',
		'laporan_kerja.status'			=> 'status',
		'laporan_kerja.status_verif'	=> 'status_verif',
		'laporan_kerja.tanggal_verif'	=> 'tanggal_verif',
		'laporan_kerja.created_by'	=> 'created_by',
		
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($stat)
	{

		$user_id      = $this->session->userdata('user_id');
        $data_pegawai = $this->pegawai_user_m->get_by(array('user_id' => $user_id), true);

        $pegawai = $this->pegawai_m->get_by(array('id' => $data_pegawai->pegawai_id), true);

		$join1 = array('master_kpi', $this->_table.'.master_kpi_id = master_kpi.id','left');
		$join2 = array('pegawai', $this->_table.'.pegawai_id = pegawai.id','left');		
		 
		// $join_tables = array($join1, $join2, $join3);
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in($this->_table.'.status_verif', $stat);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.divisi_id', $pegawai->divisi_id);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in($this->_table.'.status_verif', $stat);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.divisi_id', $pegawai->divisi_id);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in($this->_table.'.status_verif', $stat);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.divisi_id', $pegawai->divisi_id);
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
	public function get_datatable_pending($stat)
	{

		$user_id      = $this->session->userdata('user_id');
        $data_pegawai = $this->pegawai_user_m->get_by(array('user_id' => $user_id), true);

        $pegawai = $this->pegawai_m->get_by(array('id' => $data_pegawai->pegawai_id), true);

		$join1 = array('master_kpi', $this->_table.'.master_kpi_id = master_kpi.id','left');
		$join2 = array('pegawai', $this->_table.'.pegawai_id = pegawai.id','left');		
		 
		// $join_tables = array($join1, $join2, $join3);
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in($this->_table.'.status_verif', $stat);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.pegawai_id', $pegawai->id);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in($this->_table.'.status_verif', $stat);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.pegawai_id', $pegawai->id);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in($this->_table.'.status_verif', $stat);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.pegawai_id', $pegawai->id);
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
	public function get_datatable_verif($stat, $pic_id)
	{

		$join1 = array('master_kpi', $this->_table.'.master_kpi_id = master_kpi.id','left');
		$join2 = array('pegawai', $this->_table.'.pegawai_id = pegawai.id','left');		
		 
		// $join_tables = array($join1, $join2, $join3);
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in($this->_table.'.status_verif', $stat);
		$this->db->where_in($this->_table.'.pic_id', $pic_id);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->group_by($this->_table.'.master_kpi_id');
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in($this->_table.'.status_verif', $stat);
		$this->db->where_in($this->_table.'.pic_id', $pic_id);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->group_by($this->_table.'.master_kpi_id');
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in($this->_table.'.status_verif', $stat);
		$this->db->where_in($this->_table.'.pic_id', $pic_id);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->group_by($this->_table.'.master_kpi_id');
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

	public function get_id()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `laporan_kerja` WHERE SUBSTRING(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y')";	
		return $this->db->query($format);
	}

	

}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */