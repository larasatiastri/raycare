<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_m extends MY_Model
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

	private $_fillable_changepass = array(
		'password'
		);

	public $rules_login = array(
		'username' => array(
			'field' => 'username', 
			'label' => 'Username', 
			'rules' => 'trim|required|xss_clean'
		), 
		'password' => array(
			'field' => 'password', 
			'label' => 'Password', 
			'rules' => 'trim|required'
		)
	);

	public $rules = array(
		'nama' => array(
			'field' => 'nama', 
			'label' => 'Nama Lengkap', 
			'rules' => 'trim|required|min_length[3]|xss_clean'
		), 
		'username' => array(
			'field' => 'username', 
			'label' => 'Username', 
			'rules' => 'trim|required|min_length[3]|callback__unique_username|xss_clean'
		), 
		'password' => array(
			'field' => 'password', 
			'label' => 'Password', 
			'rules' => 'trim|min_length[6]|matches[password_confirm]'
		),
		'password_confirm' => array(
			'field' => 'password_confirm', 
			'label' => 'Confirm password', 
			'rules' => 'trim|min_length[6]|matches[password]'
		),
		'user_level_id' => array(
			'field' => 'user_level_id', 
			'label' => 'User Level', 
			'rules' => 'trim|required|xss_clean'
		), 
		'cabang_id' => array(
			'field' => 'cabang_id', 
			'label' => 'Cabang', 
			'rules' => 'trim|required|xss_clean'
		),
		'bahasa' => array(
			'field' => 'bahasa', 
			'label' => 'bahasa', 
			'rules' => 'trim|required|xss_clean'
		), 
		
	);

	public $rules_change_password = array(
		
		'password' => array(
			'field' => 'password', 
			'label' => 'Password', 
			'rules' => 'trim|min_length[6]|matches[password_confirm]'
		),
		'password_confirm' => array(
			'field' => 'password_confirm', 
			'label' => 'Konfirmasi Password', 
			'rules' => 'trim|min_length[6]|matches[password]'
		)

	);	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'user.id'         => 'id', 
		'user.url'       => 'url', 
		'user.nama'       => 'nama_lengkap', 
		'user.username'   => 'username',
		'user_level.nama' => 'nama_level',
		'cabang.nama'     => 'nama_cabang',
		'user.is_active'  => 'active'
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

	public function login ($data = array())
	{
		$select = array(
			'a.*', 
			'b.nama AS nama_level',
			'b.dashboard_url',
			'CONCAT(c.url,b.dashboard_url) as dashboard'
		);
		
		$user = $this->db
		    ->select($select)
			->from($this->_table . ' a')
			->join('user_level b', 'b.id = a.user_level_id', 'left')
			->join('cabang c', 'b.cabang_id = c.id', 'left')
			->where('a.username', $data['username'])
			->where('a.password', $this->hash($data['password']))
			->where('a.is_active', 1)
			->get()->row();

		if (count($user)){
			// Log in user
			$sess_data = array(
				'session'       => md5(rand().time()), 
				'user_id'       => $user->id, 
				'username'      => $user->username,
				'nama_lengkap'  => $user->nama, //($user_rows->Usernama_lengkap == '' || $user_rows->Usernama_lengkap == NULL) ? 'Unknown User. Please update your profile.' : $user_rows->Usernama_lengkap,
				'level_id'      => $user->user_level_id, 
				'nama_level'    => $user->nama_level, 
				// 'cabang_id'     => $user->cabang_id,
				'language'      => $user->bahasa,
				'url'			=> $user->url,
				'url_login'		=> base_url(),
				'dashboard_url' => $user->dashboard,
				'logged_in'     => TRUE,
			);

			$data_cabang = $this->cabang_m->get_by(array('url' => base_url(), 'is_active' => 1), true);
			$sess_data['cabang_id'] = $data_cabang->id;
			$sess_data['site_logo'] = $data_cabang->url_logo;
			if($data['url_encod'] != false)
			{
				$sess_data['dashboard_url'] = base64_decode(urldecode($data['url_encod']));
			}
			
			$this->session->set_userdata($sess_data);

			// store user log
			$user_log = array(
				'user_id'        => $this->session->userdata("user_id"),
				'remote_address' => $this->session->userdata("ip_address"),
				'user_agent'     => $this->session->userdata("user_agent"),
				'last_login'     => date("Y-m-d H:i:s")
				);

			$log_id = $this->user_log_m->save($user_log);

			$this->session->set_userdata(array('log_id' => $log_id));
			
		}

	}

	public function logout ()
	{
		// delete user menufile
		$this->user_level_menu_m->delete_menu_file();

		// update user log
		$log_id = $this->session->userdata('log_id');
		if ($log_id) {
			$user_log = array('last_logout' => date("Y-m-d H:i:s"));
			$this->user_log_m->save($user_log, $log_id);
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
		return (int) $this->session->userdata('level_id');
	}
	/**
	 * [logged_in description]
	 * @return [type] [description]
	 */
	public function logged_in ()
	{
		
		return (bool) $this->session->userdata('logged_in');
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

	public function get_data_perawat_klinik()
	{	
		$this->db->where($this->_table.'.user_level_id IN (9,18)');
		return $this->db->get($this->_table);
	}
}

/* End of file user.php */
/* Location: ./application/models/user.php */