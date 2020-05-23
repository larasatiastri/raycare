<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengumuman_m extends MY_Model {

	protected $_table      = 'pengumuman';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'id',
		'tanggal',
		'keterangan',
		'speed',
		'is_active',
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pengumuman.id'           	=> 'id', 
		'pengumuman.tanggal'        => 'tanggal', 
		'pengumuman.keterangan'  	=> 'keterangan', 
		'pengumuman.speed'   		=> 'speed', 
		'pengumuman.is_active'    	=> 'is_active', 
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
	public function fillable()
	{
		return $this->_fillable;
	}

	public function get_pengumuman_aktif()
	{
		$now = date('Y-m-d');
		$SQL = "SELECT keterangan, speed FROM pengumuman WHERE date(tanggal) <= '$now' AND is_active = 1 ORDER BY tanggal DESC LIMIT 1";

		return $this->db->query($SQL)->result_array();

	}

}

/* End of file pengumuman_m.php */
/* Location: ./application/models/pengumuman_m.php */