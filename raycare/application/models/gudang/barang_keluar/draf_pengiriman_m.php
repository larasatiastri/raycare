<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Draf_pengiriman_m extends MY_Model {

	protected $_table        = 'draf_pengiriman';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns_cabang = array(

		//column di table          => alias
		'penjualan.no_penjualan'             => 'no_penjualan', 
		'draf_pengiriman.no_surat_jalan'     => 'no_surat_jalan', 
		'cabang.nama'                        => 'customer_nama', 
		'cabang.kode'                        => 'customer_kode', 
		'penjualan.tanggal_pesan'            => 'tanggal_pesan',
		'penjualan.tanggal_kirim'            => 'tanggal_kirim',
		'penjualan.keterangan'               => 'keterangan',
		'penjualan_cetak.no_cetak'           => 'no_cetak', 
		'draf_pengiriman.draf_pengiriman_id' => 'id',
		'draf_pengiriman.gudang_id'          => 'gudang_id',
		'penjualan.id'                       => 'penjualan_id',
	);

	protected $datatable_columns_customer = array(

		//column di table          => alias
		'penjualan.no_penjualan'             => 'no_penjualan', 
		'draf_pengiriman.no_surat_jalan'     => 'no_surat_jalan', 
		'customer.nama'                      => 'customer_nama', 
		'customer.kode'                      => 'customer_kode', 
		'penjualan.tanggal_pesan'            => 'tanggal_pesan',
		'penjualan.tanggal_kirim'            => 'tanggal_kirim',
		'penjualan.keterangan'               => 'keterangan',
		'penjualan_cetak.no_cetak'           => 'no_cetak', 
		'draf_pengiriman.draf_pengiriman_id' => 'id',
		'draf_pengiriman.gudang_id'          => 'gudang_id',
		'penjualan.id'                       => 'penjualan_id',
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable_internal($type, $gudang_id)
	{	
		$join1 = array('penjualan', $this->_table.'.penjualan_id = penjualan.id', 'left');
		$join2 = array('cabang', $this->_table.'.customer_id = cabang.id', 'left');
		$join3 = array('penjualan_cetak', 'penjualan.id = penjualan_cetak.penjualan_id', 'left');
		$join_tables = array($join1, $join2, $join3);

		$wheres = array(
			'draf_pengiriman.is_active' => 1,
			'draf_pengiriman.tipe_cus'  => $type,
			'draf_pengiriman.gudang_id' => $gudang_id,
		);

		$status = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_cabang);
		// $params['sort_by'] = 'penjualan.no_penjualan';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		// $this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		// $this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		// $this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

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


	public function get_datatable_external($type, $gudang_id)
	{

		$join1 = array('penjualan', $this->_table.'.penjualan_id = penjualan.id', 'left');
		$join2 = array('customer', $this->_table.'.customer_id = customer.id', 'left');
		$join3 = array('penjualan_cetak', 'penjualan.id = penjualan_cetak.penjualan_id', 'left');
		$join_tables = array($join1, $join2, $join3);

		$wheres = array(
			'draf_pengiriman.is_active' => 1,
			'draf_pengiriman.tipe_cus'  => $type,
			'draf_pengiriman.gudang_id' => $gudang_id,
		);

		$status = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_customer);
		// $params['sort_by'] = 'penjualan.no_penjualan';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		// $this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		// $this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		// $this->db->where_in('status', $status);
		$this->db->group_by('penjualan.id');

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

	public function get_max_id() {

		$this->db->select_max('draf_pengiriman_id');
		return $this->db->get($this->_table)->result_array();
	}

	public function get_no_pengiriman()
	{
		$format = "SELECT MAX(SUBSTRING(`no_pengiriman`,10,3)) AS max_no_pengiriman FROM `pengiriman` WHERE SUBSTRING(`no_pengiriman`,5,4) = DATE_FORMAT(NOW(), '%y%m')";	
		return $this->db->query($format);
	}

	public function delete_all_by($where)
	{
		$this->db->where($where);
		return $this->db->where($this->table);
	}

	public function save_id($data){

		$data['created_by']   = $this->session->userdata('user_id');			
		$data['created_date'] = date('Y-m-d H:i:s');

		$this->db->set($data);
		$this->db->insert($this->_table);
		$id = $this->db->insert_id();

		return $id;
	}

}
