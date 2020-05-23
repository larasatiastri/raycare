<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kasir_terima_uang_m extends MY_Model {

	protected $_table      = 'kasir_terima_uang';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'kasir_terima_uang.tanggal'				=> 'tanggal',
		'kasir_terima_uang.user_id'				=> 'user_id',
		'kasir_terima_uang.rupiah'				=> 'rupiah',
		'kasir_terima_uang.subjek'				=> 'subjek',
		'kasir_terima_uang.keterangan'			=> 'keterangan',
		'kasir_terima_uang.created_by'			=> 'created_by',
		'kasir_terima_uang.tipe_user'			=> 'tipe_user',
		'a.nama'								=> 'nama_user_created',
		'b.nama'								=> 'nama_user',
		'user_level.nama'						=> 'nama_user_level',
		'gudang_orang.nama'						=> 'nama_user_gudang',
	);

	protected $datatable_columns_pilih_kasir_terima_uang = array(
		//column di table  => alias
		'kasir_terima_uang.id'			=> 'id',
		'kasir_terima_uang.nama'			=> 'nama',
		'kasir_terima_uang.tempat_lahir'	=> 'tempat_lahir',
		'kasir_terima_uang.tanggal_lahir'	=> 'tanggal_lahir',
		'kasir_terima_uang.is_active'		=> 'active',
		'kasir_terima_uang_alamat.alamat'	=> 'alamat'
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($date)
	{

		$join1 = array('user a', $this->_table.'.created_by = a.id','left');
		$join2 = array('user b', $this->_table.'.user_id = b.id','left');
		$join3 = array('user_level', $this->_table.'.user_id = user_level.id','left');
		$join4 = array('gudang_orang', $this->_table.'.user_id = gudang_orang.id','left');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('left(tanggal, 7) =', $date);
		// $this->db->where('kasir_titip_uang_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('left(tanggal, 7) =', $date);
		// $this->db->where('kasir_titip_uang_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('left(tanggal, 7) =', $date);
		// $this->db->where('kasir_titip_uang_alamat.is_primary', 1);

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

	public function get_datatable_history_kasir_terima_uang($status, $date)
	{

		$join1 = array('poliklinik a', $this->_table.'.poliklinik_tujuan_id = a.id','left');
		$join2 = array('poliklinik b', $this->_table.'.poliklinik_asal_id = b.id','left');
		$join3 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');

		$join_tables = array($join1, $join2, $join3);

		$status_kasir_terima_uang = '';

		if($status == 1) 
		{
			$status_kasir_terima_uang = 1;

		} elseif ($status == 2) 
		{

			$status_kasir_terima_uang = 0;

		} elseif ($status == 3)
		{
		
			$status_kasir_terima_uang = 0;
		
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);

		

		if($status == 0) 
		{

			$this->db->where('left(tanggal_kasir_terima_uang, 10) <', $date);
			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->where('selesai', $status_kasir_terima_uang);
			$this->db->or_where('left(tanggal_kasir_terima_uang, 10) >=', $date);
			$this->db->where('selesai', $status_kasir_terima_uang);
			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->or_where('left(tanggal_kasir_terima_uang, 10) =', $date);
			$this->db->where('selesai', $status_kasir_terima_uang);
			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->or_where('left(tanggal_kasir_terima_uang, 10) !=', $date);
			$this->db->where('selesai', $status_kasir_terima_uang);
			$this->db->where('kasir_terima_uang.is_active', 1);


		} elseif ($status == 1) {

			$this->db->where('left(tanggal_kasir_terima_uang, 10) >=', $date);
			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_kasir_terima_uang, 10) <', $date);
			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_kasir_terima_uang, 10) >=', $date);
			$this->db->where('kasir_terima_uang.is_active', 1);
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

			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->where('selesai', 1);
			$this->db->or_where('selesai', 0);
			$this->db->where('kasir_terima_uang.is_active', 1);

		} elseif ($status == 1) {

			$this->db->where('left(tanggal_kasir_terima_uang, 10) >=', $date);
			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_kasir_terima_uang, 10) <', $date);
			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_kasir_terima_uang, 10) >=', $date);
			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->where('selesai', 0);

		}
		// $this->db->where('kasir_terima_uang_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		

		if($status == 0) 
		{

			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->where('selesai', 1);
			$this->db->or_where('selesai', 0);
			$this->db->where('kasir_terima_uang.is_active', 1);

		} elseif ($status == 1) {

			$this->db->where('left(tanggal_kasir_terima_uang, 10) >=', $date);
			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_kasir_terima_uang, 10) <', $date);
			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_kasir_terima_uang, 10) >=', $date);
			$this->db->where('kasir_terima_uang.is_active', 1);
			$this->db->where('selesai', 0);

		}
		// $this->db->where('kasir_terima_uang_alamat.is_primary', 1);

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

	public function get_datatable_pilih_kasir_terima_uang()
	{

		$join1 = array('kasir_terima_uang_alamat', $this->_table.'.id = kasir_terima_uang_alamat.kasir_terima_uang_id','left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_pilih_kasir_terima_uang);

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
		foreach ($this->datatable_columns_pilih_kasir_terima_uang as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_pilih_kasir_terima_uang;
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
		$format = "SELECT MAX(SUBSTRING(`no_member`,16,4)) AS max_no_member FROM `kasir_terima_uang` WHERE SUBSTRING(`no_member`,14,2) = DATE_FORMAT(NOW(), '%y')";	
		return $this->db->query($format);
	}
}

/* End of file titip_terima_uang_m.php */
/* Location: ./application/models/titip_terima_uang_m.php */