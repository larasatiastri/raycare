<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_hd_note_m extends MY_Model {

	protected $_table        = 'tindakan_hd_note';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		
	);

	protected $datatable_columns = array(
		//column di table  => alias
		'tindakan_hd_note.note'    => 'note', 
		'a.nama'                   => 'nama_perawat_buat', 
		'b.nama'                   => 'nama_perawat_edit', 
		'tindakan_hd_note.status'  => 'status', 
		'tindakan_hd_note.id'    => 'id', 
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
	 	 
		$join1 = array('user a',$this->_table . '.created_by = a.id','left');
		$join2 = array('user b',$this->_table . '.modified_by = b.id','left');
		$join_tables = array($join1, $join2);


		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'tindakan_hd_note.id';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('tindakan_hd_id', $tindakan_hd_id);
		$this->db->where('status', 0);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('tindakan_hd_id', $tindakan_hd_id);
		$this->db->where('status', 0);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('tindakan_hd_id', $tindakan_hd_id);
		$this->db->where('status', 0);

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

