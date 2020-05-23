<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_permintaan_pembelian_detail_other_m extends MY_Model {

	protected $_table        = 'order_permintaan_pembelian_detail_other';
	protected $_order_by     = 'order_permintaan_pembelian_id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		
		);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	
	public function get_datatable($id, $user_level_id)
	{	
		$datatable_columns_view_item = array(

		'order_permintaan_pembelian_detail_other.id'         						=> 'id', 
		'order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id'   	=> 'order_permintaan_pembelian_id', 
		'order_permintaan_pembelian.tanggal'   										=> 'tanggal', 
		'order_permintaan_pembelian_detail_other.is_selected'  						=> 'is_selected',
		'order_permintaan_pembelian_detail_other.satuan'  							=> 'satuan',
		'order_permintaan_pembelian_detail_other.nama'  							=> 'nama',
		'order_permintaan_pembelian_detail_other.jumlah'  							=> 'jumlah',
		'order_permintaan_pembelian.subjek'     									=> 'subjek',
		'order_permintaan_pembelian.keterangan' 									=> 'keterangan',
		'order_permintaan_pembelian.is_finish'  									=> 'is_finish',
		// '(SELECT COUNT(*) FROM order_permintaan_pembelian_detail_other)' 			=> 'jumlah',
		'(SELECT COUNT(jumlah_persetujuan) FROM persetujuan_permintaan_pembelian WHERE tipe_permintaan = 2 AND order_permintaan_pembelian_id = order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id)' 			=> 'jumlah_setujui',
		'persetujuan_permintaan_pembelian.persetujuan_permintaan_pembelian_id'					=> 'persetujuan_permintaan_pembelian_id',
		'persetujuan_permintaan_pembelian.tipe_permintaan'					=> 'tipe_permintaan',
		'persetujuan_permintaan_pembelian.user_level_id'					=> 'user_level_id',
		'persetujuan_permintaan_pembelian.`order`'							=> 'order_ppp',
		'persetujuan_permintaan_pembelian.`status`'							=> 'status_ppp',
		'persetujuan_permintaan_pembelian.tanggal_baca'						=> 'tanggal_baca',		
		'persetujuan_permintaan_pembelian.dibaca_oleh'						=> 'dibaca_oleh',
		'persetujuan_permintaan_pembelian.satuan_id'						=> 'satuan_ppp',
		'persetujuan_permintaan_pembelian.jumlah_persetujuan'						=> 'jumlah_persetujuan',
		'item_satuan.nama'													=> 'nama_satuan',

		);

		$join1 = array('order_permintaan_pembelian','order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id = order_permintaan_pembelian.id', 'LEFT');
		$join2 = array('persetujuan_permintaan_pembelian','order_permintaan_pembelian.id = persetujuan_permintaan_pembelian.order_permintaan_pembelian_id', 'LEFT');
		$join3 = array('item_satuan','persetujuan_permintaan_pembelian.satuan_id = item_satuan.id', 'LEFT');
		$join_tables = array($join1, $join2, $join3) ;

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_view_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id', $id);
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 2);
		// $this->db->group_by('order_permintaan_pembelian_detail_other.id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id', $id);
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 2);
		// $this->db->where('item_satuan.id = order_permintaan_pembelian_detail.item_satuan_id');
		// $this->db->group_by('order_permintaan_pembelian_detail_other.id');
		// $this->db->group_by('order_permintaan_pembelian_detail.item_id');

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id', $id);
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 2);
		// $this->db->where('item_satuan.id = order_permintaan_pembelian_detail.item_satuan_id');
		// $this->db->group_by('order_permintaan_pembelian_detail_other.id');
		// $this->db->group_by('order_permintaan_pembelian_detail.item_id');

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