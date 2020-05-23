<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rujukan_m extends MY_Model {

	protected $_table      = 'rujukan';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'rujukan.id'                   => 'id',
		'rujukan.pasien_id'            => 'pasien_id',
		'rujukan.cabang_asal_id'       => 'cabang_asal_id',
		'rujukan.poliklinik_asal_id'   => 'poliklinik_asal_id',
		'rujukan.cabang_tujuan_id' => 'cabang_tujuan_id',
		'rujukan.poliklinik_tujuan_id' => 'poliklinik_tujuan_id',
		'rujukan.poliklinik_tujuan'    => 'poliklinik_tujuan',
		'rujukan.dokter_id'            => 'dokter_id',
		'rujukan.tanggal_dirujuk'      => 'tanggal_dirujuk',
		'rujukan.tanggal_rujukan'      => 'tanggal_rujukan',
		'rujukan.subjek'               => 'subjek',
		'rujukan.keterangan'           => 'keterangan',
		'rujukan.selesai'              => 'selesai',
		'rujukan.subjek'               => 'subjek',
		'a.nama'                       => 'nama_poli',
		'b.nama'                       => 'nama_poli_asal',
		'pasien.nama'                  => 'nama_pasien',
		'pasien.no_member'             => 'no_member',
		'pasien.url_photo'             => 'photo_pasien',
		'rujukan.is_active'            => 'is_active'
	);

	protected $datatable_columns_pilih_rujukan = array(
		//column di table  => alias
		'rujukan.id'			=> 'id',
		'rujukan.nama'			=> 'nama',
		'rujukan.tempat_lahir'	=> 'tempat_lahir',
		'rujukan.tanggal_lahir'	=> 'tanggal_lahir',
		'rujukan.is_active'		=> 'active',
		'rujukan_alamat.alamat'	=> 'alamat'
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

		$join1 = array('poliklinik a', $this->_table.'.poliklinik_tujuan_id = a.id','left');
		$join2 = array('poliklinik b', $this->_table.'.poliklinik_asal_id = b.id','left');
		$join3 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'is_active', 1);
		// $this->db->where('rujukan_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('rujukan_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where('rujukan_alamat.is_primary', 1);

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
// die_dump($result->records);
		return $result; 
	}

	public function get_datatable_history_rujukan($status, $date)
	{

		$join1 = array('poliklinik a', $this->_table.'.poliklinik_tujuan_id = a.id','left');
		$join2 = array('poliklinik b', $this->_table.'.poliklinik_asal_id = b.id','left');
		$join3 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');

		$join_tables = array($join1, $join2, $join3);

		$status_rujukan = '';

		if($status == 1) 
		{
			$status_rujukan = 1;

		} elseif ($status == 2) 
		{

			$status_rujukan = 0;

		} elseif ($status == 3)
		{
		
			$status_rujukan = 0;
		
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);

		

		if($status == 0) 
		{

			$this->db->where('left(tanggal_rujukan, 10) <', $date);
			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', $status_rujukan);
			$this->db->or_where('left(tanggal_rujukan, 10) >=', $date);
			$this->db->where('selesai', $status_rujukan);
			$this->db->where('rujukan.is_active', 1);
			$this->db->or_where('left(tanggal_rujukan, 10) =', $date);
			$this->db->where('selesai', $status_rujukan);
			$this->db->where('rujukan.is_active', 1);
			$this->db->or_where('left(tanggal_rujukan, 10) !=', $date);
			$this->db->where('selesai', $status_rujukan);
			$this->db->where('rujukan.is_active', 1);


		} elseif ($status == 1) {

			$this->db->where('left(tanggal_rujukan, 10) >=', $date);
			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_rujukan, 10) <', $date);
			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_rujukan, 10) >=', $date);
			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', 0);

		}

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		

		if($status == 0) 
		{

			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', 1);
			$this->db->or_where('selesai', 0);
			$this->db->where('rujukan.is_active', 1);

		} elseif ($status == 1) {

			$this->db->where('left(tanggal_rujukan, 10) >=', $date);
			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_rujukan, 10) <', $date);
			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_rujukan, 10) >=', $date);
			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', 0);

		}
		// $this->db->where('rujukan_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		

		if($status == 0) 
		{

			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', 1);
			$this->db->or_where('selesai', 0);
			$this->db->where('rujukan.is_active', 1);

		} elseif ($status == 1) {

			$this->db->where('left(tanggal_rujukan, 10) >=', $date);
			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_rujukan, 10) <', $date);
			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_rujukan, 10) >=', $date);
			$this->db->where('rujukan.is_active', 1);
			$this->db->where('selesai', 0);

		}
		// $this->db->where('rujukan_alamat.is_primary', 1);

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

	public function get_datatable_pilih_rujukan()
	{

		$join1 = array('rujukan_alamat', $this->_table.'.id = rujukan_alamat.rujukan_id','left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_pilih_rujukan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_pilih_rujukan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_pilih_rujukan;
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
	public function get_no_member()
	{
		$format = "SELECT MAX(SUBSTRING(`no_member`,16,4)) AS max_no_member FROM `rujukan` WHERE SUBSTRING(`no_member`,14,2) = DATE_FORMAT(NOW(), '%y')";	
		return $this->db->query($format);
	}
}

/* End of file user_level_m.php */
/* Location: ./application/models/user_level_m.php */