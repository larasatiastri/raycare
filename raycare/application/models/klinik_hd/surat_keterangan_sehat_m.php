<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_keterangan_sehat_m extends MY_Model {

	protected $_table        = 'surat_sehat';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'surat_sehat.no_surat'     => 'no_surat', 
		'pasien.nama'              => 'nama', 
		'user.nama'                => 'nama_dokter_buat', 
		'surat_sehat.created_date' => 'tanggal', 
		'pasien.no_member'         => 'no_member', 
		'pasien.url_photo'         => 'url_photo', 
		'surat_sehat.is_active'    => 'is_active',
		'surat_sehat.id'           => 'id'
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
		$params['sort_by'] = 'surat_sehat.id';
		$params['sort_dir'] = 'desc';

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
				$SQL = "SELECT MAX(SUBSTRING(no_surat,6,3)) as max_no FROM surat_sehat WHERE SUBSTR(no_surat,14,1)='".$roman_month."' AND SUBSTR(no_surat,16,4) = '".date('Y')."' ";
				break;
			case 2:
				$SQL = "SELECT MAX(SUBSTRING(no_surat,6,3)) as max_no FROM surat_sehat WHERE SUBSTR(no_surat,14,2)='".$roman_month."' AND SUBSTR(no_surat,17,4) = '".date('Y')."' ";
				break;
			case 3:
				$SQL = "SELECT MAX(SUBSTRING(no_surat,6,3)) as max_no FROM surat_sehat WHERE SUBSTR(no_surat,14,3)='".$roman_month."' AND SUBSTR(no_surat,18,4) = '".date('Y')."' ";
				break;
			case 4:
				$SQL = "SELECT MAX(SUBSTRING(no_surat,6,3)) as max_no FROM surat_sehat WHERE SUBSTR(no_surat,14,4)='".$roman_month."' AND SUBSTR(no_surat,19,4) = '".date('Y')."' ";
				break;
		}
		return $this->db->query($SQL);

	}

}

