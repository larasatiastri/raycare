<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran_detail_item_m extends MY_Model {

	protected $_table      = 'pembayaran_detail_item';
	protected $_timestamps = true;
	protected $_order_by   = 'id';

	private $_fillable = array(
		'pasien_id',
		'dashboard_url', 
	);

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'o_s_pembayaran_transaksi.id'           => 'id',
		'o_s_pembayaran_transaksi.transaksi_id' => 'tindakan_id',
		'o_s_pembayaran_transaksi.cabang_id'    => 'cabang_id',
		'o_s_pembayaran_transaksi.pasien_id'    => 'pasien_id',
		'o_s_pembayaran_transaksi.tipe'         => 'tipe',
		'o_s_pembayaran_transaksi.diskon'       => 'diskon',
		'o_s_pembayaran_transaksi.rupiah'       => 'rupiah',
		'o_s_pembayaran_transaksi.is_active'    => 'is_active',
		'o_s_pembayaran_transaksi.is_pay'       => 'is_pay',
		'tindakan_hd.no_transaksi'              => 'no_transaksi',
		'tindakan_hd.penjamin_id'               => 'penjamin_id',
		'poliklinik.id'                         => 'poliklinik_id',
		'poliklinik.nama'                       => 'nama_poli',
		'penjamin.nama'                         => 'nama_penjamin',
		'cabang.nama'                           => 'nama_cabang',
	);

	function __construct()
	{
		parent::__construct();
	}

		/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable()
	{
		
		$join1 = array("tindakan_hd", $this->_table . '.transaksi_id = tindakan_hd.id', 'left');
		$join2 = array("penjamin", 'tindakan_hd.penjamin_id = penjamin.id', 'left');
		$join3 = array("poliklinik", $this->_table . '.poliklinik_id = poliklinik.id', 'left');
		$join4 = array("cabang", $this->_table . '.cabang_id = cabang.id', 'left');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active', 1);
		// $this->db->where($this->_table.'.pasien_id', $pasien_id);
		// $this->db->where('pembayaran_alamat.is_primary', 1);

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
		die(dump($result->records));
		return $result; 
	}

	
	/**
	 * [fillable_add description]
	 * @return [type] [description]
	 */
	public function fillable()
	{
		return $this->_fillable;
	}
	
}
