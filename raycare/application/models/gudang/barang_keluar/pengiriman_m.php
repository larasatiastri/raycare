<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_m extends MY_Model {

	protected $_table        = 'pengiriman';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns_cabang = array(

		//column di table          => alias
		'invoice.no_invoice'        => 'no_invoice', 
		'pengiriman.no_surat_jalan' => 'no_surat_jalan', 
		'cabang.nama'               => 'customer_nama', 
		'cabang.kode'               => 'customer_kode', 
		'penjualan.tanggal_pesan'   => 'tanggal_pesan',
		'pengiriman.tanggal'   => 'tanggal_kirim',
		'pengiriman.keterangan'     => 'keterangan',
		'penjualan_cetak.no_cetak'  => 'no_cetak', 
		'pengiriman.id'             => 'id',
		'pengiriman.status'         => 'status',
		'pengiriman.gudang_id'      => 'gudang_id',
		'penjualan.id'              => 'penjualan_id',
	);

	protected $datatable_columns_customer = array(

		//column di table          => alias
		'invoice.no_invoice'        => 'no_invoice', 
		'pengiriman.no_surat_jalan' => 'no_surat_jalan', 
		'customer.nama'             => 'customer_nama', 
		'customer.kode'             => 'customer_kode', 
		'penjualan.tanggal_pesan'   => 'tanggal_pesan',
		'penjualan.tanggal_kirim'   => 'tanggal_kirim',
		'pengiriman.keterangan'     => 'keterangan',
		'penjualan_cetak.no_cetak'  => 'no_cetak', 
		'pengiriman.id'             => 'id',
		'pengiriman.status'         => 'status',
		'pengiriman.gudang_id'      => 'gudang_id',
		'penjualan.id'              => 'penjualan_id',
	);

	public function __construct()
	{
		parent::__construct();
	}


	public function get_datatable_internal($type, $gudang_id, $history)
	{	
		$join1 = array('penjualan', $this->_table.'.penjualan_id = penjualan.id', 'left');
		$join2 = array('cabang', $this->_table.'.customer_id = cabang.id', 'left');
		$join3 = array('penjualan_cetak', 'penjualan.id = penjualan_cetak.penjualan_id', 'left');
		$join4 = array('invoice', $this->_table.'.invoice_id = invoice.id', 'left');
		$join_tables = array($join1, $join2, $join3, $join4);

		$wheres = array(
			'pengiriman.is_active' => 1,
			'pengiriman.tipe_cus'  => $type,
			'pengiriman.gudang_id' => $gudang_id,
		);

		$status = array(1);
		if ($history) {
			$status = array(2,3,4);
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_cabang);
		$params['sort_by'] = 'penjualan.tanggal_kirim';
		$params['sort_dir']	= 'desc';
		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->where_in('pengiriman.status', $status);
		$this->db->group_by('pengiriman.id');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->where_in('pengiriman.status', $status);
		$this->db->group_by('pengiriman.id');

		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		//$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->where_in('pengiriman.status', $status);
		$this->db->group_by('pengiriman.id');

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


	public function get_datatable_external($type, $gudang_id, $history)
	{

		$join1 = array('penjualan', $this->_table.'.penjualan_id = penjualan.id', 'left');
		$join2 = array('customer', $this->_table.'.customer_id = customer.id', 'left');
		$join3 = array('penjualan_cetak', 'penjualan.id = penjualan_cetak.penjualan_id', 'left');
		$join4 = array('invoice', $this->_table.'.invoice_id = invoice.id', 'left');
		$join_tables = array($join1, $join2, $join3, $join4);

		$wheres = array(
			'pengiriman.is_active' => 1,
			'pengiriman.tipe_cus'  => $type,
			'pengiriman.gudang_id' => $gudang_id,
		);

		$status = array(1,2);
		if ($history) {
			$status = array(3,4);
		}

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_customer);
		$params['sort_by'] = 'penjualan.tanggal_kirim';
		$params['sort_dir']	= 'desc';
		// $params['sort_by'] = 'penjualan.no_surat_jalan';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($wheres);
		$this->db->where_in('pengiriman.status', $status);
		$this->db->group_by('pengiriman.id');

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($wheres);
		$this->db->where_in('pengiriman.status', $status);
		$this->db->group_by('pengiriman.id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($wheres);
		$this->db->where_in('pengiriman.status', $status);
		$this->db->group_by('pengiriman.id');

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


	public function get_no_surat_jalan()
	{
		$format = "SELECT MAX(SUBSTRING(`no_surat_jalan`,5,3)) AS max_no_do FROM `pengiriman` WHERE RIGHT(`no_surat_jalan`,4) = DATE_FORMAT(NOW(), '%Y');";	
		return $this->db->query($format);
	}

	public function get_max_id_pengiriman()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,12,4)) AS max_id FROM `pengiriman` WHERE SUBSTR(`id`,4,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

}
