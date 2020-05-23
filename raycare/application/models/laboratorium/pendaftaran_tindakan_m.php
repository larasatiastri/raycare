<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran_tindakan_m extends MY_Model {

	protected $_table        = 'pendaftaran_tindakan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
				
	);

	private $_fillable_edit = array(
		
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pendaftaran_tindakan.id'           => 'id', 
		'pendaftaran_tindakan.created_date' => 'tanggal', 
		'pendaftaran_tindakan.nama_pasien' => 'nama_pasien', 
		'pendaftaran_tindakan.no_telp' => 'no_telp'
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
		$cabang_id = $this->session->userdata('cabang_id');

		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pendaftaran_tindakan.created_date';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.cabang_id',$cabang_id);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.poliklinik_id',3);
		$this->db->where($this->_table.'.status',1);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.cabang_id',$cabang_id);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.poliklinik_id',3);
		$this->db->where($this->_table.'.status',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.cabang_id',$cabang_id);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.poliklinik_id',3);
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

	

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */