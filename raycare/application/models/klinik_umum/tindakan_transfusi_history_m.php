<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_transfusi_history_m extends MY_Model {

	protected $_table        = 'tindakan_transfusi_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_transfusi_history.id'              => 'id',
		'tindakan_transfusi_history.nomor_tindakan'  => 'nomor_tindakan', 
		'tindakan_transfusi_history.tanggal'         => 'tanggal', 
		'pasien.nama'                   => 'nama', 
		'pasien.id'                     => 'pasienid', 
		'pasien.no_member'              => 'no_member', 
		'pasien.tempat_lahir'           => 'tempat_lahir', 
		'pasien.tanggal_lahir'          => 'tanggal_lahir',
		'user.nama'                     => 'nama1',
		'pasien.url_photo'              => 'url_photo',
		'user.url'                      => 'url_photo1',
		'pasien.no_member'              => 'no_member',
		'tindakan_transfusi_history.status'          => 'status',
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($status)
	{	
 		$join1 = array('pasien', $this->_table . '.pasien_id = pasien.id');
	 	$join2 = array('cabang_poliklinik_dokter', $this->_table . '.dokter_id = cabang_poliklinik_dokter.dokter_id');
	 	$join3 = array('user',  'cabang_poliklinik_dokter.dokter_id = user.id');
		  
		$join_tables = array($join1,$join2,$join3);
		//$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'tindakan_transfusi_history.tanggal';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		 $this->db->where('tindakan_transfusi_history.cabang_id',$this->session->userdata('cabang_id'));
		//$this->db->where('dokter_id',$this->session->userdata('user_id'));
		 $this->db->where_in('tindakan_transfusi_history.status',$status);
		 $this->db->where('tindakan_transfusi_history.is_active',1);
		 $this->db->group_by('tindakan_transfusi_history.id');
		 
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		 
		 $this->db->where('tindakan_transfusi_history.cabang_id',$this->session->userdata('cabang_id'));
		//$this->db->where('dokter_id',$this->session->userdata('user_id'));
		 $this->db->where_in('tindakan_transfusi_history.status',$status);
		 $this->db->where('tindakan_transfusi_history.is_active',1);
		 $this->db->group_by('tindakan_transfusi_history.id');
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		 
		 $this->db->where('tindakan_transfusi_history.cabang_id',$this->session->userdata('cabang_id'));
		//$this->db->where('dokter_id',$this->session->userdata('user_id'));
		 $this->db->where_in('tindakan_transfusi_history.status',$status);
		 $this->db->where('tindakan_transfusi_history.is_active',1);
		 $this->db->group_by('tindakan_transfusi_history.id');
	  

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

	public function get_max_id()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `tindakan_transfusi_history` WHERE SUBSTRING(`id`,4,4) = DATE_FORMAT(NOW(), '%m%y')";	
		return $this->db->query($format);
	}
}
