<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persetujuan_permintaan_pembelian_m extends MY_Model {

	protected $_table        = 'persetujuan_permintaan_pembelian';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'persetujuan_permintaan_pembelian.persetujuan_permintaan_pembelian_id'   => 'persetujuan_permintaan_pembelian_id', 
		'persetujuan_permintaan_pembelian.order_permintaan_pembelian_id'    	 => 'order_permintaan_pembelian_id', 
		'persetujuan_permintaan_pembelian.order_permintaan_pembelian_detail_id'  => 'order_permintaan_pembelian_detail_id', 
		'persetujuan_permintaan_pembelian.user_level_id'    					 => 'user_level_id', 
		'persetujuan_permintaan_pembelian.`order`'                            	 => 'p_p_p_order', 
		'persetujuan_permintaan_pembelian.`status`'    							 => 'status', 
		'persetujuan_permintaan_pembelian.tipe_permintaan'    					=> 'tipe_permintaan', 
		'user.nama'   															=> 'user', 
		'user_level.nama'   													=> 'user_level',
		'persetujuan_permintaan_pembelian.tanggal_baca'     					=> 'tanggal_baca',
		'persetujuan_permintaan_pembelian.dibaca_oleh' 							=> 'dibaca_oleh',
		'persetujuan_permintaan_pembelian.is_active'  							=> 'is_active',
		'persetujuan_permintaan_pembelian.tanggal_persetujuan'  				=> 'tanggal_persetujuan',
		'persetujuan_permintaan_pembelian.disetujui_oleh'  						=> 'disetujui_oleh',
		'persetujuan_permintaan_pembelian.jumlah_persetujuan'  					=> 'jumlah_persetujuan',
		'persetujuan_permintaan_pembelian.satuan_id'  							=> 'satuan_id',
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

	protected $datatable_columns_proses = array(
		//column di table  => alias
		'persetujuan_permintaan_pembelian.id'         => 'id', 
		'persetujuan_permintaan_pembelian.tanggal'   => 'tanggal', 
		'user.nama'   => 'user', 
		'user_level.nama'   => 'user_level',
		'persetujuan_permintaan_pembelian.subjek'     => 'subjek',
		'persetujuan_permintaan_pembelian.keterangan'     => 'keterangan',
		'persetujuan_permintaan_pembelian.is_active'     => 'is_active',
		'persetujuan_permintaan_pembelian.is_finish'     => 'is_finish',
		'(SELECT COUNT(*) FROM persetujuan_permintaan_pembelian_detail WHERE persetujuan_permintaan_pembelian_detail.persetujuan_permintaan_pembelian_id = persetujuan_permintaan_pembelian.id)'	=> 'jumlah_terdaftar',
		'(SELECT COUNT(*) FROM persetujuan_permintaan_pembelian_detail_other WHERE persetujuan_permintaan_pembelian_detail_other.persetujuan_permintaan_pembelian_id = persetujuan_permintaan_pembelian.id)'	=> 'jumlah_tidak_terdaftar',
		'MAX(persetujuan_permintaan_pembelian.status)' => 'status_terakhir'
		);

	protected $datatable_columns_item_terdaftar= array(
		//column di table  => alias
		'order_permintaan_pembelian_detail.id'     => 'id', 
		'order_permintaan_pembelian_detail.jumlah' => 'jumlah', 
		'item.kode'                                => 'kode', 
		'item.nama'                                => 'nama',
		'item_satuan.nama'                         => 'satuan',
		'order_permintaan_pembelian.is_active'	   => 'active'
		
	);

	protected $datatable_columns_item_tidak_terdaftar = array(
		//column di table  => alias
		'order_permintaan_pembelian.id'         => 'id', 
		'order_permintaan_pembelian_detail_other.id'   => 'id_order_permintaan_pembelian_detail_other', 
		'order_permintaan_pembelian_detail_other.nama'   => 'nama', 
		'order_permintaan_pembelian_detail_other.jumlah'   => 'jumlah', 
		
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
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.`status` <= 3');
		$this->db->group_by('persetujuan_permintaan_pembelian.user_level_id');
		$this->db->group_by('persetujuan_permintaan_pembelian.order_permintaan_pembelian_id');

		// $this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan',1);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.`status` <= 3');
		$this->db->group_by('persetujuan_permintaan_pembelian.user_level_id');
		$this->db->group_by('persetujuan_permintaan_pembelian.order_permintaan_pembelian_id');

		// $this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.`status` <= 3');
		$this->db->group_by('persetujuan_permintaan_pembelian.user_level_id');
		$this->db->group_by('persetujuan_permintaan_pembelian.order_permintaan_pembelian_id');
		// $this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan',1);

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
	
	public function get_datatable_item_terdaftar($id)
	{	
		$join1 = array('order_permintaan_pembelian', $this->_table.'.order_permintaan_pembelian_id = order_permintaan_pembelian.id');
		$join2 = array('order_permintaan_pembelian_detail', 'order_permintaan_pembelian.id = order_permintaan_pembelian_detail.order_permintaan_pembelian_id');
		$join3 = array('item', 'order_permintaan_pembelian_detail.item_id = item.id');
		$join4 = array('item_satuan','order_permintaan_pembelian_detail.item_satuan_id = item_satuan.id');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item_terdaftar);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		$this->db->where('order_permintaan_pembelian.is_active',1);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan',1);

		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		$this->db->where('order_permintaan_pembelian.is_active',1);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan',1);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('order_permintaan_pembelian_detail.order_permintaan_pembelian_id', $id);
		$this->db->where('order_permintaan_pembelian.is_active',1);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan',1);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item_terdaftar as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item_terdaftar;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_item_tidak_terdaftar($id)
	{	
		$join1 = array('order_permintaan_pembelian', $this->_table.'.order_permintaan_pembelian_id = order_permintaan_pembelian.id');
		$join2 = array('order_permintaan_pembelian_detail_other', 'order_permintaan_pembelian.id = order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id');
		$join_tables = array($join1, $join2);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item_tidak_terdaftar);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id', $id);
		$this->db->where('order_permintaan_pembelian.is_active',1);
		$this->db->where('tipe_permintaan',2);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id', $id);
		$this->db->where('order_permintaan_pembelian.is_active',1);
		$this->db->where('tipe_permintaan',2);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id', $id);
		$this->db->where('order_permintaan_pembelian.is_active',1);
		$this->db->where('tipe_permintaan',2);

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item_tidak_terdaftar as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item_tidak_terdaftar;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_user_setujui($id, $user_level_id)
	{	

		$datatable_user_setujui = array(

			'persetujuan_permintaan_pembelian.persetujuan_permintaan_pembelian_id'  => 'id',
			'persetujuan_permintaan_pembelian.order_permintaan_pembelian_id'		=> 'order_permintaan_pembelian_id',
			'persetujuan_permintaan_pembelian.disetujui_oleh'						=> 'user_id',
			'persetujuan_permintaan_pembelian.`order`'								=> 'order_ppp',
			'persetujuan_permintaan_pembelian.`status`'								=> 'status_ppp',
			'persetujuan_permintaan_pembelian.tanggal_baca'							=> 'tanggal_baca',
			'persetujuan_permintaan_pembelian.dibaca_oleh'							=> 'dibaca_oleh',
			'persetujuan_permintaan_pembelian.tanggal_persetujuan'					=> 'tanggal_persetujuan',
			'persetujuan_permintaan_pembelian.jumlah_persetujuan'					=> 'jumlah_persetujuan',
			'a.nama'																=> 'nama_user_disetujui_oleh',
			'b.nama'																=> 'nama_user_dibaca_oleh',
			'user.id'																=> 'user_id',
			'user.nama'																=> 'nama_user',
			'user_level.id'															=> 'user_level_id',
			'user_level.nama'			 											=> 'nama_user_level'

		);

		$join1 = array('user a', $this->_table.'.disetujui_oleh = a.id', 'left');
		$join2 = array('user b', $this->_table.'.dibaca_oleh = b.id', 'left');
		$join3 = array('user', $this->_table.'.user_level_id = user.id', 'left');
		$join4 = array("user_level", 'user.user_level_id = user_level.id', 'left');
		// $join2 = array('order_permintaan_pembelian_detail_other', 'order_permintaan_pembelian.id = order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_user_setujui);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('persetujuan_permintaan_pembelian.order_permintaan_pembelian_id', $id);
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 1);
		// $this->db->where('order_permintaan_pembelian.is_active',1);
		// $this->db->where('tipe_permintaan',2);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_permintaan_pembelian.order_permintaan_pembelian_id', $id);
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 1);
		// $this->db->where('order_permintaan_pembelian.is_active',1);
		// $this->db->where('tipe_permintaan',2);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_permintaan_pembelian.order_permintaan_pembelian_id', $id);
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 1);
		// $this->db->where('order_permintaan_pembelian.is_active',1);
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

	public function get_datatable_user_setujui2($id, $user_level_id)
	{	

		$datatable_user_setujui = array(

			'persetujuan_permintaan_pembelian.persetujuan_permintaan_pembelian_id'  => 'id',
			'persetujuan_permintaan_pembelian.order_permintaan_pembelian_id'		=> 'order_permintaan_pembelian_id',
			'persetujuan_permintaan_pembelian.disetujui_oleh'						=> 'user_id',
			'persetujuan_permintaan_pembelian.`order`'								=> 'order_ppp',
			'persetujuan_permintaan_pembelian.`status`'								=> 'status_ppp',
			'persetujuan_permintaan_pembelian.tanggal_baca'							=> 'tanggal_baca',
			'persetujuan_permintaan_pembelian.dibaca_oleh'							=> 'dibaca_oleh',
			'persetujuan_permintaan_pembelian.tanggal_persetujuan'					=> 'tanggal_persetujuan',
			'persetujuan_permintaan_pembelian.jumlah_persetujuan'					=> 'jumlah_persetujuan',
			'a.nama'																=> 'nama_user_disetujui_oleh',
			'b.nama'																=> 'nama_user_dibaca_oleh',
			'user.id'																=> 'user_id',
			'user.nama'																=> 'nama_user',
			'user_level.id'															=> 'user_level_id',
			'user_level.nama'			 											=> 'nama_user_level'

		);

		$join1 = array('user a', $this->_table.'.disetujui_oleh = a.id', 'left');
		$join2 = array('user b', $this->_table.'.dibaca_oleh = b.id', 'left');
		$join3 = array('user', $this->_table.'.user_level_id = user.id', 'left');
		$join4 = array("user_level", 'user.user_level_id = user_level.id', 'left');
		// $join2 = array('order_permintaan_pembelian_detail_other', 'order_permintaan_pembelian.id = order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id');
		$join_tables = array($join1, $join2, $join3, $join4);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_user_setujui);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('persetujuan_permintaan_pembelian.order_permintaan_pembelian_id', $id);
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 2);
		// $this->db->where('order_permintaan_pembelian.is_active',1);
		// $this->db->where('tipe_permintaan',2);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('persetujuan_permintaan_pembelian.order_permintaan_pembelian_id', $id);
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 2);
		// $this->db->where('order_permintaan_pembelian.is_active',1);
		// $this->db->where('tipe_permintaan',2);
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('persetujuan_permintaan_pembelian.order_permintaan_pembelian_id', $id);
		$this->db->where('persetujuan_permintaan_pembelian.user_level_id', $user_level_id);
		$this->db->where('persetujuan_permintaan_pembelian.tipe_permintaan', 2);
		// $this->db->where('order_permintaan_pembelian.is_active',1);
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

	public function get_max()
	{
		$sql = "select max(persetujuan_permintaan_pembelian_id) as max_id from persetujuan_permintaan_pembelian";
		return $this->db->query($sql);
	}

	public function update_status_p_p_p($data, $persetujuan_permintaan_pembelian_id, $user_id)
	{

		$this->db->where('order_permintaan_pembelian_id', $persetujuan_permintaan_pembelian_id);
		$this->db->where('user_level_id', $user_id);
		$this->db->update($this->_table, $data);

	}

	public function query_user_level_id($order_permintaan_pembelian_id)
	{

		$sql = "SELECT
					*
					FROM
					persetujuan_permintaan_pembelian
					WHERE
					persetujuan_permintaan_pembelian.order_permintaan_pembelian_id = $order_permintaan_pembelian_id
					GROUP BY
					persetujuan_permintaan_pembelian.user_level_id";
		return $this->db->query($sql);

	}

	public function data_disetujui($user_level_id, $order_permintaan_pembelian_id)
	{

		$sql = "SELECT
					disetujui_oleh,
					keterangan
					FROM
					persetujuan_permintaan_pembelian
					WHERE
					persetujuan_permintaan_pembelian.user_level_id = $user_level_id
					and
					persetujuan_permintaan_pembelian.order_permintaan_pembelian_id = $order_permintaan_pembelian_id
					";
		return $this->db->query($sql);
	}


	public function delete_id($id)
	{

		$sql = "DELETE FROM persetujuan_permintaan_pembelian
				WHERE persetujuan_permintaan_pembelian_id =  $id 
				LIMIT 1";
		
		return $this->db->query($sql);
	}

	public function data_order($order_permintaan_pembelian_id, $order_ppp)
	{

		$sql = "SELECT
				persetujuan_permintaan_pembelian.order_permintaan_pembelian_id,
				persetujuan_permintaan_pembelian.order_permintaan_pembelian_detail_id,
				persetujuan_permintaan_pembelian.persetujuan_permintaan_pembelian_id,
				persetujuan_permintaan_pembelian.tipe_permintaan,
				persetujuan_permintaan_pembelian.`order` AS `order`,
				persetujuan_permintaan_pembelian.user_level_id,
				persetujuan_permintaan_pembelian.`status` AS `status`
				FROM
				persetujuan_permintaan_pembelian
				WHERE
				persetujuan_permintaan_pembelian.order_permintaan_pembelian_id = $order_permintaan_pembelian_id
				AND
				persetujuan_permintaan_pembelian.`order` <= $order_ppp";

		return $this->db->query($sql);

	}

	public function data_order2($order_permintaan_pembelian_id, $order_ppp)
	{

		$sql = "SELECT
				persetujuan_permintaan_pembelian.order_permintaan_pembelian_id,
				persetujuan_permintaan_pembelian.order_permintaan_pembelian_detail_id,
				persetujuan_permintaan_pembelian.persetujuan_permintaan_pembelian_id,
				persetujuan_permintaan_pembelian.tipe_permintaan,
				persetujuan_permintaan_pembelian.`order` AS `order`,
				persetujuan_permintaan_pembelian.user_level_id,
				persetujuan_permintaan_pembelian.`status` AS `status`
				FROM
				persetujuan_permintaan_pembelian
				WHERE
				persetujuan_permintaan_pembelian.order_permintaan_pembelian_id = $order_permintaan_pembelian_id
				AND
				persetujuan_permintaan_pembelian.`order` < $order_ppp";

		return $this->db->query($sql);

	}	

	public function get_data_item_terdaftar($order_permintaan_pembelian_id, $user_level_id)
	{

		$sql = "SELECT
					persetujuan_permintaan_pembelian.persetujuan_permintaan_pembelian_id,
					persetujuan_permintaan_pembelian.order_permintaan_pembelian_id,
					persetujuan_permintaan_pembelian.tipe_permintaan,
					persetujuan_permintaan_pembelian.user_level_id,
					persetujuan_permintaan_pembelian.`status`,
					persetujuan_permintaan_pembelian.order_permintaan_pembelian_detail_id,
					persetujuan_permintaan_pembelian.satuan_id as satuan_id,
					persetujuan_permintaan_pembelian.jumlah_persetujuan AS jumlah_setujui,
					item.kode,
					item.nama,
					order_permintaan_pembelian_detail.jumlah,
					order_permintaan_pembelian_detail.item_id,
					a.nama AS nama_satuan_order,
					b.nama AS nama_satuan_persetujuan
					FROM
					persetujuan_permintaan_pembelian
					RIGHT JOIN order_permintaan_pembelian_detail ON persetujuan_permintaan_pembelian.order_permintaan_pembelian_detail_id = order_permintaan_pembelian_detail.id
					RIGHT JOIN order_permintaan_pembelian ON order_permintaan_pembelian_detail.order_permintaan_pembelian_id = order_permintaan_pembelian.id AND persetujuan_permintaan_pembelian.order_permintaan_pembelian_id = order_permintaan_pembelian.id
					LEFT JOIN item ON order_permintaan_pembelian_detail.item_id = item.id
					LEFT JOIN item_satuan AS a ON order_permintaan_pembelian_detail.item_satuan_id = a.id
					LEFT JOIN item_satuan AS b ON persetujuan_permintaan_pembelian.satuan_id = b.id
					WHERE
					persetujuan_permintaan_pembelian.order_permintaan_pembelian_id = $order_permintaan_pembelian_id 
					AND
					persetujuan_permintaan_pembelian.user_level_id = $user_level_id 
					AND
					persetujuan_permintaan_pembelian.tipe_permintaan = 1";

		return $this->db->query($sql);

	}

	public function get_data_item_tidak_terdaftar($order_permintaan_pembelian_id, $user_level_id)
	{

		$sql = "SELECT
					persetujuan_permintaan_pembelian.persetujuan_permintaan_pembelian_id,
					persetujuan_permintaan_pembelian.order_permintaan_pembelian_id,
					persetujuan_permintaan_pembelian.tipe_permintaan,
					persetujuan_permintaan_pembelian.user_level_id,
					persetujuan_permintaan_pembelian.`status`,
					persetujuan_permintaan_pembelian.order_permintaan_pembelian_detail_id,
					persetujuan_permintaan_pembelian.satuan_id AS satuan_id,
					persetujuan_permintaan_pembelian.jumlah_persetujuan AS jumlah_setujui,
					item_satuan.nama AS nama_satuan_persetujuan,
					order_permintaan_pembelian_detail_other.nama AS nama_item,
					order_permintaan_pembelian_detail_other.jumlah AS jumlah_item,
					order_permintaan_pembelian_detail_other.satuan AS satuan_item
					FROM
					persetujuan_permintaan_pembelian
					RIGHT JOIN order_permintaan_pembelian ON persetujuan_permintaan_pembelian.order_permintaan_pembelian_id = order_permintaan_pembelian.id
					LEFT JOIN item_satuan ON persetujuan_permintaan_pembelian.satuan_id = item_satuan.id
					LEFT JOIN order_permintaan_pembelian_detail_other ON persetujuan_permintaan_pembelian.order_permintaan_pembelian_detail_id = order_permintaan_pembelian_detail_other.id AND order_permintaan_pembelian_detail_other.order_permintaan_pembelian_id = order_permintaan_pembelian.id
					WHERE
						persetujuan_permintaan_pembelian.order_permintaan_pembelian_id = $order_permintaan_pembelian_id
					AND persetujuan_permintaan_pembelian.user_level_id = $user_level_id
					AND persetujuan_permintaan_pembelian.tipe_permintaan = 2";

		return $this->db->query($sql);

	}

	public function get_satuan($id)
	{
		$format = "SELECT id, nama FROM item_satuan WHERE item_satuan.item_id = '$id'";

		return $this->db->query($format);
	}

}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/daftar_permintaan_po_m.php */