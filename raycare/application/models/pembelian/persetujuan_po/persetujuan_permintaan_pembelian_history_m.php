<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_permintaan_pembelian_history_m extends MY_Model {

	protected $_table        = 'persetujuan_permintaan_pembelian_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'persetujuan_permintaan_pembelian_history.id'   										=> 'id', 
		'persetujuan_permintaan_pembelian_history.order_permintaan_pembelian_id'    	 => 'order_permintaan_pembelian_id', 
		'persetujuan_permintaan_pembelian_history.order_permintaan_pembelian_detail_id'  => 'order_permintaan_pembelian_detail_id', 
		'persetujuan_permintaan_pembelian_history.user_level_id'    					 => 'user_level_id', 
		'persetujuan_permintaan_pembelian_history.`order`'                            	 => 'p_p_p_order', 
		'persetujuan_permintaan_pembelian_history.`status`'    							 => 'status', 
		'persetujuan_permintaan_pembelian_history.tipe_permintaan'    					=> 'tipe_permintaan', 
		'user.nama'   															=> 'user', 
		'user_level.nama'   													=> 'user_level',
		'persetujuan_permintaan_pembelian_history.tanggal_baca'     					=> 'tanggal_baca',
		'persetujuan_permintaan_pembelian_history.dibaca_oleh' 							=> 'dibaca_oleh',
		// 'persetujuan_permintaan_pembelian_history.is_active'  							=> 'is_active',
		'persetujuan_permintaan_pembelian_history.tanggal_persetujuan'  				=> 'tanggal_persetujuan',
		'persetujuan_permintaan_pembelian_history.disetujui_oleh'  						=> 'disetujui_oleh',
		'persetujuan_permintaan_pembelian_history.jumlah_persetujuan'  					=> 'jumlah_persetujuan',
		'persetujuan_permintaan_pembelian_history.satuan_id'  							=> 'satuan_id',
		'order_permintaan_pembelian.id'  										=> 'id',
		'order_permintaan_pembelian.tanggal'  									=> 'tanggal',
		'order_permintaan_pembelian.subjek'  									=> 'subjek',
		'order_permintaan_pembelian.keterangan'  								=> 'keterangan',
		'order_permintaan_pembelian.tipe'  										=> 'tipe',
		'order_permintaan_pembelian_detail.item_id'  							=> 'item_id',
		'order_permintaan_pembelian_detail.item_satuan_id'  					=> 'item_satuan_id',
		'item.nama'  															=> 'nama_item',
		'item_satuan.nama'  													=> 'nama_item_satuan',
		'(SELECT COUNT(*) FROM order_permintaan_pembelian_detail WHERE order_permintaan_pembelian_detail.order_permintaan_pembelian_id = order_permintaan_pembelian.id)'	=> 'jumlah_terdaftar',
		'(SELECT COUNT(*) FROM order_permintaan_pembelian_detail_other WHERE order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id = order_permintaan_pembelian.id)'	=> 'jumlah_tidak_terdaftar',
		// 'persetujuan_permintaan_pembelian.satuan_id'  							=> 'satuan_id',
	);



	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	
	public function get_datatable($user_level_id)
	{	
		$join1 = array('order_permintaan_pembelian', $this->_table.'.order_permintaan_pembelian_id = order_permintaan_pembelian.id', 'LEFT');
		$join2 = array('order_permintaan_pembelian_detail', 'order_permintaan_pembelian.id = order_permintaan_pembelian_detail.order_permintaan_pembelian_id', 'LEFT');
		$join3 = array('item', 'order_permintaan_pembelian_detail.item_id = item.id', 'LEFT');
		$join4 = array('item_satuan','order_permintaan_pembelian_detail.item_satuan_id = item_satuan.id', 'LEFT');
		$join5 = array('user','order_permintaan_pembelian.user_id = user.id', 'LEFT');
		$join6 = array('user_level', 'user.user_level_id = user_level.id', 'LEFT');
		$join_tables = array($join1, $join2, $join3, $join4, $join5, $join6);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('persetujuan_permintaan_pembelian_history.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian_history.`status` <= 3');
		$this->db->group_by('persetujuan_permintaan_pembelian_history.user_level_id');
		$this->db->group_by('persetujuan_permintaan_pembelian_history.order_permintaan_pembelian_id');

		// $this->db->where('persetujuan_permintaan_pembelian_history.tipe_permintaan',1);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_permintaan_pembelian_history.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian_history.`status` <= 3');
		$this->db->group_by('persetujuan_permintaan_pembelian_history.user_level_id');
		$this->db->group_by('persetujuan_permintaan_pembelian_history.order_permintaan_pembelian_id');

		// $this->db->where('persetujuan_permintaan_pembelian_history.tipe_permintaan',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_permintaan_pembelian_history.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian_history.`status` <= 3');
		$this->db->group_by('persetujuan_permintaan_pembelian_history.user_level_id');
		$this->db->group_by('persetujuan_permintaan_pembelian_history.order_permintaan_pembelian_id');
		// $this->db->where('persetujuan_permintaan_pembelian_history.tipe_permintaan',1);

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
	
}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/daftar_permintaan_po_m.php */