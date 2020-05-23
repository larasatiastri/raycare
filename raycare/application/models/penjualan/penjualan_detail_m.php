<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penjualan_detail_m extends MY_Model {

	protected $_table        = 'penjualan_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'customer.nama'        => 'customer_nama', 
		'customer.kode'        => 'customer_kode', 
		'penjualan_detail.tanggal'    => 'tanggal',
		'penjualan_detail.keterangan' => 'keterangan',
		'customer.id'          => 'customer_id', 
		'penjualan_detail.id'         => 'id',
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_draft_penjualan($type)
	{	
		$join1 = array('customer', $this->_table.'.customer_id = customer.id');
		$join_tables = array($join1);

		$wheres = array(
			'draf_penjualan.is_active'     => 1,
			'draf_penjualan.status'        => 1,
			'draf_penjualan.tipe_customer' => $type
		);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

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

	public function get_data_item($id)
	{
		$this->db->select('penjualan_detail.id AS id, penjualan_detail.penjualan_id AS penjualan_id, item.id AS item_id, item.nama AS item_nama, item.kode AS item_kode, 
								item_satuan.id AS satuan_id, item_satuan.nama AS satuan, penjualan_detail.jumlah AS jumlah, penjualan_detail.diskon AS diskon, 
								penjualan_detail.box_paket_id AS box_paket_id, penjualan_detail.jumlah_paket AS jumlah_paket, penjualan_detail.tanggal_kirim AS tanggal_kirim, penjualan_detail.harga AS harga');
		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where($this->_table.'.penjualan_id', $id);
		$this->db->where($this->_table.'.box_paket_id IS NULL');

		return $this->db->get($this->_table);
	}

	public function get_data_box($id)
	{
		$this->db->select('penjualan_detail.id AS id, penjualan_detail.penjualan_id AS penjualan_id, item.id AS item_id, item.nama AS item_nama, item.kode AS item_kode, 
								item_satuan.id AS satuan_id, item_satuan.nama AS satuan, penjualan_detail.jumlah AS jumlah, penjualan_detail.diskon AS diskon, 
								penjualan_detail.box_paket_id AS box_paket_id, penjualan_detail.jumlah_paket AS jumlah_paket, SUM(penjualan_detail.harga) AS harga, penjualan_detail.tanggal_kirim AS tanggal_kirim');
		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where($this->_table.'.penjualan_id', $id);
		$this->db->where($this->_table.'.box_paket_id IS NOT NULL');
		$this->db->group_by($this->_table.'.box_paket_id');

		return $this->db->get($this->_table);
	}

	public function get_jumlah_stock_penjualan($item_id) {

		$this->db->select_sum('jumlah_konversi');
		$this->db->where('item_id', $item_id);

		return $this->db->get($this->_table)->result_array();

	}

	public function get_max_id_detail_penjualan()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `penjualan_detail` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}
}

