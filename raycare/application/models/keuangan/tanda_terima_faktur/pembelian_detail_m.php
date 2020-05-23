<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_detail_m extends MY_Model {

	protected $_table        = 'pembelian_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;


	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'pembelian_detail.id'             => 'id', 
		'pembelian_detail.kode'           => 'kode', 
		'pembelian_detail.nama'           => 'nama', 
		'pembelian_detail.alamat'         => 'alamat',
		'pembelian_detail.nomor_telepon1' => 'nomor_telepon1',
		'pembelian_detail.nomor_telepon2' => 'nomor_telepon2',
		'pembelian_detail.nomor_fax'      => 'nomor_fax',
		'pembelian_detail.is_active'      => 'is_active'
		
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
		$join_tables = array();

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('is_active',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('is_active',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('is_active',1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns as $col => $alias)
		{
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

	public function get_data_detail($pembelian_id)
	{
		$this->db->select("item.id as item_id, item.kode, item.nama, item_satuan.nama as nama_satuan, pembelian_detail.id as id, pembelian_detail.jumlah_pesan, pembelian_detail.harga_beli, pembelian_detail.diskon, pembelian_detail.tanggal_kirim, pembelian_detail.item_satuan_id, pembelian_detail.urutan, pembelian_detail.jumlah_disetujui, pembelian_detail.jumlah_diterima, (SELECT CONCAT_WS(' ',SUM(jumlah_primary),item_satuan.nama) FROM `pmb_po_detail` JOIN item_satuan ON pmb_po_detail.item_satuan_primary_id = item_satuan.id WHERE pmb_po_detail.po_detail_id = pembelian_detail.id GROUP BY pmb_po_detail.po_detail_id, pmb_po_detail.item_satuan_primary_id) AS jumlah_terima, pembelian_detail.jumlah_belum_diterima, pembelian_detail.`status`, pembelian_detail.disetujui_oleh, pembelian_detail.keterangan");
		$this->db->join('item',$this->_table.'.item_id = item.id');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id');
		$this->db->where($this->_table.'.pembelian_id', $pembelian_id);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->order_by($this->_table.'.urutan', 'asc');

		return $this->db->get($this->_table)->result_array();
	}
	public function get_data_detail_invoice($pembelian_id)
	{
		$this->db->select('item.id as item_id, item.kode, item.nama, item_satuan.nama as nama_satuan, pembelian_detail.id as id, pembelian_detail.jumlah_pesan, pembelian_detail.harga_beli, pembelian_detail.diskon, pembelian_detail.tanggal_kirim, pembelian_detail.item_satuan_id, pembelian_detail.urutan, pembelian_detail.jumlah_disetujui, pembelian_detail.`status`, pembelian_detail.disetujui_oleh, pembelian_detail.keterangan');
		$this->db->join('item',$this->_table.'.item_id = item.id');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id');
		$this->db->where($this->_table.'.pembelian_id', $pembelian_id);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->where($this->_table.'.status', 2);
		$this->db->order_by($this->_table.'.urutan', 'asc');

		return $this->db->get($this->_table)->result_array();
	}

	public function get_tanggal_kirim($pembelian_id)
	{
		$this->db->select('tanggal_kirim');
		$this->db->where($this->_table.'.pembelian_id', $pembelian_id);
		$this->db->where($this->_table.'.is_active', 1);
		$this->db->group_by($this->_table.'.tanggal_kirim');

		return $this->db->get($this->_table)->result_array();
	}

	public function get_max_id_detail_pembelian()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,13,4)) AS max_id FROM `pembelian_detail` WHERE SUBSTR(`id`,5,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_detail_persetujuan($id_detail)
	{
		$format = "SELECT CASE WHEN pembelian_detail.`status` = '2' THEN 'Disetujui' WHEN `status` = '3' THEN 'Ditolak' END as `status`, user.nama, pembelian_detail.jumlah_pesan, pembelian_detail.jumlah_disetujui FROM pembelian_detail JOIN user ON pembelian_detail.disetujui_oleh = user.id WHERE pembelian_detail.id = '$id_detail'";
		return $this->db->query($format)->row(0);
	}

	public function get_data_pmb($po_detail_id)
	{
		$this->db->select('pmb_po_detail.created_date, pmb_po_detail.jumlah, item_satuan.nama as nama_satuan, `user`.nama');
		$this->db->join('item_satuan', 'pmb_po_detail.item_satuan_id = item_satuan.id', 'left');
		$this->db->join('`user`', 'pmb_po_detail.created_by = `user`.id', 'left');
		$this->db->where('po_detail_id', $po_detail_id);

		return $this->db->get('pmb_po_detail')->result_array();
	}

	public function get_po_detail($po_detail_id)
	{
		$this->db->select('item.id as item_id, item.kode, item.nama, item_satuan.nama as nama_satuan, pembelian_detail.id as id, pembelian_detail.jumlah_pesan, pembelian_detail.harga_beli, pembelian_detail.diskon, pembelian_detail.tanggal_kirim, pembelian_detail.item_satuan_id, pembelian_detail.urutan, pembelian_detail.jumlah_disetujui, pembelian_detail.`status`, pembelian_detail.disetujui_oleh, pembelian_detail.keterangan');
		$this->db->join('item',$this->_table.'.item_id = item.id');
		$this->db->join('item_satuan',$this->_table.'.item_satuan_id = item_satuan.id');
		$this->db->where($this->_table.'.id', $po_detail_id);

		return $this->db->get($this->_table)->row(0);
	}

}

/* End of file pembelian_detail_m.php */
/* Location: ./application/models/pembelian/pembelian_detail_m.php */