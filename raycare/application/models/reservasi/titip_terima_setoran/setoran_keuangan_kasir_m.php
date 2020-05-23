<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setoran_keuangan_kasir_m extends MY_Model
{
	protected $_table        = 'setoran_keuangan_kasir';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'setoran_keuangan_kasir.id'         		=> 'id', 
		'setoran_keuangan_kasir.tanggal' 			=> 'tanggal', 
		'setoran_keuangan_kasir.user_id'    		=> 'user_id',
		'setoran_keuangan_kasir_level.total_setor'  => 'total_setor',
		'setoran_keuangan_kasir_level.status'  		=> 'status',
		'setoran_keuangan_kasir_level.keterangan'   => 'keterangan',
		// 'cabang.nama'        => 'nama_cabang',
		// 'setoran_keuangan_kasir.is_active'    => 'active'
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns_setoran_keuangan_kasir = array(
		//column di table  => alias
		'setoran_keuangan_kasir.id'            => 'id', 
		'setoran_keuangan_kasir.nama'  => 'nama_lengkap', 
		'setoran_keuangan_kasir.setoran_keuangan_kasirname'      => 'setoran_keuangan_kasirname',
		'setoran_keuangan_kasir.is_active'     => 'active',
		'setoran_keuangan_kasir.setoran_keuangan_kasir_level_id' => 'setoran_keuangan_kasir_level_id',
	);
	
	function __construct ()
	{
		parent::__construct();
	}

	public function login ($data = array())
	{
		$select = array(
			'a.*', 
			'b.nama AS nama_level', 
			'c.url AS dashboard_url'
			);
		
		$setoran_keuangan_kasir = $this->db
		    ->select($select)
			->from($this->_table . ' a')
			->join('setoran_keuangan_kasir_level b', 'b.id = a.setoran_keuangan_kasir_level_id', 'left')
			->join('menu c', 'c.id = b.dashboard_url', 'left')
			->where('a.setoran_keuangan_kasirname', $data['setoran_keuangan_kasirname'])
			->where('a.password', $this->hash($data['password']))
			->where('a.is_active', 1)
			->get()->row();

		if (count($setoran_keuangan_kasir)){
			// Log in setoran_keuangan_kasir
			$sess_data = array(
				'session'       => md5(rand().time()), 
				'setoran_keuangan_kasir_id'       => $setoran_keuangan_kasir->id, 
				'setoran_keuangan_kasirname'      => $setoran_keuangan_kasir->setoran_keuangan_kasirname,
				'nama_lengkap'  => $setoran_keuangan_kasir->nama, //($setoran_keuangan_kasir_rows->setoran_keuangan_kasirnama_lengkap == '' || $setoran_keuangan_kasir_rows->setoran_keuangan_kasirnama_lengkap == NULL) ? 'Unknown setoran_keuangan_kasir. Please update your profile.' : $setoran_keuangan_kasir_rows->setoran_keuangan_kasirnama_lengkap,
				'level_id'      => $setoran_keuangan_kasir->setoran_keuangan_kasir_level_id, 
				'nama_level'    => $setoran_keuangan_kasir->nama_level, 
				'cabang_id'     => $setoran_keuangan_kasir->cabang_id,
				'language'      => $setoran_keuangan_kasir->bahasa,
				'url'			=> $setoran_keuangan_kasir->url,
				'dashboard_url' => $setoran_keuangan_kasir->dashboard_url,
				'logged_in'     => TRUE,
			);
			
			$this->session->set_setoran_keuangan_kasirdata($sess_data);

			// store setoran_keuangan_kasir log
			$setoran_keuangan_kasir_log = array(
				'setoran_keuangan_kasir_id'        => $this->session->setoran_keuangan_kasirdata("setoran_keuangan_kasir_id"),
				'remote_address' => $this->session->setoran_keuangan_kasirdata("ip_address"),
				'setoran_keuangan_kasir_agent'     => $this->session->setoran_keuangan_kasirdata("setoran_keuangan_kasir_agent"),
				'last_login'     => date("Y-m-d H:i:s")
				);

			$log_id = $this->setoran_keuangan_kasir_log_m->save($setoran_keuangan_kasir_log);

			$this->session->set_setoran_keuangan_kasirdata(array('log_id' => $log_id));
			
		}

	}

	public function logout ()
	{
		// delete setoran_keuangan_kasir menufile
		$this->menu_m->delete_menu_file();

		// update setoran_keuangan_kasir log
		$log_id = $this->session->setoran_keuangan_kasirdata('log_id');
		if ($log_id) {
			$setoran_keuangan_kasir_log = array('last_logout' => date("Y-m-d H:i:s"));
			$this->setoran_keuangan_kasir_log_m->save($setoran_keuangan_kasir_log, $log_id);
		}
		
		// destroy session
		$this->session->sess_destroy();
	}
	/**
	 * [level_id description]
	 * @return [type] [description]
	 */
	public function level_id()
	{
		return (int) $this->session->setoran_keuangan_kasirdata('level_id');
	}
	/**
	 * [logged_in description]
	 * @return [type] [description]
	 */
	public function logged_in ()
	{
		return (bool) $this->session->setoran_keuangan_kasirdata('logged_in');
	}
	
	

	/**
	 * hash sha512 string+encryp 
	 * @params  [type] $string [description]
	 * @return [type]         [description]
	 */
	public function hash ($string)
	{
		$encryption_key = config_item('password_encryption_key');
		return hash('sha512', $string . $encryption_key );

		// using sha1 dulu, maestrobyte memakainya
		//return sha1($string);
	}
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{	
		$join1 = array("setoran_keuangan_kasir_level", $this->_table . '.setoran_keuangan_kasir_level_id = setoran_keuangan_kasir_level.id', 'left');
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
		$join1 = array("setoran_keuangan_kasir_level", $this->_table . '.setoran_keuangan_kasir_level_id = setoran_keuangan_kasir_level.id', 'left');
		$join2 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');

		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('setoran_keuangan_kasir_level_id', 12);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('setoran_keuangan_kasir_level_id', 12);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('setoran_keuangan_kasir_level_id', 12);

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
		$params = $this->datatable_param($this->datatable_columns_setoran_keuangan_kasir);

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
		foreach ($this->datatable_columns_setoran_keuangan_kasir as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_setoran_keuangan_kasir;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_pilih_setoran_keuangan_kasir($user_id)
	{
		$datatable_columns_pilih_setoran_keuangan_kasir = array(
		//column di table  => alias
		'setoran_keuangan_kasir.id'				=> 'id',
		'setoran_keuangan_kasir.tanggal'		=> 'tanggal',
		'setoran_keuangan_kasir.user_id'		=> 'user_id',
		'setoran_keuangan_kasir.total_setor'	=> 'total_setor',
		'setoran_keuangan_kasir.status'			=> 'status',
		'setoran_keuangan_kasir.keterangan'		=> 'keterangan',
		'user_level.nama'						=> 'nama_user_level',
		'a.id'									=> 'id_user',
		'a.nama'								=> 'nama_user_created',
		'b.nama'								=> 'nama_user',
		'b.is_active'							=> 'active',
		'titip_setoran.subjek'					=> 'subjek',

		);

		$join1 = array('user a', $this->_table.'.created_by = a.id','left');
		$join2 = array('user b', $this->_table.'.user_id = b.id','left');
		$join3 = array("user_level", 'b.user_level_id = user_level.id', 'left');
		$join4 = array('titip_setoran', $this->_table.'.user_id = titip_setoran.id','left');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_setoran_keuangan_kasir);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('b.is_active', 1);
		$this->db->where($this->_table.'.user_id', $user_id);
		$this->db->where($this->_table.'.status', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('b.is_active', 1);
		$this->db->where($this->_table.'.user_id', $user_id);
		$this->db->where($this->_table.'.status', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('b.is_active', 1);
		$this->db->where($this->_table.'.user_id', $user_id);
		$this->db->where($this->_table.'.status', 1);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_pilih_setoran_keuangan_kasir as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_setoran_keuangan_kasir;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}

	public function get_datatable_pilih_gudang_orang($cabang_id)
	{
		$datatable_columns_pilih_setoran_keuangan_kasir = array(
		//column di table  => alias
		'setoran_keuangan_kasir.id'				=> 'id',
		'setoran_keuangan_kasir.setoran_keuangan_kasir_level_id'	=> 'setoran_keuangan_kasir_level_id',
		'setoran_keuangan_kasir.cabang_id'		=> 'cabang_id',
		'setoran_keuangan_kasir.nama'				=> 'nama',
		'setoran_keuangan_kasir.setoran_keuangan_kasirname'			=> 'setoran_keuangan_kasirname',
		'setoran_keuangan_kasir.is_active'		=> 'active',
		'setoran_keuangan_kasir_level.nama'		=> 'nama_setoran_keuangan_kasir_level',
		'cabang.id'				=> 'cabang_id',
		'gudang_orang.id'				=> 'setoran_keuangan_kasir_id',
		'gudang_orang.nama'				=> 'nama_gudang_orang',
		);

		$join1 = array("setoran_keuangan_kasir_level", $this->_table . '.setoran_keuangan_kasir_level_id = setoran_keuangan_kasir_level.id', 'left');
		$join2 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');
		$join3 = array("gudang_orang", 'setoran_keuangan_kasir.id = gudang_orang.id', 'inner');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_pilih_setoran_keuangan_kasir);

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
		foreach ($datatable_columns_pilih_setoran_keuangan_kasir as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_pilih_setoran_keuangan_kasir;
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
		
		$setoran_keuangan_kasir = $this->get_by($where,true);
		
		if(is_object($setoran_keuangan_kasir) && $setoran_keuangan_kasir->id) return true;
		
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
		$this->db->where('setoran_keuangan_kasir_level_id', 10);
		return $this->db->get($this->_table);
	}
}

/* End of file setoran_keuangan_kasir.php */
/* Location: ./application/models/setoran_keuangan_kasir.php */