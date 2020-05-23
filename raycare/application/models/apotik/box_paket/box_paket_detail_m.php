<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Box_paket_detail_m extends MY_Model {

	protected $_table        = 'box_paket_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(

		'box_paket_detail.nama' => 'box_nama',
		'box_paket_detail.id'   => 'id',
		
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable()
	{	

		$join = array();
		$join_tables = array();

		$wheres = array(
			'is_active' => 1
		);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);

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

	public function get_max_id_box_paket_detail()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,10,3)) AS max_id FROM `box_paket_detail` WHERE SUBSTR(`id`,5,4) = DATE_FORMAT(NOW(), '%m%y');";	
		return $this->db->query($format);
	}

	public function get_item_box_paket($box_paket_id)
	{
		$format = "SELECT box_paket_detail.*, item.kode as kode_item, item.nama as nama_item, item_satuan.nama as nama_satuan FROM `box_paket_detail` JOIN item ON box_paket_detail.item_id = item.id JOIN item_satuan ON box_paket_detail.item_satuan_id = item_satuan.id WHERE box_paket_id = '$box_paket_id';";
		
		return $this->db->query($format);

	}


}
