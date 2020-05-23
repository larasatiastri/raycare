<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_m extends MY_Model {

	protected $_table        = 'pengiriman';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pengiriman.id'                 => 'id', 
		'pengiriman.no_surat_jalan'     => 'no_surat_jalan',
		'pengiriman.penjualan_id'       => 'penjualan_id',
		'pengiriman.invoice_id'         => 'invoice_id',
		'pengiriman.customer_id'        => 'customer_id',
		'pengiriman.sopir_id'           => 'sopir_id',
		'pengiriman.customer_alamat_id' => 'customer_alamat_id',
		'pengiriman.tipe_cus'           => 'tipe_cus',
		'pengiriman.tanggal'            => 'tanggal',
		'pengiriman.status'             => 'status',
		'pengiriman.status_keuangan'    => 'status_keuangan',
		'pengiriman.diterima_oleh'      => 'diterima_oleh',
		'pengiriman.tanggal_diterima'   => 'tanggal_diterima',
		'pengiriman.is_active'          => 'is_active',
		'cabang.nama'                   => 'nama_customer',

		
		// '(SELECT SUM(permintaan_biaya_tipe.jumlah) FROM permintaan_biaya_tipe WHERE permintaan_biaya_id = permintaan_biaya_id)' => 'total_rupiah',
	);

	protected $datatable_columns_history = array(
		//column di table  => alias
		'pengiriman.id'                 => 'id', 
		'pengiriman.no_surat_jalan'     => 'no_surat_jalan',
		'pengiriman.penjualan_id'       => 'penjualan_id',
		'pengiriman.invoice_id'         => 'invoice_id',
		'pengiriman.customer_id'        => 'customer_id',
		'pengiriman.sopir_id'           => 'sopir_id',
		'pengiriman.customer_alamat_id' => 'customer_alamat_id',
		'pengiriman.tipe_cus'           => 'tipe_cus',
		'pengiriman.tanggal'            => 'tanggal',
		'pengiriman.status'             => 'status',
		'pengiriman.status_keuangan'    => 'status_keuangan',
		'pengiriman.diterima_oleh'      => 'diterima_oleh',
		'pengiriman.tanggal_diterima'   => 'tanggal_diterima',
		'pengiriman.is_active'          => 'is_active',
		'cabang.nama'                   => 'nama_customer',
		'penjualan.no_penjualan'        => 'no_penjualan',
		'penjualan.no_invoice'          => 'no_invoice',
		'penjualan.keterangan'          => 'keterangan',
		'penjualan.tanggal_pesan'       => 'tanggal_pesan',
		'`user`.nama'					=> 'nama_diterima_oleh',

		// '(SELECT SUM(permintaan_biaya_tipe.jumlah) FROM permintaan_biaya_tipe WHERE permintaan_biaya_id = permintaan_biaya_id)' => 'total_rupiah',
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($customer_id)
	{	
		$join1 = array('cabang', $this->_table.'.customer_id = cabang.id', 'LEFT');
		$join2 = array('cabang_alamat', $this->_table.'.customer_alamat_id = cabang_alamat.id', 'LEFT');

		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);
		$params['sort_by'] = 'pengiriman.tanggal';
		$params['sort_dir'] = 'desc';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.customer_id', $customer_id);
		$this->db->where($this->_table.'.tipe_cus', 1);
		$this->db->where($this->_table.'.`status`', 1);
		$this->db->where($this->_table.'.`is_active`', 1);
		// $this->db->group_by('id');
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.customer_id', $customer_id);
		$this->db->where($this->_table.'.tipe_cus', 1);
		$this->db->where($this->_table.'.`status`', 1);
		$this->db->where($this->_table.'.`is_active`', 1);

		// $this->db->group_by('id');
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.customer_id', $customer_id);
		$this->db->where($this->_table.'.tipe_cus', 1);
		$this->db->where($this->_table.'.`status`', 1);
		$this->db->where($this->_table.'.`is_active`', 1);

		// $this->db->group_by('id');
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

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

	public function get_datatable_history($customer_id)
	{	
		$join1 = array('cabang', $this->_table.'.customer_id = cabang.id', 'LEFT');
		$join2 = array('cabang_alamat', $this->_table.'.customer_alamat_id = cabang_alamat.id', 'LEFT');
		$join3 = array('penjualan', $this->_table.'.penjualan_id = penjualan.id', 'LEFT');
		$join4 = array('`user`', $this->_table.'.diterima_oleh = `user`.id', 'LEFT');

		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_history);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.customer_id', $customer_id);
		$this->db->where($this->_table.'.tipe_cus', 1);
		$this->db->where($this->_table.'.`status` >= 3');
		$this->db->where($this->_table.'.`is_active`', 1);
		
		// $this->db->group_by('id');
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.customer_id', $customer_id);
		$this->db->where($this->_table.'.tipe_cus', 1);
		$this->db->where($this->_table.'.`status` >= 3');
		$this->db->where($this->_table.'.`is_active`', 1);

		// $this->db->group_by('id');
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.customer_id', $customer_id);
		$this->db->where($this->_table.'.tipe_cus', 1);
		$this->db->where($this->_table.'.`status` >= 3');
		$this->db->where($this->_table.'.`is_active`', 1);

		// $this->db->group_by('id');
		// $this->db->where('user_id', $user_id);
		// $this->db->where('left(tanggal, 7) =', $date);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_history as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_history;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

}

/* End of file Penerimaan_barang.php */
/* Location: ./application/models/keuangan/Penerimaan_barang.php */