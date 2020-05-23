<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pilih_user_m extends MY_Model
{
	protected $_table        = 'user';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'user_level_id', 
		'cabang_id', 
		'nama',
		'username', 
		'password', 
		'bahasa', 
		'url'
		
	);

	private $_fillable_edit = array(
		'nama',
		'username', 
		'user_level_id', 
		'cabang_id', 
		'bahasa', 
		'url',
		'created_by',
		'created_date',
		'modified_by',
		'modified_date'
		
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'user.id'				 	 => 'id_user',
		'user.nama'				 	 => 'nama_user',
		'user_level.nama'			 => 'nama_user_level',
		'user_level.id'				 => 'user_level_id',
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns_user = array(
		//column di table  => alias
		'user.id'            => 'id', 
		'user.nama'  => 'nama_lengkap', 
		'user.username'      => 'username',
		'user.is_active'     => 'active',
		'user.user_level_id' => 'user_level_id',
	);
	
	function __construct ()
	{
		parent::__construct();
	}

	
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{	
		$join1 = array("user_level", $this->_table . '.user_level_id = user_level.id', 'left');
		$join2 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');

		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);

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

	public function get_datatable_staff()
	{
		$join1 = array("user_level", $this->_table . '.user_level_id = user_level.id', 'left');
		$join2 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');

		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('user_level_id', 12);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('user_level_id', 12);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('user_level_id', 12);

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
		$params = $this->datatable_param($this->datatable_columns_user);

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
		foreach ($this->datatable_columns_user as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_user;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_pilih_user($cabang_id)
	{
		$datatable_columns_pilih_user = array(
		//column di table  => alias
		'user.id'				=> 'id',
		'user.user_level_id'	=> 'user_level_id',
		'user.cabang_id'		=> 'cabang_id',
		'user.nama'				=> 'nama',
		'user.username'			=> 'username',
		'user.is_active'		=> 'active',
		'user_level.nama'		=> 'nama_user_level',
		'cabang.id'				=> 'cabang_id',
		);

		$join1 = array("user_level", $this->_table . '.user_level_id = user_level.id', 'left');
		$join2 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_user);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.cabang_id', $cabang_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.cabang_id', $cabang_id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.cabang_id', $cabang_id);

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

	public function get_datatable_pilih_gudang_orang($cabang_id)
	{
		$datatable_columns_pilih_user = array(
		//column di table  => alias
		'user.id'				=> 'id',
		'user.user_level_id'	=> 'user_level_id',
		'user.cabang_id'		=> 'cabang_id',
		'user.nama'				=> 'nama',
		'user.username'			=> 'username',
		'user.is_active'		=> 'active',
		'user_level.nama'		=> 'nama_user_level',
		'cabang.id'				=> 'cabang_id',
		'gudang_orang.id'				=> 'user_id',
		'gudang_orang.nama'				=> 'nama_gudang_orang',
		);

		$join1 = array("user_level", $this->_table . '.user_level_id = user_level.id', 'left');
		$join2 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');
		$join3 = array("gudang_orang", 'user.id = gudang_orang.id', 'inner');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_user);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.cabang_id', $cabang_id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.cabang_id', $cabang_id);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.cabang_id', $cabang_id);

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

	public function get_datatable_pilih_user_setoran()
	{
		$datatable_columns_pilih_user = array(
		//column di table  => alias
		'kasir_titip_uang.id'		 => 'kasir_titip_uang_id',
		'kasir_titip_uang.tanggal'	 => 'tanggal',
		'kasir_titip_uang.user_id'	 => 'user_id',
		'kasir_titip_uang.tipe_user' => 'tipe_user', 
		'kasir_titip_uang.is_lunas'	 => 'is_lunas',
		'user.id'				 	 => 'id_user',
		'user.nama'				 	 => 'nama_user',
		'user.is_active'			 => 'is_active',
		'user_level.nama'			 => 'nama_user_level',
		'cabang.id'					 => 'cabang_id',
		);

		$join1 = array("kasir_titip_uang", 'user.id = kasir_titip_uang.id', 'left');
		$join2 = array("user_level", 'user.user_level_id = user_level.id', 'left');
		$join3 = array("cabang", 'user.cabang_id = cabang.id', 'left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_user);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('user.user_level_id', 5);
		$this->db->or_where('user.user_level_id', 13);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('user.user_level_id', 5);
		$this->db->or_where('user.user_level_id', 13);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('user.user_level_id', 5);
		$this->db->or_where('user.user_level_id', 13);
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

	public function get_datatable_pilih_user_po()
	{
		$datatable_columns_pilih_user = array(
		//column di table  => alias
		'user.id'				 	 => 'id_user',
		'user.nama'				 	 => 'nama_user',
		'user_level.nama'			 => 'nama_user_level',
		'user_level.id'				 => 'user_level_id',
		'user.cabang_id'				 => 'cabang_id',
		);

		$join1 = array("user_level", 'user.user_level_id = user_level.id', 'left');
		$join2 = array("cabang", 'user.cabang_id = cabang.id', 'left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_user);
		$params['sort_by'] = 'user.id';
		// die_dump($params);
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('user.is_active', 1);
		// $this->db->where('user.user_level_id', 5);
		// $this->db->or_where('user.user_level_id', 13);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('user.is_active', 1);
		// $this->db->where('user.user_level_id', 5);
		// $this->db->or_where('user.user_level_id', 13);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('user.is_active', 1);
		// $this->db->where('user.user_level_id', 5);
		// $this->db->or_where('user.user_level_id', 13);
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
	/**
	 * [fillable_changepass description]
	 * @return [type] [description]
	 */
	public function fillable_changepass()
	{
		return $this->_fillable_changepass;
	}

	function validate_password($id, $password)
	{
		$where = array(
			"id"	=> $id,
			"password"	=> $this->hash($password)
		);
		
		$user = $this->get_by($where,true);
		
		if(is_object($user) && $user->id) return true;
		
		return false;
	}

	public function get_data_active()
	{
		$this->db->where('is_active',1);
		$this->db->order_by('id','asc');
		return $this->db->get($this->_table);
	}

	public function get_data_dokter()
	{
		$this->db->where('user_level_id', 10);
		return $this->db->get($this->_table);
	}

	public function get_data_by_id($id)
	{
		$this->db->where('id', $id);
		return $this->db->get($this->_table);
	}

	////begin/////
	///digunakan di controller keuangan/arus_kas_kasir///////////

	public function get_kasir_user($level_id)
	{
		$sql = "SELECT
				user_level.nama AS nama_user_level,
				`user`.id AS user_id,
				`user`.nama AS nama_user
				FROM
				`user`
				LEFT JOIN user_level ON `user`.user_level_id = user_level.id
				WHERE
				`user`.user_level_id = $level_id";

		return $this->db->query($sql);
	}

	///end///
	/////////
	

	//////////////////////digunakan di view persetujuan permintaan PO///////////////////////
	
	public function get_nama($disetujui_oleh)
	{

		$sql = "SELECT
				`user`.nama,
				`user`.id,
				persetujuan_permintaan_pembelian.order_permintaan_pembelian_id,
				persetujuan_permintaan_pembelian.persetujuan_permintaan_pembelian_id,
				persetujuan_permintaan_pembelian.tipe_permintaan,
				persetujuan_permintaan_pembelian.keterangan,
				persetujuan_permintaan_pembelian.disetujui_oleh
				FROM
				`user`
				LEFT JOIN persetujuan_permintaan_pembelian ON `user`.id = persetujuan_permintaan_pembelian.disetujui_oleh
				WHERE
				`user`.id = $disetujui_oleh
				";

		return $this->db->query($sql);

	}

}

/* End of file user.php */
/* Location: ./application/models/pembelian/permintaan_po/pilih_user_m.php */