<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jurnal_template_trans_tipe_detail_m extends MY_Model
{
	protected $_table        = 'jurnal_template_trans_tipe_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable = array(
		'variable',
		'description'
	);



	// Array of database columns which should be read and sent back to DataTables

	protected $datatable_columns = array(
		//column di table  => alias
		'jurnal_template_trans_tipe_detail.id'          					=> 'id', 
		'jurnal_template_trans_tipe_detail.jurnal_template_trans_tipe_id' 	=> 'jurnal_template_trans_tipe_id', 
		'jurnal_template_trans_tipe_detail.variable'        				=> 'variable', 
		'jurnal_template_trans_tipe_detail.description'     				=> 'description',  
	);

	function __construct ()
	{
		parent::__construct();
	}

	
	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($id)
	{
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('jurnal_template_trans_tipe_id',$id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('jurnal_template_trans_tipe_id',$id);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('jurnal_template_trans_tipe_id',$id);

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

/* End of file sale_m.php */
/* Location: ./application/models/master/sale_m.php */