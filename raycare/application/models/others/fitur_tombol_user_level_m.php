<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fitur_tombol_user_level_m extends MY_Model {

	protected $_table        = 'fitur_tombol_user_level';
	protected $_order_by     = 'fitur_tombol_user_level_id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'fitur_tombol_user_level_id',
		'page',
		'button', 
		'user_level_id', 
	);

	private $_fillable_edit = array(
		'fitur_tombol_user_level_id',
		'page',
		'button', 
		'user_level_id', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'fitur_tombol_user_level.page'          => 'page', 
		'fitur_tombol_user_level.button'        => 'button',
		'fitur_tombol_user_level.user_level_id' => 'user_level_id', 	
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

	public function max(){
		$query = $this->db->query('SELECT MAX(fitur_tombol_user_level_id) as fitur_tombol_user_level_id FROM fitur_tombol_user_level');

		return $query->row();
	}


	public function get_by_id($id)
	{	
		$this->db->where('fitur_tombol_user_level_id', $id);
		$this->db->order_by('fitur_tombol_user_level_id', 'desc');
		return $this->db->get($this->_table);
	}

	public function delete_id($id)
	{
		$this->db->where('fitur_tombol_user_level_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->_table);
	}

	public function delete_by_page($page,$button)
	{
		$this->db->where('page', $page);
		$this->db->where('button', $button);
		$this->db->delete($this->_table);
	}

}

/* End of file fitur_tombol_user_level.php */
/* Location: ./application/models/master/fitur_tombol_user_level.php */