<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kotak_sampah_m extends MY_Model {

	protected $_table        = 'kotak_sampah';
	protected $_order_by     = 'kotak_sampah_id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'kotak_sampah_id',
		'tipe',
		'data_id', 
		'created_by', 
		'created_date', 
			
	);

	private $_fillable_edit = array(
		'kotak_sampah_id',
		'tipe',
		'data_id', 
		'created_by', 
		'created_date', 

	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'kotak_sampah.created_date'    => 'created_date',
		'kotak_sampah.tipe'  		=> 'tipe', 
		'user.nama'    				=> 'nama',
		'kotak_sampah.created_by'      => 'created_by',
		'kotak_sampah.kotak_sampah_id' => 'kotak_sampah_id', 
		'kotak_sampah.data_id'    	=> 'data_id',

		
	);

	public function __construct()
	{
		parent::__construct();
	}

	function get_id($id)
	{	
		$this->db->where('kotak_sampah_id', $id);
		// $this->db->where('tipe', $tipe);
		$this->db->order_by('kotak_sampah_id', 'desc');
		$query = $this->db->query('SELECT kotak_sampah_id FROM kotak_sampah');
		
		return $query->row();
		return $this->db->get($this->_table);
	}

	function delete_id($id)
	{

		$this->db->where('kotak_sampah_id', $id);
		$this->db->limit(1);
		$this->db->delete($this->_table);

	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{	

		$join = array('user', $this->_table.'.created_by = user.id');


		$join_tables = array($join);

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
		$query = $this->db->query('SELECT MAX(kotak_sampah_id) as kotak_sampah_id FROM kotak_sampah');

		return $query->row();
	}

	public function max2(){
		$query = $this->db->query('SELECT MAX(kotak_sampah_id) as kotak_sampah_id FROM kotak_sampah');

		return $query->row();
	}

	public function simpan($data){
		$query = $this->db->insert($this->_table, $data); 
	}


	public function get_data_id($data_id, $tipe){
		$query = $this->db->query('SELECT * FROM kotak_sampah');
		// $query = $this->db->query('data_id', $data_id);
		// $query = $this->db->query('tipe', $tipe);


		// return $this->db->get($this->_table);
		return $query->row();

	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->_table);
		return true;
	}

}

/* End of file kotak_sampah_m.php */
/* Location: ./application/models/master/kotak_sampah_m.php */