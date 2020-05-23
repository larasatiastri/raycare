<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_penawaran_m extends MY_Model {

	protected $_table        = 'pembelian_penawaran';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembelian_penawaran.id'                    => 'id', 
		'pembelian_penawaran.pembelian_id'          => 'pembelian_id', 
		'pembelian_penawaran.supplier_penawaran_id' => 'supplier_penawaran_id', 
		'pembelian_penawaran.nomor_penawaran'       => 'nomor_penawaran',
		'pembelian_penawaran.url'                   => 'url',
		'pembelian_penawaran.status'                => 'status',
		'pembelian_penawaran.keterangan'            => 'keterangan',
		'pembelian_penawaran.is_active'             => 'is_active'
		
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

	
	public function get_max_id_pembelian_penawaran()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `pembelian_penawaran` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

}

/* End of file pembelian_penawaran_m.php */
/* Location: ./application/models/pembelian/pembelian_penawaran_m.php */