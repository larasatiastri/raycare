<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_cetak_m extends MY_Model {

	protected $_table        = 'pengiriman_cetak';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
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

	public function get_pengiriman_cetak($id)
	{
		$sql = "SELECT pengiriman_cetak.no_cetak, `user`.nama, pengiriman_cetak.created_date
				FROM pengiriman_cetak
				JOIN `user` ON `user`.id = pengiriman_cetak.created_by
				WHERE pengiriman_cetak.pengiriman_id = '$id'
				ORDER BY pengiriman_cetak.no_cetak DESC";

		return $this->db->query($sql)->result_array();
	}

	public function get_no_cetak($id)
	{
		$format = "SELECT MAX(SUBSTRING(`no_cetak`,10,3)) AS max_no_cetak FROM `pengiriman_cetak` WHERE SUBSTRING(`no_cetak`,5,2) = DATE_FORMAT(NOW(), '%y') AND pengiriman_id = '$id'";	
		return $this->db->query($format);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/master/cabang_m.php */