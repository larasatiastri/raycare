<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pegawai_m extends MY_Model {

	protected $_table      = 'realnew_core.pegawai';
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
		'pegawai.nik'                    => 'nik',
		'pegawai.nama'                   => 'nama',
		'pegawai.gelar'                  => 'gelar',
		'pegawai.status_pegawai'                   => 'status',
		'pegawai.status_aktivasi'                   => 'status_aktivasi',
		'jabatan.nama'              	=> 'jabatan',
		'divisi.nama'				=> 'divisi',
		'pg.nama'				=> 'pic',
		'cabang.nama'				=> 'cabang',
		'pegawai.tempat_lahir'           => 'tempat_lahir',
		'pegawai.tanggal_mulai_kerja'           => 'tanggal_mulai_kerja',
		'pegawai.tanggal_keluar'           => 'tanggal_keluar',
		'pegawai.tanggal_akhir'           => 'tanggal_akhir',
		'pegawai.gender'                 => 'gender',
		'pegawai.is_active'              => 'active',
		'pegawai.url_photo'              => 'url_photo',
		'pegawai.jabatan_id'              => 'jabatan_id',
		'pegawai.divisi_id'              => 'divisi_id',
		'pegawai.cabang_id'              => 'cabang_id',
	);
	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns_report = array(
		//column di table  => alias
		'pegawai.id'                     => 'id',
		'pegawai.nik'                    => 'nik',
		'pegawai.nama'                   => 'nama',
		'pegawai.gelar'                  => 'gelar',
		'pegawai.status_pegawai'                   => 'status',
		'pegawai.status_aktivasi'                   => 'status_aktivasi',
		'jabatan.nama'              	=> 'jabatan',
		'divisi.nama'				=> 'divisi',
		'cabang.nama'				=> 'cabang'
	);

	protected $datatable_columns_fee = array(
		//column di table  => alias
		'pegawai.id'                     => 'id',
		'pegawai.nik'                    => 'nik',
		'pegawai.nama'                   => 'nama',
		'pegawai.gelar'                  => 'gelar',
		'pegawai.jabatan_id'              => 'jabatan_id',
		'pegawai.divisi_id'              => 'divisi_id',
		'pegawai.cabang_id'              => 'cabang_id',
		'jabatan.nama'              	=> 'jabatan',
		'cabang.nama'				=> 'cabang'
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

		$join1 = array('pegawai pg', $this->_table.'.pic_id = pg.id','left');
		$join2 = array('jabatan', $this->_table.'.jabatan_id = jabatan.id','left');
		$join3 = array('divisi', 'pegawai.divisi_id = divisi.id','left');
		$join4 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		
		
		 
		// $join_tables = array($join1, $join2, $join3);
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pegawai.id';
		$params['sort_dir'] = 'asc';
  
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
		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_uang_makan()
	{

		$join1 = array('pegawai pg', $this->_table.'.pic_id = pg.id','left');
		$join2 = array('jabatan', $this->_table.'.jabatan_id = jabatan.id','left');
		$join3 = array('divisi', 'pegawai.divisi_id = divisi.id','left');
		$join4 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		
		
		 
		// $join_tables = array($join1, $join2, $join3);
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pegawai.id';
		$params['sort_dir'] = 'asc';
  
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_aktivasi', 1);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_aktivasi', 1);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_aktivasi', 1);
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

	public function get_datatable_uang_gaji()
	{

		$join1 = array('pegawai pg', $this->_table.'.pic_id = pg.id','left');
		$join2 = array('jabatan', $this->_table.'.jabatan_id = jabatan.id','left');
		$join3 = array('divisi', 'pegawai.divisi_id = divisi.id','left');
		$join4 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		
		
		 
		// $join_tables = array($join1, $join2, $join3);
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pegawai.id';
		$params['sort_dir'] = 'asc';
  
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_aktivasi', 1);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_aktivasi', 1);
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_aktivasi', 1);
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
	public function get_datatable_tidak_hadir($tanggal, $cabang_id, $jabatan_id=NULL, $pegawai_id=NULL )
	{
		$join1 = array('jabatan', $this->_table.'.jabatan_id = jabatan.id','left');
		$join2 = array('divisi', $this->_table.'.divisi_id = divisi.id','left');
		$join3 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		
		
		// $join_tables = array($join1, $join2, $join3);
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_report);
		$params['sort_by'] = 'pegawai.id';
		$params['sort_dir'] = 'asc';
  
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		if($cabang_id != NULL && $cabang_id != 0){
			$this->db->where('pegawai.cabang_id', $cabang_id);
		}if($jabatan_id != NULL && $jabatan_id != 0){
			$this->db->where('pegawai.jabatan_id', $jabatan_id);
		}if($pegawai_id != NULL && $pegawai_id != 0){
			$this->db->where('pegawai.id', $pegawai_id);
		}
		$this->db->where($this->_table.'.status_aktivasi', 1);
		$this->db->where("pegawai.id NOT IN (SELECT pegawai_id FROM pegawai_absensi WHERE date(pegawai_absensi.waktu) = '$tanggal')");

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		if($cabang_id != NULL && $cabang_id != 0){
			$this->db->where('pegawai.cabang_id', $cabang_id);
		}if($jabatan_id != NULL && $jabatan_id != 0){
			$this->db->where('pegawai.jabatan_id', $jabatan_id);
		}if($pegawai_id != NULL && $pegawai_id != 0){
			$this->db->where('pegawai.id', $pegawai_id);
		}
		$this->db->where($this->_table.'.status_aktivasi', 1);
		$this->db->where("pegawai.id NOT IN (SELECT pegawai_id FROM pegawai_absensi WHERE date(pegawai_absensi.waktu) = '$tanggal')");
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		if($cabang_id != NULL && $cabang_id != 0){
			$this->db->where('pegawai.cabang_id', $cabang_id);
		}if($jabatan_id != NULL && $jabatan_id != 0){
			$this->db->where('pegawai.jabatan_id', $jabatan_id);
		}if($pegawai_id != NULL && $pegawai_id != 0){
			$this->db->where('pegawai.id', $pegawai_id);
		}
		$this->db->where($this->_table.'.status_aktivasi', 1);
		$this->db->where("pegawai.id NOT IN (SELECT pegawai_id FROM pegawai_absensi WHERE date(pegawai_absensi.waktu) = '$tanggal')");

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_report as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_report;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}


		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_fee_dokter()
	{

		$join1 = array('jabatan', $this->_table.'.jabatan_id = jabatan.id','left');
		$join2 = array('cabang', $this->_table.'.cabang_id = cabang.id','left');
		
		 
		// $join_tables = array($join1, $join2, $join3);
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_fee);
		$params['sort_by'] = 'pegawai.id';
		$params['sort_dir'] = 'asc';
  
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_aktivasi', 1);
		$this->db->where('pegawai.jabatan_id', config_item('dokter_pj_id'));
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_aktivasi', 1);
		$this->db->where('pegawai.jabatan_id', config_item('dokter_pj_id'));
		// $this->db->where('pasien_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status_aktivasi', 1);
		$this->db->where('pegawai.jabatan_id', config_item('dokter_pj_id'));
		// $this->db->where('pasien_alamat.is_primary', 1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_fee as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_fee;
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

	public function get_nik()
	{
		$format = "SELECT MAX(SUBSTRING(`nik`,5,4)) AS max_nik FROM `pegawai` WHERE SUBSTRING(`nik`,1,4) = DATE_FORMAT(NOW(), '%Y')";	
		return $this->db->query($format);
	}

}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */