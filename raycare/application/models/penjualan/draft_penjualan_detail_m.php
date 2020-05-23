<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Draft_penjualan_detail_m extends MY_Model {

	protected $_table        = 'draf_penjualan_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'customer.nama'               => 'customer_nama', 
		'customer.kode'               => 'customer_kode', 
		'penjualan_detail.tanggal'    => 'tanggal',
		'penjualan_detail.keterangan' => 'keterangan',
		'customer.id'                 => 'customer_id', 
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

	public function get_max_tgl_kirim($draf_penjualan_id){

		$this->db->select_max('tanggal_kirim');
		$this->db->where('draf_penjualan_id', $draf_penjualan_id);

		return $this->db->get($this->_table)->result_array();
	}

	public function get_jumlah_stock_penjualan($item_id) {

		$this->db->select_sum('jumlah_konversi');
		$this->db->where('item_id', $item_id);

		return $this->db->get($this->_table)->result_array();
	}

	public function get_data_item($id)
	{
		$this->db->select('draf_penjualan_detail.id AS id,
							draf_penjualan_detail.draf_penjualan_id AS draf_penjualan_id,
							item.id AS item_id,
							item.nama AS item_nama,
							item.kode AS item_kode,
							draf_penjualan_detail.item_satuan_id AS satuan_id,
							item_satuan.nama AS satuan,
							draf_penjualan_detail.jumlah AS jumlah,
							draf_penjualan_detail.box_paket_id AS box_paket_id,
							draf_penjualan_detail.jumlah_paket AS jumlah_paket,
							draf_penjualan_detail.item_satuan_id_primary AS item_satuan_id_primary,
							draf_penjualan_detail.jumlah_konversi AS jumlah_konversi,
							draf_penjualan_detail.harga AS harga,
							draf_penjualan_detail.diskon AS diskon,
							draf_penjualan_detail.tanggal_kirim AS tanggal_kirim,
							draf_penjualan_detail.status AS status,
							draf_penjualan_detail.keterangan_tolak_gudang AS keterangan_tolak'
						);

		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where($this->_table.'.draf_penjualan_id', $id);
		$this->db->where($this->_table.'.box_paket_id IS NULL');

		return $this->db->get($this->_table);
	}

	public function get_data_box($id)
	{
		$this->db->select('draf_penjualan_detail.id AS id,
							draf_penjualan_detail.draf_penjualan_id AS draf_penjualan_id,
							item.id AS item_id,
							item.nama AS item_nama,
							item.kode AS item_kode,
							draf_penjualan_detail.item_satuan_id AS satuan_id,
							item_satuan.nama AS satuan,
							draf_penjualan_detail.jumlah AS jumlah,
							draf_penjualan_detail.box_paket_id AS box_paket_id,
							draf_penjualan_detail.jumlah_paket AS jumlah_paket,
							draf_penjualan_detail.item_satuan_id_primary AS item_satuan_id_primary,
							draf_penjualan_detail.jumlah_konversi AS jumlah_konversi,
							SUM(draf_penjualan_detail.harga) AS harga,
							draf_penjualan_detail.diskon AS diskon,
							draf_penjualan_detail.tanggal_kirim AS tanggal_kirim,
							draf_penjualan_detail.status AS status,
							draf_penjualan_detail.keterangan_tolak_gudang AS keterangan_tolak'

						);

		$this->db->join('item',$this->_table.'.item_id = item.id', 'left');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id', 'left');
		$this->db->where($this->_table.'.draf_penjualan_id', $id);
		$this->db->where($this->_table.'.box_paket_id IS NOT NULL');
		$this->db->group_by($this->_table.'.box_paket_id');

		return $this->db->get($this->_table);
	}

	public function delete_item_box($draf_penjualan_id, $box_paket_id)
	{
		$format = "DELETE FROM draf_penjualan_detail 
					WHERE draf_penjualan_id = $draf_penjualan_id AND box_paket_id = $box_paket_id ";

		return $this->db->query($format);
	}

	public function update_item_box($tanggal_kirim, $draf_penjualan_id, $box_paket_id)
	{
		$date          = date('Y-m-d H:i:s');
		$id            = $this->session->userdata('user_id');
		$tanggal_kirim = date('Y-m-d H:i:s', strtotime($tanggal_kirim));
		$format = "UPDATE draf_penjualan_detail SET tanggal_kirim = '$tanggal_kirim', modified_by = $id, modified_date = '$date' 
					WHERE draf_penjualan_id = $draf_penjualan_id AND box_paket_id = $box_paket_id ";

		return $this->db->query($format);
	}


	public function get_max_id() {

		$this->db->select_max('id');
		return $this->db->get($this->_table)->result_array();
	}

	public function get_max_id_penjualan_detail() {

		$SQL = 'SELECT MAX(id) as max_id FROM draf_penjualan_detail';
		return $this->db->query($SQL);
	}

	public function save_id($data){

		$data['created_by']   = $this->session->userdata('user_id');			
		$data['created_date'] = date('Y-m-d H:i:s');

		$this->db->set($data);
		$this->db->insert($this->_table);
		$id = $this->db->insert_id();

		return $id;
	}

	public function delete_where($wheres){
        
        $this->db->where($wheres);
        $this->db->delete($this->_table);
    }

}

