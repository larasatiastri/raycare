<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Draf_pengiriman_detail_m extends MY_Model {

	protected $_table        = 'draf_pengiriman_detail';
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

		$this->db->select_max('draf_pengiriman_detail_id');
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
		$this->db->select('draf_pengiriman_detail.draf_pengiriman_detail_id AS id, draf_pengiriman_detail.draf_pengiriman_id AS draf_pengiriman_id, item.id AS item_id, item.nama AS item_nama, 
							item.kode AS item_kode, item_satuan.id AS satuan_id, item_satuan.nama AS satuan, draf_pengiriman_detail.box_paket_id AS box_paket_id, 
							item.is_identitas AS is_identitas');
		
		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where($this->_table.'.draf_pengiriman_id', $id);
		$this->db->where($this->_table.'.box_paket_id IS NULL');

		return $this->db->get($this->_table);
	}

	public function get_data_box($id)
	{
		$this->db->select('draf_pengiriman_detail.draf_pengiriman_detail_id AS id, draf_pengiriman_detail.draf_pengiriman_id AS draf_pengiriman_id, item.id AS item_id, item.nama AS item_nama, item.kode AS item_kode, 
							item_satuan.id AS satuan_id, item_satuan.nama AS satuan, draf_pengiriman_detail.box_paket_id AS box_paket_id');

		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where($this->_table.'.draf_pengiriman_id', $id);
		$this->db->where($this->_table.'.box_paket_id IS NOT NULL');
		$this->db->group_by($this->_table.'.box_paket_id');

		return $this->db->get($this->_table);
	}

	public function get_data_edit_konversi($penjualan_detail_id, $item_id, $item_satuan_id) {

		$sql = "SELECT
					draf_pengiriman_detail.draf_pengiriman_detail_id,
					draf_pengiriman_penjualan_detail.draf_pengiriman_penjualan_detail_id,
					draf_pengiriman_detail.jumlah,
					draf_pengiriman_detail.jumlah_konversi
				FROM
					draf_pengiriman_detail
				JOIN draf_pengiriman_penjualan_detail ON draf_pengiriman_detail.draf_pengiriman_detail_id = draf_pengiriman_penjualan_detail.draf_pengiriman_detail_id
				WHERE
					penjualan_detail_id = $penjualan_detail_id
				AND draf_pengiriman_detail.item_id = $item_id
				AND draf_pengiriman_detail.item_satuan_id = $item_satuan_id";

		return $this->db->query($sql)->result_array();
	}

	public function get_data_edit_identitas($penjualan_detail_id, $item_id, $item_satuan_id, $inventory_identitas_id) {

		$sql = "SELECT
					draf_pengiriman_detail.draf_pengiriman_detail_id,
					draf_pengiriman_penjualan_detail.draf_pengiriman_penjualan_detail_id,
					draf_pengiriman_identitas.draf_pengiriman_identitas_id,
					draf_pengiriman_detail.jumlah,
					draf_pengiriman_identitas.jumlah_konversi,
					draf_pengiriman_identitas.jumlah AS jumlah_identitas,
					draf_pengiriman_identitas_detail.`value` AS isi
					
				FROM
					draf_pengiriman_detail
				JOIN draf_pengiriman_penjualan_detail ON draf_pengiriman_detail.draf_pengiriman_detail_id = draf_pengiriman_penjualan_detail.draf_pengiriman_detail_id
				JOIN draf_pengiriman_identitas ON draf_pengiriman_identitas.draf_pengiriman_detail_id = draf_pengiriman_detail.draf_pengiriman_detail_id
				JOIN draf_pengiriman_identitas_detail ON draf_pengiriman_identitas_detail.draf_pengiriman_identitas_id = draf_pengiriman_identitas.draf_pengiriman_identitas_id
				WHERE
					penjualan_detail_id = $penjualan_detail_id
				AND draf_pengiriman_detail.item_id = $item_id
				AND draf_pengiriman_detail.item_satuan_id = $item_satuan_id
				AND draf_pengiriman_identitas_detail.inventory_identitas_id = '".$inventory_identitas_id."'
				GROUP BY draf_pengiriman_identitas_detail.draf_pengiriman_identitas_id";

		return $this->db->query($sql)->result_array();
	}

	// konversi
	public function get_data_stock_draf_konversi($item_id, $item_satuan_id) {

		$sql = "SELECT
					draf_pengiriman_detail.draf_pengiriman_detail_id AS draf_pengiriman_detail_id,
					draf_pengiriman_detail.draf_pengiriman_id AS draf_pengiriman_id,
					draf_pengiriman_detail.item_id AS item_id,
					draf_pengiriman_detail.item_satuan_id AS item_satuan_id,
					SUM(draf_pengiriman_detail.jumlah) AS jumlah,
					SUM(draf_pengiriman_detail.jumlah_konversi) AS jumlah_konversi
				FROM
					draf_pengiriman_detail
				WHERE 
				draf_pengiriman_detail.item_id = $item_id
				AND draf_pengiriman_detail.item_satuan_id = $item_satuan_id ";

		return $this->db->query($sql)->result_array();
	}

	// identitas
	public function get_data_stock_draf($item_id, $item_satuan_id, $inventory_identitas_id){

		$sql = "SELECT
					draf_pengiriman_detail.draf_pengiriman_detail_id,
					draf_pengiriman_penjualan_detail.draf_pengiriman_penjualan_detail_id,
					draf_pengiriman_identitas.draf_pengiriman_identitas_id,
					SUM(draf_pengiriman_detail.jumlah) AS jumlah,
					SUM(draf_pengiriman_identitas.jumlah_konversi) AS jumlah_konversi,
					SUM(draf_pengiriman_identitas.jumlah) AS jumlah_identitas,
					draf_pengiriman_identitas_detail.`value` AS isi
				FROM
					draf_pengiriman_detail
				JOIN draf_pengiriman_penjualan_detail ON draf_pengiriman_detail.draf_pengiriman_detail_id = draf_pengiriman_penjualan_detail.draf_pengiriman_detail_id
				JOIN draf_pengiriman_identitas ON draf_pengiriman_identitas.draf_pengiriman_detail_id = draf_pengiriman_detail.draf_pengiriman_detail_id
				JOIN draf_pengiriman_identitas_detail ON draf_pengiriman_identitas_detail.draf_pengiriman_identitas_id = draf_pengiriman_identitas.draf_pengiriman_identitas_id
				WHERE draf_pengiriman_detail.item_id = $item_id
				AND draf_pengiriman_detail.item_satuan_id = $item_satuan_id
				AND draf_pengiriman_identitas_detail.inventory_identitas_id = $inventory_identitas_id";

		return $this->db->query($sql)->result_array();
	}


	public function get_data_draf($penjualan_detail_id) {

		$sql = "SELECT
					draf_pengiriman_detail.draf_pengiriman_detail_id,
					draf_pengiriman_penjualan_detail.draf_pengiriman_penjualan_detail_id,
					draf_pengiriman_detail.jumlah,
					draf_pengiriman_detail.jumlah_konversi,
					draf_pengiriman_detail.item_satuan_id,
					item_satuan.nama AS satuan
				FROM
					draf_pengiriman_detail
				JOIN draf_pengiriman_penjualan_detail ON draf_pengiriman_detail.draf_pengiriman_detail_id = draf_pengiriman_penjualan_detail.draf_pengiriman_detail_id
				JOIN item_satuan ON draf_pengiriman_detail.item_satuan_id = item_satuan.id
				WHERE
					penjualan_detail_id = $penjualan_detail_id
				-- AND draf_pengiriman_detail.item_id = $item_id
				";

		return $this->db->query($sql)->result_array();

	}

	public function get_data_identitas($penjualan_detail_id){

		$sql = "SELECT
					draf_pengiriman_detail.item_id AS item_id,
					draf_pengiriman_detail.item_satuan_id AS item_satuan_id,
					draf_pengiriman_detail.item_satuan_primary_id AS item_satuan_primary_id,
					draf_pengiriman_detail.jumlah AS jumlah,
					draf_pengiriman_detail.jumlah_konversi AS jumlah_konversi,
					draf_pengiriman_identitas.draf_pengiriman_identitas_id AS draf_pengiriman_identitas_id,
					draf_pengiriman_identitas.jumlah AS jumlah_identitas,
					draf_pengiriman_identitas.jumlah_konversi AS jumlah_konversi_identitas
				FROM
					draf_pengiriman_identitas
				LEFT JOIN draf_pengiriman_detail ON draf_pengiriman_identitas.draf_pengiriman_detail_id = draf_pengiriman_detail.draf_pengiriman_detail_id
				LEFT JOIN draf_pengiriman_penjualan_detail ON draf_pengiriman_penjualan_detail.draf_pengiriman_detail_id = draf_pengiriman_detail.draf_pengiriman_detail_id
				WHERE
					draf_pengiriman_detail.box_paket_id IS NULL
				AND draf_pengiriman_penjualan_detail.penjualan_detail_id = $penjualan_detail_id
				GROUP BY draf_pengiriman_identitas.draf_pengiriman_identitas_id";

		return $this->db->query($sql)->result_array();
	}

	public function get_data_value_identitas($draf_pengiriman_identitas_id) {

		$sql = "SELECT
					draf_pengiriman_detail.draf_pengiriman_detail_id AS draf_pengiriman_detail_id,
					draf_pengiriman_detail.item_id AS item_id,
					draf_pengiriman_detail.item_satuan_id AS item_satuan_id,
					item_satuan.nama AS satuan,
					draf_pengiriman_detail.item_satuan_primary_id AS item_satuan_primary_id,
					draf_pengiriman_detail.jumlah AS jumlah,
					draf_pengiriman_detail.jumlah_konversi AS jumlah_konversi,
					draf_pengiriman_identitas.draf_pengiriman_identitas_id AS draf_pengiriman_identitas_id,
					draf_pengiriman_identitas.jumlah AS jumlah_identitas,
					draf_pengiriman_identitas.jumlah_konversi AS jumlah_konversi_identitas,
					draf_pengiriman_identitas_detail.draf_pengiriman_identitas_detail_id AS draf_pengiriman_identitas_detail_id,
					draf_pengiriman_identitas_detail.inventory_identitas_id AS inventory_identitas_id,
					draf_pengiriman_identitas_detail.identitas_id AS identitas_id,
					draf_pengiriman_identitas_detail.judul AS judul,
					draf_pengiriman_identitas_detail.`value` AS isi,
					draf_pengiriman_penjualan_detail.draf_pengiriman_penjualan_detail_id AS draf_pengiriman_penjualan_detail_id
				FROM
					draf_pengiriman_identitas
				LEFT JOIN draf_pengiriman_identitas_detail ON draf_pengiriman_identitas_detail.draf_pengiriman_identitas_id = draf_pengiriman_identitas.draf_pengiriman_identitas_id
				LEFT JOIN draf_pengiriman_detail ON draf_pengiriman_identitas.draf_pengiriman_detail_id = draf_pengiriman_detail.draf_pengiriman_detail_id
				LEFT JOIN draf_pengiriman_penjualan_detail ON draf_pengiriman_penjualan_detail.draf_pengiriman_detail_id = draf_pengiriman_detail.draf_pengiriman_detail_id
				LEFT JOIN item_satuan ON item_satuan.id = draf_pengiriman_detail.item_satuan_id
				WHERE
					draf_pengiriman_detail.box_paket_id IS NULL
				AND draf_pengiriman_identitas.draf_pengiriman_identitas_id = $draf_pengiriman_identitas_id ";

		return $this->db->query($sql)->result_array();
	}

	public function get_data_pilih_box($draf_pengiriman_id, $box_paket_id)
	{
		$this->db->select('draf_pengiriman_detail.draf_pengiriman_detail_id AS id, draf_pengiriman_detail.draf_pengiriman_id AS draf_pengiriman_id, 
							draf_pengiriman_detail.kode_box_paket AS kode_box_paket, draf_pengiriman_detail.box_paket_id AS box_paket_id, draf_pengiriman_detail.jumlah_paket AS jumlah_paket');

		$this->db->where($this->_table.'.draf_pengiriman_id', $draf_pengiriman_id);
		$this->db->where($this->_table.'.box_paket_id', $box_paket_id);
		$this->db->group_by($this->_table.'.kode_box_paket');

		return $this->db->get($this->_table)->result_array();
	}

	public function get_jumlah_box($box_paket_id, $draf_pengiriman_id) {

		$this->db->select('kode_box_paket');
		$this->db->where('box_paket_id', $box_paket_id);
		$this->db->where('draf_pengiriman_id', $draf_pengiriman_id);
		$this->db->group_by($this->_table.'.kode_box_paket');

		return $this->db->get($this->_table)->result_array();
	}
}
