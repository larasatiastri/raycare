<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_pembayaran_detail_m extends MY_Model {

	protected $_table        = 'pembelian_pembayaran_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembelian_pembayaran_detail.id'            => 'id',
		'pembelian_pembayaran_detail.pembelian_id'  => 'id_pembelian',
		'pembelian_pembayaran_detail.no_pembayaran' => 'no_pembayaran',
		'pembelian_pembayaran_detail.supplier_id'   => 'supplier_id',
		'pembelian_pembayaran_detail.tipe_supplier' => 'tipe_supplier',
		'pembelian_pembayaran_detail.keuangan_id'   => 'keuangan_id',
		'pembelian_pembayaran_detail.kasir_id'      => 'kasir_id',
		'pembelian_pembayaran_detail.tanggal'       => 'tanggal',
		'pembelian_pembayaran_detail.total'         => 'total',
		'pembelian_pembayaran_detail.status'        => 'status',
		'kasir.nama'						 => 'nama_kasir',
		'keuangan.nama'						 => 'keuangan',
		'pembelian.no_pembelian'             => 'no_pembelian'
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
		$join1 = array('pembelian', $this->_table.'.pembelian_id = pembelian.id');
		$join2 = array('user keuangan', $this->_table.'.keuangan_id = keuangan.id');
		$join3 = array('user kasir', $this->_table.'.kasir_id = kasir.id');
		$join_tables = array($join1,$join2,$join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = $this->_table.'.id';
		$params['sort_dir'] = 'asc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		

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

	public function get_max_id_bayar_detail_pembelian()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,14,4)) AS max_id FROM `pembelian_pembayaran_detail` WHERE SUBSTR(`id`,6,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_data_invoice($pembelian_pembayaran_id)
	{
		$this->db->select('pembelian_pembayaran_detail.*, pembelian_invoice.id AS id_pembelian_invoice, pembelian_invoice.pembelian_id, pembelian_invoice.no_invoice, pembelian_invoice.total_invoice, pembelian_invoice.tgl_invoice, pembelian_invoice.keterangan AS keterangan_inv, pembelian_invoice.url');
		$this->db->join('pembelian_invoice', $this->_table.'.pembelian_invoice_id = pembelian_invoice.id', 'left');
		$this->db->where($this->_table.'.pembelian_pembayaran_id', $pembelian_pembayaran_id);

		return $this->db->get($this->_table);
	}	

	public function get_data_invoice_tipe($pembelian_pembayaran_id)
	{
		$this->db->select('pembelian_pembayaran_detail.pembayaran_tipe, pembelian_pembayaran_detail.nomor_tipe, SUM(jumlah) as jumlah, pembelian_pembayaran_detail.bank_id');
		$this->db->join('pembelian_invoice', $this->_table.'.pembelian_invoice_id = pembelian_invoice.id', 'left');
		$this->db->where($this->_table.'.pembelian_pembayaran_id', $pembelian_pembayaran_id);
		$this->db->group_by($this->_table.'.pembayaran_tipe, nomor_tipe');

		return $this->db->get($this->_table);
	}	
	
}

/* End of file pembelian_pembayaran_detail_m.php */
/* Location: ./application/models/pembelian/pembelian_pembayaran_detail_m.php */