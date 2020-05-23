<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_permintaan_pembelian_detail_m extends MY_Model {

	protected $_table        = 'order_permintaan_pembelian_detail';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'order_permintaan_pembelian_detail.id'         						=> 'id', 
		'order_permintaan_pembelian_detail.order_permintaan_pembelian_id'   => 'order_permintaan_pembelian_id', 
		'order_permintaan_pembelian_detail.tanggal'   						=> 'tanggal', 
		'order_permintaan_pembelian_detail.is_selected'  					=> 'is_selected',
		'order_permintaan_pembelian_detail.item_id'  						=> 'item_id',
		'order_permintaan_pembelian_detail.item_satuan_id'  				=> 'item_satuan_id',
		'item.kode'   														=> 'kode', 
		'item.nama_item'   													=> 'nama_item', 
		'item_satuan.nama_satuan'   										=> 'nama_satuan', 
		'user.nama'   														=> 'user', 
		'user.nama'   														=> 'user', 
		'user_level.nama'   												=> 'user_level',
		'order_permintaan_pembelian.subjek'     							=> 'subjek',
		'order_permintaan_pembelian.keterangan' 							=> 'keterangan',
		'order_permintaan_pembelian.is_finish'  							=> 'is_finish',
		'(SELECT COUNT(*) FROM order_permintaan_pembelian_detail WHERE order_permintaan_pembelian_detail.order_permintaan_pembelian_id = 2)' => 'jumlah'
	
	);

	protected $datatable_columns_proses = array(
		//column di table  => alias
		'order_permintaan_pembelian_detail.id'         => 'id', 
		'order_permintaan_pembelian_detail.tanggal'   => 'tanggal', 
		'user.nama'   => 'user', 
		'user_level.nama'   => 'user_level',
		'order_permintaan_pembelian_detail.subjek'     => 'subjek',
		'order_permintaan_pembelian_detail.keterangan'     => 'keterangan',
		'order_permintaan_pembelian_detail.is_active'     => 'is_active',
		'order_permintaan_pembelian_detail.is_finish'     => 'is_finish',
		'(SELECT COUNT(*) FROM order_permintaan_pembelian_detail_detail WHERE order_permintaan_pembelian_detail_detail.order_permintaan_pembelian_detail_id = order_permintaan_pembelian_detail.id)'	=> 'jumlah_terdaftar',
		'(SELECT COUNT(*) FROM order_permintaan_pembelian_detail_detail_other WHERE order_permintaan_pembelian_detail_detail_other.order_permintaan_pembelian_detail_id = order_permintaan_pembelian_detail.id)'	=> 'jumlah_tidak_terdaftar',
		'MAX(persetujuan_permintaan_pembelian.status)' => 'status_terakhir'
		);

	protected $datatable_columns_item = array(
		//column di table  => alias
		'order_permintaan_pembelian_detail.id'         => 'id', 
		'order_permintaan_pembelian_detail_detail_other.id'   => 'id_detail', 
		'order_permintaan_pembelian_detail_detail_other.nama'   => 'nama', 
		'order_permintaan_pembelian_detail_detail_other.jumlah'   => 'jumlah', 
		
	);


	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	
	public function get_datatable($id)
	{	
		$datatable_columns_view_item = array(

		'order_permintaan_pembelian_detail.id'         						=> 'id', 
		'order_permintaan_pembelian_detail.order_permintaan_pembelian_id'   => 'order_permintaan_pembelian_id', 
		'order_permintaan_pembelian.tanggal'   								=> 'tanggal', 
		'order_permintaan_pembelian_detail.is_selected'  					=> 'is_selected',
		'order_permintaan_pembelian_detail.item_id'  						=> 'item_id',
		'order_permintaan_pembelian_detail.item_satuan_id'  				=> 'item_satuan_id',
		'order_permintaan_pembelian_detail.jumlah'  						=> 'jumlah',
		'item.kode'   														=> 'kode', 
		'item.nama'   														=> 'nama_item', 
		'a.nama'   															=> 'nama_satuan', 
		'b.nama'   															=> 'nama_satuan_ppp', 
		'order_permintaan_pembelian.subjek'     							=> 'subjek',
		'order_permintaan_pembelian.keterangan' 							=> 'keterangan',
		'order_permintaan_pembelian.is_finish'  							=> 'is_finish',
		// '(SELECT COUNT(order_permintaan_pembelian_id) FROM order_permintaan_pembelian_detail where order_permintaan_pembelian_id = order_pembelian_detail.order_permintaan_pembelian_id)' 			=> 'jumlah',
		'(SELECT COUNT(order_permintaan_pembelian_detail_id) FROM persetujuan_permintaan_pembelian WHERE tipe_permintaan = 1 AND order_permintaan_pembelian_id = order_permintaan_pembelian_detail.order_permintaan_pembelian_id)' 			=> 'jumlah_pesanan',
		'(SELECT COUNT(jumlah_persetujuan) FROM persetujuan_permintaan_pembelian WHERE tipe_permintaan = 1 AND order_permintaan_pembelian_id = order_permintaan_pembelian_detail.order_permintaan_pembelian_id)' => 'jumlah_setujui',
		'persetujuan_permintaan_pembelian.persetujuan_permintaan_pembelian_id'					=> 'persetujuan_permintaan_pembelian_id',
		'persetujuan_permintaan_pembelian.tipe_permintaan'					=> 'tipe_permintaan',
		'persetujuan_permintaan_pembelian.user_level_id'					=> 'user_level_id',
		'persetujuan_permintaan_pembelian.`order`'							=> 'order_ppp',
		'persetujuan_permintaan_pembelian.`status`'							=> 'status_ppp',
		'persetujuan_permintaan_pembelian.tanggal_baca'						=> 'tanggal_baca',		
		'persetujuan_permintaan_pembelian.dibaca_oleh'						=> 'dibaca_oleh',
		'persetujuan_permintaan_pembelian.jumlah_persetujuan'				=> 'jumlah_persetujuan',
		'persetujuan_permintaan_pembelian.satuan_id'						=> 'satuan'

		);

		$join1 = array('order_permintaan_pembelian','order_permintaan_pembelian_detail.order_permintaan_pembelian_id = order_permintaan_pembelian.id', 'LEFT');
		$join2 = array('persetujuan_permintaan_pembelian','persetujuan_permintaan_pembelian.order_permintaan_pembelian_detail_id = order_permintaan_pembelian_detail.id', 'LEFT');
		$join3 = array('item', $this->_table.'.item_id = item.id', 'RIGHT');
		$join4 = array('item_satuan a',$this->_table.'.item_satuan_id = a.id', 'RIGHT');
		$join5 = array('item_satuan b', 'persetujuan_permintaan_pembelian.satuan_id = b.id', 'LEFT');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_view_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		// $this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 1);
		// $this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		// $this->db->where('a.id = order_permintaan_pembelian_detail.item_satuan_id');
		// $this->db->where('b.id = persetujuan_permintaan_pembelian.satuan_id');
		// $this->db->group_by('order_permintaan_pembelian_detail.item_id');
		// $this->db->count_all('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		// $this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 1);
		// $this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		// $this->db->where('a.id = order_permintaan_pembelian_detail.item_satuan_id');
		// $this->db->where('b.id = persetujuan_permintaan_pembelian.satuan_id');
		// $this->db->group_by('order_permintaan_pembelian_detail.item_id');
		// $this->db->count_all('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		// $this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 1);
		// $this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		// $this->db->where('a.id = order_permintaan_pembelian_detail.item_satuan_id');
		// $this->db->where('b.id = persetujuan_permintaan_pembelian.satuan_id');
		// $this->db->group_by('order_permintaan_pembelian_detail.item_id');
		// $this->db->count_all('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns_view_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns_view_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}
	
}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/daftar_permintaan_po_m.php */