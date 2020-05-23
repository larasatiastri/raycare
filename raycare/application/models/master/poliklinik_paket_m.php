<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poliklinik_paket_m extends MY_Model {

	protected $_table        = 'poliklinik_paket';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'id',
		'poliklinik_id',
		'paket_id',
		'is_active', 		
	);

	private $_fillable_edit = array(
		'id',
		'poliklinik_id',
		'paket_id',
		'is_active',
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'poliklinik_paket.id'         		=> 'id', 
		'poliklinik_paket.poliklinik_id'   	=> 'poliklinik_id', 
		'poliklinik_paket.paket_id'   		=> 'paket_id', 
		'poliklinik_paket.is_active'     	=> 'is_active',
		
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
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);

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

	function get_id($id)
	{	
		$this->db->where('id', $id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->query('SELECT id FROM poliklinik_paket');
		
		return $query->row();
		return $this->db->get($this->_table);
	}

	public function get_poliklinik_paket_id($paket_id, $poliklinik_id){

		$this->db->select('poliklinik_paket.id AS id, poliklinik_paket.paket_id AS paket_id, poliklinik_paket.poliklinik_id AS poliklinik_id,
							poliklinik.nama');
		$this->db->join('poliklinik', $this->_table.'.poliklinik_id = poliklinik.id','left');
		// $this->db->join('poliklinik_paket',$this->_table.'.poliklinik_id = poliklinik_paket.id','left');
		$this->db->where('poliklinik_paket.paket_id',$paket_id);
		$this->db->where('poliklinik_paket.poliklinik_id',$poliklinik_id);

		return $this->db->get($this->_table);
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

/* End of file poliklinik_paket_m.php */
/* Location: ./application/models/master/poliklinik_paket_m.php */