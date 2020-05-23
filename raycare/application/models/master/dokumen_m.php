<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dokumen_m extends MY_Model {

	protected $_table        = 'dokumen';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		'nama',
		'is_kadaluarsa',
		'is_required',
		'notif_hari', 
		'is_active'
	);

	private $_fillable_edit = array(
		'nama',
		'is_kadaluarsa',
		'is_required',
		'notif_hari', 
		'is_active'
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'dokumen.id'            => 'id', 
		'dokumen.nama'          => 'nama', 
		'dokumen.is_kadaluarsa' => 'is_kadaluarsa', 
		'dokumen.is_required'   => 'is_required', 
		'dokumen.notif_hari'   => 'notif_hari', 
		
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

	public function get_data_dokumen($pasien_id,$dok_id='')
	{
		$this->db->join('pasien_dokumen', $this->_table.'.id = pasien_dokumen.dokumen_id','left');
		$this->db->where('pasien_dokumen.pasien_id',$pasien_id);
		$this->db->where_in('dokumen.id',$dok_id);

		return $this->db->get($this->_table)->result();
	}

}

/* End of file dokumen_m.php */
/* Location: ./application/models/master/dokumen_m.php */