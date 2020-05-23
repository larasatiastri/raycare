<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Outstanding_upload_dokumen_klaim_m extends MY_Model {

	protected $_table        = 'outstanding_upload_dokumen_klaim';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	private $_fillable_edit = array(
	);



	protected $datatable_columns = array(
		//column di table  => alias
		'outstanding_upload_dokumen_klaim.id'   	=> 'id', 
		'tindakan_hd_history.tanggal'       		=> 'tanggal', 
		'pasien.nama'      							=> 'nama_pasien', 
		'outstanding_upload_dokumen_klaim.status'   =>'status',	
		'tindakan_hd_history.id'       			    => 'id_tindakan', 
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
 
		$join1 = array('tindakan_hd_history',$this->_table . '.tindakan_hd_history_id = tindakan_hd_history.id');
	 	$join2 = array('pasien', $this->_table.'.pasien_id = pasien.id');
		$join_tables = array($join1,$join2);


		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']  = 'tindakan_hd_history.tanggal';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('tindakan_hd_history.is_sep',0);
		$this->db->where($this->_table.'.status',1);
		$this->db->or_where('tindakan_hd_history.is_inacbg',0);
		$this->db->where($this->_table.'.status',1);
		//$this->db->group_by(array("a.nama","pasien_penjamin.no_kartu","pasien_klaim.tipe","pasien_klaim.created_date","pasien_penjamin.id")); 
		 
	 
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('tindakan_hd_history.is_sep',0);
		$this->db->where($this->_table.'.status',1);
		$this->db->or_where('tindakan_hd_history.is_inacbg',0);
		$this->db->where($this->_table.'.status',1);
	 		 	 
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('tindakan_hd_history.is_sep',0);
		$this->db->where($this->_table.'.status',1);
		$this->db->or_where('tindakan_hd_history.is_inacbg',0);
		$this->db->where($this->_table.'.status',1);
	
	  

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

	public function get_max_id()
	{
		$sql = "SELECT MAX(CAST(id AS INT))  as max_id FROM `outstanding_upload_dokumen_klaim`;";

		return $this->db->query($sql);
	}
	
}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */