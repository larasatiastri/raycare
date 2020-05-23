<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class O_s_pmsn_po_det_m extends MY_Model {

	protected $_table        = 'o_s_pmsn_po_det';
	protected $_order_by     = 'id';
	protected $_timestamps   = true;

	// Array of database columns which should be read and sent back to DataTables
	protected $datatable_columns = array(
		//column di table  => alias
		'o_s_pmsn_po_det.id'           		=> 'id',
		'item.nama'						=> 'nama_item',
		'user.nama'						=> 'nama_user',
		'order_permintaan_barang.tanggal'						=> 'tanggal_permintaan',
		'SUM(o_s_pmsn_po_det.jumlah)'      	=> 'jumlah',
		'item_satuan.nama'				=> 'nama_item_satuan',
		'o_s_pmsn_po_det.item_id' 				=> 'item_id',
		'o_s_pmsn_po_det.item_satuan_id' 		=> 'item_satuan_id',
		'o_s_pmsn_po_det.pemesanan_detail_id' 	=> 'pemesanan_detail_id',
		'o_s_pmsn_po_det.status'       		=> 'status'
	);

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable($tipe)
	{	

		if ($tipe == 1) {
			$tipe = array('1');
		}

		if ($tipe == 3) {
			$tipe = array('2','3');
		}
		$join1 = array('order_permintaan_barang', $this->_table.'.pemesanan_detail_id = order_permintaan_barang.id','left');
		$join2 = array('item', $this->_table.'.item_id = item.id','left');
		$join3 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id','left');
		$join4 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id','left');
		$join5 = array('user', 'order_permintaan_barang.created_by = user.id','left');

		$join_tables = array($join1, $join2, $join3,$join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->where($this->_table.'.status', 1);
		$this->db->or_where($this->_table.'.status', 3);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->group_by($this->_table.'.item_id');
		$this->db->group_by($this->_table.'.item_satuan_id');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->where($this->_table.'.status', 1);
		$this->db->or_where($this->_table.'.status', 3);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->group_by($this->_table.'.item_id');
		$this->db->group_by($this->_table.'.item_satuan_id');
		// dapatkan total record filtered/search;

		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->where($this->_table.'.status', 1);
		$this->db->or_where($this->_table.'.status', 3);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->group_by($this->_table.'.item_id');
		$this->db->group_by($this->_table.'.item_satuan_id');

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

	public function get_datatable_tolak($tipe)
	{	

		if ($tipe == 1) {
			$tipe = array('1');
		}

		if ($tipe == 3) {
			$tipe = array('2','3');
		}
		$join1 = array('order_permintaan_barang', $this->_table.'.pemesanan_detail_id = order_permintaan_barang.id','left');
		$join2 = array('item', $this->_table.'.item_id = item.id','left');
		$join3 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id','left');
		$join4 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id','left');
		$join5 = array('user', 'order_permintaan_barang.created_by = user.id','left');

		$join_tables = array($join1, $join2, $join3,$join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->where($this->_table.'.status', 1);
		$this->db->or_where($this->_table.'.status', 3);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->group_by($this->_table.'.item_satuan_id');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->where($this->_table.'.status', 1);
		$this->db->or_where($this->_table.'.status', 3);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->group_by($this->_table.'.item_satuan_id');
		// dapatkan total record filtered/search;

		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->where($this->_table.'.status', 1);
		$this->db->or_where($this->_table.'.status', 3);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->group_by($this->_table.'.item_satuan_id');

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

	/**
	 * [get_datatable description]
	 * @return [type] [description]
	 */
	public function get_datatable_history($tipe)
	{	
		if ($tipe == 1) {
			$tipe = array('1');
		}

		if ($tipe == 3) {
			$tipe = array('2','3');
		}

		$join1 = array('order_permintaan_barang', $this->_table.'.pemesanan_detail_id = order_permintaan_barang.id','left');
		$join2 = array('item', $this->_table.'.item_id = item.id','left');
		$join3 = array('item_satuan', $this->_table.'.item_satuan_id = item_satuan.id','left');
		$join4 = array('item_sub_kategori', 'item.item_sub_kategori = item_sub_kategori.id','left');
		$join5 = array('user', 'order_permintaan_barang.created_by = user.id','left');

		$join_tables = array($join1, $join2, $join3, $join4, $join5);

		// get params dari input postnya datatable
		$params = $this->datatable_param($this->datatable_columns);

		// prepare buat total record tanpa filter dan limit
		// filter = false, limit= false 
		$this->datatable_prepare($join_tables, $params);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->where($this->_table.'.status', 2);
		$this->db->group_by($this->_table.'.item_id');
		$this->db->group_by($this->_table.'.item_satuan_id');

		// dapatkan total row count;
		$query = $this->db->select('(1)')->get();
		$total_records = $query->num_rows();
		// $total_records = $this->db->count_all_results();
		// die(dump($total_records));

		// prepare buat total record filtered/search, 
		// filter=true, limit=false
		$this->datatable_prepare($join_tables, $params, true);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->where($this->_table.'.status', 2);
		$this->db->group_by($this->_table.'.item_id');
		$this->db->group_by($this->_table.'.item_satuan_id');
		// dapatkan total record filtered/search;

		$query = $this->db->select('(1)')->get();
		$total_display_records = $query->num_rows();
		// $total_display_records = $this->db->count_all_results();

		// prepare buat data yg mau di display
		// filter=true, limit=true
		$this->datatable_prepare($join_tables, $params, true, true);
		$this->db->where_in('item_sub_kategori.tipe', $tipe);
		$this->db->where($this->_table.'.status', 2);
		$this->db->group_by($this->_table.'.item_id');
		$this->db->group_by($this->_table.'.item_satuan_id');

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

	public function get_max_id_os_po()
	{
		$format = "SELECT MAX(SUBSTRING(`id`,14,4)) AS max_id FROM `o_s_pmsn_po_det` WHERE SUBSTR(`id`,6,7) = DATE_FORMAT(NOW(), '%m-%Y');";	
		return $this->db->query($format);
	}

	public function get_detail_os($item_id, $item_satuan_id)
	{
		$format = "SELECT
						`user`.nama,
						date(order_permintaan_barang.tanggal) as tanggal,
						o_s_pmsn_po_det.jumlah
					FROM
						o_s_pmsn_po_det
					JOIN order_permintaan_barang ON o_s_pmsn_po_det.pemesanan_detail_id = order_permintaan_barang.id
					JOIN `user` ON order_permintaan_barang.user_id = USER .id
					WHERE
						o_s_pmsn_po_det.`status` = 1
					AND item_id = $item_id
					AND item_satuan_id = $item_satuan_id
					OR o_s_pmsn_po_det.`status` = 3
					AND item_id = $item_id
					AND item_satuan_id = $item_satuan_id";

		return $this->db->query($format);
	}

	public function get_detail_os_processed($item_id, $item_satuan_id)
	{
		$format = "SELECT
						`user`.nama,
						date(order_permintaan_barang.tanggal) as tanggal,
						o_s_pmsn_po_det.jumlah
					FROM
						o_s_pmsn_po_det
					JOIN order_permintaan_barang ON o_s_pmsn_po_det.pemesanan_detail_id = order_permintaan_barang.id
					JOIN `user` ON order_permintaan_barang.user_id = USER .id
					WHERE
						o_s_pmsn_po_det.`status` = 2
					AND item_id = $item_id
					AND item_satuan_id = $item_satuan_id";

		return $this->db->query($format);
	}
}

/* End of file o_s_pmsn_po_det_m.php */
/* Location: ./application/models/pembelian/o_s_pmsn_po_det_m.php */