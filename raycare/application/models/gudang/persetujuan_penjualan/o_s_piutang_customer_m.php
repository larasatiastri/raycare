<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class O_s_piutang_customer_m extends MY_Model {

	protected $_table        = 'o_s_piutang_customer';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	private $_fillable_add = array(
			
	);

	private $_fillable_edit = array(
		 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'penjualan.no_penjualan'   => 'no_so',
		'cabang.nama'              => 'nama_customer',
		'penjualan.tanggal_pesan'  => 'tanggal_so',
		'master_tipe_bayar.nama'   => 'nama_tipe',
		'b.lama_tempo'             => 'lama_tempo',
		'penjualan.id'			   => 'penjualan_id',
		'penjualan.jatuh_tempo'    => 'jatuh_tempo',
		'penjualan.total'          => 'total_so',
		'penjualan.diskon'         => 'diskon_so',
		'penjualan.pph'            => 'pph_so',
		'penjualan.biaya_tambahan' => 'biaya_tambahan_so',
		'(SELECT SUM(o_s_piutang_customer.total_piutang) FROM o_s_piutang_customer WHERE o_s_piutang_customer.customer_id = cabang.id AND o_s_piutang_customer.tipe_customer = 1 AND date(o_s_piutang_customer.tanggal) <= date(penjualan.created_date) )' => 'total_piutang',
		'master_tipe_bayar.id'   => 'id_tipe_bayar',
	);


	protected $datatable_columns2 = array(
		//column di table  => alias
		'penjualan.no_penjualan'   => 'no_so',
		'cabang.nama'              => 'nama_customer',
		'penjualan.tanggal_pesan'  => 'tanggal_so',
		'master_tipe_bayar.nama'   => 'nama_tipe',
		'b.lama_tempo'             => 'lama_tempo',
		'penjualan.id'			   => 'penjualan_id',
		'penjualan.jatuh_tempo'    => 'jatuh_tempo',
		'penjualan.total'          => 'total_so',
		'penjualan.diskon'         => 'diskon_so',
		'penjualan.pph'            => 'pph_so',
		'penjualan.biaya_tambahan' => 'biaya_tambahan_so',
		'(SELECT SUM(o_s_piutang_customer.total_piutang) FROM o_s_piutang_customer WHERE o_s_piutang_customer.customer_id = customer.id AND o_s_piutang_customer.tipe_customer = 2 AND date(o_s_piutang_customer.tanggal) <= date(penjualan.created_date) )' => 'total_piutang',
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
		$join1 = array('cabang', 'o_s_piutang_customer.customer_id = cabang.id AND o_s_piutang_customer.tipe_customer = 1');
		$join2 = array('penjualan', 'o_s_piutang_customer.transaksi_id = penjualan.id AND o_s_piutang_customer.transaksi_tipe = 1 AND penjualan.status_keuangan = 1');
		$join3 = array('customer_tipe_pembayaran b','penjualan.tipe_pembayaran = b.id AND b.tipe_customer = 1');
		$join4 = array('master_tipe_bayar','b.tipe_bayar_id = master_tipe_bayar.id');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

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

	public function get_max_id() {

		$this->db->select_max('id');
		return $this->db->get($this->_table)->result_array();
	}

	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable_add()
	{
		return $this->_fillable_add;
	}
	/**
	 * [fillable_edit description]
	 * @return [type] [description]
	 */
	public function fillable_edit()
	{
		return $this->_fillable_edit;
	}

}

/* End of file o_s_piutang_customer_m.php */
/* Location: ./application/models/keuangan/persetujuan_bebas_piutang/o_s_piutang_customer_m.php */