<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_m extends MY_Model {

	protected $_table        = 'penjualan';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'penjualan.id'               => 'id',
		'penjualan.customer_id'      => 'customer_id',
		'penjualan.tipe_customer'    => 'tipe_customer',
		'penjualan.no_penjualan'     => 'no_penjualan',
		'penjualan.no_invoice'       => 'no_invoice',
		'penjualan.no_surat_jalan'   => 'no_surat_jalan',
		'penjualan.tujuan_id'        => 'tujuan_id',
		'penjualan.keterangan'       => 'keterangan',
		'penjualan.tanggal_pesan'    => 'tanggal_pesan',
		'penjualan.tanggal_kirim'    => 'tanggal_kirim',
		'penjualan.`status`'         => 'status',
		'penjualan.status_keuangan'  => 'status_keuangan',
		'penjualan.catatan_keuangan' => 'catatan_keuangan',
		'penjualan.is_active'        => 'is_active',
		'penjualan.is_invoice'       => 'is_invoice',
		'customer.kode'              => 'customer_kode',
		'customer.nama'              => 'customer_nama',
		'penjualan_cetak.no_cetak'   => 'no_cetak'
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
		$join1 = array('customer', 'penjualan.customer_id = customer.id', 'left');
		$join2 = array('penjualan_cetak', 'penjualan.id = penjualan_cetak.penjualan_id', 'left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('penjualan.`status`',1);
		$this->db->where('customer.tipe', $tipe);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('penjualan.`status`',1);
		$this->db->where('customer.tipe', $tipe);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('penjualan.`status`',1);
		$this->db->where('customer.tipe', $tipe);

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

	public function get_datatable_siap_kirim($tipe)
	{	
		$join1 = array('customer', 'penjualan.customer_id = customer.id', 'left');
		$join2 = array('penjualan_cetak', 'penjualan.id = penjualan_cetak.penjualan_id', 'left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('penjualan.`status`',2);
		$this->db->where('customer.tipe', $tipe);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('penjualan.`status`',2);
		$this->db->where('customer.tipe', $tipe);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('penjualan.`status`',2);
		$this->db->where('customer.tipe', $tipe);

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

	public function get_datatable_history($tipe)
	{	
		$join1 = array('customer', 'penjualan.customer_id = customer.id', 'left');
		$join2 = array('penjualan_cetak', 'penjualan.id = penjualan_cetak.penjualan_id', 'left');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('penjualan.`status`',3);
		$this->db->where('customer.tipe', $tipe);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('penjualan.`status`',3);
		$this->db->where('customer.tipe', $tipe);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('penjualan.`status`',3);
		$this->db->where('customer.tipe', $tipe);

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

}

/* End of file cabang_m.php */
/* Location: ./application/models/gudang/gudang_m.php */