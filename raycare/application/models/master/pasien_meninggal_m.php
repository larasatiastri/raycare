<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasien_meninggal_m extends MY_Model {

	protected $_table      = 'pasien_meninggal';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pasien.no_member'                   => 'no_member',
		'pasien.nama'                        => 'nama',
		'pasien_meninggal.tanggal_meninggal' => 'tanggal_meninggal',
		'pasien_meninggal.lokasi_meninggal'  => 'lokasi_meninggal',
		'pasien_meninggal.keterangan'        => 'keterangan',
		'pasien_meninggal.tipe_lokasi'       => 'tipe_lokasi',
		'pasien_meninggal.cabang_meninggal'  => 'cabang_meninggal',
		'pasien.url_photo'                   => 'url_photo',
		'pasien_meninggal.id'                => 'id',
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
		$level_id = $this->session->userdata('level_id');
		$user_id = $this->session->userdata('user_id');

		$join1 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pasien.is_meninggal',1);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pasien.is_meninggal',1);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pasien.is_meninggal',1);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
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

		$join1 = array('pasien', $this->_table.'.pasien_id = pasien.id','left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('pasien.is_meninggal',1);
		$this->db->where('MONTH(tanggal_meninggal)',$param_month);
		$this->db->where('YEAR(tanggal_meninggal)',$param_year);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('pasien.is_meninggal',1);
		$this->db->where('MONTH(tanggal_meninggal)',$param_month);
		$this->db->where('YEAR(tanggal_meninggal)',$param_year);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
		}
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('pasien.is_meninggal',1);
		$this->db->where('MONTH(tanggal_meninggal)',$param_month);
		$this->db->where('YEAR(tanggal_meninggal)',$param_year);
		$this->db->where('pasien.cabang_id', $this->session->userdata('cabang_id'));
		if($level_id == config_item('level_marketing_id')){
			$this->db->where('pasien.marketing_id', $user_id);
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
}

/* End of file pasien_meninggal_m.php */
/* Location: ./application/models/pasien_meninggal_m.php */