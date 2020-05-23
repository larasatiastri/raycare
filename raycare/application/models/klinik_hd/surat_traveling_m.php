<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_traveling_m extends MY_Model {

	protected $_table        = 'surat_traveling';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'surat_traveling.no_surat'   			=> 'no_surat', 
		'pasien.nama'         		 			=> 'nama', 
		'user.nama'   	 						=> 'nama_dokter_buat', 
		'surat_traveling.tanggal_surat'   	 	=> 'tanggal', 
		'pasien.no_member'         		 		=> 'no_member', 
		'pasien.url_photo'         		 		=> 'url_photo', 
		'surat_traveling.is_active'   	 		=> 'is_active',
		'surat_traveling.rs_tujuan'   	 		=> 'rs_tujuan',
		'surat_traveling.lama_traveling'   	 		=> 'lama_traveling',
		'surat_traveling.jenis_lama'   	 		=> 'jenis_lama',
		'surat_traveling.alasan_traveling'   	 		=> 'alasan_traveling',
		'surat_traveling.alasan_pindah'   	 		=> 'alasan_pindah',
		'surat_traveling.status'   	 		=> 'status',
		'surat_traveling.id'   	 				=> 'id',
		'surat_traveling.is_manual'   	 				=> 'is_manual',
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{	

		$join1 = array('pasien',$this->_table . '.pasien_id = pasien.id');
		$join2 = array('user', $this->_table.'.created_by = user.id');
		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'surat_traveling.id';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('surat_traveling.is_active', 1);
		$this->db->where('surat_traveling.is_manual', 0);
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('surat_traveling.is_active', 1);
		$this->db->where('surat_traveling.is_manual', 0);
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('surat_traveling.is_active', 1);
		$this->db->where('surat_traveling.is_manual', 0);
		 
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
	public function get_datatable_pindah()
	{	
		$join1 = array('pasien',$this->_table . '.pasien_id = pasien.id');
		$join2 = array('user', $this->_table.'.created_by = user.id');
		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'surat_traveling.id';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('surat_traveling.is_active', 1);
		$this->db->where('surat_traveling.alasan',1);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('surat_traveling.is_active', 1);
		$this->db->where('surat_traveling.alasan',1);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('surat_traveling.is_active', 1);
		$this->db->where('surat_traveling.alasan',1);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		 
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
	public function get_datatable_report($param_month, $param_year)
	{	
		$level_id = $this->session->userdata('level_id');
		$user_id = $this->session->userdata('user_id');

		$join1 = array('pasien',$this->_table . '.pasien_id = pasien.id');
		$join2 = array('user', $this->_table.'.created_by = user.id');
		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'surat_traveling.id';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('MONTH(tanggal_surat)',$param_month);
		$this->db->where('YEAR(tanggal_surat)',$param_year);
		$this->db->where('alasan',1);
		$this->db->where('pasien.is_meninggal',0);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		$this->db->group_by($this->_table.'.pasien_id');

	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('MONTH(tanggal_surat)',$param_month);
		$this->db->where('YEAR(tanggal_surat)',$param_year);
		$this->db->where('alasan',1);
		$this->db->where('pasien.is_meninggal',0);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		$this->db->group_by($this->_table.'.pasien_id');
	
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('MONTH(tanggal_surat)',$param_month);
		$this->db->where('YEAR(tanggal_surat)',$param_year);
		$this->db->where('alasan',1);
		$this->db->where('pasien.is_meninggal',0);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		$this->db->group_by($this->_table.'.pasien_id');

		 
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
	}/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_report_travel($param_month, $param_year)
	{	
		$level_id = $this->session->userdata('level_id');
		$user_id = $this->session->userdata('user_id');
		
		$join1 = array('pasien',$this->_table . '.pasien_id = pasien.id');
		$join2 = array('user', $this->_table.'.created_by = user.id');
		$join_tables = array($join1,$join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'surat_traveling.id';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('MONTH(tanggal_surat)',$param_month);
		$this->db->where('YEAR(tanggal_surat)',$param_year);
		$this->db->where('pasien.is_meninggal',0);
		$this->db->where('alasan',2);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		$this->db->group_by($this->_table.'.pasien_id');

	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('MONTH(tanggal_surat)',$param_month);
		$this->db->where('YEAR(tanggal_surat)',$param_year);
		$this->db->where('pasien.is_meninggal',0);
		$this->db->where('alasan',2);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		$this->db->group_by($this->_table.'.pasien_id');
	
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('MONTH(tanggal_surat)',$param_month);
		$this->db->where('YEAR(tanggal_surat)',$param_year);
		$this->db->where('pasien.is_meninggal',0);
		$this->db->where('alasan',2);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		$this->db->group_by($this->_table.'.pasien_id');

		 
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

	public function get_max_no($roman_month)
	{
		$month_lenght = strlen($roman_month);
		switch ($month_lenght) {
			case 1:
				$SQL = "SELECT MAX(SUBSTRING(no_surat,7,3)) as max_no FROM surat_traveling WHERE SUBSTR(no_surat,15,1)='".$roman_month."' AND SUBSTR(no_surat,17,4) = '".date('Y')."' ";
				break;
			case 2:
				$SQL = "SELECT MAX(SUBSTRING(no_surat,7,3)) as max_no FROM surat_traveling WHERE SUBSTR(no_surat,15,2)='".$roman_month."' AND SUBSTR(no_surat,18,4) = '".date('Y')."' ";
				break;
			case 3:
				$SQL = "SELECT MAX(SUBSTRING(no_surat,7,3)) as max_no FROM surat_traveling WHERE SUBSTR(no_surat,15,3)='".$roman_month."' AND SUBSTR(no_surat,19,4) = '".date('Y')."' ";
				break;
			case 4:
				$SQL = "SELECT MAX(SUBSTRING(no_surat,7,3)) as max_no FROM surat_traveling WHERE SUBSTR(no_surat,15,4)='".$roman_month."' AND SUBSTR(no_surat,20,4) = '".date('Y')."' ";
				break;
		}
		return $this->db->query($SQL);

	}

	public function get_last_date($pasien_id)
	{
		$date = date('Y-m-d');

		$this->db->where('pasien_id', $pasien_id);
		$this->db->where('status', 0);
		$this->db->where('date(tanggal_surat) <=', $date);
		$this->db->order_by('tanggal_surat','desc');

		return $this->db->get($this->_table);
	}

}

