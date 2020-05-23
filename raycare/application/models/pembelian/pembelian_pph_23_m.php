<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_pph_23_m extends MY_Model {

	protected $_table        = 'pembelian_pph_23';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembelian_pph_23.id'                    => 'id', 
		'pembelian_pph_23.nomor_pph'          => 'nomor_pph', 
		'pembelian_pph_23.nomor_pembelian'          => 'nomor_pembelian', 
		'pembelian_pph_23.pph_23_nominal' => 'pph_23_nominal', 
		'pembelian_pph_23.status'                => 'status',
		'pembelian_pph_23.kode_pajak'                => 'kode_pajak',
		'pembelian_pph_23.pembelian_id'          => 'pembelian_id'		
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tipe)
	{	
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.created_date';
		$params['sort_dir'] = 'ASC';
		$wheres = array();

		if($tipe == 1){
			$wheres = array('status' => 1);
		}if($tipe == 2){
			$wheres = array('status >' => 1);
		}
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

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

	
	public function get_max_id_pembelian_pph_23()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `pembelian_pph_23` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

}

/* End of file pembelian_pph_23_m.php */
/* Location: ./application/models/pembelian/pembelian_pph_23_m.php */