<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_hd_tindakan_lain_m extends MY_Model {

	protected $_table        = 'tindakan_hd_tindakan_lain';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_hd_tindakan_lain.nama_tindakan'        => 'nama_tindakan', 
		'tindakan_hd_tindakan_lain.status'   			 => 'status', 
		'user.nama'	=> 'nama_perawat',
		'tindakan_hd_tindakan_lain.waktu_tindakan'   	 => 'waktu_tindakan', 
		'tindakan_hd_tindakan_lain.id'	=> 'id',	
		'tindakan_hd_tindakan_lain.tindakan_hd'	=> 'tindakan_hd',	
		'tindakan_hd_tindakan_lain.tindakan_id'	=> 'tindakan_id',	
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tindakan_hd_id)
	{	

		$join1 = array('user',$this->_table . '.perawat_tindak_id = user.id','left');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by']	= $this->_table.'.id';
		$params['sort_dir']	= 'ASC';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.tindakan_hd', $tindakan_hd_id);
		$this->db->where($this->_table.'.is_active', 1);
	  
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.tindakan_hd', $tindakan_hd_id);
		$this->db->where($this->_table.'.is_active', 1);
	  
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.tindakan_hd', $tindakan_hd_id);
		$this->db->where($this->_table.'.is_active', 1);
	  

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
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `tindakan_hd_tindakan_lain` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}


}

/* End of file Item_m.php */
/* Location: ./application/models/master/cabang_m.php */