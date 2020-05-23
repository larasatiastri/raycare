<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_permintaan_po_m extends MY_Model {

	protected $_table        = 'order_permintaan_barang';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'order_permintaan_barang.id'         => 'id', 
		'order_permintaan_barang.tanggal'    => 'tanggal', 
		'user.nama'                             => 'user', 
		'user_level.nama'                       => 'user_level',
		'order_permintaan_barang.subjek'     => 'subjek',
		'order_permintaan_barang.keterangan' => 'keterangan',
		'order_permintaan_barang.is_active'  => 'is_active',
		'order_permintaan_barang.is_finish'  => 'is_finish',
		'(SELECT COUNT(*) FROM order_permintaan_barang_detail WHERE order_permintaan_barang_detail.order_permintaan_barang_id = 2)'	=> 'jumlah'
	);

	protected $datatable_columns_proses = array(
		//column di table  => alias
		'order_permintaan_barang.id'         => 'id', 
		'order_permintaan_barang.tanggal'    => 'tanggal', 
		'user.nama'                             => 'user', 
		'user_level.nama'                       => 'user_level',
		'order_permintaan_barang.subjek'     => 'subjek',
		'order_permintaan_barang.keterangan' => 'keterangan',
		'order_permintaan_barang.is_active'  => 'is_active',
		'order_permintaan_barang.is_finish'  => 'is_finish',
		'(SELECT COUNT(*) FROM order_permintaan_barang_detail WHERE order_permintaan_barang_detail.order_permintaan_barang_id = order_permintaan_barang.id)'	=> 'jumlah_terdaftar',
		'(SELECT COUNT(*) FROM order_permintaan_barang_detail_other WHERE order_permintaan_barang_detail_other.order_permintaan_barang_id = order_permintaan_barang.id)'	=> 'jumlah_tidak_terdaftar',
		'MAX(persetujuan_permintaan_pembelian.status)' => 'status_terakhir'
		);

	protected $datatable_columns_item = array(
		//column di table  => alias
		'order_permintaan_barang.id'                  => 'id', 
		'order_permintaan_barang_detail_other.id'     => 'id_detail', 
		'order_permintaan_barang_detail_other.nama'   => 'nama', 
		'order_permintaan_barang_detail_other.jumlah' => 'jumlah', 
		'order_permintaan_barang_detail_other.satuan' => 'satuan', 
		
	);

	protected $datatable_columns_permintaan = array(
		//column di table  => alias
		'link_pembelian_d_ke_permintaan_d.id'        => 'id',
		'order_permintaan_barang.tanggal'         => 'tanggal', 
		'order_permintaan_barang.subjek'          => 'subjek', 
		'order_permintaan_barang.keterangan'      => 'keterangan', 
		'user.nama'                                  => 'user', 
		'user_level.nama'                            => 'user_level', 
		'link_pembelian_d_ke_permintaan_d.jumlah'    => 'jumlah', 
		'item_satuan.nama'                           => 'satuan', 
		'link_pembelian_d_ke_permintaan_d.is_active' => 'is_active'
	);

	protected $datatable_columns_link_permintaan = array(
		//column di table  => alias
		'order_permintaan_barang.id'            => 'id', 
		'order_permintaan_barang.tanggal'       => 'tanggal', 
		'user.nama'                                => 'user', 
		'user_level.nama'                          => 'user_level',
		'order_permintaan_barang.subjek'        => 'subjek',
		'order_permintaan_barang.keterangan'    => 'keterangan',
		'order_permintaan_barang.is_active'     => 'is_active',
		'order_permintaan_barang.is_finish'     => 'is_finish',
		'item.kode'                                => 'kode',
		'item.nama'                                => 'nama_item',
		'item_satuan.nama'                         => 'nama_satuan',
		'order_permintaan_barang_detail.jumlah' => 'jumlah_item',
		'order_permintaan_barang_detail.id'     => 'id_detail'
	);

	protected $datatable_columns_link_permintaan_tidak_terdaftar = array(
		//column di table  => alias
		'order_permintaan_barang.id'                  => 'id', 
		'order_permintaan_barang.tanggal'             => 'tanggal', 
		'user.nama'                                      => 'user', 
		'user_level.nama'                                => 'user_level', 
		'order_permintaan_barang.subjek'              => 'subjek', 
		'order_permintaan_barang.keterangan'          => 'keterangan', 
		'order_permintaan_barang.is_active'           => 'is_active', 
		'order_permintaan_barang.is_finish'           => 'is_finish',
		'order_permintaan_barang_detail_other.id'     => 'id_detail', 
		'order_permintaan_barang_detail_other.nama'   => 'nama', 
		'order_permintaan_barang_detail_other.jumlah' => 'jumlah', 
		'order_permintaan_barang_detail_other.satuan' => 'satuan'
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
		$datatable_columns = array(
		//column di table  => alias
			'order_permintaan_barang.id'                                => 'id', 
			'order_permintaan_barang.tanggal'                           => 'tanggal', 
			'user.nama'                                                 => 'user', 
			'user_level.nama'                                           => 'user_level',
			'order_permintaan_barang.subjek'                            => 'subjek',
			'order_permintaan_barang.keterangan'                        => 'keterangan',
			'order_permintaan_barang.tipe'                              => 'tipe',
			'order_permintaan_barang.is_active'                         => 'is_active',
			'order_permintaan_barang.is_finish'                         => 'is_finish',
			'order_permintaan_barang.status'                            => 'status',
			'order_permintaan_barang_detail.order_permintaan_barang_id' => 'order_permintaan_barang_id',
			'order_permintaan_barang_detail.item_id'                    => 'item_id',
			'order_permintaan_barang_detail.item_satuan_id'             => 'item_satuan_id',
			'(SELECT COUNT(DISTINCT item_id) FROM order_permintaan_barang_detail WHERE order_permintaan_barang_detail.order_permintaan_barang_id = order_permintaan_barang.id)'				=> 'jumlah_terdaftar',
			'(SELECT COUNT(DISTINCT nama) FROM order_permintaan_barang_detail_other WHERE order_permintaan_barang_detail_other.order_permintaan_barang_id = order_permintaan_barang.id)'	=> 'jumlah_tidak_terdaftar',
			'order_permintaan_barang_detail_other.order_permintaan_barang_id' => 'order_permintaan_barang_id_other',
			'order_permintaan_barang_detail_other.nama'                       => 'nama_item_other',
			'order_permintaan_barang_detail_other.satuan'                     => 'nama_satuan_other',
			'order_permintaan_barang_detail_other.jumlah'                     => 'jumlah_other'
		);

		$join1 = array('user', $this->_table.'.user_id = user.id', 'LEFT');
		// $join2 = array('persetujuan_permintaan_barang', $this->_table.'.id = persetujuan_permintaan_barang.order_permintaan_barang_id');
		$join3 = array('user_level', $this->_table.'.user_level_id = user_level.id', 'LEFT');
		$join4 = array('order_permintaan_barang_detail', $this->_table.'.id = order_permintaan_barang_detail.order_permintaan_barang_id', 'LEFT');
		$join5 = array('order_permintaan_barang_detail_other', $this->_table.'.id = order_permintaan_barang_detail_other.order_permintaan_barang_id', 'LEFT');
		$join_tables = array($join1, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns);
		$params['sort_by'] = $this->_table.'.id';
		$params['sort_dir'] = 'DESC';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_finish',1);
		$this->db->group_by($this->_table.'.id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		// $this->db->where($wheres);
		$this->db->where($this->_table.'.is_finish',1);
		$this->db->group_by($this->_table.'.id');

		// $this->db->where('persetujuan_permintaan_barang.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_barang.order = (SELECT MAX(persetujuan_permintaan_barang.order) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.order_permintaan_barang_id = order_permintaan_barang.id)');
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();
		//$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		// $this->db->where($wheres);
		$this->db->where($this->_table.'.is_finish',1);
		$this->db->group_by($this->_table.'.id');

		// $this->db->where('persetujuan_permintaan_barang.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_barang.order = (SELECT MAX(persetujuan_permintaan_barang.order) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.order_permintaan_barang_id = order_permintaan_barang.id)');	

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_proses($status)
	{	
		$datatable_columns = array(
		//column di table  => alias
			'order_permintaan_barang.id'                                => 'id', 
			'order_permintaan_barang.tanggal'                           => 'tanggal', 
			'user.nama'                                                 => 'user', 
			'user_level.nama'                                           => 'user_level',
			'order_permintaan_barang.subjek'                            => 'subjek',
			'order_permintaan_barang.keterangan'                        => 'keterangan',
			'order_permintaan_barang.tipe'                              => 'tipe',
			'order_permintaan_barang.is_active'                         => 'is_active',
			'order_permintaan_barang.is_finish'                         => 'is_finish',
			'order_permintaan_barang.status'                            => 'status_terakhir',
			'order_permintaan_barang_detail.order_permintaan_barang_id' => 'order_permintaan_barang_id',
			'order_permintaan_barang_detail.item_id'                    => 'item_id',
			'order_permintaan_barang_detail.item_satuan_id'             => 'item_satuan_id',
			'(SELECT COUNT(DISTINCT item_id) FROM order_permintaan_barang_detail WHERE order_permintaan_barang_detail.order_permintaan_barang_id = order_permintaan_barang.id)'				=> 'jumlah_terdaftar',
			'(SELECT COUNT(DISTINCT nama) FROM order_permintaan_barang_detail_other WHERE order_permintaan_barang_detail_other.order_permintaan_barang_id = order_permintaan_barang.id)'	=> 'jumlah_tidak_terdaftar',
			'order_permintaan_barang_detail_other.order_permintaan_barang_id' => 'order_permintaan_barang_id_other',
			'order_permintaan_barang_detail_other.nama'                       => 'nama_item_other',
			'order_permintaan_barang_detail_other.satuan'                     => 'nama_satuan_other',
			'order_permintaan_barang_detail_other.jumlah'                     => 'jumlah_other'
		);

		$join1 = array('user', $this->_table.'.user_id = user.id', 'LEFT');
		$join3 = array('user_level', $this->_table.'.user_level_id = user_level.id', 'LEFT');
		$join4 = array('order_permintaan_barang_detail', $this->_table.'.id = order_permintaan_barang_detail.order_permintaan_barang_id', 'LEFT');
		$join5 = array('order_permintaan_barang_detail_other', $this->_table.'.id = order_permintaan_barang_detail_other.order_permintaan_barang_id', 'LEFT');
		$join_tables = array($join1, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($datatable_columns);
		$params['sort_by'] = $this->_table.'.id';
		$params['sort_dir'] = 'DESC';

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_finish',0);
		$this->db->where_in($this->_table.'.status',$status);
		$this->db->group_by($this->_table.'.id');
		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
        $total_records = $query->num_rows();
		//$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_finish',0);
		$this->db->where_in($this->_table.'.status',$status);
		$this->db->group_by($this->_table.'.id');

		// $this->db->where('persetujuan_permintaan_barang.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_barang.order = (SELECT MAX(persetujuan_permintaan_barang.order) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.order_permintaan_barang_id = order_permintaan_barang.id)');
		// dapatkan total record filtered/search;
		$query = $this->db->select('(1)')->get();
        $total_display_records = $query->num_rows();
		//$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_finish',0);
		$this->db->where_in($this->_table.'.status',$status);
		$this->db->group_by($this->_table.'.id');

		// $this->db->where('persetujuan_permintaan_barang.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_barang.order = (SELECT MAX(persetujuan_permintaan_barang.order) FROM persetujuan_permintaan_barang WHERE persetujuan_permintaan_barang.order_permintaan_barang_id = order_permintaan_barang.id)');	

		// tentukan kolom yang mau diselect
		foreach ($datatable_columns as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $datatable_columns;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_item($id)
	{	
		$join1 = array('order_permintaan_barang_detail_other', $this->_table.'.id = order_permintaan_barang_detail_other.order_permintaan_barang_id');
		$join_tables = array($join1);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_item);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('order_permintaan_barang_detail_other.order_permintaan_barang_id', $id);
		$this->db->where('is_active',1);
		$this->db->group_by('order_permintaan_barang_detail_other.nama');
		
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('order_permintaan_barang_detail_other.order_permintaan_barang_id', $id);
		$this->db->where('is_active',1);
		$this->db->group_by('order_permintaan_barang_detail_other.nama');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('order_permintaan_barang_detail_other.order_permintaan_barang_id', $id);
		$this->db->where('is_active',1);
		$this->db->group_by('order_permintaan_barang_detail_other.nama');

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_item as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_item;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_permintaan($item_id)
	{	
		
		$join1 = array('user', $this->_table.'.user_id = user.id');
		$join2 = array('user_level', $this->_table.'.user_level_id = user_level.id');
		$join3 = array('order_permintaan_barang_detail', $this->_table.'.id = order_permintaan_barang_detail.order_permintaan_barang_id');
		// $join4 = array('order_permintaan_barang_detail_other', $this->_table.'.id = order_permintaan_barang_detail_other.order_permintaan_barang_id');
		$join5 = array('link_pembelian_d_ke_permintaan_d', 'order_permintaan_barang_detail.id = link_pembelian_d_ke_permintaan_d.order_permintaan_barang_detail_id');
		$join8 = array('item_satuan', 'link_pembelian_d_ke_permintaan_d.satuan_id = item_satuan.id');
		$join_tables = array($join1, $join2, $join3, $join5, $join8);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_permintaan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where('link_pembelian_d_ke_permintaan_d.is_active',1);
		$this->db->where('order_permintaan_barang_detail.item_id', $item_id);
		$this->db->where('link_pembelian_d_ke_permintaan_d.tipe_pembelian',2);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where('link_pembelian_d_ke_permintaan_d.is_active',1);
		$this->db->where('order_permintaan_barang_detail.item_id', $item_id);
		$this->db->where('link_pembelian_d_ke_permintaan_d.tipe_pembelian',2);


		// $this->db->where('persetujuan_permintaan_pembelian.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_pembelian.order = (SELECT MAX(persetujuan_permintaan_pembelian.order) FROM persetujuan_permintaan_pembelian WHERE persetujuan_permintaan_pembelian.order_permintaan_barang_id = order_permintaan_barang.id)');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where('link_pembelian_d_ke_permintaan_d.is_active',1);
		$this->db->where('order_permintaan_barang_detail.item_id', $item_id);
		$this->db->where('link_pembelian_d_ke_permintaan_d.tipe_pembelian',2);
		

		// $this->db->where('persetujuan_permintaan_pembelian.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_pembelian.order = (SELECT MAX(persetujuan_permintaan_pembelian.order) FROM persetujuan_permintaan_pembelian WHERE persetujuan_permintaan_pembelian.order_permintaan_barang_id = order_permintaan_barang.id)');	

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_permintaan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_permintaan;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_link_permintaan($item_id)
	{	

		$join1 = array('user', $this->_table.'.user_id = user.id');
		$join2 = array('user_level', $this->_table.'.user_level_id = user_level.id');
		$join3 = array('order_permintaan_barang_detail', $this->_table.'.id = order_permintaan_barang_detail.order_permintaan_barang_id');
 		$join5 = array('item', 'order_permintaan_barang_detail.item_id = item.id');
		$join6 = array('item_satuan', 'order_permintaan_barang_detail.item_satuan_id = item_satuan.id');
		$join_tables = array($join1, $join2, $join3, $join5, $join6);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_link_permintaan);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_finish',1);
		$this->db->where('order_permintaan_barang_detail.item_id', $item_id);
		$this->db->group_by($this->_table.'.id');
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where('order_permintaan_barang_detail.item_id', $item_id);

		$this->db->where($this->_table.'.is_finish',1);
		$this->db->group_by($this->_table.'.id');


		// $this->db->where('persetujuan_permintaan_pembelian.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_pembelian.order = (SELECT MAX(persetujuan_permintaan_pembelian.order) FROM persetujuan_permintaan_pembelian WHERE persetujuan_permintaan_pembelian.order_permintaan_barang_id = order_permintaan_barang.id)');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_finish',1);
		$this->db->where('order_permintaan_barang_detail.item_id', $item_id);
		$this->db->group_by($this->_table.'.id');

		// $this->db->where('persetujuan_permintaan_pembelian.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_pembelian.order = (SELECT MAX(persetujuan_permintaan_pembelian.order) FROM persetujuan_permintaan_pembelian WHERE persetujuan_permintaan_pembelian.order_permintaan_barang_id = order_permintaan_barang.id)');	

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_link_permintaan as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_link_permintaan;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_datatable_link_permintaan_tidak_terdaftar()
	{	

		$join1 = array('user', $this->_table.'.user_id = user.id');
		$join2 = array('user_level', $this->_table.'.user_level_id = user_level.id');
		$join3 = array('order_permintaan_barang_detail_other', $this->_table.'.id = order_permintaan_barang_detail_other.order_permintaan_barang_id');
		$join_tables = array($join1, $join2, $join3);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns_link_permintaan_tidak_terdaftar);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_finish',1);
		// dapatkan total row count;
		$total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where($this->_table.'.is_active',1);

		$this->db->where($this->_table.'.is_finish',1);


		// $this->db->where('persetujuan_permintaan_pembelian.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_pembelian.order = (SELECT MAX(persetujuan_permintaan_pembelian.order) FROM persetujuan_permintaan_pembelian WHERE persetujuan_permintaan_pembelian.order_permintaan_barang_id = order_permintaan_barang.id)');
		// dapatkan total record filtered/search;
		$total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where($this->_table.'.is_active',1);
		$this->db->where($this->_table.'.is_finish',1);

		// $this->db->where('persetujuan_permintaan_pembelian.status', $status);
		// $this->db->group_by($this->_table.'.id');$this->db->where('persetujuan_permintaan_pembelian.order = (SELECT MAX(persetujuan_permintaan_pembelian.order) FROM persetujuan_permintaan_pembelian WHERE persetujuan_permintaan_pembelian.order_permintaan_barang_id = order_permintaan_barang.id)');	

		// tentukan kolom yang mau diselect
		foreach ($this->datatable_columns_link_permintaan_tidak_terdaftar as $col => $alias)
		{
			// $cols_select[] = $col;
			$this->db->select($col . ' AS ' . $alias);
		}
		
		//return  result object
		$result = new stdClass();

		$result->columns               = $this->datatable_columns_link_permintaan_tidak_terdaftar;
		$result->total_records         = $total_records;
		$result->total_display_records = $total_display_records;
		$result->records               = $this->db->get(); 
		// die(dump($result->records));
		return $result; 
	}

	public function get_data_link_permintaan($item_id)
	{
		$format = "SELECT link_pembelian_d_ke_permintaan_d.id AS id, 
						order_permintaan_barang.tanggal AS tanggal, 
						order_permintaan_barang.subjek AS subjek, 
						order_permintaan_barang.keterangan AS keterangan,
						order_permintaan_barang_detail.id as id_detail, 
						user.nama AS user, 
						user_level.nama AS user_level, 
						link_pembelian_d_ke_permintaan_d.jumlah AS jumlah, 
						item_satuan.nama AS satuan, 
						link_pembelian_d_ke_permintaan_d.is_active AS is_active
					FROM order_permintaan_barang
					JOIN user ON order_permintaan_barang.user_id = user.id
					JOIN user_level ON order_permintaan_barang.user_level_id = user_level.id
					JOIN order_permintaan_barang_detail ON order_permintaan_barang.id = order_permintaan_barang_detail.order_permintaan_barang_id
					JOIN link_pembelian_d_ke_permintaan_d ON order_permintaan_barang_detail.id = link_pembelian_d_ke_permintaan_d.order_permintaan_barang_detail_id
					JOIN item_satuan ON link_pembelian_d_ke_permintaan_d.satuan_id = item_satuan.id
					WHERE link_pembelian_d_ke_permintaan_d.is_active =  1
					AND order_permintaan_barang_detail.item_id =  $item_id
					AND link_pembelian_d_ke_permintaan_d.tipe_pembelian =  2
					ORDER BY order_permintaan_barang.id asc
					LIMIT 10";

		return $this->db->query($format);
	}

	public function get_data_order_permintaan($item_id, $id)
	{
		$format = "SELECT order_permintaan_barang.id AS id, 
							order_permintaan_barang.tanggal AS tanggal, 
							user.nama AS user, 
							user_level.nama AS user_level, 
							order_permintaan_barang.subjek AS subjek, 
							order_permintaan_barang.keterangan AS keterangan, 
							order_permintaan_barang.is_active AS is_active, 
							order_permintaan_barang.is_finish AS is_finish, 
							item.kode AS kode, 
							item.nama AS nama_item, 
							item_satuan.nama AS nama_satuan, 
							SUM(order_permintaan_barang_detail.jumlah) AS jumlah_item
					FROM order_permintaan_barang
					JOIN user ON order_permintaan_barang.user_id = user.id
					JOIN user_level ON order_permintaan_barang.user_level_id = user_level.id
					JOIN order_permintaan_barang_detail ON order_permintaan_barang.id = order_permintaan_barang_detail.order_permintaan_barang_id
					JOIN item ON order_permintaan_barang_detail.item_id = item.id
					JOIN item_satuan ON order_permintaan_barang_detail.item_satuan_id = item_satuan.id
					WHERE order_permintaan_barang.is_active =  1
					AND order_permintaan_barang.is_finish =  1
					AND order_permintaan_barang_detail.item_id =  $item_id
					AND order_permintaan_barang.id = $id
					GROUP BY order_permintaan_barang.id, order_permintaan_barang_detail.item_satuan_id
					ORDER BY order_permintaan_barang.id asc
					LIMIT 10";

		return $this->db->query($format);
	}

	public function get_data_order_permintaan_other()
	{
		$format = "SELECT order_permintaan_barang.id AS id, 
							order_permintaan_barang.tanggal AS tanggal, 
							user.nama AS user, 
							user_level.nama AS user_level, 
							order_permintaan_barang.subjek AS subjek, 
							order_permintaan_barang.keterangan AS keterangan, 
							order_permintaan_barang.is_active AS is_active, 
							order_permintaan_barang.is_finish AS is_finish, 
							order_permintaan_barang_detail_other.id AS id_detail, 
							order_permintaan_barang_detail_other.nama AS nama, 
							order_permintaan_barang_detail_other.jumlah AS jumlah, 
							order_permintaan_barang_detail_other.satuan AS satuan
					FROM order_permintaan_barang
					JOIN user ON order_permintaan_barang.user_id = user.id
					JOIN user_level ON order_permintaan_barang.user_level_id = user_level.id
					JOIN order_permintaan_barang_detail_other ON order_permintaan_barang.id = order_permintaan_barang_detail_other.order_permintaan_barang_id
					WHERE order_permintaan_barang.is_active =  1
					AND order_permintaan_barang.is_finish =  1
					GROUP BY order_permintaan_barang.id
					ORDER BY order_permintaan_barang.id asc
					LIMIT 10";

		return $this->db->query($format);
	}
}

/* End of file cabang_m.php */
/* Location: ./application/models/pembelian/daftar_permintaan_po_m.php */