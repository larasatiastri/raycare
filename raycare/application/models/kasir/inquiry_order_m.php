<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inquiry_order_m extends MY_Model {

	protected $_table      = 'inquiry_order';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'inquiry_order.id'                => 'id',
		'inquiry_order.tanggal_setor'           => 'tanggal_setor',
		'inquiry_order.tanggal'           => 'tanggal',
		'inquiry_order.shift'             => 'shift',
		'user.nama'                       => 'nama_resepsionis',
		'inquiry_order.nomor_bukti_setor' => 'nomor_bukti_setor',
		'inquiry_order.total'             => 'total',
		'inquiry_order.keterangan'        => 'keterangan',
		'inquiry_order.status_keuangan'        => 'status_keuangan',
		'inquiry_order.status_adm_klaim'        => 'status_adm_klaim',
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
		
		$join1 = array("user", $this->_table . '.created_by = user.id', 'left');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal';
		$params['sort_dir'] = 'desc';

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
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_setoran()
	{
		
		$join1 = array("user", $this->_table . '.created_by = user.id', 'left');

		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.tanggal';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.status', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.status', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.status', 1);

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


	public function get_max_id()
	{
		$format = "SELECT MAX(RIGHT(`id`,4)) AS max_id FROM `inquiry_order` WHERE SUBSTR(`id`,9,4) = DATE_FORMAT(NOW(), '%m%y');";	
		return $this->db->query($format);
	}

	
	
}
