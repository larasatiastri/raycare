<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengepakan_box_paket_m extends MY_Model {

	protected $_table        = 'pengepakan_box_paket';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
		
	);

	

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pengepakan_box_paket.id'             => 'id',
		'pengepakan_box_paket.box_paket_id'   => 'box_paket_id',
		'pengepakan_box_paket.kode_box_paket' => 'kode_box_paket',
		'pengepakan_box_paket.info'           => 'info',
		'pengepakan_box_paket.created_by'     => 'created_by',
		'pengepakan_box_paket.created_date'   => 'created_date',
		'pengepakan_box_paket_cetak.no_cetak' => 'no_cetak',
		'box_paket.nama'                      => 'nama_box_paket',
		'`user`.nama'                         => 'user_nama',

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
		$join1 = array('pengepakan_box_paket_cetak', 'pengepakan_box_paket.id = pengepakan_box_paket_cetak.pengepakan_box_paket_id', 'left');
		$join2 = array('box_paket', 'pengepakan_box_paket.box_paket_id = box_paket.id', 'left');
		$join3 = array('`user`', 'pengepakan_box_paket.created_by = `user`.id', 'left');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->group_by('pengepakan_box_paket.id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->group_by('pengepakan_box_paket.id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->group_by('pengepakan_box_paket.id');

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

	public function get_kode_box()
	{
		$format = "SELECT MAX(SUBSTRING(`kode_box_paket`,11,2)) AS max_number FROM `pengepakan_box_paket` WHERE SUBSTRING(`kode_box_paket`,7,2) = DATE_FORMAT(NOW(), '%y') AND SUBSTRING(`kode_box_paket`, 9, 2) = DATE_FORMAT(NOW(), '%m')";	
		return $this->db->query($format);
	}
}

