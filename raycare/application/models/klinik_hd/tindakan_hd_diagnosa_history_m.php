<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_hd_diagnosa_history_m extends MY_Model {

	protected $_table        = 'tindakan_hd_diagnosa_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias

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
 	 	 
		$join_tables = array();

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

	public function get_last_diagnose($tindakan_hd_id)
	{
		$columns = array(
            'icd_code.code_ast',
            'icd_code.name'
        );

        $diagnose = $this->db
            ->select($columns)
            ->from($this->_table)
            ->join('icd_code', $this->_table.'.kode_diagnosis = icd_code.code_ast')
            ->where($this->_table.'.tindakan_hd_id', $tindakan_hd_id)
            ->where($this->_table.'.tipe', 1)
            ->get()->result();

        return $diagnose;
	}



}

/* End of file tindakan_hd_diagnosa_history_m.php */
/* Location: ./application/models/klinik_hd/tindakan_hd_diagnosa_history_m.php */