<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Draft_penjualan_m extends MY_Model {

	protected $_table        = 'draf_penjualan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns_cabang = array(
		
		//column di table              => alias
		'cabang.nama'                  => 'customer_nama', 
		'cabang.kode'                  => 'customer_kode', 
		'draf_penjualan.tanggal_pesan' => 'tanggal_pesan',
		'draf_penjualan.tanggal_kirim' => 'tanggal_kirim',
		'draf_penjualan.keterangan'    => 'keterangan',
		'draf_penjualan.id'            => 'id',
	);

	protected $datatable_columns_customer = array(

		//column di table              => alias
		'customer.nama'                => 'customer_nama', 
		'customer.kode'                => 'customer_kode', 
		'draf_penjualan.tanggal_pesan' => 'tanggal_pesan',
		'draf_penjualan.tanggal_kirim' => 'tanggal_kirim',
		'draf_penjualan.keterangan'    => 'keterangan',
		'draf_penjualan.id'            => 'id',
	);


	public function __construct()
	{
		parent::__construct();
	}

	public function get_persetujuan_penjualan_cabang() {

		$join1 = array('cabang', $this->_table.'.customer_id = cabang.id');
		$join_tables = array($join1);

		$wheres = array(
			'draf_penjualan.is_active'     => 1,
			'draf_penjualan.status'        => 1,
			'draf_penjualan.tipe_customer' => 1,
			'draf_penjualan.is_final'      => 1,
		);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_cabang);
		$params['sort_by'] = "draf_penjualan.tanggal_kirim";

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
		foreach ($this->datatable_columns_cabang as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_cabang;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}


	public function get_persetujuan_penjualan_customer() {

		$join1 = array('customer', $this->_table.'.customer_id = customer.id');
		$join_tables = array($join1);

		$wheres = array(
			'draf_penjualan.is_active'     => 1,
			'draf_penjualan.status'        => 1,
			'draf_penjualan.tipe_customer' => 2,
			'draf_penjualan.is_final'      => 1,
		);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_customer);
		$params['sort_by'] = "draf_penjualan.tanggal_kirim";

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
		foreach ($this->datatable_columns_customer as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_customer;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 

		return $result; 
	}
}
