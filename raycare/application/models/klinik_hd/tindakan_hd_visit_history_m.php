<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_hd_visit_history_m extends MY_Model {

	protected $_table        = 'tindakan_hd_visit_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_hd_history.no_transaksi'         => 'no_transaksi', 
		'pasien.nama'                      => 'nama', 
		'user.nama'                        => 'nama_dokter', 
		'tindakan_hd_visit_history.start_visit'    => 'start_visit', 
		'tindakan_hd_visit_history.end_visit'      => 'end_visit', 
		'tindakan_hd_visit_history.keterangan'     => 'keterangan', 
		'tindakan_hd_visit_history.tindakan_hd_id' => 'tindakan_hd_id',
		'pasien.url_photo'                 => 'url_photo',
		'pasien.no_member'                 => 'no_member',
		'tindakan_hd_visit_history.pasien_id' => 'pasienid',
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
	 	 
		$join1 = array('tindakan_hd_history',$this->_table . '.tindakan_hd_id = tindakan_hd_history.id');
		$join2 = array('pasien',$this->_table . '.pasien_id = pasien.id','left');
		$join3 = array('user',$this->_table . '.dokter_id = user.id','left');
		$join_tables = array($join1, $join2, $join3);


		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'tindakan_hd_visit_history.id';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

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

}

