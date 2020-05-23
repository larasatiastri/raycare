<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_identitas_m extends MY_Model {

	protected $_table        = 'pengiriman_identitas';
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

	public function get_max_id() {

		$this->db->select_max('id');
		return $this->db->get($this->_table)->result_array();
	}

	public function save_id($data){

		$data['created_by']   = $this->session->userdata('user_id');			
		$data['created_date'] = date('Y-m-d H:i:s');

		$this->db->set($data);
		$this->db->insert($this->_table);
		$id = $this->db->insert_id();
		
		return $id;
	}

	public function get_data_item($id)
	{
		$this->db->select('pengiriman_detail.id AS id, pengiriman_detail.pengiriman_id AS pengiriman_id, item.id AS item_id, item.nama AS item_nama, 
							item.kode AS item_kode, item_satuan.id AS satuan_id, item_satuan.nama AS satuan, pengiriman_detail.box_paket_id AS box_paket_id, 
							item.is_identitas AS is_identitas');
		
		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where($this->_table.'.pengiriman_id', $id);
		$this->db->where($this->_table.'.box_paket_id IS NULL');

		return $this->db->get($this->_table);
	}

	public function get_data_box($id)
	{
		$this->db->select('pengiriman_detail.id AS id, pengiriman_detail.pengiriman_id AS pengiriman_id, item.id AS item_id, item.nama AS item_nama, item.kode AS item_kode, 
							item_satuan.id AS satuan_id, item_satuan.nama AS satuan, pengiriman_detail.box_paket_id AS box_paket_id');

		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where($this->_table.'.pengiriman_id', $id);
		$this->db->where($this->_table.'.box_paket_id IS NOT NULL');
		$this->db->group_by($this->_table.'.box_paket_id');

		return $this->db->get($this->_table);
	}

	public function get_data_by($id)
	{

		$sql = "SELECT * FROM pengiriman_identitas WHERE id in ('$id') GROUP BY inventory_id";

		return $this->db->query($sql);
	}

	public function get_data_inventory($id)
	{
        $wheres = rtrim($id,',');

		$sql = "SELECT * FROM pengiriman_identitas WHERE pengiriman_detail_id IN ('$wheres')";

		return $this->db->query($sql);
	}

	public function get_stok($id)
	{
		$format = "SELECT jumlah FROM pengiriman_identitas WHERE id = '$id'";

		return $this->db->query($format);
	}
}
