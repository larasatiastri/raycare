<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_box_m extends MY_Model {

	protected $_table        = 'pengiriman_box';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		
		//column di table  => alias
		'gudang.nama'                   => 'nama_gudang',
		'gudang.alamat'                 => 'alamat',
		'inf_lokasi.nama_kelurahan'     => 'kelurahan',
		'inf_lokasi.nama_kecamatan'     => 'kecamatan',
		'inf_lokasi.nama_kabupatenkota' => 'kabupatenkota',
		'gudang.id'                     => 'id',
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable($customer_id, $tgl_kirim)
	{	
		$join1 = array('penjualan', 'penjualan.tujuan_id = gudang.id');
		$join2 = array('inf_lokasi', 'gudang.kode_lokasi = inf_lokasi.lokasi_kode');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('penjualan.customer_id', $customer_id);
		$this->db->where('penjualan.tanggal_kirim', $tgl_kirim);
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('penjualan.customer_id', $customer_id);
		$this->db->where('penjualan.tanggal_kirim', $tgl_kirim);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('penjualan.customer_id', $customer_id);
		$this->db->where('penjualan.tanggal_kirim', $tgl_kirim);

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
}
