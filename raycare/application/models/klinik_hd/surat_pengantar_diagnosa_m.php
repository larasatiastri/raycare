<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_pengantar_diagnosa_m extends MY_Model {

	protected $_table      = 'surat_pengantar_diagnosa';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
	);

	protected $datatable_columns_pilih_rujukan = array(
		//column di table  => alias
		
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
// die_dump($result->records);
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

	public function get_diagnosa($surat_pengantar_id)
	{
		$this->db->select('icd_code.code_ast, icd_code.name');
		$this->db->where('surat_pengantar_id', $surat_pengantar_id);
		$this->db->join('icd_code','surat_pengantar_diagnosa.icd_code = icd_code.code_ast');

		return $this->db->get($this->_table);
	}

	
}

/* End of file surat_pengantar_diagnosa_m.php */
/* Location: ./application/models/surat_pengantar_diagnosa_m.php */