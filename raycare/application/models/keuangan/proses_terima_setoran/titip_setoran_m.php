<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Titip_setoran_m extends MY_Model {

	protected $_table      = 'titip_setoran';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'titip_setoran.id'					=> 'id',
		'titip_setoran.tanggal'				=> 'tanggal',
		'titip_setoran.penerima_id'			=> 'penerima_id',
		'titip_setoran.rupiah'				=> 'rupiah',
		'titip_setoran.rupiah_bon'				=> 'rupiah_bon',
		'titip_setoran.subjek'				=> 'subjek',
		'titip_setoran.keterangan'			=> 'keterangan',
		'titip_setoran.status'				=> 'status',
		'titip_setoran.created_by'			=> 'created_by',
		'a.nama'								=> 'nama_user_created',
		'b.nama'								=> 'nama_user',
		'user_level.nama'						=> 'nama_user_level',
		'gudang_orang.nama'						=> 'nama_user_gudang',
	);

	protected $datatable_columns_pilih_titip_setoran = array(
		//column di table  => alias
		'titip_setoran.id'			=> 'id',
		'titip_setoran.nama'			=> 'nama',
		'titip_setoran.tempat_lahir'	=> 'tempat_lahir',
		'titip_setoran.tanggal_lahir'	=> 'tanggal_lahir',
		'titip_setoran.is_active'		=> 'active',
		'titip_setoran_alamat.alamat'	=> 'alamat'
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($status)
	{

		$join1 = array('user a', $this->_table.'.created_by = a.id','left');
		$join2 = array('user b', $this->_table.'.penerima_id = b.id','left');
		$join3 = array('user_level', $this->_table.'.penerima_id = user_level.id','left');
		$join4 = array('gudang_orang', $this->_table.'.penerima_id = gudang_orang.id','left');
		$join_tables = array($join1, $join2, $join3, $join4);

		$status_titip_setoran = '';

		if ($status == 1) {

			$status_titip_setoran = 1;
		
		} elseif ($status == 2) {

			$status_titip_setoran = 2;
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal';
		$params['sort_dir'] = 'DESC';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// $this->db->where('left(tanggal, 7) =', $date);
		
		if ($status == 1) {

			$this->db->where($this->_table.'.status', 1);

		} elseif ($status == 2) {
			
			$this->db->where($this->_table.'.status', 2);

		}


		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
		if ($status == 1) {

			$this->db->where($this->_table.'.status', 1);

		} elseif ($status == 2) {
			
			$this->db->where($this->_table.'.status', 2);

		}

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		
		if ($status == 1) {

			$this->db->where($this->_table.'.status', 1);

		} elseif ($status == 2) {
			
			$this->db->where($this->_table.'.status', 2);

		}

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

	public function get_datatable_history()
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
		// $this->db->where('left(tanggal, 7) =', $date);
		$this->db->where($this->_table.'is_lunas', 1);
		// $this->db->where('titip_setoran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// $this->db->where('left(tanggal, 7) =', $date);
		$this->db->where($this->_table.'.is_lunas', 1);
		// $this->db->where('titip_setoran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		// $this->db->where('left(tanggal, 7) =', $date);
		$this->db->where($this->_table.'.is_lunas', 1);
		// $this->db->where('titip_setoran_alamat.is_primary', 1);

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

	public function get_datatable_history_titip_setoran($status, $date)
	{

		$join1 = array('poliklinik a', $this->_table.'.poliklinik_tujuan_id = a.id','left');
		$join2 = array('poliklinik b', $this->_table.'.poliklinik_asal_id = b.id','left');
		$join3 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');

		$join_tables = array($join1, $join2, $join3);

		$status_titip_setoran = '';

		if($status == 1) 
		{
			$status_titip_setoran = 1;

		} elseif ($status == 2) 
		{

			$status_titip_setoran = 0;

		} elseif ($status == 3)
		{
		
			$status_titip_setoran = 0;
		
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);

		

		if($status == 0) 
		{

			$this->db->where('left(tanggal_titip_setoran, 10) <', $date);
			$this->db->where('titip_setoran.is_active', 1);
			$this->db->where('selesai', $status_titip_setoran);
			$this->db->or_where('left(tanggal_titip_setoran, 10) >=', $date);
			$this->db->where('selesai', $status_titip_setoran);
			$this->db->where('titip_setoran.is_active', 1);
			$this->db->or_where('left(tanggal_titip_setoran, 10) =', $date);
			$this->db->where('selesai', $status_titip_setoran);
			$this->db->where('titip_setoran.is_active', 1);
			$this->db->or_where('left(tanggal_titip_setoran, 10) !=', $date);
			$this->db->where('selesai', $status_titip_setoran);
			$this->db->where('titip_setoran.is_active', 1);


		} elseif ($status == 1) {

			$this->db->where('left(tanggal_titip_setoran, 10) >=', $date);
			$this->db->where('titip_setoran.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_titip_setoran, 10) <', $date);
			$this->db->where('titip_setoran.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_titip_setoran, 10) >=', $date);
			$this->db->where('titip_setoran.is_active', 1);
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

			$this->db->where('titip_setoran.is_active', 1);
			$this->db->where('selesai', 1);
			$this->db->or_where('selesai', 0);
			$this->db->where('titip_setoran.is_active', 1);

		} elseif ($status == 1) {

			$this->db->where('left(tanggal_titip_setoran, 10) >=', $date);
			$this->db->where('titip_setoran.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_titip_setoran, 10) <', $date);
			$this->db->where('titip_setoran.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_titip_setoran, 10) >=', $date);
			$this->db->where('titip_setoran.is_active', 1);
			$this->db->where('selesai', 0);

		}
		// $this->db->where('titip_setoran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		

		if($status == 0) 
		{

			$this->db->where('titip_setoran.is_active', 1);
			$this->db->where('selesai', 1);
			$this->db->or_where('selesai', 0);
			$this->db->where('titip_setoran.is_active', 1);

		} elseif ($status == 1) {

			$this->db->where('left(tanggal_titip_setoran, 10) >=', $date);
			$this->db->where('titip_setoran.is_active', 1);
			$this->db->where('selesai', 1);

		} elseif ($status == 2) {

			$this->db->where('left(tanggal_titip_setoran, 10) <', $date);
			$this->db->where('titip_setoran.is_active', 1);
			$this->db->where('selesai', 0);
			

		} elseif ($status == 3) {

			$this->db->where('left(tanggal_titip_setoran, 10) >=', $date);
			$this->db->where('titip_setoran.is_active', 1);
			$this->db->where('selesai', 0);

		}
		// $this->db->where('titip_setoran_alamat.is_primary', 1);

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

	public function get_datatable_pilih_user()
	{
		$datatable_columns_pilih_user = array(
		//column di table  => alias
		'titip_setoran.id'		 => 'titip_setoran_id',
		'titip_setoran.tanggal'	 => 'tanggal',
		'titip_setoran.user_id'	 => 'user_id',
		'titip_setoran.tipe_user' => 'tipe_user', 
		'titip_setoran.is_lunas'	 => 'is_lunas',
		'user.nama'				 	 => 'nama_user',
		'user.is_active'			 => 'is_active',
		'user_level.nama'			 => 'nama_user_level',
		'cabang.id'					 => 'cabang_id',
		);

		$join1 = array("user", $this->_table . '.user_id = user.id', 'left');
		$join2 = array("user_level", 'user.user_level_id = user_level.id', 'left');
		$join3 = array("cabang", 'user.cabang_id = cabang.id', 'left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_user);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_user', 1);
		$this->db->where($this->_table.'.is_lunas', 0);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_user', 1);
		$this->db->where($this->_table.'.is_lunas', 0);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_user', 1);
		$this->db->where($this->_table.'.is_lunas', 0);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_user as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_user;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable_pilih_gudang_orang()
	{
		$datatable_columns_pilih_user = array(
		//column di table  => alias
		'titip_setoran.id'		=> 'titip_setoran_id',
		'titip_setoran.tanggal'	=> 'tanggal',
		'titip_setoran.user_id'	=> 'user_id',
		'titip_setoran.tipe_user'	=> 'tipe_user',
		'titip_setoran.is_lunas'	=> 'is_lunas',
		'user.nama'					=> 'nama_user',
		'user.is_active'			=> 'is_active',
		'user_level.nama'			=> 'nama_user_level',
		'gudang_orang.id'			=> 'user_id',
		'gudang_orang.nama'			=> 'nama_gudang_orang',
		);

		$join1 = array("user", $this->_table . '.user_id = user.id', 'left');
		$join2 = array("user_level", 'user.user_level_id = user_level.id', 'left');
		$join3 = array("gudang_orang", 'user.id = gudang_orang.id', 'left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_user);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tipe_user', 2);
		$this->db->where($this->_table.'.is_lunas', 0);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tipe_user', 2);
		$this->db->where($this->_table.'.is_lunas', 0);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tipe_user', 2);
		$this->db->where($this->_table.'.is_lunas', 0);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_user as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_user;
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
	
	function get_sisa($user_id,$tipe)
	{
		// $this->db->where('id',$id);
		$this->db->where('user_id',$user_id);
		$this->db->where('tipe_user',$tipe);
		$this->db->where('is_lunas',0);
		$this->db->order_by('tanggal', 'asc');
		return $this->db->get($this->_table); 
	} 


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
		$format = "SELECT MAX(SUBSTRING(`no_member`,16,4)) AS max_no_member FROM `titip_setoran` WHERE SUBSTRING(`no_member`,14,2) = DATE_FORMAT(NOW(), '%y')";	
		return $this->db->query($format);
	}
}

/* End of file titip_setoran_m.php */
/* Location: ./application/models/titip_setoran_m.php */