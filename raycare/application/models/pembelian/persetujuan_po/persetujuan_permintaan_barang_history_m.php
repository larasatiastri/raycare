<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_permintaan_barang_history_m extends MY_Model {

	protected $_table        = 'persetujuan_permintaan_barang_history';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'persetujuan_permintaan_barang_history.id'   										=> 'id', 
		'persetujuan_permintaan_barang_history.order_permintaan_barang_id'    	 => 'order_permintaan_barang_id', 
		'persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id'  => 'order_permintaan_barang_detail_id', 
		'persetujuan_permintaan_barang_history.user_level_id'    					 => 'user_level_id', 
		'persetujuan_permintaan_barang_history.`order`'                            	 => 'p_p_p_order', 
		'persetujuan_permintaan_barang_history.`status`'    							 => 'status', 
		'persetujuan_permintaan_barang_history.tipe_permintaan'    					=> 'tipe_permintaan', 
		'user.nama'   															=> 'user', 
		'user_level.nama'   													=> 'user_level',
		'persetujuan_permintaan_barang_history.tanggal_baca'     					=> 'tanggal_baca',
		'persetujuan_permintaan_barang_history.dibaca_oleh' 							=> 'dibaca_oleh',
		// 'persetujuan_permintaan_barang_history.is_active'  							=> 'is_active',
		'persetujuan_permintaan_barang_history.tanggal_persetujuan'  				=> 'tanggal_persetujuan',
		'persetujuan_permintaan_barang_history.disetujui_oleh'  						=> 'disetujui_oleh',
		'persetujuan_permintaan_barang_history.jumlah_persetujuan'  					=> 'jumlah_persetujuan',
		'persetujuan_permintaan_barang_history.satuan_id'  							=> 'satuan_id',
		'order_permintaan_barang.id'  										=> 'id',
		'order_permintaan_barang.tanggal'  									=> 'tanggal',
		'order_permintaan_barang.subjek'  									=> 'subjek',
		'order_permintaan_barang.keterangan'  								=> 'keterangan',
		'order_permintaan_barang.tipe'  										=> 'tipe',
		'order_permintaan_barang_detail.item_id'  							=> 'item_id',
		'order_permintaan_barang_detail.item_satuan_id'  					=> 'item_satuan_id',
		'item.nama'  															=> 'nama_item',
		'item_satuan.nama'  													=> 'nama_item_satuan',
		'(SELECT COUNT(*) FROM order_permintaan_barang_detail WHERE order_permintaan_barang_detail.order_permintaan_barang_id = order_permintaan_barang.id)'	=> 'jumlah_terdaftar',
		'(SELECT COUNT(*) FROM order_permintaan_barang_detail_other WHERE order_permintaan_barang_detail_other.order_permintaan_barang_id = order_permintaan_barang.id)'	=> 'jumlah_tidak_terdaftar',
		// 'persetujuan_permintaan_barang.satuan_id'  							=> 'satuan_id',
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
		$join1 = array('order_permintaan_barang', $this->_table.'.order_permintaan_barang_id = order_permintaan_barang.id', 'LEFT');
		$join2 = array('order_permintaan_barang_detail', 'order_permintaan_barang.id = order_permintaan_barang_detail.order_permintaan_barang_id', 'LEFT');
		$join3 = array('item', 'order_permintaan_barang_detail.item_id = item.id', 'LEFT');
		$join4 = array('item_satuan','order_permintaan_barang_detail.item_satuan_id = item_satuan.id', 'LEFT');
		$join5 = array('user','order_permintaan_barang.user_id = user.id', 'LEFT');
		$join6 = array('user_level', 'user.user_level_id = user_level.id', 'LEFT');
		$join_tables = array($join1, $join2, $join3, $join4, $join5, $join6);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('persetujuan_permintaan_barang_history.user_level_id', $user_level_id);
		$this->db->group_by('persetujuan_permintaan_barang_history.user_level_id');
		$this->db->group_by('persetujuan_permintaan_barang_history.order_permintaan_barang_id');

		// $this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan',1);

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
	    $total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_permintaan_barang_history.user_level_id', $user_level_id);
		$this->db->group_by('persetujuan_permintaan_barang_history.user_level_id');
		$this->db->group_by('persetujuan_permintaan_barang_history.order_permintaan_barang_id');

		// $this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan',1);
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
	    $total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_permintaan_barang_history.user_level_id', $user_level_id);
		$this->db->group_by('persetujuan_permintaan_barang_history.user_level_id');
		$this->db->group_by('persetujuan_permintaan_barang_history.order_permintaan_barang_id');
		// $this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan',1);

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

	public function get_datatable_user_setujui($id, $user_level_id, $order_permintaan_barang_detail_id,$order)
	{	

		$datatable_user_setujui = array(

			'persetujuan_permintaan_barang_history.id' => 'id',
			'persetujuan_permintaan_barang_history.order_permintaan_barang_id'       => 'order_permintaan_barang_id',
			'persetujuan_permintaan_barang_history.disetujui_oleh'                   => 'user_id',
			'persetujuan_permintaan_barang_history.`order`'                          => 'order_ppp',
			'persetujuan_permintaan_barang_history.`status`'                         => 'status_ppp',
			'persetujuan_permintaan_barang_history.tanggal_baca'                     => 'tanggal_baca',
			'persetujuan_permintaan_barang_history.dibaca_oleh'                      => 'dibaca_oleh',
			'persetujuan_permintaan_barang_history.tanggal_persetujuan'              => 'tanggal_persetujuan',
			'persetujuan_permintaan_barang_history.jumlah_persetujuan'               => 'jumlah_persetujuan',
			'a.nama'                                                         => 'nama_user_disetujui_oleh',
			'b.nama'                                                         => 'nama_user_dibaca_oleh',
			'user.id'                                                        => 'user_id',
			'user.nama'                                                      => 'nama_user',
			'user_level.id'                                                  => 'user_level_id',
			'user_level.nama'                                                => 'nama_user_level',
			'order_permintaan_barang_detail.item_id'                         => 'item_id',
			'order_permintaan_barang_detail.item_satuan_id'                  => 'item_satuan_id'

		);

		$join1 = array('user a', $this->_table.'.disetujui_oleh = a.id', 'left');
		$join2 = array('user b', $this->_table.'.dibaca_oleh = b.id', 'left');
		$join3 = array('user', $this->_table.'.user_level_id = user.user_level_id', 'left');
		$join4 = array("user_level", 'user.user_level_id = user_level.id', 'left');
		$join5 = array("order_permintaan_barang_detail", 'persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id = order_permintaan_barang_detail.id', 'left');
		// $join2 = array('order_permintaan_barang_detail_other', 'order_permintaan_barang.id = order_permintaan_barang_detail_other.order_permintaan_barang_id');
		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_user_setujui);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_id', $id);
		$this->db->where('persetujuan_permintaan_barang_history.`order` <=', $order);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id', $order_permintaan_barang_detail_id);
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 1);
		$this->db->where('persetujuan_permintaan_barang_history.box_paket_id', null);
		$this->db->or_where('persetujuan_permintaan_barang_history.order_permintaan_barang_id', $id);
		$this->db->where('persetujuan_permintaan_barang_history.`order` >', $order);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id', $order_permintaan_barang_detail_id);
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 1);
		$this->db->where('persetujuan_permintaan_barang_history.box_paket_id', null);
		$this->db->group_by('persetujuan_permintaan_barang_history.user_level_id');
		// $this->db->where('order_permintaan_barang.is_active',1);
		// $this->db->where('tipe_permintaan',2);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_id', $id);
		$this->db->where('persetujuan_permintaan_barang_history.`order` <=', $order);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id', $order_permintaan_barang_detail_id);
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 1);
		$this->db->where('persetujuan_permintaan_barang_history.box_paket_id', null);
		$this->db->or_where('persetujuan_permintaan_barang_history.order_permintaan_barang_id', $id);
		$this->db->where('persetujuan_permintaan_barang_history.`order` >', $order);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id', $order_permintaan_barang_detail_id);
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 1);
		$this->db->where('persetujuan_permintaan_barang_history.box_paket_id', null);
		$this->db->group_by('persetujuan_permintaan_barang_history.user_level_id');
		// $this->db->where('order_permintaan_barang.is_active',1);
		// $this->db->where('tipe_permintaan',2);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_id', $id);
		$this->db->where('persetujuan_permintaan_barang_history.`order` <=', $order);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id', $order_permintaan_barang_detail_id);
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 1);
		$this->db->where('persetujuan_permintaan_barang_history.box_paket_id', null);
		$this->db->or_where('persetujuan_permintaan_barang_history.order_permintaan_barang_id', $id);
		$this->db->where('persetujuan_permintaan_barang_history.`order` >', $order);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id', $order_permintaan_barang_detail_id);
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 1);
		$this->db->where('persetujuan_permintaan_barang_history.box_paket_id', null);
		$this->db->group_by('persetujuan_permintaan_barang_history.user_level_id');
		// $this->db->where('order_permintaan_barang.is_active',1);
		// $this->db->where('tipe_permintaan',2);

		// tentukan kolom yang mau diselect
		foreach ($datatable_user_setujui as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_user_setujui;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_view_tidak_terdaftar($id, $user_level_id)
	{	

		$datatable_columns_view_item = array(
		
			'order_permintaan_barang_detail_other.id'                         => 'id',
			'order_permintaan_barang_detail_other.order_permintaan_barang_id' => 'order_permintaan_barang_id',
			'order_permintaan_barang_detail_other.is_selected'                => 'is_selected',
			'order_permintaan_barang_detail_other.satuan'                     => 'satuan',
			'order_permintaan_barang_detail_other.nama'                       => 'nama',
			'order_permintaan_barang_detail_other.jumlah'                     => 'jumlah',
			'order_permintaan_barang_detail_other.harga_ref'                  => 'harga_ref',
			'order_permintaan_barang_detail_other.supplier'                   => 'supplier',
			'order_permintaan_barang_detail_other.`status`'                   => 'status_ppp',
			'persetujuan_permintaan_barang_history.id'  => 'persetujuan_permintaan_barang_id',
			'persetujuan_permintaan_barang_history.tipe_permintaan'                   => 'tipe_permintaan',
			'persetujuan_permintaan_barang_history.user_level_id'                     => 'user_level_id',
			'persetujuan_permintaan_barang_history.`order`'                           => 'order_ppp',
			// 'persetujuan_permintaan_barang_history.`status`'                          => 'status_ppp',
			'persetujuan_permintaan_barang_history.tanggal_baca'                      => 'tanggal_baca',
			'persetujuan_permintaan_barang_history.dibaca_oleh'                       => 'dibaca_oleh',
			'persetujuan_permintaan_barang_history.satuan_id'                         => 'satuan_ppp',
			'persetujuan_permintaan_barang_history.jumlah_persetujuan'                => 'jumlah_persetujuan'

		);

		$join1 = array('order_permintaan_barang_detail_other','persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id = order_permintaan_barang_detail_other.id', 'LEFT');
		$join_tables = array($join1) ;

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_view_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('order_permintaan_barang_detail_other.order_permintaan_barang_id', $id);
		
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 2);
		$this->db->group_by('order_permintaan_barang_detail_other.id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('order_permintaan_barang_detail_other.order_permintaan_barang_id', $id);
		
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 2);
		$this->db->group_by('order_permintaan_barang_detail_other.id');
		

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('order_permintaan_barang_detail_other.order_permintaan_barang_id', $id);
		
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 2);
		$this->db->group_by('order_permintaan_barang_detail_other.id');

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
		return $result; 
	}
	public function get_datatable_view_terdaftar($id, $user_level_id)
	{	

		$datatable_columns_view_item = array(
		
			'order_permintaan_barang_detail.id'                         => 'id',
			'order_permintaan_barang_detail.order_permintaan_barang_id' => 'order_permintaan_barang_id',
			'order_permintaan_barang_detail.is_selected'                => 'is_selected',
			'item_a.kode'                                               => 'kode',
			'item_a.nama'                                               => 'nama_item',
			'satuan_a.nama'                                             => 'nama_satuan',
			'satuan_b.nama'                                             => 'nama_satuan_ppp',
			'order_permintaan_barang_detail.jumlah'                     => 'jumlah',
			'order_permintaan_barang_detail.harga_ref'                  => 'harga_ref',
			'supplier.nama'                                             => 'nama_supp',
			'supplier.kode'                                             => 'kode_supp',
			'order_permintaan_barang_detail.`status`'                   => 'status_ppp',
			'persetujuan_permintaan_barang_history.id'                  => 'persetujuan_permintaan_barang_history_id',
			'persetujuan_permintaan_barang_history.tipe_permintaan'     => 'tipe_permintaan',
			'persetujuan_permintaan_barang_history.user_level_id'       => 'user_level_id',
			'persetujuan_permintaan_barang_history.`order`'             => 'order_ppp',
			'persetujuan_permintaan_barang_history.tanggal_baca'        => 'tanggal_baca',
			'persetujuan_permintaan_barang_history.dibaca_oleh'         => 'dibaca_oleh',
			'persetujuan_permintaan_barang_history.satuan_id'           => 'satuan_ppp',
			'order_permintaan_barang_detail.jumlah_disetujui'  => 'jumlah_persetujuan'

		);

		$join1 = array('order_permintaan_barang_detail','persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id = order_permintaan_barang_detail.id', 'LEFT');
		$join2 = array('supplier','order_permintaan_barang_detail.supplier_id = supplier.id', 'LEFT');
		$join3 = array('item item_a','order_permintaan_barang_detail.item_id = item_a.id', 'LEFT');
		$join4 = array('item_satuan satuan_a','order_permintaan_barang_detail.item_satuan_id = satuan_a.id', 'LEFT');
		$join5 = array('item_satuan satuan_b','order_permintaan_barang_detail.item_satuan_disetujui_id = satuan_b.id', 'LEFT');
		$join_tables = array($join1, $join2, $join3, $join4, $join5) ;

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns_view_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('order_permintaan_barang_detail.order_permintaan_barang_id', $id);
		
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 1);
		$this->db->group_by('order_permintaan_barang_detail.id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('order_permintaan_barang_detail.order_permintaan_barang_id', $id);
		
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 1);
		$this->db->group_by('order_permintaan_barang_detail.id');
		

		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('order_permintaan_barang_detail.order_permintaan_barang_id', $id);
		
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 1);
		$this->db->group_by('order_permintaan_barang_detail.id');

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
		return $result; 
	}

	public function get_datatable_user_setujui2_view($id, $user_level_id, $order_permintaan_barang_detail_id)
	{	

		$datatable_user_setujui = array(

			'persetujuan_permintaan_barang_history.id'  => 'id',
			'persetujuan_permintaan_barang_history.order_permintaan_barang_id'		=> 'order_permintaan_barang_id',
			'persetujuan_permintaan_barang_history.disetujui_oleh'						=> 'user_id',
			'persetujuan_permintaan_barang_history.`order`'								=> 'order_ppp',
			'persetujuan_permintaan_barang_history.`status`'								=> 'status_ppp',
			'persetujuan_permintaan_barang_history.tanggal_baca'							=> 'tanggal_baca',
			'persetujuan_permintaan_barang_history.dibaca_oleh'							=> 'dibaca_oleh',
			'persetujuan_permintaan_barang_history.tanggal_persetujuan'					=> 'tanggal_persetujuan',
			'persetujuan_permintaan_barang_history.jumlah_persetujuan'					=> 'jumlah_persetujuan',
			'a.nama'																=> 'nama_user_disetujui_oleh',
			'b.nama'																=> 'nama_user_dibaca_oleh',
			
			'user_level.id'															=> 'user_level_id',
			'user_level.nama'			 											=> 'nama_user_level'

		);

		$join1 = array('user a', $this->_table.'.disetujui_oleh = a.id', 'left');
		$join2 = array('user b', $this->_table.'.dibaca_oleh = b.id', 'left');
		$join3 = array("user_level", 'persetujuan_permintaan_barang_history.user_level_id = user_level.id', 'left');
		// $join2 = array('order_permintaan_barang_detail_other', 'order_permintaan_barang.id = order_permintaan_barang_detail_other.order_permintaan_barang_id');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_user_setujui);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_id', $id);
		// $this->db->where('persetujuan_permintaan_barang_history.user_level_id <=', $user_level_id);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id', $order_permintaan_barang_detail_id);
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 2);
		// $this->db->where('order_permintaan_barang.is_active',1);
		// $this->db->where('tipe_permintaan',2);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_id', $id);
		// $this->db->where('persetujuan_permintaan_barang_history.user_level_id <=', $user_level_id);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id', $order_permintaan_barang_detail_id);
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 2);
		// $this->db->where('order_permintaan_barang.is_active',1);
		// $this->db->where('tipe_permintaan',2);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_id', $id);
		// $this->db->where('persetujuan_permintaan_barang_history.user_level_id <=', $user_level_id);
		$this->db->where('persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id', $order_permintaan_barang_detail_id);
		$this->db->where('persetujuan_permintaan_barang_history.tipe_permintaan', 2);
		// $this->db->where('order_permintaan_barang.is_active',1);
		// $this->db->where('tipe_permintaan',2);

		// tentukan kolom yang mau diselect
		foreach ($datatable_user_setujui as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_user_setujui;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_data_order($id, $order_permintaan_barang_id, $user_level_id)
	{

		$format = "SELECT
					persetujuan_permintaan_barang_history.id,
					persetujuan_permintaan_barang_history.order_permintaan_barang_id,
					persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id,
					persetujuan_permintaan_barang_history.box_paket_id,
					persetujuan_permintaan_barang_history.tipe_permintaan,
					persetujuan_permintaan_barang_history.user_level_id,
					persetujuan_permintaan_barang_history.`order`,
					persetujuan_permintaan_barang_history.`status` AS status_view,
					persetujuan_permintaan_barang_history.tanggal_baca,
					persetujuan_permintaan_barang_history.dibaca_oleh,
					persetujuan_permintaan_barang_history.tanggal_persetujuan,
					persetujuan_permintaan_barang_history.disetujui_oleh,
					persetujuan_permintaan_barang_history.jumlah_persetujuan,
					persetujuan_permintaan_barang_history.satuan_id,
					persetujuan_permintaan_barang_history.keterangan,
					persetujuan_permintaan_barang_history.is_active,
					persetujuan_permintaan_barang_history.created_by,
					persetujuan_permintaan_barang_history.created_date,
					persetujuan_permintaan_barang_history.modified_by,
					persetujuan_permintaan_barang_history.modified_date
					FROM
					persetujuan_permintaan_barang_history
					WHERE
					persetujuan_permintaan_barang_history.order_permintaan_barang_detail_id = $id
					AND
					persetujuan_permintaan_barang_history.order_permintaan_barang_id = '$order_permintaan_barang_id' 
					AND
					persetujuan_permintaan_barang_history.user_level_id < $user_level_id";

		return $this->db->query($format);

	}

	
}

/* End of file cabang_m.php */
/* Location: ./application/models/barang/daftar_permintaan_po_m.php */